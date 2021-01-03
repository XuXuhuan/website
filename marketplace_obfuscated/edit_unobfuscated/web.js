"use strict";
const refMenuButton = document.querySelector("#menuToggle");
const refSideNav = document.querySelector("#sidenav");
const refCancelOperationOverlay = document.querySelector("#cancelOperationOverlay");
const refMarketInfoCont = document.querySelector("#marketInfoCont");
const refNotificationCont = document.querySelector("#notificationCont");
const refNotificationText = document.querySelector("#notificationText");
const refMarketDetailsButton = document.querySelector("#marketButton");
const refProductsButton = document.querySelector("#productsButton");
const refSelectedTabLine = document.querySelector("#selectedTabLine");
const refConfirmButton = document.querySelector("#confirmButton");
const refCancelButton = document.querySelector("#cancelButton");
const URLparameters = new URLSearchParams(window.location.search);
const adaptedURL = window.URL || window.webkitURL;
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
var marketLogoUploaded;
var marketName;
if (document.querySelector("#marketLogoImageDisplay")) {
	marketLogoUploaded = document.querySelector("#marketLogoImageDisplay").style.backgroundImage !== 'url("../../Assets/global/imageNotFound.png")';
	marketName = document.querySelector("#marketNameValue").innerHTML;
	edit_marketLogoTextOverlaySet();
}
var checkNotification;
var checkMarketName;
var checkDeleteMarket;
var isMarketNameChangeInProgress = false;
var marketDeletionEmailSent = false;
var marketProductsFieldValue = "";
var marketNameFieldValue = "";
var marketNameError = "";
var marketBioFieldValue = "";
var marketDeletionButtonText = "Delete Market";
var marketDeletionError = "";
var currentProductsListPage = 1;
function setNotification(message, isError) {
	refNotificationCont.style.top = 0;
	refNotificationCont.style.backgroundColor = isError === true ? "#E60505" : "#40AF00";
	refNotificationText.innerHTML = message;
	clearTimeout(checkNotification);
	checkNotification = setTimeout(function() {
		refNotificationCont.style.top = "-10vh";
	}, 1000);
}
function escapeText(text) {
	return text.replace(/&/g, "&amp;")
	.replace(/</g, "&lt;")
	.replace(/>/g, "&gt;")
	.replace(/"/g, "&quot;")
	.replace(/'/g, "&#039;");
}
function fetchNewPage(newPage, query) {
	const refExistingProductsCont = document.querySelector("#existingProductsCont");
	const refFetchProductsError = document.querySelector("#fetchProductsError");
	const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
	var URLdata;
	xhr.open("POST", "../products/fetchProductsDetails.php", true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.responseType = "json";
	xhr.onload = function() {
		if (xhr.status === 200) {
			if (xhr.response["errormessage"].length === 0 && xhr.response["productDetails"].length > 0) {
				currentProductsListPage = newPage;
				refExistingProductsCont.innerHTML = "";
				xhr.response["productDetails"].forEach(function(item) {
					refExistingProductsCont.innerHTML += `
					<div class='productContentsRow infoRow'>
						<img src='${item["productImageURL"]}' alt='Product Image' class='productImage'>
						<div class='productNameAndInfoCont infoColumnRow'>
							<a href='https://www.streetor.sg/marketplace/products/?prodid=${item["productID"]}' class='productName'>${item["productName"]}</a>
							<p class='pricingInfoLabel'>S$${item["productPricing"]}</p>
							<p class='productInfoText'>${item["productInfo"]}</p>
							<div class='productRatingRow'>
								<p class='ratingLabel'>${item["productRating"]}</p>
								<svg height='18' width='18' class='productRatingStar'>
									<defs>
										<linearGradient id='starGradient'>
											<stop offset='100%' stop-color='#e1c900'></stop>
										</linearGradient>
									</defs>
									<polygon points='9,0 4,18 18,7 0,7 15,18' style='fill: url(#starGradient);'></polygon>
								</svg>
							</div>
							<div class='productMenuButtonCont'>
								<svg class='productMenuButton' width='5' height='20'>
									<circle cx='2.5' cy='2.5' r='2.5' class='productMenuButtonDot'/>
									<circle cx='2.5' cy='10' r='2.5' class='productMenuButtonDot'/>
									<circle cx='2.5' cy='17.5' r='2.5' class='productMenuButtonDot'/>
								</svg>
								<span class='productMenuPopUp hideProductMenuPopUp'>
									<a href='https://www.streetor.sg/marketplace/products/edit/?id=${item["productID"]}' class='notSelectable productMenuPopUpLink'>
										Edit
										<div class='productMenuPopUpTail'></div>
									</a>
									<p class='productMenuDelete' data-productid='${item["productID"]}'>Delete</p>
								</span>
							</div>
						</div>
					</div>`;
				});
				document.querySelectorAll(".productMenuButtonCont").forEach(function(item, index) {
					const refPopUp = document.querySelectorAll(".productMenuPopUp");
					item.addEventListener("click", function() {
						refPopUp[index].classList.toggle("hideProductMenuPopUp");
					});
				});
				document.querySelectorAll(".productMenuDelete").forEach(function(item, index) {
					item.addEventListener("click", function() {
						const refConfirmationOverlay = document.querySelector("#confirmationOverlay");
						const refConfirmationText = document.querySelector("#confirmationText");
						const refProductNames = document.querySelectorAll(".productName");
						refConfirmationText.innerHTML = "Are you sure you want to delete " + refProductNames[index + 1].innerHTML + "? This process cannot be undone!";
						refConfirmationOverlay.setAttribute("data-destid", item.getAttribute("data-productid"));
						if (!refConfirmationOverlay.classList.contains("displaying")) {
							refConfirmationOverlay.classList.add("displaying");
						}
					});
				});
				if (xhr.response["currentResults"] > 0 && xhr.response["maxResults"] > 0) {
					document.querySelector("#resultCount").innerHTML = xhr.response["currentResults"] + " of " + xhr.response["maxResults"] + " results";
					document.querySelector("#currentPageCount").value = Math.ceil(xhr.response["currentResults"] / 10);
					document.querySelector("#maxPagesCount").innerHTML = Math.ceil(xhr.response["maxResults"] / 10);
					if (Math.ceil(xhr.response["maxResults"] / 10) === newPage) {
						if (document.querySelector("#nextPageButton")) {
							const refNextPageButton = document.querySelector("#nextPageButton");
							refNextPageButton.remove();
						}
					}
					if (newPage === 1) {
						if (document.querySelector("#prevPageButton")) {
							const refPrevPageButton = document.querySelector("#prevPageButton");
							refPrevPageButton.remove();
						}
					}
					if (newPage > 1) {
						const refChangePageCont = document.querySelector("#changePageCont");
						const refCreatePrevPageButton = document.createElement("button");
						const refCreatePrevPageImage = document.createElement("div");
						refCreatePrevPageButton.id = "prevPageButton";
						refCreatePrevPageButton.onclick = function(triggered) {leftArrowProductFetch(triggered)};
						refCreatePrevPageImage.id = "leftArrowCont";
						refCreatePrevPageImage.classList.add("changePageArrowCont");
						refCreatePrevPageButton.appendChild(refCreatePrevPageImage);
						refChangePageCont.appendChild(refCreatePrevPageButton);
					}
					if (Math.ceil(xhr.response["maxResults"] / 10) > newPage) {
						const refChangePageCont = document.querySelector("#changePageCont");
						const refCreateNextPageButton = document.createElement("button");
						const refCreateNextPageImage = document.createElement("div");
						refCreateNextPageButton.id = "nextPageButton";
						refCreateNextPageButton.onclick = function(triggered) {rightArrowProductFetch(triggered)};
						refCreateNextPageImage.id = "rightArrowCont";
						refCreateNextPageImage.classList.add("changePageArrowCont");
						refCreateNextPageButton.appendChild(refCreateNextPageImage);
						refChangePageCont.appendChild(refCreateNextPageButton);
					}
				}
			} else {
				refFetchProductsError.innerHTML = xhr.response["errormessage"];
			}
		} else {
			refFetchProductsError.innerHTML = "An error occurred.";
		}
	}
	if (query.trim().length > 0) {
		URLdata = "hasQuery=1&page=" + encodeURIComponent(newPage) + "&marketid=" + encodeURIComponent(URLparameters.get("id")) + "&query=" + encodeURIComponent(query);
	} else {
		URLdata = "hasQuery=0&page=" + encodeURIComponent(newPage) + "&marketid=" + encodeURIComponent(URLparameters.get("id"));
	}
	xhr.send(URLdata);
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
document.querySelectorAll(".marketCategoryBox").forEach(function(eachBox) {
	eachBox.addEventListener("mouseup", function(triggered) {
		if (triggered.button === 0) {
			eachBox.classList.toggle("tickedCategoryBox");
		}
	});
});
refCancelOperationOverlay.addEventListener("mousedown", edit_cancelMarketNameChange);
function edit_cancelMarketNameChange() {
	const refMarketNameDetailsRow = document.querySelector("#marketNameRow");
	const refMarketNameDetailsCont = document.querySelector("#marketNameDetailsCont");
	const refNewMarketNameError = document.querySelector("#newMarketNameError");
	const refMarketNameValue = document.querySelector("#marketNameValue");
	marketNameError = "";
	marketNameFieldValue = "";
	refNewMarketNameError.remove();
	refMarketNameValue.removeAttribute("onkeyup");
	refMarketNameValue.removeAttribute("onkeydown");
	refMarketNameDetailsCont.style = 0;
	refMarketNameDetailsRow.style = 0;
	refCancelOperationOverlay.classList.remove("showOverlay");
	refMarketNameDetailsCont.innerHTML = `
	<p id='marketNameValue' class='rowInfo'>${marketName}</p>
	<div id='marketNameEditIcon' class='editIcon' onclick='edit_marketNameEditIconClick()'></div>`;
	isMarketNameChangeInProgress = false;
}
function edit_validateMarketNameFieldKeyUp(key) {
	const refNewMarketNameField = document.querySelector("#marketNameValue");
	const refNewMarketNameError = document.querySelector("#newMarketNameError");
	clearTimeout(checkMarketName);
	if (key.keyCode === 27) {
		edit_cancelMarketNameChange();
	}
	else if (key.keyCode === 13) {
		if (refNewMarketNameField.innerHTML.trim().length > 0) {
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
			xhr.send("type=1&value=" + encodeURIComponent(refNewMarketNameField.innerHTML) + "&id=" + encodeURIComponent(URLparameters.get("id")));
		} else {
			refNewMarketNameError.innerHTML = "This field is required.";
			marketNameError = "This field is required.";
		}
	} else {
		if (refNewMarketNameField.innerHTML.trim().length > 0) {
			refNewMarketNameError.innerHTML = "";
			marketNameError = "";
			checkMarketName = setTimeout(function() {
				const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
				xhr.open("POST", "../register/marketNameTaken.php", true);
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr.onerror = function() {
					setNotification("An error occurred.", true);
				}
				xhr.onload = function() {
					if (xhr.status === 200) {
						refNewMarketNameError.innerHTML = xhr.responseText;
						marketNameError = xhr.responseText;
					} else {
						setNotification("An error occurred.", true);
					}
				}
				xhr.send("marketname=" + encodeURIComponent(refNewMarketNameField.innerHTML));
			}, 350);
		} else {
			refNewMarketNameError.innerHTML = "This field is required.";
			marketNameError = "This field is required.";
		}
	}
}
function edit_changeMarketCategory() {
	var marketCategories = [];
	document.querySelectorAll(".marketCategoryBox").forEach(function(item, index) {
		if (item.classList.contains("tickedCategoryBox")) {
			marketCategories.push(availableCategories[index]);
		}
	});
	if (marketCategories.length > 0) {
		const requestJSON = {
			"type" : 3,
			"categories" : marketCategories,
			"id" : URLparameters.get("id")
		}
		const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
		xhr.open("POST", "updateMarketDetails.php", true);
		xhr.setRequestHeader("Content-Type", "application/json");
		xhr.responseType = "json";
		document.querySelector("#changeCategoryButtonCont").innerHTML = '<div id="loadingImageCont"></div>';
		xhr.onerror = function() {
			setNotification("An error occurred.", true);
		}
		xhr.onload = function() {
			if (xhr.status === 200) {
				if (xhr.response["message"] === "Categories updated.") {
					setNotification(xhr.response["message"], false);
				}
				document.querySelector("#changeCategoryButtonCont").innerHTML = "<button id='changeCategoryButton' class='inputMethod' onclick='edit_updateMarketBio()'>Make Changes</button>";
			} else {
				setNotification("An error occurred.", true);
			}
		}
		xhr.send(JSON.stringify(requestJSON));
	}
}
function edit_validateMarketNameFieldKeyDown() {
	clearTimeout(checkMarketName);
}
function edit_marketNameEditIconClick() {
	const refMarketNameDetailsCont = document.querySelector("#marketNameDetailsCont");
	const refMarketNameRow = document.querySelector("#marketNameRow");
	refMarketNameDetailsCont.innerHTML = `
	<p id="marketNameValue" class="rowInfo inputMethod" contenteditable="true" spellcheck="false">${marketName}</p>
	<p id="newMarketNameError" class="inputErrorText inputMethod">Enter to confirm, Esc/click outside to cancel</p>`;
	const refMarketNameValue = document.querySelector("#marketNameValue");
	refMarketNameDetailsCont.style.flexDirection = "column";
	refCancelOperationOverlay.classList.add("showOverlay");
	refMarketNameRow.style.height = document.querySelector("#marketNameRow").getBoundingClientRect()["height"] + 26.18 + "px";
	refMarketNameValue.onkeyup = edit_validateMarketNameFieldKeyUp;
	refMarketNameValue.onkeydown = edit_validateMarketNameFieldKeyDown;
	refMarketNameValue.focus();
	isMarketNameChangeInProgress = true;
}
function marketLogoTextOverlayClicked() {
	const refMarketLogoImageDisplay = document.querySelector("#marketLogoImageDisplay");
	var dataForm = new FormData();
	dataForm.append("type", 1);
	dataForm.append("id", URLparameters.get("id"));
	const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
	xhr.open("POST", "changeMarketLogo.php", true);
	xhr.responseType = "json";
	xhr.onerror = function() {
		setNotification("An error occurred.", true);
	}
	xhr.onload = function() {
		if (xhr.status === 200) {
			if (xhr.response["errormessage"].length === 0) {
				refMarketLogoImageDisplay.style.backgroundImage = 'url("../../Assets/global/imageNotFound.png")';
				marketLogoUploaded = false;
				edit_marketLogoTextOverlaySet();
			} else {
				setNotification(xhr.response["errormessage"], 1);
			}
		} else {
			setNotification("An error occurred.", true);
		}
	}
	xhr.send(dataForm);
}
function edit_marketLogoTextOverlaySet() {
	const refMarketLogoText = document.querySelector("#marketLogoText");
	const refMarketLogoTextOverlay = document.querySelector("#marketLogoTextOverlay");
	if (marketLogoUploaded === true) {
		if (document.querySelector("#marketLogoUpload")) {
			const refFileInputElement = document.querySelector("#marketLogoUpload");
			refFileInputElement.remove();
		}
		refMarketLogoText.innerHTML = "REMOVE IMAGE";
		refMarketLogoTextOverlay.addEventListener("click", marketLogoTextOverlayClicked);
	} else {
		refMarketLogoTextOverlay.removeEventListener("click", marketLogoTextOverlayClicked);
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
	}
}
function edit_marketLogoTextOverlayMouseEnter() {
	const refMarketLogoTextOverlay = document.querySelector("#marketLogoTextOverlay");
	refMarketLogoTextOverlay.style.opacity = 1;
}
function edit_marketLogoTextOverlayMouseLeave(event) {
	const refMarketLogoTextOverlay = document.querySelector("#marketLogoTextOverlay");
	var e = event.toElement || event.relatedTarget;
	if (!e || e.parentNode != refMarketLogoTextOverlay) {
		refMarketLogoTextOverlay.style.opacity = 0;
	}
}
function edit_uploadImageFile() {
	const refMarketLogoImageDisplay = document.querySelector("#marketLogoImageDisplay");
	if (acceptedImageFileTypes.test(document.querySelector("#marketLogoUpload").value)) {
		if (document.querySelector("#marketLogoUpload").files[0]["size"] <= 4000000) {
			var pseudoImage = new Image();
			var newObjectURL = adaptedURL.createObjectURL(document.querySelector("#marketLogoUpload").files[0]);
			pseudoImage.onload = function() {
				if (pseudoImage.width >= 150 && pseudoImage.height >= 150) {
					var dataForm = new FormData();
					dataForm.append("type", 2);
					dataForm.append("id", URLparameters.get("id"));
					dataForm.append("image", document.querySelector("#marketLogoUpload").files[0]);
					const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
					xhr.open("POST", "changeMarketLogo.php", true);
					xhr.responseType = "json";
					xhr.onerror = function() {
						setNotification("An error occurred.", true);
					}
					xhr.onload = function() {
						if (xhr.status === 200) {
							if (xhr.response["errormessage"].length === 0) {
								refMarketLogoImageDisplay.style.backgroundImage = "";
								refMarketLogoImageDisplay.style.backgroundImage = 'url("' + xhr.response["newMarketLogoURL"] + '")';
								marketLogoUploaded = true;
								edit_marketLogoTextOverlaySet();
							} else {
								setNotification(xhr.response["errormessage"], 1);
							}
						} else {
							setNotification("An error occurred.", true);
						}
					}
					xhr.send(dataForm);
				} else {
					setNotification("Image must have dimensions of at least 150px by 150px.", true);
				}
				adaptedURL.revokeObjectURL(newObjectURL);
			}
			pseudoImage.src = newObjectURL;
		} else {
			setNotification("File size too large. Maximum file size is 4MB.", true);
		}
	} else {
		setNotification("Only JPEG or PNG files are accepted.", true);
	}
}
function edit_updateMarketBio() {
	const refMarketBioField = document.querySelector("#marketBioField");
	const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
	xhr.open("POST", "updateMarketDetails.php", true);
	xhr.setRequestHeader("Content-type", "application/json");
	xhr.responseType = "json";
	document.querySelector("#updateBioButtonCont").innerHTML = '<div id="loadingImageCont"></div>';
	xhr.onerror = function() {
		setNotification("An error occurred.", true);
	}
	xhr.onload = function() {
		if (xhr.status === 200) {
			setNotification(xhr.response["message"], xhr.response["isError"]);
			document.querySelector("#updateBioButtonCont").innerHTML = "<button id='changeCategoryButton' class='inputMethod' onclick='edit_updateMarketBio()'>Make Changes</button>";
		} else {
			setNotification("An error occurred.", true);
		}
	}
	xhr.send(
		JSON.stringify(
			{
				"type" : 2,
				"id" : URLparameters.get("id"),
				"value" : refMarketBioField.value
			}
		)
	);
}
function edit_deleteMarket() {
	if (marketDeletionEmailSent === false) {
		clearTimeout(checkDeleteMarket);
		checkDeleteMarket = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			marketDeletionEmailSent = true;
			xhr.open("POST", "updateMarketDetails.php", true);
			xhr.responseType = "json";
			xhr.onload = function() {
				if (xhr.status === 200) {
					marketDeletionError = xhr.response["message"];
					var leftCooldown = xhr.response["leftoverCooldown"];
					if (document.querySelector("#deleteMarketError")) {
						const refDeleteMarketError = document.querySelector("#deleteMarketError");
						refDeleteMarketError.innerHTML = marketDeletionError;
					}
					if (leftCooldown <= 1) {
						marketDeletionButtonText = "Re-send Email";
						if (document.querySelector("#deleteMarketButton")) {
							const refDeleteMarketButton = document.querySelector("#deleteMarketButton");
							refDeleteMarketButton.innerHTML = marketDeletionButtonText;
						}
					} else {
						marketDeletionButtonText = "Re-send Email (" + leftCooldown + ")";
						if (document.querySelector("#deleteMarketButton")) {
							const refDeleteMarketButton = document.querySelector("#deleteMarketButton");
							refDeleteMarketButton.innerHTML = marketDeletionButtonText;
						}
						leftCooldown--;
						for (var i = 1; i <= xhr.response["leftoverCooldown"]; i++) {
							setTimeout(function() {
								if (leftCooldown === 0) {
									marketDeletionButtonText = "Re-send Email";
									marketDeletionEmailSent = false;
								} else {
									marketDeletionButtonText = "Re-send Email (" + leftCooldown + ")";
									leftCooldown--;
								}
								if (leftCooldown === xhr.response["leftoverCooldown"] - 3) {
									marketDeletionError = "";
								}
								if (document.querySelector("#deleteMarketButton")) {
									const refDeleteMarketButton = document.querySelector("#deleteMarketButton");
									refDeleteMarketButton.innerHTML = marketDeletionButtonText;
								}
								if (document.querySelector("#deleteMarketError")) {
									const refDeleteMarketError = document.querySelector("#deleteMarketError");
									refDeleteMarketError.innerHTML = marketDeletionError;
								}
							}, 1000 * i);
						}
					}
				} else {
					const refDeleteMarketError = document.querySelector("#deleteMarketError");
					marketDeletionError = "An error occurred.";
					refDeleteMarketError.innerHTML = "An error occurred.";
				}
			}
			xhr.send("type=4&id=" + encodeURIComponent(URLparameters.get("id")));
		}, 350);
	}
}
function edit_searchProducts(event) {
	const refProductSearchField = document.querySelector("#productSearchField");
	if (event.keyCode) {
		if (event.keyCode === 13) {
			if (refProductSearchField.value.trim().length > 0) {
				fetchNewPage(1, refProductSearchField.value);
			}
		}
	}
	else if (refProductSearchField.value.trim().length > 0) {
		fetchNewPage(1, refProductSearchField.value);
	}
}
function countFieldProductFetch(event) {
	if (/[^0-9]/.test(currentProductsListPage) === false && event.keyCode === 13 && refCurrentPageCountField.value.trim().length > 0) {
		fetchNewPage(refCurrentPageCountField.value.trim(), "");
	}
}
function leftArrowProductFetch(event) {
	if (/[^0-9]/.test(currentProductsListPage) === false && currentProductsListPage > 1 && event.button === 0) {
		fetchNewPage(currentProductsListPage - 1, "");
	}
}
function rightArrowProductFetch(event) {
	if (/[^0-9]/.test(currentProductsListPage) === false && event.button === 0) {
		fetchNewPage(currentProductsListPage + 1, "");
	}
}
refMarketDetailsButton.addEventListener("click", function() {
	if (!refMarketDetailsButton.classList.contains("selectedTab")) {
		refMarketDetailsButton.classList.add("selectedTab");
		if (refProductsButton.classList.contains("selectedTab")) {
			refProductsButton.classList.remove("selectedTab");
		}
		if (document.querySelector("#productSearchField")) {
			marketProductsFieldValue = document.querySelector("#productSearchField").value;
		}
		refSelectedTabLine.style.top = 0;
		var marketNameDetailsContStyles = isMarketNameChangeInProgress === true ? "style='flex-direction: column'" : "";
		var marketNameHTML = isMarketNameChangeInProgress === true ? 
		`<p id="marketNameValue" class="rowInfo" contenteditable="true" spellcheck="false" onkeydown="edit_validateMarketNameFieldKeyDown()" onkeyup="edit_validateMarketNameFieldKeyUp()">${marketNameFieldValue}</p>
		<p id="newMarketNameError" class="inputErrorText inputMethod">${marketNameError}</p>` :
		`<p id="marketNameValue" class="rowInfo" contenteditable="false" spellcheck="false"></p>
		<div id="marketNameEditIcon" class="editIcon" onclick="edit_marketNameEditIconClick()"></div>`;
		isMarketNameChangeInProgress === true && !refCancelOperationOverlay.classList.contains("showOverlay") ? refCancelOperationOverlay.classList.add("showOverlay") : null;
		refMarketInfoCont.innerHTML = `
		<h1 class="topHeaderInfo">Details</h1>
		<div class="infoColumnRow" id="marketLogoRow">
			<p class="rowInfo" id="marketLogoLabel">Market Logo:</p>
			<div id="marketLogoImageDisplay" class="inputMethod" style="url('../../Assets/global/imageNotFound.png')">
				<div id="marketLogoTextOverlay" onmouseout="edit_marketLogoTextOverlayMouseLeave(event)" onmouseover="edit_marketLogoTextOverlayMouseEnter()">
					<p id="marketLogoText" class="notSelectable"></p>
				</div>
			</div>
		</div>
		<div class="infoRow" id="marketNameRow">
			<p id="marketNameLabel" class="rowInfo">Market Name:</p>
			<div class="detailsCont" id="marketNameDetailsCont" ${marketNameDetailsContStyles}>
				${marketNameHTML}
			</div>
		</div>
		<div class="infoColumnRow" id="marketBioRow">
			<p id="marketBioLabel" class="rowInfo">Market Information (optional):</p>
			<textarea id="marketBioField" class="inputMethod" rows="10" placeholder="Give information about your store in 500 characters." maxlength="500"></textarea>
			<div id="updateBioButtonCont">
				<button id="changeCategoryButton" class="inputMethod" onclick='edit_updateMarketBio()'>Make Changes</button>
			</div>
		</div>
		<div id="marketCategoryRow" class="infoColumnRow">
			<p id="marketCategoryLabel" class="rowInfo">Categories:</p>
			<div id="marketCategoryCont">
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Automotive</p>
					<div class='marketCategoryBox inputMethod' id='automotiveBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Baby Care</p>
					<div class='marketCategoryBox inputMethod' id='babyCareBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Books</p>
					<div class='marketCategoryBox inputMethod' id='booksBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>CD and Vinyl</p>
					<div class='marketCategoryBox inputMethod' id='CDandVinylBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Clothes and Accessories</p>
					<div class='marketCategoryBox inputMethod' id='clothesAndAccessoriesBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Electronics</p>
					<div class='marketCategoryBox inputMethod' id='electronicsBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Gardening</p>
					<div class='marketCategoryBox inputMethod' id='gardeningBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Outdoors and Sports</p>
					<div class='marketCategoryBox inputMethod' id='outdoorsAndSportsBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Groceries</p>
					<div class='marketCategoryBox inputMethod' id='groceriesBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Health</p>
					<div class='marketCategoryBox inputMethod' id='healthBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Household</p>
					<div class='marketCategoryBox inputMethod' id='householdBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Personal Care</p>
					<div class='marketCategoryBox inputMethod' id='personalCareBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Kitchen and Dining</p>
					<div class='marketCategoryBox inputMethod' id='kitchenAndDiningBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Travel Supplies</p>
					<div class='marketCategoryBox inputMethod' id='travelSuppliesBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Beauty</p>
					<div class='marketCategoryBox inputMethod' id='beautyBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Movies and TV</p>
					<div class='marketCategoryBox inputMethod' id='moviesAndTVBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Musical Instruments</p>
					<div class='marketCategoryBox inputMethod' id='musicalInstrumentsBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Office Supplies</p>
					<div class='marketCategoryBox inputMethod' id='officeSuppliesBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Pet Supplies</p>
					<div class='marketCategoryBox inputMethod' id='petSuppliesBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Software</p>
					<div class='marketCategoryBox inputMethod' id='softwareBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Tools</p>
					<div class='marketCategoryBox inputMethod' id='toolsBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Toys</p>
					<div class='marketCategoryBox inputMethod' id='toysBox'></div>
				</div>
				<div class='categoryBoxCont'>
					<p class='categoryLabel'>Games</p>
					<div class='marketCategoryBox inputMethod' id='gamesBox'></div>
				</div>
			</div>
			<div id="changeCategoryButtonCont">
				<button id="changeCategoryButton" class="inputMethod" onclick="edit_changeMarketCategory()">Make Changes</button>
			</div>
		</div>
		<div id="deleteMarketRow" class="infoColumnRow">
			<p id="deleteMarketLabel" class="rowInfo">Delete This Market</p>
			<div id="deleteMarketButtonCont">
				<button id="deleteMarketButton" class="inputMethod">${marketDeletionButtonText}</button>
			</div>
			<p id="deleteMarketError" class="inputErrorText">${marketDeletionError}</p>
		</div>`;
		document.querySelectorAll(".marketCategoryBox").forEach(function(eachBox) {
			eachBox.addEventListener("mouseup", function(triggered) {
				if (triggered.button === 0) {
					eachBox.classList.toggle("tickedCategoryBox");
				}
			});
		});
		const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
		const refMarketBioField = document.querySelector("#marketBioField");
		const refMarketLogoImageDisplay = document.querySelector("#marketLogoImageDisplay");
		xhr.open("POST", "fetchMarketDetails.php", true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
		xhr.responseType = "json";
		xhr.onerror = function() {
			marketName = "[Error.]";
			refMarketBioField.value = "[Error.]";
			refMarketLogoImageDisplay.style.backgroundImage = 'url("../../Assets/global/imageNotFound.png")';
			if (document.querySelector("#marketNameValue")) {
				document.querySelector("#marketNameValue").innerHTML = "[Error.]";
			}
		}
		xhr.onload = function() {
			if (xhr.status === 200) {
				if (xhr.response["message"].length === 0) {
					if (isMarketNameChangeInProgress === false) {
						document.querySelector("#marketNameDetailsCont").innerHTML = `<p id="marketNameValue" class="rowInfo">${xhr.response["marketName"]}</p>
						<div id="marketNameEditIcon" class="editIcon" onclick="edit_marketNameEditIconClick()"></div>`;
					}
					refMarketLogoImageDisplay.style.backgroundImage = "";
					refMarketLogoImageDisplay.style.backgroundImage = 'url("' + xhr.response["marketLogoURL"] + '")';
					marketLogoUploaded = document.querySelector("#marketLogoImageDisplay").style.backgroundImage !== 'url("../../Assets/global/imageNotFound.png")';
					edit_marketLogoTextOverlaySet();
					refMarketBioField.innerHTML = xhr.response["marketInfo"];
					marketName = xhr.response["marketName"];
					document.querySelectorAll(".marketCategoryBox").forEach(function(eachBox, itemIndex) {
						const checkIfHasCategory = xhr.response["marketCategories"].indexOf(availableCategories[itemIndex]);
						if (checkIfHasCategory !== -1) {
							eachBox.classList.add("tickedCategoryBox");
						}
					});
				} else {
					marketName = "[Error.]";
					refMarketBioField.value = "[Error.]";
					refMarketLogoImageDisplay.style.backgroundImage = 'url("../../Assets/global/imageNotFound.png")';
					if (document.querySelector("#marketNameValue")) {
						document.querySelector("#marketNameValue").innerHTML = "[Error.]";
					}
				}
			} else {
				marketName = "[Error.]";
				refMarketBioField.value = "[Error.]";
				refMarketLogoImageDisplay.style.backgroundImage = 'url("../../Assets/global/imageNotFound.png")';
				if (document.querySelector("#marketNameValue")) {
					document.querySelector("#marketNameValue").innerHTML = "[Error.]";
				}
			}
		}
		xhr.send("id=" + encodeURIComponent(URLparameters.get("id")));
	}
});
refProductsButton.addEventListener("click", function() {
	if (!refProductsButton.classList.contains("selectedTab")) {
		const refNewMarketNameField = document.querySelector("#newMarketNameField");
		const refNewMarketNameError = document.querySelector("#newMarketNameError");
		const refMarketBioField = document.querySelector("#marketBioField");
		marketBioFieldValue = refMarketBioField.value;
		refProductsButton.classList.add("selectedTab");
		if (refMarketDetailsButton.classList.contains("selectedTab")) {
			refMarketDetailsButton.classList.remove("selectedTab");
		}
		refSelectedTabLine.style.top = "50px";
		if (document.querySelector("#newMarketNameField")) {
			marketNameFieldValue = refNewMarketNameField.value;
			marketNameError = refNewMarketNameError.innerHTML;
		} else {
			marketNameFieldValue = "";
			marketNameError = "";
		}
		refMarketInfoCont.innerHTML = `
		<h1 class="topHeaderInfo">Products</h1>
		<div id="productListCont">
			<div id="productSearchBarContainer">
				<input type='text' value='${escapeText(marketProductsFieldValue)}' id='productSearchField' placeholder='Search'>
				<button id='productSearchButton' type='submit'>
					<div id='productSearchImage'></div>
				</button>
			</div>
			<p id="fetchProductsError" class="inputErrorText"></p>
			<p id="resultCount">0 of 0 results</p>
			<div id="productRowsContainer">
				<div id="newProductCont">
					<div class='productContentsRow infoRow' id="newProductRow">
						<div id="newProductIcon"></div>
						<div class='productNameAndInfoCont infoColumnRow' id="newProductInfoCont">
							<a href='https://www.streetor.sg/marketplace/products/create/?id=${URLparameters.get("id")}' class='productName' id="newProductLabel">New Product</a>
						</div>
					</div>
				</div>
				<div id="existingProductsCont">
				</div>
				<div class='infoColumnRow' id='changePageWrapper'>
					<div id='changePageCont'></div>
					<p id='pageCount' class='notSelectable'><input type='number' value='1' id='currentPageCount' onkeyup='countFieldProductFetch(Event)'> of <span id='maxPagesCount'>0</span> pages</p>
				</div>
			</div>
		</div>`;
		document.querySelector("#productSearchField").onkeydown = edit_searchProducts;
		document.querySelector("#productSearchButton").onclick = edit_searchProducts;
		if (marketProductsFieldValue.trim().length > 0) {
			fetchNewPage(currentProductsListPage, marketProductsFieldValue);
		} else {
			fetchNewPage(currentProductsListPage, "");
		}
	}
});
refConfirmButton.addEventListener("click", function() {
	const refConfirmationOverlay = document.querySelector("#confirmationOverlay");
	if (refConfirmationOverlay.getAttribute("data-destid")) {
		refConfirmationOverlay.classList.remove("displaying");
		const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
		xhr.open("POST", "deleteProduct.php", true);
		xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xhr.responseType = "text";
		xhr.onerror = function() {
			setNotification("An error occurred.", true);
		}
		xhr.onload = function() {
			if (xhr.status === 200) {
				if (xhr.responseText === "Product Deleted.") {
					setNotification(xhr.responseText, false);
					fetchNewPage(currentProductsListPage, "");
				} else {
					setNotification(xhr.responseText, true);
				}
			} else {
				setNotification("An error occurred.", true);
			}
		}
		xhr.send("id=" + encodeURIComponent(refConfirmationOverlay.getAttribute("data-destid")));
	}
});
refCancelButton.addEventListener("click", function() {
	const refConfirmationOverlay = document.querySelector("#confirmationOverlay");
	refConfirmationOverlay.classList.remove("displaying");
});
document.addEventListener("mousedown", function(event) {
	if (event.detail > 1) {
	  event.preventDefault();
	}
}, false);