"use strict";
const refMenuButton = document.querySelector("#menuToggle");
const refSideNav = document.querySelector("#sidenav");
const refNotificationCont = document.querySelector("#notificationCont");
const refNotificationText = document.querySelector("#notificationText");
var checkSubscribe;
refMenuButton.style.filter = "brightness(100%)";
refMenuButton.style.cursor = "pointer";
refMenuButton.addEventListener("click", function(triggered) {
	if (triggered.button === 0) {
		if (refSideNav.classList.contains("openedSideNav") || refMenuButton.style.animationName === "menuAnimationOpen") {
			refSideNav.classList.remove("openedSideNav");
			refMenuButton.style.animationName = "menuAnimationClose";
		} else {
			refSideNav.classList.add("openedSideNav");
			refMenuButton.style.animationName = "menuAnimationOpen";
		}
	}
});
function toggleSubscribe(event) {
	const refSubscribeButtonCont = document.querySelector("#subscribeButtonCont");
	if (event.button === 0) {
		clearTimeout(checkSubscribe);
		checkSubscribe = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			const URLparameters = new URLSearchParams(window.location.search);
			xhr.open("POST", "subscribe.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xhr.responseType = "json";
			refSubscribeButtonCont.innerHTML = '<div id="loadingImageCont"></div>';
			xhr.onerror = function() {
				refNotificationCont.style.backgroundColor = "#E60505";
				refNotificationText.innerHTML = "An error occurred.";
				setTimeout(function() {
                    refNotificationCont.style.top = "-10vh";
				},1000);
			}
			xhr.onload = function() {
				if (xhr.status === 200) {
					const notificationColor = xhr.response["notificationColor"];
					const notificationText = xhr.response["notificationText"];
                    refNotificationCont.style.top = 0;
					refNotificationCont.style.backgroundColor = notificationColor;
					refNotificationText.innerHTML = notificationText;
					refSubscribeButtonCont.innerHTML = '<button id="subscribeButton" class="' + xhr.response["buttonClass"] + '" onmouseup="toggleSubscribe(event)" onmousedown="cancelToggleSubscribeTimeout(event)">' + xhr.response["buttonText"] + '</button>';
					setTimeout(function() {
						refNotificationCont.style.top = "-10vh";
					},1000);
					if (xhr.response["notificationText"] === "Subscribed!" || xhr.response["notificationText"] === "Unsubscribed!") {
						const refSubscriberCount = document.querySelector("#subscriberCount");
						refSubscriberCount.innerHTML = xhr.response["subscriberCount"];
					}
				} else {
                    refNotificationCont.style.top = 0;
                    refNotificationCont.style.backgroundColor = "#E60505";
					refNotificationText.innerHTML = "An error occurred.";
					setTimeout(function() {
						refNotificationCont.style.top = "-10vh";
					},1000);
				}
			}
			xhr.send("id=" + encodeURIComponent(URLparameters["id"]));
		}, 350);
	}
}
function cancelToggleSubscribeTimeout(event) {
	if (event.button === 0) {
		clearTimeout(checkSubscribe);
	}
}