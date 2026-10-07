<?php
/**
 * Curiosidades vallenatas (magazine).
 *
 * @package Efemerides
 */

get_header();
$ev_all = ev_url( 'curiosidades' );
$ev_crumbs = array( array( 'name' => 'Curiosidades', 'url' => $ev_all ) );
if ( is_tax() ) {
	$ev_crumbs[] = array( 'name' => single_term_title( '', false ), 'url' => get_term_link( get_queried_object() ) );
}
ev_page_header(
	array(
		'crumbs' => $ev_crumbs,
		'kicker' => 'Curiosidades vallenatas',
		'title'  => is_tax() ? esc_html( single_term_title( '', false ) ) : 'Historias que probablemente <span class="italic text-terracotta">no conocías</span>',
		'intro'  => 'Un pequeño magazine digital para descubrir las historias detrás de la música.',
	)
);
get_template_part( 'template-parts/magazine', null, array( 'chips' => ev_term_chips( 'tema_curiosidad', $ev_all ), 'empty_text' => 'Aún no hay curiosidades publicadas.' ) );
get_template_part( 'template-parts/newsletter' );
get_footer();
