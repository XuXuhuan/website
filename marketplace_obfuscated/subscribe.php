<?php
session_start();
error_reporting(0);
date_default_timezone_set("MST");
$assocReturn = array("notificationColor" => "#E60505",
					"notificationText" => "An error occurred.",
					"buttonClass" => "",
					"buttonText" => "",
					"subscriberCount" => 0);
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if (!$mysqliConnection -> connect_errno) {
	if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
		if (!empty($_POST["id"]) && !preg_match("/[^0-9]/i", $_POST["id"])) {
			$marketID = $mysqliConnection -> real_escape_string($_POST["id"]);
			$selectSubscribedQuery = "SELECT subscribingUser,
				COUNT(subscribedMarket = '{$marketID}') AS subscriptionCount,
				(SELECT COUNT(subscribingUser = '{$_SESSION["userID"]}') FROM subscriptions WHERE subscribingUser = '{$_SESSION["userID"]}') AS userSubscribedCount,
				(SELECT COUNT(marketID = '{$marketID}' AND marketOwner = '{$_SESSION["userID"]}') FROM marketdetails WHERE marketID = '{$marketID}' AND marketOwner = '{$_SESSION["userID"]}') AS isUserOwner
			FROM subscriptions
			WHERE subscribedMarket = '{$marketID}'";
			if ($queriedSubscriptions = $mysqliConnection -> query($selectSubscribedQuery)) {
				if ($assocQueriedSubscriptions = $queriedSubscriptions -> fetch_assoc()) {
					if ($assocQueriedSubscriptions["isUserOwner"] == 0) {
						if (!empty($assocQueriedSubscriptions["subscribingUser"])) {
							$unsubscribeFromMarketQuery = "DELETE FROM subscriptions
							WHERE subscribingUser = '{$_SESSION["userID"]}'
							AND subscribedMarket = '{$marketID}'";
							if ($mysqliConnection -> query($unsubscribeFromMarketQuery)) {
								$assocReturn["notificationText"] = "Unsubscribed!";
								$assocReturn["notificationColor"] = "#40AF00";
								$assocReturn["buttonText"] = "Subscribe";
								$assocReturn["subscriberCount"] = (int)$assocQueriedSubscriptions["subscriptionCount"] - 1;
							}
						} else {
							if ((int) $assocQueriedSubscriptions["userSubscribedCount"] < 10) {
								$subscribeToMarketQuery = "INSERT INTO subscriptions (subscribingUser, subscribedMarket, subscriptionTime)
								VALUES ('{$_SESSION["userID"]}', '{$marketID}', NOW())";
								if ($mysqliConnection -> query($subscribeToMarketQuery)) {
									$assocReturn["notificationText"] = "Subscribed!";
									$assocReturn["notificationColor"] = "#40AF00";
									$assocReturn["buttonClass"] = "unsubscribeButton";
									$assocReturn["buttonText"] = "Unsubscribe";
									$assocReturn["subscriberCount"] = (int)$assocQueriedSubscriptions["subscriberCount"] + 1;
								} else {
									$assocReturn["notificationText"] = "An error occurred.";
								}
							} else {
								$assocReturn["notificationText"] = "Subscriptions are limited to 10 markets per user.";
							}
						}
					} else {
						$assocReturn["notificationText"] = "Cannot subscribe to your own market.";
						$assocReturn["buttonClass"] = "cannotSubscribe";
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