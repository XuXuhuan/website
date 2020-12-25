<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$DOMtitle;
$imageURL;
$message;
$topnavText;
$topnavColor;
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if (isset($_COOKIE["darktheme"]) && $_COOKIE["darktheme"] === "false") {
	$stylesheetLink = "emailVerificationLightTheme.css";
} else {
	$stylesheetLink = "emailVerificationDarkTheme.css";
}
if ($mysqliConnection -> connect_errno) {
	$DOMtitle = "A connection error occurred · Streetor";
	$imageURL = "../Assets/global/CrossMark.png";
	$message = "A connection error occurred. Please try again later.";
	$topnavText = "A connection error occurred.";
	$topnavColor = "#topnav {
		background-color: #E60505;
	}";
} else {
	if (empty(trim($_GET["email"])) || empty(trim($_GET["token"])) || empty(filter_var($_GET["email"], FILTER_VALIDATE_EMAIL)) || strlen($_GET["token"]) !== 20) {
		$DOMtitle = "Invalid Link · Streetor";
		$imageURL = "../Assets/global/CrossMark.png";
		$message = "This email verification link was keyed in wrongly. If your email has already been verified, please proceed to the <a href='../login/' style='color: #4486f4;'>login page</a>.";
		$topnavText = "This link is invalid.";
		$topnavColor = "#topnav {
			background-color: #E60505;
		}";
	} else {
		$getEmail = $mysqliConnection -> real_escape_string($_GET["email"]);
		$getVerifToken = $mysqliConnection -> real_escape_string($_GET["token"]);
		$selectEmailUsersQuery = "
		SELECT username, accountID
		FROM accountdetails
		WHERE email = '{$getEmail}'
		AND emailVerificationToken = '{$getVerifToken}'
		AND emailVerified = 0
		AND emailVerificationTime >= SUBDATE(NOW(), INTERVAL 10 MINUTE);";
		if ($queriedEmailUsers = $mysqliConnection -> query($selectEmailUsersQuery)) {
			if ($queriedEmailUsers -> num_rows > 0) {
				if ($assocEmailUsers = $queriedEmailUsers -> fetch_assoc()) {
					$dbUsername = htmlspecialchars($assocEmailUsers["username"], ENT_QUOTES);
					$updateVerificationQuery = "
					UPDATE accountdetails
					SET emailVerified = 1,
					emailVerificationToken = NULL
					WHERE username = '{$assocEmailUsers["accountID"]}'";
					if ($updatedVerification = $mysqliConnection -> query($updateVerificationQuery)) {
						$DOMtitle = "Email Verified! · Streetor";
						$imageURL = "../Assets/global/CheckMark.png";
						$message = "The email of your account, {$dbUsername}, has been successfully verified! If you are not logged in, please proceed to the <a href='../login/' style='color: #4486f4;'>login page</a>. Otherwise, please proceed to the <a href='../home/' style='color: #4486f4;'>home page</a>.";
						$topnavText = "Your email has been verified!";
						$topnavColor = "#topnav {
							background-color: #00D200;
						}";
					} else {
						$DOMtitle = "An error occurred · Streetor";
						$imageURL = "../Assets/global/CrossMark.png";
						$message = "An error occurred.";
						$topnavText = "An error occurred.";
						$topnavColor = "#topnav {
							background-color: #E60505;
						}";
					}
				} else {
					$DOMtitle = "An error occurred · Streetor";
					$imageURL = "../Assets/global/CrossMark.png";
					$message = "An error occurred.";
					$topnavText = "An error occurred.";
					$topnavColor = "#topnav {
						background-color: #E60505;
					}";
				}
			} else {
				$DOMtitle = "Invalid Link · Streetor";
				$imageURL = "../Assets/global/CrossMark.png";
				$message = "This email verification link has either expired, or it was keyed in wrongly. If your email has already been verified, please proceed to the <a href='../login/' style='color: #4486f4;'>login page</a>.";
				$topnavText = "This link is invalid.";
				$topnavColor = "#topnav {
					background-color: #E60505;
				}";
			}
			$queriedEmailUsers -> free();
		} else {
			$DOMtitle = "An error occurred · Streetor";
			$imageURL = "../Assets/global/CrossMark.png";
			$message = "An error occurred.";
			$topnavText = "An error occurred.";
			$topnavColor = "#topnav {
				background-color: #E60505;
			}";
		}
	}
}
if ($stylesheetLink === "emailVerificationDarkTheme.css") {
	$topnavColor = "";
}
$mysqliConnection -> close();
echo "
<!DOCTYPE html>
<html lang='en'>
	<head>
		<meta charset='UTF-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1.0'>
		<link rel='stylesheet' href='{$stylesheetLink}'>
		<title>{$DOMtitle}</title>
		<style>
			{$topnavColor}
			#imageCont {
				background-image: url({$imageURL});
			}
		</style>
	</head>
	<body>
		<nav id='topnav'>
			<p id='topnavText'>{$topnavText}</p>
		</nav>
		<div id='mainCont'>
			<div id='imageCont'></div>
			<p id='verifText'>{$message}</p>
		</div>
	</body>
</html>";
?>