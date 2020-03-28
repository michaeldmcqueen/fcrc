var thwcfe_public_price = (function($, window, document) {
	'use strict';
	
	var currRequest = null;

	function is_active_price_field(elm){
		var result = true;

		if(elm.hasClass("thwcfe-disabled-field") || elm.hasClass("thwcfe-disabled-shipping-field")){
			result = false;
		}
		return result;
	}

	function is_multiselect_field(elm, type){
		var multiple = 0;

		if(type == 'checkbox'){
			multiple = elm.data("multiple");
		}else if(type == "select"){
			if(elm.attr("multiple")){
				multiple = 1;
			}
		}
		return multiple;
	}

	function get_price_field_value(elm, type, name){
		var value = thwcfe_public_base.get_field_value(type, elm, name);

		if(type === 'radio'){
			value = elm.is(':checked') ? value : '';

		}else if(type === 'file'){
			value = elm.data('file-name');
			value = value ? value : '';
		}
		return value;
	}
	
	function setup_price_fields(wrapper, data){
		wrapper.find('.thwcfe-price-field').off("change", thwcfe_public_conditions.conditional_field_value_change_listner);
		wrapper.find('.thwcfe-price-field').on("change", thwcfe_public_conditions.conditional_field_value_change_listner);
	}

	function get_selected_options(elm, name, type, multiple){
		var options = null;
		
		if(type === "select"){
			options = elm.find('option:selected');

		}else if(type === "radio"){
			if(elm.is(':checked')){
				options = elm; //$("input[type=radio][name='"+name+"']:checked");
			}

		}else if(type === "checkbox" && multiple){
			options = $("input[type=checkbox][name='"+name+"[]']:checked");
		}
		return options;
	}

	function prepare_price_props_for_selected_options(elm, name, type, multiple){
		var price_props = null;
		var oPrice = '';
		var oPriceType = '';
		var options = get_selected_options(elm, name, type, multiple);

		if(options){
			if(multiple){
				options.each(function(){
					var oprice = $(this).data('price');
					var opriceType = $(this).data('price-type');	

					if(oprice){
						opriceType = opriceType ? opriceType : 'normal';
						
						if(oPrice.trim()){
							oPrice += ',';
						}
						
						if(oPriceType.trim()){
							oPriceType += ',';
						}
						
						oPrice += oprice;
						oPriceType += opriceType;
					}
				});
			}else{
				oPrice = options.data('price');
				oPriceType = options.data('price-type');
				oPriceType = oPriceType ? oPriceType : 'normal';
			}

			if(!thwcfe_public_base.isEmpty(oPrice) && !thwcfe_public_base.isEmpty(oPriceType)){
				price_props = {
					'price' : oPrice,
					'priceType' : oPriceType
				}
			}
		}
		return price_props; 
	}
		
	function calculate_extra_cost(){
		var price_field_elms = $('.thwcfe-price-field');

		if(price_field_elms.length > 0){
			var priceInfoArr = {};

			price_field_elms.each(function(){
				var pfield = $(this);

				if(is_active_price_field(pfield)){
					var ftype = pfield.getType();
					if(pfield.hasClass("thwcfe-checkout-file-value")){
						ftype = 'file';
					}

					var multiple  = is_multiselect_field(pfield, ftype);
					var id        = pfield.prop("id");
					var name      = thwcfe_public_base.get_field_name(ftype, pfield.prop("name"), id, multiple)
					var value     = get_price_field_value(pfield, ftype, name);
					var valueText = value;
					var qtyField  = '';
					var label     = pfield.data("price-label");
					var price     = pfield.data("price");		
					var priceType = pfield.data("price-type");
					var priceUnit = pfield.data("price-unit");
					var taxable   = pfield.data("taxable");
					var tax_class = pfield.data("tax_class");

					if(thwcfe_public_base.isInputChoiceField(ftype, multiple)){
						var price_props = prepare_price_props_for_selected_options(pfield, name, ftype, multiple);
						if(price_props){
							price = price_props['price'];
							priceType = price_props['priceType'];
						}else{
							price = 0;
							priceType = '';
						}
					}
					/*if(ftype === 'checkbox' && multiple){
						var price_props = prepare_price_props_for_multiple_option_field(pfield, name, ftype);
						if(price_props){
							price = price_props['price'];
							priceType = price_props['priceType'];
						}
					}*/
					
					if(priceType && priceType === 'dynamic'){
						if(!$.isNumeric(priceUnit) && $('#'+priceUnit).length){
							qtyField = $('#'+priceUnit).val();
							priceUnit = 1;
						}
					}else{
						priceUnit = 0;
					}
					
					if(value && name && (price || (priceType && priceType === 'custom'))){
						var priceInfo = {};
						priceInfo['name'] = name;
						//priceInfo['label'] = label+' ('+valueText+')';
						priceInfo['label'] = label;
						priceInfo['price'] = price;
						priceInfo['price_type'] = priceType;
						priceInfo['price_unit'] = priceUnit;
						priceInfo['value'] = value;
						priceInfo['qty_field'] = qtyField;
						priceInfo['taxable'] = taxable;
						priceInfo['tax_class'] = tax_class;
						priceInfo['multiple'] = multiple;
						
						priceInfoArr[name] = priceInfo;
					}
				}
			});
			
			var data = {
	            action: 'thwcfe_calculate_extra_cost',
				price_info: JSON.stringify(priceInfoArr)
	        };

	        currRequest = $.ajax({
	            type: 'POST',
	            url : thwcfe_public_var.ajax_url,
	            data: data,
				beforeSend : function(jqxhr){
					//console.log('Before price calculation...'); 
					if(currRequest != null) {
						currRequest.abort();

						if(currRequest.uid){
							this.data = this.data+'&abort_req='+currRequest.uid;
						}
					}

					jqxhr.uid = thwcfe_public_base.uniqueId(16);
					this.data = this.data+'&uid='+jqxhr.uid;
				},
	            success: function(code){
	            	//console.log('Price calculated...');
	            	$('body').trigger('update_checkout');
	            }
	        });
	    }
	}

	function may_calculate_extra_cost(){
		calculate_extra_cost();
	}
	
	return {
		setup_price_fields : setup_price_fields,
		may_calculate_extra_cost : may_calculate_extra_cost,
	};
}(window.jQuery, window, document));
