<?php
session_start();
date_default_timezone_set("MST");
error_reporting(0);
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength / 2));
}
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true && isset($_SESSION["userID"]) && !empty($_SESSION["userID"])) {
	$hashedNewToken = hash("sha512", getRandomString(50));
    $updateTokenHashQuery = "
    UPDATE accountdetails
    SET tokenHash = '{$updateTokenHashQuery}'
	WHERE accountID = {$_SESSION["userID"]}";
	$mysqliConnection -> query($updateTokenHashQuery);
}
setcookie("logincookie", "", time() - 3600, "/", "www.streetor.sg", false, false);
$_SESSION["loggedIn"] = false;
unset($_SESSION["userID"]);
unset($_SESSION["username"]);
unset($_SESSION["emailVerifToken"]);
unset($_SESSION["userChangeToken"]);
unset($_SESSION["passChangeToken"]);
unset($_SESSION["emailChangeToken"]);
unset($_SESSION["accountDeletionToken"]);
unset($_SESSION["email"]);
$mysqliConnection -> close();
echo "https://www.streetor.sg/login/";
?>