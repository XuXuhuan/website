<?php
error_reporting(0);
date_default_timezone_set("MST");
session_start();
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength));
}
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if (isset($_COOKIE["darktheme"]) && $_COOKIE["darktheme"] === "false") {
	$stylesheetLink = "signupLightTheme.css";
} else {
	$stylesheetLink = "signupDarkTheme.css";
}
if ($mysqliConnection -> connect_errno) {
	echo "A connection error occurred. Please try again later.";
}
else if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
	header("Location: https://www.streetor.sg/home/");
}
else if (isset($_COOKIE["logincookie"])) {
	$cookieValues = json_decode($_COOKIE["logincookie"], true);
	$rememberMeID = $mysqliConnection -> real_escape_string($cookieValues["remembermeid"]);
	$remememberMeToken = $mysqliConnection -> real_escape_string($cookieValues["remembermetoken"]);
	if (strlen($rememberMeID) === 30) {
		$selectAccountDetailsQuery = "
		SELECT accountID, username, tokenHash, email, rememberID
		FROM accountdetails
		WHERE rememberID = '$rememberMeID'";
		if ($queriedDetails = $mysqliConnection -> query($selectAccountDetailsQuery)) {
			if ($queriedDetails -> num_rows > 0) {
				if ($assocQueriedDetails = $queriedDetails -> fetch_assoc()) {
					$dbAccountID = $assocQueriedDetails["accountID"];
					$dbUsername = $assocQueriedDetails["username"];
					$dbTokenHash = $assocQueriedDetails["tokenHash"];
					$dbEmail = $assocQueriedDetails["email"];
					$dbRememberID = $assocQueriedDetails["rememberID"];
					if (hash_equals($dbTokenHash, hash("sha512", $remememberMeToken)) === true) {
						$randomToken = getRandomString(50);
						$updateTokenHashQuery = "
						UPDATE accountdetails
						SET tokenHash = '" . hash("sha512", $randomToken) . "'
						WHERE rememberID = '$dbAccountID'";
						if ($updatedTokenHash = $mysqliConnection -> query($updateTokenHashQuery)) {
							$newCookieDecoded = array("remembermeid" => $dbRememberID,
													"remembermetoken" => $randomToken);
							setcookie("logincookie", json_encode($newCookieDecoded), strtotime("9999-12-31"), "/", "www.streetor.sg", true, true);
							$_SESSION["loggedIn"] = true;
							$_SESSION["userID"] = $dbAccountID;
							$_SESSION["username"] = $dbUsername;
							$_SESSION["email"] = $dbEmail;
							header("Location: https://www.streetor.sg/home/");
						} else {
							echo "<p>An internal error occurred. Please try again later.</p>";
						}
					}
				} else {
					echo "<p>An internal error occurred. Please try again later.</p>";
				}
			} else {
				header("Location: https://www.streetor.sg/login/");
			}
			$queriedDetails -> free();
		} else {
			echo "<p>An internal errors occurred. Please try again later.</p>";
		}
	}
}
$mysqliConnection -> close();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="lifestyle, life, tips, share, social media">
        <meta name="description" content="Share about your lifestyle or lifestyle tips!">
		<link rel="stylesheet" href="css/signupPage.css">
		<script src="web.js" defer></script>
        <title>Sign Up · Streetor</title>
    </head>
    <body>
		<input spellcheck="false" autocomplete="off" type="text" placeholder="Username" id="usernameField"/>
		<p id="usernameError" style="color: red; margin: 0;"></p>
		<div>
			<input type="password" spellcheck="false" autocomplete="off" placeholder="Password" id="passwordField"/>
			<button id="showpass">Show password</button>
		</div>
		<p id="passwordError" style="color: red; margin: 0;"></p>
		<input spellcheck="false" autocomplete="off" type="text" placeholder="First Name" id="firstNameField"/>
		<p id="firstNameError" style="color: red; margin: 0;"></p>
		<input spellcheck="false" autocomplete="off" type="text" placeholder="Last Name" id="lastNameField"/>
		<p id="lastNameError" style="color: red; margin: 0;"></p>
		<input spellcheck="false" autocomplete="off" type="email" placeholder="Email" id="emailField"/>
		<p id="emailError" style="color: red; margin: 0;"></p>
		<button id="signUpButton">Sign Up</button>
		<p id="signupMessage"></p>
		<a href="login/">Already have an account? Log in here</a>
    </body>
</html>