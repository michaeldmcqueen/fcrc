/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
    $(document).ready(function(){
    
    $(".membership-status:contains('Expired')" ).css( "color", "#c10808" );
    $(".membership-status:contains('Active')" ).css( "color", "#2ECF62" );

    var membershipStatus = $(".membership-status").text();

        if ($('.membership-status:contains("Expired")').length > 0) {
            $('.my_account_memberships').after('<div role="alert"><div class="bg-red500 text-white font-bold rounded-t px-4 py-2">Membership Has Expired</div><div class="border border-t-0 border-red400 rounded-b bg-red100 px-4 py-3 text-red700"><p><a class="text-red700" href="/memberships">Renew your Membership</a></p></div></div>');
        }
        // if ($('.membership-status:contains("Active")').length > 0) {
        //     $('.my_account_memberships').after('<div role="alert"><div class="bg-red500 text-white font-bold rounded-t px-4 py-2">Membership Has Expired</div><div class="border border-t-0 border-red400 rounded-b bg-red100 px-4 py-3 text-red700"><p><a class="text-red700" href="/memberships">Renew your Membership</a></p></div></div>');
        // }
    

    // Woocommerce Family Membership Fields
    // If logged in fill out Email and Name fields for first rows and set to disbaled
    if ($('.logged-in.woocommerce-checkout').length > 0) {
        var familyHeadfName = $('#billing_first_name').val();
        var familyHeadlName = $('#billing_last_name').val();        
        var familyHeadName = familyHeadfName + ' ' + familyHeadlName;
        var familyHeadEmail = $('#billing_email').val();

        $('#family_accounts_name').val(familyHeadName);
        $('#family_accounts_name').addClass( "disabled");

        $('#family_accounts_email').val(familyHeadEmail);
        $('#family_accounts_email').addClass( "disabled");
    };

    });

} )( jQuery );
