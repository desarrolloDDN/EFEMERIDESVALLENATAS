<?php
/**
 * Tipos de contenido: efemérides, curiosidades, testimonios, prensa,
 * suscriptores y mensajes de contacto.
 *
 * @package Efemerides
 */

defined( 'ABSPATH' ) || exit;

/** Categorías de efeméride (se crean al configurar el sitio). */
function ev_ephemeris_categories() {
	return array(
		'nacimientos'    => 'Nacimientos',
		'fallecimientos' => 'Fallecimientos',
		'discos'         => 'Discos',
		'canciones'      => 'Canciones',
		'festivales'     => 'Festivales',
		'compositores'   => 'Compositores',
		'interpretes'    => 'Intérpretes',
		'historicos'     => 'Acontecimientos históricos',
		'cultura'        => 'Cultura vallenata',
	);
}

function ev_months() {
	return array( 1 => 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre' );
}

/** Campos de una efeméride. */
function ev_ephemeris_meta_fields() {
	return array(
		'ev_day'      => array( 'Día', 'integer' ),
		'ev_month'    => array( 'Mes (1-12)', 'integer' ),
		'ev_year'     => array( 'Año', 'string' ),
		'ev_place'    => array( 'Lugar', 'string' ),
		'ev_people'   => array( 'Protagonistas (separados por coma)', 'string' ),
		'ev_source'   => array( 'Fuente / referencia', 'string' ),
		'ev_featured' => array( 'Destacar en la página de inicio', 'boolean' ),
		'ev_sample'   => array( 'Contenido de ejemplo (placeholder)', 'boolean' ),
	);
}

/** Taxonomías antes que los tipos de contenido: sus reglas de URL deben tener prioridad. */
add_action(
	'init',
	function () {
		register_taxonomy(
			'tipo_efemeride',
			'efemeride',
			array(
				'labels'            => array( 'name' => 'Categorías de efeméride', 'singular_name' => 'Categoría' ),
				'hierarchical'      => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'slug' => 'efemerides/categoria', 'with_front' => false ),
			)
		);

		register_taxonomy(
			'tema_curiosidad',
			'curiosidad',
			array(
				'labels'            => array( 'name' => 'Temas', 'singular_name' => 'Tema' ),
				'hierarchical'      => true,
				'show_in_rest'      => true,
				'show_admin_column' => true,
				'rewrite'           => array( 'slug' => 'curiosidades/tema', 'with_front' => false ),
			)
		);

	},
	9
);

add_action(
	'init',
	function () {
		$common = array(
			'public'       => true,
			'show_in_rest' => true,
			'menu_position' => 5,
		);

		register_post_type(
			'efemeride',
			$common + array(
				'labels'        => array(
					'name'          => 'Efemérides',
					'singular_name' => 'Efeméride',
					'add_new_item'  => 'Añadir efeméride',
					'edit_item'     => 'Editar efeméride',
					'all_items'     => 'Todas las efemérides',
					'menu_name'     => 'Efemérides',
				),
				'menu_icon'     => 'dashicons-calendar-alt',
				'has_archive'   => 'efemerides',
				'rewrite'       => array( 'slug' => 'efemerides', 'with_front' => false ),
				'supports'      => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions' ),
			)
		);

		register_post_type(
			'curiosidad',
			$common + array(
				'labels'      => array(
					'name'          => 'Curiosidades',
					'singular_name' => 'Curiosidad',
					'add_new_item'  => 'Añadir curiosidad',
					'edit_item'     => 'Editar curiosidad',
				),
				'menu_icon'   => 'dashicons-lightbulb',
				'has_archive' => 'curiosidades',
				'rewrite'     => array( 'slug' => 'curiosidades', 'with_front' => false ),
				'supports'    => array( 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'revisions' ),
			)
		);

		$private = array(
			'public'             => false,
			'show_ui'            => true,
			'show_in_rest'       => true,
			'publicly_queryable' => false,
			'exclude_from_search' => true,
		);

		register_post_type(
			'testimonio',
			$private + array(
				'labels'    => array( 'name' => 'Testimonios', 'singular_name' => 'Testimonio', 'add_new_item' => 'Añadir testimonio' ),
				'menu_icon' => 'dashicons-format-quote',
				'supports'  => array( 'title', 'editor', 'page-attributes' ),
				'show_in_rest' => true,
			)
		);

		register_post_type(
			'medio',
			$private + array(
				'labels'    => array( 'name' => 'Prensa (medios)', 'singular_name' => 'Nota de prensa', 'add_new_item' => 'Añadir nota de prensa' ),
				'menu_icon' => 'dashicons-megaphone',
				'supports'  => array( 'title', 'thumbnail', 'page-attributes' ),
			)
		);

		register_post_type(
			'suscriptor',
			array(
				'public'       => false,
				'show_ui'      => true,
				'show_in_menu' => 'tools.php',
				'labels'       => array( 'name' => 'Suscriptores del boletín', 'singular_name' => 'Suscriptor' ),
				'supports'     => array( 'title' ),
				'capabilities' => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap' => true,
			)
		);

		register_post_type(
			'mensaje',
			array(
				'public'       => false,
				'show_ui'      => true,
				'show_in_menu' => 'tools.php',
				'labels'       => array( 'name' => 'Mensajes de contacto', 'singular_name' => 'Mensaje' ),
				'supports'     => array( 'title', 'editor' ),
				'capabilities' => array( 'create_posts' => 'do_not_allow' ),
				'map_meta_cap' => true,
			)
		);

		foreach ( ev_ephemeris_meta_fields() as $key => $f ) {
			register_post_meta(
				'efemeride',
				$key,
				array(
					'type'          => $f[1],
					'single'        => true,
					'show_in_rest'  => true,
					'auth_callback' => fn() => current_user_can( 'edit_posts' ),
				)
			);
		}
		foreach ( array( 'ev_role', 'ev_sample' ) as $key ) {
			register_post_meta( 'testimonio', $key, array( 'type' => 'ev_sample' === $key ? 'boolean' : 'string', 'single' => true, 'show_in_rest' => true, 'auth_callback' => fn() => current_user_can( 'edit_posts' ) ) );
		}
		foreach ( array( 'ev_url', 'ev_headline', 'ev_date' ) as $key ) {
			register_post_meta( 'medio', $key, array( 'type' => 'string', 'single' => true, 'show_in_rest' => true, 'auth_callback' => fn() => current_user_can( 'edit_posts' ) ) );
		}
	}
);

/* ---------- Cajas de campos en el editor ---------- */

add_action(
	'add_meta_boxes',
	function () {
		add_meta_box( 'ev_ephemeris', 'Datos de la efeméride', 'ev_render_ephemeris_box', 'efemeride', 'side', 'high' );
		add_meta_box( 'ev_testimonial', 'Autor del testimonio', 'ev_render_testimonial_box', 'testimonio', 'side', 'high' );
		add_meta_box( 'ev_press', 'Datos de la nota', 'ev_render_press_box', 'medio', 'normal', 'high' );
	}
);

function ev_render_ephemeris_box( $post ) {
	wp_nonce_field( 'ev_meta', 'ev_meta_nonce' );
	$m = fn( $k ) => get_post_meta( $post->ID, $k, true );
	echo '<p style="display:flex;gap:8px"><label style="flex:1">Día<br><input type="number" min="1" max="31" name="ev_day" value="' . esc_attr( $m( 'ev_day' ) ) . '" style="width:100%"></label>';
	echo '<label style="flex:2">Mes<br><select name="ev_month" style="width:100%">';
	foreach ( ev_months() as $n => $name ) {
		printf( '<option value="%d" %s>%s</option>', (int) $n, selected( (int) $m( 'ev_month' ), $n, false ), esc_html( ucfirst( $name ) ) );
	}
	echo '</select></label></p>';
	foreach ( array( 'ev_year' => 'Año', 'ev_place' => 'Lugar', 'ev_people' => 'Protagonistas (separados por coma)', 'ev_source' => 'Fuente / referencia' ) as $k => $label ) {
		printf( '<p><label>%s<br><input type="text" name="%s" value="%s" style="width:100%%"></label></p>', esc_html( $label ), esc_attr( $k ), esc_attr( $m( $k ) ) );
	}
	printf( '<p><label><input type="checkbox" name="ev_featured" value="1" %s> Destacar en la página de inicio</label></p>', checked( (bool) $m( 'ev_featured' ), true, false ) );
	printf( '<p><label><input type="checkbox" name="ev_sample" value="1" %s> Contenido de ejemplo (muestra la etiqueta «Ejemplo»)</label></p>', checked( (bool) $m( 'ev_sample' ), true, false ) );
	echo '<p class="description">El resumen de la tarjeta se toma del «Extracto».</p>';
}

function ev_render_testimonial_box( $post ) {
	wp_nonce_field( 'ev_meta', 'ev_meta_nonce' );
	printf( '<p>El <strong>título</strong> es el nombre de la persona y el <strong>contenido</strong> es el testimonio.</p><p><label>Profesión / medio<br><input type="text" name="ev_role" value="%s" style="width:100%%"></label></p>', esc_attr( get_post_meta( $post->ID, 'ev_role', true ) ) );
	echo '<p class="description">Publica solo testimonios con autorización expresa de su autor.</p>';
}

function ev_render_press_box( $post ) {
	wp_nonce_field( 'ev_meta', 'ev_meta_nonce' );
	echo '<p>El <strong>título</strong> es el nombre del medio. La <strong>imagen destacada</strong> se usa como logotipo.</p>';
	foreach ( array( 'ev_headline' => 'Titular de la nota', 'ev_url' => 'Enlace a la nota (URL)', 'ev_date' => 'Fecha (texto libre)' ) as $k => $label ) {
		printf( '<p><label>%s<br><input type="text" name="%s" value="%s" style="width:100%%"></label></p>', esc_html( $label ), esc_attr( $k ), esc_attr( get_post_meta( $post->ID, $k, true ) ) );
	}
}

add_action(
	'save_post',
	function ( $post_id, $post ) {
		if ( ! isset( $_POST['ev_meta_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['ev_meta_nonce'] ), 'ev_meta' ) ) {
			return;
		}
		if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) || ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$fields = array(
			'efemeride'  => array( 'ev_day' => 'int', 'ev_month' => 'int', 'ev_year' => 'text', 'ev_place' => 'text', 'ev_people' => 'text', 'ev_source' => 'text', 'ev_featured' => 'bool', 'ev_sample' => 'bool' ),
			'testimonio' => array( 'ev_role' => 'text' ),
			'medio'      => array( 'ev_headline' => 'text', 'ev_url' => 'url', 'ev_date' => 'text' ),
		);
		foreach ( $fields[ $post->post_type ] ?? array() as $key => $type ) {
			$raw = wp_unslash( $_POST[ $key ] ?? '' );
			switch ( $type ) {
				case 'int':
					$value = absint( $raw );
					break;
				case 'bool':
					$value = ! empty( $raw ) ? 1 : 0;
					break;
				case 'url':
					$value = esc_url_raw( $raw );
					break;
				default:
					$value = sanitize_text_field( $raw );
			}
			update_post_meta( $post_id, $key, $value );
		}
		// Clave de orden cronológico (MMDD) para consultas rápidas.
		if ( 'efemeride' === $post->post_type ) {
			update_post_meta( $post_id, 'ev_sort', sprintf( '%02d%02d', (int) get_post_meta( $post_id, 'ev_month', true ), (int) get_post_meta( $post_id, 'ev_day', true ) ) );
		}
	},
	10,
	2
);

/** Columna de fecha en el listado de efemérides. */
add_filter(
	'manage_efemeride_posts_columns',
	function ( $cols ) {
		return array_slice( $cols, 0, 2, true ) + array( 'ev_date' => 'Fecha' ) + array_slice( $cols, 2, null, true );
	}
);
add_action(
	'manage_efemeride_posts_custom_column',
	function ( $col, $id ) {
		if ( 'ev_date' === $col ) {
			echo esc_html( ev_ephemeris_date_label( $id ) );
		}
	},
	10,
	2
);

/** Archivo de efemérides: todas, en orden de calendario. */
add_action(
	'pre_get_posts',
	function ( $q ) {
		if ( is_admin() || ! $q->is_main_query() ) {
			return;
		}
		if ( $q->is_post_type_archive( 'efemeride' ) || $q->is_tax( 'tipo_efemeride' ) ) {
			$q->set( 'posts_per_page', -1 );
			$q->set( 'meta_key', 'ev_sort' );
			$q->set( 'orderby', array( 'meta_value' => 'ASC', 'title' => 'ASC' ) );
		}
		if ( $q->is_post_type_archive( 'curiosidad' ) ) {
			$q->set( 'posts_per_page', 13 );
		}
	}
);
