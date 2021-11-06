<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$logoutOrLogin = '<p id="logLabel" class="notSelectable">Logout</p>';
$logoutOrLoginScript = '
<script>"use strict";const _0x2f34=["../../lo","Micro","text","stene","Selec","POST","XMLHt","php","addEv","soft.","#logL","respo","tpReq","click","entLi","abel","uest","XMLHT","onloa","tor","href","locat","send","gout.","open","ion","nseTy","query","nseTe"];(function(_0x5e9acd,_0x2f344a){const _0x41584a=function(_0x419e75){while(--_0x419e75){_0x5e9acd["push"](_0x5e9acd["shift"]());}};_0x41584a(++_0x2f344a);}(_0x2f34,0x1f1));const _0x4158=function(_0x5e9acd,_0x2f344a){_0x5e9acd=_0x5e9acd-0x0;let _0x41584a=_0x2f34[_0x5e9acd];return _0x41584a;};const _0x5dd2de=document[_0x4158("0x17")+_0x4158("0x0")+_0x4158("0xf")](_0x4158("0x6")+_0x4158("0xb"));var _0x2d4945;_0x5dd2de[_0x4158("0x4")+_0x4158("0xa")+_0x4158("0x1c")+"r"](_0x4158("0x9"),function(){clearTimeout(_0x2d4945);_0x2d4945=setTimeout(function(){const _0x4e392c=window[_0x4158("0x2")+_0x4158("0x8")+_0x4158("0xc")]?new XMLHttpRequest():new ActiveXObject(_0x4158("0x1a")+_0x4158("0x5")+_0x4158("0xd")+"TP");_0x4e392c[_0x4158("0x14")](_0x4158("0x1"),_0x4158("0x19")+_0x4158("0x13")+_0x4158("0x3"),!![]);_0x4e392c[_0x4158("0x7")+_0x4158("0x16")+"pe"]=_0x4158("0x1b");_0x4e392c[_0x4158("0xe")+"d"]=function(){window[_0x4158("0x11")+_0x4158("0x15")][_0x4158("0x10")]=_0x4e392c[_0x4158("0x7")+_0x4158("0x18")+"xt"];};_0x4e392c[_0x4158("0x12")]();},0x15e);});</script>';
$pageTitle = "";
$productRows = "";
$searchError = "";
$headStyles = "";
$maxResults = 0;
$numberOfRatings = 0;
$averageRating = 0;
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength / 2));
}
if (isset($_COOKIE["darktheme"]) && $_COOKIE["darktheme"] === "false") {
	$stylesheetLink = "productsLightTheme.css";
} else {
	$stylesheetLink = "productsDarkTheme.css";
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
										setcookie("logincookie", json_encode($newCookieValuesDecoded), strtotime("9999-12-31"), "/", "streetor.sg", true, true);
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
									header("Location: https://streetor.sg/login/");
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
			}
		}
		if (!empty($_GET["prodid"])) {
			if (!preg_match("/[^0-9]/i", $_GET["prodid"])) {
				$escapedProductID = $mysqliConnection -> real_escape_string($_GET["prodid"]);
				$selectProductDetailsQuery = "SELECT marketdetails.marketName, marketproducts.marketID, marketproducts.productID, marketproducts.productName, marketproducts.productInfo, marketproducts.pricing, COUNT(ratings.productID = '{$escapedProductID}') AS numberOfRatings, AVG(ratings.rating) AS averageRating
				FROM marketproducts
				LEFT JOIN ratings
				ON ratings.productID = marketproducts.productID
				JOIN marketdetails
				ON marketdetails.marketID = marketproducts.marketID
				WHERE marketproducts.productID = '{$escapedProductID}'";
				$firstProductImageURL;
				$productImageURLs;
				$firstStarGradient;
				$secondStarGradient;
				$thirdStarGradient;
				$fourthStarGradient;
				$fifthStarGradient;
				if ($queriedProductDetails = $mysqliConnection -> query($selectProductDetailsQuery)) {
					if ($queriedProductDetails -> num_rows > 0) {
						if ($assocProductDetails = $queriedProductDetails -> fetch_assoc()) {
							if (!empty($assocProductDetails["productID"])) {
								$numberOfRatings = $assocProductDetails["numberOfRatings"];
								$averageRating = empty($assocProductDetails["averageRating"]) ? 0 : $assocProductDetails["averageRating"];
								$foundProductImages = glob("../../uploads/productPictures/{$assocProductDetails["productID"]}/*.png");
								$productPricing = number_format($assocProductDetails["pricing"], 2);
								$firstStarGradient = $averageRating >= 1 ? 100 : $averageRating * 100;
								$secondStarGradient = $averageRating >= 2 ? 100 : ($averageRating - 1) * 100;
								$thirdStarGradient = $averageRating >= 3 ? 100 : ($averageRating - 2) * 100;
								$fourthStarGradient = $averageRating >= 4 ? 100 : ($averageRating - 3) * 100;
								$fifthStarGradient = $averageRating === 5 ? 100 : ($averageRating - 4) * 100;
								$pageTitle = htmlspecialchars($assocProductDetails["productName"], ENT_QUOTES);
								$escapedProductName = htmlspecialchars($assocProductDetails["productName"], ENT_QUOTES);
								$marketName = htmlspecialchars($assocProductDetails["marketName"], ENT_QUOTES);
								$productInfo = empty($assocProductDetails["productInfo"]) ? "No product information found." : nl2br(htmlspecialchars($assocProductDetails["productInfo"], ENT_QUOTES));
								if (!empty($foundProductImages)) {
									$firstProductImageURL = "url({$foundProductImages[0]})";
									foreach($foundProductImages as $eachImageURL) {
										$productImageURLs .= " url({$eachImageURL})";
									}
								} else {
									$productImageURLs = "url(../../Assets/global/imageNotFound.png)";
									$firstProductImageURL = "url(../../Assets/global/imageNotFound.png)";
								}
								$headStyles = "
								<style>
									body::before {
										position: absolute;
										width: 0;
										height: 0;
										overflow: hidden;
										z-index: -1;
										content:{$productImageURLs};
									}
								</style>";
								$productPageHTML = "
								<div id='mainCont'>
									<p id='marketID' style='display: none'>{$assocProductDetails["marketID"]}</p>
									<a href='https://streetor.sg/marketplace/register/' id='registerMarketplaceLink' class='notSelectable'>
										<div id='registerMarketplaceImageCont'></div>
										Register
									</a>
									<div id='productContents'>
										<div id='searchFormCont'>
											<div id='searchForm'>
												<label for='productSearchField' id='productSearchLabel'>Search</label>
												<div id='searchBarCont'>
													<input autocomplete='off' type='text' name='query' id='productSearchField' placeholder='Search In {$marketName}'>
													<button id='productSearchButton' type='submit'>
														<div id='productSearchImage'></div>
													</button>
												</div>
												<p class='inputErrorText' id='searchErrorText'></p>
											</div>
										</div>
										<div id='productDetailsContainer'>
											<div id='mainDetailsCont'>
												<div id='productImageScroller' style='background-image: {$firstProductImageURL}'></div>
												<h2 id='productNameLabel'>{$escapedProductName}</h2>
												<p id='pricingLabel'>S\${$productPricing}</p>
												<p id='productInfoText'>{$productInfo}</p>
											</div>
											<div id='ratingStarRow'>
												<p id='ratingLabel'>{$averageRating} ({$numberOfRatings})</p>
												<div id='ratingStarCont'>
													<div id='firstStarOuterCont'>
														<svg height='18' width='18' id='firstStar' class='ratingStar'>
															<defs>
																<linearGradient class='starGradient' id='firstStarGradient'>
																	<stop offset='{$firstStarGradient}%' stop-color='#e1c900' class='filledStop'></stop>
																	<stop offset='{$firstStarGradient}%' stop-color='#000000' class='unfilledStop'></stop>
																</linearGradient>
															</defs>
															<polygon points='9,0 4,18 18,7 0,7 15,18' style='fill: url(#firstStarGradient);'></polygon>
														</svg>
														<div id='firstStarLeftHalf' class='ratingStarLeftHalf'></div>
														<div id='firstStarRightHalf' class='ratingStarRightHalf'></div>
													</div>
													<div id='secondStarOuterCont'>
														<svg height='18' width='18' id='secondStar' class='ratingStar'>
															<defs>
																<linearGradient class='starGradient' id='secondStarGradient'>
																	<stop offset='{$secondStarGradient}%' stop-color='#e1c900' class='filledStop'></stop>
																	<stop offset='{$secondStarGradient}%' stop-color='#000000' class='unfilledStop'></stop>
																</linearGradient>
															</defs>
															<polygon points='9,0 4,18 18,7 0,7 15,18' style='fill: url(#secondStarGradient);'></polygon>
														</svg>
														<div id='secondStarLeftHalf' class='ratingStarLeftHalf'></div>
														<div id='secondStarRightHalf' class='ratingStarRightHalf'></div>
													</div>
													<div id='thirdStarOuterCont'>
														<svg height='18' width='18' id='thirdStar' class='ratingStar'>
															<defs>
																<linearGradient class='starGradient' id='thirdStarGradient'>
																	<stop offset='{$thirdStarGradient}%' stop-color='#e1c900' class='filledStop'></stop>
																	<stop offset='{$thirdStarGradient}%' stop-color='#000000' class='unfilledStop'></stop>
																</linearGradient>
															</defs>
															<polygon points='9,0 4,18 18,7 0,7 15,18' style='fill: url(#thirdStarGradient);'></polygon>
														</svg>
														<div id='thirdStarLeftHalf' class='ratingStarLeftHalf'></div>
														<div id='thirdStarRightHalf' class='ratingStarRightHalf'></div>
													</div>
													<div id='fourthStarOuterCont'>
														<svg height='18' width='18' id='fourthStar' class='ratingStar'>
															<defs>
																<linearGradient class='starGradient' id='fourthStarGradient'>
																	<stop offset='{$fourthStarGradient}%' stop-color='#e1c900' class='filledStop'></stop>
																	<stop offset='{$fourthStarGradient}%' stop-color='#000000' class='unfilledStop'></stop>
																</linearGradient>
															</defs>
															<polygon points='9,0 4,18 18,7 0,7 15,18' style='fill: url(#fourthStarGradient);'></polygon>
														</svg>
														<div id='fourthStarLeftHalf' class='ratingStarLeftHalf'></div>
														<div id='fourthStarRightHalf' class='ratingStarRightHalf'></div>
													</div>
													<div id='fifthStarOuterCont'>
														<svg height='18' width='18' id='fifthStar' class='ratingStar'>
															<defs>
																<linearGradient class='starGradient' id='fifthStarGradient'>
																	<stop offset='{$fifthStarGradient}%' stop-color='#e1c900' class='filledStop'></stop>
																	<stop offset='{$fifthStarGradient}%' stop-color='#000000' class='unfilledStop'></stop>
																</linearGradient>
															</defs>
															<polygon points='9,0 4,18 18,7 0,7 15,18' style='fill: url(#fifthStarGradient);'></polygon>
														</svg>
														<div id='fifthStarLeftHalf' class='ratingStarLeftHalf'></div>
														<div id='fifthStarRightHalf' class='ratingStarRightHalf'></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>";
							} else {
								$loginAlert = '
								<div id="alertCont">
									<p id="alertText">This product is unavailable.</p>
								</div>';
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
							<p id="alertText">This product is unavailable.</p>
						</div>';
					}
					$queriedProductDetails -> free();
				} else {
					$loginAlert = '
					<div id="alertCont">
						<p id="alertText">An error occurred.</p>
					</div>';
				}
			} else {
				$loginAlert = '
				<div id="alertCont">
					<p id="alertText">You have entered an invalid product ID in the URL.</p>
				</div>';
			}
		}
		else if (!empty($_GET["marketid"]) && empty($_GET["query"])) {
			if (!preg_match("/[^0-9]/i", $_GET["marketid"])) {
				$escapedMarketID = $mysqliConnection -> real_escape_string($_GET["marketid"]);
				$selectProductsDetailsQuery = "SELECT marketproducts.productID, marketproducts.productName, marketproducts.productInfo, marketproducts.pricing, ratings.rating
				FROM marketproducts
				LEFT JOIN ratings
				ON marketproducts.productID = ratings.productID
				WHERE marketID = '{$escapedMarketID}'
				ORDER BY ratings.rating DESC, marketproducts.productName
				LIMIT 10;";
				$selectProductsDetailsQuery .= "SELECT COUNT(marketID = '{$escapedMarketID}') AS maxResults
				FROM marketproducts
				WHERE marketID = '{$escapedMarketID}'";
				$marketName;
				if ($mysqliConnection -> multi_query($selectProductsDetailsQuery)) {
					do {
						if ($queriedProductsDetails = $mysqliConnection -> store_result()) {
							if ($queriedProductsDetails -> num_rows > 0) {
								while ($assocProductsDetails = $queriedProductsDetails -> fetch_assoc()) {
									if (isset($assocProductsDetails["maxResults"])) {
										$maxResults = $assocProductsDetails["maxResults"];
									} else {
										$findProductImage = glob("../../uploads/productPictures/{$assocProductsDetails["productID"]}/1.png");
										$imageFileName = "../../Assets/global/imageNotFound.png";
										$escapedProductName = htmlspecialchars($assocProductsDetails["productName"], ENT_QUOTES);
										$escapedProductInfo = empty($assocProductsDetails["productInfo"]) ? '<b>No description found.</b>' : nl2br(htmlspecialchars($assocProductsDetails["productInfo"], ENT_QUOTES));
										$productRating = empty($assocProductsDetails["rating"]) ? 0 : $assocProductsDetails["rating"];
										$productPricing = number_format($assocProductsDetails["pricing"], 2);
										if (!empty($findProductImage)) {
											$imageFileName = $findProductImage[0];
										}
										$productRows .= "
										<div class='productContentsRow infoRow'>
											<img src='{$imageFileName}' alt='Product Image' class='productImage'>
											<div class='productNameAndInfoCont infoColumnRow'>
												<a href='https://streetor.sg/marketplace/products/?prodid={$assocProductsDetails["productID"]}' class='productName'>{$escapedProductName}</a>
												<p class='pricingInfoLabel'>S\${$productPricing}</p>
												<p class='productInfoText'>{$escapedProductInfo}</p>
												<div class='productRatingRow'>
													<p class='ratingLabel'>{$productRating}</p>
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
									}
								}
							} else {
								$searchError = "No results found.";
							}
							$queriedProductsDetails -> free();
						} else {
							$loginAlert = '
							<div id="alertCont">
							<p id="alertText">An error occurred.</p>
							</div>';
						}
					} while ($mysqliConnection -> next_result());
					$selectMarketNameQuery = "SELECT marketName
					FROM marketdetails
					WHERE marketID = '{$escapedMarketID}'";
					if ($queriedMarketName = $mysqliConnection -> query($selectMarketNameQuery)) {
						if ($queriedMarketName -> num_rows > 0) {
							if ($assocMarketName = $queriedMarketName -> fetch_assoc()) {
								$pageTitle = htmlspecialchars($assocMarketName["marketName"], ENT_QUOTES);
								$marketName = htmlspecialchars($assocMarketName["marketName"], ENT_QUOTES);
							} else {
								$loginAlert = '
								<div id="alertCont">
									<p id="alertText">An error occurred.</p>
								</div>';
							}
						} else {
							$loginAlert = '
							<div id="alertCont">
								<p id="alertText">This product may have been deleted. Please check again later.</p>
							</div>';
						}
						$queriedMarketName -> free();
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
				$currentResults = $maxResults >= 10 ? "10" : $maxResults;
				$resultCount = "<p id='resultCount'>{$currentResults} of {$maxResults} results</p>";
				$numberOfPages = ceil($maxResults / 10);
				$changePageHTML = empty($productRows) ? "" : 
				"<div class='infoColumnRow' id='changePageWrapper'>
					<div id='changePageCont'>
						" . ($maxResults <= 10 ? '' : "
						<button id='nextPageButton' onmouseup='rightArrowProductFetch(event)' onmousedown='cancelRightArrowIncrementTimeout(event)'>
							<div class='changePageArrowCont' id='rightArrowCont'></div>
						</button>") . "
					</div>
					<p id='pageCount' class='notSelectable'><input type='number' value='1' max='{$numberOfPages}' min='1' value='1' id='currentPageCount' onkeyup='countFieldMarketFetch(Event)' onkeydown='cancelCountFieldIncrementTimeout(Event)'> of <span id='maxPagesCount'>{$numberOfPages}</span> pages</p>
				</div>";
				$productPageHTML = "
				<div id='mainCont'>
					<a href='https://streetor.sg/marketplace/register/' id='registerMarketplaceLink' class='notSelectable'>
						<div id='registerMarketplaceImageCont'></div>
						Register
					</a>
					<div id='productContents'>
						<h1 id='pageHeader'>{$pageTitle}</h1>
						<div id='searchFormCont'>
							<div id='searchForm'>
								<label for='productSearchField' id='productSearchLabel'>Search</label>
								<div id='searchBarCont'>
									<input autocomplete='off' type='text' name='query' id='productSearchField' placeholder='Search In {$marketName}'>
									<button id='productSearchButton' type='submit'>
										<div id='productSearchImage'></div>
									</button>
								</div>
								<p class='inputErrorText' id='searchErrorText'>{$searchError}</p>
							</div>
						</div>
						{$resultCount}
						<div id='productsContainer'>
							{$productRows}
						</div>
						{$changePageHTML}
					</div>
				</div>";
			} else {
				$loginAlert = '
				<div id="alertCont">
					<p id="alertText">You have entered an invalid market ID in the URL.</p>
				</div>';
			}
		}
		else if (!empty($_GET["marketid"]) && !empty($_GET["query"])) {
			if (!preg_match("/[^0-9]/i", $_GET["marketid"])) {
				$escapedSearchQuery = $mysqliConnection -> real_escape_string($_GET["query"]);
				$escapedMarketID = $mysqliConnection -> real_escape_string($_GET["marketid"]);
				$selectProductsDetailsQuery = "SELECT marketproducts.productID, marketproducts.productName, marketproducts.productInfo, marketproducts.pricing, ratings.rating
				FROM marketproducts
				LEFT JOIN ratings
				ON marketproducts.productID = ratings.productID
				WHERE productName LIKE '%{$escapedSearchQuery}%'
				AND marketID = '{$escapedMarketID}'
				ORDER BY ratings.rating DESC, marketproducts.productName
				LIMIT 10;";
				$selectProductsDetailsQuery .= "SELECT COUNT(productName LIKE '%{$escapedSearchQuery}%') AS maxResults
				FROM marketproducts
				WHERE productName LIKE '%{$escapedSearchQuery}%'";
				$marketName;
				if ($mysqliConnection -> multi_query($selectProductsDetailsQuery)) {
					do {
						if ($queriedProductsDetails = $mysqliConnection -> store_result()) {
							if ($queriedProductsDetails -> num_rows > 0) {
								while ($assocProductsDetails = $queriedProductsDetails -> fetch_assoc()) {
									if (isset($assocProductsDetails["maxResults"])) {
										$maxResults = $assocProductsDetails["maxResults"];
									} else {
										if (!empty($assocProductsDetails["productID"])) {
											$findProductImage = glob("../../uploads/productPictures/{$assocProductsDetails["productID"]}/1.png");
											$imageFileName = "../../Assets/global/imageNotFound.png";
											$escapedProductName = htmlspecialchars($assocProductsDetails["productName"], ENT_QUOTES);
											$escapedProductInfo = empty($assocProductsDetails["productInfo"]) ? '<b>No description found.</b>' : nl2br(htmlspecialchars($assocProductsDetails["productInfo"], ENT_QUOTES));
											$productRating = empty($assocProductsDetails["rating"]) ? 0 : $assocProductsDetails["rating"];
											$productPricing = number_format($assocProductsDetails["pricing"], 2);
											if (!empty($findProductImage)) {
												$imageFileName = $findProductImage[0];
											}
											$productRows .= "
											<div class='productContentsRow infoRow'>
												<img src='{$imageFileName}' alt='Product Image' class='productImage'>
												<div class='productNameAndInfoCont infoColumnRow'>
													<a href='https://streetor.sg/marketplace/products/?prodid={$assocProductsDetails["productID"]}' class='productName'>{$escapedProductName}</a>
													<p class='pricingInfoLabel'>S\${$productPricing}</p>
													<p class='productInfoText'>{$escapedProductInfo}</p>
													<div class='productRatingRow'>
														<p class='ratingLabel'>{$productRating}</p>
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
											$maxResults = $assocProductsDetails["maxResults"];
										} else {
											$searchError = "No results found.";
										}
									}
								}
							} else {
								$searchError = "No results found.";
							}
							$queriedProductsDetails -> free();
						} else {
							$loginAlert = '
							<div id="alertCont">
								<p id="alertText">An error occurred.</p>
							</div>';
						}
					} while ($mysqliConnection -> next_result());
					$selectMarketNameQuery = "SELECT marketName
					FROM marketdetails
					WHERE marketID = '{$escapedMarketID}'";
					if ($queriedMarketName = $mysqliConnection -> query($selectMarketNameQuery)) {
						if ($queriedMarketName -> num_rows > 0) {
							if ($assocMarketName = $queriedMarketName -> fetch_assoc()) {
								$pageTitle = htmlspecialchars($assocMarketName["marketName"], ENT_QUOTES);
								$marketName = htmlspecialchars($assocMarketName["marketName"], ENT_QUOTES);
							} else {
								$loginAlert = '
								<div id="alertCont">
									<p id="alertText">An error occurred.</p>
								</div>';
							}
						} else {
							$loginAlert = '
							<div id="alertCont">
								<p id="alertText">This product may have been deleted. Please check again later.</p>
							</div>';
						}
						$queriedMarketName -> free();
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
				$sanitisedSearchQuery = htmlspecialchars($_GET["query"], ENT_QUOTES);
				$currentResults = $maxResults >= 10 ? "10" : $maxResults;
				$resultCount = empty($_GET["query"]) ? "" : "<p id='resultCount'>{$currentResults} of {$maxResults} results</p>";
				$numberOfPages = ceil($maxResults / 10);
				$changePageHTML = empty($productRows) ? "" : 
				"<div class='infoColumnRow' id='changePageWrapper'>
					<div id='changePageCont'>
						" . ($maxResults <= 10 ? '' : "
						<button id='nextPageButton' onmouseup='rightArrowProductFetch(event)' onmousedown='cancelRightArrowIncrementTimeout(event)'>
							<div class='changePageArrowCont' id='rightArrowCont'></div>
						</button>") . "
					</div>
					<p id='pageCount' class='notSelectable'><input type='number' value='1' max='{$numberOfPages}' min='1' value='1' id='currentPageCount' onkeyup='countFieldMarketFetch(Event)' onkeydown='cancelCountFieldIncrementTimeout(Event)'> of <span id='maxPagesCount'>{$numberOfPages}</span> pages</p>
				</div>";
				$productPageHTML = "
				<div id='mainCont'>
					<a href='https://streetor.sg/marketplace/register/' id='registerMarketplaceLink' class='notSelectable'>
						<div id='registerMarketplaceImageCont'></div>
						Register
					</a>
					<div id='productContents'>
						<h1 id='pageHeader'>{$pageTitle}</h1>
						<div id='searchFormCont'>
							<div id='searchForm'>
								<label for='productSearchField' id='productSearchLabel'>Search</label>
								<div id='searchBarCont'>
									<input autocomplete='off' type='text' value='{$sanitisedSearchQuery}' name='query' id='productSearchField' placeholder='Search In {$marketName}'>
									<button id='productSearchButton' type='submit'>
										<div id='productSearchImage'></div>
									</button>
								</div>
								<p class='inputErrorText' id='searchErrorText'>{$searchError}</p>
							</div>
						</div>
						{$resultCount}
						<div id='productsContainer'>
							{$productRows}
						</div>
						{$changePageHTML}
					</div>
				</div>";
			} else {
				$loginAlert = '
				<div id="alertCont">
					<p id="alertText">You have entered an invalid market ID in the URL.</p>
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
			<title>{$pageTitle} · Streetor</title>
			{$headStyles}
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
					<p id='notificationText'>An error occurred.</p>
				</div>
			</header>
			<main>
				{$logoutOrLoginScript}
				{$productPageHTML}
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
			<title>Error · Streetor</title>
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