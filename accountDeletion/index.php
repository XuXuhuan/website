<?php
error_reporting(0);
date_default_timezone_set("MST");
session_start();
$stylesheetLink;
$DOMtitle;
$imageURL;
$message;
$topnavText;
$topnavColor;
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if (isset($_COOKIE["darktheme"]) && $_COOKIE["darktheme"] === "false") {
	$stylesheetLink = "accountDeletionLightTheme.css";
} else {
	$stylesheetLink = "accountDeletionDarkTheme.css";
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
	if (empty(trim($_GET["email"])) || empty(trim($_GET["token"])) || empty(filter_var($_GET["email"], FILTER_VALIDATE_EMAIL)) || strlen($_GET["token"]) !== 50) {
		$DOMtitle = "Invalid Link · Streetor";
		$imageURL = "../Assets/global/CrossMark.png";
		$message = "This account deletion link was keyed in wrongly. If you would like to sign up for another account, please proceed to the <a href='../signup/' style='color: #4486f4;'>sign up page</a>.";
		$topnavText = "This link is invalid.";
		$topnavColor = "#topnav {
			background-color: #E60505;
		}";
	} else {
		$getEmail = $mysqliConnection -> real_escape_string($_GET["email"]);
		$getVerifToken = $mysqliConnection -> real_escape_string($_GET["token"]);
		$selectEmailUsersQuery = "
		SELECT username
		FROM accountdetails
		WHERE email = '{$getEmail}'
		AND accountDeletionToken = '{$getVerifToken}'
		AND accountDeletionTime >= SUBDATE(NOW(), INTERVAL 10 MINUTE);";
		if ($queriedEmailUsers = $mysqliConnection -> query($selectEmailUsersQuery)) {
			if ($queriedEmailUsers -> num_rows > 0) {
				if ($assocEmailUsers = $queriedEmailUsers -> fetch_assoc()) {
					$dbUsername = htmlspecialchars($assocEmailUsers["username"], ENT_QUOTES);
					$updateVerificationQuery = "
					DELETE FROM accountdetails
					WHERE accountDeletionToken = '{$getVerifToken}'
					AND email = '{$getEmail}'";
					if ($preparedUpdateVerif = $mysqliConnection -> query($updateVerificationQuery)) {
						setcookie("logincookie", null, -1, "/", "www.streetor.sg", true, true);
						$_SESSION["loggedIn"] = false;
						unset($_SESSION["userID"]);
						unset($_SESSION["username"]);
						unset($_SESSION["emailVerifToken"]);
						unset($_SESSION["userChangeToken"]);
						unset($_SESSION["passChangeToken"]);
						unset($_SESSION["emailChangeToken"]);
						unset($_SESSION["accountDeletionToken"]);
						unset($_SESSION["email"]);
						$DOMtitle = "Account Deleted · Streetor";
						$imageURL = "../Assets/global/CheckMark.png";
						$message = "Your account, {$dbUsername}, has been successfully deleted. If you would like to sign up for another account, please proceed to the <a href='../signup/' style='color: #4486f4;'>sign up page</a>.";
						$topnavText = "Your account has been deleted.";
						$topnavColor = "#topnav {
							background-color: #00D200;
						}";
					} else {
						$DOMtitle = "An internal error occured · Streetor";
						$imageURL = "../Assets/global/CrossMark.png";
						$message = "An error occurred.";
						$topnavText = "An error occurred.";
						$topnavColor = "#topnav {
							background-color: #E60505;
						}";
					}
				} else {
					$DOMtitle = "An internal error occured · Streetor";
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
				$message = "This email deletion link has either expired, was keyed in wrongly or your account has already been deleted. If you would like to sign up for another account, please proceed to the <a href='../signup/' style='color: #4486f4;'>sign up page</a>.";
				$topnavText = "This link is invalid.";
				$topnavColor = "#topnav {
					background-color: #E60505;
				}";
			}
			$queriedEmailUsers -> free();
		} else {
			$DOMtitle = "An internal error occured · Streetor";
			$imageURL = "../Assets/global/CrossMark.png";
			$message = "An error occurred.";
			$topnavText = "An error occurred.";
			$topnavColor = "#topnav {
				background-color: #E60505;
			}";
		}
	}
}
if ($stylesheetLink === "accountDeletionDarkTheme.css") {
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
			<p id='deleteText'>{$message}</p>
		</div>
	</body>
</html>";
?>