<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$stylesheetLink;
$loginAlert;
$logoutOrLogin = '<p id="logLabel" class="notSelectable">Logout</p>';
$logoutOrLoginScript = '
<script>
	"use strict";
	const refLogLabel = document.querySelector("#logLabel");
	var checkLog;
	refLogLabel.addEventListener("click", function() {
		clearTimeout(checkLog);
		checkLog = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "../logout.php", true);
			xhr.responseType = "text";
			xhr.onload = function() {
				window.location.href = xhr.responseText;
			}
			xhr.send();
		}, 350);
	});
</script>';
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
					SELECT accountID, username, tokenHash, email, emailVerified, emailVerificationTime, 2FAenabled, biography
					FROM accountdetails
					WHERE rememberID = '$rememberMeID'";
					if ($allNeededDetails = $mysqliConnection -> query($selectAccountDetailsQuery)) {
						if ($allNeededDetails -> num_rows > 0) {
							if ($assocNeededDetails = $allNeededDetails -> fetch_assoc()) {
								$dbAccountID = $assocNeededDetails["accountID"];
								$dbUsername = $assocNeededDetails["username"];
								$dbTokenHash = $assocNeededDetails["tokenHash"];
								$dbEmail = $assocNeededDetails["email"];
								$dbLastSentTime = $assocNeededDetails["emailVerificationTime"];
								if (hash_equals($dbTokenHash, hash("sha512", $remememberMeToken)) === true) {
									$generateNewToken = getRandomString(50);
									$logoutOrLogin = "<p id='logLabel' class='notSelectable'>Logout</p>";
									$logoutOrLoginScript = '
									<script>
										"use strict";
										const refLogLabel = document.querySelector("#logLabel");
										var checkLog;
										refLogLabel.addEventListener("click", function() {
											clearTimeout(checkLog);
											checkLog = setTimeout(function() {
												const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
												xhr.open("POST", "../logout.php", true);
												xhr.responseType = "text";
												xhr.onload = function() {
													window.location.href = xhr.responseText;
												}
												xhr.send();
											}, 350);
										});
									</script>';
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
									if ($dbEmailVerif == false) {
										$remainingCooldown = strtotime($dbLastSentTime) > (time() - 120) ?
										"Please wait " . (strtotime($dbLastSentTime) + 120 - time()) . " more seconds!" : "";
										$loginAlert = '
										<div id="alertCont">
											<p id="alertText">It seems like you still haven&#39t verified your email! Please click the button below to send your email address the verification email. Verification is required to change some settings or to message sellers.</p>
											<p id="resendEmailError">' . $remainingCooldown . '</p>
											<button id="resendEmailButton" class="notSelectable">Send Email</button>
											<button id="cancelAlertButton" class="notSelectable">Cancel</button>
										</div>
										<script>
											"use strict";
											const refButton = document.querySelector("#resendEmailButton");
											const refEmailError = document.querySelector("#resendEmailError");
											const refLoggedOutCont = document.querySelector("#alertCont");
											const refCancelAlertButton = document.querySelector("#cancelAlertButton");
											var checkVerificationEmail;
											var leftCooldown = 0;
											refButton.addEventListener("mouseup", function(triggered) {
												if (triggered.button === 0 && leftCooldown === 0) {
													clearTimeout(checkVerificationEmail);
													checkVerificationEmail = setTimeout(function() {
														const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
														xhr.open("POST", "../sendVerifEmail.php", true);
														xhr.setRequestHeader("Content-type", "application/json");
														xhr.responseType = "json";
														xhr.onload = function() {
															refEmailError.innerHTML = xhr.response["message"];
															leftCooldown = xhr.response["leftoverCooldown"];
															if (leftCooldown <= 1) {
																refButton.innerHTML = "Re-send Email";
															} else {
																refButton.innerHTML = "Re-send Email (" + leftCooldown + ")";
																leftCooldown--;
																for (var i = 0; i <= leftCooldown; i++) {
																	setTimeout(function() {
																		if (leftCooldown === xhr.response["leftoverCooldown"] - 3) {
																			refEmailError.innerHTML = "";
																		}
																		if (leftCooldown === 0) {
																			refButton.innerHTML = "Re-send Email";
																		} else {
																			refButton.innerHTML = "Re-send Email (" + leftCooldown + ")";
																			leftCooldown--;
																		}
																	}, 1000 * i);
																}
															}
														}
														xhr.send();
													}, 500);
												}
											});
											refButton.addEventListener("mousedown", function(triggered) {
												if (triggered.button === 0) {
													clearTimeout(checkVerificationEmail);
												}
											});
											refCancelAlertButton.addEventListener("mousedown", function(triggered) {
												if (triggered.button === 0) {
													refLoggedOutCont.parentElement.removeChild(refLoggedOutCont);
												}
											});
										</script>';
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
					<button id="cancelAlertButton" class="notSelectable">Cancel</button>
				</div>
				<script>
					"use strict";
					const refLoggedOutCont = document.querySelector("#alertCont");
					const refCancelAlertButton = document.querySelector("#cancelAlertButton");
					refCancelAlertButton.addEventListener("mousedown", function(triggered) {
						if (triggered.button === 0) {
							refLoggedOutCont.parentElement.removeChild(refLoggedOutCont);
						}
					});
				</script>';
			}
		}
		else if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
			$selectEmailVerifiedQuery = "
			SELECT emailVerificationTime
			FROM accountdetails
			WHERE accountID = " . $_SESSION["userID"] . "
			AND emailVerified = 0";
			if ($queriedEmailVerified = $mysqliConnection -> query($selectEmailVerifiedQuery)) {
				if ($queriedEmailVerified -> num_rows > 0) {
					if ($assocQueriedEmailVerified = $queriedEmailVerified -> fetch_assoc()) {
						$dbLastSentTime = $assocQueriedEmailVerified["emailVerificationTime"];
						$remainingCooldown = strtotime($dbLastSentTime) > (time() - 120) ?
						"Please wait " . (strtotime($dbLastSentTime) - time() + 120) . " more seconds!" : "";
						$loginAlert = '
						<div id="alertCont">
							<p id="alertText">It seems like you still haven&#39t verified your email! Please click the button below to send your email address the verification email. Verification is required to change some settings or to message sellers.</p>
							<p id="resendEmailError">' . $remainingCooldown . '</p>
							<button id="resendEmailButton" class="notSelectable">Send Email</button>
							<button id="cancelAlertButton" class="notSelectable">Cancel</button>
						</div>
						<script>
							"use strict";
							const refButton = document.querySelector("#resendEmailButton");
							const refEmailError = document.querySelector("#resendEmailError");
							const refLoggedOutCont = document.querySelector("#alertCont");
							const refCancelAlertButton = document.querySelector("#cancelAlertButton");
							var checkVerificationEmail;
							var leftCooldown = 0;
							refButton.addEventListener("mouseup", function(triggered) {
								if (triggered.button === 0 && leftCooldown === 0) {
									clearTimeout(checkVerificationEmail);
									checkVerificationEmail = setTimeout(function() {
										const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
										xhr.open("POST", "../sendVerifEmail.php", true);
										xhr.setRequestHeader("Content-type", "application/json");
										xhr.responseType = "json";
										xhr.onload = function() {
											refEmailError.innerHTML = xhr.response["message"];
											leftCooldown = xhr.response["leftoverCooldown"];
											if (leftCooldown <= 1) {
												refButton.innerHTML = "Re-send Email";
											} else {
												refButton.innerHTML = "Re-send Email (" + leftCooldown + ")";
												leftCooldown--;
												for (var i = 0; i <= leftCooldown; i++) {
													setTimeout(function() {
														if (leftCooldown === xhr.response["leftoverCooldown"] - 3) {
															refEmailError.innerHTML = "";
														}
														if (leftCooldown === 0) {
															refButton.innerHTML = "Re-send Email";
														} else {
															refButton.innerHTML = "Re-send Email (" + leftCooldown + ")";
															leftCooldown--;
														}
													}, 1000 * i);
												}
											}
										}
										xhr.send();
									}, 500);
								}
							});
							refButton.addEventListener("mousedown", function(triggered) {
								if (triggered.button === 0) {
									clearTimeout(checkVerificationEmail);
								}
							});
							refCancelAlertButton.addEventListener("mousedown", function(triggered) {
								if (triggered.button === 0) {
									refLoggedOutCont.parentElement.removeChild(refLoggedOutCont);
								}
							});
						</script>';
					} else {
						$loginAlert = '
						<div id="alertCont">
							<p id="alertText">An internal error occurred. Please refresh the page or try again later.</p>
						</div>';
					}
				}
				$queriedEmailVerified -> free();
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