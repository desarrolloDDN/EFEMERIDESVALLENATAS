<?php
/**
 * Resultados de búsqueda.
 *
 * @package Efemerides
 */

get_header();
$ev_blog = ev_url( 'blog' );
if ( is_category() ) {
	ev_page_header(
		array(
			'crumbs' => array( array( 'name' => 'Blog', 'url' => $ev_blog ), array( 'name' => single_cat_title( '', false ), 'url' => get_term_link( get_queried_object() ) ) ),
			'kicker' => 'Blog · Categoría',
			'title'  => esc_html( single_cat_title( '', false ) ),
		)
	);
} elseif ( is_search() ) {
	ev_page_header(
		array(
			'crumbs' => array( array( 'name' => 'Búsqueda', 'url' => get_search_link() ) ),
			'kicker' => 'Búsqueda',
			'title'  => esc_html( sprintf( 'Resultados para «%s»', get_search_query() ) ),
		)
	);
} else {
	ev_page_header(
		array(
			'crumbs' => array( array( 'name' => 'Blog', 'url' => $ev_blog ) ),
			'kicker' => 'Blog',
			'title'  => 'Descubre las historias <span class="italic text-terracotta">detrás de la música</span>',
			'intro'  => 'Historia, personajes, canciones, discos, festivales y efemérides de la memoria vallenata.',
		)
	);
}
get_template_part(
	'template-parts/magazine',
	null,
	array(
		'chips'      => is_search() ? array() : ev_term_chips( 'category', $ev_blog ),
		'empty_text' => is_search() ? 'No encontramos resultados. Prueba con otra palabra.' : 'Aún no hay artículos publicados.',
	)
);
get_template_part( 'template-parts/newsletter' );
get_footer();
