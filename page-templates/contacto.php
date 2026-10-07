<?php
/**
 * Template Name: Contacto
 *
 * @package Efemerides
 */

get_header();
the_post();
ev_page_header(
	array(
		'crumbs' => array( array( 'name' => get_the_title(), 'url' => get_permalink() ) ),
		'kicker' => 'Contacto',
		'title'  => 'Conversemos sobre <span class="italic text-terracotta">la memoria</span>',
		'intro'  => 'Prensa, presentaciones, ventas institucionales o una historia que crees que merece ser recordada.',
	)
);
list( $ev_status, $ev_msg ) = ev_form_status( 'contacto-form' );
$ev_subjects = array(
	'general' => 'Consulta general',
	'prensa'  => 'Prensa y medios',
	'ventas'  => 'Ventas institucionales / al por mayor',
	'eventos' => 'Presentaciones y eventos',
	'pedido'  => 'Ayuda con un pedido',
);
$ev_current = sanitize_key( $_GET['asunto'] ?? 'general' );
$ev_legal   = get_page_by_path( 'tratamiento-de-datos' );
?>
<section class="container-editorial grid gap-14 py-16 lg:grid-cols-12 lg:py-24">
	<div class="lg:col-span-7">
		<?php if ( trim( get_the_content() ) ) : ?>
			<div class="prose-editorial ev-no-dropcap mb-10"><?php the_content(); ?></div>
		<?php endif; ?>
		<form id="contacto-form" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="relative grid gap-5 sm:grid-cols-2" data-ajax-form>
			<?php ev_form_hidden( 'ev_contact' ); ?>
			<div><label for="c-name" class="field-label">Nombre</label><input id="c-name" name="name" required autocomplete="name" class="field"></div>
			<div><label for="c-email" class="field-label">Correo electrónico</label><input id="c-email" name="email" type="email" required autocomplete="email" class="field"></div>
			<div class="sm:col-span-2">
				<label for="c-subject" class="field-label">Asunto</label>
				<select id="c-subject" name="subject" class="field">
					<?php foreach ( $ev_subjects as $ev_k => $ev_l ) : ?>
						<option value="<?php echo esc_attr( $ev_l ); ?>" <?php selected( $ev_current, $ev_k ); ?>><?php echo esc_html( $ev_l ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="sm:col-span-2"><label for="c-message" class="field-label">Mensaje</label><textarea id="c-message" name="message" required rows="6" minlength="10" class="field"></textarea></div>
			<label class="flex items-start gap-3 text-sm sm:col-span-2">
				<input type="checkbox" name="consent" value="1" required class="mt-0.5 h-5 w-5 shrink-0 accent-[#a95132]">
				<span>Autorizo el tratamiento de mis datos según la <a href="<?php echo esc_url( $ev_legal ? get_permalink( $ev_legal ) : home_url( '/tratamiento-de-datos/' ) ); ?>" class="underline">política de tratamiento de datos</a>.</span>
			</label>
			<div class="sm:col-span-2">
				<button type="submit" class="btn btn-dark w-full sm:w-auto" data-loading-text="Enviando…">Enviar mensaje</button>
				<p role="status" aria-live="polite" data-form-msg class="mt-4 text-sm <?php echo 'error' === $ev_status ? 'text-terracotta-deep' : 'text-forest'; ?>"><?php echo esc_html( $ev_msg ); ?></p>
			</div>
		</form>
	</div>
	<aside class="space-y-8 lg:col-span-4 lg:col-start-9">
		<?php foreach ( array( 'Correo' => ev_opt( 'contact_email' ), 'Teléfono / WhatsApp' => ev_opt( 'contact_phone' ), 'Ciudad' => ev_opt( 'contact_city' ) ) as $ev_k => $ev_v ) : ?>
			<div class="border-t border-line pt-4">
				<p class="label text-ink-soft"><?php echo esc_html( $ev_k ); ?></p>
				<p class="mt-1 break-words font-display text-2xl <?php echo ev_is_placeholder( $ev_v ) || false !== strpos( $ev_v, '[' ) ? 'text-terracotta' : 'text-carbon'; ?>"><?php echo esc_html( $ev_v ); ?></p>
			</div>
		<?php endforeach; ?>
		<div class="border-t border-line pt-4">
			<p class="label text-ink-soft">Redes</p>
			<ul class="mt-3 flex gap-2">
				<?php foreach ( ev_social_links() as $ev_s ) : ?>
					<li><a href="<?php echo esc_url( $ev_s['url'] ? $ev_s['url'] : '#' ); ?>" aria-label="<?php echo esc_attr( $ev_s['label'] ); ?>" class="flex h-11 w-11 items-center justify-center rounded-full border border-line hover:border-terracotta hover:text-terracotta"><?php echo ev_icon( $ev_s['key'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</aside>
</section>
<?php
get_footer();
