<?php
/**
 * Perfil del autor: fotografía | historia.
 * $args['variant'] = 'home' | 'page'
 *
 * @package Efemerides
 */
$ev_page = 'page' === ( $args['variant'] ?? 'home' );
$ev_name = ev_opt( 'book_author' );
$ev_tag  = $ev_page ? 'h1' : 'h2';
?>
<section aria-labelledby="autor-t" class="paper-texture relative overflow-hidden <?php echo $ev_page ? 'pb-20 pt-10 lg:pb-28' : 'py-24 lg:py-36'; ?>">
	<div class="container-editorial grid items-center gap-12 lg:grid-cols-12 lg:gap-20">
		<div class="reveal relative lg:col-span-5">
			<div aria-hidden="true" class="absolute -bottom-4 -right-4 left-6 top-6 border border-terracotta/50 sm:-bottom-6 sm:-right-6"></div>
			<?php ev_media( (int) ev_opt( 'author_photo' ), 'Fotografía del autor', '4/5', 'paper', 'relative', 'ev-portrait', $ev_page ); ?>
		</div>
		<div class="lg:col-span-7">
			<div class="reveal">
				<p class="label mb-5 flex items-center gap-3 text-terracotta"><span aria-hidden="true" class="h-px w-8 bg-current"></span>El autor</p>
				<<?php echo $ev_tag; ?> id="autor-t" class="text-[2.75rem] sm:text-6xl lg:text-7xl"><?php echo esc_html( $ev_name ); ?></<?php echo $ev_tag; ?>>
				<p class="label mt-4 text-ink-soft"><?php echo esc_html( ev_opt( 'author_role' ) ); ?></p>
			</div>
			<figure class="reveal relative mt-16 border-l-2 border-gold pl-6 sm:pl-8" <?php echo ev_delay( 100 ); ?>>
				<span aria-hidden="true" class="absolute -left-1 -top-10 font-display text-8xl leading-none text-terracotta/25">“</span>
				<blockquote class="font-display text-[1.75rem] italic leading-snug text-carbon sm:text-4xl"><?php echo esc_html( ev_opt( 'author_quote' ) ); ?></blockquote>
				<figcaption class="label mt-4 text-ink-soft">— <?php echo esc_html( $ev_name ); ?></figcaption>
			</figure>
			<div class="reveal" <?php echo ev_delay( 180 ); ?>>
				<?php if ( $ev_page && ! empty( $args['bio'] ) ) : ?>
					<div class="prose-editorial ev-no-dropcap mt-10 max-w-2xl text-ink-soft"><?php echo $args['bio']; // phpcs:ignore -- contenido de the_content ya filtrado. ?></div>
				<?php else : ?>
					<p class="mt-10 max-w-2xl text-lg leading-relaxed text-ink-soft"><?php echo esc_html( ev_opt( 'author_short_bio' ) ); ?></p>
				<?php endif; ?>
				<?php if ( ! $ev_page ) : ?>
					<a href="<?php echo esc_url( ev_url( 'autor' ) ); ?>" class="group mt-8 inline-flex items-center gap-2 font-semibold text-carbon">
						<span class="link-editorial">Leer la entrevista completa</span><?php echo ev_icon( 'arrow-right', 20, 'transition-transform group-hover:translate-x-1' ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
