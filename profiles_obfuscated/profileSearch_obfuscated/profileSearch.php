<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if (isset($_COOKIE["darktheme"]) && $_COOKIE["darktheme"] === "false") {
	$stylesheetLink = "profilesLightTheme.css";
} else {
	$stylesheetLink = "profilesDarkTheme.css";
}
if ($mysqliConnection -> connect_errno) {
	$loginAlert = '
	<div id="alertCont">
		<p id="alertText">A connection error occurred. Please refresh the page or try again later.</p>
	</div>';
}
else if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") {
	
}
?>