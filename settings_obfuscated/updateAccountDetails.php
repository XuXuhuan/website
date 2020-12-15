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
		$changeType = $_POST["type"];
		if (empty($changeType) && empty(json_decode(file_get_contents("php://input"), true)["type"])) {
			$assocReturn["message"] = "Invalid request.";
		}
		else if (json_decode(file_get_contents("php://input"), true)["type"] === 4) {
			$changeContent = json_decode(file_get_contents("php://input"), true)["content"];
			if (strlen($changeContent) > 200) {
				$assocReturn["message"] = "This biography is too long. The maximum number of characters is 200.";
			}
			else if (!empty(trim($changeContent))) {
				$replacedContent = preg_replace("/^[ \t\r\n\v\f]{2,}$/m", "", $changeContent);
				$escapedReplacedContent = $mysqliConnection -> real_escape_string($replacedContent);
				$updateBioQuery = "UPDATE accountdetails
				SET biography = '{$escapedReplacedContent}'
				WHERE accountID = '{$_SESSION["userID"]}'";
				if ($mysqliConnection -> query($updateBioQuery)) {
					$assocReturn["message"] = "Biography updated.";
				} else {
					$assocReturn["message"] = "An error occurred.";
				}
			} else {
				$updateBioQuery = "UPDATE accountdetails
				SET biography = NULL
				WHERE accountID = '{$_SESSION["userID"]}'";
				if ($mysqliConnection -> query($updateBioQuery)) {
					$assocReturn["message"] = "Market info updated.";
				} else {
					$assocReturn["message"] = "An error occurred.";
				}
			}
		} else {
			switch($changeType) {
				case "1": //change username
					$changeContent = $mysqliConnection -> real_escape_string($_POST["content"]);
					$assocReturn["leftoverCooldown"] = 0;
					if (preg_match("/[^a-z0-9._]/i", $changeContent) == true) {
						$assocReturn["message"] = "Username may only contain letters, numbers, . and _.";
					}
					else if (empty(trim($changeContent))) {
						$assocReturn["message"] = "This field is required.";
					}
					else if (strlen(trim($changeContent)) < 3) {
						$assocReturn["message"] = "Username must contain at least 3 characters.";
					}
					else if (strlen($changeContent) > 20) {
						$assocReturn["message"] = "Username exceeds the 20 character limit.";
					} else {
						$randomString = getRandomString(50);
						$_SESSION["userChangeToken"] = isset($_SESSION["userChangeToken"]) ? $_SESSION["userChangeToken"] : $randomString;
						$selectNeededDetailsQuery = "SELECT firstName, username, email, userChangeTime
													FROM accountdetails
													WHERE accountID = '{$_SESSION["userID"]}'";
						if ($queriedNeededDetails = $mysqliConnection -> query($selectNeededDetailsQuery)) {
							if ($queriedNeededDetails -> num_rows > 0) {
								if ($assocNeededDetails = $queriedNeededDetails -> fetch_assoc()) {
									$dbUsername = $assocNeededDetails["username"];
									$dbFirstName = $assocNeededDetails["firstName"];
									$dbEmail = $assocNeededDetails["email"];
									$dbLastSentTime = $assocNeededDetails["userChangeTime"];
									if ((time() - 120) < strtotime($dbLastSentTime)) {
										$assocReturn["leftoverCooldown"] = strtotime($dbLastSentTime) + 120 - time();
										$assocReturn["message"] = "Please wait until the cooldown is over!";
									} else {
										$updateAccountDetailsQuery = "UPDATE accountdetails
																	SET userChangeTime = NOW(),
																	userChangeToken = '{$_SESSION["userChangeToken"]}',
																	newUsername = '{$changeContent}'
																	WHERE accountID = '{$_SESSION["userID"]}'";
										$emailDOM = "
										<!DOCTYPE html>
										<html>
											<head>
												<title>Username Change · Streetor</title>
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
														background-color: #06BA00;
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
																		<td id='infoText'>An account, under the name of {$dbUsername}, has requested to change its username to {$changeContent}. To verify that this is you, click the link shown below. This link is valid for 10 minutes.</td>
																	</tr>
																	<tr>
																		<td align='center' style='padding-bottom: 20px;'>
																			<a id='verificationLink' href='https://www.streetor.sg/changeUsername/?email={$dbEmail}&token={$_SESSION["userChangeToken"]}'>
																				Change Username
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
										if ($mysqliConnection -> query($updateAccountDetailsQuery)) {
											if (mail($dbEmail, "Username Change", $emailDOM, implode(PHP_EOL, $emailHeaders))) {
												$assocReturn["message"] = "An email has been sent to your email address for verification.";
												$assocReturn["leftoverCooldown"] = 120;
											} else {
												$assocReturn["message"] = "An error occurred and an email could not be sent to your email address.";
											}
										} else {
											$assocReturn["message"] = "An error occurred.";
										}
									}
								} else {
									$assocReturn["message"] = "An error occurred.";
								}
							} else {
								$assocReturn["message"] = "No records were found in the database. This account may have been deleted.";
							}
							$queriedNeededDetails -> free();
						} else {
							$assocReturn["message"] = "An error occurred.";
						}
					}
				break;
				case "2": //change password
					$changeContent = $mysqliConnection -> real_escape_string($_POST["content"]);
					$assocReturn["leftoverCooldown"] = 0;
					if (empty(preg_replace("/(strong(er)*)*(complex)*(password[0-9]{0,3})/i", "", $changeContent))) {
						$assocReturn["newPassError"] = "Please create a stronger password.";
					}
					else if (empty(trim($changeContent))) {
						$assocReturn["newPassError"] = "This field is required.";
					}
					else if (strlen($changeContent) < 8) {
						$assocReturn["newPassError"] = "Password must contain 8 or more characters.";
					} else {
						$randomString = getRandomString(50);
						$_SESSION["passChangeToken"] = isset($_SESSION["passChangeToken"]) ? $_SESSION["passChangeToken"] : $randomString;
						$selectNeededDetailsQuery = "SELECT firstName, username, email, passChangeTime
													FROM accountdetails
													WHERE accountID = '{$_SESSION["userID"]}'";
						if ($queriedNeededDetails = $mysqliConnection -> query($selectNeededDetailsQuery)) {
							if ($queriedNeededDetails -> num_rows > 0) {
								if ($assocNeededDetails = $queriedNeededDetails -> fetch_assoc()) {
									$dbUsername = $assocNeededDetails["username"];
									$dbFirstName = $assocNeededDetails["firstName"];
									$dbEmail = $assocNeededDetails["email"];
									$dbLastSentTime = $assocNeededDetails["passChangeTime"];
									if ((time() - 120) < strtotime($dbLastSentTime)) {
										$assocReturn["leftoverCooldown"] = strtotime($dbLastSentTime) + 120 - time();
										$assocReturn["message"] = "Please wait until the cooldown is over!";
									} else {
										$hashedNewPassword = password_hash(base64_encode(hash("sha512", $changeContent, true)), PASSWORD_DEFAULT);
										$updateAccountDetailsQuery = "UPDATE accountdetails
										SET passChangeTime = NOW(),
										passChangeToken = '{$_SESSION["passChangeToken"]}',
										newPassword = '{$hashedNewPassword}'
										WHERE accountID = '{$_SESSION["userID"]}'";
										$emailDOM = "
										<!DOCTYPE html>
										<html>
											<head>
												<title>Password Change · Streetor</title>
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
														background-color: #06BA00;
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
																		<td id='infoText'>An account, under the name of {$dbUsername}, has requested to change its password. To verify that this is you, click the link shown below. This link is valid for 10 minutes.</td>
																	</tr>
																	<tr>
																		<td align='center' style='padding-bottom: 20px;'>
																			<a id='verificationLink' href='https://www.streetor.sg/changePassword/?email={$dbEmail}&token={$_SESSION["passChangeToken"]}'>
																				Change Password
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
										if ($mysqliConnection -> query($updateAccountDetailsQuery)) {
											if (mail($dbEmail, "Password Change", $emailDOM, implode(PHP_EOL, $emailHeaders))) {
												$assocReturn["message"] = "An email has been sent to your email address for verification.";
												$assocReturn["leftoverCooldown"] = 120;
											} else {
												$assocReturn["message"] = "An error occurred and an email could not be sent to your email address.";
											}
										} else {
											$assocReturn["message"] = "An error occurred.";
										}
									}
								} else {
									$assocReturn["message"] = "An error occurred.";
								}
							} else {
								$assocReturn["message"] = "No records were found in the database. This account may have been deleted.";
							}
							$queriedNeededDetails -> free();
						} else {
							$assocReturn["message"] = "An error occurred.";
						}
					}
				break;
				case "3": //change email
					$changeContent = $mysqliConnection -> real_escape_string($_POST["content"]);
					$assocReturn["leftoverCooldown"] = 0;
					if (empty(trim($_POST["content2"]))) {
						$assocReturn["message"] = "This field is required.";
					}
					if (empty($changeContent)) {
						$assocReturn["newEmailError"] = "This field is required.";
					}
					else if (empty(filter_var($changeContent, FILTER_VALIDATE_EMAIL))) {
						$assocReturn["newEmailError"] = "Please enter a valid email.";
					}
					if (!empty(trim($_POST["content"])) && !empty(trim($_POST["content2"]))) {
						$randomString = getRandomString(50);
						$_SESSION["emailChangeToken"] = isset($_SESSION["emailChangeToken"]) ? $_SESSION["emailChangeToken"] : $randomString;
						$selectNeededDetailsQuery = "SELECT firstName, username, password, email, emailChangeTime
													FROM accountdetails
													WHERE accountID = '{$_SESSION["userID"]}'";
						if ($queriedNeededDetails = $mysqliConnection -> query($selectNeededDetailsQuery)) {
							if ($queriedNeededDetails -> num_rows > 0) {
								if ($assocNeededDetails = $queriedNeededDetails -> fetch_assoc()) {
									$dbUsername = $assocNeededDetails["username"];
									$dbPassword = $assocNeededDetails["password"];
									$dbFirstName = $assocNeededDetails["firstName"];
									$dbEmail = $assocNeededDetails["email"];
									$dbLastSentTime = $assocNeededDetails["emailChangeTime"];
									if (password_verify(base64_encode(hash("sha512", $_POST["content2"], true)), $dbPassword) === true) {
										if ((time() - 120) < strtotime($dbLastSentTime)) {
											$assocReturn["leftoverCooldown"] = strtotime($dbLastSentTime) + 120 - time();
											$assocReturn["message"] = "Please wait until the cooldown is over!";
										} else {
											$updateAccountDetailsQuery = "UPDATE accountdetails
																		SET emailChangeTime = NOW(),
																		emailChangeToken = '{$_SESSION["emailChangeToken"]}',
																		newEmail = '{$changeContent}'
																		WHERE accountID = '{$_SESSION["userID"]}'";
											$verificationEmailDOM = "
											<!DOCTYPE html>
											<html>
												<head>
													<title>Email Change · Streetor</title>
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
															background-color: #06BA00;
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
																			<td id='infoText'>An account, under the name of {$dbUsername}, has requested to use this email address to receive updates/notifications. To verify that this is you, click the link shown below. This link is valid for 10 minutes.</td>
																		</tr>
																		<tr>
																			<td align='center' style='padding-bottom: 20px;'>
																				<a id='verificationLink' href='https://www.streetor.sg/changeEmail/?email={$dbEmail}&token={$_SESSION["emailChangeToken"]}'>
																					Change Email
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
											if ($mysqliConnection -> query($updateAccountDetailsQuery)) {
												if (mail($changeContent, "Email Change", $verificationEmailDOM, implode(PHP_EOL, $emailHeaders))) {
													$assocReturn["leftoverCooldown"] = 120;
													$assocReturn["message"] = "An email has been sent to your new email address for verification.";
												} else {
													$assocReturn["message"] = "An error occurred and emails could not be sent to your email addresses.";
												}
											} else {
												$assocReturn["message"] = "An error occurred.";
											}
										}
									} else {
										$assocReturn["message"] = "Incorrect password.";
									}
								} else {
									$assocReturn["message"] = "An error occurred.";
								}
							} else {
								$assocReturn["message"] = "No records were found in the database. This account may have been deleted.";
							}
							$queriedNeededDetails -> free();
						} else {
							$assocReturn["message"] = "An error occurred.";
						}
					}
				break;
				case "5": //change 2FA
					$changeContent = $mysqliConnection -> real_escape_string($_POST["content"]);
					$assocReturn["canSwitch"] = false;
					if (empty(trim($_POST["content2"]))) {
						$assocReturn["message"] = "This field is required.";
					} else {
						$selectNeededDetailsQuery = "SELECT password, 2FAenabled
						FROM accountdetails
						WHERE accountID = '{$_SESSION["userID"]}'";
						if ($queriedNeededDetails = $mysqliConnection -> query($selectNeededDetailsQuery)) {
							if ($assocNeededDetails = $queriedNeededDetails -> fetch_assoc()) {
								$dbPassword = $assocNeededDetails["password"];
								$db2FAenabled = $assocNeededDetails["2FAenabled"];
								$update2FAquery = "UPDATE accountdetails
								SET 2FAenabled = !{$db2FAenabled}
								WHERE accountID = '{$_SESSION["userID"]}'";
								if (password_verify(base64_encode(hash("sha512", $_POST["content2"], true)), $dbPassword) === true) {
									if ($mysqliConnection -> query($update2FAquery)) {
										$toggledDB2FAenabled = !$db2FAenabled;
										$twoFactorAuthToggleState = $toggledDB2FAenabled === 1 ? "enabled" : "disabled";
										$assocReturn["message"] = "Your 2 factor authentication has been <b>{$twoFactorAuthToggleState}</b>.";
										$assocReturn["switch"] = (bool)$toggledDB2FAenabled;
									} else {
										$assocReturn["message"] = "An error occurred.";
									}
								} else {
									$assocReturn["message"] = "Incorrect password.";
								}
							} else {
								$assocReturn["message"] = "An error occurred.";
							}
							$queriedNeededDetails -> free();
						} else {
							$assocReturn["message"] = "An error occurred.";
						}
					}
				break;
				default:
					$assocReturn["message"] = "Invalid request.";
				break;
			}
		}
	} else {
		$assocReturn["message"] = "This feature is reserved for signed in users only. Please <a href='https://www.streetor.sg/login/' style='color: #4486f4;'>log in</a> and try again.";
	}
}
$mysqliConnection -> close();
echo json_encode($assocReturn);
?>