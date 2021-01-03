<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if ($mysqliConnection -> connect_errno) {
	echo "A connection error occurred. Please try again later.";
}
else if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
	if (!empty($_POST["id"]) && !preg_match("/[^0-9]/i", $_POST["id"])) {
		$escapedProductID = $mysqliConnection -> real_escape_string($_POST["id"]);
		$checkIfUserIsOwnerQuery = "SELECT marketproducts.productID
		FROM marketproducts
		JOIN marketdetails
		ON marketproducts.marketID = marketdetails.marketID
		WHERE marketdetails.marketOwner = '{$_SESSION["userID"]}'
		AND marketproducts.productID = '{$escapedProductID}'";
		if ($queriedProduct = $mysqliConnection -> query($checkIfUserIsOwnerQuery)) {
			if ($queriedProduct -> num_rows > 0) {
				if ($assocProductDetails = $queriedProduct -> fetch_assoc()) {
					$deleteError = false;
					if (is_dir("../../uploads/productPictures/{$assocProductDetails["productID"]}")) {
						$allFiles = glob("../../uploads/productPictures/{$assocProductDetails["productID"]}/*");
						foreach($allFiles as $imageFile) {
							if (!unlink($imageFile)) {
								$deleteError = true;
							}
						}
						if (!rmdir("../../uploads/productPictures/{$assocProductDetails["productID"]}")) {
							$deleteError = true;
						}
					}
					if ($deleteError === false) {
						$deleteProductRowQuery = "DELETE FROM marketproducts
						WHERE productID = '{$assocProductDetails["productID"]}'";
						if ($mysqliConnection -> query($deleteProductRowQuery)) {
							echo "Product Deleted.";
						} else {
							echo "An error occurred.";
						}
					} else {
						echo "An error occurred.";
					}
				} else {
					echo "An error occurred.";
				}
			} else {
				echo "This product was not found in your market.";
			}
		} else {
			echo "An error occurred.";
		}
	} else {
		echo "Invalid request.";
	}
} else {
	echo "Please log in and try again.";
}
?>