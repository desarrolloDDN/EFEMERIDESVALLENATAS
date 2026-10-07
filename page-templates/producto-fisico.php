<?php
/**
 * Template Name: Producto — Edición física
 *
 * @package Efemerides
 */

get_header();
the_post();
get_template_part( 'template-parts/product', null, array( 'type' => 'physical' ) );
get_footer();
