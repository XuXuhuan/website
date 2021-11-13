<?php
error_reporting(0);
date_default_timezone_set("MST");
session_start();
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
$assocReturn = array("message" => "",
					 "leftoverCooldown" => "");
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength / 2));
}
$randomString = getRandomString(20);
$_SESSION["emailVerifToken"] = isset($_SESSION["emailVerifToken"]) ? $_SESSION["emailVerifToken"] : $randomString;
if ($mysqliConnection -> connect_errno) {
	$assocReturn["message"] = "An error occurred.";
} else {
	if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
		$selectNeededDetailsQuery = "
		SELECT username, tokenHash, firstName, email, emailVerified, emailVerificationTime
		FROM accountdetails
		WHERE accountID = '{$_SESSION["userID"]}'";
		if ($neededDetails = $mysqliConnection -> query($selectNeededDetailsQuery)) {
			if ($neededDetails -> num_rows > 0) {
				if ($assocNeededDetails = $neededDetails -> fetch_assoc()) {
				$dbUsername = htmlspecialchars($assocNeededDetails["username"], ENT_QUOTES);
				$dbTokenHash = $assocNeededDetails["tokenHash"];
				$dbFirstName = htmlspecialchars($assocNeededDetails["firstName"], ENT_QUOTES);
				$dbEmail = urlencode($assocNeededDetails["email"]);
				$dbEmailVerif = $assocNeededDetails["emailVerified"];
				$dbLastSentTime = $assocNeededDetails["emailVerificationTime"];
					if ($dbEmailVerif == true) {
						$assocReturn["message"] = "This account has already been verified! Please reload this page.";
					}
					if ((time() - 120) < strtotime($dbLastSentTime)) {
						$assocReturn["leftoverCooldown"] = strtotime($dbLastSentTime) + 120 - time();
						$assocReturn["message"] = "Please wait until the cooldown is over!";
					} else {
						if (mail($assocNeededDetails["email"], "Email Verification", $emailDOM, implode(PHP_EOL, $emailHeaders))) {
							$updateVerificationEmailTimeQuery = "
							UPDATE accountdetails
							SET emailVerificationTime = NOW(),
							emailVerificationToken = '{$_SESSION["emailVerifToken"]}'
							WHERE accountID = '{$_SESSION["userID"]}'";
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
					$assocReturn["message"] = "An error occurred.";
				}
			} else {
				$assocReturn["message"] = "No records were found in the database. This account may have been deleted.";
			}
			$neededDetails -> free();
		} else {
			$assocReturn["message"] = "An error occurred.";
		}
	} else {
		$assocReturn["message"] = "Please <a href='https://streetor.sg/login/' style='color: #4486f4;'>log in and try again.</a>";
	}
}
$mysqliConnection -> close();
echo json_encode($assocReturn);
?>