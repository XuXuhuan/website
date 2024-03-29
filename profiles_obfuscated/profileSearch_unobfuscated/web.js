"use strict";
const refMenuButton = document.querySelector("#menuToggle");
const refSideNav = document.querySelector("#sidenav");
const refCurrentPageCountField = document.querySelector("#currentPageCount");
const refResultCount = document.querySelector("#resultCount");
const refNotificationCont = document.querySelector("#notificationCont");
const refNotificationText = document.querySelector("#notificationText");
var currentPage = 1;
var checkChangePage;
var checkNotification;
refMenuButton.style.filter = "brightness(100%)";
refMenuButton.style.cursor = "pointer";
function fetchNewPage(newPage) {
	const refProfilesContainer = document.querySelector("#profilesContainer");
	const refSearchErrorText = document.querySelector("#searchErrorText");
	const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
	const URLparameters = new URLSearchParams(window.location.search);
	xhr.open("POST", "fetchProfilesDetails.php", true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.responseType = "json";
	xhr.onload = function() {
		if (xhr.status === 200) {
			refProfilesContainer.innerHTML = "";
			refSearchErrorText.innerHTML = xhr.response["errormessage"];
			xhr.response["profileDetails"].forEach(function(item) {
				refProfilesContainer.innerHTML += `
				<div class="infoColumnRow">
					<a href="https://streetor.sg/profiles/?id=${item["profileID"]}" class="userListName">${item["profileUsername"]}</a>
					<p class="bioPreview">${item["profileBiography"]}</p>
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
					refCreatePrevPageButton.onmouseup = function(triggered) {leftArrowProfileFetch(triggered)};
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
					refCreateNextPageButton.onmouseup = function(triggered) {rightArrowProfileFetch(triggered)};
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
	xhr.send("query=" + encodeURIComponent(URLparameters.get("query")) + "&page=" + encodeURIComponent(newPage));
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
function countFieldProfileFetch(event) {
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
function leftArrowProfileFetch(event) {
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
function rightArrowProfileFetch(event) {
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