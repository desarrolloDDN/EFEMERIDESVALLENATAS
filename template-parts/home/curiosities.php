<?php
/**
 * Curiosidades (magazine).
 *
 * @package Efemerides
 */
$ev_items = get_posts( array( 'post_type' => 'curiosidad', 'posts_per_page' => 4 ) );
if ( ! $ev_items ) {
	return;
}
$ev_lead = array_shift( $ev_items );
?>
<section aria-labelledby="cur-t" class="border-t border-line bg-ivory py-24 lg:py-36">
	<div class="container-editorial">
		<div class="flex flex-col gap-10 lg:flex-row lg:items-end lg:justify-between">
			<?php ev_section_heading( array( 'kicker' => 'Curiosidades vallenatas', 'id' => 'cur-t', 'title' => 'Historias que probablemente <span class="italic">no conocías</span>' ) ); ?>
			<a href="<?php echo esc_url( ev_url( 'curiosidades' ) ); ?>" class="group inline-flex shrink-0 items-center gap-2 font-semibold">
				<span class="link-editorial">Ver todas las curiosidades</span><?php echo ev_icon( 'arrow-right', 20, 'transition-transform group-hover:translate-x-1' ); ?>
			</a>
		</div>
		<div class="mt-14 grid gap-12 lg:grid-cols-12 lg:gap-10">
			<div class="reveal lg:col-span-7"><?php ev_article_card( $ev_lead, 'feature' ); ?></div>
			<ul class="grid gap-10 sm:grid-cols-2 lg:col-span-5 lg:grid-cols-1 lg:gap-8">
				<?php foreach ( $ev_items as $ev_i => $ev_post ) : ?>
					<li class="reveal <?php echo 2 === $ev_i ? 'lg:hidden' : ''; ?>" <?php echo ev_delay( $ev_i * 90 ); ?>><?php ev_article_card( $ev_post ); ?></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>
