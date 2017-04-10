function click_edit(path,caption,credit,album) {
	$(".modal_edit_image").css("display","block");
	var album_array= album.split(",");
	$(".modal_image_to_edit").attr('src',path);
	$("#old_pic").val(path);
	$("#edit_caption").val(caption);
	$("#edit_credit").val(credit); 
	//uncheck every checkbox
	$("input:checkbox[name='edit_title[]']").each(function() {
		$(this).prop('checked', false);
	});
	//and check box that the image is in
	for (var i = 0; i < album_array.length; i++) {
		$("input:checkbox[name='edit_title[]']").each(function() {
			if ($(this).val() == album_array[i]){
				$(this).prop('checked', true);
			}
		});
	}
}

function click_edit_album(title,style,images_in_album) {
	$(".modal_edit_album").css("display","block");
	$("#edit_title").val(title);
	$("#edit_style").val(style); 
	var images_in_album_array=images_in_album.split(" ");
	for (var i = 0; i < images_in_album_array.length; i++) {
		$(".checkbox_image").each(function() {
			if ($(this).attr('src') == images_in_album_array[i]){
				$(this).click();
			}
		});
	}
}

$(document).ready(function () {
	"use strict";
	var image_info = $(this).siblings(".img_info");
	var caption= image_info.find(".caption_span").text();
	var credit= image_info.find(".credit_span").text();
	var album= image_info.find(".album_span").text();
	//modal to enlarge image
	$(document).on("click",".thumbnail",function() {
		$(".modal_thumbnail").css("display", "block");
		var source= $(this).attr('src');
		var image_info = $(this).siblings(".img_info");
		var caption= image_info.find(".caption_span").text();
		var credit= image_info.find(".credit_span").text();
		var album= image_info.find(".album_span").text();
		
		$(".modal_image").attr('src',source);
		$(".modal_caption").text("Caption: "+caption);
		$(".modal_credit").text("Image is from: "+credit);
		$(".modal_album").text("Image is in Album: "+album);
	});

	$(".exit").on("click",function() {
		$(".modal").css("display", "none");
	});

	//modal to edit image

	$(".exit").on("click",function() {
		$(".modal").css("display", "none");
	});

	$(".submit_edit_button").on("click",function() {
		$(".modal").css("display", "none");
	});

	//trigger file upload file when clicking on the image
	$(".modal_image_to_edit").on("click",function() {
		$("#edit_image_upload").click();
	});

	//if a new image file is selected, add a border to the image
	$("#edit_image_upload").on("change",function (){
       $(".modal_image_to_edit").css("border-style","double");
     });
	
	//delete album button is clicked, ask again
	$("#delete_album_button").on("click",function() {
		$(".confirmation").css("display","block");
	});

	//delete image button is clicked, ask again
	$("#delete_image_button").on("click",function() {
		$(".confirmation").css("display","block");
	});

	$("input:submit[name='cancel']").on("click",function() {
		$(".modal").css("display", "none");
	});






});