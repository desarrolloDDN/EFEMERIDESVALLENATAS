<?php
/**
 * Artículo del blog.
 *
 * @package Efemerides
 */

get_header();
while ( have_posts() ) :
	the_post();
	get_template_part(
		'template-parts/article',
		null,
		array(
			'crumbs'  => array( array( 'name' => 'Blog', 'url' => ev_url( 'blog' ) ), array( 'name' => get_the_title(), 'url' => get_permalink() ) ),
			'related' => ev_related( get_post(), 'category' ),
		)
	);
endwhile;
get_footer();
