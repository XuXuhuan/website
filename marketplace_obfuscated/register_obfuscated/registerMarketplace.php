<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$receivedJSON = json_decode(file_get_contents("php://input"), true);
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
$assocReturn = array("marketNameError" => "",
					"message" => "");
if ($mysqliConnection -> connect_errno) {
	$assocReturn["message"] = "A connection error occurred. Please try again later.";
} else {
	if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
		$selectedCategory = array();
		$toggleCategory = array();
		foreach($receivedJSON["marketCategories"] as $categorySelected) {
			switch($categorySelected) {
				case "automotive":
					$selectedCategory[] = "automotive";
					$toggleCategory[] = 1;
				break;
				case "babyCare":
					$selectedCategory[] = "babyCare";
					$toggleCategory[] = 1;
				break;
				case "books":
					$selectedCategory[] = "books";
					$toggleCategory[] = 1;
				break;
				case "CDandVinyl":
					$selectedCategory[] = "CDandVinyl";
					$toggleCategory[] = 1;
				break;
				case "clothesAndAccessories":
					$selectedCategory[] = "clothesAndAccessories";
					$toggleCategory[] = 1;
				break;
				case "electronics":
					$selectedCategory[] = "electronics";
					$toggleCategory[] = 1;
				break;
				case "gardening":
					$selectedCategory[] = "gardening";
					$toggleCategory[] = 1;
				break;
				case "outdoorsAndSports":
					$selectedCategory[] = "outdoorsAndSports";
					$toggleCategory[] = 1;
				break;
				case "groceries":
					$selectedCategory[] = "groceries";
					$toggleCategory[] = 1;
				break;
				case "health":
					$selectedCategory[] = "health";
					$toggleCategory[] = 1;
				break;
				case "household":
					$selectedCategory[] = "household";
					$toggleCategory[] = 1;
				break;
				case "personalCare":
					$selectedCategory[] = "personalCare";
					$toggleCategory[] = 1;
				break;
				case "kitchenAndDining":
					$selectedCategory[] = "kitchenAndDining";
					$toggleCategory[] = 1;
				break;
				case "travelSupplies":
					$selectedCategory[] = "travelSupplies";
					$toggleCategory[] = 1;
				break;
				case "beauty":
					$selectedCategory[] = "beauty";
					$toggleCategory[] = 1;
				break;
				case "moviesAndTV":
					$selectedCategory[] = "moviesAndTV";
					$toggleCategory[] = 1;
				break;
				case "musicalInstruments":
					$selectedCategory[] = "musicalInstruments";
					$toggleCategory[] = 1;
				break;
				case "officeSupplies":
					$selectedCategory[] = "officeSupplies";
					$toggleCategory[] = 1;
				break;
				case "petSupplies":
					$selectedCategory[] = "petSupplies";
					$toggleCategory[] = 1;
				break;
				case "software":
					$selectedCategory[] = "software";
					$toggleCategory[] = 1;
				break;
				case "tools":
					$selectedCategory[] = "tools";
					$toggleCategory[] = 1;
				break;
				case "toys":
					$selectedCategory[] = "toys";
					$toggleCategory[] = 1;
				break;
				case "games":
					$selectedCategory[] = "games";
					$toggleCategory[] = 1;
				break;
			}
		}
		if (count($selectedCategory) === 0) {
			$assocReturn["message"] = "Please select at least one category.";
		}
		if (empty($receivedJSON["marketName"])) {
			$assocReturn["marketNameError"] = "This field is required.";
		}
		else if (strlen($receivedJSON["marketName"]) < 3) {
			$assocReturn["marketNameError"] = "Market name must contain at least 3 characters.";
		}
		else if (strlen($receivedJSON["marketName"]) > 30) {
			$assocReturn["marketNameError"] = "Market name has to be under 30 characters.";
		}
		else if (preg_match("/[^a-z0-9._\[\]\(\) ]/i", $receivedJSON["marketName"]) == true) {
			$assocReturn["marketNameError"] = "Your market name can only contain letters, numbers, (), [], . and _.";
		}
		if (empty($assocReturn["marketNameError"]) && empty($assocReturn["message"])) {
			$marketName = $mysqliConnection -> real_escape_string($receivedJSON["marketName"]);
			$checkMarketsQuery = "SELECT marketOwner
			FROM marketdetails
			WHERE LOWER(marketName) = LOWER('{$marketName}')";
			if ($queriedMarkets = $mysqliConnection -> query($checkMarketsQuery)) {
				if ($queriedMarkets -> num_rows > 0) {
					$assocReturn["marketNameError"] = "This market already exists.";
				} else {
					$escapedMarketName = $mysqliConnection -> real_escape_string($receivedJSON["marketName"]);
					$specifiedColumns = implode(",\n", $selectedCategory);
					$specifiedColumnsToggle = implode(",\n", $toggleCategory);
					$insertValuesQuery = "INSERT INTO marketdetails (
						marketName,
						marketOwner,
						{$specifiedColumns}
					)
					VALUES (
						'{$escapedMarketName}',
						'{$_SESSION["userID"]}',
						{$specifiedColumnsToggle}
					)";
					if ($mysqliConnection -> query($insertValuesQuery)) {
						$assocReturn["message"] = "Success!";
					} else {
						$assocReturn["message"] = "An internal error occurred. Please try again later.";
					}
				}
				$queriedMarkets -> free();
			} else {
				$assocReturn["message"] = "An internal error occurred. Please try again later.";
			}
		}
	} else {
		$assocReturn["message"] = "Please <a href='https://www.streetor.sg/login/' style='color: #4486f4;'>log in</a> and try again.";
	}
}
echo json_encode($assocReturn);
$mysqliConnection -> close();
?>