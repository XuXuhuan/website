"use strict";
const refMenuButton = document.querySelector("#menuToggle");
const refSideNav = document.querySelector("#sidenav");
const refNotificationCont = document.querySelector("#notificationCont");
const refNotificationText = document.querySelector("#notificationText");
const refImageUploadField = document.querySelector("#uploadProductImageField");
const refImageUploadError = document.querySelector("#productImagesError");
const refProductImagesList = document.querySelector("#productImagesList");
const refProductNameField = document.querySelector("#productNameField");
const refProductNameError = document.querySelector("#productNameError");
const refProductInfoField = document.querySelector("#productInfoField");
const refProductPriceDollarsField = document.querySelector("#productPriceDollars");
const refProductPriceCentsField = document.querySelector("#productPriceCents");
const refProductPriceError = document.querySelector("#productPriceError");
const refProductCreationButtonCont = document.querySelector("#productCreationButtonCont");
const refCreationMessage = document.querySelector("#creationMessage");
const adaptedURL = window.URL || window.webkitURL;
const acceptedImageFileTypes = /(\.jpg|\.png|\.jpeg)$/i;
var uploadedImages = [];
var uploadedImageFiles = [];
var checkNotification;
refMenuButton.style.filter = "brightness(100%)";
refMenuButton.style.cursor = "pointer";
function deleteImage(index) {
	const refProductImageDisplays = document.querySelectorAll(".customProductImageCont");
	refProductImageDisplays[index].remove();
	uploadedImages.splice(index, 1);
	uploadedImageFiles.splice(index, 1);
}
function setNotification(message, isError) {
	refNotificationCont.style.top = 0;
	refNotificationCont.style.backgroundColor = isError === true ? "#E60505" : "#40AF00";
	refNotificationText.innerHTML = message;
	clearTimeout(checkNotification);
	checkNotification = setTimeout(function() {
		refNotificationCont.style.top = "-10vh";
	}, 1000);
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
refImageUploadField.addEventListener("input", function() {
	if (uploadedImages.length + refImageUploadField.files.length <= 10) {
		Array.prototype.forEach.call(refImageUploadField.files, function(item) {
			if (acceptedImageFileTypes.test(item.name)) {
				if (item.size <= 4000000) {
					var pseudoImage = new Image();
					var newObjectURL = adaptedURL.createObjectURL(item);
					pseudoImage.onload = function() {
						if (pseudoImage.width >= 150 && pseudoImage.height >= 150) {
							uploadedImages.push(newObjectURL);
							uploadedImageFiles.push(item);
							var newImageCont = document.createElement("div");
							newImageCont.classList.add("productImageDisplay");
							newImageCont.classList.add("customProductImageCont");
							newImageCont.style.backgroundImage = "url('" + newObjectURL + "')";
							refProductImagesList.appendChild(newImageCont);
							newImageCont.addEventListener("click", function() {
								deleteImage(uploadedImages.indexOf(newObjectURL));
							});
						} else {
							setNotification("Images must have dimensions of at least 150px by 150px.", true);
							adaptedURL.revokeObjectURL(newObjectURL);
						}
					}
					pseudoImage.src = newObjectURL;
				} else {
					setNotification("File size too large. Maximum file size is 4MB.", true);
				}
			} else {
				setNotification("Only JPEG or PNG files are accepted.", true);
			}
		});
	} else {
		setNotification("Please upload a maximum of 10 images.");
	}
});
refProductNameField.addEventListener("input", function() {
	if (refProductNameField.value.trim().length === 0) {
		refProductNameError.innerHTML = "This field is required.";
	}
	else if (refProductNameField.value.length > 30) {
		refProductNameError.innerHTML = "Product name must be under 30 characters long.";
	} else {
		refProductNameError.innerHTML = "";
	}
});
refProductPriceDollarsField.addEventListener("input", function() {
	if (parseInt(refProductPriceDollarsField.value) > 0) {
		refProductPriceError.innerHTML = "";
	} else {
		refProductPriceError.innerHTML = "This field is required.";
	}
});
function create_createProduct() {
	if (refProductNameField.value.trim() > 0 && parseInt(refProductPriceDollarsField.value) > 0) {
		const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
		refProductCreationButtonCont.innerHTML = '<div id="loadingImageCont"></div>';
		xhr.open("POST", "createProduct.php", true);
		xhr.responseType = "json";
		var formData = new FormData();
		var cents = parseInt(refProductPriceCentsField.value) > 0 ? parseInt(refProductPriceCentsField.value) : 0;
		formData.append("images", uploadedImageFiles);
		formData.append("name", refProductNameField.value);
		formData.append("info", refProductInfoField.value);
		formData.append("priceDollars", parseInt(refProductPriceDollarsField.value));
		formData.append("priceCents", cents);
		xhr.onerror = function() {
			refCreationMessage.innerHTML = "An error occurred.";
			setNotification("An error occurred.", true);
		}
		xhr.onload = function() {
			if (xhr.status === 200) {
				if (xhr.response["message"] === "Product Created!") {
					setNotification(xhr.response["message"], false);
				}
				refImageUploadError.innerHTML = xhr.response["productImagesError"];
				refProductNameError.innerHTML = xhr.response["productNameError"];
				refProductPriceError.innerHTML = xhr.response["productPriceError"];
				refCreationMessage.innerHTML = xhr.response["message"];
				refProductCreationButtonCont.innerHTML = "<button id='productCreationButton' onclick='create_createProduct()'>Create</button>";
			} else {
				refCreationMessage.innerHTML = "An error occurred.";
			}
		}
		xhr.send(formData);
	}
}