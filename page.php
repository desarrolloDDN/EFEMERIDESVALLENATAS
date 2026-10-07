<?php
/**
 * Página genérica (incluye páginas legales, carrito, checkout y mi cuenta de WooCommerce).
 *
 * @package Efemerides
 */

get_header();
while ( have_posts() ) :
	the_post();
	$ev_is_shop_page = ev_has_shop() && ( is_cart() || is_checkout() || is_account_page() );
	?>
	<article <?php post_class(); ?>>
		<?php if ( $ev_is_shop_page ) : ?>
			<div class="container-editorial pb-24 pt-8 lg:pt-12">
				<?php ev_breadcrumbs( array( array( 'name' => get_the_title(), 'url' => get_permalink() ) ) ); ?>
				<h1 class="mb-12 mt-8 text-5xl sm:text-6xl"><?php the_title(); ?></h1>
				<div class="ev-woo"><?php the_content(); ?></div>
			</div>
		<?php else : ?>
			<?php
			ev_page_header(
				array(
					'crumbs' => array( array( 'name' => get_the_title(), 'url' => get_permalink() ) ),
					'title'  => esc_html( get_the_title() ),
					'intro'  => has_excerpt() ? get_the_excerpt() : '',
				)
			);
			?>
			<div class="container-editorial py-16">
				<div class="prose-editorial ev-no-dropcap mx-auto max-w-3xl"><?php the_content(); ?></div>
			</div>
		<?php endif; ?>
	</article>
	<?php
endwhile;
get_footer();
