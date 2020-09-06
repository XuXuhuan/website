<?php
error_reporting(0);
date_default_timezone_set("MST");
session_start();
$assocReturn = array("concatText" => "",
                    "concatSlider" => "");
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if ($mysqliConnection -> connect_error) {
    $assocReturn["username"] = $assocReturn["email"] = $assocReturn["emailVerifiedRowInfoText"] = "[A connection error occurred. Please refresh the page or try again later.]";
    $assocReturn["biographyHTML"] = '<p class="notSelectable" style="font-family: BalooDaReg, Verdana, sans-serif; font-size: 20px; color: #ffffff;">[A connection error occurred. Please refresh the page or try again later.]</p>';
} else {
    if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] !== "off") {
        if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
            $neededDetailsQuery = "
            SELECT 2FAenabled
            FROM accountdetails
            WHERE accountID = " . $_SESSION["userID"];
            if ($allNeededDetails = $mysqliConnection -> query($neededDetailsQuery)) {
                if ($allNeededDetails -> num_rows > 0) {
                    if ($assocNeededDetails = $allNeededDetails -> fetch_assoc()) {
                        if ($assocNeededDetails["2FAenabled"] == true) {
                            $assocReturn["concatSlider"] = '
                            <span id="twoFactorAuthSwitchCont" class="sliderSwitchCont switchedOnSwitchCont" onclick="twoFactorAuthSwitch()">
                                <span id="twoFactorAuthSwitch" class="switchedOnSwitch"></span>
                            </span>';
                        } else {
                            $assocReturn["concatSlider"] = '
                            <span id="twoFactorAuthSwitchCont" class="sliderSwitchCont" onclick="twoFactorAuthSwitch()">
                                <span id="twoFactorAuthSwitch"></span>
                            </span>';
                        }
                    } else {
                        $assocReturn["concatText"] = "[Error. Please refresh the page or try again later.]";
                    }
                } else {
                    $assocReturn["concatText"] = "[Error. Please refresh the page or try again later.]";
                }
                $allNeededDetails -> free();
            } else {
                $assocReturn["concatText"] = "[Error. Please <a href='https://www.streetor.sg/login/' style='color: #4486f4;'>log in</a> and try again.]";
            }
        } else {
            $assocReturn["concatText"] = "[This feature is reserved for signed in users only. Please <a href='https://www.streetor.sg/login/' style='color: #4486f4;'>log in</a> and try again.]";
        }
    } else {
        $assocReturn["concatText"] = "[Your connection is insecure and this request could not be processed. Please refresh the page or try again later.]";
    }
}
$mysqliConnection -> close();
echo json_encode($assocReturn);
?>