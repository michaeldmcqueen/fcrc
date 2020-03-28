(function($) {
	
	$( function() {
		// Toggle Product details box
		$("#rwpp-container .menu-item-handle .dashicons").click(function (e) { 
			e.preventDefault();
			$(this).parent().parent().find(".product-details-box").slideToggle();
		});
		
		// Make list sortable
		$( ".sortable" ).sortable({
			update: function( event, ui ) {
				update_all_index();
			}
		});
		$( ".sortable" ).disableSelection();

		// Ajax submit form
		$("#frm_rwpp").submit(function(e){
			e.preventDefault();
			
			$("#btn_save_rwpp").attr('disabled', 'true');
			$(".submit-btn-wrapper .spinner").addClass('show');

			$.ajax({
				type: "POST",
				url: ajaxurl,
				data: $('#frm_rwpp').serialize(),
				success: function (response) {
					$(".response").html(response);

					$(".submit-btn-wrapper .spinner").removeClass('show');
					$("#btn_save_rwpp").removeAttr('disabled');
				}
			});
		});

		// update all product indexes
		function update_all_index(){
			$(".sortable li").each(function (index, element) {
				$(this).find('input[name="new_menu_order[]"]').val(index);
			});
		}
	} );

})(jQuery);