"use strict";
const emailTest = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
const passEasyTest = /(strong(er)*)*(complex)*(password[0-9]{0,3})/gi;
const userDirtRegexp = /[^a-z0-9._]/gi;
const refMenuButton = document.querySelector("#menuToggle");
const refSideNav = document.querySelector("#sidenav");
const refThemeSwitch = document.querySelector("#darkThemeSwitch");
const refStyleSheetLink = document.querySelector("#stylesheetLink");
const refSettingsInfoCont = document.querySelector("#settingsInfoCont");
const refSelectedTabLine = document.querySelector("#selectedTabLine");
const refAccountButton = document.querySelector("#accountButton");
const refSecurityButton = document.querySelector("#securityButton");
const refChangePassButton = document.querySelector("#changePasswordText");
var checkEmailVerification;
var checkUser;
var checkUserSubmit;
var checkPassword;
var checkEmail;
var checkEmailSubmit;
var checkBio;
var checkDeleteAccount;
var checkTwoFactorAuth;
var changeUserOpenedToggle = false;
var changePassOpenedToggle = false;
var changeEmailOpenedToggle = false;
var emailVerificationEmailSent = false;
var changeUserEmailSent = false;
var changePassEmailSent = false;
var changeEmailEmailSent = false;
var accountDeletionEmailSent = false;
var emailVerificationButtonText = "Send Email";
var changeUserButtonText = "Change Username";
var changePassButtonText = "Change Password";
var changeEmailButtonText = "Change Email";
var accountDeletionButtonText = "Send Email";
var changeBioButtonText = "Save Changes"
var emailVerificationError = "";
var newUsernameChangeError = "This field is required.";
var newPasswordChangeError = "This field is required.";
var confirmPasswordChangeError = "This field is required.";
var newEmailChangeError = "This field is required.";
var newEmailConfirmPasswordChangeError = "This field is required.";
var twoFactorAuthConfirmPasswordError = "This field is required.";
var bioInputError = "";
var accountDeletionError = "";
var newUsernameText = "";
var newPasswordText = "";
var confirmPasswordText = "";
var newEmailText = "";
var newEmailConfirmPasswordText = "";
var bioText = "";
var twoFactorAuthConfirmPasswordText = "";
refMenuButton.style.filter = "brightness(100%)";
refMenuButton.style.cursor = "pointer";
function getCookie(cookieName) {
	var name = cookieName + "=";
	var decodedCookie = decodeURIComponent(document.cookie);
	var cookieParts = decodedCookie.split(";");
	for(var i = 0; i < cookieParts.length; i++) {
		var eachCookiePart = cookieParts[i];
		if (eachCookiePart.charAt(0) == " ") {
			eachCookiePart = eachCookiePart.substring(1);
		}
		if (eachCookiePart.indexOf(name) == 0) {
			return eachCookiePart.substring(name.length);
		}
	}
	return "";
}
function changeUserToggle() {
	const refNewUserCont = document.querySelector("#newUserCont");
	const refNewUserSendEmailButton = document.querySelector("#sendChangeUsernameEmailButton");
	if (changeUserOpenedToggle === false) {
		if (!document.querySelector("#changeUsernameStyles")) {
			var addChangeUserStyles = document.createElement("style");
			document.head.appendChild(addChangeUserStyles);
			var addChangeUserSheet = addChangeUserStyles.sheet;
			addChangeUserStyles.id = "changeUsernameStyles";
			addChangeUserSheet.insertRule(`
			#changeUsernameText {
				margin-bottom: 15px !important;
			}`);
			addChangeUserSheet.insertRule(`
			#changeUserCont {
				height: ${refNewUserCont.getBoundingClientRect()["height"] + 5 + refNewUserSendEmailButton.getBoundingClientRect()["height"]}px !important;
			}`);
			addChangeUserSheet.insertRule(`
			#changeUserMenuArrow {
				transform: rotate(180deg);
			}`);
		}
		changeUserOpenedToggle = true;
	} else {
		if (document.querySelector("#changeUsernameStyles")) {
			const refChangeUserStyles = document.querySelector("#changeUsernameStyles");
			refChangeUserStyles.parentNode.removeChild(refChangeUserStyles);
		}
		changeUserOpenedToggle = false;
	}
}
function changePassToggle() {
	const refNewPassCont = document.querySelector("#newPassCont");
	const refInnerConfirmPassCont = document.querySelector("#innerConfirmPassCont");
	if (changePassOpenedToggle === false) {
		if (!document.querySelector("#changePasswordStyles")) {
			var addChangePassStyles = document.createElement("style");
			document.head.appendChild(addChangePassStyles);
			var addChangePassSheet = addChangePassStyles.sheet;
			addChangePassStyles.id = "changePasswordStyles";
			addChangePassSheet.insertRule(`
			#changePasswordText {
				margin-bottom: 15px !important;
			}`);
			addChangePassSheet.insertRule(`
			#changePassMenuArrow {
				transform: rotate(180deg);
			}`);
			addChangePassSheet.insertRule(`
			#changePassCont {
				height: ${refNewPassCont.getBoundingClientRect()["height"] + refInnerConfirmPassCont.getBoundingClientRect()["height"]}px !important;
			}`);
		}
		changePassOpenedToggle = true;
	} else {
		if (document.querySelector("#changePasswordStyles")) {
			const refChangePassStyles = document.querySelector("#changePasswordStyles");
			refChangePassStyles.parentNode.removeChild(refChangePassStyles);
		}
		changePassOpenedToggle = false;
	}
}
function changeEmailToggle() {
	const refNewEmailCont = document.querySelector("#newEmailCont");
	const refNewEmailConfirmPassCont = document.querySelector("#confirmPasswordNewEmailCont");
	const refNewEmailSendEmailButton = document.querySelector("#sendChangeEmailEmailButton");
	if (changeEmailOpenedToggle === false) {
		if (!document.querySelector("#changeEmailStyles")) {
			var addChangeEmailStyles = document.createElement("style");
			document.head.appendChild(addChangeEmailStyles);
			var addChangeEmailSheet = addChangeEmailStyles.sheet;
			addChangeEmailStyles.id = "changeEmailStyles";
			addChangeEmailSheet.insertRule(`
			#changeEmailText {
				margin-bottom: 15px !important;
			}`);
			addChangeEmailSheet.insertRule(`
			#changeEmailCont {
				height: ${refNewEmailCont.getBoundingClientRect()["height"] + refNewEmailConfirmPassCont.getBoundingClientRect()["height"] + 5 + refNewEmailSendEmailButton.getBoundingClientRect()["height"]}px !important;
			}`);
			addChangeEmailSheet.insertRule(`
			#changeEmailMenuArrow {
				transform: rotate(180deg);
			}`);
		}
		changeEmailOpenedToggle = true;
	} else {
		if (document.querySelector("#changeEmailStyles")) {
			const refChangeEmailStyles = document.querySelector("#changeEmailStyles");
			refChangeEmailStyles.parentNode.removeChild(refChangeEmailStyles);
		}
		changeEmailOpenedToggle = false;
	}
}
function newPassFieldShowToggle() {
	const newPassField = document.querySelector("#newPasswordField");
	if (newPassField.type === "password") {
		if (!document.querySelector("#newPasswordStyles")) {
			var createdToggleFieldStyle = document.createElement("style");
			document.head.appendChild(createdToggleFieldStyle);
			var createdToggleFieldSheet = createdToggleFieldStyle.sheet;
			createdToggleFieldStyle.id = "newPasswordStyles"
			createdToggleFieldSheet.insertRule(`
			#newPassImage {
				background-image: var(--showPassLink) !important;
			}`);
		}
		newPassField.type = "text";
	} else {
		if (document.querySelector("#newPasswordStyles")) {
			const newPassSheet = document.querySelector("#newPasswordStyles");
			newPassSheet.parentNode.removeChild(newPassSheet);
		}
		newPassField.type = "password";
	}
}
function confirmPassFieldShowToggle() {
	const confirmPassField = document.querySelector("#confirmPasswordField");
	if (confirmPassField.type === "password") {
		if (!document.querySelector("#confirmPasswordStyles")) {
			var createdToggleFieldStyle = document.createElement("style");
			document.head.appendChild(createdToggleFieldStyle);
			var createdToggleFieldSheet = createdToggleFieldStyle.sheet;
			createdToggleFieldStyle.id = "confirmPasswordStyles";
			createdToggleFieldSheet.insertRule(`
			#confirmPassImage {
				background-image: var(--showPassLink) !important;
			}`);
		}
		confirmPassField.type = "text";
	} else {
		if (document.querySelector("#confirmPasswordStyles")) {
			const confirmPassSheet = document.querySelector("#confirmPasswordStyles");
			confirmPassSheet.parentNode.removeChild(confirmPassSheet);
		}
		confirmPassField.type = "password";
	}
}
function newEmailConfirmPassFieldShowToggle() {
	const refNewEmailConfirmPassField = document.querySelector("#confirmPasswordNewEmailField");
	if (refNewEmailConfirmPassField.type === "password") {
		if (!document.querySelector("#newEmailConfirmPasswordStyles")) {
			var createdToggleFieldStyle = document.createElement("style");
			document.head.appendChild(createdToggleFieldStyle);
			var createdToggleFieldSheet = createdToggleFieldStyle.sheet;
			createdToggleFieldStyle.id = "newEmailConfirmPasswordStyles";
			createdToggleFieldSheet.insertRule(`
			#newEmailConfirmPassImage {
				background-image: var(--showPassLink) !important;
			}`);
		}
		refNewEmailConfirmPassField.type = "text";
	} else {
		if (document.querySelector("#newEmailConfirmPasswordStyles")) {
			const refNewEmailConfirmPasswordSheet = document.querySelector("#newEmailConfirmPasswordStyles");
			refNewEmailConfirmPasswordSheet.parentNode.removeChild(refNewEmailConfirmPasswordSheet);
		}
		refNewEmailConfirmPassField.type = "password";
	}
}
function change2FAConfirmPassFieldShowToggle() {
	const refChange2FAConfirmPassField = document.querySelector("#confirmPasswordChange2FAField");
	if (refChange2FAConfirmPassField.type === "password") {
		if (!document.querySelector("#change2FAConfirmPasswordStyles")) {
			var createdToggleFieldStyle = document.createElement("style");
			document.head.appendChild(createdToggleFieldStyle);
			var createdToggleFieldSheet = createdToggleFieldStyle.sheet;
			createdToggleFieldStyle.id = "change2FAConfirmPasswordStyles";
			createdToggleFieldSheet.insertRule(`
			#change2FAConfirmPassImage {
				background-image: var(--showPassLink) !important;
			}`);
		}
		refChange2FAConfirmPassField.type = "text";
	} else {
		if (document.querySelector("#change2FAConfirmPasswordStyles")) {
			const refChange2FAConfirmPasswordSheet = document.querySelector("#change2FAConfirmPasswordStyles");
			refChange2FAConfirmPasswordSheet.parentNode.removeChild(refChange2FAConfirmPasswordSheet);
		}
		refChange2FAConfirmPassField.type = "password";
	}
}
function validateChangeUserField(event) {
	const refNewUserField = document.querySelector("#newUsernameField");
	const refNewUserError = document.querySelector("#newUsernameError");
	clearTimeout(checkUser);
	clearTimeout(checkUserSubmit);
	if (userDirtRegexp.test(refNewUserField.value) === true) {
		refNewUserError.innerHTML = "Username may only contain letters, numbers, . and _.";
		newUsernameChangeError = "Username may only contain letters, numbers, . and _.";
	}
	else if (refNewUserField.value.trim().length === 0) {
		refNewUserError.innerHTML = "This field is required.";
		newUsernameChangeError = "This field is required.";
	}
	else if (refNewUserField.value.length < 3 || refNewUserField.value.length > 20) {
		refNewUserError.innerHTML = "Username may only contain between 3-20 characters.";
		newUsernameChangeError = "Username may only contain between 3-20 characters.";
	}
	else if (event.keyCode === 13) {
		updateUsername();
	} else {
		newUsernameChangeError = "";
		refNewUserError.innerHTML = "";
		checkUser = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "../UsernameTaken.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.responseType = "text";
			xhr.onload = function() {
				newUsernameChangeError = xhr.responseText
				refNewUserError.innerHTML = xhr.responseText;
			}
			xhr.send("username=" + encodeURIComponent(refNewUserField.value));
		}, 350);
	}
}
function cancelChangeUserFieldTimeout() {
	clearTimeout(checkUser);
	clearTimeout(checkUserSubmit);
}
function validateChangePassFields(event) {
	const refNewPassField = document.querySelector("#newPasswordField");
	const refNewPassError = document.querySelector("#newPasswordError");
	const refConfirmPassField = document.querySelector("#confirmPasswordField");
	const refConfirmPassError = document.querySelector("#confirmPasswordError");
	if (refNewPassField.value.trim().length === 0) {
		newPasswordChangeError = "This field is required.";
		refNewPassError.innerHTML = "This field is required.";
	}
	else if (refNewPassField.value.length < 8) {
		newPasswordChangeError = "Password must contain 8 or more characters.";
		refNewPassError.innerHTML = "Password must contain 8 or more characters.";
	}
	else if (refNewPassField.value.replace(passEasyTest, "") === "") {
		newPasswordChangeError = "Please create a stronger password.";
		refNewPassError.innerHTML = "Please create a stronger password.";
	}
	else if (refConfirmPassField.value !== refNewPassField.value) {
		newPasswordChangeError = "";
		refNewPassError.innerHTML = "";
		newPasswordChangeError = "Passwords do not match.";
		refConfirmPassError.innerHTML = "Passwords do not match.";
	} else {
		newPasswordChangeError = "";
		refNewPassError.innerHTML = "";
	}
	if (refConfirmPassField.value.trim().length === 0) {
		confirmPasswordChangeError = "This field is required.";
		refConfirmPassError.innerHTML = "This field is required.";
	}
	else if (refConfirmPassField.value !== refNewPassField.value) {
		confirmPasswordChangeError = "Passwords do not match.";
		refConfirmPassError.innerHTML = "Passwords do not match.";
	} else {
		confirmPasswordChangeError = "";
		refConfirmPassError.innerHTML = "";
	}
	if (event && event.keyCode === 13) {
		if (newPasswordChangeError === "" && confirmPasswordChangeError === "") {
			updatePassword();
		}
	}
}
function validateChangeEmailField(event) {
	const refEmailField = document.querySelector("#newEmailField");
	const refEmailError = document.querySelector("#newEmailError");
	const refNewEmailConfirmPassField = document.querySelector("#confirmPasswordNewEmailField");
	clearTimeout(checkEmail);
	if (refEmailField.value.trim().length === 0) {
		refEmailError.innerHTML = "This field is required.";
		newEmailChangeError = "This field is required.";
	}
	else if (emailTest.test(refEmailField.value) === false) {
		refEmailError.innerHTML = "Please enter a valid email.";
		newEmailChangeError = "Please enter a valid email.";
	}
	else if (event.keyCode === 13 && refNewEmailConfirmPassField.value.length > 0) {
		updateEmail();
	} else {
		refEmailError.innerHTML = "";
		newEmailChangeError = "";
		checkEmail = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "../EmailTaken.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.responseType = "text";
			xhr.onload = function() {
				newEmailChangeError = xhr.responseText;
				refEmailError.innerHTML = xhr.responseText;
			}
			xhr.send("email=" + encodeURIComponent(refEmailField.value));
		}, 350);
	}
}
function cancelChangeEmailFieldTimeout() {
	clearTimeout(checkEmail);
}
function validateNewEmailPasswordField(event) {
	const refEmailField = document.querySelector("#newEmailField");
	const refNewEmailConfirmPassField = document.querySelector("#confirmPasswordNewEmailField");
	const refNewEmailConfirmPassError = document.querySelector("#confirmPasswordNewEmailError");
	if (refNewEmailConfirmPassField.value.trim().length === 0) {
		refNewEmailConfirmPassError.innerHTML = "This field is required.";
		newEmailConfirmPasswordChangeError = "This field is required.";
	}
	else if (event.keyCode === 13 && refEmailField.value.length > 0) {
		updateEmail();
	} else {
		refNewEmailConfirmPassError.innerHTML = "";
		newEmailConfirmPasswordChangeError = "";
	}
}
function validateChange2FAPasswordField(event) {
	const refChange2FAConfirmPassField = document.querySelector("#confirmPasswordChange2FAField");
	const refChange2FAConfirmPassError = document.querySelector("#confirmPasswordChange2FAError");
	if (refChange2FAConfirmPassField.value.trim().length === 0) {
		refChange2FAConfirmPassError.innerHTML = "This field is required.";
		newEmailConfirmPasswordChangeError = "This field is required.";
	}
	else if (event.keyCode === 13) {
		twoFactorAuthSwitch();
	} else {
		refChange2FAConfirmPassError.innerHTML = "";
		newEmailConfirmPasswordChangeError = "";
	}
}
function sendEmailVerification() {
	if (emailVerificationEmailSent === false) {
		clearTimeout(checkEmailVerification);
		checkEmailVerification = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			emailVerificationEmailSent = true;
			xhr.open("POST", "../sendVerifEmail.php", true);
			xhr.responseType = "json";
			xhr.onload = function() {
				emailVerificationError = xhr.response["message"];
				if (document.querySelector("#verifyEmailError")) {
					const refVerifyEmailError = document.querySelector("#verifyEmailError");
					refVerifyEmailError.innerHTML = emailVerificationError;
				}
				var leftCooldown = xhr.response["leftoverCooldown"];
				if (leftCooldown <= 1) {
					emailVerificationButtonText = "Re-send Email";
					if (document.querySelector("#verifyEmailButton")) {
						const refVerifyEmailButton = document.querySelector("#verifyEmailButton");
						refVerifyEmailButton.innerHTML = emailVerificationButtonText;
					}
				} else {
					emailVerificationButtonText = "Re-send Email (" + leftCooldown + ")";
					leftCooldown--;
					if (document.querySelector("#verifyEmailButton")) {
						const refVerifyEmailButton = document.querySelector("#verifyEmailButton");
						refVerifyEmailButton.innerHTML = emailVerificationButtonText;
					}
					for (var i = 1; i <= xhr.response["leftoverCooldown"]; i++) {
						setTimeout(function() {
							if (leftCooldown === 0) {
								emailVerificationButtonText = "Re-send Email";
								emailVerificationEmailSent = false;
							} else {
								emailVerificationButtonText = "Re-send Email (" + leftCooldown + ")";
								leftCooldown--;
							}
							if (leftCooldown === xhr.response["leftoverCooldown"] - 3) {
								emailVerificationError = "";
							}
							if (document.querySelector("#verifyEmailButton")) {
								const refVerifyEmailButton = document.querySelector("#verifyEmailButton");
								refVerifyEmailButton.innerHTML = emailVerificationButtonText;
							}
							if (document.querySelector("#verifyEmailError")) {
								const refVerifyEmailError = document.querySelector("#verifyEmailError");
								refVerifyEmailError.innerHTML = emailVerificationError;
							}
						}, 1000 * i);
					}
				}
			}
			xhr.send();
		}, 350);
	}
}
function cancelEmailVerificationTimeout() {
	clearTimeout(checkEmailVerification);
}
function updateUsername() {
	const refNewUserField = document.querySelector("#newUsernameField");
	clearTimeout(checkUserSubmit);
	checkUserSubmit = setTimeout(function() {
		if (userDirtRegexp.test(refNewUserField.value) === false && refNewUserField.value.length >= 3 && refNewUserField.value.length <= 20) {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "updateAccountDetails.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.responseType = "json";
			xhr.onload = function() {
				newUsernameChangeError = xhr.response["message"];
				var leftCooldown = xhr.response["leftoverCooldown"];
				if (document.querySelector("#newUsernameError")) {
					const refNewUsernameError = document.querySelector("#newUsernameError");
					refNewUsernameError.innerHTML = newUsernameChangeError;
				}
				if (leftCooldown <= 1) {
					changeUserButtonText = "Re-send Email";
					if (document.querySelector("#sendChangeUsernameEmailButton")) {
						const refChangeUserButton = document.querySelector("#sendChangeUsernameEmailButton");
						refChangeUserButton.innerHTML = changeUserButtonText;
					}
				} else {
					changeUserButtonText = "Re-send Email (" + leftCooldown + ")";
					if (document.querySelector("#sendChangeUsernameEmailButton")) {
						const refChangeUserButton = document.querySelector("#sendChangeUsernameEmailButton");
						refChangeUserButton.innerHTML = changeUserButtonText;
					}
					leftCooldown--;
					for (var i = 1; i <= xhr.response["leftoverCooldown"]; i++) {
						setTimeout(function() {
							if (leftCooldown === 0) {
								changeUserButtonText = "Re-send Email";
								changeUserEmailSent = false;
							} else {
								changeUserButtonText = "Re-send Email (" + leftCooldown + ")";
								leftCooldown--;
							}
							if (leftCooldown === xhr.response["leftoverCooldown"] - 3) {
								newUsernameChangeError = "";
							}
							if (document.querySelector("#sendChangeUsernameEmailButton")) {
								const refChangeUserButton = document.querySelector("#sendChangeUsernameEmailButton");
								refChangeUserButton.innerHTML = changeUserButtonText;
							}
							if (document.querySelector("#newUsernameError")) {
								const refNewUsernameError = document.querySelector("#newUsernameError");
								refNewUsernameError.innerHTML = newUsernameChangeError;
							}
						}, 1000 * i);
					}
				}
			}
			xhr.send("type=1&content=" + encodeURIComponent(refNewUserField.value));
		}
	}, 350);
}
function cancelUpdateUsernameTimeout() {
	clearTimeout(checkUserSubmit);
}
function updatePassword() {
	const refNewPassField = document.querySelector("#newPasswordField");
	const refConfirmPassField = document.querySelector("#confirmPasswordField");
	clearTimeout(checkPassword);
	checkPassword = setTimeout(function() {
		if (refNewPassField.value.length >= 8 && refNewPassField.value.replace(passEasyTest, "") !== "" && refConfirmPassField.value === refNewPassField.value) {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "updateAccountDetails.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.responseType = "json";
			xhr.onload = function() {
				newPasswordChangeError = xhr.response["newPassError"] ? xhr.response["newPassError"] : "";
				confirmPasswordChangeError = xhr.response["message"];
				var leftCooldown = xhr.response["leftoverCooldown"];
				if (document.querySelector("#newPasswordError")) {
					const refNewPasswordError = document.querySelector("#newPasswordError");
					refNewPasswordError.innerHTML = newPasswordChangeError;
				}
				if (document.querySelector("#confirmPasswordError")) {
					const refConfirmPassError = document.querySelector("#confirmPasswordError");
					refConfirmPassError.innerHTML = confirmPasswordChangeError;
				}
				if (leftCooldown <= 1) {
					changePassButtonText = "Re-send Email";
					if (document.querySelector("#sendChangePasswordEmailButton")) {
						const refChangePassButton = document.querySelector("#sendChangePasswordEmailButton");
						refChangePassButton.innerHTML = changePassButtonText;
					}
				} else {
					changePassButtonText = "Re-send Email (" + leftCooldown + ")";
					if (document.querySelector("#sendChangePasswordEmailButton")) {
						const refChangePassButton = document.querySelector("#sendChangePasswordEmailButton");
						refChangePassButton.innerHTML = changePassButtonText;
					}
					leftCooldown--;
					for (var i = 1; i <= xhr.response["leftoverCooldown"]; i++) {
						setTimeout(function() {
							if (leftCooldown === 0) {
								changePassButtonText = "Re-send Email";
								changePassEmailSent = false;
							} else {
								changePassButtonText = "Re-send Email (" + leftCooldown + ")";
								leftCooldown--;
							}
							if (leftCooldown === xhr.response["leftoverCooldown"] - 3) {
								confirmPasswordChangeError = "";
							}
							if (document.querySelector("#sendChangePasswordEmailButton")) {
								const refChangePassButton = document.querySelector("#sendChangePasswordEmailButton");
								refChangePassButton.innerHTML = changePassButtonText;
							}
							if (document.querySelector("#newPasswordError")) {
								const refNewPasswordError = document.querySelector("#newPasswordError");
								refNewPasswordError.innerHTML = newPasswordChangeError;
							}
							if (document.querySelector("#confirmPasswordError")) {
								const refConfirmPassError = document.querySelector("#confirmPasswordError");
								refConfirmPassError.innerHTML = confirmPasswordChangeError;
							}
						}, 1000 * i);
					}
				}
			}
			xhr.send("type=2&content=" + encodeURIComponent(refNewPassField.value));
		}
	}, 350);
}
function cancelUpdatePasswordTimeout() {
	clearTimeout(checkPassword);
}
function updateEmail() {
	const refNewEmailField = document.querySelector("#newEmailField");
	const refNewEmailConfirmPassField = document.querySelector("#confirmPasswordNewEmailField");
	clearTimeout(checkEmailSubmit);
	checkEmailSubmit = setTimeout(function() {
		if (emailTest.test(refNewEmailField.value) === true) {
			if (refNewEmailConfirmPassField.value.trim().length > 0) {
				const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
				xhr.open("POST", "updateAccountDetails.php", true);
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr.responseType = "json";
				xhr.onload = function() {
					newEmailChangeError = xhr.response["newEmailError"] ? xhr.response["newEmailError"] : "";
					newEmailConfirmPasswordChangeError = xhr.response["message"];
					var leftCooldown = xhr.response["leftoverCooldown"];
					newEmailConfirmPasswordText = "";
					refNewEmailConfirmPassField.value = "";
					if (document.querySelector("#newEmailError")) {
						const refNewEmailError = document.querySelector("#newEmailError");
						refNewEmailError.innerHTML = newEmailChangeError;
					}
					if (document.querySelector("#confirmPasswordNewEmailError")) {
						const refNewEmailConfirmPassError = document.querySelector("#confirmPasswordNewEmailError");
						refNewEmailConfirmPassError.innerHTML = newEmailConfirmPasswordChangeError;
					}
					if (leftCooldown <= 1) {
						changeEmailButtonText = "Re-send Email";
						if (document.querySelector("#sendChangeEmailEmailButton")) {
							const refChangeEmailButton = document.querySelector("#sendChangeEmailEmailButton");
							refChangeEmailButton.innerHTML = changeEmailButtonText;
						}
					} else {
						changeEmailButtonText = "Re-send Email (" + leftCooldown + ")";
						if (document.querySelector("#sendChangeEmailEmailButton")) {
							const refChangeEmailButton = document.querySelector("#sendChangeEmailEmailButton");
							refChangeEmailButton.innerHTML = changeEmailButtonText;
						}
						leftCooldown--;
						for (var i = 1; i <= xhr.response["leftoverCooldown"]; i++) {
							setTimeout(function() {
								if (leftCooldown === 0) {
									changeEmailButtonText = "Re-send Email";
									changeEmailEmailSent = false;
								} else {
									changeEmailButtonText = "Re-send Email (" + leftCooldown + ")";
									leftCooldown--;
								}
								if (leftCooldown === xhr.response["leftoverCooldown"] - 3) {
									newEmailConfirmPasswordChangeError = "";
								}
								if (document.querySelector("#sendChangeEmailEmailButton")) {
									const refChangeEmailButton = document.querySelector("#sendChangeEmailEmailButton");
									refChangeEmailButton.innerHTML = changeEmailButtonText;
								}
								if (document.querySelector("#newEmailError")) {
									const refNewEmailError = document.querySelector("#newEmailError");
									refNewEmailError.innerHTML = newEmailChangeError;
								}
								if (document.querySelector("#confirmPasswordNewEmailError")) {
									const refNewEmailConfirmPassError = document.querySelector("#confirmPasswordNewEmailError");
									refNewEmailConfirmPassError.innerHTML = newEmailConfirmPasswordChangeError;
								}
							}, 1000 * i);
						}
					}
				}
				xhr.send("type=3&content=" + encodeURIComponent(refNewEmailField.value) + "&content2=" + encodeURIComponent(refNewEmailConfirmPassField.value));
			} else {
				newEmailConfirmPasswordChangeError = "This field is required.";
				if (document.querySelector("#confirmPasswordNewEmailError")) {
					const refNewEmailConfirmPassError = document.querySelector("#confirmPasswordNewEmailError");
					refNewEmailConfirmPassError.innerHTML = newEmailConfirmPasswordChangeError;
				}
			}
		}
	}, 350);
}
function cancelUpdateEmailTimeout() {
	clearTimeout(checkEmailSubmit);
}
function updateBio() {
	const refBioInput = document.querySelector("#bioInput");
	const refBioError = document.querySelector("#bioInputError");
	clearTimeout(checkBio);
	checkBio = setTimeout(function() {
		const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
		xhr.open("POST", "updateAccountDetails.php", true);
		xhr.setRequestHeader("Content-type", "application/json");
		xhr.responseType = "json";
		xhr.onload = function() {
			bioInputError = xhr.response["message"];
			refBioError.innerHTML = xhr.response["message"];
		}
		xhr.send(JSON.stringify({type : 4, content : refBioInput.value}));
	}, 350);
}
function cancelUpdateBioTimeout() {
	clearTimeout(checkBio);
}
function deleteAccount() {
	if (accountDeletionEmailSent === false) {
		clearTimeout(checkDeleteAccount);
		checkDeleteAccount = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			accountDeletionEmailSent = true;
			xhr.open("POST", "../sendDeletionEmail.php", true);
			xhr.responseType = "json";
			xhr.onload = function() {
				accountDeletionError = xhr.response["message"];
				var leftCooldown = xhr.response["leftoverCooldown"];
				if (document.querySelector("#deleteAccountError")) {
					const refDeleteAccountError = document.querySelector("#deleteAccountError");
					refDeleteAccountError.innerHTML = accountDeletionError;
				}
				if (leftCooldown <= 1) {
					accountDeletionButtonText = "Re-send Email";
					if (document.querySelector("#deleteAccountButton")) {
						const refDeleteAccountButton = document.querySelector("#deleteAccountButton");
						refDeleteAccountButton.innerHTML = accountDeletionButtonText;
					}
				} else {
					accountDeletionButtonText = "Re-send Email (" + leftCooldown + ")";
					if (document.querySelector("#deleteAccountButton")) {
						const refDeleteAccountButton = document.querySelector("#deleteAccountButton");
						refDeleteAccountButton.innerHTML = accountDeletionButtonText;
					}
					leftCooldown--;
					for (var i = 1; i <= xhr.response["leftoverCooldown"]; i++) {
						setTimeout(function() {
							if (leftCooldown === 0) {
								accountDeletionButtonText = "Re-send Email";
								accountDeletionEmailSent = false;
							} else {
								accountDeletionButtonText = "Re-send Email (" + leftCooldown + ")";
								leftCooldown--;
							}
							if (leftCooldown === xhr.response["leftoverCooldown"] - 3) {
								accountDeletionError = "";
							}
							if (document.querySelector("#deleteAccountButton")) {
								const refDeleteAccountButton = document.querySelector("#deleteAccountButton");
								refDeleteAccountButton.innerHTML = accountDeletionButtonText;
							}
							if (document.querySelector("#deleteAccountError")) {
								const refDeleteAccountError = document.querySelector("#deleteAccountError");
								refDeleteAccountError.innerHTML = accountDeletionError;
							}
						}, 1000 * i);
					}
				}
			}
			xhr.send();
		}, 350);
	}
}
function cancelDeleteAccountTimeout() {
	clearTimeout(checkDeleteAccount);
}
function themeSwitch() {
	if (getCookie("darktheme") !== "false") {
		document.cookie = "darktheme=false; expires=31 Dec 10000 12:00:00 UTC; path=/";
		if (refStyleSheetLink.href !== "https://streetor.sg/settings/settingsLightTheme.css") {
			refStyleSheetLink.setAttribute("href", "settingsLightTheme.css");
		}
	} else {
		document.cookie = "darktheme=true; expires=31 Dec 10000 12:00:00 UTC; path=/";
		if (refStyleSheetLink.href !== "https://streetor.sg/settings/settingsDarkTheme.css") {
			refStyleSheetLink.setAttribute("href", "settingsDarkTheme.css");
		}
	}
}
function twoFactorAuthSwitch() {
	const refTwoFactorAuthConfirmPasswordField = document.querySelector("#confirmPasswordChange2FAField");
	const refTwoFactorAuthConfirmPasswordError = document.querySelector("#confirmPasswordChange2FAError");
	if (refTwoFactorAuthConfirmPasswordField.value.trim().length > 0) {
		clearTimeout(checkTwoFactorAuth);
		checkTwoFactorAuth = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "updateAccountDetails.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.responseType = "json";
			xhr.onerror = function() {
				twoFactorAuthConfirmPasswordError = "An internal error occurred. Please try again later.";
				refTwoFactorAuthConfirmPasswordError.innerHTML = "An internal error occurred. Please try again later.";
			}
			xhr.onload = function() {
				const refTwoFactorAuthSwitchCont = document.querySelector("#twoFactorAuthSwitchCont");
				const refTwoFactorAuthSwitch = document.querySelector("#twoFactorAuthSwitch");
				twoFactorAuthConfirmPasswordError = xhr.response["message"];
				refTwoFactorAuthConfirmPasswordError.innerHTML = xhr.response["message"];
				twoFactorAuthConfirmPasswordText = "";
				refTwoFactorAuthConfirmPasswordField.value = "";
				if (xhr.response["switch"] === true) {
					 if (!refTwoFactorAuthSwitchCont.classList.contains("switchedOnSwitchCont")) {
						refTwoFactorAuthSwitchCont.classList.add("switchedOnSwitchCont");
					 }
					 if (!refTwoFactorAuthSwitch.classList.contains("switchedOnSwitch")) {
						refTwoFactorAuthSwitch.classList.add("switchedOnSwitch");
					 }
				} else {
					if (refTwoFactorAuthSwitchCont.classList.contains("switchedOnSwitchCont")) {
					   refTwoFactorAuthSwitchCont.classList.remove("switchedOnSwitchCont");
					}
					if (refTwoFactorAuthSwitch.classList.contains("switchedOnSwitch")) {
					   refTwoFactorAuthSwitch.classList.remove("switchedOnSwitch");
					}
				}
			}
			xhr.send("type=5&content=null&content2=" + encodeURIComponent(refTwoFactorAuthConfirmPasswordField.value));
		}, 350);
	} else {
		twoFactorAuthConfirmPasswordError = "This field is required.";
		if (document.querySelector("#confirmPasswordChange2FAError")) {
			const refTwoFactorAuthConfirmPassError = document.querySelector("#confirmPasswordChange2FAError");
			refTwoFactorAuthConfirmPassError.innerHTML = twoFactorAuthConfirmPasswordError;
		}
	}
}
function cancelTwoFactorAuthSwitchTimeout() {
	clearTimeout(checkTwoFactorAuth);
}
refAccountButton.addEventListener("click", function(triggered) {
	if (triggered.button === 0 && refAccountButton.classList.contains("selectedTab") === false) {
		const refTwoFactorAuthConfirmPasswordField = document.querySelector("#confirmPasswordChange2FAField");
		const refTwoFactorAuthConfirmPasswordError = document.querySelector("#confirmPasswordChange2FAError");
		twoFactorAuthConfirmPasswordText = refTwoFactorAuthConfirmPasswordField.value;
		twoFactorAuthConfirmPasswordError = refTwoFactorAuthConfirmPasswordError.innerHTML;
		refAccountButton.classList.toggle("selectedTab");
		refSecurityButton.classList.toggle("selectedTab");
		refSelectedTabLine.style.top = 0;
		refSettingsInfoCont.innerHTML = `
		<h1 class="topHeaderInfo notSelectable">Details</h1>
		<div class="infoRow" id="usernameRow">
			<p class="rowInfo notSelectable" id="usernameRowInfo">Username:</p>
		</div>
		<div class="infoRow" id="emailRow">
			<p class="rowInfo notSelectable" id="emailRowInfo">Email:</p>
		</div>
		<div class="infoColumnRow" id="emailVerifiedRow" style="padding-bottom: 0;">
			<p class="rowInfo notSelectable" id="emailVerifiedRowInfo" style="margin-bottom: 10px;">Email Verified: </p>
		</div>
		<h1 class="notSelectable">Change Details</h1>
		<div class="infoColumnRow">
			<p id="changeUsernameText" class="notSelectable" onclick="changeUserToggle()">
				Change Your Username
				<svg version="1.0" xmlns="http://www.w3.org/2000/svg" class="dropDownMenuArrow" id="changeUserMenuArrow" width="10px" height="10px" viewBox="0 0 1131.000000 744.000000" preserveAspectRatio="xMidYMid meet">
					<g transform="translate(0.000000,744.000000) scale(0.100000,-0.100000)" stroke="none">
						<path d="M887 6552 c-482 -482 -877 -882 -877 -887 0 -13 5642 -5655 5655
						-5655 12 0 5645 5645 5645 5657 0 12 -1753 1763 -1765 1763 -5 0 -882 -872
						-1947 -1937 l-1938 -1938 -1938 1938 c-1065 1065 -1942 1937 -1947 1937 -6 0
						-405 -395 -888 -878z"/>
					</g>
				</svg>
			</p>
			<div id="changeUserCont">
				<div id="newUserCont" style="margin-bottom: 5px;">
					<label for="newUsername" id="newUsernameLabel">New Username</label>
					<input type="text" value="${newUsernameText}" onkeyup="validateChangeUserField(Event)" onkeydown="cancelChangeUserFieldTimeout()" placeholder="New Username" autocomplete="off" id="newUsernameField">
					<p id="newUsernameError" class="inputErrorText">${newUsernameChangeError}</p>
				</div>
				<div class="sendEmailButtonCont" style="height: 40px;">
					<button id="sendChangeUsernameEmailButton" class="notSelectable" onmouseup="updateUsername()" onmousedown="cancelUpdateUsernameTimeout()">${changeUserButtonText}</button>
				</div>
			</div>
		</div>
		<div class="infoColumnRow">
			<p id="changePasswordText" class="notSelectable" onclick="changePassToggle()">
				Change Your Password
				<svg version="1.0" xmlns="http://www.w3.org/2000/svg" class="dropDownMenuArrow" id="changePassMenuArrow" width="10px" height="10px" viewBox="0 0 1131.000000 744.000000" preserveAspectRatio="xMidYMid meet">
					<g transform="translate(0.000000,744.000000) scale(0.100000,-0.100000)" stroke="none">
						<path d="M887 6552 c-482 -482 -877 -882 -877 -887 0 -13 5642 -5655 5655
						-5655 12 0 5645 5645 5645 5657 0 12 -1753 1763 -1765 1763 -5 0 -882 -872
						-1947 -1937 l-1938 -1938 -1938 1938 c-1065 1065 -1942 1937 -1947 1937 -6 0
						-405 -395 -888 -878z"/>
					</g>
				</svg>
			</p>
			<div id="changePassCont">
				<div id="newPassCont">
					<label for="newPassword" id="newPasswordLabel">New Password</label>
					<input type="password" value="${newPasswordText}" onkeyup="validateChangePassFields(Event)" placeholder="New Password" autocomplete="off" id="newPasswordField">
					<button id="newPassShowButton" class="passwordShowButton notSelectable" onclick="newPassFieldShowToggle()">
						<div class="showPassImage" id="newPassImage"></div>
					</button>
					<p id="newPasswordError" class="inputErrorText">${newPasswordChangeError}</p>
				</div>
				<div id="innerConfirmPassCont">
					<div id="confirmPassCont">
						<label for="confirmPassword" id="confirmPasswordLabel">Confirm Password</label>
						<input type="password" value="${confirmPasswordText}" onkeyup="validateChangePassFields(Event)" placeholder="Confirm Password" autocomplete="off" id="confirmPasswordField">
						<button id="confirmPassShowButton" class="passwordShowButton notSelectable" onclick="confirmPassFieldShowToggle()">
							<div class="showPassImage" id="confirmPassImage"></div>
						</button>
						<p id="confirmPasswordError" class="inputErrorText">${confirmPasswordChangeError}</p>
					</div>
					<div class="sendEmailButtonCont" style="height: 40px;">
						<button id="sendChangePasswordEmailButton" class="notSelectable" onmouseup="updatePassword()" onmousedown="cancelUpdatePasswordTimeout()">${changePassButtonText}</button>
					</div>
				</div>
			</div>
		</div>
		<div class="infoColumnRow">
			<p id="changeEmailText" class="notSelectable" onclick="changeEmailToggle()">
				Change Your Email
				<svg version="1.0" xmlns="http://www.w3.org/2000/svg" class="dropDownMenuArrow" id="changeEmailMenuArrow" width="10px" height="10px" viewBox="0 0 1131.000000 744.000000" preserveAspectRatio="xMidYMid meet">
					<g transform="translate(0.000000,744.000000) scale(0.100000,-0.100000)" stroke="none">
						<path d="M887 6552 c-482 -482 -877 -882 -877 -887 0 -13 5642 -5655 5655
						-5655 12 0 5645 5645 5645 5657 0 12 -1753 1763 -1765 1763 -5 0 -882 -872
						-1947 -1937 l-1938 -1938 -1938 1938 c-1065 1065 -1942 1937 -1947 1937 -6 0
						-405 -395 -888 -878z"/>
					</g>
				</svg>
			</p>
			<div id="changeEmailCont">
				<div id="newEmailCont" style="margin-bottom: 5px;">
					<label for="newEmail" id="newEmailLabel">New Email</label>
					<input type="text" value="${newEmailText}" onkeyup="validateChangeEmailField(Event)" onkeydown="cancelChangeEmailFieldTimeout()" placeholder="New Email" autocomplete="off" id="newEmailField">
					<p id="newEmailError" class="inputErrorText">${newEmailChangeError}</p>
				</div>
				<div id="confirmPasswordNewEmailCont">
					<label for="newEmailConfirmPassword" id="newEmailConfirmPasswordLabel">Password</label>
					<input type="password" value="${newEmailConfirmPasswordText}" placeholder="Password" autocomplete="off" id="confirmPasswordNewEmailField" onkeyup="validateNewEmailPasswordField(Event)">
					<button id="newEmailConfirmPassShowButton" class="passwordShowButton notSelectable" onclick="newEmailConfirmPassFieldShowToggle()">
						<div class="showPassImage" id="newEmailConfirmPassImage"></div>
					</button>
					<p id="confirmPasswordNewEmailError" class="inputErrorText">${newEmailConfirmPasswordChangeError}</p>
				</div>
				<div class="sendEmailButtonCont" style="height: 40px;">
					<button id="sendChangeEmailEmailButton" class="notSelectable" onmouseup="updateEmail()" onmousedown="cancelUpdateEmailTimeout()">${changeEmailButtonText}</button>
				</div>
			</div>
		</div>
		<h1 class="notSelectable">Other Settings</h1>
		<div class="infoColumnRow">
			<label for="bioInput" onkeyup="saveBioValue()" class="rowInfo notSelectable" id="bioInfoLabel" style="vertical-align: top;">Biography (optional):</label>
			<div id="bioContainer">
				<textarea id="bioInput" placeholder="Share about yourself in 200 characters." maxlength="200" rows="10">${bioText}</textarea>
			</div>
			<p id="bioInputError" class="inputErrorText">${bioInputError}</p>
			<button id="saveBioButton" class="notSelectable" onmouseup="updateBio()" onmousedown="cancelUpdateBioTimeout()">${changeBioButtonText}</button>
		</div>
		<div class="infoRow">
			<label for="darkThemeSwitchCont" class="rowInfo notSelectable" id="darkThemeLabel">Dark Theme:</label>
			<span id="darkThemeSwitchCont" class="sliderSwitchCont" onclick="themeSwitch()">
				<span id="darkThemeSwitch"></span>
			</span>
		</div>
		<div class="infoColumnRow" style="padding-bottom: 0;">
			<label id="deleteAccountButtonLabel" for="deleteAccountButtonLabel" class="rowInfo notSelectable">Delete This Account:</label>
			<div id="deleteAccountButtonCont" style="height: 40px;">
				<button id="deleteAccountButton" class="notSelectable" onmouseup="deleteAccount()" onmousedown="cancelDeleteAccountTimeout()">${accountDeletionButtonText}</button>
			</div>
			<p id="deleteAccountError" class="inputErrorText">${accountDeletionError}</p>
		</div>`;
		const refUsernameInfo = document.querySelector("#usernameRowInfo");
		const refEmailInfo = document.querySelector("#emailRowInfo");
		const refEmailVerifiedRow = document.querySelector("#emailVerifiedRow");
		const refEmailVerifiedInfo = document.querySelector("#emailVerifiedRowInfo");
		const refBioInfo = document.querySelector("#bioInfoLabel");
		const refBioCont = document.querySelector("#bioContainer");
		const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
		xhr.open("GET", "fetchAccountTabDetails.php", true);
		xhr.responseType = "json";
		xhr.onerror = function() {
			refUsernameInfo.innerHTML = "Username: [Error. Please try again later]";
			refEmailInfo.innerHTML = "Email: [Error. Please try again later]";
			refEmailVerifiedInfo.innerHTML = "Email Verified: [Error. Please try again later.]";
			refBioInfo.innerHTML = "Biography (optional): [Error. Please try again later.]";
		}
		xhr.onload = function() {
			refUsernameInfo.innerHTML = "Username: " + xhr.response["username"];
			refEmailInfo.innerHTML = "Email: " + xhr.response["email"];
			refEmailVerifiedRow.style = xhr.response["emailVerifiedRowStyle"];
			refEmailVerifiedInfo.style = xhr.response["emailVerifiedRowInfoStyle"];
			refEmailVerifiedInfo.innerHTML += xhr.response["emailVerifiedRowInfoText"];
			refEmailVerifiedRow.innerHTML += xhr.response["emailVerificationHTML"];
			refBioCont.innerHTML = xhr.response["biographyHTML"];
			if (document.querySelector("#verifyEmailButton")) {
				document.querySelector("#verifyEmailButton").innerHTML = emailVerificationButtonText;
			}
			if (document.querySelector("#verifyEmailError")) {
				document.querySelector("#verifyEmailError").innerHTML = emailVerificationError;
			}
		}
		xhr.send();
	}
});
refSecurityButton.addEventListener("click", function(triggered) {
	if (triggered.button === 0 && refSecurityButton.classList.contains("selectedTab") === false) {
		const refNewUserField = document.querySelector("#newUsernameField");
		const refNewPassField = document.querySelector("#newPasswordField");
		const refConfirmPassField = document.querySelector("#confirmPasswordField");
		const refNewEmailField = document.querySelector("#newEmailField");
		const refNewEmailConfirmPassField = document.querySelector("#confirmPasswordNewEmailField");
		const refBioInput = document.querySelector("#bioInput");
		newUsernameText = refNewUserField.value;
		newPasswordText = refNewPassField.value;
		confirmPasswordText = refConfirmPassField.value;
		newEmailText = refNewEmailField.value;
		newEmailConfirmPasswordText = refNewEmailConfirmPassField.value;
		bioText = refBioInput.value;
		refAccountButton.classList.toggle("selectedTab");
		refSecurityButton.classList.toggle("selectedTab");
		refSelectedTabLine.style.top = "50px";
		refSettingsInfoCont.innerHTML = `
		<h1 class="topHeaderInfo notSelectable">Account Security</h1>
		<div class="infoColumnRow" id="twoFactorAuthRow" style="border: 0;">
			<div id="change2FACont" class="infoRow" style="margin-bottom: 5px; padding: 0; border: 0;">
				<p id="twoFactorAuthLabel" class="rowInfo notSelectable">2 Factor Authentication:</p>
			</div>
			<div id="confirmPasswordChange2FACont">
				<label for="change2FAConfirmPassword" id="change2FAConfirmPasswordLabel">Password</label>
				<input value="${twoFactorAuthConfirmPasswordText}" type="password" placeholder="Password" autocomplete="off" id="confirmPasswordChange2FAField" onkeyup="validateChange2FAPasswordField(Event)">
				<button id="change2FAConfirmPassShowButton" class="passwordShowButton notSelectable" onclick="change2FAConfirmPassFieldShowToggle()">
					<div class="showPassImage" id="change2FAConfirmPassImage"></div>
				</button>
				<p id="confirmPasswordChange2FAError" class="inputErrorText">${twoFactorAuthConfirmPasswordError}</p>
			</div>
		</div>`;
		const refTwoFactorAuthCont = document.querySelector("#change2FACont");
		const refTwoFactorAuthText = document.querySelector("#twoFactorAuthLabel");
		const refTwoFactorAuthMessage = document.querySelector("#confirmPasswordChange2FAError");
		const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
		xhr.open("GET", "fetchSecurityTabDetails.php", true);
		xhr.responseType = "json";
		xhr.onerror = function() {
			refTwoFactorAuthText.innerHTML = "Two Factor Authentication: [Error. Please try again later]";
			refTwoFactorAuthMessage.innerHTML = "An internal error occurred. Please try again later.";
		}
		xhr.onload = function() {
			refTwoFactorAuthText.innerHTML += xhr.response["concatText"];
			refTwoFactorAuthCont.innerHTML += xhr.response["concatSlider"]
		}
		xhr.send();
	}
});
refMenuButton.addEventListener("click", function(triggered) {
	if (triggered.button === 0) {
		if (refSideNav.classList.contains("openedSideNav") || refMenuButton.style.animationName === "menuAnimationOpen") {
			refSideNav.classList.remove("openedSideNav");
			refMenuButton.style.animationName = "menuAnimationClose";
		} else {
			refSideNav.classList.add("openedSideNav");
			refMenuButton.style.animationName = "menuAnimationOpen";
		}
	}
});