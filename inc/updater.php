<?php
/**
 * Actualizaciones del tema desde GitHub.
 *
 * WordPress consulta la última «release» publicada en GitHub y, si su versión es
 * mayor que la instalada, muestra el aviso de actualización en Escritorio →
 * Actualizaciones y en Apariencia → Temas, con el botón «Actualizar ahora».
 *
 * Cada release debe incluir el archivo adjunto «efemerides-vallenatas.zip»
 * (carpeta efemerides-vallenatas/ en la raíz). Ver README → Publicar una versión.
 *
 * @package Efemerides
 */

defined( 'ABSPATH' ) || exit;

define( 'EV_GITHUB_REPO', 'desarrolloDDN/EFEMERIDESVALLENATAS' );
define( 'EV_RELEASE_ASSET', 'efemerides-vallenatas.zip' );

/** Última release publicada (en caché 6 horas; «Comprobar de nuevo» la renueva). */
function ev_latest_release() {
	$cached = get_site_transient( 'ev_theme_release' );
	if ( false !== $cached ) {
		return $cached;
	}
	$response = wp_remote_get(
		'https://api.github.com/repos/' . EV_GITHUB_REPO . '/releases/latest',
		array(
			'timeout' => 10,
			'headers' => array( 'Accept' => 'application/vnd.github+json' ),
		)
	);
	$release = array();
	if ( ! is_wp_error( $response ) && 200 === wp_remote_retrieve_response_code( $response ) ) {
		$data = json_decode( wp_remote_retrieve_body( $response ), true );
		foreach ( (array) ( $data['assets'] ?? array() ) as $asset ) {
			if ( EV_RELEASE_ASSET === $asset['name'] ) {
				$release = array(
					'version' => ltrim( (string) $data['tag_name'], 'vV' ),
					'package' => $asset['browser_download_url'],
					'url'     => $data['html_url'],
				);
				break;
			}
		}
	}
	// Si GitHub no responde, reintentar en 1 hora.
	set_site_transient( 'ev_theme_release', $release, $release ? 6 * HOUR_IN_SECONDS : HOUR_IN_SECONDS );
	return $release;
}

/** Responde a la consulta de WordPress para temas con «Update URI: https://github.com/…». */
add_filter(
	'update_themes_github.com',
	function ( $update, $theme_data, $stylesheet ) {
		if ( 'efemerides-vallenatas' !== $stylesheet ) {
			return $update;
		}
		$release = ev_latest_release();
		if ( empty( $release['version'] ) || version_compare( $release['version'], $theme_data['Version'], '<=' ) ) {
			return $update;
		}
		return array(
			'theme'        => $stylesheet,
			'version'      => $release['version'],
			'url'          => $release['url'],
			'package'      => $release['package'],
			'requires'     => '6.4',
			'requires_php' => '8.0',
		);
	},
	10,
	3
);

/**
 * «Comprobar de nuevo» en Escritorio → Actualizaciones consulta GitHub al instante.
 * Prioridad 1: debe ejecutarse antes de wp_update_themes (prioridad 10 en el mismo gancho).
 */
add_action(
	'load-update-core.php',
	function () {
		if ( isset( $_GET['force-check'] ) ) {
			delete_site_transient( 'ev_theme_release' );
			delete_site_transient( 'update_themes' );
		}
	},
	1
);

/** Tras actualizar, olvidar la release en caché. */
add_action(
	'upgrader_process_complete',
	function ( $upgrader, $options ) {
		if ( 'theme' === ( $options['type'] ?? '' ) ) {
			delete_site_transient( 'ev_theme_release' );
		}
	},
	10,
	2
);

/** Seguridad: si el zip trae otro nombre de carpeta, renombrarla a efemerides-vallenatas. */
add_filter(
	'upgrader_source_selection',
	function ( $source, $remote_source, $upgrader, $hook_extra = array() ) {
		global $wp_filesystem;
		if ( ( $hook_extra['theme'] ?? '' ) !== 'efemerides-vallenatas' || ! $wp_filesystem ) {
			return $source;
		}
		$target = trailingslashit( $remote_source ) . 'efemerides-vallenatas/';
		if ( untrailingslashit( $source ) === untrailingslashit( $target ) ) {
			return $source;
		}
		return $wp_filesystem->move( $source, $target, true ) ? $target : $source;
	},
	10,
	4
);
