<?php
/**
 * SEO: títulos, meta descripción, Open Graph, X Cards y datos estructurados.
 * Si hay un plugin SEO activo (Yoast, Rank Math, AIOSEO, SEOPress) el tema solo
 * añade los datos estructurados de Libro y Producto, y deja el resto al plugin.
 * El sitemap lo genera WordPress (/wp-sitemap.xml) o el plugin SEO.
 *
 * @package Efemerides
 */

defined( 'ABSPATH' ) || exit;

function ev_default_description() {
	return 'Efemérides Vallenatas: el libro que recorre la historia del vallenato fecha por fecha. Personajes, canciones, discos y festivales de la cultura vallenata. Disponible en edición física y digital.';
}

/** Títulos con las palabras clave principales. */
add_filter(
	'document_title_parts',
	function ( $parts ) {
		if ( is_front_page() ) {
			$parts['title']   = 'Efemérides Vallenatas';
			$parts['tagline'] = 'La memoria del vallenato contada fecha por fecha';
		} elseif ( is_post_type_archive( 'efemeride' ) ) {
			$parts['title'] = 'Efemérides del vallenato';
		} elseif ( is_post_type_archive( 'curiosidad' ) ) {
			$parts['title'] = 'Curiosidades vallenatas';
		} elseif ( is_singular( 'efemeride' ) ) {
			$parts['title'] = ev_ephemeris_date_label( get_the_ID() ) . ': ' . get_the_title();
		}
		return $parts;
	}
);
add_filter( 'document_title_separator', fn() => '·' );

/** Descripción de la página actual. */
function ev_meta_description() {
	if ( is_front_page() ) {
		return ev_default_description();
	}
	if ( is_singular() ) {
		$post = get_queried_object();
		if ( has_excerpt( $post ) ) {
			return wp_strip_all_tags( get_the_excerpt( $post ) );
		}
		$tpl  = get_page_template_slug( $post );
		$desc = array(
			'page-templates/el-libro.php'         => 'Efemérides Vallenatas: un libro sobre la historia del vallenato contada fecha por fecha. Ficha técnica, formatos físico y digital, y la historia detrás de la obra.',
			'page-templates/autor.php'            => 'Conoce al autor de Efemérides Vallenatas: su trayectoria, su relación con la música vallenata y el proceso de investigación detrás del libro.',
			'page-templates/prensa.php'           => 'Reseñas, testimonios y apariciones en medios de Efemérides Vallenatas, el libro sobre la historia del vallenato.',
			'page-templates/tienda.php'           => 'Compra Efemérides Vallenatas en edición física con envío nacional a toda Colombia o en edición digital con acceso inmediato.',
			'page-templates/producto-fisico.php'  => 'Compra la edición física de Efemérides Vallenatas, libro sobre la historia del vallenato. Envío nacional a toda Colombia.',
			'page-templates/producto-digital.php' => 'Compra la edición digital de Efemérides Vallenatas y consulta la memoria del vallenato desde cualquier dispositivo.',
			'page-templates/contacto.php'         => 'Contacta al equipo de Efemérides Vallenatas: prensa, ventas institucionales, presentaciones del libro y ayuda con pedidos.',
		)[ $tpl ] ?? '';
		return $desc ? $desc : wp_trim_words( wp_strip_all_tags( strip_shortcodes( $post->post_content ) ), 28, '…' );
	}
	if ( is_post_type_archive( 'efemeride' ) ) {
		return 'Calendario interactivo de efemérides del vallenato: nacimientos, canciones, discos, festivales y acontecimientos de la cultura vallenata, fecha por fecha.';
	}
	if ( is_post_type_archive( 'curiosidad' ) ) {
		return 'Curiosidades del vallenato: historias detrás de grandes canciones, acordeones, personajes y datos de la música vallenata que probablemente no conocías.';
	}
	if ( is_home() ) {
		return 'Artículos sobre la historia del vallenato, compositores, acordeoneros, discos, festivales y efemérides de la música vallenata.';
	}
	if ( is_category() || is_tax() ) {
		return wp_strip_all_tags( term_description() ) ?: ev_default_description();
	}
	return ev_default_description();
}

add_action(
	'wp_head',
	function () {
		if ( ev_seo_plugin_active() ) {
			return;
		}
		$desc  = ev_meta_description();
		$title = wp_get_document_title();
		$url   = is_singular() ? get_permalink() : home_url( add_query_arg( array() ) );
		$image = is_singular() && has_post_thumbnail() ? get_the_post_thumbnail_url( null, 'ev-feature' ) : EV_URI . '/assets/img/og-image.png';
		$type  = is_singular( array( 'post', 'curiosidad', 'efemeride' ) ) ? 'article' : 'website';

		printf( '<meta name="description" content="%s">' . "\n", esc_attr( $desc ) );
		$og = array(
			'og:locale'      => 'es_CO',
			'og:type'        => $type,
			'og:site_name'   => 'Efemérides Vallenatas',
			'og:title'       => $title,
			'og:description' => $desc,
			'og:url'         => $url,
			'og:image'       => $image,
		);
		foreach ( $og as $k => $v ) {
			printf( '<meta property="%s" content="%s">' . "\n", esc_attr( $k ), esc_attr( $v ) );
		}
		foreach ( array( 'twitter:card' => 'summary_large_image', 'twitter:title' => $title, 'twitter:description' => $desc, 'twitter:image' => $image ) as $k => $v ) {
			printf( '<meta name="%s" content="%s">' . "\n", esc_attr( $k ), esc_attr( $v ) );
		}
		if ( is_front_page() ) {
			ev_json_ld(
				array(
					'@context' => 'https://schema.org',
					'@graph'   => array(
						array(
							'@type'       => 'WebSite',
							'@id'         => home_url( '/#website' ),
							'url'         => home_url( '/' ),
							'name'        => 'Efemérides Vallenatas',
							'description' => ev_default_description(),
							'inLanguage'  => 'es-CO',
						),
						array(
							'@type'  => 'Organization',
							'@id'    => home_url( '/#organization' ),
							'name'   => 'Efemérides Vallenatas',
							'url'    => home_url( '/' ),
							'sameAs' => array_values( array_filter( wp_list_pluck( ev_social_links(), 'url' ) ) ),
						),
					),
				)
			);
		}
		if ( is_singular( array( 'post', 'curiosidad', 'efemeride' ) ) ) {
			ev_json_ld(
				array(
					'@context'         => 'https://schema.org',
					'@type'            => 'Article',
					'headline'         => get_the_title(),
					'description'      => $desc,
					'datePublished'    => get_the_date( 'c' ),
					'dateModified'     => get_the_modified_date( 'c' ),
					'author'           => array( '@type' => 'Person', 'name' => ev_opt( 'book_author' ) ),
					'publisher'        => array( '@type' => 'Organization', 'name' => 'Efemérides Vallenatas' ),
					'mainEntityOfPage' => get_permalink(),
					'image'            => $image,
					'inLanguage'       => 'es-CO',
				)
			);
		}
	},
	1
);

/** Schema.org Book (Home y El libro). Solo publica datos reales, nunca placeholders. */
function ev_book_schema() {
	$real = fn( $k ) => ! ev_is_placeholder( ev_opt( $k ) ) ? ev_opt( $k ) : null;
	$data = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Book',
		'@id'         => ev_url( 'el-libro' ) . '#book',
		'name'        => 'Efemérides Vallenatas',
		'alternativeHeadline' => 'La memoria del vallenato contada fecha por fecha',
		'inLanguage'  => 'es',
		'genre'       => array( 'Historia', 'Música', 'Cultura vallenata' ),
		'about'       => array( 'historia del vallenato', 'cultura vallenata', 'música vallenata' ),
		'url'         => ev_url( 'el-libro' ),
	);
	if ( $real( 'book_author' ) ) {
		$data['author'] = array( '@type' => 'Person', 'name' => $real( 'book_author' ) );
	}
	if ( $real( 'book_isbn' ) ) {
		$data['isbn'] = $real( 'book_isbn' );
	}
	if ( $real( 'book_publisher' ) ) {
		$data['publisher'] = array( '@type' => 'Organization', 'name' => $real( 'book_publisher' ) );
	}
	if ( $real( 'book_pages' ) && is_numeric( $real( 'book_pages' ) ) ) {
		$data['numberOfPages'] = (int) $real( 'book_pages' );
	}
	if ( $real( 'book_year' ) ) {
		$data['datePublished'] = $real( 'book_year' );
	}
	$data['workExample'] = array();
	foreach ( array( 'physical', 'digital' ) as $type ) {
		$p       = ev_product( $type );
		$example = array(
			'@type'      => 'Book',
			'bookFormat' => 'physical' === $type ? 'https://schema.org/Paperback' : 'https://schema.org/EBook',
			'url'        => $p['url'],
		);
		if ( null !== $p['price'] ) {
			$example['offers'] = array(
				'@type'         => 'Offer',
				'price'         => $p['price'],
				'priceCurrency' => 'COP',
				'availability'  => $p['in_stock'] ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
			);
		}
		$data['workExample'][] = $example;
	}
	ev_json_ld( $data );
}

/** Schema.org Product (páginas de edición). Google exige precio: solo se publica con precio real. */
function ev_product_schema( $type ) {
	$p    = ev_product( $type );
	$data = array(
		'@context'    => 'https://schema.org',
		'@type'       => 'Product',
		'name'        => 'Efemérides Vallenatas — ' . $p['name'],
		'description' => $p['description'],
		'brand'       => array( '@type' => 'Brand', 'name' => 'Efemérides Vallenatas' ),
		'category'    => 'Libros',
		'url'         => $p['url'],
	);
	if ( null !== $p['price'] ) {
		$data['offers'] = array(
			'@type'         => 'Offer',
			'price'         => $p['price'],
			'priceCurrency' => 'COP',
			'availability'  => $p['in_stock'] ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
			'url'           => $p['url'],
			'areaServed'    => 'CO',
		);
	}
	ev_json_ld( $data );
}

/** No indexar carrito, checkout ni cuenta. */
add_filter(
	'wp_robots',
	function ( $robots ) {
		if ( ev_has_shop() && ( is_cart() || is_checkout() || is_account_page() ) ) {
			$robots['noindex'] = true;
		}
		return $robots;
	}
);

/** Excluir del sitemap tipos privados. */
add_filter(
	'wp_sitemaps_post_types',
	function ( $types ) {
		unset( $types['testimonio'], $types['medio'], $types['suscriptor'], $types['mensaje'] );
		return $types;
	}
);
