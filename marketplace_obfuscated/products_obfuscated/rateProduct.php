<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$assocReturn = array("notificationColor" => "#E60505",
					"notificationText" => "An internal error occurred.",
					"ratingCount" => 0,
					"averageRating" => 0,
					"ratingAlreadyExists" => null);
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if (!$mysqliConnection -> connect_errno) {
	if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
		if (!empty($_POST["productid"]) && !empty($_POST["rating"]) && !preg_match("/[^0-9]/i", $_POST["productid"]) && $_POST["rating"] <= 5) {
			$escapedProductID = $mysqliConnection -> real_escape_string($_POST["productid"]);
			$escapedRating = $mysqliConnection -> real_escape_string($_POST["rating"]);
			$checkIfRatingExistsQuery = "SELECT accountID, AVG(rating) AS averageRating, COUNT(productID = '{$escapedProductID}') AS ratingCount
			FROM ratings
			WHERE accountID = {$_SESSION["userID"]}
			AND productID = '{$escapedProductID}'";
			if ($queriedRatings = $mysqliConnection -> query($checkIfRatingExistsQuery)) {
				if ($assocQueriedRatings = $queriedRatings -> fetch_assoc()) {
					if (empty($assocQueriedRatings["accountID"])) {
						$insertRatingValuesQuery = "INSERT INTO ratings(accountID, productID, rating)
						VALUES ({$_SESSION["userID"]}, '{$escapedProductID}', '{$escapedRating}')";
						if ($mysqliConnection -> query($insertRatingValuesQuery)) {
							$assocReturn["notificationColor"] = "#40AF00";
							$assocReturn["notificationText"] = "Rating submitted!";
							$assocReturn["averageRating"] = empty($assocQueriedRatings["averageRating"]) ? 0 : $assocQueriedRatings["averageRating"];
							$assocReturn["ratingCount"] = (int)$assocQueriedRatings["ratingCount"];
							$assocReturn["ratingAlreadyExists"] = false;
						}
					} else {
						$changeRatingValuesQuery = "UPDATE ratings
						SET rating = '{$escapedRating}'
						WHERE accountID = {$_SESSION["userID"]}
						AND productID = '{$escapedProductID}'";
						if ($mysqliConnection -> query($changeRatingValuesQuery)) {
							$assocReturn["notificationColor"] = "#40AF00";
							$assocReturn["notificationText"] = "Rating submitted!";
							$assocReturn["averageRating"] = empty($assocQueriedRatings["averageRating"]) ? 0 : $assocQueriedRatings["averageRating"];
							$assocReturn["ratingCount"] = (int)$assocQueriedRatings["ratingCount"];
							$assocReturn["ratingAlreadyExists"] = true;
						}
					}
				}
				$queriedRatings -> free();
			}
		} else {
			$assocReturn["notificationText"] = "Invalid product ID or rating.";
		}
	}
}
echo json_encode($assocReturn);
?>