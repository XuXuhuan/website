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
const refVignette = document.querySelector("#vignette");
const refRememberBox = document.querySelector("#rememberTickBox");
const refCancelTwoFactorAuthButton = document.querySelector("#cancelTwoFactorAuthButton");
const refTwoFactorAuthField = document.querySelector("#verificationCodeField");
const refTwoFactorAuthBlanks = document.querySelectorAll(".verificationCodeBlanks");
const refTwoFactorAuthResendEmail = document.querySelector("#twoFactorAuthResendEmailButton");
const refNotificationCont = document.querySelector("#notificationCont");
const refNotificationText = document.querySelector("#notificationText");
const userDirtRegexp = /[^a-z0-9._]/gi;
var twoFactorAuthEmailSent = false;
var currentBlank = 0;
var checkNotification;
var checkLogin;
function setNotification(message, isError) {
	refNotificationCont.style.top = 0;
	refNotificationCont.style.backgroundColor = isError === true ? "#E60505" : "#40AF00";
	refNotificationText.innerHTML = message;
	clearTimeout(checkNotification);
	checkNotification = setTimeout(function() {
		refNotificationCont.style.top = "-10vh";
	}, 1000);
}
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
refRememberBox.addEventListener("click", function() {
	refRememberBox.classList.toggle("tickedBox");
});
refCancelTwoFactorAuthButton.addEventListener("click", function() {
	refVignette.style = 0;
	currentBlank = 0;
	refTwoFactorAuthBlanks.forEach(function(item) {
		item.innerHTML = "";
	});
});
refTwoFactorAuthField.addEventListener("paste", function(event) {
	const getClipboardData = event.clipboardData;
	if (!isNaN(parseInt(getClipboardData.getData("Text")))) {
		var i, z;
		for (i = currentBlank, z = 0; i < getClipboardData.getData("Text").length && i < 6 - currentBlank; i++, z++) {
			refTwoFactorAuthBlanks[i].innerHTML = getClipboardData.getData("Text").charAt(z);
		}
		currentBlank = i;
		if (currentBlank === 6) {
			submitTwoFactorAuthCode();
		}
	}
});
refTwoFactorAuthResendEmail.addEventListener("click", function() {
	if (twoFactorAuthEmailSent === false) {
		const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
		xhr.open("POST", "resend2FAEmail.php", true);
		xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		xhr.responseType = "json";
		xhr.onload = function() {
			var leftCooldown = xhr.response["leftoverCooldown"];
			var responseMessage = xhr.response["message"];
			if (responseMessage.length > 0) {
				setNotification(responseMessage, responseMessage !== "Email sent!");
			}
			if (leftCooldown <= 1) {
				refTwoFactorAuthResendEmail.innerHTML = "Re-send Email";
			} else {
				twoFactorAuthEmailSent = true;
				refTwoFactorAuthResendEmail.innerHTML = "Re-send Email (" + leftCooldown + ")";
				leftCooldown--;
				for (var i = 1; i <= xhr.response["leftoverCooldown"]; i++) {
					setTimeout(function() {
						if (leftCooldown === 0) {
							refTwoFactorAuthResendEmail.innerHTML = "Re-send Email";
							twoFactorAuthEmailSent = false;
						} else {
							refTwoFactorAuthResendEmail.innerHTML = "Re-send Email (" + leftCooldown + ")";
							leftCooldown--;
						}
					}, 1000 * i);
				}
			}
		}
		xhr.send("username=" + encodeURIComponent(refUsernameField.value) + "&password=" + encodeURIComponent(refPasswordField.value));
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
						if (xhr.response["2FARequired"] === true) {
							if (refVignette.style.opacity !== 0) {
								refVignette.style.display = "block";
								refTwoFactorAuthField.focus();
							}
						}
						if (xhr.response["successURL"].length > 0) {
							window.location = xhr.response["successURL"];
						} else {
							refLoginButtonCont.innerHTML = '<button id="logInButton" onmouseup="submitLogin(event)" onmousedown="cancelSubmitLoginTimeout(event)">Login</button>';
						}
					} else {
						refLoginMessage.innerHTML = "An error occurred.";
					}
				}
				xhr.send("username=" + encodeURIComponent(refUsernameField.value) + "&password=" + encodeURIComponent(refPasswordField.value) + "&remember=" + (refRememberBox.classList.contains("tickedBox") ? 1 : 0));
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
function fillVerificationCodeBlanks(key) {
	if (!isNaN(key.key)) {
		if (currentBlank < 6) {
			refTwoFactorAuthBlanks[currentBlank].innerHTML = key.key;
			currentBlank++;
			if (currentBlank === 6) {
				submitTwoFactorAuthCode();
			}
		}
	}
	if (key.keyCode === 8 && currentBlank > 0) {
		currentBlank--;
		refTwoFactorAuthBlanks[currentBlank].innerHTML = "";
	}
}
function submitTwoFactorAuthCode() {
	var verificationCode = "";
	refTwoFactorAuthBlanks.forEach(function(item) {
		verificationCode += item.innerHTML;
	});
	const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
	xhr.open("POST", "login.php", true);
	xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xhr.responseType = "json";
	refLoginButtonCont.innerHTML = '<div id="loadingImageCont"></div>';
	xhr.onload = function() {
		if (xhr.status === 200) {
			if (xhr.response["errormessages"]["2FAError"].length > 0) {
				setNotification(xhr.response["errormessages"]["2FAError"], true);
			}
			if (xhr.response["successURL"].length > 0) {
				window.location = xhr.response["successURL"];
				setNotification("Login Success!", false);
			} else {
				refLoginButtonCont.innerHTML = '<button id="logInButton" onmouseup="submitLogin(event)" onmousedown="cancelSubmitLoginTimeout(event)">Login</button>';
			}
		} else {
			refLoginMessage.innerHTML = "An error occurred.";
		}
	}
	xhr.send("username=" + encodeURIComponent(refUsernameField.value) + "&password=" + encodeURIComponent(refPasswordField.value) + "&remember=" + (refRememberBox.classList.contains("tickedBox") ? 1 : 0) + "&2FACode=" + encodeURIComponent(verificationCode));
}