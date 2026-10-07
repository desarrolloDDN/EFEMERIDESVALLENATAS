<?php
/**
 * Configuración base: soportes, menús, recursos y rendimiento.
 *
 * @package Efemerides
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'efemerides', EV_DIR . '/languages' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' ) );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/editor.css' );
		add_theme_support( 'custom-logo', array( 'height' => 64, 'width' => 64, 'flex-width' => true ) );

		add_image_size( 'ev-card', 720, 480, true );
		add_image_size( 'ev-feature', 1280, 960, true );
		add_image_size( 'ev-wide', 1920, 823, true );
		add_image_size( 'ev-portrait', 900, 1125, true );

		register_nav_menus(
			array(
				'primary' => __( 'Menú principal', 'efemerides' ),
				'footer'  => __( 'Menú del pie (explorar)', 'efemerides' ),
				'legal'   => __( 'Menú legal', 'efemerides' ),
			)
		);
	}
);

/** Fuentes, estilos y scripts. */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style(
			'ev-fonts',
			'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Manrope:wght@400;500;600;700&display=swap',
			array(),
			null
		);
		$css = EV_DIR . '/assets/css/main.css';
		wp_enqueue_style( 'ev-main', EV_URI . '/assets/css/main.css', array( 'ev-fonts' ), file_exists( $css ) ? filemtime( $css ) : EV_VERSION );

		$js = EV_DIR . '/assets/js/main.js';
		wp_enqueue_script( 'ev-main', EV_URI . '/assets/js/main.js', array(), file_exists( $js ) ? filemtime( $js ) : EV_VERSION, array( 'strategy' => 'defer', 'in_footer' => true ) );
		wp_localize_script(
			'ev-main',
			'EV',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'ev_forms' ),
			)
		);

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
);

/** Preconexión a Google Fonts. */
add_filter(
	'wp_resource_hints',
	function ( $urls, $relation ) {
		if ( 'preconnect' === $relation ) {
			$urls[] = 'https://fonts.googleapis.com';
			$urls[] = array( 'href' => 'https://fonts.gstatic.com', 'crossorigin' );
		}
		return $urls;
	},
	10,
	2
);

/** Clase no-js (el JS la retira) para que el contenido nunca quede oculto sin JavaScript. */
add_action(
	'wp_head',
	function () {
		echo "<script>document.documentElement.classList.remove('no-js')</script>\n";
	},
	0
);

/** Rendimiento: retirar emojis y recursos innecesarios. */
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );

add_filter( 'excerpt_length', fn() => 28 );
add_filter( 'excerpt_more', fn() => '…' );

/** Imágenes: carga diferida y decodificación asíncrona por defecto (WP ya genera WebP/AVIF si el servidor lo permite). */
add_filter(
	'wp_get_attachment_image_attributes',
	function ( $attr ) {
		$attr['decoding'] = 'async';
		return $attr;
	}
);

/** Formatos modernos al subir imágenes JPEG (WordPress 6.5+ con soporte del servidor). */
add_filter(
	'image_editor_output_format',
	function ( $formats ) {
		if ( wp_image_editor_supports( array( 'mime_type' => 'image/avif' ) ) ) {
			$formats['image/jpeg'] = 'image/avif';
		} elseif ( wp_image_editor_supports( array( 'mime_type' => 'image/webp' ) ) ) {
			$formats['image/jpeg'] = 'image/webp';
		}
		return $formats;
	}
);

/** Regenerar reglas de URL al activar o actualizar el tema. */
add_action( 'after_switch_theme', 'flush_rewrite_rules' );
add_action(
	'init',
	function () {
		if ( get_option( 'ev_rewrite_version' ) !== EV_VERSION . '-2' ) {
			flush_rewrite_rules();
			update_option( 'ev_rewrite_version', EV_VERSION . '-2' );
		}
	},
	99
);
