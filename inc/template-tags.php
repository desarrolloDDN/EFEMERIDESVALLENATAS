<?php
/**
 * Utilidades de plantilla: enlaces, iconos, datos de efemérides y migas de pan.
 *
 * @package Efemerides
 */

defined( 'ABSPATH' ) || exit;

/** Páginas del sitio: plantilla => slug por defecto. */
function ev_pages() {
	return array(
		'el-libro'           => 'page-templates/el-libro.php',
		'autor'              => 'page-templates/autor.php',
		'prensa'             => 'page-templates/prensa.php',
		'tienda'             => 'page-templates/tienda.php',
		'edicion-fisica'     => 'page-templates/producto-fisico.php',
		'edicion-digital'    => 'page-templates/producto-digital.php',
		'contacto'           => 'page-templates/contacto.php',
	);
}

/**
 * URL de una página del tema, encontrada por su plantilla (si el editor cambió el slug,
 * el enlace sigue funcionando). Cae a /slug/ si la página aún no existe.
 */
function ev_url( $key ) {
	static $cache = array();
	if ( isset( $cache[ $key ] ) ) {
		return $cache[ $key ];
	}
	$map = array(
		'inicio'       => home_url( '/' ),
		'efemerides'   => get_post_type_archive_link( 'efemeride' ),
		'curiosidades' => get_post_type_archive_link( 'curiosidad' ),
		'blog'         => get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/blog/' ),
	);
	if ( isset( $map[ $key ] ) ) {
		return $cache[ $key ] = $map[ $key ];
	}
	$template = ev_pages()[ $key ] ?? null;
	if ( $template ) {
		$found = get_posts(
			array(
				'post_type'      => 'page',
				'post_status'    => 'publish',
				'meta_key'       => '_wp_page_template',
				'meta_value'     => $template,
				'posts_per_page' => 1,
				'fields'         => 'ids',
			)
		);
		if ( $found ) {
			return $cache[ $key ] = get_permalink( $found[0] );
		}
	}
	$fallback = 'edicion-fisica' === $key || 'edicion-digital' === $key ? "/tienda/{$key}/" : "/{$key}/";
	return $cache[ $key ] = home_url( $fallback );
}

/** Icono lineal SVG en línea. */
function ev_icon( $name, $size = 20, $class = '' ) {
	$paths = array(
		'arrow-right' => '<path d="M5 12h14M13 6l6 6-6 6"/>',
		'arrow-left'  => '<path d="M19 12H5M11 6l-6 6 6 6"/>',
		'bag'         => '<path d="M5 8h14l-1.2 12H6.2L5 8Z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/>',
		'menu'        => '<path d="M4 8h16M4 16h16"/>',
		'close'       => '<path d="M6 6l12 12M18 6 6 18"/>',
		'search'      => '<circle cx="11" cy="11" r="6.5"/><path d="m16 16 4 4"/>',
		'check'       => '<path d="m5 12.5 4.5 4.5L19 7.5"/>',
		'truck'       => '<path d="M3 6h11v10H3zM14 10h4l3 3v3h-7"/><circle cx="7" cy="17.5" r="1.6"/><circle cx="17" cy="17.5" r="1.6"/>',
		'device'      => '<rect x="5" y="3" width="14" height="18" rx="2"/><path d="M11 18h2"/>',
		'lock'        => '<rect x="5" y="10" width="14" height="10" rx="1.5"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>',
		'link'        => '<path d="M10 14a4 4 0 0 0 5.7 0l3-3a4 4 0 0 0-5.7-5.7l-1 1"/><path d="M14 10a4 4 0 0 0-5.7 0l-3 3a4 4 0 0 0 5.7 5.7l1-1"/>',
		'instagram'   => '<rect x="3.5" y="3.5" width="17" height="17" rx="4.5"/><circle cx="12" cy="12" r="4"/><circle cx="17.2" cy="6.8" r=".6" fill="currentColor"/>',
		'facebook'    => '<path d="M14 8h3V4h-3a4 4 0 0 0-4 4v3H7v4h3v6h4v-6h3l1-4h-4V8Z"/>',
		'youtube'     => '<rect x="2.5" y="5.5" width="19" height="13" rx="3.5"/><path d="m10 9 5 3-5 3V9Z"/>',
		'tiktok'      => '<path d="M14 3v11.5a3.5 3.5 0 1 1-3.5-3.5"/><path d="M14 3c.5 2.6 2.4 4.4 5 4.6"/>',
		'whatsapp'    => '<path d="M4 20l1.3-3.8A8 8 0 1 1 8 19.1L4 20Z"/><path d="M9 9c0 3 2.5 6 6 6l1-1.5-2-1-1 1c-1-.5-2-1.5-2.5-2.5l1-1-1-2L9 9Z"/>',
		'x'           => '<path d="M4 4l16 16M20 4 4 20"/>',
	);
	return sprintf(
		'<svg width="%1$d" height="%1$d" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false" class="%2$s">%3$s</svg>',
		(int) $size,
		esc_attr( $class ),
		$paths[ $name ] ?? ''
	);
}

/** Redes configuradas en el Personalizador. */
function ev_social_links() {
	$out = array();
	foreach ( array( 'instagram' => 'Instagram', 'facebook' => 'Facebook', 'youtube' => 'YouTube', 'tiktok' => 'TikTok' ) as $key => $label ) {
		$out[] = array(
			'key'   => $key,
			'label' => $label,
			'url'   => ev_opt( 'social_' . $key ),
		);
	}
	return $out;
}

/* ---------- Efemérides ---------- */

function ev_month_name( $m ) {
	return ev_months()[ (int) $m ] ?? '';
}

function ev_ephemeris_date_label( $post_id ) {
	$d = (int) get_post_meta( $post_id, 'ev_day', true );
	$m = (int) get_post_meta( $post_id, 'ev_month', true );
	return $d && $m ? sprintf( '%d de %s', $d, ev_month_name( $m ) ) : '—';
}

/** Datos normalizados de una efeméride. */
function ev_ephemeris( $post = null ) {
	$post  = get_post( $post );
	$terms = get_the_terms( $post, 'tipo_efemeride' );
	$term  = $terms && ! is_wp_error( $terms ) ? $terms[0] : null;
	$meta  = fn( $k ) => get_post_meta( $post->ID, $k, true );
	return array(
		'id'       => $post->ID,
		'title'    => get_the_title( $post ),
		'url'      => get_permalink( $post ),
		'summary'  => has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 24 ),
		'day'      => (int) $meta( 'ev_day' ),
		'month'    => (int) $meta( 'ev_month' ),
		'year'     => $meta( 'ev_year' ) ? $meta( 'ev_year' ) : '[Año]',
		'place'    => $meta( 'ev_place' ) ? $meta( 'ev_place' ) : '[Lugar]',
		'people'   => $meta( 'ev_people' ),
		'source'   => $meta( 'ev_source' ) ? $meta( 'ev_source' ) : '[Fuente / referencia del libro]',
		'sample'   => (bool) $meta( 'ev_sample' ),
		'category' => $term ? $term->name : '',
		'cat_slug' => $term ? $term->slug : '',
	);
}

/** Todas las efemérides en orden de calendario. */
function ev_get_ephemerides( $args = array() ) {
	return get_posts(
		$args + array(
			'post_type'      => 'efemeride',
			'posts_per_page' => -1,
			'meta_key'       => 'ev_sort',
			'orderby'        => array( 'meta_value' => 'ASC', 'title' => 'ASC' ),
		)
	);
}

/** Destacadas para la Home (si no hay marcadas, las primeras del calendario). */
function ev_featured_ephemerides( $limit = 6 ) {
	$featured = ev_get_ephemerides(
		array(
			'posts_per_page' => $limit,
			'meta_query'     => array( array( 'key' => 'ev_featured', 'value' => '1' ) ),
		)
	);
	return $featured ? $featured : ev_get_ephemerides( array( 'posts_per_page' => $limit ) );
}

/* ---------- Taxonomía principal de un contenido ---------- */

function ev_primary_term_name( $post = null ) {
	$post = get_post( $post );
	$tax  = array(
		'post'       => 'category',
		'curiosidad' => 'tema_curiosidad',
		'efemeride'  => 'tipo_efemeride',
	)[ $post->post_type ] ?? '';
	if ( ! $tax ) {
		return '';
	}
	$terms = get_the_terms( $post, $tax );
	return $terms && ! is_wp_error( $terms ) ? $terms[0]->name : '';
}

function ev_reading_minutes( $post = null ) {
	$words = str_word_count( wp_strip_all_tags( get_post_field( 'post_content', $post ) ) );
	return max( 1, (int) ceil( $words / 220 ) );
}

/* ---------- Migas de pan (visibles + Schema.org) ---------- */

/**
 * @param array $items [ [ 'name' => '', 'url' => '' ], ... ] sin incluir «Inicio».
 */
function ev_breadcrumbs( $items, $tone = 'light' ) {
	$all = array_merge( array( array( 'name' => 'Inicio', 'url' => home_url( '/' ) ) ), $items );
	$dark = 'dark' === $tone;
	echo '<nav aria-label="Migas de pan" class="label !tracking-[0.12em] ' . ( $dark ? 'text-paper/70' : 'text-ink-soft' ) . '"><ol class="flex flex-wrap items-center gap-x-2 gap-y-1">';
	$last = count( $all ) - 1;
	foreach ( $all as $i => $c ) {
		echo '<li class="flex items-center gap-2">';
		if ( $i > 0 ) {
			echo '<span aria-hidden="true" class="opacity-50">/</span>';
		}
		if ( $i === $last ) {
			printf( '<span aria-current="page" class="%s">%s</span>', $dark ? 'text-ivory' : 'text-carbon', esc_html( $c['name'] ) );
		} else {
			printf( '<a href="%s" class="link-editorial hover:text-terracotta">%s</a>', esc_url( $c['url'] ), esc_html( $c['name'] ) );
		}
		echo '</li>';
	}
	echo '</ol></nav>';

	if ( ! ev_seo_plugin_active() ) {
		ev_json_ld(
			array(
				'@context'        => 'https://schema.org',
				'@type'           => 'BreadcrumbList',
				'itemListElement' => array_map(
					fn( $c, $i ) => array( '@type' => 'ListItem', 'position' => $i + 1, 'name' => $c['name'], 'item' => $c['url'] ),
					$all,
					array_keys( $all )
				),
			)
		);
	}
}

function ev_json_ld( $data ) {
	echo '<script type="application/ld+json">' . wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}

function ev_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'AIOSEO_VERSION' ) || defined( 'SEOPRESS_VERSION' );
}

/** Imprime un estilo inline con variable CSS (retardo de animaciones). */
function ev_delay( $ms ) {
	return sprintf( 'style="--delay:%dms;--reveal-delay:%dms"', (int) $ms, (int) $ms );
}

/* ---------- Menús ---------- */

/** Enlaces por defecto si no hay menú asignado. */
function ev_default_menu( $location ) {
	$main = array(
		array( 'El libro', ev_url( 'el-libro' ) ),
		array( 'Efemérides', ev_url( 'efemerides' ) ),
		array( 'Curiosidades', ev_url( 'curiosidades' ) ),
		array( 'Autor', ev_url( 'autor' ) ),
		array( 'Prensa', ev_url( 'prensa' ) ),
		array( 'Tienda', ev_url( 'tienda' ) ),
	);
	if ( 'primary' === $location ) {
		return $main;
	}
	if ( 'footer' === $location ) {
		return array_merge( array( array( 'Inicio', home_url( '/' ) ) ), $main, array( array( 'Blog', ev_url( 'blog' ) ), array( 'Contacto', ev_url( 'contacto' ) ) ) );
	}
	$legal = array();
	foreach ( array( 'terminos-y-condiciones' => 'Términos y condiciones', 'politica-de-privacidad' => 'Política de privacidad', 'tratamiento-de-datos' => 'Política de tratamiento de datos', 'politica-de-envios' => 'Política de envíos', 'politica-de-devoluciones' => 'Política de devoluciones' ) as $slug => $label ) {
		$page    = get_page_by_path( $slug );
		$legal[] = array( $label, $page ? get_permalink( $page ) : home_url( "/{$slug}/" ) );
	}
	return $legal;
}

/**
 * Elementos de un menú: [ [label, url, active], ... ]
 */
function ev_menu_items( $location ) {
	$items     = array();
	$locations = get_nav_menu_locations();
	if ( ! empty( $locations[ $location ] ) ) {
		$menu_items = wp_get_nav_menu_items( $locations[ $location ] );
		foreach ( (array) $menu_items as $item ) {
			if ( ! $item->menu_item_parent ) {
				$items[] = array( $item->title, $item->url );
			}
		}
	}
	if ( ! $items ) {
		$items = ev_default_menu( $location );
	}
	$current = untrailingslashit( home_url( add_query_arg( array() ) ) );
	return array_map(
		function ( $i ) use ( $current ) {
			$url    = untrailingslashit( $i[1] );
			$active = $url && $url !== untrailingslashit( home_url() ) && 0 === strpos( $current, $url );
			return array( $i[0], $i[1], $active );
		},
		$items
	);
}
