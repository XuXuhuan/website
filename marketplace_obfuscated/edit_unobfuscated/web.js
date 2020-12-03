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
const URLparameters = new URLSearchParams(window.location.search);
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
var marketCategories = [];
var marketLogoImageURL = document.querySelector("#marketLogoImageDisplay").style.backgroundImage;
var marketName = document.querySelector("#marketNameValue").innerHTML;
var checkNotification;
var checkMarketName;
var checkMarketBio;
var isMarketNameChangeInProgress = false;
var marketNameFieldValue = "";
var marketNameError = "";
var marketBioFieldValue = "";
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
function fetchNewPage(newPage) {
	const refExistingProductsCont = document.querySelector("#existingProductsCont");
	const refFetchProductsError = document.querySelector("#fetchProductsError");
	const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
	var URLdata;
	xhr.open("POST", "fetchMarketProducts.php", true);
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
						</div>
					</div>`;
				});
				if (xhr.response["currentResults"] > 0 && xhr.response["maxResults"] > 0) {
					refResultCount.innerHTML = xhr.response["currentResults"] + " of " + xhr.response["maxResults"] + " results";
					refCurrentPageCountField.value = Math.ceil(xhr.response["currentResults"] / 10);
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
			refNotificationCont.style.top = 0;
			refNotificationCont.style.backgroundColor = "#E60505";
			refNotificationText.innerHTML = "An error occurred.";
			clearTimeout(checkNotification);
			checkNotification = setTimeout(function() {
				refNotificationCont.style.top = "-10vh";
			},1000);
		}
	}
	if (URLparameters.has("query")) {
		URLdata = "hasQuery=1&page=" + encodeURIComponent(newPage) + "&marketid=" + encodeURIComponent(URLparameters.get("marketid")) + "&query=" + encodeURIComponent(URLparameters.get("query"));
	} else {
		URLdata = "hasQuery=0&page=" + encodeURIComponent(newPage) + "&marketid=" + encodeURIComponent(URLparameters.get("marketid"));
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
document.querySelectorAll(".marketCategoryBox").forEach(function(eachBox, itemIndex) {
	eachBox.addEventListener("mouseup", function(triggered) {
		if (triggered.button === 0) {
			const findItemInSelectedCategories = marketCategories.indexOf(availableCategories[itemIndex]);
			if (findItemInSelectedCategories === -1) {
				marketCategories.push(availableCategories[itemIndex]);
			} else {
				marketCategories.splice(findItemInSelectedCategories, 1);
			}
			eachBox.classList.toggle("tickedCategoryBox");
			if (marketCategories.length === 0) {
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
	const refMarketNameDetailsCont = document.querySelector("#marketNameDetailsCont");
	refMarketNameDetailsCont.innerHTML = `<input id="newMarketNameField" class="newDetailField inputMethod" onkeyup="edit_validateMarketNameFieldKeyUp(Event)" onkeydown="edit_validateMarketNameFieldKeyDown()">
	<p id="newMarketNameError" class="inputErrorText">Enter to confirm, Esc/click outside to cancel</p>`;
	refMarketNameDetailsCont.style.flexDirection = "column";
	refCancelOperationOverlay.classList.add("showOverlay");
	isMarketNameChangeInProgress = true;
}
function edit_marketLogoTextOverlayMouseEnter() {
	const refMarketLogoText = document.querySelector("#marketLogoText");
	const refMarketLogoTextOverlay = document.querySelector("#marketLogoTextOverlay");
	if (marketLogoImageURL !== 'url("../../Assets/global/imageNotFound.png")') {
		refMarketLogoText.innerHTML = "REMOVE IMAGE";
		refMarketLogoTextOverlay.style.opacity = 1;
		refMarketLogoTextOverlay.addEventListener("click", function() {
			var dataForm = new FormData();
			dataForm.append("type", 1);
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
						marketLogoImageURL = refMarketLogoImageDisplay.style.backgroundImage;
					} else {
						setNotification(xhr.response["errormessage"], 1);
					}
				} else {
					setNotification("An error occurred.", true);
				}
			}
			xhr.send(dataForm);
		});
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
	if (!e || e.parentNode != refMarketLogoTextOverlay) {
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
		var dataForm = new FormData();
		dataForm.append("type", 2);
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
					refMarketLogoImageDisplay.style.backgroundImage = 'url("' + xhr.response["newMarketLogoURL"] + '")';
					marketLogoImageURL = refMarketLogoImageDisplay.style.backgroundImage;
				} else {
					setNotification(xhr.response["errormessage"], 1);
				}
			} else {
				setNotification("An error occurred.", true);
			}
		}
		xhr.send(dataForm);
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
					"value" : refMarketBioField.value
				}
			)
		);
	}, 350);
}
function countFieldProductFetch(event) {
	if (/[^0-9]/.test(currentPage) === false && event.keyCode === 13) {
		checkChangePage = refCurrentPageCountField.value;
	}
}
function leftArrowProductFetch(event) {
	if (/[^0-9]/.test(currentPage) === false && currentPage > 1 && event.button === 0) {
		fetchNewPage(currentPage - 1);
	}
}
function rightArrowProductFetch(event) {
	if (/[^0-9]/.test(currentPage) === false && event.button === 0) {
		fetchNewPage(currentPage + 1);
	}
}
refMarketDetailsButton.addEventListener("click", function() {
	if (!refMarketDetailsButton.classList.contains("selectedTab")) {
		refMarketDetailsButton.classList.add("selectedTab");
		if (refProductsButton.classList.contains("selectedTab")) {
			refProductsButton.classList.remove("selectedTab");
		}
		refSelectedTabLine.style.top = 0;
		var marketNameDetailsContStyles = isMarketNameChangeInProgress === true ? "style='flex-direction: column'" : "";
		var marketNameHTML = isMarketNameChangeInProgress === true ? 
		`<input value="${marketNameFieldValue}" class="newDetailField inputMethod" id="newMarketNameField" onkeyup="edit_validateMarketNameFieldKeyUp(Event) onkeydown="edit_validateMarketNameFieldKeyDown()">
		<p id="newMarketNameError" class="inputErrorText">${marketNameError}</p>` : `<p id="marketNameValue" class="rowInfo"></p>
		<div id="marketNameEditIcon" class="editIcon" onclick="edit_marketNameEditIconClick()"></div>`;
		isMarketNameChangeInProgress === true && !refCancelOperationOverlay.classList.contains("showOverlay") ? refCancelOperationOverlay.classList.add("showOverlay") : null;
		refMarketInfoCont.innerHTML = `
		<h1 class="topHeaderInfo">Details</h1>
		<div class="infoColumnRow" id="marketLogoRow">
			<p class="rowInfo" id="marketLogoLabel">Market Logo:</p>
			<div id="marketLogoImageDisplay" class="inputMethod" style="url('../../Assets/global/imageNotFound')">
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
			<button id="changeCategoryButton" class="inputMethod">Make Changes</button>
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
			<button id="changeCategoryButton" class="inputMethod">Make Changes</button>
		</div>
		<div id="deleteMarketRow" class="infoColumnRow">
			<p id="deleteMarketLabel" class="rowInfo">Delete This Market</p>
			<button id="deleteMarketButton" class="inputMethod">Delete Market</button>
		</div>`;
		document.querySelectorAll(".marketCategoryBox").forEach(function(eachBox, itemIndex) {
			eachBox.addEventListener("mouseup", function(triggered) {
				if (triggered.button === 0) {
					const findItemInSelectedCategories = marketCategories.indexOf(availableCategories[itemIndex]);
					if (findItemInSelectedCategories === -1) {
						marketCategories.push(availableCategories[itemIndex]);
					} else {
						marketCategories.splice(findItemInSelectedCategories, 1);
					}
					eachBox.classList.toggle("tickedCategoryBox");
					if (marketCategories.length === 0) {
						if (!refMarketRegisterError.classList.contains("inputErrorText")) {
							refMarketRegisterError.classList.add("inputErrorText");
						}
						setNotification("Please select at least one category.", true);
					}
				}
			});
		});
		const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
		const refMarketBioField = document.querySelector("#marketBioField");
		const refMarketLogoImageDisplay = document.querySelector("#marketLogoImageDisplay");
		xhr.open("GET", "fetchMarketDetails.php", true);
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
				if (xhr.response["errormessage"].length === 0) {
					if (isMarketNameChangeInProgress === false) {
						marketNameHTML = `<p id="marketNameValue" class="rowInfo">${xhr.response["marketName"]}</p>
						<div id="marketNameEditIcon" class="editIcon" onclick="edit_marketNameEditIconClick()"></div>`;
					}
					marketName = xhr.response["marketName"];
					marketBio = xhr.response["marketBio"];
					marketImageURL = xhr.response["marketImageURL"];
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
		xhr.send();
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
				<input type='text' id='productSearchField' placeholder='Search'>
				<button id='productSearchButton' type='submit'>
					<div id='productSearchImage'></div>
				</button>
			</div>
			<p id="resultCount">0 of 0 results</p>
			<div id="productRowsContainer">
				<div id="newProductCont">
					<div class='productContentsRow infoRow' id="newProductRow">
						<div id="newProductIcon"></div>
						<div class='productNameAndInfoCont infoColumnRow' id="newProductInfoCont">
							<a href='https://www.streetor.sg/marketplace/createProduct/?id=${URLparameters.get("id")}' class='productName' id="newProductLabel">New Product</a>
						</div>
					</div>
				</div>
				<div id="existingProductsCont">
				</div>
			</div>
		</div>`;
		fetchNewPage(currentProductsListPage + 1);
	}
});
document.addEventListener("mousedown", function(event) {
	if (event.detail > 1) {
	  event.preventDefault();
	}
}, false);