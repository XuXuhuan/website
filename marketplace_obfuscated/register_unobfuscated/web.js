"use strict";
const refMenuButton = document.querySelector("#menuToggle");
const refSideNav = document.querySelector("#sidenav");
const refTickBoxes = document.querySelectorAll(".marketCategoryBox");
const refMarketNameField = document.querySelector("#marketNameField");
const refMarketNameError = document.querySelector("#marketNameError");
const refMarketRegisterButton = document.querySelector("#marketRegisterButton");
const refMarketRegisterError = document.querySelector("#registerMessage");
const refMarketRegisterButtonCont = document.querySelector("#marketRegisterButtonCont");
var checkMarketName;
var checkMarketRegister;
var assocRequestJSON = {
	marketName : "",
	marketCategories : {
		automotive : false,
		babyCare : false,
		books : false,
		CDandVinyl : false,
		clothesAndAccessories : false,
		electronics : false,
		gardening : false,
		outdoorsAndSports : false,
		groceries : false,
		health : false,
		household : false,
		personalCare : false,
		kitchenAndDining : false,
		travelSupplies : false,
		beauty : false,
		moviesAndTV : false,
		musicalInstruments : false,
		officeSupplies : false,
		petSupplies : false,
		software : false,
		tools : false,
		toys : false,
		games : false
	}
};
refMenuButton.style.filter = "brightness(100%)";
refMenuButton.style.cursor = "pointer";
function submitMarketRegister(event) {
	if (event.button === 0) {
		clearTimeout(checkMarketName);
		clearTimeout(checkMarketRegister);
		var numberOfCheckedBoxes = 0;
		refTickBoxes.forEach(function(item) {
			if (item.classList.contains("tickedCategoryBox")) {
				numberOfCheckedBoxes++;
			}
		});
		if (refMarketNameField.value.length > 3 &&
			refMarketNameField.value.length < 30 &&
			numberOfCheckedBoxes > 0) {
			checkMarketName = setTimeout(function() {
				const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
				xhr.open("POST", "registerMarkplace.php", true);
				xhr.setRequestHeader("Content-Type", "application/json");
				xhr.responseType = "json";
				refMarketRegisterButtonCont.innerHTML = '<div id="loadingImageCont"></div>';
				assocRequestJSON["marketName"] = refMarketNameField.value;
				xhr.onload = function() {
					if (xhr.status === 200) {
						if (xhr.response["message"] !== "Success!") {
							if (!refMarketRegisterError.classList.contains("inputErrorText")) {
								refMarketRegisterError.classList.add("inputErrorText");
							}
						}
						refMarketNameError.innerHTML = xhr.response["errormessages"]["emailError"];
						refMarketRegisterError.innerHTML = xhr.response["message"];
						refMarketRegisterButtonCont.innerHTML = '<button id="marketRegisterButton" onmouseup="submitMarketRegister(event)" onmousedown="cancelMarketRegisterTimeout(event)">Register</button>';
					} else {
						refMarketRegisterError.innerHTML = "An internal server error occurred. Please try again later.";
					}
				}
				xhr.send(JSON.stringify(assocRequestJSON));
			}, 350);
		}
	}
}
function cancelMarketRegisterTimeout(event) {
	if (event.button === 0) {
		clearTimeout(checkMarketName);
		clearTimeout(checkMarketRegister);
	}
}
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
refTickBoxes.forEach(function(eachBox, itemIndex) {
	eachBox.addEventListener("mouseup", function(triggered) {
		if (triggered.button === 0) {
			eachBox.classList.toggle("tickedCategoryBox");
			assocRequestJSON["marketCategories"][itemIndex] = !assocRequestJSON["marketCategories"][itemIndex];
		}
	});
});
refMarketNameField.addEventListener("keyup", function() {
	clearTimeout(checkMarketName);
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
refMarketNameField.addEventListener("keydown", function() {
	clearTimeout(checkMarketName);
});