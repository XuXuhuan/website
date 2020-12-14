<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
$acceptedMIMEtypes = array("image/png", "image/jpg", "image/jpeg");
$assocReturn = array("errormessage" => "",
					"newMarketLogoURL" => "");
if ($mysqliConnection -> connect_errno) {
	$assocReturn["errormessage"] = "A connection error occurred. Please try again later.";
}
else if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
	if ($_POST["type"] >= 1 && $_POST["type"] <= 2 && !empty($_POST["id"]) && !preg_match("/[^0-9]/i", $_POST["id"])) {
		$escapedMarketID = $mysqliConnection -> real_escape_string($_POST["id"]);
		$checkIfMarketExistsQuery = "SELECT marketID
		FROM marketdetails
		WHERE marketID = '{$escapedMarketID}'";
		if ($queriedMarket = $mysqliConnection -> query($checkIfMarketExistsQuery)) {
			if ($queriedMarket -> num_rows > 0) {
				switch ($_POST["type"]) {
					case 1:
						if (file_exists("../../uploads/marketLogos/{$_POST["id"]}.png")) {
							unlink("../../uploads/marketLogos/{$_POST["id"]}.png");
						}
					break;
					case 2:
						$errorCode = (int)$_FILES["image"]["error"];
						if ($errorCode !== UPLOAD_ERR_OK) {
							switch( $code ) {
								case UPLOAD_ERR_INI_SIZE:
									$assocReturn["errormessage"] = "Image is larger than file size limit (8MB)";
								break;
								case UPLOAD_ERR_FORM_SIZE:
									$assocReturn["errormessage"] = "Image exceeds maximum file size directive in form.";
								break;
								case UPLOAD_ERR_PARTIAL:
									$assocReturn["errormessage"] = "Image was only partially uploaded";
								break;
								case UPLOAD_ERR_NO_FILE:
									$assocReturn["errormessage"] = "No file was uploaded.";
								break;
								case UPLOAD_ERR_NO_TMP_DIR:
									$assocReturn["errormessage"] = "Temporary folder is missing.";
								break;
								case UPLOAD_ERR_CANT_WRITE:
									$assocReturn["errormessage"] = "Failed to write file to disk.";
								break;
								case UPLOAD_ERR_EXTENSION:
									$assocReturn["errormessage"] = "An extension stopped the file upload.";
								break;
								default:
									$assocReturn["errormessage"] = "Upload error."; 
								break; 
							}
						} else {
							if (!file_exists("../../uploads/marketLogos/{$_POST["id"]}.png")) {
								if (in_array(
										strtolower(
											mime_content_type($_FILES["image"])
										), $acceptedMIMEtypes)
									) {
									if (getimagesize($_FILES["image"])[0] >= 150 && getimagesize($_FILES["image"])[1] >= 150) {
										$processedImage;
										if (mime_content_type($_FILES["image"]) === "image/png") {
											$processedImage = imagecreatefrompng($_FILES["image"]);
										}
										else if (mime_content_type($_FILES["image"]) === "image/jpg" || mime_content_type($_FILES["image"]) === "image/jpeg") {
											$processedImage = imagecreatefromjpeg($_FILES["image"]);
										}
										imagepng($processedImage, "../../uploads/marketLogos/{$_POST["id"]}.png");
										$assocReturn["newMarketLogoURL"] = "../../uploads/marketLogos/{$_POST["id"]}.png";
									} else {
										$assocReturn["errormessage"] = "Image must have dimensions of at least 150px by 150px.";
									}
								} else {
									$assocReturn["errormessage"] = "Only JPEG or PNG files are accepted.";
								}
							} else {
								$assocReturn["errormessage"] = "A logo already exists for this market.";
							}
						}
					break;
				}
			} else {
				$assocReturn["errormessage"] = "Invalid Market ID.";
			}
			$queriedMarket -> free();
		} else {
			$assocReturn["errormessage"] = "An error occurred.";
		}
	} else {
		$assocReturn["errormessage"] = "Invalid Request.";
	}
} else {
	$assocReturn["errormessage"] = "Please log in and try again.";
}
echo json_encode($assocReturn);
$mysqliConnection -> close();
?>