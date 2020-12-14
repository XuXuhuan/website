<?php
error_reporting(0);
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
$fetchMarketName = $mysqliConnection -> real_escape_string($_POST["marketname"]);
$fetchMarketNameQuery = "SELECT marketName
FROM marketdetails
WHERE LOWER(marketName) = LOWER('{$fetchMarketName}')";
if ($mysqliConnection -> connect_errno) {
	echo "A connection error occurred. Please try again later.";
}
else if ($queriedMarketName = $mysqliConnection -> query($fetchMarketNameQuery)) {
	if ($queriedMarketName -> num_rows > 0) {
		echo "This market already exists.";
	}
	$queriedMarketName -> free();
} else {
	echo "An error occurred.";
}
$mysqliConnection -> close();
?>