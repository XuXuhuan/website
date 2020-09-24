<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$searchQuery = $_POST["query"];
$pageCount = $_POST["page"];
$assocReturn = array(
	"profileDetails" => array(),
	"errormessage" => "",
	"currentResults" => 0,
	"maxResults" => 0
);
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if ($mysqliConnection -> connect_errno) {
	$assocReturn["errormessage"] = "A connection error occurred. Please try again later.";
}
else if (!empty($searchQuery) && !empty($pageCount) && preg_match("/[^0-9]/", $pageCount) == false && $pageCount > 0) {
	$escapedSearchQuery = $mysqliConnection -> real_escape_string($searchQuery);
	$escapedPageCount = $mysqliConnection -> real_escape_string($pageCount * 10);
	$escapedMinResults = $mysqliConnection -> real_escape_string($pageCount * 10 - 10);
	$selectProfilesDetailsQuery = "SELECT accountID, username, biography, COUNT(username REGEXP '^($escapedSearchQuery)') AS maxResults
	FROM accountdetails
	WHERE username REGEXP '^($escapedSearchQuery)'
	LIMIT $escapedMinResults, $escapedPageCount";
	if ($queriedProfilesDetails = $mysqliConnection -> query($selectProfilesDetailsQuery)) {
		if ($queriedProfilesDetails -> num_rows > 0) {
			while ($assocProfilesDetails = $queriedProfilesDetails -> fetch_assoc()) {
				if (!empty($assocProfilesDetails["accountID"])) {
					if ($assocProfilesDetails[""])
					$assocReturn["profileDetails"][] = array("profileID" => $assocProfilesDetails["accountID"],
						"profileUsername" => $assocProfilesDetails["username"],
						"profileBiography" => $assocProfilesDetails["biography"]
					);
					$assocReturn["maxResults"] = $assocProfilesDetails["maxResults"];
					$assocReturn["currentResults"] = $escapedPageCount > $assocProfilesDetails["maxResults"] ? $assocProfilesDetails["maxResults"] : $escapedPageCount;
				} else {
					$assocReturn["errormessage"] = "No results found.";
				}
			}
		} else {
			$assocReturn["errormessage"] = "No results found.";
		}
	} else {
		$assocReturn["errormessage"] = "An internal error occurred. Please try again later.";
	}
} else {
	$assocReturn["errormessage"] = "Invalid request. Please try again later.";
}
echo json_encode($assocReturn);
?>