<?php

function test_add_shipping_calculator() {
	echo wcccpp_get_calculator_template();
}

function wcccpp_ajax_callback() {
	$url = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx';
	$data = array();

	$data['nCdEmpresa'] = '';
	$data['sDsSenha'] = '';
	$data['sCepOrigem'] = '[CEP DE ORIGEM AQUI]';
	$data['sCepDestino'] = $_POST['sCepDestino'];
	$data['nVlPeso'] = '1';
	$data['nCdFormato'] = '1';
	$data['nVlComprimento'] = '16';
	$data['nVlAltura'] = '2';
	$data['nVlLargura'] = '11';
	$data['nVlDiametro'] = '0';
	$data['sCdMaoPropria'] = 'n';
	$data['nVlValorDeclarado'] = '0';
	$data['sCdAvisoRecebimento'] = 'n';
	$data['StrRetorno'] = 'xml';
	$data['nCdServico'] = '40010,41106'; // Sedex e Pac

	$data = http_build_query($data);

	$curl = curl_init($url . '?' . $data);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$result = curl_exec( $curl );
	$result_xml = simplexml_load_string( $result );

	wp_send_json_success( $result_xml );
	wp_die();
}

function wcccpp_enqueue_script() {
	wp_enqueue_script( 'wcccpp-calculator', plugin_dir_url( __FILE__ ) . 'assets/js/wcccpp-calculator.js', array(), '0.0.1', true );
}
