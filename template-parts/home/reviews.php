<?php
/**
 * Reseñas y prensa.
 *
 * @package Efemerides
 */
?>
<section aria-labelledby="res-t" class="py-24 lg:py-36">
	<div class="container-editorial">
		<?php ev_section_heading( array( 'kicker' => 'Reseñas', 'id' => 'res-t', 'title' => 'Lo que dicen <span class="italic">del libro</span>' ) ); ?>
		<ul class="mt-14 grid gap-12 md:grid-cols-2 lg:gap-16">
			<?php foreach ( ev_testimonials( 4 ) as $ev_i => $ev_t ) : ?>
				<li class="reveal" <?php echo ev_delay( ( $ev_i % 2 ) * 100 ); ?>><?php ev_testimonial_card( $ev_t ); ?></li>
			<?php endforeach; ?>
		</ul>
		<div class="reveal mt-24">
			<h3 class="label mb-6 text-center text-ink-soft">En los medios</h3>
			<?php ev_press_logos(); ?>
		</div>
	</div>
</section>
