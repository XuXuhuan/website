<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$assocReturn = array(
	"message" => "",
	"marketLogoURL" => "",
	"marketName" => "",
	"marketInfo" => "",
	"marketCategories" => []
);
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if ($mysqliConnection -> connect_errno) {
	$assocReturn["message"] = "A connection error occurred. Please try again later.";
} else {
	if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
		if (!empty($_POST["id"]) && !preg_match("/[^0-9]/", $_POST["id"])) {
			$marketCategoriesBoxes;
			$escapedMarketID = $mysqliConnection -> real_escape_string($_POST["id"]);
			$selectMarketDetailsQuery = "SELECT *
			FROM marketdetails
			WHERE marketID = '{$escapedMarketID}'
			AND marketOwner = '{$_SESSION["userID"]}'";
			$availableCategories = array(
				"automotive",
				"babyCare",
				"books",
				"CDandVinyl",
				"clothesAndAccessories",
				"electronics",
				"gardening",
				"outdoorsAndSports",
				"groceries",
				"health",
				"household",
				"personalCare",
				"kitchenAndDining",
				"travelSupplies",
				"beauty",
				"moviesAndTV",
				"musicalInstruments",
				"officeSupplies",
				"petSupplies",
				"software",
				"tools",
				"toys",
				"games"
			);
			if ($queriedMarketDetails = $mysqliConnection -> query($selectMarketDetailsQuery)) {
				if ($queriedMarketDetails -> num_rows > 0) {
					if ($assocMarketDetails = $queriedMarketDetails -> fetch_assoc()) {
						foreach($availableCategories as $marketColumns) {
							if ($assocMarketDetails[$marketColumns] == 1) {
								$assocReturn["marketCategories"][] = $marketColumns;
							}
						}
						$assocReturn["marketName"] = htmlspecialchars($assocMarketDetails["marketName"], ENT_QUOTES);
						$findMarketLogo = glob("../../uploads/marketLogos/{$assocMarketDetails["marketID"]}.png");
						$imageFileName = "../../Assets/global/imageNotFound.png";
						$assocReturn["marketInfo"] = htmlspecialchars($assocMarketDetails['biography'], ENT_QUOTES);
						if (!empty($findMarketLogo)) {
							$imageFileName = $findMarketLogo[0];
						}
						$assocReturn["marketLogoURL"] = $imageFileName;
					} else {
						$assocReturn["message"] = "An error occurred.";
					}
				} else {
					$assocReturn["message"] = "An error occurred.";
				}
				$queriedMarketDetails -> free();
			} else {
				$assocReturn["message"] = "No markets owned by you with this ID were found.";
			}
			$queriedMarketDetails -> free();
		} else {
			$assocReturn["message"] = "Invalid Request.";
		}
	} else {
		$assocReturn["message"] = "Please log in and try again.";
	}
}
echo json_encode($assocReturn);
$mysqliConnection -> close();
?>