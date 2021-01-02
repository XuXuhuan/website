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
const URLparameters = new URLSearchParams(window.location.search);
var uploadedImages = [];
var checkNotification;
var checkProductName;
refMenuButton.style.filter = "brightness(100%)";
refMenuButton.style.cursor = "pointer";
function deleteImage(index) {
	const refProductImageDisplays = document.querySelectorAll(".customProductImageCont");
	refProductImageDisplays[index].remove();
	uploadedImages.splice(index, 1);
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
refProductNameField.addEventListener("keydown", function() {
	clearTimeout(checkProductName);
});
refProductNameField.addEventListener("keyup", function() {
	clearTimeout(checkProductName);
	if (refProductNameField.value.trim().length === 0) {
		refProductNameError.innerHTML = "This field is required.";
	}
	else if (refProductNameField.value.length > 30) {
		refProductNameError.innerHTML = "Product name must be under 30 characters long.";
	} else {
		refProductNameError.innerHTML = "";
		checkProductName = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "productNameTaken.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.responseType = "text";
			xhr.onerror = function() {
				refProductNameError.innerHTML = "An error occurred.";
			}
			xhr.onload = function() {
				if (xhr.status === 200) {
					refProductNameError.innerHTML = xhr.responseText;
				} else {
					refProductNameError.innerHTML = "An error occurred.";
				}
			}
			xhr.send("id=" + encodeURIComponent(URLparameters.get("id")) + "&name=" + encodeURIComponent(refProductNameField.value));
		}, 350);
	}
});
document.querySelectorAll("#productPriceDollars, #productPriceCents").forEach(function(item) {
	item.addEventListener("input", function() {
		if (parseInt(refProductPriceDollarsField.value) + parseInt(refProductPriceCentsField.value) / 100 <= 0) {
			refProductPriceError.innerHTML = "This field is required.";
		}
		else if (parseInt(refProductPriceCentsField.value) >= 100) {
			refProductPriceError.innerHTML = "Invalid product price.";
		} else {
			refProductPriceError.innerHTML = "";
		}
	});
})
function create_createProduct() {
	if (refProductNameField.value.trim().length > 0 && parseInt(refProductPriceDollarsField.value) > 0) {
		refProductCreationButtonCont.innerHTML = '<div id="loadingImageCont"></div>';
		var placeholderUploadedImages = uploadedImages;
		var formData = new FormData();
		var cents = parseInt(refProductPriceCentsField.value) > 0 ? parseInt(refProductPriceCentsField.value) : 0;
		formData.append("id", URLparameters.get("id"));
		formData.append("name", refProductNameField.value);
		formData.append("info", refProductInfoField.value);
		formData.append("priceDollars", parseInt(refProductPriceDollarsField.value));
		formData.append("priceCents", cents);
		placeholderUploadedImages.forEach(async function(item, index) {
			let fetchImage = await fetch(item).then(response => response.blob());
			formData.append("image" + (index + 1), fetchImage);
			if (index === uploadedImages.length - 1) {
				submitXHR();
			}
		});
		function submitXHR() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "createProduct.php", true);
			xhr.responseType = "json";
			xhr.onerror = function() {
				refCreationMessage.innerHTML = "An error occurred.";
				setNotification("An error occurred.", true);
			}
			xhr.onload = function() {
				if (xhr.status === 200) {
					if (xhr.response["message"] === "Product Created!") {
						setNotification(xhr.response["message"], false);
					}
					refImageUploadError.innerHTML = xhr.response["imagesError"];
					refProductNameError.innerHTML = xhr.response["nameError"];
					refProductPriceError.innerHTML = xhr.response["priceError"];
					refCreationMessage.innerHTML = xhr.response["message"];
					refProductCreationButtonCont.innerHTML = "<button id='productCreationButton' onclick='create_createProduct()'>Create</button>";
				} else {
					refCreationMessage.innerHTML = "An error occurred.";
				}
			}
			xhr.send(formData);
		}
	}
}