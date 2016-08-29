<?php

function wcccpp_get_calculator_template() {
	ob_start();
?>
	<form class="wcccpp-correios-calculator" action="" method="post">

		<section class="wcccpp-calculator-form">
			<a class="button wcccpp-button" href="#0">Calcular CEP</a>
			<section class="wcccpp-calculator-inside" style="display: none;">
				<p class="wcccpp-field">
					<label for="sCepDestino">Digite o seu CEP
						<a class="wcccpp-idontknow" target="_blank" href="http://www.buscacep.correios.com.br/sistemas/buscacep/default.cfm">Não sabe o seu CEP?</a>
					</label>
					<input class="input-text" type="text" name="sCepDestino" id="sCepDestino" placeholder="00000-000">
					<input class="button" type="submit" value="Calcular">
				</p>
				
				<p class="wcccpp-messages"></p>
			</section>
		</section>
	</form>

<?php
	return ob_get_clean();
}
