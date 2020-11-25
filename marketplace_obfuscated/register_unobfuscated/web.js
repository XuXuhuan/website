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
const availableCategories = [
	"automotive",
	"babyCare",
	"books",
	"CDandVinyl",
	"clothesAndAccessories",
	"electronics",
	"gardening",
	"outdoorsAndSports",
	"groceries",
	"health",
	"household",
	"personalCare",
	"kitchenAndDining",
	"travelSupplies",
	"beauty",
	"moviesAndTV",
	"musicalInstruments",
	"officeSupplies",
	"petSupplies",
	"software",
	"tools",
	"toys",
	"games"];
var assocRequestJSON = {
	marketName : "",
	marketCategories : []
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
			assocRequestJSON["marketCategories"].length > 0) {
			checkMarketName = setTimeout(function() {
				const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
				xhr.open("POST", "registerMarketplace.php", true);
				xhr.setRequestHeader("Content-Type", "application/json");
				xhr.responseType = "json";
				refMarketRegisterButtonCont.innerHTML = '<div id="loadingImageCont"></div>';
				assocRequestJSON["marketName"] = refMarketNameField.value;
				xhr.onload = function() {
					if (xhr.status === 200) {
						if (xhr.response["message"] === "Success!") {
							if (refMarketRegisterError.classList.contains("inputErrorText")) {
								refMarketRegisterError.classList.remove("inputErrorText");
							}
						}
						refMarketNameError.innerHTML = xhr.response["marketNameError"];
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
refMenuButton.addEventListener("click", function() {
	if (refSideNav.classList.contains("openedSideNav") || refMenuButton.style.animationName === "menuAnimationOpen") {
		refSideNav.classList.remove("openedSideNav");
		refMenuButton.style.animationName = "menuAnimationClose";
	} else {
		refSideNav.classList.add("openedSideNav");
		refMenuButton.style.animationName = "menuAnimationOpen";
	}
});
refTickBoxes.forEach(function(eachBox, itemIndex) {
	eachBox.addEventListener("mouseup", function(triggered) {
		if (triggered.button === 0) {
			const findItemInSelectedCategories = assocRequestJSON["marketCategories"].indexOf(availableCategories[itemIndex]);
			if (findItemInSelectedCategories === -1) {
				assocRequestJSON["marketCategories"].push(availableCategories[itemIndex]);
			} else {
				assocRequestJSON["marketCategories"].splice(findItemInSelectedCategories, 1);
			}
			eachBox.classList.toggle("tickedCategoryBox");
			if (assocRequestJSON["marketCategories"].length === 0) {
				if (!refMarketRegisterError.classList.contains("inputErrorText")) {
					refMarketRegisterError.classList.add("inputErrorText");
				}
				refMarketRegisterError.innerHTML = "Please select at least one category.";
			} else {
				refMarketRegisterError.innerHTML = "";
			}
		}
	});
});
refMarketNameField.addEventListener("keyup", function() {
	clearTimeout(checkMarketName);
	if (refMarketNameField.value.length.trim() === 0) {
		refMarketNameError.innerHTML = "This field is required.";
	}
	else if (refMarketNameField.value.length < 3) {
		refMarketNameError.innerHTML = "Market name must contain at least 3 characters.";
	}
	else if (refMarketNameField.value.length > 30) {
		refMarketNameError.innerHTML = "Market name has to be under 30 characters.";
	}
	else if (/[^a-z0-9._\[\]\(\) ]/gi.test(refMarketNameField.value) === true) {
		refMarketNameError.innerHTML = "Your market name can only contain letters, numbers, (), [], . and _.";
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