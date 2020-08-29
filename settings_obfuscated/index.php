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
			SELECT emailVerificationTime, accountDeletionTime, emailVerified, biography
			FROM accountdetails
			WHERE accountID = " . $_SESSION["userID"] . "
			AND emailVerified = 0";
			if ($allNeededDetails = $mysqliConnection -> query($selectEmailVerifiedQuery)) {
				if ($allNeededDetails -> num_rows > 0) {
					if ($assocNeededDetails = $allNeededDetails -> fetch_assoc()) {
						$dbVerificationEmailLastSentTime = $assocNeededDetails["emailVerificationTime"];
						$dbDeletionEmailLastSentTime = $assocNeededDetails["accountDeletionTime"];
						$remainingVerificationCooldown = strtotime($dbVerificationEmailLastSentTime) > (time() - 120) ?
						"Please wait " . (strtotime($dbVerificationEmailLastSentTime) - time() + 120) . " more seconds!" : "";
						$remainingDeletionCooldown = strtotime($dbDeletionEmailLastSentTime) > (time() - 120) ?
						"Please wait " . (strtotime($dbDeletionEmailLastSentTime) - time() + 120) . " more seconds!" : "";
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
			<link id="stylesheetLink" rel="stylesheet" href="settingsDarkTheme.css">
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
									<g transform="translate(0.000000,744.000000) scale(0.100000,-0.100000)" stroke="none" fill="#ffffff">
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
									<g transform="translate(0.000000,744.000000) scale(0.100000,-0.100000)" stroke="none" fill="#ffffff">
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
									<g transform="translate(0.000000,744.000000) scale(0.100000,-0.100000)" stroke="none" fill="#ffffff">
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
					<script>
						"use strict";
						const emailTest = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
						const passEasyTest = /(strong(er)*)*(complex)*(password[0-9]{0,3})|(12345678(9)*)/gi;
						const userDirtRegexp = /[^a-z0-9._]/gi;
						const refMenuButton = document.querySelector("#menuToggle");
						const refSideNav = document.querySelector("#sidenav");
						const refThemeSwitch = document.querySelector("#darkThemeSwitch");
						const refStyleSheetLink = document.querySelector("#stylesheetLink");
						const refSettingsInfoCont = document.querySelector("#settingsInfoCont");
						const refSelectedTabLine = document.querySelector("#selectedTabLine");
						const refAccountButton = document.querySelector("#accountButton");
						const refSecurityButton = document.querySelector("#securityButton");
						const refChangePassButton = document.querySelector("#changePasswordText");
						var checkEmailVerification;
						var checkUser;
						var checkUserSubmit;
						var checkPassword;
						var checkEmail;
						var checkEmailSubmit;
						var checkBio;
						var checkDeleteAccount;
						var checkTwoFactorAuth;
						var changeUserOpenedToggle = false;
						var changePassOpenedToggle = false;
						var changeEmailOpenedToggle = false;
						var emailVerificationEmailSent = false;
						var changeUserEmailSent = false;
						var changePassEmailSent = false;
						var changeEmailEmailSent = false;
						var accountDeletionEmailSent = false;
						var emailVerificationButtonText = "Send Email";
						var changeUserButtonText = "Change Username";
						var changePassButtonText = "Change Password";
						var changeEmailButtonText = "Change Email";
						var accountDeletionButtonText = "Send Email";
						var changeBioButtonText = "Save Changes"
						var emailVerificationError = "";
						var newUsernameChangeError = "This field is required.";
						var newPasswordChangeError = "This field is required.";
						var confirmPasswordChangeError = "This field is required.";
						var newEmailChangeError = "This field is required.";
						var newEmailConfirmPasswordChangeError = "This field is required.";
						var bioInputError = "";
						var accountDeletionError = "";
						var newUsernameText = "";
						var newPasswordText = "";
						var confirmPasswordText = "";
						var newEmailText = "";
						var newEmailConfirmPasswordText = "";
						var bioText = "";
						var theme;
						refMenuButton.style.filter = "brightness(100%)";
						refMenuButton.style.cursor = "pointer";
						function getCookie(cookieName) {
							var name = cookieName + "=";
							var decodedCookie = decodeURIComponent(document.cookie);
							var cookieParts = decodedCookie.split(";");
							for(var i = 0; i < cookieParts.length; i++) {
								var eachCookiePart = cookieParts[i];
								if (eachCookiePart.charAt(0) == " ") {
									eachCookiePart = eachCookiePart.substring(1);
								}
								if (eachCookiePart.indexOf(name) == 0) {
									return eachCookiePart.substring(name.length);
								}
							}
							return "";
						}
						if (getCookie("darktheme") !== "false") {
							const refDarkThemeSwitchCont = document.querySelector("#darkThemeSwitchCont");
							const refDarkThemeSwitch = document.querySelector("#darkThemeSwitch");
							refDarkThemeSwitchCont.style.backgroundColor = "rgba(255, 255, 255, 0.2)";
							refDarkThemeSwitch.style.left = "23px";
							theme = "darkTheme";
						} else {
							theme = "lightTheme";
						}
						function changeUserToggle() {
							const refNewUserLabel = document.querySelector("#newUsernameLabel");
							const refNewUserField = document.querySelector("#newUsernameField");
							const refNewUserCont = document.querySelector("#newUserCont");
							const refNewUserSendEmailButton = document.querySelector("#sendChangeUsernameEmailButton");
							if (changeUserOpenedToggle === false) {
								if (!document.querySelector("#changeUsernameStyles")) {
									var addChangeUserStyles = document.createElement("style");
									document.head.appendChild(addChangeUserStyles);
									var addChangeUserSheet = addChangeUserStyles.sheet;
									addChangeUserStyles.id = "changeUsernameStyles";
									addChangeUserSheet.insertRule(`
									#changeUsernameText {
										margin-bottom: 15px !important;
									}`);
									addChangeUserSheet.insertRule(`
									#changeUserCont {
										height: ${refNewUserCont.getBoundingClientRect()["height"] + 5 + refNewUserSendEmailButton.getBoundingClientRect()["height"]}px !important;
									}`);
									addChangeUserSheet.insertRule(`
									#changeUserMenuArrow {
										transform: rotate(180deg);
									}`);
								}
								changeUserOpenedToggle = true;
							} else {
								if (document.querySelector("#changeUsernameStyles")) {
									const refChangeUserStyles = document.querySelector("#changeUsernameStyles");
									refChangeUserStyles.parentNode.removeChild(refChangeUserStyles);
								}
								changeUserOpenedToggle = false;
							}
						}
						function changePassToggle() {
							const refNewPassLabel = document.querySelector("#newPasswordLabel");
							const refNewPassField = document.querySelector("#newPasswordField");
							const refConfirmPassLabel = document.querySelector("#confirmPasswordLabel");
							const refConfirmPassField = document.querySelector("#confirmPasswordField");
							const refNewPassCont = document.querySelector("#newPassCont");
							const refInnerConfirmPassCont = document.querySelector("#innerConfirmPassCont");
							const refNewPassSendEmailButton = document.querySelector("#sendChangePasswordEmailButton");
							if (changePassOpenedToggle === false) {
								if (!document.querySelector("#changePasswordStyles")) {
									var addChangePassStyles = document.createElement("style");
									document.head.appendChild(addChangePassStyles);
									var addChangePassSheet = addChangePassStyles.sheet;
									addChangePassStyles.id = "changePasswordStyles";
									addChangePassSheet.insertRule(`
									#changePasswordText {
										margin-bottom: 15px !important;
									}`);
									addChangePassSheet.insertRule(`
									#changePassMenuArrow {
										transform: rotate(180deg);
									}`);
									addChangePassSheet.insertRule(`
									#changePassCont {
										height: ${refNewPassCont.getBoundingClientRect()["height"] + refInnerConfirmPassCont.getBoundingClientRect()["height"]}px !important;
									}`);
								}
								changePassOpenedToggle = true;
							} else {
								if (document.querySelector("#changePasswordStyles")) {
									const refChangePassStyles = document.querySelector("#changePasswordStyles");
									refChangePassStyles.parentNode.removeChild(refChangePassStyles);
								}
								changePassOpenedToggle = false;
							}
						}
						function changeEmailToggle() {
							const refNewEmailLabel = document.querySelector("#newEmailLabel");
							const refNewEmailField = document.querySelector("#newEmailField");
							const refNewEmailCont = document.querySelector("#newEmailCont");
							const refNewEmailConfirmPassCont = document.querySelector("#confirmPasswordNewEmailCont");
							const refNewEmailSendEmailButton = document.querySelector("#sendChangeEmailEmailButton");
							if (changeEmailOpenedToggle === false) {
								if (!document.querySelector("#changeEmailStyles")) {
									var addChangeEmailStyles = document.createElement("style");
									document.head.appendChild(addChangeEmailStyles);
									var addChangeEmailSheet = addChangeEmailStyles.sheet;
									addChangeEmailStyles.id = "changeEmailStyles";
									addChangeEmailSheet.insertRule(`
									#changeEmailText {
										margin-bottom: 15px !important;
									}`);
									addChangeEmailSheet.insertRule(`
									#changeEmailCont {
										height: ${refNewEmailCont.getBoundingClientRect()["height"] + refNewEmailConfirmPassCont.getBoundingClientRect()["height"] + 5 + refNewEmailSendEmailButton.getBoundingClientRect()["height"]}px !important;
									}`);
									addChangeEmailSheet.insertRule(`
									#changeEmailMenuArrow {
										transform: rotate(180deg);
									}`);
								}
								changeEmailOpenedToggle = true;
							} else {
								if (document.querySelector("#changeEmailStyles")) {
									const refChangeEmailStyles = document.querySelector("#changeEmailStyles");
									refChangeEmailStyles.parentNode.removeChild(refChangeEmailStyles);
								}
								changeEmailOpenedToggle = false;
							}
						}
						function newPassFieldShowToggle() {
							const newPassField = document.querySelector("#newPasswordField");
							if (newPassField.type === "password") {
								if (!document.querySelector("#newPasswordStyles")) {
									var createdToggleFieldStyle = document.createElement("style");
									document.head.appendChild(createdToggleFieldStyle);
									var createdToggleFieldSheet = createdToggleFieldStyle.sheet;
									createdToggleFieldStyle.id = "newPasswordStyles"
									createdToggleFieldSheet.insertRule(`
									#newPassImage {
										background-image: url("../Assets/${theme}/PasswordVisible.png") !important;
										width: calc(1000 / 600 * 15px) !important;
									}`);
								}
								newPassField.type = "text";
							} else {
								if (document.querySelector("#newPasswordStyles")) {
									const newPassSheet = document.querySelector("#newPasswordStyles");
									newPassSheet.parentNode.removeChild(newPassSheet);
								}
								newPassField.type = "password";
							}
						}
						function confirmPassFieldShowToggle() {
							const confirmPassField = document.querySelector("#confirmPasswordField");
							if (confirmPassField.type === "password") {
								if (!document.querySelector("#confirmPasswordStyles")) {
									var createdToggleFieldStyle = document.createElement("style");
									document.head.appendChild(createdToggleFieldStyle);
									var createdToggleFieldSheet = createdToggleFieldStyle.sheet;
									createdToggleFieldStyle.id = "confirmPasswordStyles";
									createdToggleFieldSheet.insertRule(`
									#confirmPassImage {
										background-image: url("../Assets/${theme}/PasswordVisible.png");
										width: calc(1000 / 600 * 15px);
									}`);
								}
								confirmPassField.type = "text";
							} else {
								if (document.querySelector("#confirmPasswordStyles")) {
									const confirmPassSheet = document.querySelector("#confirmPasswordStyles");
									confirmPassSheet.parentNode.removeChild(confirmPassSheet);
								}
								confirmPassField.type = "password";
							}
						}
						function newEmailConfirmPassFieldShowToggle() {
							const newEmailConfirmPassField = document.querySelector("#confirmPasswordNewEmailField");
							if (newEmailConfirmPassField.type === "password") {
								if (!document.querySelector("#newEmailConfirmPasswordStyles")) {
									var createdToggleFieldStyle = document.createElement("style");
									document.head.appendChild(createdToggleFieldStyle);
									var createdToggleFieldSheet = createdToggleFieldStyle.sheet;
									createdToggleFieldStyle.id = "newEmailConfirmPasswordStyles";
									createdToggleFieldSheet.insertRule(`
									#newEmailConfirmPassImage {
										background-image: url("../Assets/${theme}/PasswordVisible.png");
										width: calc(1000 / 600 * 15px);
									}`);
								}
								newEmailConfirmPassField.type = "text";
							} else {
								if (document.querySelector("#newEmailConfirmPasswordStyles")) {
									const newEmailConfirmPasswordSheet = document.querySelector("#newEmailConfirmPasswordStyles");
									newEmailConfirmPasswordSheet.parentNode.removeChild(newEmailConfirmPasswordSheet);
								}
								newEmailConfirmPassField.type = "password";
							}
						}
						function validateChangeUserField(event) {
							const refNewUserField = document.querySelector("#newUsernameField");
							const refNewUserError = document.querySelector("#newUsernameError");
							clearTimeout(checkUser);
							clearTimeout(checkUserSubmit);
							if (userDirtRegexp.test(refNewUserField.value) === true) {
								refNewUserError.innerHTML = "Username may only contain letters, numbers, . and _.";
								newUsernameChangeError = "Username may only contain letters, numbers, . and _.";
							}
							else if (refNewUserField.value.trim().length === 0) {
								refNewUserError.innerHTML = "This field is required.";
								newUsernameChangeError = "This field is required.";
							}
							else if (refNewUserField.value.length < 3 || refNewUserField.value.length > 20) {
								refNewUserError.innerHTML = "Username may only contain between 3-20 characters.";
								newUsernameChangeError = "Username may only contain between 3-20 characters.";
							} else if (event && event.keyCode === 13) {
								updateUsername();
							} else {
								newUsernameChangeError = "";
								refNewUserError.innerHTML = "";
								checkUser = setTimeout(function() {
									const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
									xhr.open("POST", "../UsernameTaken.php", true);
									xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
									xhr.responseType = "text";
									xhr.onload = function() {
										newUsernameChangeError = xhr.responseText
										refNewUserError.innerHTML = xhr.responseText;
									}
									xhr.send("username=" + encodeURIComponent(refNewUserField.value));
								}, 350);
							}
						}
						function validateChangePassFields(event) {
							const refNewPassField = document.querySelector("#newPasswordField");
							const refNewPassError = document.querySelector("#newPasswordError");
							const refConfirmPassField = document.querySelector("#confirmPasswordField");
							const refConfirmPassError = document.querySelector("#confirmPasswordError");
							if (refNewPassField.value.trim().length === 0) {
								newPasswordChangeError = "This field is required.";
								refNewPassError.innerHTML = "This field is required.";
							}
							else if (refNewPassField.value.length < 8) {
								newPasswordChangeError = "Password must contain 8 or more characters.";
								refNewPassError.innerHTML = "Password must contain 8 or more characters.";
							}
							else if (refNewPassField.value.replace(passEasyTest, "") === "") {
								newPasswordChangeError = "Please create a stronger password.";
								refNewPassError.innerHTML = "Please create a stronger password.";
							}
							else if (refConfirmPassField.value !== refNewPassField.value) {
								newPasswordChangeError = "";
								refNewPassError.innerHTML = "";
								newPasswordChangeError = "Passwords do not match.";
								refConfirmPassError.innerHTML = "Passwords do not match.";
							} else {
								newPasswordChangeError = "";
								refNewPassError.innerHTML = "";
							}
							if (refConfirmPassField.value.trim().length === 0) {
								confirmPasswordChangeError = "This field is required.";
								refConfirmPassError.innerHTML = "This field is required.";
							}
							else if (refConfirmPassField.value !== refNewPassField.value) {
								confirmPasswordChangeError = "Passwords do not match.";
								refConfirmPassError.innerHTML = "Passwords do not match.";
							} else {
								confirmPasswordChangeError = "";
								refConfirmPassError.innerHTML = "";
							}
							if (event && event.keyCode === 13) {
								if (newPasswordChangeError === "" && confirmPasswordChangeError === "") {
									updatePassword();
								}
							}
						}
						function validateChangeEmailField(event) {
							const refEmailField = document.querySelector("#newEmailField");
							const refEmailError = document.querySelector("#newEmailError");
							const refNewEmailConfirmPassField = document.querySelector("#confirmPasswordNewEmailField");
							clearTimeout(checkEmail);
							if (refEmailField.value.trim().length === 0) {
								refEmailError.innerHTML = "This field is required.";
								newEmailChangeError = "This field is required.";
							}
							else if (emailTest.test(refEmailField.value) === false) {
								refEmailError.innerHTML = "Please enter a valid email.";
								newEmailChangeError = "Please enter a valid email.";
							}
							else if (event.keyCode === 13 && refNewEmailConfirmPassField.value.length > 0) {
								updateEmail();
							} else {
								refEmailError.innerHTML = "";
								newEmailChangeError = "";
								checkEmail = setTimeout(function() {
									const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
									xhr.open("POST", "../EmailTaken.php", true);
									xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
									xhr.responseType = "text";
									xhr.onload = function() {
										newEmailChangeError = xhr.responseText;
										refEmailError.innerHTML = xhr.responseText;
									}
									xhr.send("email=" + encodeURIComponent(refEmailField.value));
								}, 350);
							}
						}
						function validateNewEmailPasswordField(event) {
							const refEmailField = document.querySelector("#newEmailField");
							const refNewEmailConfirmPassField = document.querySelector("#confirmPasswordNewEmailField");
							const refNewEmailConfirmPassError = document.querySelector("#confirmPasswordNewEmailError");
							if (refNewEmailConfirmPassField.value.trim().length === 0) {
								refNewEmailConfirmPassError.innerHTML = "This field is required.";
								newEmailConfirmPasswordChangeError = "This field is required.";
							}
							else if (event.keyCode === 13 && refEmailField.value.length > 0) {
								updateEmail();
							} else {
								refNewEmailConfirmPassError.innerHTML = "";
								newEmailConfirmPasswordChangeError = "";
							}
						}
						function sendEmailVerification() {
							clearTimeout(checkEmailVerification);
							if (emailVerificationEmailSent === false) {
								checkEmailVerification = setTimeout(function() {
									const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
									emailVerificationEmailSent = true;
									xhr.open("POST", "../sendVerifEmail.php", true);
									xhr.responseType = "json";
									xhr.onload = function() {
										emailVerificationError = xhr.response["message"];
										if (document.querySelector("#verifyEmailError")) {
											const refVerifyEmailError = document.querySelector("#verifyEmailError");
											refVerifyEmailError.innerHTML = emailVerificationError;
										}
										var leftCooldown = xhr.response["leftoverCooldown"];
										if (leftCooldown <= 1) {
											emailVerificationButtonText = "Re-send Email";
											if (document.querySelector("#verifyEmailButton")) {
												const refVerifyEmailButton = document.querySelector("#verifyEmailButton");
												refVerifyEmailButton.innerHTML = emailVerificationButtonText;
											}
										} else {
											emailVerificationButtonText = "Re-send Email (" + leftCooldown + ")";
											leftCooldown--;
											if (document.querySelector("#verifyEmailButton")) {
												const refVerifyEmailButton = document.querySelector("#verifyEmailButton");
												refVerifyEmailButton.innerHTML = emailVerificationButtonText;
											}
											for (var i = 1; i <= xhr.response["leftoverCooldown"]; i++) {
												setTimeout(function() {
													if (leftCooldown === 0) {
														emailVerificationButtonText = "Re-send Email";
														emailVerificationEmailSent = false;
													} else {
														emailVerificationButtonText = "Re-send Email (" + leftCooldown + ")";
														leftCooldown--;
													}
													if (leftCooldown === xhr.response["leftoverCooldown"] - 3) {
														emailVerificationError = "";
													}
													if (document.querySelector("#verifyEmailButton")) {
														const refVerifyEmailButton = document.querySelector("#verifyEmailButton");
														refVerifyEmailButton.innerHTML = emailVerificationButtonText;
													}
													if (document.querySelector("#verifyEmailError")) {
														const refVerifyEmailError = document.querySelector("#verifyEmailError");
														refVerifyEmailError.innerHTML = emailVerificationError;
													}
												}, 1000 * i);
											}
										}
									}
									xhr.send();
								}, 350);
							}
						}
						function updateUsername() {
							const refNewUserField = document.querySelector("#newUsernameField");
							const refNewUserError = document.querySelector("#newUsernameError");
							clearTimeout(checkUserSubmit);
							checkUserSubmit = setTimeout(function() {
								if (userDirtRegexp.test(refNewUserField.value) === false && refNewUserField.value.length >= 3 && refNewUserField.value.length <= 20) {
									const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
									xhr.open("POST", "updateAccountDetails.php", true);
									xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
									xhr.responseType = "json";
									xhr.onload = function() {
										newUsernameChangeError = xhr.response["message"];
										var leftCooldown = xhr.response["leftoverCooldown"];
										if (document.querySelector("#newUsernameError")) {
											const refNewUsernameError = document.querySelector("#newUsernameError");
											refNewUsernameError.innerHTML = newUsernameChangeError;
										}
										if (leftCooldown <= 1) {
											changeUserButtonText = "Re-send Email";
											if (document.querySelector("#sendChangeUsernameEmailButton")) {
												const refChangeUserButton = document.querySelector("#sendChangeUsernameEmailButton");
												refChangeUserButton.innerHTML = changeUserButtonText;
											}
										} else {
											changeUserButtonText = "Re-send Email (" + leftCooldown + ")";
											if (document.querySelector("#sendChangeUsernameEmailButton")) {
												const refChangeUserButton = document.querySelector("#sendChangeUsernameEmailButton");
												refChangeUserButton.innerHTML = changeUserButtonText;
											}
											leftCooldown--;
											for (var i = 1; i <= xhr.response["leftoverCooldown"]; i++) {
												setTimeout(function() {
													if (leftCooldown === 0) {
														changeUserButtonText = "Re-send Email";
														changeUserEmailSent = false;
													} else {
														changeUserButtonText = "Re-send Email (" + leftCooldown + ")";
														leftCooldown--;
													}
													if (leftCooldown === xhr.response["leftoverCooldown"] - 3) {
														newUsernameChangeError = "";
													}
													if (document.querySelector("#sendChangeUsernameEmailButton")) {
														const refChangeUserButton = document.querySelector("#sendChangeUsernameEmailButton");
														refChangeUserButton.innerHTML = changeUserButtonText;
													}
													if (document.querySelector("#newUsernameError")) {
														const refNewUsernameError = document.querySelector("#newUsernameError");
														refNewUsernameError.innerHTML = newUsernameChangeError;
													}
												}, 1000 * i);
											}
										}
									}
									xhr.send("type=1&content=" + encodeURIComponent(refNewUserField.value));
								}
							}, 350);
						}
						function updatePassword() {
							const refNewPassField = document.querySelector("#newPasswordField");
							const refNewPassError = document.querySelector("#newPasswordError");
							const refConfirmPassField = document.querySelector("#confirmPasswordField");
							const refConfirmPassError = document.querySelector("#confirmPasswordError");
							clearTimeout(checkPassword);
							checkPassword = setTimeout(function() {
								if (refNewPassField.value.length >= 8 && refNewPassField.value.replace(passEasyTest, "") !== "" && refConfirmPassField.value === refNewPassField.value) {
									const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
									xhr.open("POST", "updateAccountDetails.php", true);
									xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
									xhr.responseType = "json";
									xhr.onload = function() {
										newPasswordChangeError = xhr.response["newPassError"] ? xhr.response["newPassError"] : "";
										confirmPasswordChangeError = xhr.response["message"];
										var leftCooldown = xhr.response["leftoverCooldown"];
										if (document.querySelector("#newPasswordError")) {
											const refNewPasswordError = document.querySelector("#newPasswordError");
											refNewPasswordError.innerHTML = newPasswordChangeError;
										}
										if (document.querySelector("#confirmPasswordError")) {
											const refConfirmPasswordError = document.querySelector("#confirmPasswordError");
											refConfirmPassError.innerHTML = confirmPasswordChangeError;
										}
										if (leftCooldown <= 1) {
											changePassButtonText = "Re-send Email";
											if (document.querySelector("#sendChangePasswordEmailButton")) {
												const refChangePassButton = document.querySelector("#sendChangePasswordEmailButton");
												refChangePassButton.innerHTML = changePassButtonText;
											}
										} else {
											changePassButtonText = "Re-send Email (" + leftCooldown + ")";
											if (document.querySelector("#sendChangePasswordEmailButton")) {
												const refChangePassButton = document.querySelector("#sendChangePasswordEmailButton");
												refChangePassButton.innerHTML = changePassButtonText;
											}
											leftCooldown--;
											for (var i = 1; i <= xhr.response["leftoverCooldown"]; i++) {
												setTimeout(function() {
													if (leftCooldown === 0) {
														changePassButtonText = "Re-send Email";
														changePassEmailSent = false;
													} else {
														changePassButtonText = "Re-send Email (" + leftCooldown + ")";
														leftCooldown--;
													}
													if (leftCooldown === xhr.response["leftoverCooldown"] - 3) {
														confirmPasswordChangeError = "";
													}
													if (document.querySelector("#sendChangePasswordEmailButton")) {
														const refChangePassButton = document.querySelector("#sendChangePasswordEmailButton");
														refChangePassButton.innerHTML = changePassButtonText;
													}
													if (document.querySelector("#newPasswordError")) {
														const refNewPasswordError = document.querySelector("#newPasswordError");
														refNewPasswordError.innerHTML = newPasswordChangeError;
													}
													if (document.querySelector("#confirmPasswordError")) {
														const refConfirmPasswordError = document.querySelector("#confirmPasswordError");
														refConfirmPassError.innerHTML = confirmPasswordChangeError;
													}
												}, 1000 * i);
											}
										}
									}
									xhr.send("type=2&content=" + encodeURIComponent(refNewPassField.value));
								}
							}, 350);
						}
						function updateEmail() {
							const refNewEmailField = document.querySelector("#newEmailField");
							const refNewEmailError = document.querySelector("#newEmailError");
							const refNewEmailConfirmPassField = document.querySelector("#confirmPasswordNewEmailField");
							const refNewEmailConfirmPassError = document.querySelector("#confirmPasswordNewEmailError");
							clearTimeout(checkEmailSubmit);
							checkEmailSubmit = setTimeout(function() {
								if (emailTest.test(refNewEmailField.value) === true) {
									const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
									xhr.open("POST", "updateAccountDetails.php", true);
									xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
									xhr.responseType = "json";
									xhr.onload = function() {
										newEmailChangeError = xhr.response["newEmailError"] ? xhr.response["newEmailError"] : "";
										newEmailConfirmPasswordChangeError = xhr.response["message"];
										var leftCooldown = xhr.response["leftoverCooldown"];
										if (document.querySelector("#newEmailError")) {
											const refNewEmailError = document.querySelector("#newEmailError");
											refNewEmailError.innerHTML = newEmailChangeError;
										}
										if (document.querySelector("#confirmPasswordNewEmailError")) {
											const refNewEmailConfirmPasswordError = document.querySelector("#confirmPasswordNewEmailError");
											refNewEmailConfirmPassError.innerHTML = newEmailConfirmPasswordChangeError;
										}
										if (leftCooldown <= 1) {
											changeEmailButtonText = "Re-send Email";
											if (document.querySelector("#sendChangeEmailEmailButton")) {
												const refChangeEmailButton = document.querySelector("#sendChangeEmailEmailButton");
												refChangeEmailButton.innerHTML = changeEmailButtonText;
											}
										} else {
											changeEmailButtonText = "Re-send Email (" + leftCooldown + ")";
											if (document.querySelector("#sendChangeEmailEmailButton")) {
												const refChangeEmailButton = document.querySelector("#sendChangeEmailEmailButton");
												refChangeEmailButton.innerHTML = changeEmailButtonText;
											}
											leftCooldown--;
											for (var i = 1; i <= xhr.response["leftoverCooldown"]; i++) {
												setTimeout(function() {
													if (leftCooldown === 0) {
														changeEmailButtonText = "Re-send Email";
														changeEmailEmailSent = false;
													} else {
														changeEmailButtonText = "Re-send Email (" + leftCooldown + ")";
														leftCooldown--;
													}
													if (leftCooldown === xhr.response["leftoverCooldown"] - 3) {
														newEmailConfirmPasswordChangeError = "";
													}
													if (document.querySelector("#sendChangeEmailEmailButton")) {
														const refChangeEmailButton = document.querySelector("#sendChangeEmailEmailButton");
														refChangeEmailButton.innerHTML = changeEmailButtonText;
													}
													if (document.querySelector("#newEmailError")) {
														const refNewEmailError = document.querySelector("#newEmailError");
														refNewEmailError.innerHTML = newEmailChangeError;
													}
													if (document.querySelector("#confirmPasswordNewEmailError")) {
														const refNewEmailConfirmPasswordError = document.querySelector("#confirmPasswordNewEmailError");
														refNewEmailConfirmPassError.innerHTML = newEmailConfirmPasswordChangeError;
													}
												}, 1000 * i);
											}
										}
									}
									xhr.send("type=3&content=" + encodeURIComponent(refNewEmailField.value) + "&content2=" + encodeURIComponent(refNewEmailConfirmPassField.value));
								}
							}, 350);
						}
						function updateBio() {
							const refBioInput = document.querySelector("#bioInput");
							const refBioError = document.querySelector("#bioInputError");
							clearTimeout(checkBio);
							checkBio = setTimeout(function() {
								const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
								xhr.open("POST", "updateAccountDetails.php", true);
								xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
								xhr.responseType = "json";
								xhr.onload = function() {
									bioInputError = xhr.response["message"];
									refBioError.innerHTML = xhr.response["message"];
								}
								xhr.send("type=4&content=" + encodeURIComponent(refBioInput.value));
							}, 350);
						}
						function deleteAccount() {
							clearTimeout(checkDeleteAccount);
							if (accountDeletionEmailSent === false) {
								checkDeleteAccount = setTimeout(function() {
									const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
									accountDeletionEmailSent = true;
									xhr.open("POST", "../sendDeletionEmail.php", true);
									xhr.responseType = "json";
									xhr.onload = function() {
										accountDeletionError = xhr.response["message"];
										var leftCooldown = xhr.response["leftoverCooldown"];
										if (document.querySelector("#deleteAccountError")) {
											const refDeleteAccountError = document.querySelector("#deleteAccountError");
											refDeleteAccountError.innerHTML = accountDeletionError;
										}
										if (leftCooldown <= 1) {
											accountDeletionButtonText = "Re-send Email";
											if (document.querySelector("#deleteAccountButton")) {
												const refDeleteAccountButton = document.querySelector("#deleteAccountButton");
												refDeleteAccountButton.innerHTML = accountDeletionButtonText;
											}
										} else {
											accountDeletionButtonText = "Re-send Email (" + leftCooldown + ")";
											if (document.querySelector("#deleteAccountButton")) {
												const refDeleteAccountButton = document.querySelector("#deleteAccountButton");
												refDeleteAccountButton.innerHTML = accountDeletionButtonText;
											}
											leftCooldown--;
											for (var i = 1; i <= xhr.response["leftoverCooldown"]; i++) {
												setTimeout(function() {
													if (leftCooldown === 0) {
														accountDeletionButtonText = "Re-send Email";
														accountDeletionEmailSent = false;
													} else {
														accountDeletionButtonText = "Re-send Email (" + leftCooldown + ")";
														leftCooldown--;
													}
													if (leftCooldown === xhr.response["leftoverCooldown"] - 3) {
														accountDeletionError = "";
													}
													if (document.querySelector("#deleteAccountButton")) {
														const refDeleteAccountButton = document.querySelector("#deleteAccountButton");
														refDeleteAccountButton.innerHTML = accountDeletionButtonText;
													}
													if (document.querySelector("#deleteAccountError")) {
														const refDeleteAccountError = document.querySelector("#deleteAccountError");
														refDeleteAccountError.innerHTML = accountDeletionError;
													}
												}, 1000 * i);
											}
										}
									}
									xhr.send();
								}, 350);
							}
						}
						function themeSwitch() {
							const refDarkThemeSwitchCont = document.querySelector("#darkThemeSwitchCont");
							const refDarkThemeSwitch = document.querySelector("#darkThemeSwitch");
							refDarkThemeSwitchCont.classList.toggle("switchedOnSwitchCont");
							refDarkThemeSwitch.classList.toggle("switchedOnSwitch");
							if (getCookie("darktheme") !== "false") {
								document.cookie = "darktheme=false; expires=31 Dec 10000 12:00:00 UTC; path=/";
								theme = "lightTheme";
								if (refStyleSheetLink.href !== "https://streetor.sg/settings/settingsLightTheme.css") {
									refStyleSheetLink.setAttribute("href", "settingsLightTheme.css");
								}
							} else {
								document.cookie = "darktheme=true; expires=31 Dec 10000 12:00:00 UTC; path=/";
								theme = "darkTheme";
								if (refStyleSheetLink.href !== "https://streetor.sg/settings/settingsDarkTheme.css") {
									refStyleSheetLink.setAttribute("href", "settingsDarkTheme.css");
								}
							}
						}
						function twoFactorAuthSwitch() {
							clearTimeout(checkTwoFactorAuth);
							document.querySelector("#twoFactorAuthSwitchCont").classList.toggle("switchedOnSwitchCont");
							document.querySelector("#twoFactorAuthSwitch").classList.toggle("switchedOnSwitch");
							checkTwoFactorAuth = setTimeout(function() {
								const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
								xhr.open("POST", "updateAccountDetails.php", true);
								xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
								xhr.send(encodeURI("type=5&content=null"));
							}, 350);
						}
						refAccountButton.addEventListener("click", function(triggered) {
							if (triggered.button === 0 && refAccountButton.classList.contains("selectedTab") === false) {
								refAccountButton.classList.toggle("selectedTab");
								refSecurityButton.classList.toggle("selectedTab");
								refSelectedTabLine.style.top = 0;
								refSettingsInfoCont.innerHTML = `
								<h1 class="topHeaderInfo notSelectable">Details</h1>
								<div class="infoRow" id="usernameRow">
									<p class="rowInfo notSelectable" id="usernameRowInfo">Username:</p>
								</div>
								<div class="infoRow" id="emailRow">
									<p class="rowInfo notSelectable" id="emailRowInfo">Email:</p>
								</div>
								<div class="infoColumnRow" id="emailVerifiedRow" style="padding-bottom: 0;">
									<p class="rowInfo notSelectable" id="emailVerifiedRowInfo" style="margin-bottom: 10px;">Email Verified: </p>
								</div>
								<h1 class="notSelectable">Change Details</h1>
								<div class="infoColumnRow">
									<p id="changeUsernameText" class="notSelectable" onclick="changeUserToggle()">
										Change Your Username
										<svg version="1.0" xmlns="http://www.w3.org/2000/svg" class="dropDownMenuArrow" id="changeUserMenuArrow" width="10px" height="10px" viewBox="0 0 1131.000000 744.000000" preserveAspectRatio="xMidYMid meet">
											<g transform="translate(0.000000,744.000000) scale(0.100000,-0.100000)" stroke="none" fill="#ffffff">
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
											<input value="${newUsernameText}" type="text" onkeyup="validateChangeUserField(event)" placeholder="New Username" autocomplete="off" id="newUsernameField">
											<p id="newUsernameError" class="inputErrorText">${newUsernameChangeError}</p>
										</div>
										<div class="sendEmailButtonCont" style="height: 40px;">
											<button id="sendChangeUsernameEmailButton" class="notSelectable" onclick="updateUsername()">${changeUserButtonText}</button>
										</div>
									</div>
								</div>
								<div class="infoColumnRow">
									<p id="changePasswordText" class="notSelectable" onclick="changePassToggle()">
										Change Your Password
										<svg version="1.0" xmlns="http://www.w3.org/2000/svg" class="dropDownMenuArrow" id="changePassMenuArrow" width="10px" height="10px" viewBox="0 0 1131.000000 744.000000" preserveAspectRatio="xMidYMid meet">
											<g transform="translate(0.000000,744.000000) scale(0.100000,-0.100000)" stroke="none" fill="#ffffff">
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
											<input value="${newPasswordText}" type="password" onkeyup="validateChangePassFields(event)" placeholder="New Password" autocomplete="off" id="newPasswordField">
											<button id="newPassShowButton" class="passwordShowButton notSelectable" onclick="newPassFieldShowToggle()">
												<div class="showPassImage" id="newPassImage"></div>
											</button>
											<p id="newPasswordError" class="inputErrorText">${newPasswordChangeError}</p>
										</div>
										<div id="innerConfirmPassCont">
											<div id="confirmPassCont">
												<label for="confirmPassword" id="confirmPasswordLabel">Confirm Password</label>
												<input value="${confirmPasswordText}" type="password" onkeyup="validateChangePassFields(event)" placeholder="Confirm Password" autocomplete="off" id="confirmPasswordField">
												<button id="confirmPassShowButton" class="passwordShowButton notSelectable" onclick="confirmPassFieldShowToggle()">
													<div class="showPassImage" id="confirmPassImage"></div>
												</button>
												<p id="confirmPasswordError" class="inputErrorText">${confirmPasswordChangeError}</p>
											</div>
											<div class="sendEmailButtonCont" style="height: 40px;">
												<button id="sendChangePasswordEmailButton" class="notSelectable" onclick="updatePassword()">${changePassButtonText}</button>
											</div>
										</div>
									</div>
								</div>
								<div class="infoColumnRow">
									<p id="changeEmailText" class="notSelectable" onclick="changeEmailToggle()">
										Change Your Email
										<svg version="1.0" xmlns="http://www.w3.org/2000/svg" class="dropDownMenuArrow" id="changeEmailMenuArrow" width="10px" height="10px" viewBox="0 0 1131.000000 744.000000" preserveAspectRatio="xMidYMid meet">
											<g transform="translate(0.000000,744.000000) scale(0.100000,-0.100000)" stroke="none" fill="#ffffff">
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
											<input value="${newEmailText}" type="text" onkeyup="validateChangeEmailField(event)" placeholder="New Email" autocomplete="off" id="newEmailField">
											<p id="newEmailError" class="inputErrorText">${newEmailChangeError}</p>
										</div>
										<div id="confirmPasswordNewEmailCont">
											<label for="newEmailConfirmPassword" id="newEmailConfirmPasswordLabel">Password</label>
											<input value="${newEmailConfirmPasswordText}" type="password" placeholder="Password" autocomplete="off" id="confirmPasswordNewEmailField" onkeyup="validateNewEmailPasswordField(event)">
											<button id="newEmailConfirmPassShowButton" class="passwordShowButton notSelectable" onclick="newEmailConfirmPassFieldShowToggle()">
												<div class="showPassImage" id="newEmailConfirmPassImage"></div>
											</button>
											<p id="confirmPasswordNewEmailError" class="inputErrorText">${newEmailConfirmPasswordChangeError}</p>
										</div>
										<div class="sendEmailButtonCont" style="height: 40px;">
											<button id="sendChangeEmailEmailButton" class="notSelectable" onclick="updateEmail()">${changeEmailButtonText}</button>
										</div>
									</div>
								</div>
								<h1 class="notSelectable">Other Settings</h1>
								<div class="infoColumnRow">
									<label for="bioInput" onkeyup="saveBioValue()" class="rowInfo notSelectable" id="bioInfoLabel" style="vertical-align: top;">Biography (optional):</label>
									<div id="bioContainer">
										<textarea id="bioInput" placeholder="Share about yourself in 200 characters." maxlength="200" rows="10">${bioText}</textarea>
									</div>
									<p id="bioInputError" class="inputErrorText">${bioInputError}</p>
									<button id="saveBioButton" class="notSelectable" onclick="updateBio()">${changeBioButtonText}</button>
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
										<button id="deleteAccountButton" class="notSelectable" onclick="deleteAccount()">${accountDeletionButtonText}</button>
									</div>
									<p id="deleteAccountError" class="inputErrorText">${accountDeletionError}</p>
								</div>`;
								if (getCookie("darktheme") !== "false") {
									const refDarkThemeSwitchCont = document.querySelector("#darkThemeSwitchCont");
									const refDarkThemeSwitch = document.querySelector("#darkThemeSwitch");
									refDarkThemeSwitchCont.style.backgroundColor = "rgba(255, 255, 255, 0.2)";
									refDarkThemeSwitch.style.left = "23px";
								}
								const refUsernameInfo = document.querySelector("#usernameRowInfo");
								const refEmailInfo = document.querySelector("#emailRowInfo");
								const refEmailVerifiedRow = document.querySelector("#emailVerifiedRow");
								const refEmailVerifiedInfo = document.querySelector("#emailVerifiedRowInfo");
								const refBioInfo = document.querySelector("#bioInfoLabel");
								const refBioCont = document.querySelector("#bioContainer");
								const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
								xhr.open("GET", "fetchAccountTabDetails.php", true);
								xhr.responseType = "json";
								xhr.onerror = function() {
									refUsernameInfo.innerHTML = "Username: [Error. Please refresh the page or try again later]";
									refEmailInfo.innerHTML = "Email: [Error. Please refresh the page or try again later]";
									refEmailVerifiedInfo.innerHTML = "Email Verified: [Error. Please refresh the page or try again later.]";
									refBioInfo.innerHTML = "Biography (optional): [Error. Please refresh the page or try again later.]";
								}
								xhr.onload = function() {
									refUsernameInfo.innerHTML = "Username: " + xhr.response["username"];
									refEmailInfo.innerHTML = "Email: " + xhr.response["email"];
									refEmailVerifiedRow.style = xhr.response["emailVerifiedRowStyle"];
									refEmailVerifiedInfo.style = xhr.response["emailVerifiedRowInfoStyle"];
									refEmailVerifiedInfo.innerHTML += xhr.response["emailVerifiedRowInfoText"];
									refEmailVerifiedRow.innerHTML += xhr.response["emailVerificationHTML"];
									refBioCont.innerHTML = xhr.response["biographyHTML"];
									if (document.querySelector("#verifyEmailButton")) {
										document.querySelector("#verifyEmailButton").innerHTML = emailVerificationButtonText;
									}
									if (document.querySelector("#verifyEmailError")) {
										document.querySelector("#verifyEmailError").innerHTML = emailVerificationError;
									}
								}
								xhr.send();
							}
						});
						refSecurityButton.addEventListener("click", function(triggered) {
							if (triggered.button === 0 && refSecurityButton.classList.contains("selectedTab") === false) {
								const refNewUserField = document.querySelector("#newUsernameField");
								const refNewPassField = document.querySelector("#newPasswordField");
								const refConfirmPassField = document.querySelector("#confirmPasswordField");
								const refNewEmailField = document.querySelector("#newEmailField");
								const refNewEmailConfirmPassField = document.querySelector("#confirmPasswordNewEmailField");
								const refBioInput = document.querySelector("#bioInput");
								newUsernameText = refNewUserField.value;
								newPasswordText = refNewPassField.value;
								confirmPasswordText = refConfirmPassField.value;
								newEmailText = refNewEmailField.value;
								newEmailConfirmPasswordText = refNewEmailConfirmPassField.value;
								bioText = refBioInput.value;
								refAccountButton.classList.toggle("selectedTab");
								refSecurityButton.classList.toggle("selectedTab");
								refSelectedTabLine.style.top = "50px";
								refSettingsInfoCont.innerHTML = `
								<h1 class="topHeaderInfo notSelectable">Account Security</h1>
								<div class="infoRow" id="twoFactorAuthRow">
									<p id="twoFactorAuthLabel" class="rowInfo notSelectable">2 Factor Authentication:</p>
								</div>`;
								const refTwoFactorAuthRow = document.querySelector("#twoFactorAuthRow");
								const refTwoFactorAuthText = document.querySelector("#twoFactorAuthLabel");
								const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
								xhr.open("GET", "fetchSecurityTabDetails.php", true);
								xhr.responseType = "json";
								xhr.onerror = function() {
									refUsernameInfo.innerHTML = "Username: [Error. Please refresh the page or try again later]";
									refEmailInfo.innerHTML = "Email: [Error. Please refresh the page or try again later]";
								}
								xhr.onload = function() {
									refTwoFactorAuthText.innerHTML += xhr.response["concatText"];
									refTwoFactorAuthRow.innerHTML += xhr.response["concatSlider"]
								}
								xhr.send();
							}
						});
						refMenuButton.addEventListener("click", function(triggered) {
							if (triggered.button === 0) {
								refMenuButton.style.animationName = refMenuButton.style.animationName === "menuAnimationOpen" ? "menuAnimationClose" : "menuAnimationOpen";
								refSideNav.style.left = refMenuButton.style.animationName === "menuAnimationOpen" ? 0 : refSideNav.clientWidth * -1 + "px";
							}
						});
					</script>
				</div>
			</main>
			<footer>
			</footer>
		</body>
	</html>';
}
?>