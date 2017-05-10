<?php
/**
 * Plugin Name:	Woocommerce Correios CEP Calculator for Product Page
 * Plugin URI:	https://github.com/luizbills/woocommerce-correios-cep-calculator-for-product-page
 * Description:	Includes a shipping calculator of Correios on product page.
 * Version:		0.2.1
 * Author:		Luiz Bills
 * Author URI:	http://luizp.com/
 * License:		MIT
 * Text Domain:	wcccpp
 * Domain Path:	/languages/
 */

function wcccpp_directory_uri() {
	return plugin_dir_url( __FILE__ );
}

function wcccpp_settings() {
	$settings = array(
		'global_format' => 1,
		'global_declare_value' => true,
		'global_receipt_notice' => 'N',
		'global_own_hands' => 'N',

		'global_default_weight' => 1,
		'global_default_width' => 11,
		'global_default_height' => 2,
		'global_default_length' => 16,
		'global_default_diameter' => 0,

		'global_login' => '',
		'global_password' => '',

		'methods' => array()
	);

	// PAC
	array_push( $settings['methods'], array(
		'service_code' => '04510',
		'title' => 'PAC',
		'origin_postcode' => $settings['global_origin_postcode'],

		'additional_time' => $settings['global_additional_time'],
		'fee' => $settings['global_fee'],
		'format' => $settings['global_format'],
		'declare_value' => $settings['global_declare_value'],
		'receipt_notice' => $settings['global_receipt_notice'],
		'own_hands' => $settings['global_own_hands'],

		'default_weight' => $settings['global_default_weight'],
		'default_width' => $settings['global_default_width'],
		'default_height' => $settings['global_default_height'],
		'default_length' => $settings['global_default_length'],
		'default_diameter' => $settings['global_default_diameter'],

		'login' => $settings['global_login'],
		'password' => $settings['global_password'],
	) );

	// SEDEX
	array_push( $settings['methods'], array(
		'service_code' => '04014',
		'title' => 'SEDEX',
		'origin_postcode' => $settings['global_origin_postcode'],

		'additional_time' => $settings['global_additional_time'],
		'fee' => $settings['global_fee'],
		'format' => $settings['global_format'],
		'declare_value' => $settings['global_declare_value'],
		'receipt_notice' => $settings['global_receipt_notice'],
		'own_hands' => $settings['global_own_hands'],

		'default_weight' => $settings['global_default_weight'],
		'default_width' => $settings['global_default_width'],
		'default_height' => $settings['global_default_height'],
		'default_length' => $settings['global_default_length'],
		'default_diameter' => $settings['global_default_diameter'],

		'login' => $settings['global_login'],
		'password' => $settings['global_password'],
	) );

	return $settings;
}

require 'templates/cep-calculator.php';
require 'inc/plugin_functions.php';
require 'inc/plugin_hooks.php';
