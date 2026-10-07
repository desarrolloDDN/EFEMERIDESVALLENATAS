<?php
/**
 * Aviso visible solo para administradores mientras la tienda no esté conectada.
 *
 * @package Efemerides
 */
if ( ! current_user_can( 'manage_options' ) ) {
	return;
}
$ev_missing = array();
if ( ! ev_has_shop() ) {
	$ev_missing[] = 'Instala y activa WooCommerce.';
} else {
	if ( ! ev_wc_product( 'physical' ) ) {
		$ev_missing[] = 'Crea el producto «Edición física» en WooCommerce y escribe su ID en Apariencia → Personalizar → Efemérides Vallenatas → Tienda.';
	}
	if ( ! ev_wc_product( 'digital' ) ) {
		$ev_missing[] = 'Crea el producto «Edición digital» (virtual y descargable) y escribe su ID en el Personalizador.';
	}
}
if ( ! $ev_missing ) {
	return;
}
?>
<div class="mx-auto mb-10 max-w-5xl border-l-2 border-gold bg-paper/60 p-5 text-sm">
	<p class="font-semibold">Solo visible para administradores — la compra aún no está conectada:</p>
	<ul class="mt-2 list-disc space-y-1 pl-5">
		<?php foreach ( $ev_missing as $ev_m ) : ?><li><?php echo esc_html( $ev_m ); ?></li><?php endforeach; ?>
	</ul>
</div>
