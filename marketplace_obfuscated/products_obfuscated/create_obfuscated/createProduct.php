<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
$acceptedMIMEtypes = array("image/png", "image/jpg", "image/jpeg");
$assocReturn = array("imagesError" => "",
					"nameError" => "",
					"priceError" => "",
					"message" => "");
if ($mysqliConnection -> connect_errno) {
	$assocReturn["message"] = "A connection error occurred. Please try again later.";
} else {
	if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
		if (empty($_POST["name"])) {
			$assocReturn["marketNameError"] = "This field is required.";
		}
		else if (strlen(trim($_POST["name"])) > 30) {
			$assocReturn["marketNameError"] = "Product name must be under 30 characters long.";
		}
		if (preg_match("[^0-9]", $_POST["id"])) {
			$assocReturn["message"] = "Invalid request.";
		}
		if (preg_match("[^0-9]", $_POST["priceDollars"]) || preg_match("[^0-9]", $_POST["priceCents"])) {
			$assocReturn["productPriceError"] = "Invalid product price.";
		}
		if (empty($assocReturn["imagesError"]) && empty($assocReturn["nameError"]) && empty($assocReturn["priceError"]) && empty($assocReturn["message"])) {
			$escapedID = $mysqliConnection -> real_escape_string($_POST["id"]);
			$checkIfOwnerQuery = "SELECT marketOwner
			FROM marketdetails
			WHERE marketOwner = '{$_SESSION["userID"]}'
			AND marketID = '{$escapedID}'";
			if ($queriedOwnerQuery = $mysqliConnection -> query($checkIfOwnerQuery)) {
				if ($queriedOwnerQuery -> num_rows > 0) {
					$escapedName = $mysqliConnection -> real_escape_string($_POST["name"]);
					$checkIfNameIsUsedQuery = "SELECT productName
					FROM marketproducts
					WHERE marketID = '{$escapedID}'
					AND productName = '{$escapedName}'";
					if ($queriedName = $mysqliConnection -> query($checkIfNameIsUsedQuery)) {
						if ($queriedName -> num_rows === 0) {
							$escapedInfo = $mysqliConnection -> real_escape_string($_POST["info"]);
							$escapedPrice = $mysqliConnection -> real_escape_string((int) $_POST["priceDollars"] + (int) $_POST["priceCents"] / 100);
							$insertProductQueries = "INSERT INTO marketproducts(marketID, productName, productInfo, pricing)
							VALUES ('{$escapedID}', '{$escapedName}', '{$escapedInfo}', '{$escapedPrice}');";
							$insertProductQueries .= "SELECT LAST_INSERT_ID() AS insertID;";
							if ($mysqliConnection -> multi_query($insertProductQueries)) {
								$mysqliConnection -> next_result();
								if ($queriedInsertID = $mysqliConnection -> store_result()) {
									if ($queriedInsertID -> num_rows > 0) {
										if ($assocInsertID = $queriedInsertID -> fetch_assoc()) {
											$errorCode = (int)$image["tmp_name"]["error"];
											if ($errorCode !== UPLOAD_ERR_OK) {
												switch($errorCode) {
													case UPLOAD_ERR_INI_SIZE:
														$assocReturn["message"] = "Image is larger than file size limit (4MB)";
													break;
													case UPLOAD_ERR_FORM_SIZE:
														$assocReturn["message"] = "Image exceeds maximum file size directive in form.";
													break;
													case UPLOAD_ERR_PARTIAL:
														$assocReturn["message"] = "Image was only partially uploaded";
													break;
													case UPLOAD_ERR_NO_FILE:
														$assocReturn["message"] = "No file was uploaded.";
													break;
													case UPLOAD_ERR_NO_TMP_DIR:
														$assocReturn["message"] = "Temporary folder is missing.";
													break;
													case UPLOAD_ERR_CANT_WRITE:
														$assocReturn["message"] = "Failed to write file to disk.";
													break;
													case UPLOAD_ERR_EXTENSION:
														$assocReturn["message"] = "An extension stopped the file upload.";
													break;
													default:
														$assocReturn["message"] = "Upload error."; 
													break; 
												}
											} else {
												mkdir("../../../uploads/productPictures/{$assocInsertID["insertID"]}");
												$imageCount = 1;
												foreach($_FILES as $image) {
													$fileInfoResource = new finfo(FILEINFO_MIME_TYPE);
													$fileMIME = $fileInfoResource -> file($image["tmp_name"]);
													if (
														in_array(
															strtolower(
																$fileMIME
															), $acceptedMIMEtypes
														)
													) {
														if (getimagesize($image["tmp_name"])[0] >= 150 && getimagesize($image["tmp_name"])[1] >= 150) {
															$processedImage;
															if (strtolower($fileMIME) === "image/png") {
																$processedImage = imagecreatefrompng($image["tmp_name"]);
															}
															else if (strtolower($fileMIME) === "image/jpg" || strtolower($fileMIME) === "image/jpeg") {
																$processedImage = imagecreatefromjpeg($image["tmp_name"]);
															}
															imagesavealpha($processedImage, true);
															imagepng($processedImage, "../../../uploads/productPictures/{$assocInsertID["insertID"]}/{$imageCount}.png");
															imagedestroy($processedImage);
														} else {
															$assocReturn["message"] = "Image must have dimensions of at least 150px by 150px.";
														}
													} else {
														$assocReturn["message"] = "Only JPEG or PNG files are accepted.";
													}
													$imageCount++;
												}
												if (empty($assocReturn["message"])) {
													$assocReturn["message"] = "Product Created!";
												}
											}
										} else {
											$assocReturn["message"] = "An error occurred.";
										}
									} else {
										$assocReturn["message"] = "An error occurred.";
									}
									$queriedInsertID -> free();
								} else {
									$assocReturn["message"] = "An error occurred.";
								}
							} else {
								$assocReturn["message"] = "An error occurred.";
							}
						} else {
							$assocReturn["message"] = "Product name already exists in your market.";
						}
						$queriedName -> free();
					}
				} else {
					$assocReturn["message"] = "You do not own this market.";
				}
				$queriedOwnerQuery -> free();
			} else {
				$assocReturn["message"] = "An error occurred.";
			}
		}
	} else {
		$assocReturn["message"] = "Please <a href='https://www.streetor.sg/login/' style='color: #4486f4;'>log in</a> and try again.";
	}
}
echo json_encode($assocReturn);
$mysqliConnection -> close();
?>