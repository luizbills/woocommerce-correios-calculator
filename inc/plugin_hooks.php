<?php
add_action( 'woocommerce_single_product_summary', 'test_add_shipping_calculator', 30 );
//add_action( 'woocommerce_single_product_summary', 'wcccpp_ajax_callback', 30 );

add_action( 'wp_ajax_wcccpp_ajax', 'wcccpp_ajax_callback' );
add_action( 'wp_ajax_nopriv_wcccpp_ajax', 'wcccpp_ajax_callback' );

add_action( 'wp_enqueue_scripts', 'wcccpp_enqueue_script' );
