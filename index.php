<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$stylesheetLink;
$loginAlert;
$logoutOrLogin = '<p id="logLabel" class="notSelectable">Logout</p>';
$logoutOrLoginScript = '
<script>"use strict";const _0x2f34=["lo","Micro","text","stene","Selec","POST","XMLHt","php","addEv","soft.","#logL","respo","tpReq","click","entLi","abel","uest","XMLHT","onloa","tor","href","locat","send","gout.","open","ion","nseTy","query","nseTe"];(function(_0x5e9acd,_0x2f344a){const _0x41584a=function(_0x419e75){while(--_0x419e75){_0x5e9acd["push"](_0x5e9acd["shift"]());}};_0x41584a(++_0x2f344a);}(_0x2f34,0x1f1));const _0x4158=function(_0x5e9acd,_0x2f344a){_0x5e9acd=_0x5e9acd-0x0;let _0x41584a=_0x2f34[_0x5e9acd];return _0x41584a;};const _0x5dd2de=document[_0x4158("0x17")+_0x4158("0x0")+_0x4158("0xf")](_0x4158("0x6")+_0x4158("0xb"));var _0x2d4945;_0x5dd2de[_0x4158("0x4")+_0x4158("0xa")+_0x4158("0x1c")+"r"](_0x4158("0x9"),function(){clearTimeout(_0x2d4945);_0x2d4945=setTimeout(function(){const _0x4e392c=window[_0x4158("0x2")+_0x4158("0x8")+_0x4158("0xc")]?new XMLHttpRequest():new ActiveXObject(_0x4158("0x1a")+_0x4158("0x5")+_0x4158("0xd")+"TP");_0x4e392c[_0x4158("0x14")](_0x4158("0x1"),_0x4158("0x19")+_0x4158("0x13")+_0x4158("0x3"),!![]);_0x4e392c[_0x4158("0x7")+_0x4158("0x16")+"pe"]=_0x4158("0x1b");_0x4e392c[_0x4158("0xe")+"d"]=function(){window[_0x4158("0x11")+_0x4158("0x15")][_0x4158("0x10")]=_0x4e392c[_0x4158("0x7")+_0x4158("0x18")+"xt"];};_0x4e392c[_0x4158("0x12")]();},0x15e);});</script>';
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
$homePageHTML;
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
					$selectAccountDetailsQuery = "SELECT accountdetails.accountID, accountdetails.username, remembereddevices.tokenHash, accountdetails.email
					FROM accountdetails
					LEFT JOIN remembereddevices
					ON accountdetails.accountID = remembereddevices.accountID
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
									$updateNewToken = "UPDATE remembereddevices
									SET tokenHash = '{$hashedNewToken}'
									WHERE accountID = '{$dbAccountID}'";
									if ($tokenUpdateQuery = $mysqliConnection -> query($updateNewToken)) {
										$newCookieValuesDecoded = array("remembermeid" => $rememberMeID, "remembermetoken" => $generateNewToken);
										setcookie("logincookie", json_encode($newCookieValuesDecoded), strtotime("9999-12-31"), "/", "streetor.sg", true, true);
										$_SESSION["loggedIn"] = true;
										$_SESSION["userID"] = $dbAccountID;
									} else {
										$loginAlert = '
										<div id="alertCont">
											<p id="alertText">An error occurred.</p>
										</div>';
									}
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
					header("https://streetor.sg/login");
				}
			} else {
				$logoutOrLogin = "<a href='https://streetor.sg/login/' id='logLabel' class='notSelectable'>Login</a>";
				$loginAlert = '
				<div id="alertCont">
					<p id="alertText">You are logged out. You are now browsing as a guest.</p>
				</div>';
			}
		}
		if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
			$bestProductStatsHTML;
			$marketStatsHTML;
			$selectStatsQuery = "SELECT marketproducts.productID, marketproducts.productName, marketproducts.productInfo, marketproducts.pricing, AVG(ratings.rating) AS allTime, (SELECT AVG(rating) FROM ratings WHERE ratingTime >= SUBDATE(NOW(), INTERVAL 30 DAY) AND productID = marketproducts.productID) AS lastMonth
			FROM ratings
			RIGHT JOIN marketproducts
			ON marketproducts.productID = ratings.productID
			JOIN marketdetails
			ON marketproducts.marketID = marketdetails.marketID
			WHERE marketdetails.marketOwner = '{$_SESSION["userID"]}'
			GROUP BY marketproducts.productName
			ORDER BY allTime DESC
			LIMIT 1;";
			$selectStatsQuery .= "SELECT marketdetails.marketID,
			marketdetails.marketName,
			marketdetails.biography,
			AVG(ratings.rating) AS avgRAllTime,
			(SELECT AVG(rating) FROM ratings WHERE ratings.ratingTime >= SUBDATE(NOW(), INTERVAL 30 DAY) AND productID = marketproducts.productID) AS avgRPastMonth,
			COUNT(subscriptions.subscribingUser) AS avgSAllTime,
			(SELECT COUNT(subscribingUser) FROM subscriptions WHERE subscriptionTime >= SUBDATE(NOW(), INTERVAL 30 DAY) AND subscribedMarket = marketdetails.marketID) AS avgSPastMonth
			FROM marketdetails
			LEFT JOIN marketproducts
			ON marketdetails.marketID = marketproducts.marketID
			LEFT JOIN subscriptions
			ON subscriptions.subscribedMarket = marketdetails.marketID
			LEFT JOIN ratings
			ON ratings.productID = marketproducts.productID
			WHERE marketdetails.marketOwner = '{$_SESSION["userID"]}'
			GROUP BY marketdetails.marketName
			ORDER BY marketdetails.marketName";
			if ($mysqliConnection -> multi_query($selectStatsQuery)) {
				if ($queriedProductStats = $mysqliConnection -> store_result()) {
					if ($queriedProductStats -> num_rows > 0) {
						if ($assocProductStats = $queriedProductStats -> fetch_assoc()) {
							$productImagePath = empty(glob("uploads/productImages/{$assocProductStats["productID"]}/1.*")) ? "Assets/global/imageNotFound.png" : glob("uploads/productImages/{$assocProductStats["productID"]}/1.*")[0];
							$adjustedPrice = number_format($assocProductStats["pricing"], 2);
							$allTimeRating = empty($assocProductStats["allTime"]) ? 0 : $assocProductStats["allTime"];
							$lastMonthRating = empty($assocProductStats["lastMonth"]) ? 0 : $assocProductStats["lastMonth"];
							$escapedProductName = htmlspecialchars($assocProductStats["productName"], ENT_QUOTES);
							$escapedProductInfo = htmlspecialchars($assocProductStats["productInfo"], ENT_QUOTES);
							$bestProductStatsHTML = "
							<div class='productContentsRow infoRow'>
								<img src='{$productImagePath}' alt='Product Image' class='productImage'>
								<div class='productNameAndInfoCont infoColumnRow'>
									<a href='https://streetor.sg/marketplace/products/?prodid={$assocProductStats["productID"]}' class='productName' id='bestStatProductName'>{$escapedProductName}</a>
									<p class='pricingInfoLabel'>S\${$adjustedPrice}</p>
									<p class='productInfoText'>{$escapedProductInfo}</p>
									<div class='productRatingRow'>
										<p class='ratingLabel'>{$allTimeRating}</p>
										<svg height='18' width='18' class='productRatingStar'>
											<defs>
												<linearGradient id='starGradient'>
													<stop offset='100%' stop-color='#e1c900'></stop>
												</linearGradient>
											</defs>
											<polygon points='9,0 4,18 18,7 0,7 15,18' style='fill: url(#starGradient);'></polygon>
										</svg>
									</div>
									<button class='productMenuButtonCont'>
										<svg class='productMenuButton' width='5' height='20'>
											<circle cx='2.5' cy='2.5' r='2.5' class='productMenuButtonDot'/>
											<circle cx='2.5' cy='10' r='2.5' class='productMenuButtonDot'/>
											<circle cx='2.5' cy='17.5' r='2.5' class='productMenuButtonDot'/>
										</svg>
										<span class='hidePopUp productMenuPopUp'>
											<a href='https://streetor.sg/marketplace/products/edit/?id={$assocProductStats["productID"]}' class='notSelectable productMenuPopUpLink'>
												Edit
												<div class='productMenuPopUpTail'></div>
											</a>
											<p class='notSelectable' id='productMenuDelete' data-productid='{$assocProductStats["productID"]}'>Delete</p>
										</span>
									</button>
								</div>
							</div>
							<div id='productStatsRow'>
								<div class='lastMonthStatsCont'>
									<p class='lastMonthStatsLabel'>Last 30 Days</p>
									<div class='bestStatProductRatingRow'>
										<p class='ratingLabel'>{$lastMonthRating}</p>
										<svg height='18' width='18' class='productRatingStar'>
											<defs>
												<linearGradient id='starGradient'>
													<stop offset='100%' stop-color='#e1c900'></stop>
												</linearGradient>
											</defs>
											<polygon points='9,0 4,18 18,7 0,7 15,18' style='fill: url(#starGradient);'></polygon>
										</svg>
									</div>
								</div>
								<div class='allTimeStatsCont'>
									<p class='allTimeStatsLabel'>All Time</p>
									<div class='bestStatProductRatingRow'>
										<p class='ratingLabel'>{$allTimeRating}</p>
										<svg height='18' width='18' class='productRatingStar'>
											<defs>
												<linearGradient id='starGradient'>
													<stop offset='100%' stop-color='#e1c900'></stop>
												</linearGradient>
											</defs>
											<polygon points='9,0 4,18 18,7 0,7 15,18' style='fill: url(#starGradient);'></polygon>
										</svg>
									</div>
								</div>
							</div>";
						} else {
							$loginAlert = '
							<div id="alertCont">
								<p id="alertText">An error occurred.</p>
							</div>';
						}
					} else {
						$bestProductStatsHTML = "<p class='inputErrorText' id='bestStatProductError'>None at the moment.</p>";
					}
					$queriedProductStats -> free();
				} else {
					$loginAlert = '
					<div id="alertCont">
						<p id="alertText">An error occurred.</p>
					</div>';
				}
				if ($mysqliConnection -> more_results()) {
					$mysqliConnection -> next_result();
					if ($queriedMarketStats = $mysqliConnection -> store_result()) {
						while ($assocMarketStats = $queriedMarketStats -> fetch_assoc()) {
							if (!empty($assocMarketStats["marketName"])) {
								$marketLogoPath = empty(glob("uploads/marketLogos/{$assocMarketStats["marketID"]}.*")) ? "Assets/global/imageNotFound.png" : glob("uploads/marketLogos/{$assocMarketStats["marketID"]}.*")[0];
								$escapedMarketName = htmlspecialchars($assocMarketStats["marketName"], ENT_QUOTES);
								$escapedMarketInfo = htmlspecialchars($assocMarketStats["biography"], ENT_QUOTES);
								$allTimeRating = empty($assocMarketStats["avgRAllTime"]) ? 0 : $assocMarketStats["avgRAllTime"];
								$lastMonthRating = empty($assocMarketStats["avgRPastMonth"]) ? 0 : $assocMarketStats["avgRPastMonth"];
								$marketStatsHTML .= "
								<div class='marketDisplayRow infoRow'>
									<img src='{$marketLogoPath}' alt='Market Logo' class='marketLogoImage'>
									<div class='marketNameAndBioCont infoColumnRow'>
										<button class='marketMenuButtonCont'>
											<svg class='marketMenuButton' width='5' height='20'>
												<circle cx='2.5' cy='2.5' r='2.5' class='marketMenuButtonDot'/>
												<circle cx='2.5' cy='10' r='2.5' class='marketMenuButtonDot'/>
												<circle cx='2.5' cy='17.5' r='2.5' class='marketMenuButtonDot'/>
											</svg>
											<span class='hidePopUp marketMenuPopUp'>
												<a href='https://streetor.sg/marketplace/edit/?id={$assocMarketStats["marketID"]}' class='notSelectable marketMenuPopUpLink'>
													Manage
													<div class='marketMenuPopUpTail'></div>
												</a>
											</span>
										</button>
										<a href='https://streetor.sg/marketplace/?id={$assocMarketStats["marketID"]}' class='marketName'>{$escapedMarketName}</a>
										<p class='biographyText'>{$escapedMarketInfo}</p>
									</div>
								</div>
								<div class='marketStatsRow'>
									<div class='subscriberStatColumn'>
										<p class='subscriberStatLabel'>Subscribers</p>
										<div class='lastMonthStatsCont'>
											<p class='lastMonthStatsLabel'>Last 30 Days</p>
											<p class='subscriberLabel'>{$assocMarketStats["avgSPastMonth"]}</p>
										</div>
										<div class='allTimeStatsCont'>
											<p class='allTimeStatsLabel'>All Time</p>
											<p class='subscriberLabel'>{$assocMarketStats["avgSAllTime"]}</p>
										</div>
									</div>
									<div class='marketRatingStatColumn'>
										<p class='marketRatingStatLabel'>Ratings</p>
										<div class='lastMonthStatsCont'>
											<p class='lastMonthStatsLabel'>Last 30 Days</p>
											<div class='bestStatProductRatingRow'>
												<p class='ratingLabel'>{$lastMonthRating}</p>
												<svg height='18' width='18' class='productRatingStar'>
													<defs>
														<linearGradient id='starGradient'>
															<stop offset='100%' stop-color='#e1c900'></stop>
														</linearGradient>
													</defs>
													<polygon points='9,0 4,18 18,7 0,7 15,18' style='fill: url(#starGradient);'></polygon>
												</svg>
											</div>
										</div>
										<div class='allTimeStatsCont'>
											<p class='allTimeStatsLabel'>All Time</p>
											<div class='bestStatProductRatingRow'>
												<p class='ratingLabel'>{$allTimeRating}</p>
												<svg height='18' width='18' class='productRatingStar'>
													<defs>
														<linearGradient id='starGradient'>
															<stop offset='100%' stop-color='#e1c900'></stop>
														</linearGradient>
													</defs>
													<polygon points='9,0 4,18 18,7 0,7 15,18' style='fill: url(#starGradient);'></polygon>
												</svg>
											</div>
										</div>
									</div>
								</div>";
							} else {
								$marketStatsHTML = "<p class='inputErrorText' id='marketStatsError'>None at the moment.</p>";
							}
						}
					} else {
						$loginAlert = '
						<div id="alertCont">
							<p id="alertText">An error occurred.</p>
						</div>';
					}
				} else {
					$marketStatsHTML = "<p class='inputErrorText' id='marketStatsError'>None at the moment.</p>";
				}
				$homePageHTML = "
				<div id='confirmationOverlay'>
					<div id='confirmationDialog'>
						<p id='confirmationText'></p>
						<div id='confirmationButtonCont'>
							<button id='confirmButton'>Confirm</button>
							<button id='cancelButton'>Cancel</button>
						</div>
					</div>
				</div>
				<div id='informationCont'>
					<h1 id='loginText'>Hello admin!</h1>
					<p id='websiteInfoText'>Here are the statistics of your shops and products.</p>
				</div>
				<div id='bestStatProductWrapper' class='infoColumnRow'>
					<div id='bestStatProductRow' class='infoColumnRow'>
						<p id='bestStatProductLabel'>Best Product (Statistically)</p>
						<div id='bestStatProductStatsCont'>
							{$bestProductStatsHTML}
						</div>
					</div>
				</div>
				<div id='marketStatsWrapper' class='infoColumnRow'>
					<p id='marketStatsLabel'>Market Statistics</p>
					<div class='marketContentsRow'>
						{$marketStatsHTML}
					</div>
				</div>";
			} else {
				$loginAlert = '
				<div id="alertCont">
					<p id="alertText">An error occurred.</p>
				</div>';
			}
		} else {
			$selectStatsQuery = "SELECT COUNT(*) AS userCount, (SELECT COUNT(*) FROM marketdetails) AS marketCount
			FROM accountdetails";
			if ($queriedStats = $mysqliConnection -> query($selectStatsQuery)) {
				if ($assocQueriedStats = $queriedStats -> fetch_assoc()) {
					$homePageHTML = "
					<div id='informationCont'>
						<h1 id='loginText'>Hello Guest!</h1>
						<p id='websiteInfoText'>Streetor is a website for shops to put their products on display for users to browse from the comfort of their own home. Shop owners can also view statistics on the product ratings and subscribers in the past 30 days, or all time. <a href='https://streetor.sg/login/' class='hyperlinks'>Log in</a> or <a href='https://streetor.sg/signup/' class='hyperlinks'>sign up</a> to get access to features like giving product ratings and subscribing to shops to get notifications when shops release new products or discounts.</p>
					</div>
					<div id='countsRow' class='infoRow'>
						<div id='userCountCont'>
							<p id='userCountLabel'>{$assocQueriedStats["userCount"]}</p>
							<p id='usersLabel'>Account(s) Registered</p>
						</div>
						<div id='marketCountCont'>
							<p id='marketCountLabel'>{$assocQueriedStats["marketCount"]}</p>
							<p id='marketsLabel'>Shop(s) Registered</p>
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
					<p id="alertText">An error occurred.</p>
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
			<title>Home · Streetor</title>
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
					<a href='https://streetor.sg' id='homeLink'>
						<div class='innerLinksCont'>
							<div id='homeImage' class='sideNavImage'></div>
							<p id='homeText' class='notSelectable'>Home</p>
						</div>
					</a>
					<a href='https://streetor.sg/profiles/' id='profilesLink'>
						<div class='innerLinksCont'>
							<div id='profilesImage' class='sideNavImage'></div>
							<p id='profilesText' class='notSelectable'>Profiles</p>
						</div>
					</a>
					<a href='https://streetor.sg/settings/' id='settingsLink'>
						<div class='innerLinksCont'>
							<div id='settingsImage' class='sideNavImage'></div>
							<p id='settingsText' class='notSelectable'>Settings</p>
						</div>
					</a>
					<a href='https://streetor.sg/marketplace/' id='marketplaceLink'>
						<div class='innerLinksCont'>
							<div id='marketplaceImage' class='sideNavImage'></div>
							<p id='marketplaceText' class='notSelectable'>Marketplace</p>
						</div>
					</a>
					<a href='https://streetor.sg/privacy/' id='privacyLink'>
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
					{$homePageHTML}
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
			<title>Home · Streetor</title>
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
					<a href='https://streetor.sg' id='homeLink'>
						<div class='innerLinksCont'>
							<div id='homeImage' class='sideNavImage'></div>
							<p id='homeText' class='notSelectable'>Home</p>
						</div>
					</a>
					<a href='https://streetor.sg/profiles/' id='profilesLink'>
						<div class='innerLinksCont'>
							<div id='profilesImage' class='sideNavImage'></div>
							<p id='profilesText' class='notSelectable'>Profiles</p>
						</div>
					</a>
					<a href='https://streetor.sg/settings/' id='settingsLink'>
						<div class='innerLinksCont'>
							<div id='settingsImage' class='sideNavImage'></div>
							<p id='settingsText' class='notSelectable'>Settings</p>
						</div>
					</a>
					<a href='https://streetor.sg/marketplace/' id='marketplaceLink'>
						<div class='innerLinksCont'>
							<div id='marketplaceImage' class='sideNavImage'></div>
							<p id='marketplaceText' class='notSelectable'>Marketplace</p>
						</div>
					</a>
					<a href='https://streetor.sg/privacy/' id='privacyLink'>
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
					{$loginAlert}
				</div>
			</main>
			<footer>
			</footer>
		</body>
	</html>";
}
?>