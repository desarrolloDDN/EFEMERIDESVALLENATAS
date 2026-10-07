<?php
/**
 * Plantilla de artículo (blog y curiosidades).
 * $args: crumbs, related (WP_Post[]), related_title
 *
 * @package Efemerides
 */
$ev_cat = ev_primary_term_name();
?>
<article <?php post_class(); ?>>
	<header class="container-editorial pt-10 lg:pt-16">
		<?php ev_breadcrumbs( $args['crumbs'] ); ?>
		<div class="mx-auto mt-12 max-w-3xl text-center">
			<?php if ( $ev_cat ) : ?><p class="label text-terracotta"><?php echo esc_html( $ev_cat ); ?></p><?php endif; ?>
			<h1 class="mt-5 text-[2.75rem] leading-[1.02] sm:text-6xl lg:text-7xl"><?php the_title(); ?></h1>
			<p class="mt-6 text-sm text-ink-soft">
				Por <span class="font-semibold text-carbon"><?php echo esc_html( 'curiosidad' === get_post_type() ? ev_opt( 'book_author' ) : get_the_author() ); ?></span> ·
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j \d\e F \d\e Y' ) ); ?></time> ·
				<?php echo (int) ev_reading_minutes(); ?> min de lectura
			</p>
			<?php if ( get_post_meta( get_the_ID(), 'ev_sample', true ) ) : ?><span class="placeholder-tag mt-5">Contenido pendiente de redacción</span><?php endif; ?>
		</div>
	</header>

	<div class="container-editorial mt-12">
		<?php ev_media( get_post_thumbnail_id(), 'Imagen destacada: ' . get_the_title(), '21/9', 'forest', 'mx-auto max-w-5xl', 'ev-wide', true ); ?>
	</div>

	<div class="container-editorial mt-14 grid gap-12 lg:grid-cols-[1fr_minmax(0,42rem)_1fr]">
		<div class="prose-editorial lg:col-start-2"><?php the_content(); ?></div>
		<div class="border-t border-line pt-8 lg:col-start-2"><?php ev_share_links( get_permalink(), get_the_title() ); ?></div>
	</div>

	<aside class="container-editorial mt-20"><?php ev_book_cta(); ?></aside>

	<?php if ( ! empty( $args['related'] ) ) : ?>
		<section aria-labelledby="related-t" class="container-editorial py-20">
			<h2 id="related-t" class="text-4xl"><?php echo esc_html( $args['related_title'] ?? 'Artículos relacionados' ); ?></h2>
			<ul class="mt-10 grid gap-10 sm:grid-cols-2 lg:grid-cols-3">
				<?php foreach ( $args['related'] as $ev_post ) : ?>
					<li><?php ev_article_card( $ev_post ); ?></li>
				<?php endforeach; ?>
			</ul>
		</section>
	<?php else : ?>
		<div class="pb-20"></div>
	<?php endif; ?>
</article>
