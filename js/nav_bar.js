
$(document).ready(function () {
	"use strict";

	var title = document.title;
	if (title.includes("Homepage")) {
		$("#all_albums_link").addClass("current_page");
	}
	if (title.includes("Images")) {
		$("#all_images_link").addClass("current_page");
	}
	if (title.includes("Add Album")) {
		$("#add_album_link").addClass("current_page");
	}
	if (title.includes("Add Image")) {
		$("#add_image_link").addClass("current_page");
	}
});