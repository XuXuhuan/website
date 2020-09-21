"use strict";
const refMenuButton = document.querySelector("#menuToggle");
const refSideNav = document.querySelector("#sidenav");
const refCurrentPageCountField = document.querySelector("#currentPageCount");
const refResultCount = document.querySelector("#resultCount");
var currentPage = 1;
var checkChangePage;
refMenuButton.style.filter = "brightness(100%)";
refMenuButton.style.cursor = "pointer";
function fetchNewPage(newPage) {
	const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
	const refProfilesContainer = document.querySelector("#profilesContainer");
	const URLparameters = new URLSearchParams(window.location.search);
	xhr.open("POST", "fetchProfilesDetails.php", true);
	xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xhr.responseType = "json";
	xhr.onload = function() {
		xhr.response["profileDetails"].forEach(function(item) {
			refProfilesContainer.innerHTML += `
			<div class="infoColumnRow">
				<a href="${item["profileLink"]}" class="userListName">${item["profileUsername"]}</a>
				<p class="bioPreview">${item["profileBiography"]}</p>
			</div>`;
		});
		refResultCount.innerHTML = xhr.response["currentResults"] + " of " + xhr.response["maxResults"];
		refCurrentPageCountField.value = newPage;
		if (Math.floor(xhr.response["maxResults"] / 10) === newPage) {
			if (document.querySelector("#nextPageButton")) {
				const refNextPageButton = document.querySelector("#nextPageButton");
				refNextPageButton.parentNode.removeChild(refNextPageButton);
			}
		}
		if (newPage === 1) {
			if (document.querySelector("#prevPageButton")) {
				const refPrevPageButton = document.querySelector("#prevPageButton");
				refPrevPageButton.parentNode.removeChild(refPrevPageButton);
			}
		}
	}
	xhr.send("query=" + encodeURIComponent(URLparameters.get("query")) + "&page=" + encodeURIComponent(newPage));
}
refMenuButton.addEventListener("mousedown", function(triggered) {
	if (triggered.button === 0) {
		refMenuButton.style.animationName = refMenuButton.style.animationName === "menuAnimationOpen" ? "menuAnimationClose" : "menuAnimationOpen";
		refSideNav.style.left = refMenuButton.style.animationName === "menuAnimationOpen" ? 0 : refSideNav.clientWidth * -1 + "px"; 
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
function leftArrowProfileFetch() {
	if (/[^0-9]/.test(currentPage) === false && currentPage > 1) {
		clearTimeout(checkChangePage);
		checkChangePage = setTimeout(fetchNewPage(currentPage - 1), 350);
	}
}
function cancelLeftArrowDecrementTimeout() {
	clearTimeout(checkChangePage);
}
function rightArrowProfileFetch() {
	if (/[^0-9]/.test(currentPage) === false) {
		clearTimeout(checkChangePage);
		checkChangePage = setTimeout(fetchNewPage(currentPage + 1), 350);
	}
}
function cancelRightArrowIncrementTimeout() {
	clearTimeout(checkChangePage);
}