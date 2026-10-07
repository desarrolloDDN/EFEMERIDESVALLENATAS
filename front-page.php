<?php
/**
 * Página de inicio.
 * HEADER → HERO → SOBRE EL LIBRO → CADA FECHA GUARDA UNA HISTORIA → EFEMÉRIDES
 * → CURIOSIDADES → AUTOR → COMPRA → RESEÑAS → BOLETÍN → FOOTER
 *
 * @package Efemerides
 */

get_header();
ev_book_schema();
get_template_part( 'template-parts/home/hero' );
get_template_part( 'template-parts/home/about' );
get_template_part( 'template-parts/home/features' );
get_template_part( 'template-parts/home/ephemerides' );
get_template_part( 'template-parts/home/curiosities' );
get_template_part( 'template-parts/author', null, array( 'variant' => 'home' ) );
get_template_part( 'template-parts/home/buy' );
get_template_part( 'template-parts/home/reviews' );
get_template_part( 'template-parts/newsletter' );
get_footer();
