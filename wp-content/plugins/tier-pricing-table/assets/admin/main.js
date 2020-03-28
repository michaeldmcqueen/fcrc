jQuery(document).ready(tierPricingTable);
jQuery(document).on('woocommerce_variations_loaded', tierPricingTable);

function tierPricingTable($) {

    var addNewButton = jQuery('[data-add-new-price-rule]');

    addNewButton.on('click', function (e) {
        e.preventDefault();

        var newRuleInputs = jQuery(e.target).parent().find('[data-price-rules-input-wrapper]').first().clone();

        jQuery('<span data-price-rules-container></span>').insertBefore(jQuery(e.target))
            .append(newRuleInputs)
            .append('<span class="notice-dismiss remove-price-rule" data-remove-price-rule style="vertical-align: middle"></span>')
            .append('<br><br>');

        newRuleInputs.children('input').val('');
    });

    jQuery('body').on('click', '.remove-price-rule', function (e) {
        e.preventDefault();

        var element = jQuery(e.target.parentElement);
        var wrapper = element.parent('[data-price-rules-wrapper]');
        var containers = wrapper.find('[data-price-rules-container]');

        if ((containers.length) < 2) {
            containers.find('input').val('');
            return;
        }

        jQuery('.wc_input_price').trigger('change');

        element.remove();
    });
}
