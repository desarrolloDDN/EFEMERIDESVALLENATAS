<?php
/**
 * Boletín: «Recibe nuevas efemérides».
 *
 * @package Efemerides
 */
list( $ev_status, $ev_msg ) = ev_form_status( 'boletin' );
$ev_legal                   = get_page_by_path( 'tratamiento-de-datos' );
?>
<section id="boletin" aria-labelledby="boletin-t" class="paper-texture relative overflow-hidden border-y border-line py-20 lg:py-28">
	<div aria-hidden="true" class="archive-grid absolute inset-0 opacity-70"></div>
	<div class="reveal container-editorial relative grid items-end gap-10 lg:grid-cols-2">
		<div>
			<p class="label mb-5 text-terracotta">Boletín</p>
			<h2 id="boletin-t" class="text-[2.5rem] sm:text-5xl lg:text-6xl">Recibe nuevas <span class="italic">efemérides</span></h2>
			<p class="mt-5 max-w-lg text-lg text-ink-soft">Suscríbete y recibe historias, fechas y curiosidades de la memoria vallenata directamente en tu correo.</p>
		</div>
		<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" class="relative w-full" data-ajax-form>
			<?php ev_form_hidden( 'ev_newsletter' ); ?>
			<label for="ev-nl-email" class="field-label">Tu correo electrónico</label>
			<div class="flex flex-col gap-3 sm:flex-row">
				<input id="ev-nl-email" name="email" type="email" required autocomplete="email" inputmode="email" placeholder="nombre@correo.com" class="field flex-1">
				<button type="submit" class="btn btn-dark" data-loading-text="Enviando…">Suscribirme</button>
			</div>
			<label class="mt-4 flex items-start gap-3 text-xs text-ink-soft">
				<input type="checkbox" name="consent" value="1" required class="mt-0.5 h-5 w-5 shrink-0 accent-[#a95132]">
				<span>Acepto la <a href="<?php echo esc_url( $ev_legal ? get_permalink( $ev_legal ) : home_url( '/tratamiento-de-datos/' ) ); ?>" class="underline underline-offset-2">política de tratamiento de datos</a>. Puedes darte de baja cuando quieras.</span>
			</label>
			<p role="status" aria-live="polite" data-form-msg class="mt-3 min-h-6 text-sm <?php echo 'error' === $ev_status ? 'text-terracotta-deep' : 'text-forest'; ?>"><?php echo esc_html( $ev_msg ); ?></p>
		</form>
	</div>
</section>
