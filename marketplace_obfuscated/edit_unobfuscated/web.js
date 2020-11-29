"use strict";
const refMenuButton = document.querySelector("#menuToggle");
const refSideNav = document.querySelector("#sidenav");
const refCancelOperationOverlay = document.querySelector("#cancelOperationOverlay");
const refMarketNameEditIcon = document.querySelector("#marketNameEditIcon");
const refMarketLogoTextOverlay = document.querySelector("#marketLogoTextOverlay");
const refMarketLogoText = document.querySelector("#marketLogoText"); 
const refTickBoxes = document.querySelectorAll(".marketCategoryBox");
const refNotificationCont = document.querySelector("#notificationCont");
const refNotificationText = document.querySelector("#notificationText");
const refMarketLogoImageDisplay = document.querySelector("#marketLogoImageDisplay");
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
var marketLogoImageURL = refMarketLogoImageDisplay.style.backgroundImage;
var checkNotification;
var checkMarketName;
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
refCancelOperationOverlay.addEventListener("mousedown", function() {
});
function edit_validateMarketNameFieldKeyUp(key) {
	clearTimeout(checkMarketName);
	if (key.keyCode)
	checkMarketName = setTimeout(function() {

	}, 350);
}
function edit_validateMarketNameFieldKeyDown() {
	clearTimeout(checkMarketName);
}
function edit_marketNameEditIconClick() {
	const refMarketNameDetailsRow = document.querySelector("#marketNameRow");
	const refMarketNameDetailsCont = document.querySelector("#marketNameDetailsCont");
	const refCancelOperationOverlay = document.querySelector("#cancelOperationOverlay");
	const createErrorMessage = document.createElement("p");
	const createInputField = document.createElement("input");
	createErrorMessage.id = "newMarketNameError";
	createErrorMessage.classList.add("inputErrorText");
	createErrorMessage.innerHTML = "Enter to confirm, Esc/click outside to cancel";
	createInputField.id = "newMarketNameField";
	createInputField.classList.add("newDetailField");
	refMarketNameDetailsCont.innerHTML = "";
	refMarketNameDetailsCont.style.flexDirection = "column";
	refMarketNameDetailsRow.style.height = refMarketNameDetailsRow.getBoundingClientRect()["height"] + 30 + "px";
	refMarketNameDetailsCont.appendChild(createInputField);
	refMarketNameDetailsCont.appendChild(createErrorMessage);
	refCancelOperationOverlay.classList.add("showOverlay");
}
function edit_marketLogoTextOverlayMouseEnter() {
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
	console.log(0);
	if (acceptedImageFileTypes.test(document.querySelector("#marketLogoUpload").value)) {
		const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
		xhr.open("POST", "changeMarketLogo.php", true);
		xhr.responseType = "json";
		xhr.onerror = function() {
			console.log(1);
			refNotificationCont.style.top = 0;
			refNotificationCont.style.backgroundColor = "#E60505";
			refNotificationText.innerHTML = "An error occurred.";
			clearTimeout(checkNotification);
			checkNotification = setTimeout(function() {
				refNotificationCont.style.top = "-10vh";
			},1000);
		}
		xhr.onload = function() {
			if (xhr.status === 200) {
				refMarketLogoImageDisplay.style.backgroundImage = 'url("' + xhr.response["newMarketLogoURL"] + '")';
				marketLogoImageURL = refMarketLogoImageDisplay.style.backgroundImage;
			} else {
				console.log(0);
				refNotificationCont.style.top = 0;
				refNotificationCont.style.backgroundColor = "#E60505";
				refNotificationText.innerHTML = "An error occurred.";
				clearTimeout(checkNotification);
				checkNotification = setTimeout(function() {
					refNotificationCont.style.top = "-10vh";
				},1000);
			}
		}
		console.log(xhr);
		xhr.send(document.querySelector("#marketLogoUpload").files[0]);
	} else {
		refNotificationCont.style.top = 0;
		refNotificationCont.style.backgroundColor = "#E60505";
		refNotificationText.innerHTML = "Only JPEG or PNG files are accepted.";
		clearTimeout(checkNotification);
		checkNotification = setTimeout(function() {
			refNotificationCont.style.top = "-10vh";
		},1000);
	}
}
document.addEventListener("mousedown", function(event) {
	if (event.detail > 1) {
	  event.preventDefault();
	}
}, false);