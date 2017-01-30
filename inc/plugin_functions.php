<?php

function test_add_shipping_calculator() {
	echo wcccpp_get_calculator_template();
}

function wcccpp_sanitize_postcode( $postcode ) {
	return preg_replace( '([^0-9])', '', sanitize_text_field( $postcode ) );
}

function wcccpp_format_price( $price, $fee ) {
	$price = str_replace( '.', '', $price );
	$price = str_replace( ',', '.', $price );
	
	return floatval( $price ) + floatval( $fee );
}

function wcccpp_safe_load_xml( $source, $options = 0 ) {
	$old = null;
	if ( function_exists( 'libxml_disable_entity_loader' ) ) {
		$old = libxml_disable_entity_loader( true );
	}
	$dom    = new DOMDocument();
	$return = $dom->loadXML( trim( $source ), $options );
	if ( ! is_null( $old ) ) {
		libxml_disable_entity_loader( $old );
	}
	if ( ! $return ) {
		return false;
	}
	if ( isset( $dom->doctype ) ) {
		throw new Exception( 'Unsafe DOCTYPE Detected while XML parsing' );
		return false;
	}
	return simplexml_import_dom( $dom );
}

function wcccpp_get_error_message( $error ) {
	$msg = '';
	
	switch ( $error ) {
		case '-3':
			$msg = 'CEP de destino inválido';
			break;
		case '-6':
		case '-10':
		case '006':
		case '007':
		case '008':
		case '010':
		case '011':
			$msg = 'Serviço indisponível para este CEP de destino.';
			break;
		case '-33':
		case '7':
		case '99':
		case '-888':
			$msg = 'O sistema do Correios está temporariamente fora do ar. Favor tentar mais tarde.';
			break;
		default:
			break;
	}
	
	return $msg;
}

function wcccpp_get_shipping( $args ) {
	$webservice_url = 'http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx';
	
	$data = array(
		'nCdServico'          => $args['service_code'],
		'nCdEmpresa'          => $args['login'],
		'sDsSenha'            => $args['password'],
		'sCepDestino'         => wcccpp_sanitize_postcode( $args['destination_postcode'] ),
		'sCepOrigem'          => wcccpp_sanitize_postcode( $args['origin_postcode'] ),
		'nVlAltura'           => $args['default_height'],
		'nVlLargura'          => $args['default_width'],
		'nVlDiametro'         => $args['default_diameter'],
		'nVlComprimento'      => $args['default_length'],
		'nVlPeso'             => $args['default_weight'],
		'nCdFormato'          => $args['format'],
		'sCdMaoPropria'       => $args['own_hands'],
		'nVlValorDeclarado'   => $args['declare_value'] ? 0 : 0,
		'sCdAvisoRecebimento' => $args['receipt_notice'],
		'StrRetorno'          => 'xml',
	);
	
	$url = add_query_arg( $data, $webservice_url );
	
	$response = wp_safe_remote_get( esc_url_raw( $url ), array( 'timeout' => 30 ) );
	
	$shipping = array();
	
	$shipping['code'] = $args['service_code'];
	$shipping['title'] = $args['title'];
	
	if ( is_wp_error( $response ) ) {
		$shipping['error'] = true;
		$shipping['msg'] = 'O webservice do Correios está indisponível no momento';
	} elseif ( $response['response']['code'] >= 200 && $response['response']['code'] < 300 ) {
		try {
			$result = wcccpp_safe_load_xml( $response['body'], LIBXML_NOCDATA );
		} catch ( Exception $e ) {
			// invalid xml response
			$shipping['error'] = true;
			$shipping['msg'] = 'Não foi possível processar o retorno do webservice do Correios.';
		}
		
		if ( isset( $result->cServico ) ) {
			$result = $result->cServico;
			// valid xml response
			//$shipping['xml'] = $result;
			//$shipping['args'] = $data;
			//$shipping['POST'] = $_POST;
			if ( $result->Erro != 0 ) {
				$shipping['error'] = true;
				$shipping['msg'] = wcccpp_get_error_message( $result->Erro );
			} else {
				$shipping['error'] = false;
				$shipping['code'] = $args['service_code'];
				$shipping['title'] = $args['title'];
				$shipping['price'] = wcccpp_format_price( $result->Valor, $_POST['fee'] );
				$shipping['price_formatted'] = wc_price( $shipping['price'] );
				$shipping['days'] = intval( $result->PrazoEntrega) + intval( $_POST['additional_time'] );
			}
		}
	} else {
		$shipping['error'] = true;
		$shipping['msg'] = '';
	}
	
	return $shipping;
}

function wcccpp_ajax_callback() {
	$settings = wcccpp_settings();
	$responses = array();
	
	if ( count( $settings['methods'] ) > 0 ) {
		do_action( 'wcccpp_before_calculate', $settings );
		
		foreach ($settings['methods'] as $method) {
			$method['origin_postcode'] = $_POST['origin_postcode'];
			$method['destination_postcode'] = $_POST['destination_postcode'];
			array_push( $responses, wcccpp_get_shipping( $method ) );
		}

		do_action( 'wcccpp_after_calculate', $settings, $responses );
	}

	wp_send_json_success( $responses );
	//wp_die();
}

function wcccpp_enqueue_script() {
	wp_enqueue_script( 'wcccpp-calculator', wcccpp_directory_uri() . 'assets/js/wcccpp-calculator.js', array(), '0.0.1', true );
	wp_localize_script( 'wcccpp-calculator', 'wcccpp_ajax', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
}
