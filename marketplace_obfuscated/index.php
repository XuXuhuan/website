<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$marketProfile;
$marketplacePageHTML;
$stylesheetLink;
$loginAlert;
$popMarketError;
$logoutOrLogin = '<p id="logLabel" class="notSelectable">Logout</p>';
$logoutOrLoginScript = '
<script>"use strict";const _0x2f34=["../lo","Micro","text","stene","Selec","POST","XMLHt","php","addEv","soft.","#logL","respo","tpReq","click","entLi","abel","uest","XMLHT","onloa","tor","href","locat","send","gout.","open","ion","nseTy","query","nseTe"];(function(_0x5e9acd,_0x2f344a){const _0x41584a=function(_0x419e75){while(--_0x419e75){_0x5e9acd["push"](_0x5e9acd["shift"]());}};_0x41584a(++_0x2f344a);}(_0x2f34,0x1f1));const _0x4158=function(_0x5e9acd,_0x2f344a){_0x5e9acd=_0x5e9acd-0x0;let _0x41584a=_0x2f34[_0x5e9acd];return _0x41584a;};const _0x5dd2de=document[_0x4158("0x17")+_0x4158("0x0")+_0x4158("0xf")](_0x4158("0x6")+_0x4158("0xb"));var _0x2d4945;_0x5dd2de[_0x4158("0x4")+_0x4158("0xa")+_0x4158("0x1c")+"r"](_0x4158("0x9"),function(){clearTimeout(_0x2d4945);_0x2d4945=setTimeout(function(){const _0x4e392c=window[_0x4158("0x2")+_0x4158("0x8")+_0x4158("0xc")]?new XMLHttpRequest():new ActiveXObject(_0x4158("0x1a")+_0x4158("0x5")+_0x4158("0xd")+"TP");_0x4e392c[_0x4158("0x14")](_0x4158("0x1"),_0x4158("0x19")+_0x4158("0x13")+_0x4158("0x3"),!![]);_0x4e392c[_0x4158("0x7")+_0x4158("0x16")+"pe"]=_0x4158("0x1b");_0x4e392c[_0x4158("0xe")+"d"]=function(){window[_0x4158("0x11")+_0x4158("0x15")][_0x4158("0x10")]=_0x4e392c[_0x4158("0x7")+_0x4158("0x18")+"xt"];};_0x4e392c[_0x4158("0x12")]();},0x15e);});</script>';
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength / 2));
}
if (isset($_COOKIE["darktheme"]) && $_COOKIE["darktheme"] === "false") {
	$stylesheetLink = "marketplaceLightTheme.css";
} else {
	$stylesheetLink = "marketplaceDarkTheme.css";
}
if ($mysqliConnection -> connect_errno) {
	$loginAlert = '
	<div id="alertCont">
		<p id="alertText">A connection error occurred. Please try again later.</p>
	</div>';
}
else if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") {
	if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
		if (isset($_COOKIE["logincookie"])) {
			$cookieValues = json_decode($_COOKIE["logincookie"], true);
			$rememberMeID = $mysqliConnection -> real_escape_string($cookieValues["remembermeid"]);
			$remememberMeToken = $mysqliConnection -> real_escape_string($cookieValues["remembermetoken"]);
			if (strlen($rememberMeID) === 30) {
				$selectAccountDetailsQuery = "
				SELECT accountID, username, tokenHash, email
				FROM accountdetails
				WHERE rememberID = '{$rememberMeID}'";
				if ($allNeededDetails = $mysqliConnection -> query($selectAccountDetailsQuery)) {
					if ($allNeededDetails -> num_rows > 0) {
						if ($assocNeededDetails = $allNeededDetails -> fetch_assoc()) {
							$dbAccountID = $assocNeededDetails["accountID"];
							$dbUsername = $assocNeededDetails["username"];
							$dbTokenHash = $assocNeededDetails["tokenHash"];
							$dbEmail = $assocNeededDetails["email"];
							if (hash_equals($dbTokenHash, hash("sha512", $remememberMeToken)) === true) {
								$generateNewToken = getRandomString(50);
								$hashedNewToken = hash("sha512", $generateNewToken);
								$updateNewToken = "
								UPDATE accountdetails
								SET tokenHash = '{$hashedNewToken}'
								WHERE accountID = '{$dbAccountID}'";
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
										<p id="alertText">An internal error occurred. Please try again later.</p>
									</div>';
								}
							} else {
								header("Location: https://www.streetor.sg/login/");
							}
						} else {
							$loginAlert = '
							<div id="alertCont">
								<p id="alertText">An internal error occurred. Please try again later.</p>
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
		}
	}
	if (empty($_GET["id"])) {
		$fetchMostPopularRowsQuery = "SELECT marketID, marketName
		FROM marketdetails
		ORDER BY subscribers DESC
		LIMIT 4";
		if ($queriedMostPopularRows = $mysqliConnection -> query($fetchMostPopularRowsQuery)) {
			if ($queriedMostPopularRows -> num_rows > 0) {
				$popularMarkets;
				while ($assocMostPopularRows = $queriedMostPopularRows -> fetch_assoc()) {
					$popularMarkets .= "
					<a href='index.php?id={$assocMostPopularRows['marketID']}' class='popMarketLink'>
						<div class='popMarket'>
							<h2 class='popMarketName notSelectable'>{$assocMostPopularRows['marketName']}</h2>
						</div>
					</a>";
				}
				$marketplacePageHTML = "
				<a href='https://www.streetor.sg/marketplace/register/' id='registerMarketplaceLink' class='notSelectable'>
					<div id='registerMarketplaceImageCont'></div>
					Register
				</a>
				<div id='marketContents'>
					<div id='searchFormCont'>
						<form action='marketplaceSearch/index.php' id='marketplaceSearchForm' method='GET' autocomplete='off'>
							<label for='marketplaceSearchField' id='marketplaceSearchLabel'>Search</label>
							<div id='searchBarCont'>
								<input type='text' name='query' id='marketplaceSearchField' placeholder='Search Marketplace'>
								<button id='marketplaceSearchButton' type='submit'>
									<div id='marketplaceSearchImage'></div>
								</button>
							</div>
							<p class='inputErrorText' id='searchErrorText'></p>
						</form>
					</div>
					<div id='suggestedMarkets'>
						<div id='popMarketsCont' class='infoColumnRow'>
							<h1 id='popMarketHeader'>Most Popular</h1>
							<div id='popMarketsWrapper' class='infoRow'>
								{$popularMarkets}
							</div>
						</div>
						<div id='recommendedMarketsCont' class='infoColumnRow'>
							<h1 id='recommendedMarketHeader'>Recommended</h1>
							<div id='recommendedMarketsWrapper' class='infoRow'>
								<p class='inputErrorText'>No recommended markets.</p>
							</div>
						</div>
					</div>
				</div>";
			} else {
				$popMarketError = "No popular markets at the moment.";
			}
			$queriedMostPopularRows -> free();
		} else {
			$loginAlert = '
			<div id="alertCont">
				<p id="alertText">An internal error occurred. Please try again later.</p>
			</div>';
		}
		$marketProfile = "Search Marketplace";
	}
	else if (preg_match("/[^0-9]/", $_GET["id"]) == true) {
		$marketplacePageHTML = '
		<div id="marketContents">
			<div id="searchFormCont">
				<form action="marketplaceSearch/index.php" id="marketplaceSearchForm" method="GET" autocomplete="off">
					<label for="marketplaceSearchField" id="marketplaceSearchLabel">Search</label>
					<div id="searchBarCont">
						<input type="text" name="query" id="marketplaceSearchField" placeholder="Search Marketplace">
						<button id="marketplaceSearchButton" type="submit">
							<div id="marketplaceSearchImage"></div>
						</button>
					</div>
					<p class="inputErrorText" id="searchErrorText">Invalid ID in the URL provided. A valid ID only contains numbers.</p>
				</form>
			</div>
		</div>';
		$marketProfile = "Invalid ID";
	} else {
		$marketID = $mysqliConnection -> real_escape_string($_GET["id"]);
		$selectMarketDetailsQuery = "SELECT marketID, marketName, biography, subscribers, productCount
		FROM marketdetails
		WHERE marketID = '{$marketID}'";
		if ($queriedMarketDetails = $mysqliConnection -> query($selectMarketDetailsQuery)) {
			if ($queriedMarketDetails -> num_rows > 0) {
				if ($assocMarketDetails = $queriedMarketDetails -> fetch_assoc()) {
					$marketProfile = $assocMarketDetails["marketName"];
					$findMarketLogo = glob("../uploads/marketLogos/{$assocMarketDetails["marketID"]}.*");
					$imageFileName = "../../Assets/global/imageNotFound.png";
					$selectSubscriptionQuery = "SELECT subscribingUser
					FROM subscriptions
					WHERE subscribingUser = '{$_SESSION["userID"]}'";
					if ($queriedSubscriptions = $mysqliConnection -> query($selectSubscriptionQuery)) {
						$subscribeButtonClass;
						$subscribeButtonText = "Subscribe";
						$escapedMarketName = htmlspecialchars($assocMarketDetails['marketName'], ENT_QUOTES);
						$escapedBiography = nl2br(htmlspecialchars($assocMarketDetails['biography'], ENT_QUOTES));
						if ($queriedSubscriptions -> num_rows > 0) {
							if (!empty($findMarketLogo)) {
								$imageFileName = $findMarketLogo[0];
							}
							$subscribeButtonClass = ' class="unsubscribeButton"';
							$subscribeButtonText = "Unsubscribe";
						}
						$marketplacePageHTML = "
						<a href='https://www.streetor.sg/marketplace/register/' id='registerMarketplaceLink' class='notSelectable'>
							<div id='registerMarketplaceImageCont'></div>
							Register
						</a>
						<div id='marketContents'>
							<div id='searchFormCont'>
								<form action='marketplaceSearch/index.php' id='marketplaceSearchForm' method='GET' autocomplete='off'>
									<label for='marketplaceSearchField' id='marketplaceSearchLabel'>Search</label>
									<div id='searchBarCont'>
										<input type='text' name='query' id='marketplaceSearchField' placeholder='Search Marketplace'>
										<button id='marketplaceSearchButton' type='submit'>
											<div id='marketplaceSearchImage'></div>
										</button>
									</div>
									<p class='inputErrorText' id='searchErrorText'></p>
								</form>
							</div>
							<div id='marketDetailsCont'>
								<div id='nameAndBioRow'>
									<img src='{$imageFileName}' alt='Market Logo' id='marketLogo'>
									<div class='infoColumnRow' id='nameAndBioCont'>
										<h2 id='marketName'>{$escapedMarketName}</h2>
										<p id='biographyText'>{$escapedBiography}</p>
									</div>
								</div>
								<div class='infoRow' id='marketStatsRow'>
									<div class='infoColumnRow marketStatColumn'>
										<h3 class='statLabel'>Subscribers</h3>
										<p class='statText' id='subscriberCount'>{$assocMarketDetails['subscribers']}</p>
										<div id='subscribeButtonCont'>
											<button id='subscribeButton'{$subscribeButtonClass} onmousedown='cancelToggleSubscribeTimeout(event)' onmouseup='toggleSubscribe(event)'>{$subscribeButtonText}</button>
										</div>
									</div>
									<div class='infoColumnRow marketStatColumn'>
										<h3 class='statLabel'>Products</h3>
										<p class='statText' id='productCount'>{$assocMarketDetails['productCount']}</p>
										<div id='viewProductsLinkCont'>
											<a href='https://www.streetor.sg/marketplace/products/?marketid={$assocMarketDetails['marketID']}' id='viewProductsLink'>View Products</a>
										</div>
									</div>
								</div>
							</div>
						</div>";
						$queriedSubscriptions -> free();
					} else {
						$loginAlert = '
						<div id="alertCont">
							<p id="alertText">An internal error occurred. Please try again later.</p>
						</div>';
					}
				} else {
					$loginAlert = '
					<div id="alertCont">
						<p id="alertText">An internal error occurred. Please try again later.</p>
					</div>';
				}
			} else {
				$marketplacePageHTML = '
				<a href="https://www.streetor.sg/marketplace/register/" id="registerMarketplaceLink" class="notSelectable">
					<div id="registerMarketplaceImageCont"></div>
					Register
				</a>
				<div id="marketContents">
					<div id="searchFormCont">
						<form action="marketplaceSearch/index.php" id="marketplaceSearchForm" method="GET" autocomplete="off">
							<label for="marketplaceSearchField" id="marketplaceSearchLabel">Search</label>
							<div id="searchBarCont">
								<input type="text" name="query" id="marketplaceSearchField" placeholder="Search Marketplace">
								<button id="marketplaceSearchButton" type="submit">
									<div id="marketplaceSearchImage"></div>
								</button>
							</div>
							<p class="inputErrorText" id="searchErrorText">User not found.</p>
						</form>
					</div>
				</div>';
				$marketProfile = "User not found";
			}
			$queriedMarketDetails -> free();
		} else {
			$loginAlert = '
			<div id="alertCont">
				<p id="alertText">An internal error occurred. Please try again later.</p>
			</div>';
		}
	}
} else {
	$loginAlert = '
	<div id="alertCont">
		<p id="alertText">Your connection is not secure and this request could not be processed. Please try again later.</p>
	</div>';
}
$mysqliConnection -> close();
if (empty($loginAlert)) {
	echo "<!DOCTYPE html>
	<html lang='en'>
	<head>
		<meta charset='UTF-8'>
		<meta name='viewport' content='width=device-width, initial-scale=1.0'>
		<meta name='keywords' content='lifestyle, life, tips, share, social media'>
		<meta name='description' content='Share about your lifestyle or lifestyle tips!'>
		<link rel='stylesheet' href='{$stylesheetLink}'>
		<script src='web.js' defer></script>
		<title>{$marketProfile} · Streetor</title>
	</head>
	<body>
		<header>
			<nav id='topnav'>
				<button id='menuToggle'>
					<div id='menuImageCont'></div>
				</button>
				<p id='orgName' class='notSelectable'>STREETOR</p>
				{$logoutOrLogin}
			</nav>
			<nav id='sidenav'>
				<a href='https://www.streetor.sg/home/' id='homeLink'>
					<div class='innerLinksCont'>
						<div id='homeImage' class='sideNavImage'></div>
						<p id='homeText' class='notSelectable'>Home</p>
					</div>
				</a>
				<a href='https://www.streetor.sg/profiles/' id='profilesLink'>
					<div class='innerLinksCont'>
						<div id='profilesImage' class='sideNavImage'></div>
						<p id='profilesText' class='notSelectable'>Profiles</p>
					</div>
				</a>
				<a href='https://www.streetor.sg/settings/' id='settingsLink'>
					<div class='innerLinksCont'>
						<div id='settingsImage' class='sideNavImage'></div>
						<p id='settingsText' class='notSelectable'>Settings</p>
					</div>
				</a>
				<a href='https://www.streetor.sg/marketplace/' id='marketplaceLink'>
					<div class='innerLinksCont'>
						<div id='marketplaceImage' class='sideNavImage'></div>
						<p id='marketplaceText' class='notSelectable'>Marketplace</p>
					</div>
				</a>
				<a href='https://www.streetor.sg/privacy/' id='privacyLink'>
					<div class='innerLinksCont'>
						<div id='privacyImage' class='sideNavImage'></div>
						<p id='privacyText' class='notSelectable'>Privacy</p>
					</div>
				</a>
			</nav>
			<div id='notificationCont'>
				<p id='notificationText'></p>
			</div>
		</header>
		<main>
			{$logoutOrLoginScript}
			<div id='mainCont'>
				{$marketplacePageHTML}
			</div>
		</main>
		<footer>
		</footer>
	</body>
	</html>";
} else {
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
			<title>{$marketProfile} · Streetor</title>
		</head>
		<body>
			<header>
				<nav id='topnav'>
					<button id='menuToggle'>
						<div id='menuImageCont'></div>
					</button>
					<p id='orgName' class='notSelectable'>STREETOR</p>
					{$logoutOrLogin}
				</nav>
				<nav id='sidenav'>
					<a href='https://www.streetor.sg/home/' id='homeLink'>
						<div class='innerLinksCont'>
							<div id='homeImage' class='sideNavImage'></div>
							<p id='homeText' class='notSelectable'>Home</p>
						</div>
					</a>
					<a href='https://www.streetor.sg/profiles/' id='profilesLink'>
						<div class='innerLinksCont'>
							<div id='profilesImage' class='sideNavImage'></div>
							<p id='profilesText' class='notSelectable'>Profiles</p>
						</div>
					</a>
					<a href='https://www.streetor.sg/settings/' id='settingsLink'>
						<div class='innerLinksCont'>
							<div id='settingsImage' class='sideNavImage'></div>
							<p id='settingsText' class='notSelectable'>Settings</p>
						</div>
					</a>
					<a href='https://www.streetor.sg/marketplace/' id='marketplaceLink'>
						<div class='innerLinksCont'>
							<div id='marketplaceImage' class='sideNavImage'></div>
							<p id='marketplaceText' class='notSelectable'>Marketplace</p>
						</div>
					</a>
					<a href='https://www.streetor.sg/privacy/' id='privacyLink'>
						<div class='innerLinksCont'>
							<div id='privacyImage' class='sideNavImage'></div>
							<p id='privacyText' class='notSelectable'>Privacy</p>
						</div>
					</a>
				</nav>
			</header>
			<main>
				<div id='mainCont'>
					{$loginAlert}
				</div>
			</main>
			<footer>
			</footer>
		</body>
	</html>";
}
?>