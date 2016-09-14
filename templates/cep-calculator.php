<?php

function wcccpp_get_calculator_template() {
	ob_start();
	
	$settings = apply_filters( 'wcccpp_form_settings', array(
		'origin_postcode' => '',
		'additional_time' => 0,
		'fee' => 0,
	) );
?>
	<form class="wcccpp-correios-calculator" action="" method="post">

		<section class="wcccpp-calculator-form">
			<a class="button wcccpp-button" href="#0">Calcular Frete</a>
			<section class="wcccpp-calculator-inside" style="display: none;">
				<p class="wcccpp-field">
					<label for="sCepDestino">Digite o seu CEP
						<a class="wcccpp-idontknow" target="_blank" href="http://www.buscacep.correios.com.br/sistemas/buscacep/default.cfm">Não sabe o seu CEP?</a>
					</label>
					<input class="input-text" type="text" name="destination_postcode" id="destination_postcode" placeholder="00000-000">
					<input type="hidden" name="origin_postcode" id="origin_postcode" value="<?php echo $settings['origin_postcode']; ?>" />
					<input type="hidden" name="additional_time" id="additional_time" value="<?php echo $settings['additional_time']; ?>" />
					<input type="hidden" name="fee" id="fee" value="<?php echo $settings['fee']; ?>" />
					<input class="button" type="submit" value="Calcular">
				</p>
				
				<p class="wcccpp-messages"></p>
			</section>
		</section>
	</form>

<?php
	return ob_get_clean();
}
