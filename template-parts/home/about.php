<?php
/**
 * Sobre el libro + ficha.
 *
 * @package Efemerides
 */
$ev_desc = ev_opt_lines( 'book_description' );
?>
<section id="sobre-el-libro" aria-labelledby="sobre-t" class="py-24 lg:py-36">
	<div class="container-editorial grid items-center gap-14 lg:grid-cols-12 lg:gap-20">
		<div class="reveal lg:col-span-5">
			<div class="paper-texture relative flex aspect-[4/5] items-center justify-center overflow-hidden">
				<div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-[28%] bg-paper-deep"></div>
				<?php ev_book_mockup( 'relative w-[52%]', 'flat' ); ?>
			</div>
		</div>
		<div class="lg:col-span-7">
			<div class="reveal">
				<p class="label mb-5 flex items-center gap-3 text-terracotta"><span aria-hidden="true" class="h-px w-8 bg-current"></span>Sobre el libro</p>
				<h2 id="sobre-t" class="text-[2.5rem] sm:text-5xl lg:text-6xl">Una historia que merece <span class="italic">ser recordada</span></h2>
				<div class="mt-8 max-w-2xl space-y-5 text-lg leading-relaxed text-ink-soft">
					<?php foreach ( array_slice( $ev_desc, 0, 2 ) as $ev_p ) : ?>
						<p><?php echo esc_html( $ev_p ); ?></p>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="reveal mt-10 grid gap-8 sm:grid-cols-[1fr_auto] sm:items-end" <?php echo ev_delay( 120 ); ?>>
				<?php ev_book_specs(); ?>
				<a href="<?php echo esc_url( ev_url( 'el-libro' ) ); ?>" class="group inline-flex items-center gap-2 font-semibold text-carbon">
					<span class="link-editorial">Conoce el libro</span><?php echo ev_icon( 'arrow-right', 20, 'transition-transform group-hover:translate-x-1' ); ?>
				</a>
			</div>
		</div>
	</div>
</section>
