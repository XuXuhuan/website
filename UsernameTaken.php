<?php
error_reporting(0);
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
$fetchUser = $mysqliConnection -> real_escape_string($_POST["username"]);
$fetchUsernameQuery = "SELECT username
FROM accountdetails
WHERE LOWER(username) = LOWER('$fetchUser')";
if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") {
	if ($mysqliConnection -> connect_errno) {
		echo "A connection error occurred. Please try again later.";
		exit();
	}
	else if ($queriedUsername = $mysqliConnection -> query($fetchUsernameQuery)) {
		if ($queriedUsername -> num_rows > 0) {
			echo "Username already used by another account.";
		}
		$queriedUsername -> free();
	} else {
		echo "An internal error occurred. Please refresh the page or try again later.";
	}
} else {
	echo "Your connection is not secure and this request could not be processed. Please refresh the page or try again later.";
}
$mysqliConnection -> close();
?>