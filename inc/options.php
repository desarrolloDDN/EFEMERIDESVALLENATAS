<?php
/**
 * Registro central de opciones del tema (Personalizador).
 * Todo valor entre [corchetes] es un placeholder hasta recibir la información real.
 *
 * @package Efemerides
 */

defined( 'ABSPATH' ) || exit;

/**
 * Definición de secciones y campos: clave => [etiqueta, valor por defecto, tipo].
 * Tipos: text, textarea, image, number, url, email.
 */
function ev_options_schema() {
	return array(
		'ev_book'     => array(
			'title'  => __( 'Libro: ficha técnica', 'efemerides' ),
			'fields' => array(
				'book_author'      => array( 'Autor', '[Nombre del autor]', 'text' ),
				'book_publisher'   => array( 'Editorial', '[Editorial]', 'text' ),
				'book_year'        => array( 'Año de publicación', '[Año]', 'text' ),
				'book_isbn'        => array( 'ISBN', '[ISBN]', 'text' ),
				'book_pages'       => array( 'Número de páginas', '[Cantidad]', 'text' ),
				'book_language'    => array( 'Idioma', 'Español', 'text' ),
				'book_binding'     => array( 'Encuadernación', '[Tipo de encuadernación]', 'text' ),
				'book_dimensions'  => array( 'Dimensiones', '[Dimensiones]', 'text' ),
				'book_weight'      => array( 'Peso', '[Peso]', 'text' ),
				'book_count'       => array( 'Número de efemérides', '[N.º]', 'text' ),
				'book_description' => array(
					'Descripción (un párrafo por línea)',
					"Efemérides Vallenatas es un calendario de la memoria: un recorrido, día por día, por los nacimientos, despedidas, canciones, discos, festivales y acontecimientos que han dado forma a la historia del vallenato.\nCada fecha abre una puerta a una historia. Algunas son conocidas; otras permanecían guardadas en archivos, conversaciones y recuerdos. Juntas construyen un retrato documental de una de las expresiones más importantes de la identidad del Caribe colombiano.\n[Descripción ampliada del libro — pendiente de recibir el texto oficial del autor o la editorial.]",
					'textarea',
				),
				'book_back_text'   => array( 'Texto de contraportada', '[Texto de contraportada — pendiente de recibir el texto oficial.]', 'textarea' ),
				'book_cover'       => array( 'Portada oficial (imagen 2:3)', '', 'image' ),
				'book_back_cover'  => array( 'Contraportada oficial (imagen 2:3)', '', 'image' ),
			),
		),
		'ev_shop'     => array(
			'title'  => __( 'Tienda y ediciones', 'efemerides' ),
			'fields' => array(
				'phys_wc_id'       => array( 'ID del producto WooCommerce — edición física', '', 'number' ),
				'phys_price_text'  => array( 'Precio mostrado si no hay producto WooCommerce — física', '$XX.XXX COP', 'text' ),
				'phys_features'    => array( 'Características — física (una por línea)', "Libro físico\nPáginas: [Cantidad]\nFormato: [Dimensiones]\nEncuadernación: [Tipo de encuadernación]\nEnvío nacional a toda Colombia\nEntrega estimada: [X a Y] días hábiles", 'textarea' ),
				'phys_description' => array( 'Descripción — física', 'Una edición para leer con calma, subrayar, regalar y conservar en la biblioteca. Pensada como un libro de consulta al que se vuelve una y otra vez.', 'textarea' ),
				'ship_estimate'    => array( 'Tiempo estimado de entrega', '[X a Y] días hábiles', 'text' ),
				'ship_cost'        => array( 'Costo de envío', '[Costo de envío]', 'text' ),
				'dig_wc_id'        => array( 'ID del producto WooCommerce — edición digital', '', 'number' ),
				'dig_price_text'   => array( 'Precio mostrado si no hay producto WooCommerce — digital', '$XX.XXX COP', 'text' ),
				'dig_features'     => array( 'Características — digital (una por línea)', "Formato: [PDF / EPUB]\nLectura en celular, tablet y computador\nAcceso inmediato tras el pago\nBúsqueda por fecha y personaje\n[Condiciones de descarga / DRM]", 'textarea' ),
				'dig_description'  => array( 'Descripción — digital', 'Lleva la memoria vallenata contigo y consulta la obra desde cualquier dispositivo.', 'textarea' ),
				'payment_methods'  => array( 'Medios de pago visibles (separados por coma)', 'Tarjeta crédito, Tarjeta débito, PSE, Nequi, Daviplata', 'text' ),
			),
		),
		'ev_author'   => array(
			'title'  => __( 'Autor', 'efemerides' ),
			'fields' => array(
				'author_role'      => array( 'Profesión / oficio', '[Profesión / oficio del autor]', 'text' ),
				'author_photo'     => array( 'Fotografía profesional', '', 'image' ),
				'author_short_bio' => array( 'Biografía breve (Home)', '[Biografía breve del autor — dos o tres líneas que presenten quién es y cuál es su relación con el vallenato.]', 'textarea' ),
				'author_quote'     => array( 'Cita destacada', '[Cita del autor sobre la memoria y el vallenato]', 'textarea' ),
				'origin_intro'     => array( 'Origen del libro: introducción', '[Relato del autor: la pregunta, el momento o la conversación que encendió la idea de Efemérides Vallenatas.]', 'textarea' ),
				'step_idea'        => array( 'Línea de tiempo — Idea', '[Cómo y cuándo nació la idea del libro.]', 'textarea' ),
				'step_investigacion' => array( 'Línea de tiempo — Investigación', '[Fuentes consultadas, entrevistas, viajes y método de trabajo.]', 'textarea' ),
				'step_archivo'     => array( 'Línea de tiempo — Archivo', '[Organización del material: prensa, discos, documentos, testimonios.]', 'textarea' ),
				'step_escritura'   => array( 'Línea de tiempo — Escritura', '[Cómo se escribió cada efeméride y cuánto tiempo tomó.]', 'textarea' ),
				'step_edicion'     => array( 'Línea de tiempo — Edición', '[Revisión, verificación de datos y diseño editorial.]', 'textarea' ),
				'step_publicacion' => array( 'Línea de tiempo — Publicación', '[Lanzamiento y llegada del libro a los lectores.]', 'textarea' ),
			),
		),
		'ev_faq'      => array(
			'title'  => __( 'Preguntas frecuentes (tienda)', 'efemerides' ),
			'fields' => array(
				'faq_1_q' => array( 'Pregunta 1', '¿A qué ciudades realizan envíos?', 'text' ),
				'faq_1_a' => array( 'Respuesta 1', 'La edición física se envía a todo el territorio colombiano. [Confirmar operador logístico y cobertura.]', 'textarea' ),
				'faq_2_q' => array( 'Pregunta 2', '¿Cuánto tarda en llegar el libro?', 'text' ),
				'faq_2_a' => array( 'Respuesta 2', 'El tiempo estimado es de [X a Y] días hábiles según la ciudad de destino. Recibirás un número de guía por correo.', 'textarea' ),
				'faq_3_q' => array( 'Pregunta 3', '¿Cómo recibo la edición digital?', 'text' ),
				'faq_3_a' => array( 'Respuesta 3', 'Después de confirmar el pago recibirás un correo con el enlace de descarga. También quedará disponible en «Mi cuenta → Descargas». [Confirmar formato y número de descargas permitidas.]', 'textarea' ),
				'faq_4_q' => array( 'Pregunta 4', '¿Qué medios de pago aceptan?', 'text' ),
				'faq_4_a' => array( 'Respuesta 4', 'Tarjetas débito y crédito, PSE y billeteras digitales, según la pasarela de pago habilitada. [Confirmar pasarela definitiva.]', 'textarea' ),
				'faq_5_q' => array( 'Pregunta 5', '¿Puedo pedir el libro firmado o comprar al por mayor?', 'text' ),
				'faq_5_a' => array( 'Respuesta 5', '[Política de ejemplares firmados y ventas institucionales pendiente.] Escríbenos desde la página de contacto.', 'textarea' ),
			),
		),
		'ev_contact'  => array(
			'title'  => __( 'Contacto y redes', 'efemerides' ),
			'fields' => array(
				'contact_email'    => array( 'Correo de contacto público', '[correo@dominio.com]', 'text' ),
				'contact_phone'    => array( 'Teléfono / WhatsApp', '[+57 000 000 0000]', 'text' ),
				'contact_city'     => array( 'Ciudad', '[Ciudad], Colombia', 'text' ),
				'social_instagram' => array( 'Instagram (URL)', '', 'url' ),
				'social_facebook'  => array( 'Facebook (URL)', '', 'url' ),
				'social_youtube'   => array( 'YouTube (URL)', '', 'url' ),
				'social_tiktok'    => array( 'TikTok (URL)', '', 'url' ),
				'press_kit'        => array( 'Kit de prensa (URL de descarga)', '', 'url' ),
			),
		),
	);
}

/** Valor de una opción con su valor por defecto. */
function ev_opt( $key ) {
	static $defaults = null;
	if ( null === $defaults ) {
		$defaults = array();
		foreach ( ev_options_schema() as $section ) {
			foreach ( $section['fields'] as $k => $f ) {
				$defaults[ $k ] = $f[1];
			}
		}
	}
	$value = get_theme_mod( $key, $defaults[ $key ] ?? '' );
	// Un campo vaciado en el Personalizador vuelve a su placeholder.
	return ( '' === $value && isset( $defaults[ $key ] ) ) ? $defaults[ $key ] : $value;
}

/** Opción multilínea como array (una línea por elemento). */
function ev_opt_lines( $key ) {
	return array_values( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) ev_opt( $key ) ) ) ) );
}

/** ¿El valor es un placeholder entre corchetes? */
function ev_is_placeholder( $value ) {
	return (bool) preg_match( '/^\[.*\]$/s', trim( (string) $value ) );
}
