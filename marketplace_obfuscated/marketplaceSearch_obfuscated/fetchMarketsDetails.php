<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$searchQuery = $_POST["query"];
$pageCount = $_POST["page"];
$assocReturn = array(
	"marketDetails" => array(),
	"errormessage" => "",
	"currentResults" => 0,
	"maxResults" => 0
);
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if ($mysqliConnection -> connect_errno) {
	$assocReturn["errormessage"] = "A connection error occurred. Please try again later.";
}
else if (!empty($searchQuery) && !empty($pageCount) && !preg_match("/[^0-9]/", $pageCount) && $pageCount > 0) {
	$escapedSearchQuery = $mysqliConnection -> real_escape_string($searchQuery);
	$escapedPageCount = $mysqliConnection -> real_escape_string($pageCount * 10);
	$escapedMinResults = $mysqliConnection -> real_escape_string($pageCount * 10 - 10);
	$selectMarketsDetailsQuery = "SELECT marketID, marketName, biography
	FROM marketdetails
	WHERE marketName LIKE '%{$escapedSearchQuery}%'
	LIMIT {$escapedMinResults}, {$escapedPageCount};";
	$selectMarketsDetailsQuery .= "SELECT COUNT(marketName LIKE '%{$escapedSearchQuery}%') AS maxResults
	FROM marketdetails
	WHERE marketName LIKE '%{$escapedSearchQuery}%'";
	if ($mysqliConnection -> multi_query($selectMarketsDetailsQuery)) {
		do {
			if ($queriedMarketsDetails = $mysqliConnection -> store_result()) {
				if ($queriedMarketsDetails -> num_rows > 0) {
					while ($assocMarketsDetails = $queriedMarketsDetails -> fetch_assoc()) {
						if (isset($assocMarketsDetails["maxResults"])) {
							$assocReturn["maxResults"] = $assocMarketsDetails["maxResults"];
							$assocReturn["currentResults"] = $escapedPageCount > $assocMarketsDetails["maxResults"] ? $assocMarketsDetails["maxResults"] : $escapedPageCount;
						} else {
							$findMarketLogo = glob("../../uploads/marketLogos/{$assocMarketsDetails["marketID"]}.png");
							$marketImageURL = "../../Assets/global/imageNotFound.png";
							if (!empty($findMarketLogo)) {
								$marketImageURL = $findMarketLogo[0];
							}
							$assocReturn["marketDetails"][] = array(
								"marketLogoURL" => $marketImageURL,
								"marketID" => $assocMarketsDetails["marketID"],
								"marketName" => $assocMarketsDetails["marketName"],
								"biography" => $assocMarketsDetails["biography"],
							);
						}
					}
				} else {
					$assocReturn["errormessage"] = "No results found.";
				}
				$queriedMarketsDetails -> free();
			}
		} while ($mysqliConnection -> next_result());
	} else {
		$assocReturn["errormessage"] = "An internal error occurred. Please try again later.";
	}
} else {
	$assocReturn["errormessage"] = "Invalid request. Please try again later.";
}
echo json_encode($assocReturn);
$mysqliConnection -> close();
?>