# Calculadora de Frete do Correios para página de produto do Woocommerce

## Como instalar
- Crie uma pasta chamada `woocommerce-correios-calculator` dentro de `wp-content/plugins/` e coloque todos os arquivos na raiz dessa pasta.

## Como configurar

Adicione o código abaixo no `functions.php` do seu tema.
```php
add_filter( 'wcccpp_form_settings', 'prefix_wcccpp_setup' );

function prefix_wcccpp_setup( $settings ) {
  return array(
    'origin_postcode' => 'SEU CEP AQUI',
    'additional_time' => 0, // seus dias adicionais
    'fee' => 0 // sua taxa
  );
}
```
