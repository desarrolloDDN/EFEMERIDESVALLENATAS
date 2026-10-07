<?php
/**
 * Template Name: Tienda
 *
 * @package Efemerides
 */

get_header();
the_post();
ev_page_header(
	array(
		'crumbs' => array( array( 'name' => get_the_title(), 'url' => get_permalink() ) ),
		'kicker' => 'Tienda',
		'title'  => 'Consigue <span class="italic text-terracotta">Efemérides Vallenatas</span>',
		'intro'  => 'Lleva la memoria vallenata a tu biblioteca o a tu pantalla. Compra sencilla y segura.',
	)
);
?>
<section class="container-editorial py-16 lg:py-24">
	<?php get_template_part( 'template-parts/shop-notice' ); ?>
	<div class="mx-auto grid max-w-5xl gap-6 md:grid-cols-2">
		<div class="reveal"><?php ev_product_card( 'physical', 'light', 'h2' ); ?></div>
		<div class="reveal" <?php echo ev_delay( 120 ); ?>><?php ev_product_card( 'digital', 'light', 'h2' ); ?></div>
	</div>
	<ul class="mx-auto mt-16 grid max-w-5xl gap-8 border-t border-line pt-12 sm:grid-cols-3">
		<?php
		foreach ( array(
			array( 'truck', 'Envío nacional', 'A toda Colombia. Entrega estimada: ' . ev_opt( 'ship_estimate' ) . '.' ),
			array( 'device', 'Acceso inmediato', 'La edición digital llega a tu correo tras confirmar el pago.' ),
			array( 'lock', 'Pago seguro', 'Pasarela certificada. Tarjetas, PSE y billeteras digitales.' ),
		) as list( $ev_icon, $ev_t, $ev_d ) ) :
			?>
			<li class="flex gap-4"><?php echo ev_icon( $ev_icon, 26, 'shrink-0 text-terracotta' ); ?><div><p class="font-semibold"><?php echo esc_html( $ev_t ); ?></p><p class="mt-1 text-sm text-ink-soft"><?php echo esc_html( $ev_d ); ?></p></div></li>
		<?php endforeach; ?>
	</ul>
	<?php if ( trim( get_the_content() ) ) : ?>
		<div class="prose-editorial ev-no-dropcap mx-auto mt-16 max-w-3xl"><?php the_content(); ?></div>
	<?php endif; ?>
</section>
<?php
get_footer();
