"use strict";
const refMenuButton = document.querySelector("#menuToggle");
const refSideNav = document.querySelector("#sidenav");
const refMarketNameEditIcon = document.querySelector("#marketNameEditIcon");
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
refMarketNameEditIcon.addEventListener("click", function() {
	
});