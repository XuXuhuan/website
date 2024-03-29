<?php
error_reporting(0);
date_default_timezone_set("MST");
session_start();
$assocReturn = array("username" => "",
                    "email" => "",
                    "emailVerifiedRowStyle" => "",
                    "emailVerifiedRowInfoStyle" => "",
                    "emailVerifiedRowInfoText" => "",
                    "emailVerificationHTML" => "",
                    "biographyHTML" => "");
$mysqliConnection = new mysqli("localhost", "websiteUser", "jj4JWYh_X6OKm2x^NP", "mainManagement");
if ($mysqliConnection -> connect_error) {
    $assocReturn["username"] = $assocReturn["email"] = $assocReturn["emailVerifiedRowInfoText"] = "[A connection error occurred. Please try again later.]";
    $assocReturn["biographyHTML"] = '<p class="notSelectable" style="font-family: Segoe UI, LatoReg, Verdana, sans-serif; font-size: 20px; color: #ffffff;">[A connection error occurred. Please try again later.]</p>';
} else {
    if (isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true) {
        $neededDetailsQuery = "
        SELECT emailVerified, biography, username, email
        FROM accountdetails
        WHERE accountID = '{$_SESSION["userID"]}'";
        if ($allNeededDetails = $mysqliConnection -> query($neededDetailsQuery)) {
            if ($allNeededDetails -> num_rows > 0) {
                if ($assocNeededDetails = $allNeededDetails -> fetch_assoc()) {
                    $assocReturn["username"] = htmlspecialchars($assocNeededDetails["username"], ENT_QUOTES);
                    $assocReturn["email"] = htmlspecialchars($assocNeededDetails["email"], ENT_QUOTES);
                    $assocReturn["biographyHTML"] = '<textarea id="bioInput" onkeyup="changeBioValue()" placeholder="Share about yourself in 200 characters." maxlength="200" rows="10">' . htmlspecialchars($assocNeededDetails["biography"], ENT_QUOTES) . '</textarea>';
                    if ($assocNeededDetails["emailVerified"] == false) {
                        $assocReturn["emailVerifiedRowStyle"] = "padding-bottom: 0;";
                        $assocReturn["emailVerifiedRowInfoStyle"] = "margin-bottom: 10px;";
                        $assocReturn["emailVerifiedRowInfoText"] = "No";
                        $assocReturn["emailVerificationHTML"] = '
                        <div id="verifyEmailButtonCont" style="height: 40px;">
                            <button class="notSelectable" id="verifyEmailButton" onclick="sendEmailVerification()">Verify Email</button>
                        </div>
                        <p class="inputErrorText" id="verifyEmailError"></p>';
                    } else {
                        $assocReturn["emailVerifiedRowInfoText"] = "Yes";
                    }
                } else {
                    $assocReturn["username"] = $assocReturn["email"] = $assocReturn["emailVerifiedRowInfoText"] = "[Error. Please try again later.]";
                    $assocReturn["biographyHTML"] = '<p class="notSelectable" style="font-family: Segoe UI, LatoReg, Verdana, sans-serif; font-size: 20px; color: #ffffff;">[Error. Please try again later.]</p>';
                }
            } else {
                $assocReturn["username"] = $assocReturn["email"] = $assocReturn["emailVerifiedRowInfoText"] = "[Error. Please <a href='https://streetor.sg/login/' style='color: #4486f4;'>log in</a> and try again.]";
                $assocReturn["biographyHTML"] = '<p class="notSelectable" style="font-family: Segoe UI, LatoReg, Verdana, sans-serif; font-size: 20px; color: #ffffff;">[Error. Please <a href="https://streetor.sg/login/" style="color: #4486f4;">log in</a> and try again.]</p>';
            }
            $allNeededDetails -> free();
        } else {
            $assocReturn["username"] = $assocReturn["email"] = $assocReturn["emailVerifiedRowInfoText"] = "[Error. Please try again later.]";
            $assocReturn["biographyHTML"] = '<p class="notSelectable" style="font-family: Segoe UI, LatoReg, Verdana, sans-serif; font-size: 20px; color: #ffffff;">[Error. Please try again later.]</p>';
        }
    } else {
        $assocReturn["username"] = $assocReturn["email"] = $assocReturn["emailVerifiedRowInfoText"] = "[This feature is reserved for signed in users only. Please <a href='https://streetor.sg/login/' style='color: #4486f4;'>log in</a> and try again.]";
        $assocReturn["biographyHTML"] = '<p class="notSelectable" style="font-family: Segoe UI, LatoReg, Verdana, sans-serif; font-size: 20px; color: #ffffff;">[This feature is reserved for signed in users only. Please <a href="https://streetor.sg/login/" style="color: #4486f4;">log in</a> and try again.]</p>';
    }
}
$mysqliConnection -> close();
echo json_encode($assocReturn);
?>