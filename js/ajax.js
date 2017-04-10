$(document).ready( function () {

	function array_included(array,str) {
		for (var i = 0; i < array.length; i++) {
			if (str.includes(array[i])) {
				return true;
			}
		}
	}

	//Initialize the request variable to null
	request = null;
	$("#search_caption,#search_credit,.title_checked").bind("keyup change", function(){
		var request = $.ajax({
			type: 'GET',
			url: "ajax_sql.php",
			dataType: "json"
		});
		

		request.done(function(data) {
			if ($("#session").val()!=""){
				var session = true;
			}else{
				var session = false;
			}
			
			$search_caption_input = $("#search_caption").val();
			$search_credit_input = $("#search_credit").val();
			var search_caption_input=$search_caption_input;
			var search_credit_input=$search_credit_input;
			var images_displayed= [];

			for(var i = 0; i < data.length; i++){
				var rowi_caption = data[i]["caption"];
				var rowi_credit = data[i]["credit"];
				var rowi_path = data[i]["file_path"];
				var rowi_albums = data[i]["title"];
				var checked = [];
				$("input[name='checked_title[]']:checked").each(function (){
				    checked.push($(this).val());
				});

				var count=0;
				//if search caption input is empty, then all images caption count
				if (search_caption_input=="") {
					count++;
				}else{//if search caption has an input
					if ((rowi_caption.toLowerCase()).includes(search_caption_input.toLowerCase())) {
						count++;
					}
				}

				//if search credit input is empty, then all images credit count
				if (search_credit_input=="") {
					count++;
				}else{//if search credit has an input
					if ((rowi_credit.toLowerCase()).includes(search_credit_input.toLowerCase())) {
						count++;
					}
				}

				//if no album title checked, then all albums count
				if (checked.length == 0) {
					count++;
				}else if (array_included(checked,rowi_albums)) {
					count++;
				}

				if (count==3) {
					images_displayed.push(data[i]);
				}
			}

			//display image cells
			var image_cells="";
			for (var k = 0; k < images_displayed.length; k++) {
				var imagei_caption=images_displayed[k]["caption"];
				var imagei_credit=images_displayed[k]["credit"];
				var imagei_path=images_displayed[k]["file_path"];
				var imagei_albums=images_displayed[k]["title"];

				image_cells+='<div class=\"image_cell\">';
				image_cells+='<img src=\"'+imagei_path+' \"alt=\"'+imagei_caption+'\" class=\"thumbnail\">';
				image_cells+='<div class=\"img_info\">';
				image_cells+='<span>Caption: <span class=\"caption_span inline\">'+imagei_caption+'</span></span><br>';
				image_cells+='<span>Image from: <span class=\"credit_span inline\">'+imagei_credit+'</span></span><br>';
				image_cells+='<span>Image is in Album: <span class=\"album_span inline\">'+imagei_albums+'</span></span><br>';
				image_cells+='</div><!-- end of image_info div -->';
				if (session) {
					image_cells+='<span class=\"edit_button\" onclick=\"click_edit(\''+imagei_path+'\',\''+imagei_caption+'\',\''+imagei_credit+'\',\''+imagei_albums+'\')\">Edit</span>';
				}		
				image_cells+='</div><!-- end of image_cell div -->';
			}
			$("#images").html(image_cells);
		});

	});
} );