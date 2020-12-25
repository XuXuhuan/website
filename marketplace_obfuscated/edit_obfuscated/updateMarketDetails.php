<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$assocReturn = array("message" => "");
$emailHeaders[] = "MIME-Version: 1.0";
$emailHeaders[] = "Content-type:text/html; charset=utf-8";
$emailHeaders[] = "From: <noreply@streetor.sg>";
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength / 2));
}
if ($mysqliConnection -> connect_errno) {
	$assocReturn["message"] = "A connection error occurred. Please try again later.";
} else {
	if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
		$usedMethod = empty($_POST) ? json_decode(file_get_contents("php://input"), true) : $_POST;
		if (empty($usedMethod["type"]) && empty($usedMethod["id"])) {
			$assocReturn["message"] = "Invalid request.";
		} else {
			$escapedMarketID = $mysqliConnection -> real_escape_string($usedMethod["id"]);
			$checkIfUserIsOwnerQuery = "SELECT marketOwner
			FROM marketdetails
			WHERE marketID = '{$escapedMarketID}'
			AND marketOwner = '{$_SESSION["userID"]}'";
			if ($queriedUserIsOwner = $mysqliConnection -> query($checkIfUserIsOwnerQuery)) {
				if ($queriedUserIsOwner -> num_rows > 0) {
					switch($usedMethod["type"]) {
						case 1:
							$escapedContent = $mysqliConnection -> real_escape_string($usedMethod["value"]);
							$checkMarketNameQuery = "SELECT marketName
							FROM marketdetails
							WHERE marketName = '{$escapedContent}'";
							if ($queriedMarketName = $mysqliConnection -> query($checkMarketNameQuery)) {
								if ($queriedMarketName -> num_rows === 0) {
									$updateMarketNameQuery = "UPDATE marketdetails
									SET marketName = '{$escapedContent}'
									WHERE marketID = '{$usedMethod["id"]}'";
									if ($mysqliConnection -> query($updateMarketNameQuery)) {
										$assocReturn["message"] = "Market name updated.";
									} else {
										$assocReturn["message"] = "An error occurred.";
									}
								} else {
									$assocReturn["message"] = "Market name already used.";
								}
								$queriedMarketName -> free();
							} else {
								$assocReturn["message"] = "An error occurred.";
							}
						break;
						case 2:
							$changeContent = $usedMethod["value"];
							if (strlen($changeContent) > 500) {
								$assocReturn["message"] = "Market info too long. The maximum number of characters is 500.";
							}
							else if (!empty(trim($changeContent))) {
								$replacedContent = preg_replace("/^[ \t\r\n\v\f]{2,}$/m", "", $changeContent);
								$escapedReplacedContent = $mysqliConnection -> real_escape_string($replacedContent);
								$updateBioQuery = "UPDATE marketdetails
								SET biography = '{$escapedReplacedContent}'
								WHERE marketID = '{$escapedMarketID}'";
								if ($mysqliConnection -> query($updateBioQuery)) {
									$assocReturn["message"] = "Market info updated.";
									$assocReturn["isError"] = false;
								} else {
									$assocReturn["message"] = "An error occurred.";
									$assocReturn["isError"] = true;
								}
							} else {
								$updateBioQuery = "UPDATE marketdetails
								SET biography = NULL
								WHERE marketID = '{$escapedMarketID}'";
								if ($mysqliConnection -> query($updateBioQuery)) {
									$assocReturn["message"] = "Market info updated.";
									$assocReturn["isError"] = false;
								} else {
									$assocReturn["message"] = "An error occurred.";
									$assocReturn["isError"] = true;
								}
							}
						break;
						case 3:
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
							$updateLines = array();
							foreach($availableCategories as $category) {
								if (in_array($category, $usedMethod["categories"])) {
									$updateLines[] = "{$category} = 1";
								} else {
									$updateLines[] = "{$category} = 0";
								}
							}
							$formattedUpdateLines = implode(",\n", $updateLines);
							$updateMarketCategories = "UPDATE marketdetails
							SET {$formattedUpdateLines}
							WHERE marketID = {$usedMethod["id"]}";
							if ($mysqliConnection -> query($updateMarketCategories)) {
								$assocReturn["message"] = "Categories updated.";
								$assocReturn["isError"] = false;
							} else {
								$assocReturn["message"] = "An error occurred.";
								$assocReturn["isError"] = true;
							}
						break;
						case 4:
							$selectNeededDetailsQuery = "SELECT accountdetails.firstName, accountdetails.email, marketdetails.marketName
							FROM accountdetails
							JOIN marketdetails
							ON accountdetails.accountID = marketdetails.marketOwner
							WHERE accountdetails.accountID = '{$_SESSION["userID"]}'";
							if ($queriedNeededDetailsQuery = $mysqliConnection -> query($selectNeededDetailsQuery)) {
								if ($queriedNeededDetailsQuery -> num_rows > 0) {
									if ($assocNeededDetailsQuery = $queriedNeededDetailsQuery -> fetch_assoc()) {
										$dbFirstName = htmlspecialchars($assocNeededDetailsQuery["firstName"], ENT_QUOTES);
										$dbMarketName = htmlspecialchars($assocNeededDetailsQuery["marketName"], ENT_QUOTES);

										$randomString = getRandomString(50);
										$_SESSION["marketDeletionToken"] = isset($_SESSION["marketDeletionToken"]) ? $_SESSION["marketDeletionToken"] : $randomString;
										$verificationEmailDOM = "
										<!DOCTYPE html>
										<html>
											<head>
												<title>Market Deletion · Streetor</title>
												<link href='https://fonts.googleapis.com/css2?family=Baloo+Da+2&family=Montserrat&family=Roboto&display=swap' rel='stylesheet'>
												<style>
													body {
														margin: 0;
														background-color: #ececec;
													}
													#outerContainer {
														border-collapse: collapse;
													}
													#outerMain {
														background-color: #ececec
													}
													#mainContainer, #bodyContainer, #footerContainer {
														border-collapse: collapse;
														width: 100%;
														max-width: 600px;
													}
													#mainContainer {
														display: block;
														margin-left: auto;
														margin-right: auto;
													}
													#headerRow {
														height: 10vh;
														width: 100%;
														background-color: #0e0f2c;
														text-align: center;
														color: #ffffff;
														font-family: 'Montserrat', Verdana, sans-serif;
														font-size: 30px;
													}
													#bodyContainer > tr > td {
														background-color: #ffffff;
													}
													#helloText {
														font-family: 'Roboto', Helvetica, sans-serif;
													}
													#infoText {
														text-indent: 2em;
														font-family: 'Baloo Da 2', Arial, sans-serif;
														padding-bottom: 20px;
													}
													#verificationLink {
														color: #ffffff;
														display: table-cell;
														height: 50px;
														width: 250px;
														text-align: center;
														vertical-align: middle;
														font-family: 'Roboto', Helvetica, sans-serif;
														font-size: 24px;
														background-color: #E60505;
														text-decoration: none;
													}
													#websiteLabel {
														text-align: center;
														font-family: 'Montserrat', Verdana, sans-serif;
													}
													#footerContainer > tr > td {
														color: #ffffff;
														background-color: #0e0f2c;
													}
													#websiteLabel {
														padding-top: 20px;
													}
													#contactCell {
														text-align: center;
														font-family: 'Roboto', Helvetica, sans-serif;
														padding-top: 10px;
														padding-bottom: 10px;
													}
												</style>
											</head>
											<body>
												<table id='outerContainer' width='100%' border='0' cellspacing='0' cellpadding='0' align='center' bgcolor='#38444a'>
													<tr>
														<td id='outerMain'>
															<table id='mainContainer'>
																<thead>
																	<tr>
																		<td id='headerRow'>STREETOR</td>
																	</tr>
																</thead>
																<tbody id='bodyContainer'>
																	<tr>
																		<td>
																			<h1 id='helloText'>Hello {$dbFirstName},</h1>
																		</td>
																	</tr>
																	<tr>
																		<td id='infoText'>Your market, {$dbMarketName}, has been requested for deletion. To verify that this is you, click the link shown below. This link is valid for 10 minutes.</td>
																	</tr>
																	<tr>
																		<td align='center' style='padding-bottom: 20px;'>
																			<a id='verificationLink' href='https://www.streetor.sg/marketDeletion/?id={$usedMethod["id"]}&token={$_SESSION["marketDeletionToken"]}'>
																				Delete Market
																			</a>
																		</td>
																	</tr>
																</tbody>
																<tfoot id='footerContainer'>
																	<tr>
																		<td id='websiteLabel'>streetor.sg</td>
																	</tr>
																	<tr>
																		<td id='contactCell'>
																			<a href='mailto:support@streetor.sg'>Contact Support</a>
																		</td>
																	</tr>
																</tfoot>
															</table>
														</td>
													</tr>
												</table>
											</body>
										</html>";
										$updateMarketDetailsQuery = "UPDATE marketdetails
										SET marketDeletionToken = '{$_SESSION["marketDeletionToken"]}',
										marketDeletionTime = NOW()
										WHERE marketID = '{$usedMethod["id"]}'";
										if ($mysqliConnection -> query($updateMarketDetailsQuery)) {
											if (mail($assocNeededDetailsQuery["email"], "Market Deletion", $verificationEmailDOM, implode(PHP_EOL, $emailHeaders))) {
												$assocReturn["leftoverCooldown"] = 120;
												$assocReturn["message"] = "A verification email has been sent.";
											} else {
												$assocReturn["message"] = "An error occurred and no email was sent.";
											}
										} else {
											$assocReturn["message"] = "An error occurred.";
										}
									} else {
										$assocReturn["message"] = "An error occurred.";
									}
								} else {
									$assocReturn["message"] = "An error occurred.";
								}
								$queriedNeededDetailsQuery -> free();
							} else {
								$assocReturn["message"] = "An error occurred.";
							}
						break;
						default:
							$assocReturn["message"] = "Invalid request.";
						break;
					}
				} else {
					$assocReturn["message"] = "Market unavailable.";
					if ($usedMethod["type"] == 2 || $usedMethod["type"] == 3) {
						$assocReturn["isError"] = true;
					}
				}
				$queriedUserIsOwner -> free();
			} else {
				$assocReturn["message"] = "An error occurred.";
			}
		}
	} else {
		$assocReturn["message"] = "Please log in and try again.";
	}
}
echo json_encode($assocReturn);
$mysqliConnection -> close();
?>