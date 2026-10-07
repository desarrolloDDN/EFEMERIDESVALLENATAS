<?php
/**
 * Asistente de configuración inicial (Apariencia → Configurar Efemérides).
 * Crea páginas, menús, categorías, portada y blog, y opcionalmente contenido de ejemplo.
 * Es idempotente: no duplica lo que ya existe.
 *
 * @package Efemerides
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'admin_menu',
	function () {
		add_theme_page( 'Configurar Efemérides Vallenatas', 'Configurar Efemérides', 'manage_options', 'ev-setup', 'ev_setup_page' );
	}
);

/** Aviso tras activar el tema. */
add_action(
	'admin_notices',
	function () {
		if ( get_option( 'ev_setup_done' ) || ! current_user_can( 'manage_options' ) || ( isset( $_GET['page'] ) && 'ev-setup' === $_GET['page'] ) ) {
			return;
		}
		printf(
			'<div class="notice notice-info"><p><strong>Efemérides Vallenatas:</strong> crea las páginas, menús y estructura del sitio en un paso. <a class="button button-primary" href="%s">Configurar el sitio</a></p></div>',
			esc_url( admin_url( 'themes.php?page=ev-setup' ) )
		);
	}
);

function ev_setup_page() {
	$done = null;
	if ( isset( $_POST['ev_setup_nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['ev_setup_nonce'] ), 'ev_setup' ) && current_user_can( 'manage_options' ) ) {
		$done = ev_run_setup(
			array(
				'sample'     => ! empty( $_POST['sample'] ),
				'permalinks' => ! empty( $_POST['permalinks'] ),
				'hello'      => ! empty( $_POST['hello'] ),
			)
		);
	}
	?>
	<div class="wrap">
		<h1>Configurar Efemérides Vallenatas</h1>
		<?php if ( $done ) : ?>
			<div class="notice notice-success"><p><strong>Listo.</strong></p><ul style="list-style:disc;padding-left:20px">
				<?php foreach ( $done as $line ) : ?><li><?php echo esc_html( $line ); ?></li><?php endforeach; ?>
			</ul><p><a class="button button-primary" href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank">Ver el sitio</a> <a class="button" href="<?php echo esc_url( admin_url( 'customize.php?autofocus[panel]=ev_panel' ) ); ?>">Completar datos del libro</a></p></div>
		<?php endif; ?>
		<p>Este asistente crea la estructura del sitio. Puedes ejecutarlo varias veces: no duplica contenido existente.</p>
		<form method="post">
			<?php wp_nonce_field( 'ev_setup', 'ev_setup_nonce' ); ?>
			<table class="form-table" role="presentation">
				<tr><th scope="row">Páginas y menús</th><td>Inicio, El libro, Autor, Prensa, Tienda, Edición física, Edición digital, Contacto, Blog y páginas legales. Menú principal, del pie y legal. Categorías de efeméride.</td></tr>
				<tr><th scope="row">Enlaces permanentes</th><td><label><input type="checkbox" name="permalinks" value="1" checked> Usar URLs amigables (<code>/efemerides/nombre-del-evento/</code>, <code>/blog/nombre-del-articulo/</code>)</label></td></tr>
				<tr><th scope="row">Contenido de ejemplo</th><td><label><input type="checkbox" name="sample" value="1" checked> Crear 36 efemérides, 6 curiosidades y 7 artículos de ejemplo, marcados como placeholders</label><p class="description">Contienen solo textos entre [corchetes]; ningún dato histórico. Bórralos cuando cargues el contenido real.</p></td></tr>
				<tr><th scope="row">Limpieza</th><td><label><input type="checkbox" name="hello" value="1" checked> Mover a la papelera «Hello world!» y la «Sample Page» de WordPress</label></td></tr>
			</table>
			<?php submit_button( 'Configurar el sitio' ); ?>
		</form>
	</div>
	<?php
}

/** Crea una página si no existe (por slug). Devuelve su ID. */
function ev_ensure_page( $slug, $title, $template = '', $content = '', $parent = 0 ) {
	$path     = $parent ? get_post_field( 'post_name', $parent ) . '/' . $slug : $slug;
	$existing = get_page_by_path( $path );
	if ( $existing ) {
		if ( $template ) {
			update_post_meta( $existing->ID, '_wp_page_template', $template );
		}
		return $existing->ID;
	}
	return wp_insert_post(
		array(
			'post_type'     => 'page',
			'post_status'   => 'publish',
			'post_title'    => $title,
			'post_name'     => $slug,
			'post_content'  => $content,
			'post_parent'   => $parent,
			'page_template' => $template,
		)
	);
}

function ev_blocks_from_lines( $blocks ) {
	$out = '';
	foreach ( $blocks as $b ) {
		if ( 'h3' === $b['type'] ) {
			$out .= "<!-- wp:heading {\"level\":3} -->\n<h3 class=\"wp-block-heading\">" . esc_html( $b['text'] ) . "</h3>\n<!-- /wp:heading -->\n\n";
		} elseif ( 'h2' === $b['type'] ) {
			$out .= "<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">" . esc_html( $b['text'] ) . "</h2>\n<!-- /wp:heading -->\n\n";
		} elseif ( 'quote' === $b['type'] ) {
			$out .= "<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\"><!-- wp:paragraph -->\n<p>" . esc_html( $b['text'] ) . "</p>\n<!-- /wp:paragraph --></blockquote>\n<!-- /wp:quote -->\n\n";
		} else {
			$out .= "<!-- wp:paragraph -->\n<p>" . esc_html( $b['text'] ) . "</p>\n<!-- /wp:paragraph -->\n\n";
		}
	}
	return $out;
}

function ev_run_setup( $opts ) {
	$log = array();

	/* Categorías de efeméride */
	foreach ( ev_ephemeris_categories() as $slug => $name ) {
		if ( ! term_exists( $slug, 'tipo_efemeride' ) ) {
			wp_insert_term( $name, 'tipo_efemeride', array( 'slug' => $slug ) );
		}
	}
	$log[] = 'Categorías de efeméride creadas.';

	/* Páginas */
	$author_content = ev_blocks_from_lines(
		array(
			array( 'type' => 'p', 'text' => '[Biografía — origen, formación y primeros vínculos con la música vallenata.]' ),
			array( 'type' => 'p', 'text' => '[Trayectoria — recorrido profesional, publicaciones, investigación o trabajo en medios.]' ),
			array( 'type' => 'h2', 'text' => '¿Cuál es su primer recuerdo del vallenato?' ),
			array( 'type' => 'p', 'text' => '[Respuesta del autor.]' ),
			array( 'type' => 'h2', 'text' => '¿Por qué escribir un libro organizado por fechas?' ),
			array( 'type' => 'p', 'text' => '[Respuesta del autor sobre la motivación para escribir el libro.]' ),
			array( 'type' => 'h2', 'text' => '¿Cómo fue el proceso de investigación?' ),
			array( 'type' => 'p', 'text' => '[Respuesta del autor sobre archivos, fuentes, entrevistas y verificación.]' ),
			array( 'type' => 'h2', 'text' => '¿Qué historia le sorprendió más durante la escritura?' ),
			array( 'type' => 'p', 'text' => '[Respuesta del autor.]' ),
			array( 'type' => 'h3', 'text' => 'Otros trabajos' ),
			array( 'type' => 'p', 'text' => '[Título de otra obra] — [Año]. [Título de otra obra] — [Año].' ),
		)
	);
	$legal_body = fn( $intro ) => ev_blocks_from_lines(
		array(
			array( 'type' => 'p', 'text' => $intro ),
			array( 'type' => 'p', 'text' => '[Texto legal pendiente. Este documento debe ser redactado o validado por un profesional del derecho antes de la publicación del sitio.]' ),
			array( 'type' => 'h2', 'text' => '1. Responsable' ),
			array( 'type' => 'p', 'text' => '[Razón social, NIT, domicilio y datos de contacto del responsable.]' ),
			array( 'type' => 'h2', 'text' => '2. Alcance' ),
			array( 'type' => 'p', 'text' => '[Contenido pendiente.]' ),
			array( 'type' => 'h2', 'text' => '3. Contacto' ),
			array( 'type' => 'p', 'text' => '[Canal de atención para consultas y reclamos.]' ),
		)
	);

	$home    = ev_ensure_page( 'inicio', 'Inicio' );
	$blog    = ev_ensure_page( 'blog', 'Blog' );
	$book    = ev_ensure_page( 'el-libro', 'El libro', 'page-templates/el-libro.php' );
	$author  = ev_ensure_page( 'autor', 'El autor', 'page-templates/autor.php', $author_content );
	$press   = ev_ensure_page( 'prensa', 'Prensa', 'page-templates/prensa.php' );
	$shop    = ev_ensure_page( 'tienda', 'Tienda', 'page-templates/tienda.php' );
	ev_ensure_page( 'edicion-fisica', 'Edición física', 'page-templates/producto-fisico.php', '', $shop );
	ev_ensure_page( 'edicion-digital', 'Edición digital', 'page-templates/producto-digital.php', '', $shop );
	$contact = ev_ensure_page( 'contacto', 'Contacto', 'page-templates/contacto.php' );

	$legal = array(
		'terminos-y-condiciones'   => array( 'Términos y condiciones', 'Condiciones de uso del sitio y de compra de las ediciones física y digital.' ),
		'politica-de-privacidad'   => array( 'Política de privacidad', 'Cómo recopilamos, usamos y protegemos la información de quienes visitan este sitio.' ),
		'tratamiento-de-datos'     => array( 'Política de tratamiento de datos', 'Política de tratamiento de datos personales conforme a la Ley 1581 de 2012 y sus decretos reglamentarios.' ),
		'politica-de-envios'       => array( 'Política de envíos', 'Cobertura, tiempos y costos de envío de la edición física dentro de Colombia.' ),
		'politica-de-devoluciones' => array( 'Política de devoluciones', 'Condiciones para cambios, devoluciones y derecho de retracto.' ),
	);
	$legal_ids = array();
	foreach ( $legal as $slug => list( $title, $intro ) ) {
		$legal_ids[] = ev_ensure_page( $slug, $title, '', $legal_body( $intro ) );
	}
	update_option( 'wp_page_for_privacy_policy', $legal_ids[1] );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $home );
	update_option( 'page_for_posts', $blog );
	update_option( 'blogname', 'Efemérides Vallenatas' );
	update_option( 'blogdescription', 'La memoria del vallenato contada fecha por fecha' );
	$log[] = 'Páginas creadas; «Inicio» como portada y «Blog» como página de entradas.';

	/* Menús */
	$menus = array(
		'primary' => array( 'Menú principal', array( $book, 'efemeride', 'curiosidad', $author, $press, $shop ) ),
		'footer'  => array( 'Explorar', array( $home, $book, 'efemeride', 'curiosidad', $author, $press, $blog, $shop, $contact ) ),
		'legal'   => array( 'Legal', $legal_ids ),
	);
	$locations = get_theme_mod( 'nav_menu_locations', array() );
	foreach ( $menus as $location => list( $name, $items ) ) {
		if ( ! empty( $locations[ $location ] ) && wp_get_nav_menu_object( $locations[ $location ] ) ) {
			continue;
		}
		$menu_id = wp_create_nav_menu( $name );
		if ( is_wp_error( $menu_id ) ) {
			$menu_id = wp_get_nav_menu_object( $name )->term_id;
		}
		foreach ( $items as $item ) {
			if ( is_string( $item ) ) {
				$obj = get_post_type_object( $item );
				wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => $obj->labels->name, 'menu-item-type' => 'post_type_archive', 'menu-item-object' => $item, 'menu-item-status' => 'publish' ) );
			} else {
				$title = get_the_title( $item );
				if ( $item === $author ) {
					$title = 'Autor';
				}
				wp_update_nav_menu_item( $menu_id, 0, array( 'menu-item-title' => $title, 'menu-item-object-id' => $item, 'menu-item-object' => 'page', 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ) );
			}
		}
		$locations[ $location ] = $menu_id;
	}
	set_theme_mod( 'nav_menu_locations', $locations );
	$log[] = 'Menús principal, del pie y legal asignados.';

	/* Enlaces permanentes */
	if ( $opts['permalinks'] ) {
		global $wp_rewrite;
		$wp_rewrite->set_permalink_structure( '/blog/%postname%/' );
		update_option( 'category_base', 'blog/categoria' );
		$log[] = 'Enlaces permanentes: /blog/nombre-del-articulo/ y /efemerides/nombre-del-evento/.';
	}

	/* Limpieza de WordPress por defecto */
	if ( $opts['hello'] ) {
		foreach ( array( 'hello-world' => 'post', 'sample-page' => 'page' ) as $slug => $type ) {
			$p = get_page_by_path( $slug, OBJECT, $type );
			if ( $p ) {
				wp_trash_post( $p->ID );
			}
		}
		$log[] = 'Contenido de ejemplo de WordPress enviado a la papelera.';
	}

	/* WooCommerce: formato de pesos colombianos */
	if ( class_exists( 'WooCommerce' ) ) {
		update_option( 'woocommerce_currency', 'COP' );
		update_option( 'woocommerce_price_num_decimals', 0 );
		update_option( 'woocommerce_price_thousand_sep', '.' );
		update_option( 'woocommerce_price_decimal_sep', ',' );
		update_option( 'woocommerce_currency_pos', 'left' );
		$log[] = 'WooCommerce: moneda COP con formato $99.000.';
	}

	/* Contenido de ejemplo */
	if ( $opts['sample'] ) {
		$log = array_merge( $log, ev_import_sample_content() );
	}

	flush_rewrite_rules();
	update_option( 'ev_setup_done', 1 );
	return $log;
}

function ev_import_sample_content() {
	$file = EV_DIR . '/data/sample-content.json';
	$data = json_decode( (string) file_get_contents( $file ), true );
	if ( ! $data ) {
		return array( 'No se pudo leer el contenido de ejemplo.' );
	}
	$count = array( 0, 0, 0 );

	foreach ( $data['ephemerides'] as $e ) {
		if ( get_page_by_path( $e['slug'], OBJECT, 'efemeride' ) ) {
			continue;
		}
		$id = wp_insert_post(
			array(
				'post_type'    => 'efemeride',
				'post_status'  => 'publish',
				'post_title'   => $e['title'],
				'post_name'    => $e['slug'],
				'post_excerpt' => $e['summary'],
				'post_content' => ev_blocks_from_lines( array_map( fn( $t ) => array( 'type' => 'p', 'text' => $t ), $e['body'] ) ),
				'meta_input'   => array(
					'ev_day'      => $e['day'],
					'ev_month'    => $e['month'],
					'ev_year'     => '[Año]',
					'ev_place'    => '[Lugar]',
					'ev_people'   => $e['people'],
					'ev_source'   => '[Fuente / referencia del libro]',
					'ev_featured' => $e['featured'] ? 1 : 0,
					'ev_sample'   => 1,
					'ev_sort'     => sprintf( '%02d%02d', $e['month'], $e['day'] ),
				),
			)
		);
		wp_set_object_terms( $id, $e['category'], 'tipo_efemeride' );
		++$count[0];
	}

	foreach ( array( 'curiosities' => array( 'curiosidad', 'tema_curiosidad' ), 'posts' => array( 'post', 'category' ) ) as $key => list( $type, $tax ) ) {
		foreach ( $data[ $key ] as $a ) {
			if ( get_page_by_path( $a['slug'], OBJECT, $type ) ) {
				continue;
			}
			$id = wp_insert_post(
				array(
					'post_type'    => $type,
					'post_status'  => 'publish',
					'post_title'   => $a['title'],
					'post_name'    => $a['slug'],
					'post_excerpt' => $a['excerpt'],
					'post_date'    => $a['date'] . ' 09:00:00',
					'post_content' => ev_blocks_from_lines( $a['body'] ),
					'meta_input'   => array( 'ev_sample' => 1 ),
				)
			);
			$term = term_exists( $a['category'], $tax );
			if ( ! $term ) {
				$term = wp_insert_term( $a['category'], $tax );
			}
			if ( ! is_wp_error( $term ) ) {
				wp_set_object_terms( $id, (int) $term['term_id'], $tax );
			}
			++$count[ 'posts' === $key ? 2 : 1 ];
		}
	}
	return array( sprintf( 'Contenido de ejemplo: %d efemérides, %d curiosidades y %d artículos.', $count[0], $count[1], $count[2] ) );
}
