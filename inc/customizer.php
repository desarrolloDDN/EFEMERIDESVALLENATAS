<?php
/**
 * Personalizador: panel «Efemérides Vallenatas» generado desde ev_options_schema().
 *
 * @package Efemerides
 */

defined( 'ABSPATH' ) || exit;

add_action(
	'customize_register',
	function ( WP_Customize_Manager $wp_customize ) {
		$wp_customize->add_panel(
			'ev_panel',
			array(
				'title'       => 'Efemérides Vallenatas',
				'description' => 'Datos del libro, la tienda y el autor. Los valores entre [corchetes] son placeholders visibles en el sitio.',
				'priority'    => 20,
			)
		);

		foreach ( ev_options_schema() as $section_id => $section ) {
			$wp_customize->add_section( $section_id, array( 'title' => $section['title'], 'panel' => 'ev_panel' ) );

			foreach ( $section['fields'] as $key => list( $label, $default, $type ) ) {
				$sanitize = array(
					'text'     => 'sanitize_text_field',
					'textarea' => 'sanitize_textarea_field',
					'url'      => 'esc_url_raw',
					'number'   => 'absint',
					'image'    => 'absint',
				)[ $type ];

				$wp_customize->add_setting( $key, array( 'default' => $default, 'sanitize_callback' => $sanitize ) );

				if ( 'image' === $type ) {
					$wp_customize->add_control(
						new WP_Customize_Media_Control(
							$wp_customize,
							$key,
							array( 'label' => $label, 'section' => $section_id, 'mime_type' => 'image' )
						)
					);
				} else {
					$wp_customize->add_control(
						$key,
						array(
							'label'   => $label,
							'section' => $section_id,
							'type'    => 'number' === $type ? 'number' : ( 'url' === $type ? 'url' : $type ),
						)
					);
				}
			}
		}
	}
);
