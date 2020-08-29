<?php
session_start();
error_reporting(0);
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
$loginError = "";
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength / 2));
}
if ($mysqliConnection -> connect_errno) {
	$loginError = "An internal error occurred. Please try again later.";
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
		SELECT accountID, username, tokenHash, email, emailVerificationTime
		FROM accountdetails
		WHERE rememberID = '$rememberMeID'";
		if ($queriedDetails = $mysqliConnection -> query($compareDetailsQuery)) {
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
						WHERE accountID = '$dbAccountID'";
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
							$loginError = "An internal error occurred. Please log in manually or try again later.";
						}
					}
				} else {
					$loginError = "An internal error occurred. Please log in manually or try again later.";
				}
			} else {
				$loginError = "An error occurred. Your login credentials do not match those in the database.";
			}
			$queriedDetails -> free();
		}
	}
}
$mysqliConnection -> close();
echo '
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="lifestyle, life, tips, share, social media">
        <meta name="description" content="Share about your lifestyle or lifestyle tips!">
        <link rel="stylesheet" href="loginPage.css">
		<script src="web.js" defer></script>
        <title>Login · Streetor</title>
    </head>
    <body>
		<input type="text" spellcheck="false" autocomplete="off" placeholder="Username" id="usernameField">
		<p id="usernameError" style="color: red; margin: 0;"></p>
		<input type="password" spellcheck="false" autocomplete="off" placeholder="Password" id="passwordField">
		<p id="passwordError" style="color: red; margin: 0;"></p>
		<button id="loginButton">Login</button>
		<p id="loginError">' . $loginError . '</p>
		<a href="../">Don&#39;t have an account? Sign up here</a>
    </body>
</html>';
?>