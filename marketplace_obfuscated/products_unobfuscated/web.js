"use strict";
const refMenuButton = document.querySelector("#menuToggle");
const refSideNav = document.querySelector("#sidenav");
const refCurrentPageCountField = document.querySelector("#currentPageCount");
const refResultCount = document.querySelector("#resultCount");
const refNotificationCont = document.querySelector("#notificationCont");
const refNotificationText = document.querySelector("#notificationText");
const refProductSearchField = document.querySelector("#productSearchField");
const refProductSearchButton = document.querySelector("#productSearchButton");
const URLparameters = new URLSearchParams(window.location.search);
var marketID;
var currentPage = 1;
var checkChangePage;
var checkRating;
var checkNotification;
refMenuButton.style.filter = "brightness(100%)";
refMenuButton.style.cursor = "pointer";
function setRating(rating) {
	const refFilledStops = document.querySelectorAll(".filledStop");
	const refUnfilledStops = document.querySelectorAll(".unfilledStop");
	refFilledStops.forEach(function(item, index) {
		if (rating % 1 > 0) {
			if (index + 1 <= Math.floor(rating)) {
				item.setAttribute("offset", "100%");
			}
			else if (index + 1 === Math.ceil(rating)) {
				item.setAttribute("offset", ((rating % 1) * 100) + "%");
			} else {
				item.setAttribute("offset", 0);
			}
		} else {
			if (index + 1 <= rating) {
				item.setAttribute("offset", "100%");
			} else {
				item.setAttribute("offset", 0);
			}
		}
		refUnfilledStops[index].setAttribute("offset", item.getAttribute("offset"));
	});
}
function fetchNewPage(newPage) {
	const refProductsContainer = document.querySelector("#productsContainer");
	const refSearchErrorText = document.querySelector("#searchErrorText");
	const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
	var URLdata;
	xhr.open("POST", "fetchProductsDetails.php", true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.responseType = "json";
	xhr.onload = function() {
		if (xhr.status === 200) {
			currentPage = newPage;
			refProductsContainer.innerHTML = "";
			refSearchErrorText.innerHTML = xhr.response["errormessage"];
			xhr.response["productDetails"].forEach(function(item) {
				refProductsContainer.innerHTML += `
				<div class='productContentsRow infoRow'>
					<img src='${item["productImageURL"]}' alt='Product Image' class='productImage'>
					<div class='productNameAndInfoCont infoColumnRow'>
						<a href='https://streetor.sg/marketplace/products/?prodid=${item["productID"]}' class='productName'>${item["productName"]}</a>
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
					refCreatePrevPageButton.onmouseup = function(triggered) {leftArrowProductFetch(triggered)};
					refCreatePrevPageButton.onmousedown = function(triggered) {cancelLeftArrowDecrementTimeout(triggered)};
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
					refCreateNextPageButton.onmouseup = function(triggered) {rightArrowProductFetch(triggered)};
					refCreateNextPageButton.onmousedown = function(triggered) {cancelRightArrowIncrementTimeout(triggered)};
					refCreateNextPageImage.id = "rightArrowCont";
					refCreateNextPageImage.classList.add("changePageArrowCont");
					refCreateNextPageButton.appendChild(refCreateNextPageImage);
					refChangePageCont.appendChild(refCreateNextPageButton);
				}
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
refMenuButton.addEventListener("click", function() {
	if (refSideNav.classList.contains("openedSideNav") || refMenuButton.style.animationName === "menuAnimationOpen") {
		refSideNav.classList.remove("openedSideNav");
		refMenuButton.style.animationName = "menuAnimationClose";
	} else {
		refSideNav.classList.add("openedSideNav");
		refMenuButton.style.animationName = "menuAnimationOpen";
	}
});
function countFieldProductFetch(event) {
	if (/[^0-9]/.test(currentPage) === false && event.keyCode === 13) {
		clearTimeout(checkChangePage);
		checkChangePage = setTimeout(fetchNewPage(refCurrentPageCountField.value), 350);
	}
}
function cancelCountFieldIncrementTimeout(event) {
	if (event.keyCode === 13) {
		clearTimeout(checkChangePage);
	}
}
function leftArrowProductFetch(event) {
	if (/[^0-9]/.test(currentPage) === false && currentPage > 1 && event.button === 0) {
		clearTimeout(checkChangePage);
		checkChangePage = setTimeout(fetchNewPage(currentPage - 1), 350);
	}
}
function cancelLeftArrowDecrementTimeout(event) {
	if (event.button === 0) {
		clearTimeout(checkChangePage);
	}
}
function rightArrowProductFetch(event) {
	if (/[^0-9]/.test(currentPage) === false && event.button === 0) {
		clearTimeout(checkChangePage);
		checkChangePage = setTimeout(fetchNewPage(currentPage + 1), 350);
	}
}
function cancelRightArrowIncrementTimeout(event) {
	if (event.button === 0) {
		clearTimeout(checkChangePage);
	}
}
if (document.querySelector("#productImageScroller")) {
	const refProductImageScroller = document.querySelector("#productImageScroller");
	var currentProductImage = 0;
	var productImages = window.getComputedStyle(document.querySelector("body"), "::before").getPropertyValue("content").split(" ");
	var keys = {37 : 1, 38 : 1, 39 : 1, 40 : 1};
	var overallRating = document.querySelector("#ratingLabel").innerHTML.split(" ")[0];
	const refRatingStarCont = document.querySelector("#ratingStarCont");
	const refRatingStarLeftHalves = document.querySelectorAll(".ratingStarLeftHalf");
	const refRatingStarRightHalves = document.querySelectorAll(".ratingStarRightHalf");
	function preventDefault(e) {
		e.preventDefault();
	}
	function preventDefaultForScrollKeys(e) {
		if (keys[e.keyCode]) {
			preventDefault(e);
			return false;
		}
	}
	var supportsPassive = false;
	try {
		window.addEventListener("test", null, Object.defineProperty({}, "passive", {
			get: function () {
				supportsPassive = true;
			} 
		}));
	} catch(e) {}
	var wheelOpt = supportsPassive ? { passive: false } : false;
	var wheelEvent = "onwheel" in document.createElement("div") ? "wheel" : "mousewheel";
	function disableScroll() {
		window.addEventListener("DOMMouseScroll", preventDefault, false);
		window.addEventListener(wheelEvent, preventDefault, wheelOpt);
		window.addEventListener("touchmove", preventDefault, wheelOpt);
		window.addEventListener("keydown", preventDefaultForScrollKeys, false);
	}
	function enableScroll() {
		window.removeEventListener("DOMMouseScroll", preventDefault, false);
		window.removeEventListener(wheelEvent, preventDefault, wheelOpt); 
		window.removeEventListener("touchmove", preventDefault, wheelOpt);
		window.removeEventListener("keydown", preventDefaultForScrollKeys, false);
	}
	marketID = document.querySelector("#marketID").innerHTML;
	refRatingStarCont.addEventListener("mouseout", function() {
		setRating(overallRating);
	});
	refRatingStarLeftHalves.forEach(function(item, index) {
		item.addEventListener("mouseover", function() {
			setRating(index + 0.5);
		});
	});
	refRatingStarLeftHalves.forEach(function(item, index) {
		item.addEventListener("click", function() {
			clearTimeout(checkRating);
			checkRating = setTimeout(function() {
				const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
				xhr.open("POST", "rateProduct.php", true);
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr.responseType = "json";
				xhr.onerror = function() {
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
						const notificationColor = xhr.response["notificationColor"];
						const notificationText = xhr.response["notificationText"];
						const newRating = xhr.response["averageRating"];
						const newRatingCount = xhr.response["ratingCount"];
						refNotificationCont.style.top = 0;
						refNotificationCont.style.backgroundColor = notificationColor;
						refNotificationText.innerHTML = notificationText;
						clearTimeout(checkNotification);
						checkNotification = setTimeout(function() {
							refNotificationCont.style.top = "-10vh";
						},1000);
						if (xhr.response["notificationText"] === "Rating submitted!") {
							const refRatingLabel = document.querySelector("#ratingLabel");
							const newAverageRating = xhr.response["ratingAlreadyExists"] === false ? (newRating * newRatingCount + index + 0.5) / (newRatingCount + 1) : ((newRating * (newRatingCount - 1)) + index + 0.5) / newRatingCount;
							const newRatingCounter = xhr.response["ratingAlreadyExists"] === false ? newRatingCount + 1 : newRatingCount;
							refRatingLabel.innerHTML = newAverageRating + " (" + newRatingCounter + ")";
							overallRating = newAverageRating;
							setRating(overallRating);
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
				xhr.send("productid=" + encodeURIComponent(URLparameters.get("prodid")) + "&rating=" + encodeURIComponent(index + 0.5));
			}, 350);
		});
	});
	refRatingStarRightHalves.forEach(function(item, index) {
		item.addEventListener("click", function() {
			clearTimeout(checkRating);
			checkRating = setTimeout(function() {
				const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
				xhr.open("POST", "rateProduct.php", true);
				xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xhr.responseType = "json";
				xhr.onerror = function() {
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
						const notificationColor = xhr.response["notificationColor"];
						const notificationText = xhr.response["notificationText"];
						const newRating = xhr.response["averageRating"];
						const newRatingCount = xhr.response["ratingCount"];
						refNotificationCont.style.top = 0;
						refNotificationCont.style.backgroundColor = notificationColor;
						refNotificationText.innerHTML = notificationText;
						clearTimeout(checkNotification);
						checkNotification = setTimeout(function() {
							refNotificationCont.style.top = "-10vh";
						},1000);
						if (xhr.response["notificationText"] === "Rating submitted!") {
							const refRatingLabel = document.querySelector("#ratingLabel");
							const newAverageRating = xhr.response["ratingAlreadyExists"] === false ? (newRating * newRatingCount + index + 1) / (newRatingCount + 1) : ((newRating * (newRatingCount - 1)) + index + 1) / newRatingCount;
							const newRatingCounter = xhr.response["ratingAlreadyExists"] === false ? newRatingCount + 1 : newRatingCount;
							refRatingLabel.innerHTML = newAverageRating + " (" + newRatingCounter + ")";
							overallRating = newAverageRating;
							setRating(overallRating);
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
				xhr.send("productid=" + encodeURIComponent(URLparameters.get("prodid")) + "&rating=" + encodeURIComponent(index + 1));
			}, 350);
		});
	});
	refRatingStarRightHalves.forEach(function(item, index) {
		item.addEventListener("mouseover", function() {
			setRating(index + 1);
		});
	});
	if (productImages.length > 1) {
		if (!document.querySelector("div#nextImageButton")) {
			var createNextImageButton = document.createElement("div");
			createNextImageButton.id = "nextImageButton";
			refProductImageScroller.appendChild(createNextImageButton);
			function goToPreviousImage() {
				if (currentProductImage > 0) {
					currentProductImage--;
					refProductImageScroller.style.backgroundImage = productImages[currentProductImage];
					if (currentProductImage === 0) {
						const refPreviousImageButton = document.querySelector("#previousImageButton");
						refPreviousImageButton.remove();
					}
					if (!document.querySelector("#nextImageButton")) {
						var createNextImageButton = document.createElement("div");
						createNextImageButton.id = "nextImageButton";
						refProductImageScroller.appendChild(createNextImageButton);
						createNextImageButton.addEventListener("click", goToNextImage);
					}
				}
			}
			function goToNextImage() {
				if (productImages.length > currentProductImage) {
					currentProductImage++;
					refProductImageScroller.style.backgroundImage = productImages[currentProductImage];
					if (currentProductImage + 1 === productImages.length) {
						const refNextImageButton = document.querySelector("#nextImageButton");
						refNextImageButton.remove();
					}
					if (!document.querySelector("#previousImageButton")) {
						var createPreviousImageButton = document.createElement("div");
						createPreviousImageButton.id = "previousImageButton";
						refProductImageScroller.appendChild(createPreviousImageButton);
						createPreviousImageButton.addEventListener("click", goToPreviousImage);
					}
				}
			}
			createNextImageButton.addEventListener("click", goToNextImage);
			refProductImageScroller.addEventListener("wheel", function(triggered) {
				if (triggered.deltaY > 0) {
					goToNextImage();
				}
				else if (triggered.deltaY < 0) {
					goToPreviousImage();
				}
			});
		}
	}
	refProductImageScroller.addEventListener("mouseover", function() {
		disableScroll();
	});
	refProductImageScroller.addEventListener("mouseleave", function() {
		enableScroll();
	});
}
if (URLparameters.has("marketid")) {
	marketID = URLparameters.get("marketid");
} else {
	if (document.querySelector("#marketID")) {
		marketID = document.querySelector("#marketID").innerHTML;
	}
}
refProductSearchField.addEventListener("keyup", function(key) {
	if (key.keyCode === 13 && refProductSearchField.value.length > 0) {
		window.location = "https://streetor.sg/marketplace/products/?marketid=" + encodeURIComponent(marketID) + "&query=" + encodeURIComponent(refProductSearchField.value);
	}
});
refProductSearchButton.addEventListener("click", function() {
	if (refProductSearchField.value.length > 0) {
		window.location = "https://streetor.sg/marketplace/products/?marketid=" + encodeURIComponent(marketID) + "&query=" + encodeURIComponent(refProductSearchField.value);
	}
});