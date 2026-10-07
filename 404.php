<?php
/**
 * Página no encontrada.
 *
 * @package Efemerides
 */

get_header();
?>
<section class="paper-texture -mt-16 flex min-h-[80svh] items-center pt-16 lg:-mt-20">
	<div class="container-editorial text-center">
		<p class="font-display text-[10rem] leading-none text-terracotta">404</p>
		<h1 class="mt-4 text-5xl">Esta fecha no está en el archivo</h1>
		<p class="mx-auto mt-4 max-w-md text-ink-soft">La página que buscas no existe o cambió de lugar. Cada fecha guarda una historia; esta todavía no.</p>
		<div class="mt-10 flex flex-col justify-center gap-3 sm:flex-row">
			<a href="<?php echo esc_url( ev_url( 'efemerides' ) ); ?>" class="btn btn-outline">Explorar efemérides</a>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-dark">Volver al inicio</a>
		</div>
	</div>
</section>
<?php
get_footer();
