var thwcfe_public_conditions = (function($, window, document) {
	'use strict';
	
	function hide_section(celm, validations, needSetup){
		celm.hide();
		
		var sid = celm.prop('id');
		celm.addClass('thwcfe-disabled-section');
		
		if(celm.find('.thwcfe-input-field-wrapper').length){
			celm.find('.thwcfe-input-field-wrapper').each(function(){
			  	//$(this).find('.thwcfe-input-field').removeAttr("required");
				var validations = $(this).data("validations");
				disable_field_validations($(this), validations);
			});
		}
		
		var disabled_snames = $('#thwcfe_disabled_sections').val();
		var disabled_snames_x = disabled_snames ? disabled_snames.split(",") : [];
		
		disabled_snames_x.push(sid); 
		disabled_snames = disabled_snames_x.toString();
		
		$('#thwcfe_disabled_sections').val(disabled_snames);

		var trigger_price_calc = false; //needSetup ? false : true;
		enable_disable_price_fields(celm, false, trigger_price_calc);
	}
	
	function show_section(celm, validations, needSetup){
		celm.show();
		
		var sid = celm.prop('id');
		celm.removeClass('thwcfe-disabled-section');
		
		if(celm.find('.thwcfe-input-field-wrapper').length){
			celm.find('.thwcfe-input-field-wrapper:not(.thwcfe-disabled-field-wrapper)').each(function(){
			  	//$(this).find('.thwcfe-input-field').attr("required", true);
				var validations = $(this).data("validations");
				disable_field_validations($(this), validations);
			});
		}
		
		var disabled_snames = $('#thwcfe_disabled_sections').val();
		var disabled_snames_x = disabled_snames ? disabled_snames.split(",") : [];
		
		disabled_snames_x = jQuery.grep(disabled_snames_x, function(value) {
		  	return value != sid; 
		}); 
		disabled_snames = disabled_snames_x.toString();
		
		$('#thwcfe_disabled_sections').val(disabled_snames);

		var trigger_price_calc = false; //needSetup ? false : true;
		enable_disable_price_fields(celm, true, trigger_price_calc);
	}
	
	function hide_field(cfield, validations, needSetup, ship_to_diff_addr_trgr){
		var fid = '';

		if(cfield.hasClass('thwcfe-html-field-wrapper')){
			cfield.hide();
			fid = cfield.data('name');
			
		}else{
			var cinput = cfield.find(":input.thwcfe-input-field");
			if(cfield.getType() === 'hidden'){
				cinput = cfield;
			}

			if(cinput.hasClass('thwcfe-disabled-field') && !cinput.is(":visible")){
				return;
			}

			var ftype = cinput.getType();
			fid = cinput.prop('id');

			if(ftype == "radio"){
				fid = cinput.prop('name');
			}else if(ftype == "checkbox"){
			    fid = cinput.prop('name');
			    fid = fid.replace("[]", "");   
			}
			
			cinput.data('current-value', thwcfe_public_base.get_field_value(ftype, cinput, fid));
			cfield.hide();	
			thwcfe_public_base.set_field_value_by_elm(cinput, ftype, '');
			cinput.addClass('thwcfe-disabled-field');
			cfield.addClass('thwcfe-disabled-field-wrapper');

			var change_event_disabled_fields = thwcfe_public_var.change_event_disabled_fields;
			var change_e_disabled_fields = change_event_disabled_fields ? change_event_disabled_fields.split(",") : [];
			if($.inArray(fid, change_e_disabled_fields) === -1){
				//cinput.change();
				cinput.trigger('change', [{mt:true}]);
			}
			
			if(ftype == "E001" && cfield.attr('data-name')){
				fid = cfield.data('name');
			}

			disable_field_validations(cfield, validations);
		}
		
		if(fid && !skip_marking_as_disabled_field(fid, ship_to_diff_addr_trgr)){
			var disabled_fnames = $('#thwcfe_disabled_fields').val();
			var disabled_fnames_x = disabled_fnames ? disabled_fnames.split(",") : [];
			
			disabled_fnames_x.push(fid); 
			disabled_fnames = disabled_fnames_x.toString();
			
			$('#thwcfe_disabled_fields').val(disabled_fnames);
		}
	}

	function show_field(cfield, validations, needSetup){
		var fid = '';

		if(cfield.hasClass('thwcfe-html-field-wrapper')){
			cfield.show();
			fid = cfield.data('name');

		}else{
			//var cinput = cfield.find(":input");
			var cinput = cfield.find(":input.thwcfe-input-field");
			if(cfield.getType() === 'hidden'){
				cinput = cfield;
			}

			if(!cinput.hasClass('thwcfe-disabled-field')){
				return;
			}

			var ftype = cinput.getType();
			fid = cinput.prop('id');

			if(ftype == "radio"){
				fid = cinput.prop('name');
			}else if(ftype == "checkbox"){
			    fid = cinput.prop('name');
			    fid = fid.replace("[]", "");   
			}
		
			cfield.show();	
			var fval = cinput.data('current-value');
			if(fval){
				thwcfe_public_base.set_field_value_by_elm(cinput, ftype, fval);
			}
			cfield.removeClass('thwcfe-disabled-field-wrapper');
			cinput.removeClass('thwcfe-disabled-field');

			var change_event_disabled_fields = thwcfe_public_var.change_event_disabled_fields;
			var change_e_disabled_fields = change_event_disabled_fields ? change_event_disabled_fields.split(",") : [];
			if($.inArray(fid, change_e_disabled_fields) === -1){
				//cinput.change();
				cinput.trigger('change', [{mt:true}]);
			}
			//cfield.find(":input").val('');

			if(ftype == "E001" && cfield.attr('data-name')){
				fid = cfield.data('name');
			}

			enable_field_validations(cfield, validations);
		}

		if(fid){
			var disabled_fnames = $('#thwcfe_disabled_fields').val();
			var disabled_fnames_x = disabled_fnames ? disabled_fnames.split(",") : [];
			
			disabled_fnames_x = jQuery.grep(disabled_fnames_x, function(value) {
			  	return value != fid; 
			});
			
			disabled_fnames = disabled_fnames_x.toString();
			
			$('#thwcfe_disabled_fields').val(disabled_fnames);
		}
	}
	
	function hide_elm(elm, validations, needSetup){
		var elmType = elm.data("rules-elm");
		if(elmType === 'section'){
			hide_section(elm, validations, needSetup);
		}else{
			hide_field(elm, validations, needSetup, false);
		}
	}
	
	function show_elm(elm, validations, needSetup){
		var elmType = elm.data("rules-elm");
		if(elmType === 'section'){
			show_section(elm, validations, needSetup);
		}else{
			show_field(elm, validations, needSetup);
		}
	}

	function disable_field_validations(elm, validations){
		if(validations) {
			elm.removeClass(validations);
			elm.removeClass('woocommerce-validated woocommerce-invalid woocommerce-invalid-required-field');
		}
	}
	
	function enable_field_validations(elm, validations){
		elm.removeClass('woocommerce-validated woocommerce-invalid woocommerce-invalid-required-field');
		if(validations) {
			elm.addClass(validations);
		}	
	}
	
	function enable_disable_price_fields(wrapper, enable, trigger_price_calc){
		var price_fields = wrapper.find('.thwcfe-price-field');

		if(price_fields.length){
			if(enable){
				price_fields.removeClass('thwcfe-disabled-shipping-field');
			}else{
				price_fields.addClass('thwcfe-disabled-shipping-field');
			}

			if(trigger_price_calc){
				thwcfe_public_price.may_calculate_extra_cost();
			}
		}
	}

	function enable_disable_fields(wrapper, enable, ship_to_diff_addr_trgr){
		wrapper.find('.thwcfe-input-field-wrapper').each(function(){
			var cfield = $(this);
			var validations = cfield.data("validations");

			if(enable){
				show_field(cfield, validations, false);
			}else{
				hide_field(cfield, validations, false, ship_to_diff_addr_trgr);
			}								 
		});
	}
	
	function validate_condition(condition, valid, needSetup, cfield){
		if(condition){
			var operand_type = condition.operand_type;
			var operand = condition.operand;
			var operator = condition.operator;
			var cvalue = condition.value;
			
			if(operand_type === 'field' && operand){
				jQuery.each(operand, function() {
					var field = thwcfe_public_base.getInputField(this);
					
					if(thwcfe_public_base.isInputField(field)){
						var ftype = field.getType();
						var value = thwcfe_public_base.get_field_value(ftype, field, this);

						if(operator === 'empty' && value != ''){
							valid = false;
							
						}else if(operator === 'not_empty' && value == ''){
							valid = false;
							
						}else if(operator === 'value_eq' && value != cvalue){
							valid = false;
							
						}else if(operator === 'value_ne' && value == cvalue){
							valid = false;
							
						}else if(operator === 'value_in'){
							var value_arr = [];
							var cvalue_arr = [];

							if(value){
								value_arr = $.isArray(value) ? value : value.split(',');
							}
							if(cvalue){
								cvalue_arr = $.isArray(cvalue) ? cvalue : cvalue.split(',');
							}
							
							if(thwcfe_public_base.is_empty_arr(value_arr) || !thwcfe_public_base.is_subset_of(cvalue_arr, value_arr)){
								valid = false;
							}
							
						}else if(operator === 'value_cn'){
							var value_arr = [];
							var cvalue_arr = [];

							if(value){
								value_arr = $.isArray(value) ? value : value.split(',');
							}
							if(cvalue){
								cvalue_arr = $.isArray(cvalue) ? cvalue : cvalue.split(',');
							}
							
							if(!thwcfe_public_base.is_subset_of(value_arr, cvalue_arr)){
								valid = false;
							}
							
						}else if(operator === 'value_nc'){
							var value_arr = [];
							var cvalue_arr = [];

							if(value){
								value_arr = $.isArray(value) ? value : value.split(',');
							}
							if(cvalue){
								cvalue_arr = $.isArray(cvalue) ? cvalue : cvalue.split(',');
							}

							var intersection = thwcfe_public_base.array_intersection(cvalue_arr, value_arr);
							if(!thwcfe_public_base.is_empty_arr(intersection)){
								valid = false;
							}
							
						}else if(operator === 'value_gt'){
							if($.isNumeric(value) && $.isNumeric(cvalue)){
								valid = (Number(value) <= Number(cvalue)) ? false : valid;
							}else{
								valid = false;
							}
							
						}else if(operator === 'value_le'){
							if($.isNumeric(value) && $.isNumeric(cvalue)){
								valid = (Number(value) >= Number(cvalue)) ? false : valid;
							}else{
								valid = false;
							}
							
						}else if(operator === 'value_sw' && !value.startsWith(cvalue)){
							valid = false;

						}else if(operator === 'value_nsw' && value.startsWith(cvalue)){
							valid = false;

						}else if(operator === 'date_eq' && !thwcfe_public_base.is_date_eq(field, cvalue)){
							valid = false;
							
						}else if(operator === 'date_ne' && thwcfe_public_base.is_date_eq(field, cvalue)){
							valid = false;
							
						}else if(operator === 'date_gt' && !thwcfe_public_base.is_date_gt(field, cvalue)){
							valid = false;
							
						}else if(operator === 'date_lt' && !thwcfe_public_base.is_date_lt(field, cvalue)){
							valid = false;
							
						}else if(operator === 'day_eq' && !thwcfe_public_base.is_day_eq(field, cvalue)){
							valid = false;
							
						}else if(operator === 'day_ne' && thwcfe_public_base.is_day_eq(field, cvalue)){
							valid = false;
							
						}else if(operator === 'checked'){
							var checked = field.prop('checked');
							valid = checked ? valid : false;
							
						}else if(operator === 'not_checked'){
							var checked = field.prop('checked');
							valid = checked ? false : valid;

						}else if(operator === 'regex'){
							if(cvalue){
								var regex = new RegExp(cvalue);
								if(!regex.test(value)){
									valid = false;
								}
							}
						}
						
						if(needSetup){
							var depFields = field.data("fields");

							if(depFields){
								var depFieldsArr = depFields.split(",");
								depFieldsArr.push(cfield.prop('id'));
								depFields = depFieldsArr.toString();
							}else{
								depFields = cfield.prop('id');
							}

							field.data("fields", depFields);
							add_field_value_change_handler(field);
						}
					}
				});
			}
		}
		return valid;
	}

	function validate_field_condition(cfield, needSetup){
		var conditionalRules = cfield.data("rules");	
		var conditionalRulesAction = cfield.data("rules-action");
		var validations = cfield.data("validations");
		var valid = true;
		
		if(conditionalRules){
			try{
				jQuery.each(conditionalRules, function() {
					var ruleSet = this;	
					
					jQuery.each(ruleSet, function() {
						var rule = this;
						var validRS = false;
						
						jQuery.each(rule, function() {
							var conditions = this;								   	
							var validCS = true;
							
							jQuery.each(conditions, function() {
								validCS = validate_condition(this, validCS, needSetup, cfield);
							});
							
							validRS = validRS || validCS;
						});
						valid = valid && validRS;
					});
				});
			}catch(err) {
				alert(err);
			}
			
			if(conditionalRulesAction === 'hide'){
				if(valid){
					hide_elm(cfield, validations, needSetup);
				}else{
					show_elm(cfield, validations, needSetup);
				}
			}else{
				if(valid){
					show_elm(cfield, validations, needSetup);
				}else{
					hide_elm(cfield, validations, needSetup);
				}	
			}
		}
	}
	
	function conditional_field_value_change_listner(event, data){
	    var depFields = $(this).data("fields");

	    if(depFields){
	    	var depFieldsArr = depFields.split(",");

	    	if(depFieldsArr && depFieldsArr.length > 0){
	    		depFieldsArr = thwcfe_public_base.remove_duplicates(depFieldsArr);

				jQuery.each(depFieldsArr, function() {
					if(this.length > 0){	
						var cfield = $('#'+this);
						validate_field_condition(cfield, false);	
					}
				});
			}
	    }

		/*var ftype = $(this).getType();
		if(ftype == "select" || ftype == "radio"){
			thwcfe_public_price.prepare_extra_cost_from_selected_option($(this), ftype);
		}*/

		if(!(data && data.mt)){
			thwcfe_public_price.may_calculate_extra_cost();
		}
	}
	
	function add_field_value_change_handler(field){
		field.off("change", conditional_field_value_change_listner);
		field.on("change", conditional_field_value_change_listner);
	}

	function validate_all_conditions(wrapper){
		if(wrapper){
			wrapper.find('.thwcfe-conditional-field').each(function(){
				validate_field_condition($(this), true);								 
			});
			wrapper.find('.thwcfe-conditional-section').each(function(){
				validate_field_condition($(this), true);								 
			});
		}else{
			$('.thwcfe-conditional-field').each(function(){
				validate_field_condition($(this), true);										 
			});
			$('.thwcfe-conditional-section').each(function(){
				validate_field_condition($(this), true);										 
			});
		}
	}

	function prepare_shipping_conitional_fields(elm, trigger_price_calc){
		var ship_to_different_address_elm = $('#ship-to-different-address-checkbox');
		var shipping_wrapper = $('.woocommerce-shipping-fields');
		var ship_to_different_address = ship_to_different_address_elm.is(':checked');

		enable_disable_fields(shipping_wrapper, ship_to_different_address, true);
		enable_disable_price_fields(shipping_wrapper, ship_to_different_address, trigger_price_calc);
		
		if(ship_to_different_address){
			validate_all_conditions(shipping_wrapper);
		}
	}

	function skip_marking_as_disabled_field(name, ship_to_diff_addr_trgr){
		var def_shipping_fields = ['shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_postcode'];
		if(ship_to_diff_addr_trgr && def_shipping_fields.includes(name)){
			return true;
		}
		return false;
	}
	
	return {
		validate_field_condition : validate_field_condition,
		validate_all_conditions : validate_all_conditions,
		prepare_shipping_conitional_fields : prepare_shipping_conitional_fields,
		conditional_field_value_change_listner : conditional_field_value_change_listner,
	};
}(window.jQuery, window, document));
