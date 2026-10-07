<?php
/**
 * Calendario interactivo de efemérides.
 * Las tarjetas se renderizan en el servidor (SEO); main.js filtra por mes,
 * categoría y búsqueda sin recargar (?mes=3&categoria=discos&q=…).
 *
 * @package Efemerides
 */

get_header();
$ev_arch   = ev_url( 'efemerides' );
$ev_term   = is_tax( 'tipo_efemeride' ) ? get_queried_object() : null;
$ev_posts  = $GLOBALS['wp_query']->posts;
$ev_months = ev_months();
$ev_counts = array_fill( 1, 12, 0 );
$ev_sample = false;
foreach ( $ev_posts as $ev_p ) {
	$ev_m = (int) get_post_meta( $ev_p->ID, 'ev_month', true );
	if ( $ev_m ) {
		++$ev_counts[ $ev_m ];
	}
	$ev_sample = $ev_sample || get_post_meta( $ev_p->ID, 'ev_sample', true );
}
$ev_crumbs = array( array( 'name' => 'Efemérides', 'url' => $ev_arch ) );
if ( $ev_term ) {
	$ev_crumbs[] = array( 'name' => $ev_term->name, 'url' => get_term_link( $ev_term ) );
}

ev_page_header(
	array(
		'crumbs' => $ev_crumbs,
		'kicker' => $ev_term ? 'Efemérides · ' . $ev_term->name : 'Efemérides Vallenatas',
		'title'  => 'Una fecha. Una historia. <span class="italic text-terracotta">Una memoria.</span>',
		'intro'  => 'Filtra por mes o categoría, o busca por nombre y acontecimiento. Cada fecha guarda una historia.',
		'note'   => $ev_sample ? 'Incluye fechas y contenidos de ejemplo — pendientes del libro' : '',
	)
);
?>
<section class="container-editorial pb-24 pt-10" data-ephemerides>
	<nav aria-label="Meses" class="ev-month-nav sticky top-16 z-20 -mx-5 border-y border-line bg-ivory/95 backdrop-blur-md sm:mx-0 lg:top-20">
		<ul class="scrollbar-none flex overflow-x-auto px-2 sm:px-0">
			<li class="contents">
				<button type="button" data-month="0" aria-pressed="true" class="ev-tab relative flex min-h-12 shrink-0 flex-col items-center justify-center px-3 lg:flex-1">
					<span class="label !tracking-[0.12em]">Todo el año</span>
				</button>
			</li>
			<?php foreach ( $ev_months as $ev_n => $ev_name ) : ?>
				<li class="contents">
					<button type="button" data-month="<?php echo (int) $ev_n; ?>" aria-pressed="false" aria-label="<?php echo esc_attr( sprintf( '%s (%d)', $ev_name, $ev_counts[ $ev_n ] ) ); ?>" class="ev-tab relative flex min-h-12 shrink-0 flex-col items-center justify-center px-3 lg:flex-1">
						<span class="label !tracking-[0.12em]"><span class="xl:hidden"><?php echo esc_html( mb_substr( $ev_name, 0, 3 ) ); ?></span><span class="hidden xl:inline"><?php echo esc_html( $ev_name ); ?></span></span>
						<span class="text-[0.65rem] text-ink-soft"><?php echo (int) $ev_counts[ $ev_n ]; ?></span>
					</button>
				</li>
			<?php endforeach; ?>
		</ul>
	</nav>

	<div class="mt-8 grid gap-5 lg:grid-cols-[minmax(0,22rem)_1fr] lg:items-start">
		<div class="relative">
			<label for="ev-search" class="sr-only">Buscar efemérides por nombre o acontecimiento</label>
			<?php echo ev_icon( 'search', 20, 'pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-ink-soft' ); ?>
			<input id="ev-search" type="search" data-search placeholder="Buscar por nombre o acontecimiento" autocomplete="off" class="field !pl-12">
		</div>
		<?php if ( ! $ev_term ) : ?>
			<div role="group" aria-label="Filtrar por categoría" class="scrollbar-none -mx-5 flex gap-2 overflow-x-auto px-5 pb-1 sm:mx-0 sm:flex-wrap sm:px-0">
				<button type="button" data-category="" aria-pressed="true" class="ev-chip">Todas</button>
				<?php foreach ( get_terms( array( 'taxonomy' => 'tipo_efemeride', 'hide_empty' => false ) ) as $ev_t ) : ?>
					<button type="button" data-category="<?php echo esc_attr( $ev_t->slug ); ?>" aria-pressed="false" class="ev-chip"><?php echo esc_html( $ev_t->name ); ?></button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>

	<p role="status" aria-live="polite" data-count-status class="mt-8 text-sm text-ink-soft"><?php echo esc_html( sprintf( '%d efemérides', count( $ev_posts ) ) ); ?></p>

	<div class="mt-4 space-y-14" data-groups>
		<?php
		foreach ( $ev_months as $ev_n => $ev_name ) :
			$ev_in_month = array_filter( $ev_posts, fn( $p ) => (int) get_post_meta( $p->ID, 'ev_month', true ) === $ev_n );
			if ( ! $ev_in_month ) {
				continue;
			}
			?>
			<section data-group="<?php echo (int) $ev_n; ?>" aria-labelledby="<?php echo esc_attr( 'mes-' . $ev_n ); ?>">
				<h2 id="<?php echo esc_attr( 'mes-' . $ev_n ); ?>" class="mb-6 flex items-baseline gap-4 font-display text-4xl capitalize sm:text-5xl">
					<?php echo esc_html( $ev_name ); ?>
					<span aria-hidden="true" class="h-px flex-1 translate-y-[-0.3em] bg-line"></span>
					<span class="label text-ink-soft"><?php echo esc_html( sprintf( '%02d', $ev_n ) ); ?></span>
				</h2>
				<ul class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
					<?php
					foreach ( $ev_in_month as $ev_p ) :
						$ev_e = ev_ephemeris( $ev_p );
						$ev_haystack = implode( ' ', array( $ev_e['title'], $ev_e['summary'], $ev_e['place'], $ev_e['people'], $ev_e['category'], wp_strip_all_tags( $ev_p->post_content ) ) );
						?>
						<li data-item data-month="<?php echo (int) $ev_e['month']; ?>" data-category="<?php echo esc_attr( $ev_e['cat_slug'] ); ?>" data-text="<?php echo esc_attr( remove_accents( mb_strtolower( $ev_haystack ) ) ); ?>">
							<?php ev_ephemeris_card( $ev_p ); ?>
						</li>
					<?php endforeach; ?>
				</ul>
			</section>
		<?php endforeach; ?>
	</div>

	<div data-empty hidden class="mt-6 border border-dashed border-line px-6 py-16 text-center">
		<p class="font-display text-3xl">Ninguna fecha coincide</p>
		<p class="mt-2 text-ink-soft">Prueba con otro mes, otra categoría o una búsqueda diferente.</p>
		<button type="button" data-reset class="btn btn-outline mt-6">Limpiar filtros</button>
	</div>

	<?php if ( ! $ev_posts ) : ?>
		<p class="mt-6 border border-dashed border-line px-6 py-16 text-center text-ink-soft">Aún no hay efemérides publicadas.</p>
	<?php endif; ?>
</section>
<?php
get_template_part( 'template-parts/newsletter' );
get_footer();
