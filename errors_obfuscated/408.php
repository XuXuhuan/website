<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$stylesheetLink;
$infoText;
if (isset($_COOKIE["darktheme"]) && $_COOKIE["darktheme"] === "false") {
	$stylesheetLink = "https://streetor.sg/errors/errorsLightTheme.css";
} else {
	$stylesheetLink = "https://streetor.sg/errors/errorsDarkTheme.css";
}
echo "
<!DOCTYPE html>
<html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta name='keywords' content='lifestyle, life, tips, share, social media'>
        <meta name='description' content='Share about your lifestyle or lifestyle tips!'>
        <link rel='stylesheet' href='{$stylesheetLink}'>
		<script src='https://streetor.sg/errors/web.js' defer></script>
        <title>Error 408 · Streetor</title>
    </head>
    <body>
		<header>
			<nav id='topnav'>
				<button id='menuToggle'>
					<div id='menuImageCont'></div>
				</button>
				<p id='orgName' class='notSelectable'>STREETOR</p>
			</nav>
			<nav id='sidenav'>
				<a href='https://streetor.sg' id='homeLink'>
					<div class='innerLinksCont'>
						<div id='homeImage' class='sideNavImage'></div>
						<p id='homeText' class='notSelectable'>Home</p>
					</div>
				</a>
				<a href='https://streetor.sg/profiles/' id='profilesLink'>
					<div class='innerLinksCont'>
						<div id='profilesImage' class='sideNavImage'></div>
						<p id='profilesText' class='notSelectable'>Profiles</p>
					</div>
				</a>
				<a href='https://streetor.sg/settings/' id='settingsLink'>
					<div class='innerLinksCont'>
						<div id='settingsImage' class='sideNavImage'></div>
						<p id='settingsText' class='notSelectable'>Settings</p>
					</div>
				</a>
				<a href='https://streetor.sg/marketplace/' id='marketplaceLink'>
					<div class='innerLinksCont'>
						<div id='marketplaceImage' class='sideNavImage'></div>
						<p id='marketplaceText' class='notSelectable'>Marketplace</p>
					</div>
				</a>
				<a href='https://streetor.sg/privacy/' id='privacyLink'>
					<div class='innerLinksCont'>
						<div id='privacyImage' class='sideNavImage'></div>
						<p id='privacyText' class='notSelectable'>Privacy</p>
					</div>
				</a>
			</nav>
		</header>
		<main>
			<div id='mainCont'>
				<div id='errorCont'>
					<div id='errorImage'></div>
					<h1 id='topHeader'>Sorry!</h1>
					<p id='infoText'>This request timed out.</p>
				</div>
			</div>
		</main>
		<footer>
		</footer>
    </body>
</html>";
?>