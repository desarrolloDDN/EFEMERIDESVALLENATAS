<?php
/**
 * Efemérides destacadas.
 *
 * @package Efemerides
 */
$ev_items = ev_featured_ephemerides( 6 );
$ev_arch  = ev_url( 'efemerides' );
?>
<section aria-labelledby="efem-t" class="py-24 lg:py-36">
	<div class="container-editorial">
		<div class="flex flex-col gap-10 lg:flex-row lg:items-end lg:justify-between">
			<?php
			ev_section_heading(
				array(
					'kicker' => 'Efemérides Vallenatas',
					'id'     => 'efem-t',
					'title'  => 'Una fecha. Una historia. <span class="italic">Una memoria.</span>',
					'intro'  => 'Recorre el año como se recorre un archivo: mes a mes, día a día.',
				)
			);
			?>
			<a href="<?php echo esc_url( $ev_arch ); ?>" class="btn btn-outline shrink-0">Explorar el calendario</a>
		</div>
		<nav aria-label="Ir a un mes" class="reveal scrollbar-none -mx-5 mt-12 overflow-x-auto border-y border-line px-5 sm:mx-0 sm:px-0">
			<ul class="flex min-w-max justify-between gap-1 lg:min-w-0">
				<?php foreach ( ev_months() as $ev_n => $ev_m ) : ?>
					<li><a href="<?php echo esc_url( add_query_arg( 'mes', $ev_n, $ev_arch ) ); ?>" class="label block px-3 py-4 !tracking-[0.12em] text-ink-soft transition-colors hover:text-terracotta"><?php echo esc_html( mb_substr( $ev_m, 0, 3 ) ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</nav>
		<?php if ( $ev_items ) : ?>
			<ul class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
				<?php foreach ( $ev_items as $ev_i => $ev_post ) : ?>
					<li class="reveal" <?php echo ev_delay( ( $ev_i % 3 ) * 90 ); ?>><?php ev_ephemeris_card( $ev_post ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php else : ?>
			<p class="mt-10 border border-dashed border-line px-6 py-12 text-center text-ink-soft">Aún no hay efemérides publicadas. Añádelas desde <strong>Efemérides → Añadir efeméride</strong>.</p>
		<?php endif; ?>
	</div>
</section>
