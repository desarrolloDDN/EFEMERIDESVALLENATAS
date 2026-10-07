<?php
/**
 * Tienda: integración con WooCommerce.
 *
 * Flujo físico:  producto → carrito → datos de envío → pago → confirmación (WooCommerce).
 * Flujo digital: producto → pago → confirmación → descarga (producto descargable de WooCommerce).
 * Pasarelas para Colombia (plugins oficiales de WooCommerce): Wompi, Mercado Pago, PayU, Stripe.
 * Sin WooCommerce el sitio muestra precios placeholder y los botones llevan a la página del producto.
 *
 * @package Efemerides
 */

defined( 'ABSPATH' ) || exit;

function ev_has_shop() {
	return class_exists( 'WooCommerce' );
}

add_action(
	'after_setup_theme',
	function () {
		add_theme_support( 'woocommerce', array( 'product_grid' => array( 'default_columns' => 2 ) ) );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
);

/** Producto WooCommerce vinculado a una edición (o null). */
function ev_wc_product( $type ) {
	if ( ! ev_has_shop() ) {
		return null;
	}
	$id = (int) ev_opt( 'physical' === $type ? 'phys_wc_id' : 'dig_wc_id' );
	$p  = $id ? wc_get_product( $id ) : null;
	return $p && $p->is_purchasable() ? $p : null;
}

/** Datos unificados de una edición para la UI. */
function ev_product( $type ) {
	$phys = 'physical' === $type;
	$wc   = ev_wc_product( $type );
	$url  = ev_url( $phys ? 'edicion-fisica' : 'edicion-digital' );

	if ( $wc ) {
		$buy  = add_query_arg( 'add-to-cart', $wc->get_id(), $phys ? wc_get_cart_url() : wc_get_checkout_url() );
		$cart = add_query_arg( 'add-to-cart', $wc->get_id(), wc_get_cart_url() );
	} else {
		$buy  = $url . '#comprar';
		$cart = $buy;
	}

	return array(
		'type'              => $type,
		'name'              => $phys ? 'Edición física' : 'Edición digital',
		'tagline'           => $phys ? 'El objeto. Las páginas. El archivo en tus manos.' : 'La memoria vallenata, en cualquier pantalla.',
		'description'       => ev_opt( $phys ? 'phys_description' : 'dig_description' ),
		'features'          => ev_opt_lines( $phys ? 'phys_features' : 'dig_features' ),
		'price_html'        => $wc ? $wc->get_price_html() : esc_html( ev_opt( $phys ? 'phys_price_text' : 'dig_price_text' ) ),
		'price_placeholder' => ! $wc && false !== strpos( ev_opt( $phys ? 'phys_price_text' : 'dig_price_text' ), 'XX' ),
		'price'             => $wc ? (float) $wc->get_price() : null,
		'in_stock'          => $wc ? $wc->is_in_stock() : true,
		'availability'      => $wc ? ( $wc->is_in_stock() ? ( $phys ? 'Disponible' : 'Acceso inmediato' ) : 'Agotado' ) : ( $phys ? 'Disponible' : 'Acceso inmediato' ),
		'buy_url'           => $buy,
		'cart_url'          => $cart,
		'url'               => $url,
		'cta'               => $phys ? 'Comprar edición física' : 'Comprar edición digital',
		'wc'                => $wc,
	);
}

/** Número de artículos en el carrito. */
function ev_cart_count() {
	return ev_has_shop() && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
}

/** Actualiza el contador del encabezado tras añadir por AJAX. */
add_filter(
	'woocommerce_add_to_cart_fragments',
	function ( $fragments ) {
		ob_start();
		ev_cart_badge();
		$fragments['[data-cart-badge]'] = ob_get_clean();
		return $fragments;
	}
);

function ev_cart_badge() {
	$count = ev_cart_count();
	printf(
		'<span data-cart-badge class="absolute right-1 top-1 flex h-[18px] min-w-[18px] items-center justify-center rounded-full bg-terracotta px-1 text-[10px] font-bold text-ivory %s">%d</span>',
		$count ? '' : 'hidden',
		(int) $count
	);
}

/** URL del carrito (o de la tienda si no hay WooCommerce). */
function ev_cart_url() {
	return ev_has_shop() ? wc_get_cart_url() : ev_url( 'tienda' );
}

/* ---------- Envolturas y ajustes de WooCommerce ---------- */

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

add_action(
	'woocommerce_before_main_content',
	function () {
		echo '<div class="container-editorial py-12 lg:py-16 ev-woo">';
	},
	10
);
add_action(
	'woocommerce_after_main_content',
	function () {
		echo '</div>';
	},
	10
);

/** Las fichas de producto de WooCommerce redirigen a las páginas editoriales del tema. */
add_action(
	'template_redirect',
	function () {
		if ( ! ev_has_shop() || ! is_product() ) {
			return;
		}
		$id = get_queried_object_id();
		if ( $id && $id === (int) ev_opt( 'phys_wc_id' ) ) {
			wp_safe_redirect( ev_url( 'edicion-fisica' ), 301 );
			exit;
		}
		if ( $id && $id === (int) ev_opt( 'dig_wc_id' ) ) {
			wp_safe_redirect( ev_url( 'edicion-digital' ), 301 );
			exit;
		}
	}
);

/** La página «Tienda» de WooCommerce apunta a la página editorial de la tienda. */
add_filter(
	'woocommerce_return_to_shop_redirect',
	fn() => ev_url( 'tienda' )
);

/** Producto digital: una sola unidad por pedido. */
add_filter(
	'woocommerce_add_to_cart_validation',
	function ( $passed, $product_id ) {
		if ( (int) $product_id === (int) ev_opt( 'dig_wc_id' ) && WC()->cart ) {
			foreach ( WC()->cart->get_cart() as $item ) {
				if ( (int) $item['product_id'] === (int) $product_id ) {
					return false; // Ya está en el carrito.
				}
			}
		}
		return $passed;
	},
	10,
	2
);

/** Tras añadir por enlace (?add-to-cart=ID), limpiar la URL para que recargar no duplique el producto. */
add_filter(
	'woocommerce_add_to_cart_redirect',
	function ( $url ) {
		if ( $url || wp_doing_ajax() ) {
			return $url;
		}
		return remove_query_arg( array( 'add-to-cart', 'quantity' ) );
	}
);

/* ==========================================================================
   WooCommerce en español completo
   La traducción es_CO de WooCommerce está incompleta (sobre todo carrito y
   checkout por bloques). Se usa la de español de España (es_ES) solo para
   rellenar los textos que falten; las traducciones es_CO tienen prioridad.
   ========================================================================== */

/** ¿Hace falta el respaldo es_ES? (sitio en español distinto de es_ES). */
function ev_wc_es_fallback_needed() {
	$locale = determine_locale();
	return ev_has_shop() && 0 === strpos( $locale, 'es_' ) && 'es_ES' !== $locale;
}

/** Descarga (una vez por versión de WooCommerce) el paquete de idioma es_ES. */
function ev_wc_install_es_pack() {
	if ( ! ev_wc_es_fallback_needed() || ! current_user_can( 'install_languages' ) ) {
		return;
	}
	$flag = 'ev_wc_es_pack_' . WC_VERSION;
	if ( get_option( $flag ) ) {
		return;
	}
	require_once ABSPATH . 'wp-admin/includes/translation-install.php';
	require_once ABSPATH . 'wp-admin/includes/file.php';
	$api = translations_api( 'plugins', array( 'slug' => 'woocommerce', 'version' => WC_VERSION ) );
	if ( is_wp_error( $api ) || empty( $api['translations'] ) ) {
		return;
	}
	foreach ( $api['translations'] as $t ) {
		if ( 'es_ES' !== $t['language'] ) {
			continue;
		}
		$tmp = download_url( $t['package'] );
		if ( is_wp_error( $tmp ) ) {
			return;
		}
		WP_Filesystem();
		$dir = WP_LANG_DIR . '/plugins';
		wp_mkdir_p( $dir );
		$ok = unzip_file( $tmp, $dir );
		wp_delete_file( $tmp );
		if ( ! is_wp_error( $ok ) ) {
			update_option( $flag, 1, false );
		}
		return;
	}
}
add_action( 'admin_init', 'ev_wc_install_es_pack' );

/** PHP: cargar es_ES detrás de es_CO (lo cargado primero tiene prioridad). */
add_action(
	'load_textdomain',
	function ( $domain, $mofile ) {
		static $loading = false;
		if ( 'woocommerce' !== $domain || $loading || false !== strpos( (string) $mofile, 'es_ES' ) || ! ev_wc_es_fallback_needed() ) {
			return;
		}
		$fallback = WP_LANG_DIR . '/plugins/woocommerce-es_ES.mo';
		if ( is_readable( $fallback ) ) {
			$loading = true;
			load_textdomain( 'woocommerce', $fallback, determine_locale() );
			$loading = false;
		}
	},
	10,
	2
);

/** Ruta del archivo es_ES equivalente a un archivo de traducción JS es_CO. */
function ev_wc_es_json( $file ) {
	$locale = determine_locale();
	if ( ! $file || false === strpos( $file, '-' . $locale . '-' ) ) {
		return '';
	}
	$es = str_replace( '-' . $locale . '-', '-es_ES-', $file );
	return is_readable( $es ) ? $es : '';
}

/** JS (bloques de carrito y checkout): si no hay archivo es_CO, usar el es_ES. */
add_filter(
	'load_script_translation_file',
	function ( $file, $handle, $domain ) {
		if ( 'woocommerce' === $domain && $file && ! is_readable( $file ) && ev_wc_es_fallback_needed() ) {
			$es = ev_wc_es_json( $file );
			return $es ? $es : $file;
		}
		return $file;
	},
	10,
	3
);

/** JS: si hay archivo es_CO, completarlo con es_ES (es_CO tiene prioridad). */
add_filter(
	'load_script_translations',
	function ( $translations, $file, $handle, $domain ) {
		if ( 'woocommerce' !== $domain || ! ev_wc_es_fallback_needed() ) {
			return $translations;
		}
		$es_file = ev_wc_es_json( $file );
		if ( ! $es_file ) {
			return $translations;
		}
		$es = json_decode( (string) file_get_contents( $es_file ), true );
		$co = json_decode( (string) $translations, true );
		if ( empty( $es['locale_data']['messages'] ) || empty( $co['locale_data']['messages'] ) ) {
			return $translations;
		}
		$co_msgs = array_filter( $co['locale_data']['messages'], fn( $v ) => is_array( $v ) && '' !== ( $v[0] ?? '' ) );
		$co['locale_data']['messages'] = array_merge( $es['locale_data']['messages'], $co_msgs );
		return wp_json_encode( $co );
	},
	10,
	4
);
