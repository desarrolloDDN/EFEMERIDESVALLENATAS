<?php
/**
 * Template Name: El libro
 *
 * @package Efemerides
 */

get_header();
ev_book_schema();
the_post();
ev_page_header(
	array(
		'crumbs' => array( array( 'name' => get_the_title(), 'url' => get_permalink() ) ),
		'kicker' => 'El libro',
		'title'  => 'Un calendario de la <span class="italic text-terracotta">memoria vallenata</span>',
		'intro'  => 'La memoria del vallenato contada fecha por fecha.',
	)
);
$ev_content = trim( get_the_content() );
?>
<section class="py-20 lg:py-32">
	<div class="container-editorial grid gap-14 lg:grid-cols-12 lg:gap-20">
		<div class="reveal lg:col-span-5">
			<div class="paper-texture relative flex aspect-[4/5] items-center justify-center lg:sticky lg:top-28">
				<div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-1/4 bg-paper-deep"></div>
				<?php ev_book_mockup( 'relative w-[54%]' ); ?>
			</div>
		</div>
		<div class="lg:col-span-7">
			<div class="reveal">
				<h2 class="text-5xl">Una historia que merece <span class="italic">ser recordada</span></h2>
				<div class="prose-editorial mt-8 text-ink-soft">
					<?php
					if ( $ev_content ) {
						the_content();
					} else {
						foreach ( ev_opt_lines( 'book_description' ) as $ev_p ) {
							echo '<p>' . esc_html( $ev_p ) . '</p>';
						}
					}
					?>
				</div>
			</div>
			<div class="reveal mt-12" <?php echo ev_delay( 100 ); ?>><?php ev_book_specs( true ); ?></div>
		</div>
	</div>
</section>
<?php get_template_part( 'template-parts/home/features' ); ?>
<section aria-labelledby="origen-t" class="py-24 lg:py-36">
	<div class="container-editorial">
		<?php ev_section_heading( array( 'align' => 'center', 'kicker' => 'Por qué nació este libro', 'id' => 'origen-t', 'title' => 'Una pregunta dio origen a este libro', 'intro' => ev_opt( 'origin_intro' ) ) ); ?>
		<div class="mx-auto mt-20 max-w-5xl"><?php ev_timeline(); ?></div>
	</div>
</section>
<section aria-labelledby="ed-t" class="paper-texture border-t border-line py-24 lg:py-32">
	<div class="container-editorial">
		<?php ev_section_heading( array( 'align' => 'center', 'kicker' => 'Ediciones', 'id' => 'ed-t', 'title' => 'Elige cómo <span class="italic">leerlo</span>' ) ); ?>
		<div class="mx-auto mt-14 grid max-w-5xl gap-6 md:grid-cols-2">
			<?php ev_product_card( 'physical' ); ?>
			<?php ev_product_card( 'digital' ); ?>
		</div>
		<p class="mt-10 text-center"><a href="<?php echo esc_url( ev_url( 'efemerides' ) ); ?>" class="link-editorial font-semibold">O empieza explorando las efemérides →</a></p>
	</div>
</section>
<?php
get_footer();
