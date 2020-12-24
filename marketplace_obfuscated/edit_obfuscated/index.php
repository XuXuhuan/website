<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$stylesheetLink;
$loginAlert;
$logoutOrLogin = '<p id="logLabel" class="notSelectable">Logout</p>';
$editPageHTML;
$imageFileName;
$marketName;
$escapedBiography;
$logoutOrLoginScript = '
<script>"use strict";const _0x2f34=["../../lo","Micro","text","stene","Selec","POST","XMLHt","php","addEv","soft.","#logL","respo","tpReq","click","entLi","abel","uest","XMLHT","onloa","tor","href","locat","send","gout.","open","ion","nseTy","query","nseTe"];(function(_0x5e9acd,_0x2f344a){const _0x41584a=function(_0x419e75){while(--_0x419e75){_0x5e9acd["push"](_0x5e9acd["shift"]());}};_0x41584a(++_0x2f344a);}(_0x2f34,0x1f1));const _0x4158=function(_0x5e9acd,_0x2f344a){_0x5e9acd=_0x5e9acd-0x0;let _0x41584a=_0x2f34[_0x5e9acd];return _0x41584a;};const _0x5dd2de=document[_0x4158("0x17")+_0x4158("0x0")+_0x4158("0xf")](_0x4158("0x6")+_0x4158("0xb"));var _0x2d4945;_0x5dd2de[_0x4158("0x4")+_0x4158("0xa")+_0x4158("0x1c")+"r"](_0x4158("0x9"),function(){clearTimeout(_0x2d4945);_0x2d4945=setTimeout(function(){const _0x4e392c=window[_0x4158("0x2")+_0x4158("0x8")+_0x4158("0xc")]?new XMLHttpRequest():new ActiveXObject(_0x4158("0x1a")+_0x4158("0x5")+_0x4158("0xd")+"TP");_0x4e392c[_0x4158("0x14")](_0x4158("0x1"),_0x4158("0x19")+_0x4158("0x13")+_0x4158("0x3"),!![]);_0x4e392c[_0x4158("0x7")+_0x4158("0x16")+"pe"]=_0x4158("0x1b");_0x4e392c[_0x4158("0xe")+"d"]=function(){window[_0x4158("0x11")+_0x4158("0x15")][_0x4158("0x10")]=_0x4e392c[_0x4158("0x7")+_0x4158("0x18")+"xt"];};_0x4e392c[_0x4158("0x12")]();},0x15e);});</script>';
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength / 2));
}
if (isset($_COOKIE["darktheme"]) && $_COOKIE["darktheme"] === "false") {
	$stylesheetLink = "editLightTheme.css";
} else {
	$stylesheetLink = "editDarkTheme.css";
}
if ($mysqliConnection -> connect_errno) {
	$loginAlert = '
	<div id="alertCont">
		<p id="alertText">A connection error occurred. Please try again later.</p>
	</div>';
} else {
	if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") {
		if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true) {
			if (isset($_COOKIE["logincookie"])) {
				$cookieValues = json_decode($_COOKIE["logincookie"], true);
				$rememberMeID = $mysqliConnection -> real_escape_string($cookieValues["remembermeid"]);
				$remememberMeToken = $mysqliConnection -> real_escape_string($cookieValues["remembermetoken"]);
				if (strlen(trim($rememberMeID)) === 30) {
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
											<p id="alertText">An error occurred.</p>
										</div>';
									}
								} else {
									header("Location: https://www.streetor.sg/login/");
								}
							} else {
								$loginAlert = '
								<div id="alertCont">
									<p id="alertText">An error occurred.</p>
								</div>';
							}
						} else {
							$loginAlert = '
							<div id="alertCont">
								<p id="alertText">Invalid login cookie. Please try to log in again.</p>
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
					<p id="alertText">This page is open only to logged in users.</p>
				</div>';
			}
		}
		if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
			if (!empty($_GET["id"]) && !preg_match("/[^0-9]/", $_GET["id"])) {
				$marketCategoriesBoxes;
				$escapedMarketID = $mysqliConnection -> real_escape_string($_GET["id"]);
				$selectMarketDetailsQuery = "SELECT *
				FROM marketdetails
				WHERE marketID = '{$escapedMarketID}'
				AND marketOwner = '{$_SESSION["userID"]}'";
				$availableCategories = array(
					"automotive",
					"babyCare",
					"books",
					"CDandVinyl",
					"clothesAndAccessories",
					"electronics",
					"gardening",
					"outdoorsAndSports",
					"groceries",
					"health",
					"household",
					"personalCare",
					"kitchenAndDining",
					"travelSupplies",
					"beauty",
					"moviesAndTV",
					"musicalInstruments",
					"officeSupplies",
					"petSupplies",
					"software",
					"tools",
					"toys",
					"games"
				);
				if ($queriedMarketDetails = $mysqliConnection -> query($selectMarketDetailsQuery)) {
					if ($queriedMarketDetails -> num_rows > 0) {
						if ($assocMarketDetails = $queriedMarketDetails -> fetch_assoc()) {
							foreach($availableCategories as $marketColumns) {
								$boxName;
								switch($marketColumns) {
									case "automotive":
										$boxName = "Automotive";
									break;
									case "babyCare":
										$boxName = "Baby Care";
									break;
									case "books":
										$boxName = "Books";
									break;
									case "CDandVinyl":
										$boxName = "CD and Vinyl";
									break;
									case "clothesAndAccessories":
										$boxName = "Clothes and Accessories";
									break;
									case "electronics":
										$boxName = "Electronics";
									break;
									case "gardening":
										$boxName = "Gardening";
									break;
									case "outdoorsAndSports":
										$boxName = "Outdoors And Sports";
									break;
									case "groceries":
										$boxName = "Groceries";
									break;
									case "health":
										$boxName = "Health";
									break;
									case "household":
										$boxName = "Household";
									break;
									case "personalCare":
										$boxName = "Personal Care";
									break;
									case "kitchenAndDining":
										$boxName = "Kitchen And Dining";
									break;
									case "travelSupplies":
										$boxName = "Travel Supplies";
									break;
									case "beauty":
										$boxName = "Beauty";
									break;
									case "moviesAndTV":
										$boxName = "Movies And TV";
									break;
									case "musicalInstruments":
										$boxName = "Musical Instruments";
									break;
									case "officeSupplies":
										$boxName = "Office Supplies";
									break;
									case "petSupplies":
										$boxName = "Pet Supplies";
									break;
									case "software":
										$boxName = "Software";
									break;
									case "tools":
										$boxName = "Tools";
									break;
									case "toys":
										$boxName = "Toys";
									break;
									case "games":
										$boxName = "Games";
									break;
								}
								$boxTicked = $assocMarketDetails[$marketColumns] == 1 ? "tickedCategoryBox" : "";
								$marketCategoriesBoxes .= "
								<div class='categoryBoxCont'>
									<p class='categoryLabel'>{$boxName}</p>
									<div class='marketCategoryBox inputMethod {$boxTicked}' id='{$marketColumns}Box'></div>
								</div>";
							}
							$marketName = htmlspecialchars($assocMarketDetails["marketName"], ENT_QUOTES);
							$findMarketLogo = glob("../../uploads/marketLogos/{$assocMarketDetails["marketID"]}.png");
							$imageFileName = "../../Assets/global/imageNotFound.png";
							$escapedBiography = nl2br(htmlspecialchars($assocMarketDetails['biography'], ENT_QUOTES));
							if (!empty($findMarketLogo)) {
								$imageFileName = $findMarketLogo[0];
							}
							$editPageHTML = "
							<div id='cancelOperationOverlay'></div>
							<nav id='editOptions'>
								<ul id='optionsList'>
									<li class='optionItems'>
										<button id='marketButton' class='optionButtons selectedTab'>
											<p class='optionsText notSelectable' id='accountText'>Market Details</p>
										</button>
									</li>
									<li class='optionItems'>
										<button id='productsButton' class='optionButtons'>
											<p class='optionsText notSelectable' id='securityText'>Products</p>
										</button>
									</li>
								</ul>
								<div id='selectedTabLine'></div>
							</nav>
							<div id='marketInfoCont'>
								<h1 class='topHeaderInfo'>Details</h1>
								<div class='infoColumnRow' id='marketLogoRow'>
									<p class='rowInfo' id='marketLogoLabel'>Market Logo:</p>
									<div id='marketLogoImageDisplay' class='inputMethod' style='background-image: url({$imageFileName});'>
										<div id='marketLogoTextOverlay' onmouseout='edit_marketLogoTextOverlayMouseLeave(event)' onmouseover='edit_marketLogoTextOverlayMouseEnter()'>
											<p id='marketLogoText' class='notSelectable'></p>
										</div>
									</div>
								</div>
								<div class='infoRow' id='marketNameRow'>
									<p id='marketNameLabel' class='rowInfo'>Market Name:</p>
									<div class='detailsCont' id='marketNameDetailsCont'>
										<p id='marketNameValue' class='rowInfo' contenteditable='false' spellcheck='false'>{$marketName}</p>
										<div id='marketNameEditIcon' class='editIcon' onclick='edit_marketNameEditIconClick()'></div>
									</div>
								</div>
								<div class='infoColumnRow' id='marketBioRow'>
									<p id='marketBioLabel' class='rowInfo'>Market Information (optional):</p>
									<textarea id='marketBioField' class='inputMethod' rows='10' placeholder='Give information about your store in 500 characters.' maxlength='500'>{$escapedBiography}</textarea>
									<div id='updateBioButtonCont'>
										<button id='changeCategoryButton' class='inputMethod' onclick='edit_updateMarketBio()'>Make Changes</button>
									</div>
								</div>
								<div id='marketCategoryRow' class='infoColumnRow'>
									<p id='marketCategoryLabel' class='rowInfo'>Categories:</p>
									<div id='marketCategoryCont'>
										{$marketCategoriesBoxes}
									</div>
									<div id='changeCategoryButtonCont'>
										<button id='changeCategoryButton' class='inputMethod' onclick='edit_changeMarketCategory()'>Make Changes</button>
									</div>
								</div>
								<div id='deleteMarketRow' class='infoColumnRow'>
									<p id='deleteMarketLabel' class='rowInfo'>Delete This Market</p>
									<div id='deleteMarketButtonCont'>
										<button id='deleteMarketButton' class='inputMethod'>Delete Market</button>
									</div>
									<p id='deleteMarketError' class='inputErrorText'></p>
								</div>
							</div>";
						} else {
							$loginAlert = '
							<div id="alertCont">
								<p id="alertText">An error occurred.</p>
							</div>';
						}
					} else {
						$loginAlert = '
						<div id="alertCont">
							<p id="alertText">No markets owned by you with this ID were found.</p>
						</div>';
					}
				} else {
					$loginAlert = '
					<div id="alertCont">
						<p id="alertText">An error occurred.</p>
					</div>';
				}
				$queriedMarketDetails -> free();
			} else {
				$logoutOrLogin = "<a href='https://www.streetor.sg/login/' id='logLabel' class='notSelectable'>Login</a>";
				$loginAlert = '
				<div id="alertCont">
					<p id="alertText">Invalid URL.</p>
				</div>';
			}
		}
	} else {
		$loginAlert = '
		<div id="alertCont">
			<p id="alertText">Your connection is not secure and this request could not be processed. Please try again later.</p>
		</div>';
	}
}
$mysqliConnection -> close();
if (empty($loginAlert)) {
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
			<title>Edit · Streetor</title>
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
					{$editPageHTML}
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
			<title>Edit · Streetor</title>
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
				{$logoutOrLoginScript}
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