<?php
/**
 * Hero: el libro como protagonista sobre una mesa de archivo.
 *
 * @package Efemerides
 */
$ev_phys = ev_product( 'physical' );
$ev_dig  = ev_product( 'digital' );
?>
<section aria-labelledby="hero-title" class="paper-texture relative -mt-16 overflow-hidden pt-16 lg:-mt-20 lg:pt-20">
	<div aria-hidden="true" class="archive-grid absolute inset-0 [mask-image:radial-gradient(ellipse_at_70%_45%,black_20%,transparent_75%)]"></div>

	<div class="container-editorial relative grid gap-x-12 pb-14 pt-6 sm:pt-12 lg:min-h-[calc(100svh-5rem)] lg:grid-cols-12 lg:grid-rows-[1fr_auto_auto_1fr] lg:pb-20">
		<div class="text-center lg:col-span-6 lg:row-start-2 lg:text-left">
			<p class="label animate-rise mb-5 text-terracotta">Libro · Historia · Música · Caribe</p>
			<h1 id="hero-title" class="animate-rise text-[3.1rem] leading-[0.92] xs:text-6xl sm:text-7xl xl:text-[6.4rem]" <?php echo ev_delay( 120 ); ?>>
				Efemérides
				<span class="block italic text-terracotta">Vallenatas</span>
			</h1>
			<p class="animate-rise mx-auto mt-5 max-w-md font-display text-2xl italic leading-snug text-ink sm:text-[1.75rem] lg:mx-0" <?php echo ev_delay( 240 ); ?>>La memoria del vallenato contada fecha por fecha.</p>
		</div>

		<div class="relative mx-auto mt-4 w-full max-w-[17.5rem] sm:mt-10 sm:max-w-md lg:col-span-6 lg:col-start-7 lg:row-span-4 lg:row-start-1 lg:mt-0 lg:max-w-none lg:self-center">
			<div class="relative aspect-[5/5.2]">
				<div aria-hidden="true" class="absolute left-[2%] top-[8%] w-[46%] rotate-[-7deg] border border-line bg-ivory p-[4%] shadow-[0_10px_30px_-12px_rgb(23_23_23/.25)]">
					<div class="flex items-center justify-between border-b border-terracotta/40 pb-2">
						<span class="label !text-[0.55rem] text-terracotta">Ficha Nº 001</span>
						<span class="label !text-[0.55rem] text-ink-soft">Archivo</span>
					</div>
					<?php for ( $i = 0; $i < 6; $i++ ) : ?>
						<span class="mt-[7%] block h-px bg-ink/15" style="width:<?php echo 2 === $i % 3 ? '60%' : '100%'; ?>"></span>
					<?php endfor; ?>
				</div>
				<div aria-hidden="true" class="absolute right-0 top-0 w-[34%] rotate-[6deg] bg-ivory text-center shadow-[0_10px_30px_-12px_rgb(23_23_23/.3)]">
					<div class="label bg-terracotta py-[6%] !text-[0.55rem] text-ivory">12 meses</div>
					<div class="py-[10%] font-display text-[clamp(2rem,7vw,3.6rem)] leading-none text-carbon">365</div>
					<div class="label pb-[10%] !text-[0.5rem] text-ink-soft">días de memoria</div>
				</div>
				<svg aria-hidden="true" viewBox="0 0 120 120" class="ev-spin absolute bottom-[4%] right-[2%] w-[26%] text-gold">
					<defs><path id="ev-seal" d="M60 60 m-44 0 a44 44 0 1 1 88 0 a44 44 0 1 1 -88 0"/></defs>
					<circle cx="60" cy="60" r="56" fill="none" stroke="currentColor" stroke-width=".8"/>
					<circle cx="60" cy="60" r="32" fill="none" stroke="currentColor" stroke-width=".8"/>
					<text font-size="9.5" letter-spacing="3.2" fill="currentColor" font-family="Manrope, sans-serif" font-weight="600"><textPath href="#ev-seal">ARCHIVO · MEMORIA · VALLENATA ·</textPath></text>
					<circle cx="60" cy="60" r="4" fill="#A95132"/>
				</svg>
				<div class="absolute left-[24%] top-[10%] w-[50%] will-change-transform" data-parallax="0.05">
					<?php ev_book_mockup(); ?>
				</div>
			</div>
		</div>

		<div class="mt-6 flex flex-col text-center sm:mt-10 lg:col-span-6 lg:row-start-3 lg:mt-8 lg:text-left">
			<p class="animate-rise order-2 mx-auto mt-8 max-w-lg text-[1.05rem] leading-relaxed text-ink-soft lg:order-1 lg:mx-0 lg:mt-0" <?php echo ev_delay( 360 ); ?>>Una obra para descubrir, recordar y preservar las historias, personajes y acontecimientos que hacen parte de la memoria del vallenato.</p>
			<div class="animate-rise order-1 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:justify-center lg:order-2 lg:mt-8 lg:justify-start" <?php echo ev_delay( 420 ); ?>>
				<a href="<?php echo esc_url( $ev_phys['url'] ); ?>" class="btn btn-primary whitespace-nowrap">Comprar libro físico</a>
				<a href="<?php echo esc_url( $ev_dig['url'] ); ?>" class="btn btn-outline whitespace-nowrap">Adquirir edición digital</a>
			</div>
			<p class="order-3 mt-5 flex items-center justify-center gap-2 text-sm text-ink-soft lg:justify-start">
				<span aria-hidden="true" class="h-1.5 w-1.5 rounded-full bg-forest"></span>Disponible en edición física y digital.
			</p>
		</div>
	</div>
</section>
