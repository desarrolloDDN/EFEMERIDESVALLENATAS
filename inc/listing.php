<?php
/**
 * Ayudas para listados (chips de categorías y relacionados).
 *
 * @package Efemerides
 */

defined( 'ABSPATH' ) || exit;

/** Chips de una taxonomía con «Todos». */
function ev_term_chips( $taxonomy, $all_url ) {
	$current = is_tax( $taxonomy ) || is_category() ? get_queried_object_id() : 0;
	$chips   = array( array( 'Todos', $all_url, ! $current ) );
	foreach ( get_terms( array( 'taxonomy' => $taxonomy, 'hide_empty' => true ) ) as $t ) {
		if ( 'category' === $taxonomy && 'uncategorized' === $t->slug ) {
			continue;
		}
		$chips[] = array( $t->name, get_term_link( $t ), $current === $t->term_id );
	}
	return $chips;
}

/** Contenidos relacionados: misma taxonomía primero, luego recientes. */
function ev_related( $post, $taxonomy, $limit = 3 ) {
	$ids   = wp_get_post_terms( $post->ID, $taxonomy, array( 'fields' => 'ids' ) );
	$posts = $ids && ! is_wp_error( $ids ) ? get_posts(
		array(
			'post_type'      => $post->post_type,
			'posts_per_page' => $limit,
			'post__not_in'   => array( $post->ID ),
			'tax_query'      => array( array( 'taxonomy' => $taxonomy, 'terms' => $ids ) ),
		)
	) : array();
	if ( count( $posts ) < $limit ) {
		$posts = array_merge(
			$posts,
			get_posts(
				array(
					'post_type'      => $post->post_type,
					'posts_per_page' => $limit - count( $posts ),
					'post__not_in'   => array_merge( array( $post->ID ), wp_list_pluck( $posts, 'ID' ) ),
				)
			)
		);
	}
	return $posts;
}
