<?php
session_start();
date_default_timezone_set("MST");
$AssocReturn = array("errormessages" => array(
						"usernameError" => "",
						"passwordError" => "",
						"loginError" => ""
					),
					"successmessage" => "",
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
		if (empty(trim($getUser))) {
			$AssocReturn["errormessages"]["usernameError"] = "This field is required.";
		}
		if (empty(trim($getPass))) {
			$AssocReturn["errormessages"]["passwordError"] = "This field is required.";
		}
		if (empty($AssocReturn["errormessages"]["usernameError"]) &&
			empty($AssocReturn["errormessages"]["paswordError"]) &&
			empty($AssocReturn["errormessages"]["loginError"])) {
			$checkDetailsQuery = "
			SELECT accountID, username, password, email, emailVerified, rememberID, 2FAenabled, biography
			FROM accountdetails
			WHERE username = '{$getUser}'";
			if ($queriedUsers = $mysqliConnection -> query($checkDetailsQuery)) {
				if ($queriedUsers -> num_rows > 0) {
					if ($assocQueriedUsers = $queriedUsers -> fetch_assoc()) {
						$dbAccountID = $assocQueriedUsers["accountID"];
						$dbUsername = $assocQueriedUsers["username"];
						$dbPassword = $assocQueriedUsers["password"];
						$dbEmail = $assocQueriedUsers["email"];
						$dbRememberID = $assocQueriedUsers["rememberID"];
						if (password_verify(base64_encode(hash("sha512", $getPass, true)), $dbPassword) === true) {
							$getToken = getRandomString(50);
							$hashedToken = hash("sha512", $getToken);
							$updateDetailsQuery = "
							UPDATE accountdetails
							SET tokenHash = '{$hashedToken}'
							WHERE accountID = '{$dbAccountID}'";
							if ($updatedDetails = $mysqliConnection -> query($updateDetailsQuery)) {
								$toCookieValue = array("remembermeid" => $dbRememberID,
													"remembermetoken" => $getToken);
								setcookie("logincookie", json_encode($toCookieValue), strtotime("9999-12-31"), "/", "www.streetor.sg", true, true);
								$_SESSION["loggedIn"] = true;
								$_SESSION["userID"] = $dbAccountID;
								$_SESSION["username"] = $dbUsername;
								$_SESSION["email"] = $dbEmail;
								$AssocReturn["successmessage"] = "Logging in...";
								$AssocReturn["successURL"] = "https://www.streetor.sg";
							} else {
								$AssocReturn["errormessages"]["loginError"] = "An error occurred.";
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