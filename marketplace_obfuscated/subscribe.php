<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$assocReturn = array("notificationColor" => "#E60505",
					"notificationText" => "An internal error occurred.",
					"buttonClass" => "",
					"buttonText" => "",
					"subscriberCount" => 0);
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if (!$mysqliConnection -> connect_errno) {
	if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
		if (!empty($_POST["id"]) && !preg_match("/[0-9]/i", $_POST["id"])) {
			$marketID = $mysqliConnection -> real_escape_string($_POST["id"]);
			$selectSubscribedQuery = "SELECT subscribingUser, COUNT(subscribedMarket = '{$marketID}') AS subscriptionCount
			FROM subscriptions
			WHERE subscribingUser = {$_SESSION["userID"]}
			AND subscribedMarket = '{$marketID}'";
			if ($queriedSubscriptions = $mysqliConnection -> query($selectSubscribedQuery)) {
				if ($assocQueriedSubscriptions = $queriedSubscriptions -> fetch_assoc()) {
					if (!empty($assocQueriedSubscriptions["subscribingUser"])) {
						$unsubscribeFromMarketQuery = "DELETE FROM subscriptions
						WHERE subscribingUser = {$_SESSION["userID"]}
						AND subscribedMarket = '{$marketID}'";
						if ($mysqliConnection -> query($unsubscribeFromMarketQuery)) {
							$assocReturn["notificationText"] = "Unsubscribed!";
							$assocReturn["notificationColor"] = "#40AF00";
							$assocReturn["buttonText"] = "Subscribe";
							$assocReturn["subscriberCount"] = $assocQueriedSubscriptions["subscriptionCount"] - 1;
						}
					} else {
						$subscribeToMarketQuery = "INSERT INTO subscriptions (subscribingUser, subscribedMarket)
						VALUES ({$_SESSION["userID"]}, '{$marketID}')";
						if ($mysqliConnection -> query($subscribeToMarketQuery)) {
							$assocReturn["notificationText"] = "Subscribed!";
							$assocReturn["notificationColor"] = "#40AF00";
							$assocReturn["buttonClass"] = "unsubscribeButton";
							$assocReturn["buttonText"] = "Unsubscribe";
							$assocReturn["subscriberCount"] = $assocQueriedSubscriptions["subscriberCount"] + 1;
						} else {
							$assocReturn["notificationText"] = "An internal error occurred.";
						}
					}
				}
				$queriedSubscriptions -> free();
			}
		} else {
			$assocReturn["notificationText"] = "Invalid market ID.";
		}
	} else {
		$assocReturn["notificationText"] = "Please log in and try again.";
	}
}
echo json_encode($assocReturn);
$mysqliConnection -> close();
?>