<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$stylesheetLink;
$loginAlert;
$logoutOrLogin = '<p id="logLabel" class="notSelectable">Logout</p>';
$logoutOrLoginScript = '
<script>"use strict";const _0x2f34=["../lo","Micro","text","stene","Selec","POST","XMLHt","php","addEv","soft.","#logL","respo","tpReq","click","entLi","abel","uest","XMLHT","onloa","tor","href","locat","send","gout.","open","ion","nseTy","query","nseTe"];(function(_0x5e9acd,_0x2f344a){const _0x41584a=function(_0x419e75){while(--_0x419e75){_0x5e9acd["push"](_0x5e9acd["shift"]());}};_0x41584a(++_0x2f344a);}(_0x2f34,0x1f1));const _0x4158=function(_0x5e9acd,_0x2f344a){_0x5e9acd=_0x5e9acd-0x0;let _0x41584a=_0x2f34[_0x5e9acd];return _0x41584a;};const _0x5dd2de=document[_0x4158("0x17")+_0x4158("0x0")+_0x4158("0xf")](_0x4158("0x6")+_0x4158("0xb"));var _0x2d4945;_0x5dd2de[_0x4158("0x4")+_0x4158("0xa")+_0x4158("0x1c")+"r"](_0x4158("0x9"),function(){clearTimeout(_0x2d4945);_0x2d4945=setTimeout(function(){const _0x4e392c=window[_0x4158("0x2")+_0x4158("0x8")+_0x4158("0xc")]?new XMLHttpRequest():new ActiveXObject(_0x4158("0x1a")+_0x4158("0x5")+_0x4158("0xd")+"TP");_0x4e392c[_0x4158("0x14")](_0x4158("0x1"),_0x4158("0x19")+_0x4158("0x13")+_0x4158("0x3"),!![]);_0x4e392c[_0x4158("0x7")+_0x4158("0x16")+"pe"]=_0x4158("0x1b");_0x4e392c[_0x4158("0xe")+"d"]=function(){window[_0x4158("0x11")+_0x4158("0x15")][_0x4158("0x10")]=_0x4e392c[_0x4158("0x7")+_0x4158("0x18")+"xt"];};_0x4e392c[_0x4158("0x12")]();},0x15e);});</script>';
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength / 2));
}
if (isset($_COOKIE["darktheme"]) && $_COOKIE["darktheme"] === "false") {
	$stylesheetLink = "homeLightTheme.css";
} else {
	$stylesheetLink = "homeDarkTheme.css";
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
					SELECT accountID, username, tokenHash, email
					FROM accountdetails
					WHERE rememberID = '$rememberMeID'";
					if ($allNeededDetails = $mysqliConnection -> query($selectAccountDetailsQuery)) {
						if ($allNeededDetails -> num_rows > 0) {
							if ($assocNeededDetails = $allNeededDetails -> fetch_assoc()) {
								$dbAccountID = $assocNeededDetails["accountID"];
								$dbUsername = $assocNeededDetails["username"];
								$dbTokenHash = $assocNeededDetails["tokenHash"];
								$dbEmail = $assocNeededDetails["email"];
								if (hash_equals($dbTokenHash, hash("sha512", $remememberMeToken)) === true) {
									$generateNewToken = getRandomString(50);
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
								<p id="alertText">No records for this account were found in the database. Please either log in and try again, try again later or refresh the page. You are now browsing as a guest.</p>
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
					<p id="alertText">You are logged out. You are now browsing as a guest.</p>
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
?>