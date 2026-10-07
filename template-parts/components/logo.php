<?php
/**
 * Logotipo: logo personalizado o emblema tipográfico.
 *
 * @package Efemerides
 */
$ev_dark = ! empty( $args['dark'] );
?>
<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="group inline-flex items-center gap-3 <?php echo $ev_dark ? 'text-ivory' : 'text-carbon'; ?>" aria-label="Efemérides Vallenatas — inicio" rel="home">
	<?php if ( has_custom_logo() && ! $ev_dark ) : ?>
		<?php echo wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'thumbnail', false, array( 'class' => 'h-9 w-auto', 'alt' => '' ) ); ?>
	<?php else : ?>
		<span aria-hidden="true" class="relative inline-block h-8 w-8 shrink-0">
			<span class="absolute inset-0 rounded-full border border-current opacity-60"></span>
			<span class="absolute inset-[5px] rounded-full border border-current opacity-40"></span>
			<span class="absolute inset-[10px] rounded-full bg-terracotta transition-transform duration-700 group-hover:scale-110"></span>
		</span>
	<?php endif; ?>
	<span class="font-display text-[1.35rem] leading-none tracking-tight">Efemérides <span class="italic">Vallenatas</span></span>
</a>
