<?php
error_reporting(0);
date_default_timezone_set("MST");
session_start();
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
$assocReturn = array("message" => "",
					 "leftoverCooldown" => "");
if ($mysqliConnection -> connect_errno) {
	$assocReturn["message"] = "An error occurred.";
} else {
	if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] === false) {
		$getUser = $mysqliConnection -> real_escape_string($_POST["username"]);
		$getPass = $mysqliConnection -> real_escape_string($_POST["password"]);
		if (empty(trim($getUser))) {
			$assocReturn["message"] = "Please fill all required fields.";
		}
		if (empty(trim($getPass))) {
			$assocReturn["message"] = "Please fill all required fields.";
		}
		if (empty($assocReturn["message"])) {
			$selectNeededDetailsQuery = "
			SELECT password, email, 2FAenabled, 2FAsentTime
			FROM accountdetails
			WHERE username = '{$getUser}'";
			if ($neededDetails = $mysqliConnection -> query($selectNeededDetailsQuery)) {
				if ($neededDetails -> num_rows > 0) {
					if ($assocNeededDetails = $neededDetails -> fetch_assoc()) {
						$dbEmail = $assocNeededDetails["email"];
						$dbPassword = $assocNeededDetails["password"];
						$db2FAenabled = $assocNeededDetails["2FAenabled"];
						$dbLastSentTime = $assocNeededDetails["2FAsentTime"];
						if (password_verify(base64_encode(hash("sha512", $getPass, true)), $dbPassword) === true) {
							if ($db2FAenabled == true) {
								if ((time() - 120) < strtotime($dbLastSentTime)) {
									$assocReturn["leftoverCooldown"] = strtotime($dbLastSentTime) + 120 - time();
									$assocReturn["message"] = "Please wait until the cooldown is over!";
								} else {
									$randomToken = str_pad(random_int(0, 999999), 6, "0", STR_PAD_LEFT);
									if (mail($dbEmail, "{$randomToken} is your account verification code", "{$randomToken} is your account login verification code. If this request was not made by you, please reset or change your password.", "From: <no_reply@streetor.sg>")) {
										$updateVerificationEmailTimeQuery = "
										UPDATE accountdetails
										SET 2FAsentTime = NOW(),
										2FAtoken = '{$randomToken}'
										WHERE username = '{$getUser}'";
										if ($queriedUpdateTimeQuery = $mysqliConnection -> query($updateVerificationEmailTimeQuery)) {
											$assocReturn["message"] = "Email sent!";
											$assocReturn["leftoverCooldown"] = 120;
										} else {
											$assocReturn["message"] = "An error occurred, but the email was sent.";
										}
									} else {
										$assocReturn["message"] = "An error occurred and the email was not sent.";
									}
								}
							} else {
								$assocReturn["message"] = "Please log in again.";
							}
						} else {
							$assocReturn["message"] = "Incorrect Password.";
						}
					} else {
						$assocReturn["message"] = "An error occurred.";
					}
				} else {
					$assocReturn["message"] = "No records were found in the database. This account may have been deleted.";
				}
				$neededDetails -> free();
			} else {
				$assocReturn["message"] = "An error occurred.";
			}
		}
	} else {
		$assocReturn["message"] = "Please <a href='https://streetor.sg/login/' style='color: #4486f4;'>log in</a> and try again.";
	}
}
$mysqliConnection -> close();
echo json_encode($assocReturn);
?>