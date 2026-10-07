<?php
/**
 * Curiosidad individual.
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
			'crumbs'        => array( array( 'name' => 'Curiosidades', 'url' => ev_url( 'curiosidades' ) ), array( 'name' => get_the_title(), 'url' => get_permalink() ) ),
			'related'       => ev_related( get_post(), 'tema_curiosidad' ),
			'related_title' => 'Más curiosidades',
		)
	);
endwhile;
get_footer();
