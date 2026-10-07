<?php
/**
 * Template Name: El autor
 * El contenido de la página (editor) es la biografía y la entrevista.
 * Usa encabezados H2 para las preguntas: se presentan como entrevista editorial.
 *
 * @package Efemerides
 */

get_header();
the_post();
if ( ! ev_is_placeholder( ev_opt( 'book_author' ) ) && ! ev_seo_plugin_active() ) {
	ev_json_ld( array( '@context' => 'https://schema.org', '@type' => 'Person', 'name' => ev_opt( 'book_author' ), 'jobTitle' => ev_opt( 'author_role' ) ) );
}
?>
<div class="paper-texture -mt-16 pt-24 lg:-mt-20 lg:pt-32">
	<div class="container-editorial"><?php ev_breadcrumbs( array( array( 'name' => get_the_title(), 'url' => get_permalink() ) ) ); ?></div>
</div>
<?php get_template_part( 'template-parts/author', null, array( 'variant' => 'page' ) ); ?>

<section aria-labelledby="entrevista-t" class="py-24 lg:py-32">
	<div class="container-editorial">
		<?php ev_section_heading( array( 'kicker' => 'Conversación', 'id' => 'entrevista-t', 'title' => 'En sus <span class="italic">propias palabras</span>', 'intro' => 'Biografía, trayectoria y una entrevista editorial sobre la memoria, la música y el oficio de documentar.' ) ); ?>
		<div class="ev-interview prose-editorial ev-no-dropcap mx-auto mt-16 max-w-3xl"><?php the_content(); ?></div>
	</div>
</section>

<section aria-labelledby="origen-t" class="paper-texture border-y border-line py-24 lg:py-32">
	<div class="container-editorial">
		<?php ev_section_heading( array( 'align' => 'center', 'kicker' => 'Por qué nació este libro', 'id' => 'origen-t', 'title' => 'Una pregunta dio origen a este libro', 'intro' => ev_opt( 'origin_intro' ) ) ); ?>
		<div class="mx-auto mt-20 max-w-5xl"><?php ev_timeline(); ?></div>
	</div>
</section>
<?php
get_template_part( 'template-parts/newsletter' );
get_footer();
