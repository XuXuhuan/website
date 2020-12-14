<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$assocReturn = array(
	"productDetails" => array(),
	"errormessage" => "",
	"currentResults" => 0,
	"maxResults" => 0
);
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if ($mysqliConnection -> connect_errno) {
	$assocReturn["errormessage"] = "A connection error occurred. Please try again later.";
}
else if (strlen($_POST["hasQuery"]) === 1 &&
!empty($_POST["marketid"]) &&
!empty($_POST["page"]) &&
!preg_match("/[^0-9]/", $_POST["hasQuery"]) &&
!preg_match("/[^0-9]/", $_POST["marketid"]) &&
!preg_match("/[^0-9]/", $_POST["page"]) &&
$_POST["marketid"] > 0 &&
$_POST["page"] > 0 &&
($_POST["hasQuery"] == 0 || $_POST["hasQuery"] == 1)) {
	$escapedMinResults = $mysqliConnection -> real_escape_string($_POST["page"] * 10 - 10);
	$escapedPageCount = $mysqliConnection -> real_escape_string($_POST["page"] * 10);
	$escapedMarketID = $mysqliConnection -> real_escape_string($_POST["marketid"]);
	$selectProductsDetailsQuery;
	if ($_POST["hasQuery"] == 1) {
		$escapedSearchQuery = $mysqliConnection -> real_escape_string($_POST["query"]);
		$selectProductsDetailsQuery = "SELECT marketproducts.productID, marketproducts.productName, marketproducts.productInfo, ratings.rating
		FROM marketproducts
		LEFT JOIN ratings
		ON marketproducts.productID = ratings.productID
		WHERE productName LIKE '%{$escapedSearchQuery}%'
		AND marketID = '{$escapedMarketID}'
		ORDER BY ratings.rating DESC
		LIMIT {$escapedMinResults}, {$escapedPageCount};";
		$selectProductsDetailsQuery .= "SELECT COUNT(productName LIKE '%{$escapedSearchQuery}%' AND marketID = '{$escapedMarketID}') AS maxResults
		FROM marketproducts
		WHERE productName LIKE '%{$escapedSearchQuery}%'
		AND marketID = '{$escapedMarketID}'";
	} else {
		$selectProductsDetailsQuery = "SELECT marketproducts.productID, marketproducts.productName, marketproducts.productInfo, ratings.rating
		FROM marketproducts
		LEFT JOIN ratings
		ON marketproducts.productID = ratings.productID
		WHERE marketID = '{$escapedMarketID}'
		ORDER BY ratings.rating DESC
		LIMIT {$escapedMinResults}, {$escapedPageCount};";
		$selectProductsDetailsQuery .= "SELECT COUNT(marketID = '{$escapedMarketID}') AS maxResults
		FROM marketproducts
		WHERE marketID = '{$escapedMarketID}'";
	}
	if ($mysqliConnection -> multi_query($selectProductsDetailsQuery)) {
		do {
			if ($queriedProductsDetails = $mysqliConnection -> store_result()) {
				if ($queriedProductsDetails -> num_rows > 0) {
					while ($assocProductsDetails = $queriedProductsDetails -> fetch_assoc()) {
						if (isset($assocProductsDetails["maxResults"])) {
							$assocReturn["maxResults"] = $assocProductsDetails["maxResults"];
							$assocReturn["currentResults"] = $escapedPageCount > $assocProductsDetails["maxResults"] ? $assocProductsDetails["maxResults"] : $escapedPageCount;
						} else {
							$findProductImage = glob("../../uploads/productPictures/{$assocProductsDetails["productID"]}/1.png");
							$productImageURL = "../../Assets/global/imageNotFound.png";
							$productRating = empty($assocProductsDetails["rating"]) ? 0 : $assocProductsDetails["rating"];
							if (!empty($findProductImage)) {
								$productImageURL = $findProductImage[0];
							}
							$assocReturn["productDetails"][] = array(
								"productImageURL" => $productImageURL,
								"productID" => $assocProductsDetails["productID"],
								"productName" => htmlspecialchars($assocProductsDetails["productName"], ENT_QUOTES),
								"productInfo" => htmlspecialchars($assocProductsDetails["productInfo"], ENT_QUOTES),
								"productRating" => $productRating
							);
						}
					}
				} else {
					$assocReturn["errormessage"] = "No results found.";
				}
				$queriedProductsDetails -> free();
			}
		} while ($mysqliConnection -> next_result());
	} else {
		$assocReturn["errormessage"] = "An error occurred.";
	}
} else {
	$assocReturn["errormessage"] = "Invalid request. Please try again later.";
}
echo json_encode($assocReturn);
$mysqliConnection -> close();
?>