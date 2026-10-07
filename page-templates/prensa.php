<?php
/**
 * Template Name: Prensa y reseñas
 *
 * @package Efemerides
 */

get_header();
the_post();
ev_page_header(
	array(
		'crumbs' => array( array( 'name' => get_the_title(), 'url' => get_permalink() ) ),
		'kicker' => 'Prensa / Reseñas',
		'title'  => 'Lo que dicen <span class="italic text-terracotta">del libro</span>',
		'intro'  => 'Lectores, investigadores, periodistas y músicos. Los testimonios se publican solo con autorización expresa.',
	)
);
$ev_kit = ev_opt( 'press_kit' );
?>
<section class="container-editorial py-20 lg:py-28">
	<ul class="grid gap-14 md:grid-cols-2 lg:gap-20">
		<?php foreach ( ev_testimonials( 12 ) as $ev_i => $ev_t ) : ?>
			<li class="reveal" <?php echo ev_delay( ( $ev_i % 2 ) * 100 ); ?>><?php ev_testimonial_card( $ev_t ); ?></li>
		<?php endforeach; ?>
	</ul>
</section>
<section aria-labelledby="medios-t" class="container-editorial pb-20">
	<h2 id="medios-t" class="text-4xl sm:text-5xl">En los medios</h2>
	<div class="mt-10"><?php ev_press_logos(); ?></div>
	<div class="mt-12"><?php ev_press_logos( true ); ?></div>
</section>
<section class="paper-texture border-t border-line py-20">
	<div class="container-editorial grid gap-10 lg:grid-cols-2 lg:items-center">
		<div>
			<p class="label text-terracotta">Para medios</p>
			<h2 class="mt-4 text-4xl sm:text-5xl">Kit de prensa</h2>
			<p class="mt-4 max-w-lg text-lg text-ink-soft">Portada en alta resolución, fotografías del autor, ficha técnica y nota de prensa.<?php echo $ev_kit ? '' : ' <span class="placeholder-tag ml-1">Pendiente</span>'; ?></p>
		</div>
		<div class="flex flex-col gap-3 sm:flex-row lg:justify-end">
			<?php if ( $ev_kit ) : ?>
				<a href="<?php echo esc_url( $ev_kit ); ?>" class="btn btn-dark" download>Descargar kit de prensa</a>
			<?php else : ?>
				<button type="button" disabled class="btn btn-dark" title="Disponible próximamente">Descargar kit de prensa</button>
			<?php endif; ?>
			<a href="<?php echo esc_url( add_query_arg( 'asunto', 'prensa', ev_url( 'contacto' ) ) ); ?>" class="btn btn-outline">Contacto para medios</a>
		</div>
	</div>
</section>
<?php
get_footer();
