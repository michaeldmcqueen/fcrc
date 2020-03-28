<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !function_exists( 'tpt_fs' ) ) {
    // Create a helper function for easy SDK access.
    function tpt_fs()
    {
        global  $tpt_fs ;
        
        if ( !isset( $tpt_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $tpt_fs = fs_dynamic_init( array(
                'id'             => '3433',
                'slug'           => 'tier-pricing-table',
                'type'           => 'plugin',
                'public_key'     => 'pk_d9f80d20e4c964001b87a062cd2b7',
                'is_premium'     => false,
                'premium_suffix' => 'Premium',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 7,
                'is_require_payment' => true,
            ),
                'menu'           => array(
                'first-path' => 'admin.php?page=wc-settings&tab=tiered_pricing_table_settings',
                'contact'    => false,
                'support'    => false,
            ) ) );
        }
        
        return $tpt_fs;
    }
    
    // Init Freemius.
    tpt_fs();
    // Signal that SDK was initiated.
    do_action( 'tpt_fs_loaded' );
}
