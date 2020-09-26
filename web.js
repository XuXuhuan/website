"use strict";
const refUsernameField = document.querySelector("#usernameField");
const refUsernameError = document.querySelector("#usernameError");
const refNewPasswordField = document.querySelector("#newPasswordField");
const refNewPasswordToggleButton = document.querySelector("#newPasswordToggleButton");
const refNewPasswordToggleImageCont = document.querySelector("#newPasswordToggleImageCont");
const refNewPasswordError = document.querySelector("#newPasswordError");
const refConfirmPasswordField = document.querySelector("#confirmPasswordField");
const refConfirmPasswordToggleButton = document.querySelector("#confirmPasswordToggleButton");
const refConfirmPasswordToggleImageCont = document.querySelector("#confirmPasswordToggleImageCont");
const refConfirmPasswordError = document.querySelector("#confirmPasswordError");
const refFirstNameField = document.querySelector("#firstNameField");
const refFirstNameError = document.querySelector("#firstNameError");
const refLastNameField = document.querySelector("#lastNameField");
const refLastNameError = document.querySelector("#lastNameError");
const refEmailField = document.querySelector("#emailField");
const refEmailError = document.querySelector("#emailError");
const refSignUpButtonCont = document.querySelector("#signUpButtonCont");
const refSignUpButton = document.querySelector("#signUpButton");
const refSignUpMessage = document.querySelector("#signUpMessage");
const emailTest = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
const passEasyTest = /(strong(er)*)*(complex)*(password[0-9]{0,3})/gi;
const userDirtRegexp = /[^a-z0-9._]/gi;
var checkUser;
var checkEmail;
var checkSignup;
function validatePasswordFields() {
	if (refNewPasswordField.value.trim().length === 0) {
		refNewPasswordError.innerHTML = "This field is required.";
	}
	else if (refNewPasswordField.value.length < 8) {
		refNewPasswordError.innerHTML = "Password must contain 8 or more characters.";
	}
	else if (refNewPasswordField.value.replace(passEasyTest, "") === "") {
		refNewPasswordError.innerHTML = "Please create a stronger password.";
	}
	else if (refConfirmPasswordField.value !== refNewPasswordField.value) {
		refNewPasswordError.innerHTML = "";
		refConfirmPasswordError.innerHTML = "Passwords do not match.";
	} else {
		refNewPasswordError.innerHTML = "";
	}
	if (refConfirmPasswordField.value.trim().length === 0) {
		refConfirmPasswordError.innerHTML = "This field is required.";
	}
	else if (refConfirmPasswordField.value !== refNewPasswordField.value) {
		refConfirmPasswordError.innerHTML = "Passwords do not match.";
	} else {
		refConfirmPasswordError.innerHTML = "";
	}
}
refUsernameField.addEventListener("keyup", function() {
	clearTimeout(checkUser);
	clearTimeout(checkEmail);
	clearTimeout(checkSignup);
	if (userDirtRegexp.test(refUsernameField.value) === true) {
		refUsernameError.innerHTML = "Username may only contain letters, numbers, . and _.";
	}
	else if (refUsernameField.value.trim().length === 0) {
		refUsernameError.innerHTML = "This field is required.";
	}
	else if (refUsernameField.value.length < 3 || refUsernameField.value.length > 20) {
		refUsernameError.innerHTML = "Username may only contain between 3-20 characters.";
	} else {
		refUsernameError.innerHTML = "";
		checkUser = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "UsernameTaken.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.responseType = "text";
			xhr.onload = function() {
				refUsernameError.innerHTML = xhr.responseText;
			}
			xhr.send("username=" + encodeURIComponent(refUsernameField.value));
		}, 350);
	}
});
refUsernameField.addEventListener("keydown", function() {
	clearTimeout(checkUser);
	clearTimeout(checkEmail);
	clearTimeout(checkSignup);
});
refNewPasswordField.addEventListener("keyup", validatePasswordFields);
refConfirmPasswordField.addEventListener("keyup", validatePasswordFields);
refNewPasswordToggleButton.addEventListener("click", function() {
	if (refNewPasswordField.type === "password") {
		refNewPasswordField.type = "text";
		if (refNewPasswordToggleImageCont.classList.contains("showingPassImage") === false) {
			refNewPasswordToggleImageCont.classList.add("showingPassImage");
		}
	} else {
		if (refNewPasswordToggleImageCont.classList.contains("showingPassImage") === true) {
			refNewPasswordToggleImageCont.classList.remove("showingPassImage");
		}
		refNewPasswordField.type = "password";
	}
});
refConfirmPasswordToggleButton.addEventListener("click", function() {
	if (refConfirmPasswordField.type === "password") {
		refConfirmPasswordField.type = "text";
		if (refConfirmPasswordToggleImageCont.classList.contains("showingPassImage") === false) {
			refConfirmPasswordToggleImageCont.classList.add("showingPassImage");
		}
	} else {
		if (refConfirmPasswordToggleImageCont.classList.contains("showingPassImage") === true) {
			refConfirmPasswordToggleImageCont.classList.remove("showingPassImage");
		}
		refConfirmPasswordField.type = "password";
	}
});
refFirstNameField.addEventListener("keyup", function() {
	if (refFirstNameField.value.trim().length === 0) {
		refFirstNameError.innerHTML = "This field is required.";
	}
	else if (/[0-9]/g.test(refFirstNameField.value) === true) {
		refFirstNameError.innerHTML = "This field cannot contain numbers.";
	} else {
		refFirstNameError.innerHTML = "";
	}
});
refLastNameField.addEventListener("keyup", function() {
	if (refLastNameField.value.trim().length === 0) {
		refLastNameError.innerHTML = "This field is required.";
	}
	else if (/[0-9]/g.test(refLastNameField.value) === true) {
		refLastNameError.innerHTML = "This field cannot contain numbers.";
	} else {
		refLastNameError.innerHTML = "";
	}
});
refEmailField.addEventListener("keyup", function() {
	clearTimeout(checkUser);
	clearTimeout(checkEmail);
	clearTimeout(checkSignup);
	if (refEmailField.value.trim().length === 0) {
		refEmailError.innerHTML = "This field is required.";
	}
	else if (emailTest.test(refEmailField.value) === false) {
		refEmailError.innerHTML = "Please enter a valid email.";
	} else {
		refEmailError.innerHTML = "";
		checkEmail = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "EmailTaken.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.responseType = "text";
			xhr.onload = function() {
				refEmailError.innerHTML = xhr.responseText;
			}
			xhr.send("email=" + encodeURIComponent(refEmailField.value));
		}, 350);
	}
});
refEmailField.addEventListener("keydown", function() {
	clearTimeout(checkUser);
	clearTimeout(checkEmail);
	clearTimeout(checkSignup);
});
function submitSignUp(event) {
	if (event.button === 0) {
		clearTimeout(checkUser);
		clearTimeout(checkEmail);
		clearTimeout(checkSignup);
		if (refUsernameField.value.length > 3 &&
			refUsernameField.value.length < 20 &&
			userDirtRegexp.test(refUsernameField.value) === false &&
			refNewPasswordField.value.replace(passEasyTest, "") !== "" &&
			refNewPasswordField.value.length >= 8 &&
			refNewPasswordField.value === refConfirmPasswordField.value &&
			refFirstNameField.value.length > 0 &&
			/[0-9]/g.test(refFirstNameField.value) === false &&
			refLastNameField.value.length > 0 &&
			/[0-9]/g.test(refLastNameField.value) === false &&
			refEmailField.value.length > 0 &&
			emailTest.test(refEmailField.value) === true) {
			checkSignup = setTimeout(function() {
				const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
				xhr.open("POST", "signup.php", true);
				xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
				xhr.responseType = "json";
				refSignUpButtonCont.innerHTML = '<div id="loadingImageCont"></div>';
				xhr.onload = function() {
					refUsernameError.innerHTML = xhr.response["errormessages"]["usernameError"];
					refNewPasswordError.innerHTML = xhr.response["errormessages"]["passwordError"];
					refFirstNameError.innerHTML = xhr.response["errormessages"]["fNameError"];
					refLastNameError.innerHTML = xhr.response["errormessages"]["lNameError"];
					refEmailError.innerHTML = xhr.response["errormessages"]["emailError"];
					refSignUpMessage.innerHTML = xhr.response["message"];
					refSignUpButtonCont.innerHTML = '<button id="signUpButton" onmouseup="submitSignUp(event)" onmousedown="cancelSubmitSignUpTimeout(event)">Sign Up</button>';
				}
				xhr.send("username=" + encodeURIComponent(refUsernameField.value) + "&password=" + encodeURIComponent(refNewPasswordField.value) + "&fName=" + encodeURIComponent(refFirstNameField.value) + "&lName=" + encodeURIComponent(refLastNameField.value) + "&email=" + encodeURIComponent(refEmailField.value));
			}, 350);
		}
	}
}
function cancelSubmitSignUpTimeout(event) {
	if (event.button === 0) {
		clearTimeout(checkUser);
		clearTimeout(checkEmail);
		clearTimeout(checkSignup);
	}
}