<?php
/**
 * «¿Qué encontrarás?» — Cada fecha guarda una historia.
 *
 * @package Efemerides
 */
$ev_features = array(
	array( 'Personajes', 'Historias y acontecimientos relacionados con figuras fundamentales del vallenato.' ),
	array( 'Fechas', 'Un recorrido cronológico por acontecimientos relevantes.' ),
	array( 'Historias', 'Datos y relatos que forman parte de la memoria musical del Caribe.' ),
	array( 'Memoria', 'Una mirada documental a la cultura vallenata.' ),
	array( 'Curiosidades', 'Datos que incluso los amantes del vallenato pueden desconocer.' ),
);
$ev_stats    = array( array( 12, 'meses' ), array( 365, 'días' ), array( ev_opt( 'book_count' ), 'efemérides' ) );
?>
<section aria-labelledby="features-t" class="on-dark relative overflow-hidden bg-forest py-24 text-paper lg:py-36">
	<div aria-hidden="true" class="paper-texture absolute inset-0 opacity-[0.07] mix-blend-screen"></div>
	<div class="container-editorial relative">
		<div class="grid gap-12 lg:grid-cols-12 lg:items-end">
			<div class="lg:col-span-7">
				<?php
				ev_section_heading(
					array(
						'tone'   => 'dark',
						'kicker' => '¿Qué encontrarás?',
						'id'     => 'features-t',
						'title'  => 'Cada fecha guarda <span class="italic text-gold-soft">una historia</span>',
						'intro'  => 'El vallenato también se cuenta desde sus fechas. Este libro las reúne para que puedas descubrir las historias detrás de la música.',
					)
				);
				?>
			</div>
			<div class="reveal lg:col-span-5" <?php echo ev_delay( 150 ); ?>>
				<dl class="grid grid-cols-3 divide-x divide-paper/15 border-y border-paper/15">
					<?php foreach ( $ev_stats as list( $ev_n, $ev_l ) ) : ?>
						<div class="flex flex-col-reverse px-3 py-6 text-center first:pl-0 last:pr-0">
							<dt class="label mt-2 text-gold-soft"><?php echo esc_html( $ev_l ); ?></dt>
							<dd class="font-display text-4xl text-ivory sm:text-5xl"><span class="tabular-nums" <?php echo is_numeric( $ev_n ) ? 'data-count="' . (int) $ev_n . '"' : ''; ?>><?php echo esc_html( $ev_n ); ?></span></dd>
						</div>
					<?php endforeach; ?>
				</dl>
			</div>
		</div>

		<ul class="mt-16 grid gap-px overflow-hidden border border-paper/15 bg-paper/15 sm:grid-cols-2 lg:grid-cols-5">
			<?php foreach ( $ev_features as $ev_i => list( $ev_title, $ev_text ) ) : ?>
				<li class="reveal group relative bg-forest" <?php echo ev_delay( $ev_i * 80 ); ?>>
					<div class="relative flex h-full min-h-64 flex-col p-7 transition-colors duration-500 group-hover:bg-forest-deep">
						<span class="font-display text-lg italic text-gold-soft"><?php echo esc_html( sprintf( '%02d', $ev_i + 1 ) ); ?></span>
						<h3 class="mt-auto pt-10 font-sans text-sm font-bold uppercase tracking-[0.2em] !text-ivory"><?php echo esc_html( $ev_title ); ?></h3>
						<p class="mt-3 text-[0.95rem] leading-relaxed text-paper/75"><?php echo esc_html( $ev_text ); ?></p>
						<span aria-hidden="true" class="absolute bottom-0 left-0 h-[2px] w-0 bg-gold transition-all duration-700 group-hover:w-full"></span>
						<span aria-hidden="true" class="absolute right-6 top-6 h-2 w-2 rounded-full border border-gold transition-all duration-500 group-hover:scale-150 group-hover:bg-gold"></span>
					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>
