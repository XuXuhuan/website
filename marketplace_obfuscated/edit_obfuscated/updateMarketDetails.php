<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$assocReturn = array("message" => "");
$emailHeaders[] = "MIME-Version: 1.0";
$emailHeaders[] = "Content-type:text/html; charset=utf-8";
$emailHeaders[] = "From: <noreply@streetor.sg>";
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
function getRandomString($stringLength) {
	return bin2hex(random_bytes($stringLength / 2));
}
if ($mysqliConnection -> connect_errno) {
	$assocReturn["message"] = "A connection error occurred. Please try again later.";
} else {
	if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
		$usedMethod = empty($_POST) ? json_decode(file_get_contents("php://input"), true) : $_POST;
		if (empty($usedMethod["type"]) && empty($usedMethod["id"])) {
			$assocReturn["message"] = "Invalid request.";
		} else {
			$escapedMarketID = $mysqliConnection -> real_escape_string($usedMethod["id"]);
			$checkIfUserIsOwnerQuery = "SELECT marketOwner
			FROM marketdetails
			WHERE marketID = '{$escapedMarketID}'
			AND marketOwner = '{$_SESSION["userID"]}'";
			$selectedCategory = array();
			$toggleCategory = array();
			if ($queriedUserIsOwner = $mysqliConnection -> query($checkIfUserIsOwnerQuery)) {
				if ($queriedUserIsOwner -> num_rows > 0) {
					switch($usedMethod["type"]) {
						case 1:
							$escapedContent = $mysqliConnection -> real_escape_string($usedMethod["value"]);
							$checkMarketNameQuery = "SELECT marketName
							FROM marketdetails
							WHERE marketName = '{$escapedContent}'";
							if ($queriedMarketName = $mysqliConnection -> query($checkMarketNameQuery)) {
								if ($queriedMarketName -> num_rows === 0) {
									$updateMarketNameQuery = "UPDATE marketdetails
									SET marketName = '{$escapedContent}'
									WHERE marketID = '{$usedMethod["id"]}'";
									if ($mysqliConnection -> query($updateMarketNameQuery)) {
										$assocReturn["message"] = "Market name changed!";
									} else {
										$assocReturn["message"] = "An error occurred.";
									}
								} else {
									$assocReturn["message"] = "Market name already used.";
								}
							} else {
								$assocReturn["message"] = "An error occurred.";
							}
						break;
						case 2:
							$changeContent = $usedMethod["value"];
							if (strlen($changeContent) > 500) {
								$assocReturn["message"] = "Market info too long. The maximum number of characters is 500.";
							}
							else if (!empty(trim($changeContent))) {
								$replacedContent = preg_replace("/^[ \t\r\n\v\f]{2,}$/m", "", $changeContent);
								$escapedReplacedContent = $mysqliConnection -> real_escape_string($replacedContent);
								$updateBioQuery = "UPDATE marketdetails
								SET biography = '{$escapedReplacedContent}'
								WHERE marketID = '{$escapedMarketID}'";
								if ($mysqliConnection -> query($updateBioQuery)) {
									$assocReturn["message"] = "Market info updated.";
								} else {
									$assocReturn["message"] = "An error occurred.";
								}
							}
						break;
						case 3:
						break;
						case 4:
						break;
						default:
							$assocReturn["message"] = "Invalid request.";
						break;
					}
				} else {
					$assocReturn["message"] = "Market unavailable.";
				}
			} else {
				$assocReturn["message"] = "An error occurred.";
			}
		}
	} else {
		$assocReturn["message"] = "Please log in and try again.";
	}
}
?>