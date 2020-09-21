<?php
error_reporting(0);
date_default_timezone_set("MST");
session_start();
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
$getUser = $mysqliConnection -> real_escape_string($_POST["username"]);
$getPass = $mysqliConnection -> real_escape_string($_POST["password"]);
$getfName = $mysqliConnection -> real_escape_string($_POST["fName"]);
$getlName = $mysqliConnection -> real_escape_string($_POST["lName"]);
$getEmail = $mysqliConnection -> real_escape_string($_POST["email"]);
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength / 2));
}
$randomString = isset($_SESSION["emailVerifToken"]) ? $_SESSION["emailVerifToken"] : getRandomString(20);
$_SESSION["emailVerifToken"] = isset($_SESSION["emailVerifToken"]) ? $_SESSION["emailVerifToken"] : $randomString;
$fetchEmailAndUsernameQueries = "SELECT username
FROM accountdetails
WHERE LOWER(username) = LOWER('$getUser');";
$fetchEmailAndUsernameQueries .= "SELECT email
FROM accountdetails
WHERE LOWER(email) = LOWER('$getEmail')";
$AssocReturn = array("errormessages" => array(
	"usernameError" => "",
	"passwordError" => "",
	"fNameError" => "",
	"lNameError" => "",
	"emailError" => "",
	"MySQLiError" => ""),
"successmessage" => "");
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
				font-family: "Montserrat", Verdana, sans-serif;
				font-size: 30px;
			}
			#bodyContainer > tr > td {
				background-color: #ffffff;
			}
			#helloText {
				font-family: "Roboto", Helvetica, sans-serif;
			}
			#infoText, #deleteText {
				text-indent: 2em;
				font-family: "Baloo Da 2", Arial, sans-serif;
				padding-bottom: 20px;
			}
			#verificationLink, #deleteLink {
				color: #ffffff;
				display: table-cell;
				height: 50px;
				width: 250px;
				text-align: center;
				vertical-align: middle;
				font-family: "Roboto", Helvetica, sans-serif;
				font-size: 24px;
				background-color: #06BA00;
				text-decoration: none;
			}
			#websiteLabel {
				padding-top: 20px;
				text-align: center;
				font-family: "Montserrat", Verdana, sans-serif;
			}
			#footerContainer > tr > td {
				color: #ffffff;
				background-color: #0e0f2c;
			}
			#contactCell {
				text-align: center;
				font-family: "Roboto", Helvetica, sans-serif;
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
									<h1 id="helloText">Hello ' . $getfName . ',</h1>
								</td>
							</tr>
							<tr>
								<td id="infoText">An account, under the name of ' . $getUser . ', has been registered under your email. To verify that this is you, click the link shown below. This link is valid for 10 minutes.</td>
							</tr>
							<tr>
								<td align="center" style="padding-bottom: 20px;">
									<a id="verificationLink" href="https://www.streetor.sg/emailVerification/?email=' . $getEmail . '&token=' . $randomString . '">
										Verify your email
									</a>
								</td>
							</tr>
							<tr>
								<td>
									<p id="deleteText">If this was not you or you decided not to keep this account, click the link shown below. This link is also valid for 10 minutes.</p>
								</td>
							</tr>
							<tr>
								<td align="center" style="padding-bottom: 20px;">
									<a href="https://www.streetor.sg/accountDeletion/?email=' . $getEmail . '&token=' . $randomString . '" id="deleteLink">Delete your account</a>
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
if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") {
	if ($mysqliConnection -> connect_errno) {
		$AssocReturn["errormessages"]["MySQLiError"] = "A connection error occurred. Please refresh the page or try again later.";
	} else {
		if (preg_match("/[^a-z0-9._]/i", $getUser) == true) {
			$AssocReturn["errormessages"]["usernameError"] = "Username may only contain letters, numbers, . and _.";
		}
		else if (empty(trim($getUser))) {
			$AssocReturn["errormessages"]["usernameError"] = "This field is required.";
		}
		else if (strlen($getUser) < 3 || strlen($getUser) > 20) {
			$AssocReturn["errormessages"]["usernameError"] = "Username may only contain 3-20 characters.";
		}
		if (empty(preg_replace("/(strong(er)*)*(complex)*(password[0-9]{0,3})/i", "", $getPass))) {
			$AssocReturn["errormessages"]["passwordError"] = "Please create a stronger password.";
		}
		else if (empty(trim($getPass))) {
			$AssocReturn["errormessages"]["passwordError"] = "This field is required.";
		}
		else if (strlen($getPass) < 8) {
			$AssocReturn["errormessages"]["passwordError"] = "Password must contain 8 or more characters.";
		}
		if (empty(trim($getfName))) {
			$AssocReturn["errormessages"]["fNameError"] = "This field is required.";
		}
		else if (preg_match("/[0-9]/", $getfName) === 1) {
			$AssocReturn["errormessages"]["fNameError"] = "This field cannot contain numbers.";
		}
		if (empty(trim($getlName))) {
			$AssocReturn["errormessages"]["lNameError"] = "This field is required.";
		}
		else if (preg_match("/[0-9]/", $getlName) === 1) {
			$AssocReturn["errormessages"]["lNameError"] = "This field cannot contain numbers.";
		}
		if (empty(filter_var($getEmail, FILTER_VALIDATE_EMAIL))) {
			$AssocReturn["errormessages"]["emailError"] = "Please enter a valid email.";
		}
		else if (empty(trim($getEmail))) {
			$AssocReturn["errormessages"]["emailError"] = "This field is required.";
		}
		if (empty($AssocReturn["errormessages"]["usernameError"]) &&
			empty($AssocReturn["errormessages"]["passwordError"]) &&
			empty($AssocReturn["errormessages"]["fNameError"]) &&
			empty($AssocReturn["errormessages"]["lNameError"]) &&
			empty($AssocReturn["errormessages"]["emailError"])) {
			if ($mysqliConnection -> multi_query($fetchEmailAndUsernameQueries)) {
				do {
					if ($usernameAndEmailResults = $mysqliConnection -> store_result()) {
						if ($usernameAndEmailResults -> num_rows > 0) {
							if ($assocUsernameAndEmailResults = $usernameAndEmailResults -> fetch_assoc()) {
								if (isset($assocUsernameAndEmailResults["username"])) {
									$AssocReturn["errormessages"]["usernameError"] = "Username already used by another account.";
								}
								else if (isset($assocUsernameAndEmailResults["email"])) {
									$AssocReturn["errormessages"]["emailError"] = "Email already used by another account.";
								}
							} else {
								$AssocReturn["errormessages"]["MySQLiError"] = "An internals error occurred. Please refresh the page and try again later.";
							}
						}
						$usernameAndEmailResults -> free();
					} else {
						$AssocReturn["errormessages"]["MySQLiError"] = "An internal error occurred. Please refresh the page and try again later.";
					}
				} while ($mysqliConnection -> next_result());
				if (empty($AssocReturn["errormessages"]["usernameError"]) &&
					empty($AssocReturn["errormessages"]["passwordError"]) &&
					empty($AssocReturn["errormessages"]["fNameError"]) &&
					empty($AssocReturn["errormessages"]["lNameError"]) &&
					empty($AssocReturn["errormessages"]["emailError"])) {
					$emailHeaders[] = "MIME-Version: 1.0";
					$emailHeaders[] = "Content-type:text/html; charset=utf-8";
					$emailHeaders[] = "From: <noreply@streetor.sg>";
					$insertDataQuery = "
					INSERT INTO accountdetails
					(username, password, rememberID, firstName, lastName, email, emailVerificationToken, emailVerificationTime)
					VALUES
					('$getUser',
					'" . password_hash(base64_encode(hash("sha512", $getPass, true)), PASSWORD_DEFAULT) . "',
					'" . getRandomString(30) . "',
					'$getfName',
					'$getlName',
					'$getEmail',
					'$randomString',
					'" . date("Y-m-j H:i:s", time()) . "')
					ON DUPLICATE KEY UPDATE
					rememberID = '" . getRandomString(30) . "'";
					if ($insertedData = $mysqliConnection -> query($insertDataQuery)) {
						if (mail($getEmail, "Email Verification", $emailDOM, implode(PHP_EOL, $emailHeaders))) {
							$AssocReturn["successmessage"] = "An email has been sent to your email address for verification.";
						} else {
							$AssocReturn["errormessages"]["MySQLiError"] = "An internal error occurred and a verification email was not sent to the input email address. You can go to the <a href='https://www.streetor.sg/login/'>log in</a> page or settings page to re-send the email.";
						}
					} else {
						$AssocReturn["errormessages"]["MySQLiError"] = "An internal error occurred. Please refresh the page and try again later.";
					}
				}
			} else {
				$AssocReturn["errormessages"]["MySQLiError"] = "An internal error occurred. Please refresh the page and try again later.";
			}
		}
	}
} else {
	$AssocReturn["errormessages"]["MySQLiError"] = "Your connection is not secure and this request could not be processed. Please refresh the page or try again later.";
}
$mysqliConnection -> close();
echo json_encode($AssocReturn);
?>