<?php
/**
 * Efemérides Vallenatas — funciones del tema.
 *
 * @package Efemerides
 */

defined( 'ABSPATH' ) || exit;

define( 'EV_VERSION', '1.1.0' );
define( 'EV_DIR', get_template_directory() );
define( 'EV_URI', get_template_directory_uri() );

require EV_DIR . '/inc/options.php';
require EV_DIR . '/inc/setup.php';
require EV_DIR . '/inc/post-types.php';
require EV_DIR . '/inc/customizer.php';
require EV_DIR . '/inc/template-tags.php';
require EV_DIR . '/inc/components.php';
require EV_DIR . '/inc/listing.php';
require EV_DIR . '/inc/shop.php';
require EV_DIR . '/inc/forms.php';
require EV_DIR . '/inc/seo.php';
require EV_DIR . '/inc/updater.php';

if ( is_admin() ) {
	require EV_DIR . '/inc/setup-wizard.php';
}
