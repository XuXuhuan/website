<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$stylesheetLink;
$loginAlert;
$logoutOrLogin = '<p id="logLabel" class="notSelectable">Logout</p>';
$userBiography;
$emailIsVerified;
$logoutOrLoginScript = '
<script>"use strict";const _0x2f34=["../lo","Micro","text","stene","Selec","POST","XMLHt","php","addEv","soft.","#logL","respo","tpReq","click","entLi","abel","uest","XMLHT","onloa","tor","href","locat","send","gout.","open","ion","nseTy","query","nseTe"];(function(_0x5e9acd,_0x2f344a){const _0x41584a=function(_0x419e75){while(--_0x419e75){_0x5e9acd["push"](_0x5e9acd["shift"]());}};_0x41584a(++_0x2f344a);}(_0x2f34,0x1f1));const _0x4158=function(_0x5e9acd,_0x2f344a){_0x5e9acd=_0x5e9acd-0x0;let _0x41584a=_0x2f34[_0x5e9acd];return _0x41584a;};const _0x5dd2de=document[_0x4158("0x17")+_0x4158("0x0")+_0x4158("0xf")](_0x4158("0x6")+_0x4158("0xb"));var _0x2d4945;_0x5dd2de[_0x4158("0x4")+_0x4158("0xa")+_0x4158("0x1c")+"r"](_0x4158("0x9"),function(){clearTimeout(_0x2d4945);_0x2d4945=setTimeout(function(){const _0x4e392c=window[_0x4158("0x2")+_0x4158("0x8")+_0x4158("0xc")]?new XMLHttpRequest():new ActiveXObject(_0x4158("0x1a")+_0x4158("0x5")+_0x4158("0xd")+"TP");_0x4e392c[_0x4158("0x14")](_0x4158("0x1"),_0x4158("0x19")+_0x4158("0x13")+_0x4158("0x3"),!![]);_0x4e392c[_0x4158("0x7")+_0x4158("0x16")+"pe"]=_0x4158("0x1b");_0x4e392c[_0x4158("0xe")+"d"]=function(){window[_0x4158("0x11")+_0x4158("0x15")][_0x4158("0x10")]=_0x4e392c[_0x4158("0x7")+_0x4158("0x18")+"xt"];};_0x4e392c[_0x4158("0x12")]();},0x15e);});</script>';
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength / 2));
}
if (isset($_COOKIE["darktheme"]) && $_COOKIE["darktheme"] === "false") {
	$stylesheetLink = "settingsLightTheme.css";
} else {
	$stylesheetLink = "settingsDarkTheme.css";
}
if ($mysqliConnection -> connect_errno) {
	$loginAlert = '
	<div id="alertCont">
		<p id="alertText">A connection error occurred. Please refresh the page or try again later.</p>
	</div>';
} else {
	if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") {
		if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
			if (isset($_COOKIE["logincookie"])) {
				$cookieValues = json_decode($_COOKIE["logincookie"], true);
				$rememberMeID = $mysqliConnection -> real_escape_string($cookieValues["remembermeid"]);
				$remememberMeToken = $mysqliConnection -> real_escape_string($cookieValues["remembermetoken"]);
				if (strlen($rememberMeID) === 30) {
					$selectAccountDetailsQuery = "
					SELECT accountID, username, tokenHash, email, emailVerified, biography
					FROM accountdetails
					WHERE rememberID = '$rememberMeID'";
					if ($allNeededDetails = $mysqliConnection -> query($selectAccountDetailsQuery)) {
						if ($allNeededDetails -> num_rows > 0) {
							if ($assocNeededDetails = $allNeededDetails -> fetch_assoc()) {
								$dbAccountID = $assocNeededDetails["accountID"];
								$dbUsername = $assocNeededDetails["username"];
								$dbTokenHash = $assocNeededDetails["tokenHash"];
								$dbEmail = $assocNeededDetails["email"];
								$emailIsVerified = $assocNeededDetails["emailVerified"] == true ? "Yes" : "No";
								$userBiography = $assocNeededDetails["biography"];
								if (hash_equals($dbTokenHash, hash("sha512", $remememberMeToken)) === true) {
									$generateNewToken = getRandomString(50);
									$logoutOrLogin = "<p id='logLabel' class='notSelectable'>Logout</p>";
									$logoutOrLoginScript = '
									<script>"use strict";const _0x2f34=["../lo","Micro","text","stene","Selec","POST","XMLHt","php","addEv","soft.","#logL","respo","tpReq","click","entLi","abel","uest","XMLHT","onloa","tor","href","locat","send","gout.","open","ion","nseTy","query","nseTe"];(function(_0x5e9acd,_0x2f344a){const _0x41584a=function(_0x419e75){while(--_0x419e75){_0x5e9acd["push"](_0x5e9acd["shift"]());}};_0x41584a(++_0x2f344a);}(_0x2f34,0x1f1));const _0x4158=function(_0x5e9acd,_0x2f344a){_0x5e9acd=_0x5e9acd-0x0;let _0x41584a=_0x2f34[_0x5e9acd];return _0x41584a;};const _0x5dd2de=document[_0x4158("0x17")+_0x4158("0x0")+_0x4158("0xf")](_0x4158("0x6")+_0x4158("0xb"));var _0x2d4945;_0x5dd2de[_0x4158("0x4")+_0x4158("0xa")+_0x4158("0x1c")+"r"](_0x4158("0x9"),function(){clearTimeout(_0x2d4945);_0x2d4945=setTimeout(function(){const _0x4e392c=window[_0x4158("0x2")+_0x4158("0x8")+_0x4158("0xc")]?new XMLHttpRequest():new ActiveXObject(_0x4158("0x1a")+_0x4158("0x5")+_0x4158("0xd")+"TP");_0x4e392c[_0x4158("0x14")](_0x4158("0x1"),_0x4158("0x19")+_0x4158("0x13")+_0x4158("0x3"),!![]);_0x4e392c[_0x4158("0x7")+_0x4158("0x16")+"pe"]=_0x4158("0x1b");_0x4e392c[_0x4158("0xe")+"d"]=function(){window[_0x4158("0x11")+_0x4158("0x15")][_0x4158("0x10")]=_0x4e392c[_0x4158("0x7")+_0x4158("0x18")+"xt"];};_0x4e392c[_0x4158("0x12")]();},0x15e);});</script>';
									$updateNewToken = "
									UPDATE accountdetails
									SET tokenHash = '" . hash('sha512', $generateNewToken) . "'
									WHERE accountID = '$dbAccountID'";
									if ($tokenUpdateQuery = $mysqliConnection -> query($updateNewToken)) {
										$newCookieValuesDecoded = array("remembermeid" => $rememberMeID, "remembermetoken" => $generateNewToken);
										setcookie("logincookie", json_encode($newCookieValuesDecoded), strtotime("9999-12-31"), "/", "www.streetor.sg", true, true);
										$_SESSION["loggedIn"] = true;
										$_SESSION["userID"] = $dbAccountID;
										$_SESSION["username"] = $dbUsername;
										$_SESSION["email"] = $dbEmail;
									} else {
										$loginAlert = '
										<div id="alertCont">
											<p id="alertText">An internal error occurred. Please refresh the page or try again later.</p>
										</div>';
									}
								} else {
									header("Location: https://www.streetor.sg/login/");
								}
							} else {
								$loginAlert = '
								<div id="alertCont">
									<p id="alertText">An internal error occurred. Please refresh the page or try again later.</p>
								</div>';
							}
						} else {
							$loginAlert = '
							<div id="alertCont">
								<p id="alertText">No records for this account were found in the database. Please either log in and try again, try again later or refresh the page. This page is open only to logged in users.</p>
							</div>';
						}
						$allNeededDetails -> free();
					}
				} else {
					header("https://www.streetor.sg/login");
				}
			} else {
				$logoutOrLogin = "<a href='https://www.streetor.sg/login/' id='logLabel' class='notSelectable'>Login</a>";
				$loginAlert = '
				<div id="alertCont">
					<p id="alertText">You are logged out. This page is open only to logged in users.</p>
				</div>
				<script>"use strict";const _0xed20=["entLi","tCont","ent","rtBut","Selec","paren","#aler","tor","tElem","ton","#canc","butto","elAle","addEv","mouse","down","eChil","remov","query","stene"];(function(_0x14676c,_0xed20ed){const _0x37fba9=function(_0x4b9703){while(--_0x4b9703){_0x14676c["push"](_0x14676c["shift"]());}};_0x37fba9(++_0xed20ed);}(_0xed20,0xb9));const _0x37fb=function(_0x14676c,_0xed20ed){_0x14676c=_0x14676c-0x0;let _0x37fba9=_0xed20[_0x14676c];return _0x37fba9;};const _0x4a85b1=document[_0x37fb("0xd")+_0x37fb("0x13")+_0x37fb("0x2")](_0x37fb("0x1")+_0x37fb("0x10"));const _0x53158c=document[_0x37fb("0xd")+_0x37fb("0x13")+_0x37fb("0x2")](_0x37fb("0x5")+_0x37fb("0x7")+_0x37fb("0x12")+_0x37fb("0x4"));_0x53158c[_0x37fb("0x8")+_0x37fb("0xf")+_0x37fb("0xe")+"r"](_0x37fb("0x9")+_0x37fb("0xa"),function(_0x509d02){if(_0x509d02[_0x37fb("0x6")+"n"]===0x0){_0x4a85b1[_0x37fb("0x0")+_0x37fb("0x3")+_0x37fb("0x11")][_0x37fb("0xc")+_0x37fb("0xb")+"d"](_0x4a85b1);}});</script>';
			}
		}
		else if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
			$selectEmailVerifiedQuery = "
			SELECT emailVerified, biography
			FROM accountdetails
			WHERE accountID = " . $_SESSION["userID"] . "
			AND emailVerified = 0";
			if ($allNeededDetails = $mysqliConnection -> query($selectEmailVerifiedQuery)) {
				if ($allNeededDetails -> num_rows > 0) {
					if ($assocNeededDetails = $allNeededDetails -> fetch_assoc()) {
						$emailIsVerified = $assocNeededDetails["emailVerified"] == true ? "Yes" : "No";
						$userBiography = $assocNeededDetails["biography"];
					} else {
						$loginAlert = '
						<div id="alertCont">
							<p id="alertText">An internal error occurred. Please refresh the page or try again later.</p>
						</div>';
					}
				}
				$allNeededDetails -> free();
			} else {
				$loginAlert = '
				<div id="alertCont">
					<p id="alertText">An internal error occurred. Please refresh the page or try again later.</p>
				</div>';
			}
		}
	} else {
		$loginAlert = '
		<div id="alertCont">
			<p id="alertText">Your connection is not secure and this request could not be processed. Please refresh the page or try again later.</p>
		</div>';
	}
}
$mysqliConnection -> close();
if (!empty($loginAlert)) {
	echo '
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="keywords" content="lifestyle, life, tips, share, social media">
			<meta name="description" content="Share about your lifestyle or lifestyle tips!">
			<link rel="stylesheet" href="' . $stylesheetLink . '">
			<script src="web.js" defer></script>
			<title>Home · Streetor</title>
		</head>
		<body>
			<header>
				<nav id="topnav">
					<button id="menuToggle">
						<div id="menuImageCont"></div>
					</button>
					<p id="orgName" class="notSelectable">STREETOR</p>
					' . $logoutOrLogin . '
				</nav>
				<nav id="sidenav">
					<a href="https://www.streetor.sg/home/" id="homeLink">
						<div class="innerLinksCont">
							<div id="homeImage" class="sideNavImage"></div>
							<p id="homeText" class="notSelectable">Home</p>
						</div>
					</a>
					<a href="https://www.streetor.sg/profiles/" id="profilesLink">
						<div class="innerLinksCont">
							<div id="profilesImage" class="sideNavImage"></div>
							<p id="profilesText" class="notSelectable">Profiles</p>
						</div>
					</a>
					<a href="https://www.streetor.sg/settings/" id="settingsLink">
						<div class="innerLinksCont">
							<div id="settingsImage" class="sideNavImage"></div>
							<p id="settingsText" class="notSelectable">Settings</p>
						</div>
					</a>
					<a href="https://www.streetor.sg/explore/" id="exploreLink">
						<div class="innerLinksCont">
							<div id="exploreImage" class="sideNavImage"></div>
							<p id="exploreText" class="notSelectable">Explore</p>
						</div>
					</a>
					<a href="https://www.streetor.sg/privacy/" id="privacyLink">
						<div class="innerLinksCont">
							<div id="privacyImage" class="sideNavImage"></div>
							<p id="privacyText" class="notSelectable">Privacy</p>
						</div>
					</a>
				</nav>
			</header>
			<main>
				' . $logoutOrLoginScript . '
				<div id="mainCont">
				' . $loginAlert . '
				</div>
			</main>
			<footer>
			</footer>
		</body>
	</html>';
} else {
	$emailVerificationHTML = $emailIsVerified === "No" ?
	'<div id="verifyEmailButtonCont" style="height: 40px;">
		<button class="notSelectable" id="verifyEmailButton" onclick="sendEmailVerification()">Verify Email</button>
	</div>
	<p class="inputErrorText" id="verifyEmailError" style="text-align: center;"></p>'
	: '';
	echo '
	<!DOCTYPE html>
	<html lang="en">
		<head>
			<meta charset="UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0">
			<meta name="keywords" content="lifestyle, life, tips, share, social media">
			<meta name="description" content="Share about your lifestyle or lifestyle tips!">
			<link id="stylesheetLink" rel="stylesheet" href="' . $stylesheetLink . '">
			<title>Settings · Streetor</title>
		</head>
		<body>
			<header>
				<nav id="topnav">
					<button id="menuToggle">
						<div id="menuImageCont"></div>
					</button>
					<p id="orgName" class="notSelectable">STREETOR</p>
					' . $logoutOrLogin . '
				</nav>
				<nav id="sidenav">
					<a href="https://www.streetor.sg/home/" id="homeLink">
						<div class="innerLinksCont">
							<div id="homeImage" class="sideNavImage"></div>
							<p id="homeText" class="notSelectable">Home</p>
						</div>
					</a>
					<a href="https://www.streetor.sg/profiles/" id="profilesLink">
						<div class="innerLinksCont">
							<div id="profilesImage" class="sideNavImage"></div>
							<p id="profilesText" class="notSelectable">Profiles</p>
						</div>
					</a>
					<a href="https://www.streetor.sg/settings/" id="settingsLink">
						<div class="innerLinksCont">
							<div id="settingsImage" class="sideNavImage"></div>
							<p id="settingsText" class="notSelectable">Settings</p>
						</div>
					</a>
					<a href="https://www.streetor.sg/explore/" id="exploreLink">
						<div class="innerLinksCont">
							<div id="exploreImage" class="sideNavImage"></div>
							<p id="exploreText" class="notSelectable">Explore</p>
						</div>
					</a>
					<a href="https://www.streetor.sg/privacy/" id="privacyLink">
						<div class="innerLinksCont">
							<div id="privacyImage" class="sideNavImage"></div>
							<p id="privacyText" class="notSelectable">Privacy</p>
						</div>
					</a>
				</nav>
			</header>
			<main>
				' . $logoutOrLoginScript . '
				<div id="mainCont">
					<nav id="settingsOptions">
						<ul id="optionsList">
							<li class="optionItems">
								<button id="accountButton" class="optionButtons selectedTab" id="accountButton">
									<p class="optionsText notSelectable" id="accountText">My Account</p>
								</button>
							</li>
							<li class="optionItems">
								<button id="securityButton" class="optionButtons" id="securityButton">
									<p class="optionsText notSelectable" id="securityText">Account Security</p>
								</button>
							</li>
						</ul>
						<div id="selectedTabLine"></div>
					</nav>
					<div id="settingsInfoCont">
						<h1 class="topHeaderInfo notSelectable">Details</h1>
						<div class="infoRow" id="usernameRow">
							<p class="rowInfo notSelectable" id="usernameRowInfo">Username: ' . htmlspecialchars($_SESSION["username"], ENT_QUOTES) . '</p>
						</div>
						<div class="infoRow" id="emailRow">
							<p class="rowInfo notSelectable" id="emailRowInfo">Email: ' . htmlspecialchars($_SESSION["email"], ENT_QUOTES) . '</p>
						</div>
						<div class="infoColumnRow" id="emailVerifiedRow" style="padding-bottom: 0;">
							<p class="rowInfo notSelectable" id="emailVerifiedRowInfo" style="margin-bottom: 10px;">Email Verified: ' . $emailIsVerified . '</p>
							' . $emailVeirficationHTML . '
							<div id="verifyEmailButtonCont" style="height: 40px;">
								<button class="notSelectable" id="verifyEmailButton" onclick="sendEmailVerification()">Verify Email</button>
							</div>
							<p class="inputErrorText" id="verifyEmailError" style="text-align: center;"></p>
						</div>
						<h1 class="notSelectable">Change Details</h1>
						<div class="infoColumnRow">
							<p id="changeUsernameText" class="notSelectable" onclick="changeUserToggle()">
								Change Your Username
								<svg version="1.0" xmlns="http://www.w3.org/2000/svg" class="dropDownMenuArrow" id="changeUserMenuArrow" width="10px" height="10px" viewBox="0 0 1131.000000 744.000000" preserveAspectRatio="xMidYMid meet">
									<g transform="translate(0.000000,744.000000) scale(0.100000,-0.100000)" stroke="none">
										<path d="M887 6552 c-482 -482 -877 -882 -877 -887 0 -13 5642 -5655 5655
										-5655 12 0 5645 5645 5645 5657 0 12 -1753 1763 -1765 1763 -5 0 -882 -872
										-1947 -1937 l-1938 -1938 -1938 1938 c-1065 1065 -1942 1937 -1947 1937 -6 0
										-405 -395 -888 -878z"/>
									</g>
								</svg>
							</p>
							<div id="changeUserCont">
								<div id="newUserCont" style="margin-bottom: 5px;">
									<label for="newUsername" id="newUsernameLabel">New Username</label>
									<input type="text" onkeyup="validateChangeUserField(event)" placeholder="New Username" autocomplete="off" id="newUsernameField">
									<p id="newUsernameError" class="inputErrorText">This field is required.</p>
								</div>
								<div class="sendEmailButtonCont" style="height: 40px;">
									<button id="sendChangeUsernameEmailButton" class="notSelectable" onclick="updateUsername()">Change Username</button>
								</div>
							</div>
						</div>
						<div class="infoColumnRow">
							<p id="changePasswordText" class="notSelectable" onclick="changePassToggle()">
								Change Your Password
								<svg version="1.0" xmlns="http://www.w3.org/2000/svg" class="dropDownMenuArrow" id="changePassMenuArrow" width="10px" height="10px" viewBox="0 0 1131.000000 744.000000" preserveAspectRatio="xMidYMid meet">
									<g transform="translate(0.000000,744.000000) scale(0.100000,-0.100000)" stroke="none">
										<path d="M887 6552 c-482 -482 -877 -882 -877 -887 0 -13 5642 -5655 5655
										-5655 12 0 5645 5645 5645 5657 0 12 -1753 1763 -1765 1763 -5 0 -882 -872
										-1947 -1937 l-1938 -1938 -1938 1938 c-1065 1065 -1942 1937 -1947 1937 -6 0
										-405 -395 -888 -878z"/>
									</g>
								</svg>
							</p>
							<div id="changePassCont">
								<div id="newPassCont">
									<label for="newPassword" id="newPasswordLabel">New Password</label>
									<input type="password" onkeyup="validateChangePassFields(event)" placeholder="New Password" autocomplete="off" id="newPasswordField">
									<button id="newPassShowButton" class="passwordShowButton notSelectable" onclick="newPassFieldShowToggle()">
										<div class="showPassImage" id="newPassImage"></div>
									</button>
									<p id="newPasswordError" class="inputErrorText">This field is required.</p>
								</div>
								<div id="innerConfirmPassCont">
									<div id="confirmPassCont">
										<label for="confirmPassword" id="confirmPasswordLabel">Confirm Password</label>
										<input type="password" onkeyup="validateChangePassFields(event)" placeholder="Confirm Password" autocomplete="off" id="confirmPasswordField">
										<button id="confirmPassShowButton" class="passwordShowButton notSelectable" onclick="confirmPassFieldShowToggle()">
											<div class="showPassImage" id="confirmPassImage"></div>
										</button>
										<p id="confirmPasswordError" class="inputErrorText">This field is required.</p>
									</div>
									<div class="sendEmailButtonCont" style="height: 40px;">
										<button id="sendChangePasswordEmailButton" class="notSelectable" onclick="updatePassword()">Change Password</button>
									</div>
								</div>
							</div>
						</div>
						<div class="infoColumnRow">
							<p id="changeEmailText" class="notSelectable" onclick="changeEmailToggle()">
								Change Your Email
								<svg version="1.0" xmlns="http://www.w3.org/2000/svg" class="dropDownMenuArrow" id="changeEmailMenuArrow" width="10px" height="10px" viewBox="0 0 1131.000000 744.000000" preserveAspectRatio="xMidYMid meet">
									<g transform="translate(0.000000,744.000000) scale(0.100000,-0.100000)" stroke="none">
										<path d="M887 6552 c-482 -482 -877 -882 -877 -887 0 -13 5642 -5655 5655
										-5655 12 0 5645 5645 5645 5657 0 12 -1753 1763 -1765 1763 -5 0 -882 -872
										-1947 -1937 l-1938 -1938 -1938 1938 c-1065 1065 -1942 1937 -1947 1937 -6 0
										-405 -395 -888 -878z"/>
									</g>
								</svg>
							</p>
							<div id="changeEmailCont">
								<div id="newEmailCont" style="margin-bottom: 5px;">
									<label for="newEmail" id="newEmailLabel">New Email</label>
									<input type="text" onkeyup="validateChangeEmailField(event)" placeholder="New Email" autocomplete="off" id="newEmailField">
									<p id="newEmailError" class="inputErrorText">This field is required.</p>
								</div>
								<div id="confirmPasswordNewEmailCont">
									<label for="newEmailConfirmPassword" id="newEmailConfirmPasswordLabel">Password</label>
									<input type="password" placeholder="Password" autocomplete="off" id="confirmPasswordNewEmailField" onkeyup="validateNewEmailPasswordField(event)">
									<button id="newEmailConfirmPassShowButton" class="passwordShowButton notSelectable" onclick="newEmailConfirmPassFieldShowToggle()">
										<div class="showPassImage" id="newEmailConfirmPassImage"></div>
									</button>
									<p id="confirmPasswordNewEmailError" class="inputErrorText">This field is required.</p>
								</div>
								<div class="sendEmailButtonCont" style="height: 40px;">
									<button id="sendChangeEmailEmailButton" class="notSelectable" onclick="updateEmail()">Change Email</button>
								</div>
							</div>
						</div>
						<h1 class="notSelectable">Other Settings</h1>
						<div class="infoColumnRow">
							<label for="bioInput" onkeyup="saveBioValue()" class="rowInfo notSelectable" id="bioInfoLabel" style="vertical-align: top;">Biography (optional):</label>
							<div id="bioContainer">
								<textarea id="bioInput" placeholder="Share about yourself in 200 characters." maxlength="200" rows="10">' . htmlspecialchars($userBiography, ENT_QUOTES) . '</textarea>
							</div>
							<p id="bioInputError" class="inputErrorText"></p>
							<button id="saveBioButton" class="notSelectable" onclick="updateBio()">Save Changes</button>
						</div>
						<div class="infoRow">
							<label for="darkThemeSwitchCont" class="rowInfo notSelectable" id="darkThemeLabel">Dark Theme:</label>
							<span id="darkThemeSwitchCont" class="sliderSwitchCont" onclick="themeSwitch()">
								<span id="darkThemeSwitch"></span>
							</span>
						</div>
						<div class="infoColumnRow" style="padding-bottom: 0;">
							<label id="deleteAccountButtonLabel" for="deleteAccountButtonLabel" class="rowInfo notSelectable">Delete This Account:</label>
							<div id="deleteAccountButtonCont" style="height: 40px;">
								<button id="deleteAccountButton" class="notSelectable" onclick="deleteAccount()">Send Email</button>
							</div>
							<p id="deleteAccountError" class="inputErrorText"></p>
						</div>
					</div>
					<script src="web.js"></script>
				</div>
			</main>
			<footer>
			</footer>
		</body>
	</html>';
}
?>