<?php
session_start();
date_default_timezone_set("MST");
error_reporting(0);
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true && isset($_SESSION["userID"]) && !empty($_SESSION["userID"])) {
	$rememberMeID = $mysqliConnection -> real_escape_string(json_decode($_COOKIE["logincookie"], true)["remembermeid"]);
    $updateTokenHashQuery = "DELETE FROM remembereddevices
	WHERE rememberID = '{$rememberMeID}'";
	$mysqliConnection -> query($updateTokenHashQuery);
}
setcookie("logincookie", "", time() - 3600, "/", "streetor.sg", false, false);
$_SESSION["loggedIn"] = false;
unset($_SESSION["userID"]);
unset($_SESSION["emailVerifToken"]);
unset($_SESSION["userChangeToken"]);
unset($_SESSION["passChangeToken"]);
unset($_SESSION["emailChangeToken"]);
unset($_SESSION["accountDeletionToken"]);
$mysqliConnection -> close();
echo "https://streetor.sg/login/";
?>