<?php
error_reporting(0);
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
$fetchEmail = $mysqliConnection -> real_escape_string($_POST["email"]);
$fetchEmailQuery = "SELECT email
FROM accountdetails
WHERE LOWER(email) = LOWER('$fetchEmail')";
if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") {
	if ($mysqliConnection -> connect_errno) {
		echo "A connection error occurred. Please try again later.";
		exit();
	}
	else if ($queriedEmail = $mysqliConnection -> query($fetchEmailQuery)) {
		if ($queriedEmail -> num_rows > 0) {
			echo "Email already used by another account.";
		}
		$queriedEmail -> free();
	} else {
		echo "An internal error occurred. Please refresh the page or try again later.";
	}
} else {
	echo "Your connection is not secure and this request could not be processed. Please refresh the page or try again later.";
}
$mysqliConnection -> close();
?>