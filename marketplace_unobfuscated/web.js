"use strict";
const refMenuButton = document.querySelector("#menuToggle");
const refSideNav = document.querySelector("#sidenav");
const refNotificationCont = document.querySelector("#notificationCont");
const refNotificationText = document.querySelector("#notificationText");
var checkSubscribe;
var checkNotification;
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
function toggleSubscribe(event) {
	const refSubscribeButtonCont = document.querySelector("#subscribeButtonCont");
	if (event.button === 0) {
		clearTimeout(checkSubscribe);
		checkSubscribe = setTimeout(function() {
			const xhr = window.XMLHttpRequest ? new XMLHttpRequest : new ActiveXObject("Microsoft.XMLHTTP");
			const URLparameters = new URLSearchParams(window.location.search);
			const defaultButtonText = document.querySelector("#subscribeButton").innerHTML;
			refSubscribeButtonCont.innerHTML = '<div id="loadingImageCont"></div>';
			xhr.open("POST", "subscribe.php", true);
			xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
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
                    refNotificationCont.style.top = 0;
					refNotificationCont.style.backgroundColor = notificationColor;
					refNotificationText.innerHTML = notificationText;
					refSubscribeButtonCont.innerHTML = `<button id="subscribeButton" class="${xhr.response["buttonClass"]}" ${xhr.response["buttonClass"] === "cannotSubscribe" ? "" : 'onmouseup="toggleSubscribe(event)" onmousedown="cancelToggleSubscribeTimeout(event)"'}>
															${xhr.response["buttonText"].length === 0 ? defaultButtonText : xhr.response["buttonText"]}
														</button>`;
					clearTimeout(checkNotification);
					checkNotification = setTimeout(function() {
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
					clearTimeout(checkNotification);
					checkNotification = setTimeout(function() {
						refNotificationCont.style.top = "-10vh";
					},1000);
				}
			}
			xhr.send("id=" + encodeURIComponent(URLparameters.get("id")));
		}, 350);
	}
}
function cancelToggleSubscribeTimeout(event) {
	if (event.button === 0) {
		clearTimeout(checkSubscribe);
	}
}
if (document.querySelector("#menuButtonCont")) {
	const refMenuButtonCont = document.querySelector("#menuButtonCont");
	const refPopUp = document.querySelector("#popUp");
	var canManage = refMenuButtonCont.classList.contains("cannotManage");
	refMenuButtonCont.addEventListener("click", function() {
		if (canManage === false) {
			refPopUp.classList.toggle("hidePopUp");
		}
	});
}
document.addEventListener("mousedown", function(event) {
	if (event.detail > 1) {
	  event.preventDefault();
	}
}, false);