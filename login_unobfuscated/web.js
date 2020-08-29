"use strict";
const usernameField = document.querySelector("#usernameField");
const usernameError = document.querySelector("#usernameError");
const passField = document.querySelector("#passwordField");
const passError = document.querySelector("#passwordError");
const loginButton = document.querySelector("#loginButton");
const loginError = document.querySelector("#loginError");
const userDirtRegexp = /[^a-z0-9._]/gi;
var checkLogin;
usernameField.addEventListener("keyup", function() {
	if (usernameField.value.trim().length === 0) {
		usernameError.innerHTML = "This field is required.";
	}
	else if (userDirtRegexp.test(usernameField.value) === true) {
		usernameError.innerHTML = "Please enter a valid username.";
	} else {
		usernameError.innerHTML = "";
	}
});
passField.addEventListener("keyup", function() {
	if (passField.value.trim().length === 0) {
		passError.innerHTML = "This field is required.";
	} else {
		passError.innerHTML = "";
	}
});
loginButton.addEventListener("mouseup", function() {
	clearTimeout(checkLogin);
	checkLogin = setTimeout(function() {
		if (usernameField.value.length !== 0 && passField.value.length !== 0) {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "login.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.responseType = "json";
			xhr.onload = function() {
				usernameError.innerHTML = xhr.response["errormessages"]["usernameError"];
				passError.innerHTML = xhr.response["errormessages"]["passwordError"];
				loginError.innerHTML = xhr.response["errormessages"]["loginError"];
				if (xhr.response["successURL"].length > 0) {
					window.location = xhr.response["successURL"];
					document.cookie = "darktheme=true; expires=31 Dec 10000 12:00:00 UTC; path=/; samesite=strict;";
				}
			}
			xhr.send("username=" + encodeURIComponent(usernameField.value) + "&password=" + encodeURIComponent(passField.value));
			loginButton.style.display = "none";
		}
	}, 500);
});
loginButton.addEventListener("mousedown", function() {
	clearTimeout(checkLogin);
})