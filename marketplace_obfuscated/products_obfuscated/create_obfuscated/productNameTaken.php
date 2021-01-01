<?php
error_reporting(0);
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
$fetchID = $mysqliConnection -> real_escape_string($_POST["id"]);
$fetchName = $mysqliConnection -> real_escape_string($_POST["name"]);
$fetchProductNameQuery = "SELECT productName
FROM marketproducts
WHERE LOWER(productName) = LOWER('{$fetchName}')
AND marketID = '{$fetchID}'";
if ($mysqliConnection -> connect_errno) {
	echo "A connection error occurred. Please try again later.";
}
else if ($queriedProductName = $mysqliConnection -> query($fetchProductNameQuery)) {
	if ($queriedProductName -> num_rows > 0) {
		echo "Product name already exists in your market.";
	}
	$queriedProductName -> free();
} else {
	echo "An error occurred.";
}
$mysqliConnection -> close();
?>