<?php
error_reporting(0);
date_default_timezone_set("MST");
session_start();
$alertError;
$stylesheetLink;
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength));
}
if (isset($_COOKIE["darktheme"]) && $_COOKIE["darktheme"] === "false") {
	$stylesheetLink = "signUpLightTheme.css";
} else {
	$stylesheetLink = "signUpDarkTheme.css";
}
if ($mysqliConnection -> connect_errno) {
	$alertError = "A connection error occurred. Please try again later.";
}
else if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") {
	if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
		if (isset($_COOKIE["logincookie"])) {
			$cookieValues = json_decode($_COOKIE["logincookie"], true);
			$rememberMeID = $mysqliConnection -> real_escape_string($cookieValues["remembermeid"]);
			$remememberMeToken = $mysqliConnection -> real_escape_string($cookieValues["remembermetoken"]);
			if (strlen(trim($rememberMeID)) === 30) {
				$selectAccountDetailsQuery = "SELECT accountdetails.accountID, accountdetails.username, remembereddevices.tokenHash, accountdetails.email
				FROM accountdetails
				LEFT JOIN remembereddevices
				ON accountdetails.accountID = remembereddevices.accountID
				WHERE rememberID = '{$rememberMeID}'";
				if ($queriedDetails = $mysqliConnection -> query($selectAccountDetailsQuery)) {
					if ($queriedDetails -> num_rows > 0) {
						if ($assocQueriedDetails = $queriedDetails -> fetch_assoc()) {
							$dbAccountID = $assocQueriedDetails["accountID"];
							$dbUsername = $assocQueriedDetails["username"];
							$dbTokenHash = $assocQueriedDetails["tokenHash"];
							$dbEmail = $assocQueriedDetails["email"];
							if (hash_equals($dbTokenHash, hash("sha512", $remememberMeToken)) === true) {
								$randomToken = getRandomString(50);
								$hashedRandomToken = hash("sha512", $randomToken);
								$updateTokenHashQuery = "UPDATE remembereddevices
								SET tokenHash = '{$hashedNewToken}'
								WHERE accountID = '{$dbAccountID}'";
								if ($updatedTokenHash = $mysqliConnection -> query($updateTokenHashQuery)) {
									$newCookieDecoded = array("remembermeid" => $rememberMeID,
															"remembermetoken" => $randomToken);
									setcookie("logincookie", json_encode($newCookieDecoded), strtotime("9999-12-31"), "/", "streetor.sg", true, true);
									$_SESSION["loggedIn"] = true;
									$_SESSION["userID"] = $dbAccountID;
									header("Location: https://streetor.sg");
								} else {
									$alertError = "An error occurred.";
								}
							}
						} else {
							$alertError = "An error occurred.";
						}
					} else {
						header("Location: https://streetor.sg/login/");
					}
					$queriedDetails -> free();
				} else {
					$alertError = "An error occurred.";
				}
			}
		}
	} else {
		header("Location: https://streetor.sg");
	}
} else {
	$alertError = "Your connection is insecure and this request could not be processed. Please try again later.";
}
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
        <title>Sign Up · Streetor</title>
    </head>
    <body>
		<header>
			<nav id='topnav'>
				<p id='orgName' class='notSelectable'>STREETOR</p>
			</nav>
		</header>
		<main>
			<div id='mainCont'>
				<div id='signUpContainer'>
					<h2 id='signUpLabel'>Sign Up</h2>
					<div id='usernameCont'>
						<label for='usernameField'>Username</label>
						<input class='signUpInputField' spellcheck='false' autocomplete='off' type='text' placeholder='Username' id='usernameField'/>
						<p id='usernameError' class='inputErrorText'>This field is required.</p>
					</div>
					<div id='newPasswordCont'>
						<label for='newPasswordField'>Password</label>
						<div id='newPasswordFieldCont'>
							<input class='signUpInputField' type='password' spellcheck='false' autocomplete='off' placeholder='Password' id='newPasswordField'/>
							<button class='passwordShowButton' id='newPasswordToggleButton'>
								<div class='showPassImage' id='newPasswordToggleImageCont'></div>
							</button>
						</div>
						<p id='newPasswordError' class='inputErrorText'>This field is required.</p>
					</div>
					<div id='confirmPasswordCont'>
						<label for='confirmPasswordField'>Confirm Password</label>
						<div id='confirmPasswordFieldCont'>
							<input class='signUpInputField' type='password' spellcheck='false' autocomplete='off' placeholder='Confirm Password' id='confirmPasswordField'/>
							<button class='passwordShowButton' id='confirmPasswordToggleButton'>
								<div class='showPassImage' id='confirmPasswordToggleImageCont'></div>
							</button>
						</div>
						<p id='confirmPasswordError' class='inputErrorText'>This field is required.</p>
					</div>
					<div id='firstNameCont'>
						<label for='firstNameField'>First Name</label>
						<input class='signUpInputField' spellcheck='false' autocomplete='off' type='text' placeholder='First Name' id='firstNameField'/>
						<p id='firstNameError' class='inputErrorText'>This field is required.</p>
					</div>
					<div id='lastNameCont'>
						<label for='lastNameField'>Last Name</label>
						<input class='signUpInputField' spellcheck='false' autocomplete='off' type='text' placeholder='Last Name' id='lastNameField'/>
						<p id='lastNameError' class='inputErrorText'>This field is required.</p>
					</div>
					<div id='emailCont'>
						<label for='emailField'>Email</label>
						<input class='signUpInputField' spellcheck='false' autocomplete='off' type='email' placeholder='Email' id='emailField'/>
						<p id='emailError' class='inputErrorText'>This field is required.</p>
					</div>
					<div id='signUpFormSubmitCont'>
						<div id='signUpButtonCont'>
							<button id='signUpButton' onmouseup='submitSignUp(event)' onmousedown='cancelSubmitSignUpTimeout(event)'>Sign Up</button>
						</div>
						<p id='signUpMessage'>{$alertError}</p>
					</div>
					<a href='../login/' id='loginLink'>Already have an account? Log in here</a>
                    <p id='midLabel'>OR</p>
                    <a href='../' id='homeLink'>Browse the website</a>
				</div>
			</div>
		</main>
		<footer>
		</footer>
    </body>
</html>";
$mysqliConnection -> close();
?>