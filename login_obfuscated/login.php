<?php
session_start();
date_default_timezone_set("MST");
$AssocReturn = array("errormessages" => array(
						"usernameError" => "",
						"passwordError" => "",
						"loginError" => "",
						"2FAError" => "",
					),
					"2FARequired" => null,
					"successURL" => "");
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength / 2));
}
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if ($mysqliConnection -> connect_errno) {
	$AssocReturn["errormessages"]["loginError"] = "A connection error occurred. Please try again later.";
} else {
	if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") {
		$getUser = $mysqliConnection -> real_escape_string($_POST["username"]);
		$getPass = $mysqliConnection -> real_escape_string($_POST["password"]);
		$remember = (int) $_POST["remember"] == true;
		if (empty(trim($getUser))) {
			$AssocReturn["errormessages"]["usernameError"] = "This field is required.";
		}
		if (empty(trim($getPass))) {
			$AssocReturn["errormessages"]["passwordError"] = "This field is required.";
		}
		if (empty($AssocReturn["errormessages"]["usernameError"]) &&
			empty($AssocReturn["errormessages"]["paswordError"]) &&
			empty($AssocReturn["errormessages"]["loginError"])) {
			$checkDetailsQuery = "SELECT accountID, username, password, email, emailVerified, 2FAenabled, biography, 2FAtoken, IF(2FAsentTime >= SUBDATE(NOW(), INTERVAL 10 MINUTE), 'Valid', 'Expired') AS 2FAtokenValid
			FROM accountdetails
			WHERE username = '{$getUser}'";
			if ($queriedUsers = $mysqliConnection -> query($checkDetailsQuery)) {
				if ($queriedUsers -> num_rows > 0) {
					if ($assocQueriedUsers = $queriedUsers -> fetch_assoc()) {
						$dbAccountID = $assocQueriedUsers["accountID"];
						$dbUsername = $assocQueriedUsers["username"];
						$dbPassword = $assocQueriedUsers["password"];
						$dbEmail = $assocQueriedUsers["email"];
						$db2FAenabled = $assocQueriedUsers["2FAenabled"];
						$db2FACode = $assocQueriedUsers["2FAtoken"];
						$db2FACodeValid = $assocQueriedUsers["2FAtokenValid"];
						if (password_verify(base64_encode(hash("sha512", $getPass, true)), $dbPassword) === true) {
							if ($db2FAenabled == true) {
								$AssocReturn["2FARequired"] = true;
								if (empty($db2FACode) || $db2FACodeValid == "Expired") {
									$randomToken = str_pad(random_int(0, 999999), 6, "0", STR_PAD_LEFT);
									$updateTokenHashQuery = "UPDATE accountdetails
									SET 2FAtoken = '{$randomToken}',
									2FAsentTime = NOW()
									WHERE accountID = '{$dbAccountID}'";
									if ($mysqliConnection -> query($updateTokenHashQuery)) {
										mail($dbEmail, "{$randomToken} is your account verification code", "{$randomToken} is your account login verification code. If this request was not made by you, please reset or change your password.", "From: no_reply@streetor.sg");
									} else {
										$AssocReturn["errormessages"]["2FAerror"] = "An error occurred.";
									}
								}
								else if (!empty($_POST["2FACode"]) && $db2FACode != (int)$_POST["2FACode"]) {
									$AssocReturn["errormessages"]["2FAError"] = "Incorrect Code.";
								}
								else if (!empty($_POST["2FACode"]) && $db2FACode == (int)$_POST["2FACode"]) {
									if ($remember === false) {
										$_SESSION["loggedIn"] = true;
										$_SESSION["userID"] = $dbAccountID;
										$AssocReturn["successURL"] = "https://streetor.sg";
									} else {
										$newRememberID = getRandomString(30);
										$getToken = getRandomString(50);
										$hashedToken = hash("sha512", $getToken);
										$updateDetailsQuery = "UPDATE accountdetails
										SET 2FAtoken = NULL
										WHERE accountID = '{$dbAccountID}';
										INSERT INTO remembereddevices(rememberID, tokenHash, accountID, 2FAverified, loggedInTime)
										VALUES ('{$newRememberID}', '{$hashedToken}', '{$dbAccountID}', 1, NOW());
										DELETE FROM remembereddevices
										WHERE loggedInTime <= SUBDATE(NOW(), INTERVAL 30 DAY);";
										if ($updatedDetails = $mysqliConnection -> multi_query($updateDetailsQuery)) {
											$toCookieValue = array("remembermeid" => $newRememberID,
																"remembermetoken" => $getToken);
											setcookie("logincookie", json_encode($toCookieValue), strtotime("9999-12-31"), "/", "streetor.sg", true, true);
											$_SESSION["loggedIn"] = true;
											$_SESSION["userID"] = $dbAccountID;
											$AssocReturn["successURL"] = "https://streetor.sg";
										} else {
											$AssocReturn["errormessages"]["loginError"] = "An error occurred.";
											$AssocReturn["errormessages"]["2FAError"] = "An error occurred.";
										}
									}
								}
							} else {
								if ($remember === false) {
									$_SESSION["loggedIn"] = true;
									$_SESSION["userID"] = $dbAccountID;
									$AssocReturn["successURL"] = "https://streetor.sg";
								} else {
									$newRememberID = getRandomString(30);
									$getToken = getRandomString(50);
									$hashedToken = hash("sha512", $getToken);
									$updateDetailsQuery = "INSERT INTO remembereddevices(rememberID, tokenHash, accountID, 2FAverified, loggedInTime)
									VALUES ('{$newRememberID}', '{$hashedToken}', {$dbAccountID}, 0, NOW());
									DELETE FROM remembereddevices
									WHERE loggedInTime <= SUBDATE(NOW(), INTERVAL 30 DAY);";
									if ($updatedDetails = $mysqliConnection -> multi_query($updateDetailsQuery)) {
										$toCookieValue = array("remembermeid" => $newRememberID,
															"remembermetoken" => $getToken);
										setcookie("logincookie", json_encode($toCookieValue), strtotime("9999-12-31"), "/", "streetor.sg", true, true);
										$_SESSION["loggedIn"] = true;
										$_SESSION["userID"] = $dbAccountID;
										$AssocReturn["successURL"] = "https://streetor.sg";
									} else {
										$AssocReturn["errormessages"]["loginError"] = "An error occurred.";
										$AssocReturn["errormessages"]["2FAError"] = "An error occurred.";
									}
								}
							}
						} else {
							$AssocReturn["errormessages"]["passwordError"] = "Incorrect password.";
						}
					} else {
						$AssocReturn["errormessages"]["loginError"] = "An error occurred.";
					}
				} else {
					$AssocReturn["errormessages"]["usernameError"] = "No such account exists with this username.";
				}
				$queriedUsers -> free();
			} else {
				$AssocReturn["errormessages"]["loginError"] = "An error occurred.";
			}
		}
	} else {
		$AssocReturn["errormessages"]["loginError"] = "Your connection is not secure and this request could not be processed. Please try again later.";
	}
}
$mysqliConnection -> close();
echo json_encode($AssocReturn);
?>