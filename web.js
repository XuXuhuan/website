"use strict";
const showpassReferral = document.querySelector("#showpass");
const usernameFieldReferral = document.querySelector("#usernameField");
const usernameErrorReferral = document.querySelector("#usernameError");
const passFieldReferral = document.querySelector("#passwordField");
const passErrorReferral = document.querySelector("#passwordError");
const fNameFieldReferral = document.querySelector("#firstNameField");
const fNameErrorReferral = document.querySelector("#firstNameError");
const lNameFieldReferral = document.querySelector("#lastNameField");
const lNameErrorReferral = document.querySelector("#lastNameError");
const emailFieldReferral = document.querySelector("#emailField");
const emailErrorReferral = document.querySelector("#emailError");
const signupButtonReferral = document.querySelector("#signUpButton");
const signupMessageReferral = document.querySelector("#signupMessage");
const emailTest = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
const passEasyTest = /(strong(er)*)*(complex)*(password[0-9]{0,3})/gi;
const userDirtRegexp = /[^a-z0-9._]/gi;
var passShowing = false;
var checkUser;
var checkEmail;
var checkSignup;
showpassReferral.addEventListener("click", function() {
	passShowing = passShowing === false ? true : false;
	passFieldReferral.type = passShowing === false ? "password" : "text";
	showpassReferral.innerHTML = passShowing === false ? "Show password" : "Hide password";
});
usernameFieldReferral.addEventListener("keyup", function() {
	clearTimeout(checkUser);
	clearTimeout(checkEmail);
	clearTimeout(checkSignup);
	if (userDirtRegexp.test(usernameFieldReferral.value) === true) {
		usernameErrorReferral.innerHTML = "Username may only contain letters, numbers, . and _.";
	}
	else if (usernameFieldReferral.value.trim().length === 0) {
		usernameErrorReferral.innerHTML = "This field is required.";
	}
	else if (usernameFieldReferral.value.length < 3 || usernameFieldReferral.value.length > 20) {
		usernameErrorReferral.innerHTML = "Username may only contain between 3-20 characters.";
	} else {
		usernameErrorReferral.innerHTML = "";
		checkUser = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "UsernameTaken.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.responseType = "text";
			xhr.onload = function() {
				if (xhr.readyState === xhr.DONE) {
					if (xhr.status === 200) {
						usernameErrorReferral.innerHTML = xhr.responseText;
					}
				}
			}
			xhr.send("username=" + encodeURIComponent(usernameFieldReferral.value));
		}, 350);
	}
});
usernameFieldReferral.addEventListener("keydown", function() {
	clearTimeout(checkUser);
	clearTimeout(checkEmail);
	clearTimeout(checkSignup);
});
passFieldReferral.addEventListener("keyup", function() {
	if (passFieldReferral.value.trim().length === 0) {
		passErrorReferral.innerHTML = "This field is required.";
	}
	else if (passFieldReferral.value.length < 8) {
		passErrorReferral.innerHTML = "Password must contain 8 or more characters.";
	}
	else if (passFieldReferral.value.replace(passEasyTest, "") === "") {
		passErrorReferral.innerHTML = "Please create a stronger password.";
	} else {
		passErrorReferral.innerHTML = "";
	}
});
fNameFieldReferral.addEventListener("keyup", function() {
	if (fNameFieldReferral.value.trim().length === 0) {
		fNameErrorReferral.innerHTML = "This field is required.";
	}
	else if (/[0-9]/g.test(fNameFieldReferral.value) === true) {
		fNameErrorReferral.innerHTML = "This field cannot contain numbers.";
	} else {
		fNameErrorReferral.innerHTML = "";
	}
});
lNameFieldReferral.addEventListener("keyup", function() {
	if (lNameFieldReferral.value.trim().length === 0) {
		lNameErrorReferral.innerHTML = "This field is required.";
	}
	else if (/[0-9]/g.test(lNameFieldReferral.value) === true) {
		lNameErrorReferral.innerHTML = "This field cannot contain numbers.";
	} else {
		lNameErrorReferral.innerHTML = "";
	}
});
emailFieldReferral.addEventListener("keyup", function() {
	clearTimeout(checkUser);
	clearTimeout(checkEmail);
	clearTimeout(checkSignup);
	if (emailFieldReferral.value.trim().length === 0) {
		emailErrorReferral.innerHTML = "This field is required.";
	}
	else if (emailTest.test(emailFieldReferral.value) === false) {
		emailErrorReferral.innerHTML = "Please enter a valid email.";
	} else {
		emailErrorReferral.innerHTML = "";
		checkEmail = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "EmailTaken.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.responseType = "text";
			xhr.onload = function() {
				if (xhr.readyState === xhr.DONE) {
					if (xhr.status === 200) {
						emailErrorReferral.innerHTML = xhr.responseText;
					}
				}
			}
			xhr.send("email=" + encodeURIComponent(emailFieldReferral.value));
		}, 350);
	}
});
signupButtonReferral.addEventListener("mouseup", function() {
	clearTimeout(checkUser);
	clearTimeout(checkEmail);
	clearTimeout(checkSignup);
	if (usernameFieldReferral.value.length > 3 &&
		usernameFieldReferral.value.length < 20 &&
		userDirtRegexp.test(usernameFieldReferral.value) === false &&
		passFieldReferral.value.replace(passEasyTest, "") !== "" &&
		passFieldReferral.value.length >= 8 &&
		fNameFieldReferral.value.length > 0 &&
		/[0-9]/g.test(fNameFieldReferral.value) === false &&
		lNameFieldReferral.value.length > 0 &&
		/[0-9]/g.test(lNameFieldReferral.value) === false &&
		emailFieldReferral.value.length > 0 &&
		emailTest.test(emailFieldReferral.value) === true) {
		checkSignup = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "signup.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.responseType = "json";
			xhr.onload = function() {
				if (xhr.readyState === xhr.DONE && xhr.status === 200) {
					usernameErrorReferral.innerHTML = xhr.response["errormessages"]["usernameError"];
					passErrorReferral.innerHTML = xhr.response["errormessages"]["passwordError"];
					fNameErrorReferral.innerHTML = xhr.response["errormessages"]["fNameError"];
					lNameErrorReferral.innerHTML = xhr.response["errormessages"]["lNameError"];
					emailErrorReferral.innerHTML = xhr.response["errormessages"]["emailError"];
					signupMessageReferral.innerHTML = xhr.response["errormessages"]["MySQLiError"] === "" ? xhr.response["successmessage"] : xhr.response["errormessages"]["MySQLiError"];
				}
			}
			xhr.send("username=" + encodeURIComponent(usernameFieldReferral.value) + "&password=" + encodeURIComponent(passFieldReferral.value) + "&fName=" + encodeURIComponent(fNameFieldReferral.value) + "&lName=" + encodeURIComponent(lNameFieldReferral.value) + "&email=" + encodeURIComponent(emailFieldReferral.value));
		}, 350);
	}
});
signupButtonReferral.addEventListener("mousedown", function() {
	clearTimeout(checkUser);
	clearTimeout(checkEmail);
	clearTimeout(checkSignup);
});