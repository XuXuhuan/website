"use strict";
const refMenuButton = document.querySelector("#menuToggle");
const refSideNav = document.querySelector("#sidenav");
refMenuButton.style.filter = "brightness(100%)";
refMenuButton.style.cursor = "pointer";
refMenuButton.addEventListener("mousedown", function(triggered) {
	if (triggered.button === 0) {
		refMenuButton.style.animationName = refMenuButton.style.animationName === "menuAnimationOpen" ? "menuAnimationClose" : "menuAnimationOpen";
		refSideNav.style.left = refMenuButton.style.animationName === "menuAnimationOpen" ? 0 : refSideNav.clientWidth * -1 + "px"; 
	}
});