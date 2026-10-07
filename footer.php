<?php
/**
 * Pie del sitio.
 *
 * @package Efemerides
 */
?>
</main>

<footer class="on-dark relative overflow-hidden bg-carbon text-paper/80">
	<div aria-hidden="true" class="pointer-events-none absolute -right-40 -top-40 h-[34rem] w-[34rem] rounded-full border border-gold/10"></div>
	<div aria-hidden="true" class="pointer-events-none absolute -right-24 -top-24 h-[26rem] w-[26rem] rounded-full border border-gold/10"></div>

	<div class="container-editorial relative py-16 lg:py-24">
		<div class="grid gap-12 lg:grid-cols-12">
			<div class="lg:col-span-5">
				<p class="font-display text-4xl leading-none text-ivory sm:text-5xl">Efemérides <span class="italic">Vallenatas</span></p>
				<p class="mt-4 font-display text-xl italic text-gold-soft">“La memoria del vallenato, fecha por fecha.”</p>
				<ul class="mt-8 flex gap-2" aria-label="Redes sociales">
					<?php foreach ( ev_social_links() as $ev_s ) : ?>
						<li>
							<a href="<?php echo esc_url( $ev_s['url'] ? $ev_s['url'] : '#' ); ?>" <?php echo $ev_s['url'] ? 'target="_blank" rel="noopener"' : ''; ?> aria-label="<?php echo esc_attr( $ev_s['label'] . ( $ev_s['url'] ? '' : ' (enlace pendiente)' ) ); ?>" class="flex h-11 w-11 items-center justify-center rounded-full border border-paper/20 text-paper transition-colors hover:border-gold hover:text-gold-soft">
								<?php echo ev_icon( $ev_s['key'] ); ?>
							</a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>

			<nav aria-label="Mapa del sitio" class="lg:col-span-3">
				<p class="label mb-5 text-gold-soft">Explorar</p>
				<ul class="grid grid-cols-2 gap-x-6 gap-y-3 lg:grid-cols-1">
					<?php foreach ( ev_menu_items( 'footer' ) as list( $ev_label, $ev_url ) ) : ?>
						<li><a href="<?php echo esc_url( $ev_url ); ?>" class="link-editorial hover:text-ivory"><?php echo esc_html( $ev_label ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</nav>

			<nav aria-label="Información legal" class="lg:col-span-4">
				<p class="label mb-5 text-gold-soft">Información legal</p>
				<ul class="space-y-3">
					<?php foreach ( ev_menu_items( 'legal' ) as list( $ev_label, $ev_url ) ) : ?>
						<li><a href="<?php echo esc_url( $ev_url ); ?>" class="link-editorial hover:text-ivory"><?php echo esc_html( $ev_label ); ?></a></li>
					<?php endforeach; ?>
				</ul>
				<p class="mt-8 text-sm text-paper/60"><?php echo esc_html( ev_opt( 'contact_email' ) ); ?><br><?php echo esc_html( ev_opt( 'contact_city' ) ); ?></p>
			</nav>
		</div>

		<div class="mt-16 flex flex-col gap-3 border-t border-paper/15 pt-8 text-xs text-paper/60 sm:flex-row sm:items-center sm:justify-between">
			<p>© <?php echo esc_html( max( 2026, (int) gmdate( 'Y' ) ) ); ?> Efemérides Vallenatas. Todos los derechos reservados.</p>
			<p class="font-display text-sm italic">Cada fecha guarda una historia.</p>
		</div>
	</div>
</footer>

<?php if ( ! ( ev_has_shop() && ( is_cart() || is_checkout() ) ) && ! is_page_template( array( 'page-templates/producto-fisico.php', 'page-templates/producto-digital.php', 'page-templates/tienda.php' ) ) ) : ?>
	<div data-sticky-cta class="fixed inset-x-0 bottom-0 z-30 translate-y-full border-t border-line bg-ivory/95 px-4 pb-[max(0.75rem,env(safe-area-inset-bottom))] pt-3 backdrop-blur-md transition-transform duration-500 lg:hidden" inert>
		<div class="flex items-center gap-3">
			<div class="min-w-0 flex-1">
				<p class="truncate font-display text-lg leading-tight text-carbon">Efemérides Vallenatas</p>
				<p class="text-xs text-ink-soft">Edición física y digital</p>
			</div>
			<a href="<?php echo esc_url( ev_url( 'tienda' ) ); ?>" class="btn btn-primary !min-h-12 !px-5">Comprar libro</a>
		</div>
	</div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
