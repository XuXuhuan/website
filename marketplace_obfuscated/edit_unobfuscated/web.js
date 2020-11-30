"use strict";
const refMenuButton = document.querySelector("#menuToggle");
const refSideNav = document.querySelector("#sidenav");
const refCancelOperationOverlay = document.querySelector("#cancelOperationOverlay");
const refNotificationCont = document.querySelector("#notificationCont");
const refNotificationText = document.querySelector("#notificationText");
const acceptedImageFileTypes = /(\.jpg|\.png|\.jpeg)$/i;
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
var marketLogoImageURL = document.querySelector("#marketLogoImageDisplay").style.backgroundImage;
var marketName = document.querySelector("#marketNameValue").innerHTML;
var checkNotification;
var checkMarketName;
var checkMarketBio;
var isMarketNameChangeInProgress = false;
var marketNameFieldValue = "";
var marketNameError = "";
function setNotification(message, isError) {
	refNotificationCont.style.top = 0;
	refNotificationCont.style.backgroundColor = isError === true ? "#E60505" : "#40AF00";
	refNotificationText.innerHTML = message;
	clearTimeout(checkNotification);
	checkNotification = setTimeout(function() {
		refNotificationCont.style.top = "-10vh";
	}, 1000);
}
refMenuButton.style.filter = "brightness(100%)";
refMenuButton.style.cursor = "pointer";
refMenuButton.addEventListener("click", function() {
	if (refSideNav.classList.contains("openedSideNav") || refMenuButton.style.animationName === "menuAnimationOpen") {
		refSideNav.classList.remove("openedSideNav");
		refMenuButton.style.animationName = "menuAnimationClose";
	} else {
		refSideNav.classList.add("openedSideNav");
		refMenuButton.style.animationName = "menuAnimationOpen";
	}
});
document.querySelectorAll(".marketCategoryBox").forEach(function(eachBox, itemIndex) {
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
				setNotification("Please select at least one category.", true);
			}
		}
	});
});
refCancelOperationOverlay.addEventListener("mousedown", edit_cancelMarketNameChange);
function edit_cancelMarketNameChange() {
	const refMarketNameDetailsRow = document.querySelector("#marketNameRow");
	const refMarketNameDetailsCont = document.querySelector("#marketNameDetailsCont");
	const refNewMarketNameField = document.querySelector("#newMarketNameField");
	const refNewMarketNameError = document.querySelector("#newMarketNameError");
	marketNameError = "";
	marketNameFieldValue = "";
	refNewMarketNameField.remove();
	refNewMarketNameError.remove();
	refMarketNameDetailsCont.style = 0;
	refMarketNameDetailsRow.style = 0;
	refCancelOperationOverlay.classList.remove("showOverlay");
	refMarketNameDetailsCont.innerHTML = `
	<p id='marketNameValue' class='rowInfo'>${marketName}</p>
	<div id='marketNameEditIcon' class='editIcon' onclick='edit_marketNameEditIconClick()'></div>`;
	isMarketNameChangeInProgress = false;
}
function edit_validateMarketNameFieldKeyUp(key) {
	const refNewMarketNameField = document.querySelector("#newMarketNameField");
	const refNewMarketNameError = document.querySelector("#newMarketNameError");
	clearTimeout(checkMarketName);
	if (key.keyCode === 27) {
		edit_cancelMarketNameChange();
	}
	else if (key.keyCode === 13) {
		if (refNewMarketNameField.value.trim().length > 0) {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "updateMarketDetails.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.responseType = "json";
			xhr.onerror = function() {
				setNotification("An error occurred.", true);
			}
			xhr.onload = function() {
				if (xhr.status === 200) {
					setNotification("Market Name Changed!", false);
					marketName = xhr.response["newMarketName"];
					document.querySelector("#marketNameValue").innerHTML = xhr.response["newMarketName"];
					refNewMarketNameError.innerHTML = "";
					marketNameError = "";
				} else {
					setNotification("An error occurred.", true);
				}
			}
			xhr.send("type=1&value=" + encodeURIComponent(refNewMarketNameField.value));
		} else {
			refNewMarketNameError.innerHTML = "This field is required.";
			marketNameError = "This field is required.";
		}
	} else {
		if (refNewMarketNameField.value.trim().length > 0) {
			checkMarketName = setTimeout(function() {
				
			}, 350);
		} else {
			refNewMarketNameError.innerHTML = "This field is required.";
			marketNameError = "This field is required.";
		}
	}
}
function edit_validateMarketNameFieldKeyDown() {
	clearTimeout(checkMarketName);
}
function edit_marketNameEditIconClick() {
	const refMarketNameDetailsRow = document.querySelector("#marketNameRow");
	const refMarketNameDetailsCont = document.querySelector("#marketNameDetailsCont");
	const createErrorMessage = document.createElement("p");
	const createInputField = document.createElement("input");
	createErrorMessage.id = "newMarketNameError";
	createErrorMessage.classList.add("inputErrorText");
	createErrorMessage.innerHTML = "Enter to confirm, Esc/click outside to cancel";
	createInputField.id = "newMarketNameField";
	createInputField.classList.add("newDetailField");
	createInputField.classList.add("inputMethod");
	createInputField.addEventListener("keyup", edit_validateMarketNameFieldKeyUp);
	createInputField.addEventListener("keydown", edit_validateMarketNameFieldKeyDown);
	refMarketNameDetailsCont.innerHTML = "";
	refMarketNameDetailsCont.style.flexDirection = "column";
	refMarketNameDetailsRow.style.height = refMarketNameDetailsRow.getBoundingClientRect()["height"] + 30 + "px";
	refMarketNameDetailsCont.appendChild(createInputField);
	refMarketNameDetailsCont.appendChild(createErrorMessage);
	refCancelOperationOverlay.classList.add("showOverlay");
	isMarketNameChangeInProgress = true;
}
function edit_marketLogoTextOverlayMouseEnter() {
	const refMarketLogoText = document.querySelector("#marketLogoText");
	const refMarketLogoTextOverlay = document.querySelector("#marketLogoTextOverlay");
	if (marketLogoImageURL !== 'url("../../Assets/global/imageNotFound.png")') {
		refMarketLogoText.innerHTML = "REMOVE IMAGE";
		refMarketLogoTextOverlay.style.opacity = 1;
	} else {
		if (!document.querySelector("#marketLogoUpload")) {
			const createFileInputElement = document.createElement("input");
			createFileInputElement.id = "marketLogoUpload";
			createFileInputElement.name = "marketLogoUpload";
			createFileInputElement.title = "Choose a file to upload";
			createFileInputElement.type = "file";
			createFileInputElement.accept = ".png, .jpg, .jpeg";
			refMarketLogoTextOverlay.appendChild(createFileInputElement);
			createFileInputElement.addEventListener("input", edit_uploadImageFile);
		}
		refMarketLogoText.innerHTML = "UPLOAD IMAGE";
		refMarketLogoTextOverlay.style.opacity = 1;
	}
}
function edit_marketLogoTextOverlayMouseLeave(event) {
	const refMarketLogoTextOverlay = document.querySelector("#marketLogoTextOverlay");
	var e = event.toElement || event.relatedTarget;
	if (e && e.parentNode != refMarketLogoTextOverlay) {
		if (document.querySelector("#marketLogoUpload")) {
			const refMarketLogoUpload = document.querySelector("#marketLogoUpload");
			refMarketLogoUpload.remove();
		}
		refMarketLogoTextOverlay.style.opacity = 0;
	}
}
function edit_uploadImageFile() {
	const refMarketLogoImageDisplay = document.querySelector("#marketLogoImageDisplay");
	if (acceptedImageFileTypes.test(document.querySelector("#marketLogoUpload").value)) {
		const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
		xhr.open("POST", "changeMarketLogo.php", true);
		xhr.responseType = "json";
		xhr.onerror = function() {
			setNotification("An error occurred.", true);
		}
		xhr.onload = function() {
			if (xhr.status === 200) {
				refMarketLogoImageDisplay.style.backgroundImage = 'url("' + xhr.response["newMarketLogoURL"] + '")';
				marketLogoImageURL = refMarketLogoImageDisplay.style.backgroundImage;
			} else {
				setNotification("An error occurred.", true);
			}
		}
		xhr.send(document.querySelector("#marketLogoUpload").files[0]);
	} else {
		setNotification("Only JPEG or PNG files are accepted.", true);
	}
}
function edit_updateMarketBio() {
	const refMarketBioField = document.querySelector("#marketBioField");
	clearTimeout(checkMarketBio);
	checkMarketBio = setTimeout(function() {
		const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
		xhr.open("POST", "updateMarketDetails.php", true);
		xhr.setRequestHeader("Content-type", "application/json");
		xhr.responseType = "json";
		xhr.onerror = function() {
			setNotification("An error occurred.", true);
		}
		xhr.onload = function() {
			if (xhr.status === 200) {
				setNotification(xhr.response["message"], xhr.response["isError"]);
			} else {
				setNotification("An error occurred.", true);
			}
		}
		xhr.send(
			JSON.stringify(
				{
					"type" : 2,
					"marketBio" : refMarketBioField.value
				}
			)
		);
	}, 350);
}
document.addEventListener("mousedown", function(event) {
	if (event.detail > 1) {
	  event.preventDefault();
	}
}, false);