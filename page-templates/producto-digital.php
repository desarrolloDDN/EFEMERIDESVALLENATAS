<?php
/**
 * Template Name: Producto — Edición digital
 *
 * @package Efemerides
 */

get_header();
the_post();
get_template_part( 'template-parts/product', null, array( 'type' => 'digital' ) );
get_footer();
