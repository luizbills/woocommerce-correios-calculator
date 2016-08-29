<?php
/**
 * Plugin Name:  Woocommerce Correios CEP Calculator for Product Page
 * Plugin URI:   https://github.com/luizbills/woocommerce-correios-cep-calculator-for-product-page
 * Description:  Includes a shipping calculator of Correios on product page.
 * Version:      0.1.0
 * Author:       Luiz Bills
 * Author URI:   http://luizp.com/
 * License:      MIT
 * Text Domain:  wcccpp
 * Domain Path:  /languages/
 */

function wcccpp_directory_uri() {
	return plugin_dir_url( __FILE__ );
}

require 'templates/cep-calculator.php';
require 'inc/plugin_functions.php';
require 'inc/plugin_hooks.php';
