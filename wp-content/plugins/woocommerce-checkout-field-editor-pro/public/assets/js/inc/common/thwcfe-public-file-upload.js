var thwcfe_public_file_upload = (function($, window, document) {
	'use strict';
	
	//var currRequest = null;
	var IMG_FILE_TYPES = ["image/jpg", "image/png", "image/gif", "image/jpeg"];
	
	function setup_file_upload(wrapper, data){
		wrapper.find('.thwcfe-checkout-file').on('change', upload_file);
        //wrapper.find('.thwcfe-delete-file').on('click', remove_uploaded);
	}
	
	function upload_file(event){
		var files = event.target.files;
		var parent = $("#" + event.target.id).parent();
		var wrapper = $(this).closest('.thwcfe-input-field-wrapper');
		var input = wrapper.find('.thwcfe-checkout-file-value');
		var field_name = input.attr('name');
		var data = new FormData();
		
		data.append("action", "thwcfe_file_upload");
		data.append("field_name", field_name);

		$.each(files, function(key, value){
			data.append("file", value);
		});

		$.ajax({
			type: 'POST',
			url: thwcfe_public_var.ajax_url,
			data: data,
			cache: false,
			dataType: 'json',
			processData: false, // Don't process the files
			contentType: false, // Set content type to false as jQuery will tell the server its a query string request
			beforeSend : function()    {           
				wrapper.find('.thwcfe-file-upload-status').show();
				input.val('');
				clear_message(wrapper);
			},
		})
		.done(function(data, textStatus, jqXHR){
			if(data.response == "SUCCESS"){
				var uploaded = data.uploaded;
				
				if(uploaded){
					var filenames_arr = [];
					var filenames = '';

					$(uploaded).each(function(index, uploaded_item){
					    var item_name = uploaded_item.name;
					    if(item_name && $.inArray(item_name, filenames_arr) == -1){
					    	filenames_arr.push(item_name);
					    }
					});

					if(filenames_arr.length){
						filenames = filenames_arr.toString();
					}

					input.val(JSON.stringify(uploaded));
					input.data('file-name', filenames);

					var remove_btn = wrapper.find('.thwcfe-remove-uploaded');
					remove_btn.data('file', uploaded.file);
					remove_btn.show();
					
					var prev_html = prepare_preview_html(uploaded);
					wrapper.find('.thwcfe-upload-preview').html(prev_html);
					
					wrapper.find('.thwcfe-uloaded-files').show();
					wrapper.find('.thwcfe-checkout-file').hide();

					input.trigger("change");
				}
				
				/*var preview = "";
				if( data.type === "image/jpg" || data.type === "image/png"
					|| data.type === "image/gif" || data.type === "image/jpeg") {
					preview = "<img style='width:3rem; height: auto' src='" + data.url + "' />";
				} else {
					preview = data.filename;
				}

				var previewID = parent.attr("id") + "_preview";
				var previewParent = $("#"+previewID);
				previewParent.show();
				previewParent.children(".ibenic_file_preview").empty().append( preview );
				previewParent.children( "button" ).attr("data-fileurl",data.url );
				parent.children("input").val("");
				parent.hide();*/

			} else {
				add_message(wrapper, data, "error");
				clean_file_input(wrapper);
			}
		})
		.fail(function(jqXHR, textStatus, error){
		    add_message(wrapper, data, "error");
		    clean_file_input(wrapper);
		})
		.always(function() {
		    wrapper.find('.thwcfe-file-upload-status').hide();
		});
	}

	function prepare_preview_html(uploaded){
		//var prev_html = uploaded.name;
		var file_size = '';
		if($.isNumeric(uploaded.size)){
			file_size = uploaded.size/1000;
			file_size = Math.round(file_size);
			file_size = file_size+' KB';
		}
		
		var prev_html  = '<span class="thwcfe-uloaded-file-list"><span class="thwcfe-uloaded-file-list-item">';
		prev_html += '<span class="thwcfe-columns">';
		
		if($.inArray(uploaded.type, IMG_FILE_TYPES) !== -1){
			prev_html += '<span class="thwcfe-column-thumbnail">';
			prev_html += '<img src="'+ uploaded.url +'" >';
			prev_html += '</span>';
		}

		prev_html += '<span class="thwcfe-column-title">';
		prev_html += '<span title="'+uploaded.name+'" class="title">'+uploaded.name+'</span>';
		if(file_size){
			prev_html += '<span class="size">'+file_size+'</span>';
		}
		prev_html += '</span>';

		prev_html += '<span class="thwcfe-column-actions">';
		prev_html += '<a href="#" onclick="thwcfeRemoveUploaded(this, event); return false;" class="thwcfe-action-btn thwcfe-remove-uploaded" title="Remove">X</a>';
		prev_html += '</span>';

		prev_html += '</span>';
		prev_html += '</span></span>';
		
		return prev_html;
	}

	function remove_uploaded(elm, event) {
		//var fileurl = $(event.target).attr("data-fileurl");
		var wrapper = $(elm).closest('.thwcfe-input-field-wrapper');
		var file = $(elm).data('file');
		
		var data = {
			action: 'thwcfe_remove_uploaded',
			file: file			 
		};

		$.ajax({
			type: 'POST',
			url: thwcfe_public_var.ajax_url,
			data: data,
			cache: false,
			dataType: 'json',
			beforeSend : function()    {           
				wrapper.find('.thwcfe-uloaded-files').hide();
				wrapper.find('.thwcfe-file-upload-status').show();
				clear_message(wrapper);
			},
		})
		.done(function(data, textStatus, jqXHR){
		    if(data.response == "SUCCESS"){
		    	$(elm).data('file', '');
				$(elm).hide();
				wrapper.find('.thwcfe-upload-preview').html('');
				wrapper.find('.thwcfe-uloaded-files').hide();
				wrapper.find('.thwcfe-checkout-file').show();

				clean_file_input(wrapper);
				//wrapper.find('.thwcfe-checkout-file').val('');
				//wrapper.find('.thwcfe-checkout-file-value').val('');

				//$("#ibenic_file_upload_preview").hide();
				//$("#ibenic_file_upload").show();
				add_message(wrapper, data, "success");
			}else if(data.response == "ERROR" ){
				add_message(wrapper, data, "error");
			}
		})
		.fail(function(jqXHR, textStatus, error){
			wrapper.find('.thwcfe-uloaded-files').show();
		    add_message(wrapper, error, "error");
		})
		.always(function() {
		    wrapper.find('.thwcfe-file-upload-status').hide();
		});
	}

	function change_uploaded(elm, event){
		var wrapper = $(elm).closest('.thwcfe-input-field-wrapper');

		wrapper.find('.thwcfe-remove-uploaded').hide();
		wrapper.find('.thwcfe-input-file').show();
	}
	function cancel_change_uploaded(elm, event){
		var wrapper = $(elm).closest('.thwcfe-input-field-wrapper');

		wrapper.find('.thwcfe-remove-uploaded').show();
		wrapper.find('.thwcfe-cancel-change').show();
		wrapper.find('.thwcfe-input-file').hide();
	}

	function clean_file_input(wrapper){
		var input = wrapper.find('.thwcfe-checkout-file-value');

		wrapper.find('.thwcfe-checkout-file').val('');
		input.val('');
		input.data('file-name', '');
		input.trigger("change");
	}

	function add_message(wrapper, data, type){
		//add_message( "File successfully deleted", "success");
		//alert(msg.error);
		if(data.response && data.error){
			wrapper.find('.thwcfe-file-upload-msg').html(data.error);
			wrapper.find('.thwcfe-file-upload-msg').show();
		}else{
			clear_message(wrapper);
		}
	}

	function clear_message(wrapper){
		wrapper.find('.thwcfe-file-upload-msg').html('');
		wrapper.find('.thwcfe-file-upload-msg').hide();
	}
	
	return {
		setup_file_upload : setup_file_upload,
		remove_uploaded : remove_uploaded,
		change_uploaded : change_uploaded,
		prepare_preview_html : prepare_preview_html,
		clean_file_input : clean_file_input,
	};
}(window.jQuery, window, document));

function thwcfeRemoveUploaded(elm, event){
	thwcfe_public_file_upload.remove_uploaded(elm, event);
}

function thwcfeChangeUploaded(elm, event){
	thwcfe_public_file_upload.change_uploaded(elm, event);
}
