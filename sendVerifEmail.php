<?php
error_reporting(0);
date_default_timezone_set("MST");
session_start();
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
$assocReturn = array("message" => "",
					 "leftoverCooldown" => "");
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength / 2));
}
$randomString = getRandomString(20);
$_SESSION["emailVerifToken"] = isset($_SESSION["emailVerifToken"]) ? $_SESSION["emailVerifToken"] : $randomString;
if ($mysqliConnection -> connect_errno) {
	$assocReturn["message"] = "An error occurred.";
} else {
	if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
		$selectNeededDetailsQuery = "
		SELECT username, tokenHash, firstName, email, emailVerified, emailVerificationTime
		FROM accountdetails
		WHERE accountID = '{$_SESSION["userID"]}'";
		if ($neededDetails = $mysqliConnection -> query($selectNeededDetailsQuery)) {
			if ($neededDetails -> num_rows > 0) {
				if ($assocNeededDetails = $neededDetails -> fetch_assoc()) {
				$dbUsername = $assocNeededDetails["username"];
				$dbTokenHash = $assocNeededDetails["tokenHash"];
				$dbFirstName = $assocNeededDetails["firstName"];
				$dbEmail = $assocNeededDetails["email"];
				$dbEmailVerif = $assocNeededDetails["emailVerified"];
				$dbLastSentTime = $assocNeededDetails["emailVerificationTime"];
					if ($dbEmailVerif == true) {
						$assocReturn["message"] = "This account has already been verified! Please reload this page.";
					}
					if ((time() - 120) < strtotime($dbLastSentTime)) {
						$assocReturn["leftoverCooldown"] = strtotime($dbLastSentTime) + 120 - time();
						$assocReturn["message"] = "Please wait until the cooldown is over!";
					} else {
						$emailDOM = '
						<!DOCTYPE html>
						<html>
							<head>
								<title>Email Verification · Streetor</title>
								<link href="https://fonts.googleapis.com/css2?family=Baloo+Da+2&family=Montserrat&family=Roboto&display=swap" rel="stylesheet">
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
										font-family: Segoe UI, "Montserrat", Verdana, sans-serif;
										font-size: 30px;
									}
									#bodyContainer > tr > td {
										background-color: #ffffff;
									}
									#helloText {
										font-family: Segoe UI, "Roboto", Helvetica, sans-serif;
									}
									#infoText {
										text-indent: 2em;
										font-family: Segoe UI, "Baloo Da 2", Arial, sans-serif;
										padding-bottom: 20px;
									}
									#verificationLink {
										color: #ffffff;
										display: table-cell;
										height: 50px;
										width: 250px;
										text-align: center;
										vertical-align: middle;
										font-family: Segoe UI, "Roboto", Helvetica, sans-serif;
										font-size: 24px;
										background-color: #06BA00;
										text-decoration: none;
									}
									#websiteLabel {
										text-align: center;
										font-family: Segoe UI, "Montserrat", Verdana, sans-serif;
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
										font-family: Segoe UI, "Roboto", Helvetica, sans-serif;
										padding-top: 10px;
										padding-bottom: 10px;
									}
								</style>
							</head>
							<body>
								<table id="outerContainer" width="100%" border="0" cellspacing="0" cellpadding="0" align="center" bgcolor="#38444a">
									<tr>
										<td id="outerMain">
											<table id="mainContainer">
												<thead>
													<tr>
														<td id="headerRow">STREETOR</td>
													</tr>
												</thead>
												<tbody id="bodyContainer">
													<tr>
														<td>
															<h1 id="helloText">Hello ' . $dbFirstName . ',</h1>
														</td>
													</tr>
													<tr>
														<td id="infoText">An account, under the name of ' . $dbUsername . ', has been registered under your email. To verify that this is you, click the link shown below. This link is valid for 10 minutes.</td>
													</tr>
													<tr>
														<td align="center" style="padding-bottom: 20px;">
															<a id="verificationLink" href="https://www.streetor.sg/emailVerification/?email=' . $dbEmail . '&token=' . $_SESSION["emailVerifToken"] . '">
																Verify your email
															</a>
														</td>
													</tr>
												</tbody>
												<tfoot id="footerContainer">
													<tr>
														<td id="websiteLabel">streetor.sg</td>
													</tr>
													<tr>
														<td id="contactCell">
															<a href="mailto:support@streetor.sg">Contact Support</a>
														</td>
													</tr>
												</tfoot>
											</table>
										</td>
									</tr>
								</table>
							</body>
						</html>';
						$emailHeaders[] = "MIME-Version: 1.0";
						$emailHeaders[] = "Content-type:text/html; charset=utf-8";
						$emailHeaders[] = "From: <noreply@streetor.sg>";
						if (mail($dbEmail, "Email Verification", $emailDOM, implode(PHP_EOL, $emailHeaders))) {
							$updateVerificationEmailTimeQuery = "
							UPDATE accountdetails
							SET emailVerificationTime = NOW(),
							emailVerificationToken = '{$_SESSION["emailVerifToken"]}'
							WHERE accountID = '{$_SESSION["userID"]}'";
							if ($queriedUpdateTimeQuery = $mysqliConnection -> query($updateVerificationEmailTimeQuery)) {
								$assocReturn["message"] = "Email sent!";
								$assocReturn["leftoverCooldown"] = 120;
							} else {
								$assocReturn["message"] = "An error occurred, but the email was sent.";
							}
						} else {
							$assocReturn["message"] = "An error occurred and the email was not sent.";
						}
					}
				} else {
					$assocReturn["message"] = "An error occurred.";
				}
			} else {
				$assocReturn["message"] = "No records were found in the database. This account may have been deleted.";
			}
			$neededDetails -> free();
		} else {
			$assocReturn["message"] = "An error occurred.";
		}
	} else {
		$assocReturn["message"] = "Please <a href='https://www.streetor.sg/login/' style='color: #4486f4;'>log in and try again.</a>";
	}
}
$mysqliConnection -> close();
echo json_encode($assocReturn);
?>