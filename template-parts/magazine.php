<?php
/**
 * Listado tipo magazine del bucle principal (blog, curiosidades, categorías).
 * $args: chips ([label, url, active][]), empty_text
 *
 * @package Efemerides
 */
?>
<section class="container-editorial py-14 lg:py-20">
	<?php if ( ! empty( $args['chips'] ) ) : ?>
		<nav aria-label="Categorías" class="scrollbar-none -mx-5 mb-14 flex gap-2 overflow-x-auto px-5 sm:mx-0 sm:flex-wrap sm:px-0">
			<?php foreach ( $args['chips'] as list( $ev_label, $ev_url, $ev_active ) ) : ?>
				<a href="<?php echo esc_url( $ev_url ); ?>" <?php echo $ev_active ? 'aria-current="page"' : ''; ?> class="inline-flex min-h-10 shrink-0 items-center rounded-full border px-4 text-sm transition-colors <?php echo $ev_active ? 'border-carbon bg-carbon text-ivory' : 'border-line hover:border-ink/40'; ?>"><?php echo esc_html( $ev_label ); ?></a>
			<?php endforeach; ?>
		</nav>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<?php
		$ev_first = ! is_paged();
		if ( $ev_first ) :
			the_post();
			?>
			<div class="reveal"><?php ev_article_card( get_post(), 'feature', 'h2' ); ?></div>
		<?php endif; ?>
		<ul class="<?php echo $ev_first ? 'mt-20' : ''; ?> grid gap-x-8 gap-y-16 sm:grid-cols-2 lg:grid-cols-3">
			<?php
			$ev_i = 0;
			while ( have_posts() ) :
				the_post();
				?>
				<li class="reveal" <?php echo ev_delay( ( $ev_i++ % 3 ) * 90 ); ?>><?php ev_article_card( get_post(), 'default', 'h2' ); ?></li>
			<?php endwhile; ?>
		</ul>
		<?php
		the_posts_pagination(
			array(
				'class'     => 'ev-pagination mt-20',
				'prev_text' => '← Anteriores',
				'next_text' => 'Siguientes →',
			)
		);
		?>
	<?php else : ?>
		<p class="border border-dashed border-line px-6 py-16 text-center text-ink-soft"><?php echo esc_html( $args['empty_text'] ?? 'Aún no hay publicaciones.' ); ?></p>
	<?php endif; ?>
</section>
