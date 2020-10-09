<?php
session_start();
error_reporting(0);
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
$loginError = "";
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength / 2));
}
if (isset($_COOKIE["darktheme"]) && $_COOKIE["darktheme"] === "false") {
	$stylesheetLink = "loginLightTheme.css";
} else {
	$stylesheetLink = "loginDarkTheme.css";
}
if ($mysqliConnection -> connect_errno) {
	$loginError = "An internal error occurred. Please try again later.";
}
else if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") {
	if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
		if (isset($_COOKIE["logincookie"])) {
			$cookieValues = json_decode($_COOKIE["logincookie"], true);
			$rememberMeID = $mysqliConnection -> real_escape_string($cookieValues["remembermeid"]);
			$remememberMeToken = $mysqliConnection -> real_escape_string($cookieValues["remembermetoken"]);
			if (strlen($rememberMeID) === 30) {
				$selectAccountDetailsQuery = "
				SELECT accountID, username, tokenHash, email, emailVerificationTime
				FROM accountdetails
				WHERE rememberID = '{$rememberMeID}'";
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
								$hashedRandomToken = hash("sha512", $randomToken);
								$updateTokenHashQuery = "
								UPDATE accountdetails
								SET tokenHash = '{$hashedRandomToken}'
								WHERE accountID = '{$dbAccountID}'";
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
	} else {
		header("Location: https://www.streetor.sg/home/");
	}
} else {
	$loginError = "Your connection is insecure and this request could not be processed. Please try again later.";
}
$mysqliConnection -> close();
echo "
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta name='keywords' content='lifestyle, life, tips, share, social media'>
        <meta name='description' content='Share about your lifestyle or lifestyle tips!'>
        <link rel='stylesheet' href='{$stylesheetLink}'>
		<script src='web.js' defer></script>
        <title>Login · Streetor</title>
    </head>
    <body>
		<header>
			<nav id='topnav'>
				<p id='orgName' class='notSelectable'>STREETOR</p>
			</nav>
		</header>
		<main>
			<div id='mainCont'>
                <div id='logInContainer'>
					<h2 id='logInLabel'>Log In</h2>
                    <div id='usernameCont' class='inputFieldConts'>
                        <label for='usernameField'>Username</label>
                        <input type='text' spellcheck='false' autocomplete='off' placeholder='Username' id='usernameField' class='logInInputField'>
                        <p id='usernameError' class='inputErrorText'>This field is required.</p>
                    </div>
                    <div id='passwordCont' class='inputFieldConts'>
                        <label for='passwordField'>Password</label>
                        <div id='passwordFieldCont'>
                            <input type='password' spellcheck='false' autocomplete='off' placeholder='Password' id='passwordField' class='logInInputField'>
                            <button id='passwordShowButton'>
                                <div id='passwordShowImageCont'></div>
                            </button>
                        </div>
                        <p id='passwordError' class='inputErrorText'>This field is required.</p>
                    </div>
                    <div id='submitLogInCont'>
                        <div id='logInButtonCont'>
                            <button id='logInButton' onmouseup='submitLogin(event)' onmousedown='cancelSubmitLoginTimeout(event)'>Login</button>
                        </div>
                        <p id='logInMessage'>{$loginError}</p>
                    </div>
                    <a href='../' id='signUpLink'>Don&#39;t have an account? Sign up here</a>
                </div>
            </div>
        </main>
		<footer>
		</footer>
    </body>
</html>";
?>