<?php
/**
 * Formularios: boletín y contacto.
 * Funcionan con JavaScript (AJAX) y sin él (envío normal + redirección).
 * Boletín: guarda el correo como «Suscriptor» (Herramientas → Suscriptores). No envía correos todavía.
 * Contacto: guarda el mensaje (Herramientas → Mensajes) y lo envía al correo del administrador.
 *
 * @package Efemerides
 */

defined( 'ABSPATH' ) || exit;

foreach ( array( 'ev_newsletter', 'ev_contact' ) as $ev_action ) {
	add_action( "wp_ajax_{$ev_action}", "{$ev_action}_handler" );
	add_action( "wp_ajax_nopriv_{$ev_action}", "{$ev_action}_handler" );
	add_action( "admin_post_{$ev_action}", "{$ev_action}_handler" );
	add_action( "admin_post_nopriv_{$ev_action}", "{$ev_action}_handler" );
}

/** Respuesta común AJAX / no-JS. */
function ev_form_respond( $ok, $message, $anchor ) {
	if ( wp_doing_ajax() ) {
		$ok ? wp_send_json_success( array( 'message' => $message ) ) : wp_send_json_error( array( 'message' => $message ), 400 );
	}
	$back = wp_get_referer() ? wp_get_referer() : home_url( '/' );
	$back = add_query_arg( array( 'ev_form' => $ok ? 'ok' : 'error', 'ev_src' => $anchor, 'ev_msg' => rawurlencode( $message ) ), remove_query_arg( array( 'ev_form', 'ev_src', 'ev_msg' ), $back ) );
	wp_safe_redirect( $back . '#' . $anchor );
	exit;
}

function ev_form_guard( $anchor ) {
	if ( ! isset( $_POST['ev_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['ev_nonce'] ), 'ev_forms' ) ) {
		ev_form_respond( false, 'La sesión expiró. Recarga la página e inténtalo de nuevo.', $anchor );
	}
	// Honeypot anti-spam: los humanos no ven este campo.
	if ( ! empty( $_POST['ev_website'] ) ) {
		ev_form_respond( true, 'Gracias.', $anchor );
	}
}

function ev_newsletter_handler() {
	ev_form_guard( 'boletin' );
	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	if ( ! is_email( $email ) ) {
		ev_form_respond( false, 'Escribe un correo electrónico válido.', 'boletin' );
	}
	if ( empty( $_POST['consent'] ) ) {
		ev_form_respond( false, 'Debes aceptar la política de tratamiento de datos.', 'boletin' );
	}
	$exists = get_posts( array( 'post_type' => 'suscriptor', 'title' => $email, 'post_status' => 'any', 'fields' => 'ids', 'posts_per_page' => 1 ) );
	if ( ! $exists ) {
		wp_insert_post(
			array(
				'post_type'   => 'suscriptor',
				'post_title'  => $email,
				'post_status' => 'private',
				'meta_input'  => array( 'ev_consent' => current_time( 'mysql' ) ),
			)
		);
	}
	/**
	 * Punto de integración con Mailchimp, Brevo, MailPoet, etc.
	 */
	do_action( 'ev_newsletter_subscribed', $email );
	ev_form_respond( true, '¡Gracias! Pronto recibirás nuevas efemérides en tu correo.', 'boletin' );
}

function ev_contact_handler() {
	ev_form_guard( 'contacto-form' );
	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$subject = sanitize_text_field( wp_unslash( $_POST['subject'] ?? 'Consulta general' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

	if ( ! $name || ! is_email( $email ) || mb_strlen( $message ) < 10 ) {
		ev_form_respond( false, 'Completa nombre, correo válido y un mensaje de al menos 10 caracteres.', 'contacto-form' );
	}
	if ( empty( $_POST['consent'] ) ) {
		ev_form_respond( false, 'Debes autorizar el tratamiento de tus datos.', 'contacto-form' );
	}

	wp_insert_post(
		array(
			'post_type'    => 'mensaje',
			'post_title'   => sprintf( '%s — %s <%s>', $subject, $name, $email ),
			'post_content' => $message,
			'post_status'  => 'private',
		)
	);
	wp_mail(
		get_option( 'admin_email' ),
		'[Efemérides Vallenatas] ' . $subject,
		"Nombre: $name\nCorreo: $email\nAsunto: $subject\n\n$message",
		array( 'Reply-To: ' . $name . ' <' . $email . '>' )
	);
	ev_form_respond( true, 'Mensaje recibido. Te responderemos pronto.', 'contacto-form' );
}

/** Campos ocultos comunes. */
function ev_form_hidden( $action ) {
	printf( '<input type="hidden" name="action" value="%s">', esc_attr( $action ) );
	printf( '<input type="hidden" name="ev_nonce" value="%s">', esc_attr( wp_create_nonce( 'ev_forms' ) ) );
	echo '<div aria-hidden="true" style="position:absolute;left:-9999px"><label>No llenar<input type="text" name="ev_website" tabindex="-1" autocomplete="off"></label></div>';
}

/** Mensaje tras envío sin JavaScript para el formulario indicado. */
function ev_form_status( $src ) {
	if ( empty( $_GET['ev_form'] ) || ( $_GET['ev_src'] ?? '' ) !== $src ) {
		return array( '', '' );
	}
	return array( sanitize_key( $_GET['ev_form'] ), sanitize_text_field( wp_unslash( rawurldecode( $_GET['ev_msg'] ?? '' ) ) ) );
}
