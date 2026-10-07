<?php
/**
 * Página de producto (edición física o digital).
 * $args['type'] = physical|digital
 *
 * @package Efemerides
 */
$ev_type = $args['type'];
$ev_p    = ev_product( $ev_type );
$ev_phys = 'physical' === $ev_type;
$ev_other = $ev_phys ? 'digital' : 'physical';
ev_product_schema( $ev_type );

$ev_slides = $ev_phys
	? array( 'mockup' => 'Libro', 'portada' => 'Portada', 'contraportada' => 'Contraportada', 'interior-1' => 'Interior', 'interior-2' => 'Interior 2' )
	: array( 'tablet' => 'Tablet', 'portada' => 'Portada', 'interior-1' => 'Interior' );
?>
<div class="container-editorial pt-8 lg:pt-12">
	<?php ev_breadcrumbs( array( array( 'name' => 'Tienda', 'url' => ev_url( 'tienda' ) ), array( 'name' => $ev_p['name'], 'url' => $ev_p['url'] ) ) ); ?>
</div>

<section class="container-editorial grid gap-12 py-10 lg:grid-cols-2 lg:gap-20 lg:py-16">
	<div data-gallery>
		<div id="ev-gallery-panel" role="tabpanel" class="paper-texture relative flex aspect-square items-center justify-center overflow-hidden">
			<div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-1/4 bg-paper-deep"></div>
			<?php foreach ( array_keys( $ev_slides ) as $ev_i => $ev_key ) : ?>
				<div data-slide="<?php echo esc_attr( $ev_key ); ?>" class="animate-rise relative flex w-full items-center justify-center" <?php echo $ev_i ? 'hidden' : ''; ?>>
					<?php
					switch ( $ev_key ) {
						case 'mockup':
							ev_book_mockup( 'w-[46%]' );
							break;
						case 'tablet':
							ev_tablet_mockup( 'w-[56%]' );
							break;
						case 'portada':
							echo '<div class="ev-flat relative aspect-[2/3] w-[52%] shadow-xl">';
							ev_book_cover();
							echo '</div>';
							break;
						case 'contraportada':
							echo '<div class="ev-flat relative aspect-[2/3] w-[52%] shadow-xl">';
							ev_book_back_cover();
							echo '</div>';
							break;
						default:
							echo '<div class="ev-flat relative aspect-[4/3] w-[86%] shadow-xl">';
							ev_book_interior( 'interior-2' === $ev_key ? 2 : 1 );
							echo '</div>';
					}
					?>
				</div>
			<?php endforeach; ?>
			<?php if ( ! ev_opt( 'book_cover' ) ) : ?><span class="placeholder-tag absolute left-4 top-4 bg-ivory">Imágenes provisionales</span><?php endif; ?>
		</div>
		<div role="tablist" aria-label="Imágenes del producto" class="mt-3 grid grid-cols-5 gap-2">
			<?php foreach ( $ev_slides as $ev_key => $ev_label ) : ?>
				<button type="button" role="tab" aria-controls="ev-gallery-panel" aria-selected="<?php echo 'mockup' === $ev_key || 'tablet' === $ev_key ? 'true' : 'false'; ?>" data-show="<?php echo esc_attr( $ev_key ); ?>" class="ev-thumb paper-texture flex aspect-square items-center justify-center border border-line p-1">
					<span class="label !text-[0.55rem] !tracking-[0.1em] text-ink"><?php echo esc_html( $ev_label ); ?></span>
				</button>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="lg:py-4" id="comprar">
		<p class="label text-terracotta"><?php echo $ev_phys ? 'Libro físico' : 'Edición digital'; ?></p>
		<h1 class="mt-3 text-5xl sm:text-6xl">Efemérides Vallenatas<span class="mt-2 block text-3xl italic text-ink-soft sm:text-4xl"><?php echo esc_html( $ev_p['name'] ); ?></span></h1>
		<p class="mt-6 font-display text-xl italic"><?php echo esc_html( $ev_p['tagline'] ); ?></p>

		<div class="mt-8 flex flex-wrap items-center gap-4">
			<div class="ev-price font-display text-5xl text-carbon"><?php echo wp_kses_post( $ev_p['price_html'] ); ?></div>
			<?php if ( $ev_p['price_placeholder'] ) : ?><span class="placeholder-tag">Precio provisional</span><?php endif; ?>
		</div>
		<p class="mt-3 flex items-center gap-2 text-sm text-forest"><span aria-hidden="true" class="h-2 w-2 rounded-full bg-forest"></span><?php echo esc_html( $ev_p['availability'] ); ?></p>

		<p class="mt-8 text-lg leading-relaxed text-ink-soft"><?php echo esc_html( $ev_p['description'] ); ?></p>

		<?php get_template_part( 'template-parts/shop-notice' ); ?>

		<div class="mt-8 flex flex-col gap-3 sm:flex-row">
			<?php if ( $ev_p['wc'] ) : ?>
				<a href="<?php echo esc_url( $ev_p['buy_url'] ); ?>" rel="nofollow" class="btn btn-primary flex-1">Comprar ahora</a>
				<?php if ( $ev_phys ) : ?>
					<a href="<?php echo esc_url( $ev_p['cart_url'] ); ?>" rel="nofollow" class="btn btn-outline flex-1">Agregar al carrito</a>
				<?php endif; ?>
			<?php else : ?>
				<button type="button" disabled class="btn btn-primary flex-1">Muy pronto a la venta</button>
				<a href="<?php echo esc_url( add_query_arg( 'asunto', 'ventas', ev_url( 'contacto' ) ) ); ?>" class="btn btn-outline flex-1">Avísame cuando esté disponible</a>
			<?php endif; ?>
		</div>

		<div class="mt-8 space-y-4 border-y border-line py-6 text-sm">
			<?php if ( $ev_phys ) : ?>
				<p class="flex gap-3"><?php echo ev_icon( 'truck', 20, 'shrink-0 text-terracotta' ); ?><span><strong>Envío nacional</strong> a toda Colombia. Entrega estimada: <?php echo esc_html( ev_opt( 'ship_estimate' ) ); ?>. Costo: <?php echo esc_html( ev_opt( 'ship_cost' ) ); ?>.</span></p>
			<?php else : ?>
				<p class="flex gap-3"><?php echo ev_icon( 'device', 20, 'shrink-0 text-terracotta' ); ?><span><strong>Acceso inmediato</strong> después del pago, desde celular, tablet o computador.</span></p>
			<?php endif; ?>
			<p class="flex gap-3"><?php echo ev_icon( 'lock', 20, 'shrink-0 text-terracotta' ); ?><span><strong>Pago seguro</strong> mediante pasarela certificada. Este sitio no almacena datos de tarjetas.</span></p>
			<ul class="flex flex-wrap gap-2 pt-1" aria-label="Métodos de pago">
				<?php foreach ( array_filter( array_map( 'trim', explode( ',', ev_opt( 'payment_methods' ) ) ) ) as $ev_m ) : ?>
					<li class="rounded-sm border border-line px-2.5 py-1 text-xs text-ink-soft"><?php echo esc_html( $ev_m ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>

		<h2 class="label mt-10 text-carbon">Características</h2>
		<ul class="mt-4 grid gap-2 sm:grid-cols-2">
			<?php foreach ( $ev_p['features'] as $ev_f ) : ?>
				<li class="border-l-2 border-gold pl-3 text-[0.95rem]"><?php echo esc_html( $ev_f ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
</section>

<section class="paper-texture border-y border-line py-20">
	<div class="container-editorial grid gap-12 lg:grid-cols-2">
		<div>
			<h2 class="text-4xl sm:text-5xl">Sobre la obra</h2>
			<div class="mt-6 space-y-4 text-lg leading-relaxed text-ink-soft">
				<?php
				$ev_content = trim( get_the_content() );
				if ( $ev_content ) {
					echo '<div class="prose-editorial ev-no-dropcap">';
					the_content();
					echo '</div>';
				} else {
					foreach ( array_slice( ev_opt_lines( 'book_description' ), 0, 2 ) as $ev_d ) {
						echo '<p>' . esc_html( $ev_d ) . '</p>';
					}
				}
				?>
			</div>
		</div>
		<?php ev_book_specs( $ev_phys ); ?>
	</div>
</section>

<section aria-labelledby="faq-t" class="container-editorial py-20">
	<h2 id="faq-t" class="text-4xl sm:text-5xl">Preguntas frecuentes</h2>
	<div class="mt-10 divide-y divide-line border-y border-line">
		<?php
		for ( $ev_i = 1; $ev_i <= 5; $ev_i++ ) :
			$ev_q = ev_opt( "faq_{$ev_i}_q" );
			if ( ! $ev_q ) {
				continue;
			}
			?>
			<details class="group py-2">
				<summary class="flex min-h-14 cursor-pointer list-none items-center justify-between gap-4 font-display text-2xl [&::-webkit-details-marker]:hidden"><?php echo esc_html( $ev_q ); ?><span aria-hidden="true" class="text-3xl text-terracotta transition-transform group-open:rotate-45">+</span></summary>
				<p class="pb-6 pr-10 text-ink-soft"><?php echo esc_html( ev_opt( "faq_{$ev_i}_a" ) ); ?></p>
			</details>
		<?php endfor; ?>
	</div>
</section>

<section aria-labelledby="also-t" class="border-t border-line py-20">
	<div class="container-editorial">
		<h2 id="also-t" class="text-4xl sm:text-5xl">También te puede interesar</h2>
		<div class="mt-10 max-w-md"><?php ev_product_card( $ev_other ); ?></div>
	</div>
</section>

<div class="fixed inset-x-0 bottom-0 z-30 flex items-center gap-3 border-t border-line bg-ivory/95 px-4 pb-[max(0.75rem,env(safe-area-inset-bottom))] pt-3 backdrop-blur-md lg:hidden">
	<div class="min-w-0 flex-1">
		<p class="truncate text-sm font-semibold"><?php echo esc_html( $ev_p['name'] ); ?></p>
		<div class="ev-price text-sm text-ink-soft"><?php echo wp_kses_post( $ev_p['price_html'] ); ?></div>
	</div>
	<a href="<?php echo esc_url( $ev_p['wc'] ? $ev_p['buy_url'] : '#comprar' ); ?>" rel="nofollow" class="btn btn-primary !min-h-12">Comprar</a>
</div>
