"use strict";
const refMenuButton = document.querySelector("#menuToggle");
const refSideNav = document.querySelector("#sidenav");
const refTickBoxes = document.querySelectorAll(".marketCategoryBox");
const refMarketNameField = document.querySelector("#marketNameField");
const refMarketNameError = document.querySelector("#marketNameError");
var checkMarketName;
refMenuButton.style.filter = "brightness(100%)";
refMenuButton.style.cursor = "pointer";
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
refTickBoxes.forEach(function(eachBox) {
	eachBox.addEventListener("mouseup", function(triggered) {
		if (triggered.button === 0) {
			eachBox.classList.toggle("tickedCategoryBox");
		}
	});
});
refMarketNameField.addEventListener("keyup", function(keyPress) {
	if (refMarketNameField.value.length === 0) {
		refMarketNameError.innerHTML = "This field is required.";
	}
	else if (refMarketNameField.value.length < 3) {
		refMarketNameError.innerHTML = "Market name must contain at least 3 characters.";
	}
	else if (refMarketNameField.value.length > 30) {
		refMarketNameError.innerHTML = "Market name has to be under 30 characters.";
	} else {
		refMarketNameError.innerHTML = "";
		checkMarketName = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "marketNameTaken.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.responseType = "text";
			xhr.onload = function() {
				if (xhr.status === 200) {
					refMarketNameError.innerHTML = xhr.responseText;
				} else {
					refMarketNameError.innerHTML = "An internal server error occurred. Please try again later.";
				}
			}
			xhr.send("marketname=" + encodeURIComponent(refMarketNameField.value));
		}, 350);
	}
});