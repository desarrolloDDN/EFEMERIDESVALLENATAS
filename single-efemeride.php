<?php
/**
 * Efeméride individual: /efemerides/nombre-del-evento/
 *
 * @package Efemerides
 */

get_header();
while ( have_posts() ) :
	the_post();
	$ev     = ev_ephemeris();
	$ev_all = ev_get_ephemerides( array( 'fields' => 'ids' ) );
	$ev_idx = array_search( get_the_ID(), $ev_all, true );
	$ev_n   = count( $ev_all );
	$ev_prev = $ev_n > 1 ? $ev_all[ ( $ev_idx - 1 + $ev_n ) % $ev_n ] : 0;
	$ev_next = $ev_n > 1 ? $ev_all[ ( $ev_idx + 1 ) % $ev_n ] : 0;
	$ev_date = ev_ephemeris_date_label( get_the_ID() );
	$ev_related = ev_related( get_post(), 'tipo_efemeride' );
	?>
	<article <?php post_class(); ?>>
		<header class="paper-texture relative -mt-16 overflow-hidden border-b border-line pb-16 pt-24 lg:-mt-20 lg:pt-32">
			<div aria-hidden="true" class="archive-grid absolute inset-0 [mask-image:linear-gradient(to_bottom,black,transparent)]"></div>
			<div class="container-editorial relative">
				<?php ev_breadcrumbs( array( array( 'name' => 'Efemérides', 'url' => ev_url( 'efemerides' ) ), array( 'name' => $ev_date, 'url' => get_permalink() ) ) ); ?>
				<div class="mt-12 grid gap-10 lg:grid-cols-12 lg:items-end">
					<div class="lg:col-span-4">
						<p class="animate-rise font-display text-[9rem] leading-[0.8] text-terracotta sm:text-[12rem]"><?php echo (int) $ev['day']; ?></p>
						<p class="label mt-3 text-carbon">de <?php echo esc_html( ev_month_name( $ev['month'] ) ); ?> · <?php echo esc_html( $ev['year'] ); ?></p>
					</div>
					<div class="lg:col-span-8">
						<?php if ( $ev['category'] ) : ?><p class="label text-terracotta"><?php echo esc_html( $ev['category'] ); ?></p><?php endif; ?>
						<h1 class="animate-rise mt-4 text-[2.75rem] leading-[1] sm:text-6xl lg:text-7xl"><?php the_title(); ?></h1>
						<p class="mt-6 max-w-2xl text-lg text-ink-soft"><?php echo esc_html( $ev['summary'] ); ?></p>
						<?php if ( $ev['sample'] ) : ?><span class="placeholder-tag mt-5">Efeméride de ejemplo</span><?php endif; ?>
					</div>
				</div>
			</div>
		</header>

		<div class="container-editorial grid gap-14 py-16 lg:grid-cols-12 lg:py-24">
			<div class="lg:col-span-7 lg:col-start-2">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="mb-10"><?php ev_media( get_post_thumbnail_id(), get_the_title(), '3/2', 'paper', '', 'ev-feature', true ); ?></div>
				<?php endif; ?>
				<div class="prose-editorial"><?php the_content(); ?></div>
				<div class="mt-12 border-t border-line pt-8"><?php ev_share_links( get_permalink(), $ev_date . ': ' . get_the_title() ); ?></div>
			</div>
			<aside class="lg:col-span-3 lg:col-start-10">
				<div class="border border-line bg-ivory p-6 lg:sticky lg:top-28">
					<p class="label mb-4 border-b-2 border-terracotta pb-3">Ficha de la efeméride</p>
					<dl class="space-y-3 text-sm">
						<?php
						foreach ( array(
							'Fecha'         => $ev_date,
							'Año'           => $ev['year'],
							'Lugar'         => $ev['place'],
							'Categoría'     => $ev['category'] ? $ev['category'] : '—',
							'Protagonistas' => $ev['people'] ? $ev['people'] : '—',
							'Fuente'        => $ev['source'],
						) as $ev_k => $ev_v ) :
							?>
							<div><dt class="text-ink-soft"><?php echo esc_html( $ev_k ); ?></dt><dd class="font-medium text-carbon"><?php echo esc_html( $ev_v ); ?></dd></div>
						<?php endforeach; ?>
					</dl>
				</div>
			</aside>
		</div>

		<?php if ( $ev_prev && $ev_next ) : ?>
			<nav aria-label="Efemérides anterior y siguiente" class="border-y border-line">
				<div class="container-editorial grid sm:grid-cols-2">
					<a href="<?php echo esc_url( get_permalink( $ev_prev ) ); ?>" class="group flex items-center gap-4 py-8 sm:border-r sm:border-line sm:pr-8">
						<?php echo ev_icon( 'arrow-left', 20, 'shrink-0 transition-transform group-hover:-translate-x-1' ); ?>
						<span><span class="label block text-ink-soft"><?php echo esc_html( ev_ephemeris_date_label( $ev_prev ) ); ?></span><span class="font-display text-2xl"><?php echo esc_html( get_the_title( $ev_prev ) ); ?></span></span>
					</a>
					<a href="<?php echo esc_url( get_permalink( $ev_next ) ); ?>" class="group flex items-center justify-end gap-4 border-t border-line py-8 text-right sm:border-t-0 sm:pl-8">
						<span><span class="label block text-ink-soft"><?php echo esc_html( ev_ephemeris_date_label( $ev_next ) ); ?></span><span class="font-display text-2xl"><?php echo esc_html( get_the_title( $ev_next ) ); ?></span></span>
						<?php echo ev_icon( 'arrow-right', 20, 'shrink-0 transition-transform group-hover:translate-x-1' ); ?>
					</a>
				</div>
			</nav>
		<?php endif; ?>

		<section class="container-editorial py-20">
			<?php ev_book_cta( 'Esta historia forma parte del libro', 'Efemérides Vallenatas reúne todas las fechas del año.' ); ?>
			<?php if ( $ev_related ) : ?>
				<h2 class="mt-20 text-4xl">Otras fechas para recordar</h2>
				<ul class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
					<?php foreach ( $ev_related as $ev_r ) : ?>
						<li><?php ev_ephemeris_card( $ev_r ); ?></li>
					<?php endforeach; ?>
				</ul>
			<?php endif; ?>
		</section>
	</article>
	<?php
endwhile;
get_footer();
