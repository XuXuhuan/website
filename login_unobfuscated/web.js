"use strict";
const refUsernameField = document.querySelector("#usernameField");
const refUsernameError = document.querySelector("#usernameError");
const refPasswordField = document.querySelector("#passwordField");
const refPasswordToggleButton = document.querySelector("#passwordShowButton");
const refPasswordToggleImageCont = document.querySelector("#passwordShowImageCont");
const refPasswordError = document.querySelector("#passwordError");
const refLoginButtonCont = document.querySelector("#logInButtonCont");
const refLoginButton = document.querySelector("#logInButton");
const refLoginMessage = document.querySelector("#logInMessage");
const userDirtRegexp = /[^a-z0-9._]/gi;
var checkLogin;
refUsernameField.addEventListener("keyup", function() {
	if (refUsernameField.value.trim().length === 0) {
		refUsernameError.innerHTML = "This field is required.";
	}
	else if (userDirtRegexp.test(refUsernameField.value) === true) {
		refUsernameError.innerHTML = "Please enter a valid username.";
	} else {
		refUsernameError.innerHTML = "";
	}
});
refPasswordField.addEventListener("keyup", function() {
	if (refPasswordField.value.trim().length === 0) {
		refPasswordError.innerHTML = "This field is required.";
	} else {
		refPasswordError.innerHTML = "";
	}
});
refPasswordToggleButton.addEventListener("click", function() {
	if (refPasswordField.type === "password") {
		refPasswordField.type = "text";
		if (refPasswordToggleImageCont.classList.contains("showingPassImage") === false) {
			refPasswordToggleImageCont.classList.add("showingPassImage");
		}
	} else {
		if (refPasswordToggleImageCont.classList.contains("showingPassImage") === true) {
			refPasswordToggleImageCont.classList.remove("showingPassImage");
		}
		refPasswordField.type = "password";
	}
});
function submitLogin(event) {
	if (event.button === 0) {
		clearTimeout(checkLogin);
		checkLogin = setTimeout(function() {
			if (refUsernameField.value.length > 0 && refPasswordField.value.length > 0) {
				const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
				xhr.open("POST", "login.php", true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.responseType = "json";
				refLoginButtonCont.innerHTML = '<div id="loadingImageCont"></div>';
				xhr.onload = function() {
					if (xhr.status === 200) {
						refUsernameError.innerHTML = xhr.response["errormessages"]["usernameError"];
						refPasswordError.innerHTML = xhr.response["errormessages"]["passwordError"];
						refLoginMessage.innerHTML = xhr.response["errormessages"]["loginError"];
						if (xhr.response["successURL"].length > 0) {
							window.location = xhr.response["successURL"];
						} else {
							refLoginButtonCont.innerHTML = '<button id="logInButton" onmouseup="submitLogin(event)" onmousedown="cancelSubmitLoginTimeout(event)">Login</button>';
						}
					} else {
						refLoginMessage.innerHTML = "An error occurred.";
					}
				}
				xhr.send("username=" + encodeURIComponent(refUsernameField.value) + "&password=" + encodeURIComponent(refPasswordField.value));
				refLoginButton.style.display = "none";
			}
		}, 500);
	}
}
function cancelSubmitLoginTimeout(event) {
	if (event.button === 0) {
		clearTimeout(checkLogin);
	}
}