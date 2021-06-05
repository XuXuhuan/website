"use strict";
const refMenuButton = document.querySelector("#menuToggle");
const refSideNav = document.querySelector("#sidenav");
const refNotificationCont = document.querySelector("#notificationCont");
const refNotificationText = document.querySelector("#notificationText");
const refMenuPopUps = document.querySelectorAll(".productMenuPopUp, .marketMenuPopUp");
const refProductMenuDelete = document.querySelector("#productMenuDelete");
var bestStatProductName = document.querySelector("#bestStatProductName") ? document.querySelector("#bestStatProductName").innerHTML : null;
var checkNotification;
refMenuButton.style.filter = "brightness(100%)";
refMenuButton.style.cursor = "pointer";
function setNotification(message, isError) {
	refNotificationCont.style.top = 0;
	refNotificationCont.style.backgroundColor = isError === true ? "#E60505" : "#40AF00";
	refNotificationText.innerHTML = message;
	clearTimeout(checkNotification);
	checkNotification = setTimeout(function() {
		refNotificationCont.style.top = "-10vh";
	}, 1000);
}
refMenuPopUps.forEach(function(item) {
	item.parentElement.addEventListener("click", function() {
		item.classList.toggle("hidePopUp");
	});
});
if (document.querySelector("#bestStatProductName")) {
	refProductMenuDelete.addEventListener("click", function() {
		const refConfirmationOverlay = document.querySelector("#confirmationOverlay");
		const refConfirmationText = document.querySelector("#confirmationText");
		refConfirmationText.innerHTML = "Are you sure you want to delete " + bestStatProductName + "? This process cannot be undone!";
		refConfirmationOverlay.setAttribute("data-destid", refProductMenuDelete.getAttribute("data-productid"));
		if (!refConfirmationOverlay.classList.contains("displaying")) {
			refConfirmationOverlay.classList.add("displaying");
		}
	});
}
if (document.querySelector(".productMenuButtonCont, .marketMenuButtonCont")) {
	const refConfirmButton = document.querySelector("#confirmButton");
	const refCancelButton = document.querySelector("#cancelButton");
	refConfirmButton.addEventListener("click", function() {
		const refConfirmationOverlay = document.querySelector("#confirmationOverlay");
		if (refConfirmationOverlay.getAttribute("data-destid").length > 0) {
			refConfirmationOverlay.classList.remove("displaying");
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			xhr.open("POST", "marketplace/edit/deleteProduct.php", true);
			xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhr.responseType = "text";
			xhr.onerror = function() {
				setNotification("An error occurred.", true);
			}
			xhr.onload = function() {
				if (xhr.status === 200) {
					if (xhr.responseText === "Product Deleted.") {
						window.location.reload();
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