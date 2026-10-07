<?php
/**
 * Componentes visuales reutilizables (equivalentes a los componentes del diseño):
 * BookCover, BookMockup, TabletMockup, PlaceholderMedia, SectionHeading,
 * EphemerisCard, CuriosityCard, ProductCard, TestimonialCard, PressLogos,
 * BookSpecs, Timeline, PageHeader, ShareLinks, BookCta.
 *
 * @package Efemerides
 */

defined( 'ABSPATH' ) || exit;

/* ==========================================================================
   Libro
   ========================================================================== */

/** Portada: imagen oficial si existe; si no, portada tipográfica provisional. */
function ev_book_cover( $tag = true ) {
	$cover = (int) ev_opt( 'book_cover' );
	if ( $cover ) {
		echo wp_get_attachment_image( $cover, 'large', false, array( 'class' => 'absolute inset-0 h-full w-full object-cover', 'alt' => 'Portada de Efemérides Vallenatas', 'sizes' => '(min-width:1024px) 420px, 60vw' ) );
		return;
	}
	$author = ev_opt( 'book_author' );
	?>
	<div role="img" aria-label="Portada provisional de Efemérides Vallenatas" class="ev-cover relative h-full w-full overflow-hidden bg-forest text-paper">
		<div aria-hidden="true" class="paper-texture absolute inset-0 opacity-[0.12] mix-blend-screen"></div>
		<div aria-hidden="true" class="absolute border border-gold/70" style="inset:5cqw"></div>
		<div aria-hidden="true" class="absolute border border-gold/35" style="inset:6.5cqw"></div>
		<div class="relative flex h-full flex-col items-center text-center" style="padding:12cqw 10cqw 15cqw">
			<p class="font-sans font-semibold uppercase text-gold-soft" style="font-size:3.2cqw;letter-spacing:.32em"><?php echo esc_html( $author ); ?></p>
			<div aria-hidden="true" class="relative my-auto" style="width:52cqw;aspect-ratio:1">
				<?php for ( $i = 0; $i < 7; $i++ ) : ?>
					<span class="absolute rounded-full border border-gold" style="inset:<?php echo esc_attr( $i * 3.4 ); ?>cqw;opacity:<?php echo esc_attr( 0.18 + $i * 0.07 ); ?>"></span>
				<?php endfor; ?>
				<?php for ( $i = 0; $i < 12; $i++ ) : ?>
					<span class="absolute left-1/2 top-0 bg-gold/60" style="width:.35cqw;height:3cqw;transform:translateX(-50%) rotate(<?php echo (int) ( $i * 30 ); ?>deg);transform-origin:50% 26cqw"></span>
				<?php endfor; ?>
				<span class="absolute rounded-full bg-terracotta" style="inset:19cqw"></span>
				<span class="absolute rounded-full bg-paper" style="inset:25cqw"></span>
			</div>
			<div class="font-display leading-[0.9] text-ivory" style="font-size:13cqw">
				<span class="block italic">Efemérides</span>
				<span class="block font-semibold uppercase" style="font-size:8.6cqw;letter-spacing:.14em;margin-top:2cqw">Vallenatas</span>
			</div>
			<p class="font-display italic text-paper/80" style="font-size:4cqw;margin-top:4cqw">La memoria del vallenato contada fecha por fecha</p>
		</div>
		<?php if ( $tag ) : ?>
			<span class="absolute bg-paper font-sans font-bold uppercase text-terracotta" style="right:7.5cqw;bottom:7.5cqw;font-size:2.2cqw;letter-spacing:.16em;padding:.8cqw 1.6cqw">Portada provisional</span>
		<?php endif; ?>
	</div>
	<?php
}

/** Contraportada provisional (o imagen oficial). */
function ev_book_back_cover() {
	$img = (int) ev_opt( 'book_back_cover' );
	if ( $img ) {
		echo wp_get_attachment_image( $img, 'large', false, array( 'class' => 'absolute inset-0 h-full w-full object-cover', 'alt' => 'Contraportada de Efemérides Vallenatas' ) );
		return;
	}
	?>
	<div role="img" aria-label="Contraportada provisional" class="ev-cover relative h-full w-full overflow-hidden bg-forest text-paper">
		<div aria-hidden="true" class="paper-texture absolute inset-0 opacity-[0.12] mix-blend-screen"></div>
		<div aria-hidden="true" class="absolute border border-gold/50" style="inset:5cqw"></div>
		<div class="relative flex h-full flex-col" style="padding:13cqw 12cqw">
			<p class="font-display italic text-ivory" style="font-size:6.4cqw;line-height:1.15">“Cada fecha guarda una historia.”</p>
			<p class="font-sans text-paper/75" style="font-size:3.3cqw;line-height:1.6;margin-top:6cqw"><?php echo esc_html( ev_opt( 'book_back_text' ) ); ?></p>
			<div class="mt-auto flex items-end justify-between">
				<span class="font-sans font-semibold uppercase text-gold-soft" style="font-size:2.6cqw;letter-spacing:.2em"><?php echo esc_html( ev_opt( 'book_publisher' ) ); ?></span>
				<span class="flex flex-col items-center bg-ivory text-carbon" style="padding:2cqw 3cqw;font-size:2.4cqw">
					<span aria-hidden="true" class="block" style="width:20cqw;height:9cqw;background:repeating-linear-gradient(90deg,#171717 0 .5cqw,transparent .5cqw 1.1cqw,#171717 1.1cqw 1.4cqw,transparent 1.4cqw 2.2cqw)"></span>
					<span style="margin-top:1cqw"><?php echo esc_html( ev_opt( 'book_isbn' ) ); ?></span>
				</span>
			</div>
		</div>
	</div>
	<?php
}

/** Doble página interior provisional. */
function ev_book_interior( $variant = 1 ) {
	?>
	<div role="img" aria-label="Páginas interiores de ejemplo" class="ev-cover paper-texture grid h-full w-full grid-cols-2">
		<?php foreach ( array( 0, 1 ) as $side ) : ?>
			<div class="relative flex flex-col <?php echo 0 === $side ? 'border-r border-line' : ''; ?>" style="padding:6cqw 5cqw;box-shadow:inset <?php echo 0 === $side ? '-2.5cqw' : '2.5cqw'; ?> 0 3cqw -2.5cqw rgb(0 0 0 / .18)">
				<?php if ( 0 === $side || 2 === $variant ) : ?>
					<span class="font-sans font-semibold uppercase text-terracotta" style="font-size:1.4cqw;letter-spacing:.2em">[Mes]</span>
					<span class="font-display text-carbon" style="font-size:9cqw;line-height:1">[00]</span>
					<span class="font-display italic text-carbon" style="font-size:2.4cqw;margin-top:1.5cqw">[Título de la efeméride]</span>
					<?php for ( $i = 0; $i < 9; $i++ ) : ?>
						<span class="block bg-ink/15" style="height:.55cqw;margin-top:1.6cqw;width:<?php echo 8 === $i ? '55%' : '100%'; ?>"></span>
					<?php endfor; ?>
				<?php else : ?>
					<div class="flex h-full flex-col items-center justify-center text-center">
						<span aria-hidden="true" class="block w-[60%] border border-dashed border-ink/30" style="aspect-ratio:4/5"></span>
						<span class="font-sans uppercase text-ink-soft" style="font-size:1.3cqw;letter-spacing:.18em;margin-top:2cqw">[Fotografía de archivo]</span>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
}

/**
 * Mockup 3D del libro (solo CSS): portada, lomo, cantos de páginas y sombra.
 *
 * @param string $pose  spine|flat
 */
function ev_book_mockup( $class = '', $pose = 'spine' ) {
	$ry       = 'spine' === $pose ? '26deg' : '10deg';
	$ry_hover = 'spine' === $pose ? '18deg' : '4deg';
	?>
	<div class="ev-book group relative <?php echo esc_attr( $class ); ?>" style="perspective:1600px">
		<div aria-hidden="true" class="absolute left-[8%] right-[-6%] top-[94%] h-[9%] rounded-[50%] bg-carbon/45 blur-xl transition-transform duration-700 group-hover:scale-x-95"></div>
		<div class="ev-book__body relative w-full" style="aspect-ratio:2/3;transform-style:preserve-3d;--ry:<?php echo esc_attr( $ry ); ?>;--ry-hover:<?php echo esc_attr( $ry_hover ); ?>">
			<div class="absolute inset-0 overflow-hidden rounded-r-[1.2cqw]" style="transform:translateZ(4.5cqw)">
				<?php ev_book_cover(); ?>
				<div aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-[3.5%] w-[1.6%] bg-gradient-to-r from-black/30 via-white/10 to-transparent"></div>
				<div aria-hidden="true" class="pointer-events-none absolute inset-0 bg-gradient-to-br from-white/14 via-transparent to-black/20"></div>
			</div>
			<div aria-hidden="true" class="absolute top-0 flex h-full items-center justify-center bg-forest-deep" style="width:9cqw;left:calc(50% - 4.5cqw);transform:rotateY(-90deg) translateZ(50cqw)">
				<span class="whitespace-nowrap font-display uppercase text-gold-soft" style="writing-mode:vertical-rl;font-size:3.6cqw;letter-spacing:.2em">Efemérides Vallenatas</span>
				<span class="absolute inset-0 bg-gradient-to-r from-black/35 via-transparent to-black/25"></span>
			</div>
			<div aria-hidden="true" class="absolute top-[1.2%] h-[97.6%]" style="width:8.4cqw;left:calc(50% - 4.2cqw);transform:rotateY(90deg) translateZ(49cqw);background:repeating-linear-gradient(90deg,#efe4cf 0 .35cqw,#d9cab0 .35cqw .5cqw)"></div>
			<div aria-hidden="true" class="absolute inset-0 rounded-l-[1.2cqw] bg-forest-deep" style="transform:rotateY(180deg) translateZ(4.5cqw)"></div>
		</div>
	</div>
	<?php
}

/** Mockup de la edición digital (tablet). */
function ev_tablet_mockup( $class = '' ) {
	?>
	<div class="ev-book relative <?php echo esc_attr( $class ); ?>">
		<div aria-hidden="true" class="absolute left-[6%] right-[6%] top-[96%] h-[7%] rounded-[50%] bg-carbon/40 blur-xl"></div>
		<div class="relative rounded-[6cqw] bg-carbon p-[4.2cqw] shadow-[inset_0_0_0_0.6cqw_#2c2c2c]" style="aspect-ratio:3/4">
			<div class="relative h-full w-full overflow-hidden rounded-[2cqw]">
				<?php ev_book_cover(); ?>
				<div aria-hidden="true" class="pointer-events-none absolute inset-0 bg-gradient-to-tr from-transparent via-white/5 to-white/15"></div>
			</div>
			<span aria-hidden="true" class="absolute left-1/2 top-[1.6cqw] h-[1.2cqw] w-[1.2cqw] -translate-x-1/2 rounded-full bg-[#333]"></span>
		</div>
	</div>
	<?php
}

/* ==========================================================================
   Medios y encabezados
   ========================================================================== */

/**
 * Imagen real (attachment) o placeholder elegante claramente identificado.
 *
 * @param int|null $attachment_id
 */
function ev_media( $attachment_id, $label, $aspect = '4/3', $tone = 'paper', $class = '', $size = 'ev-card', $eager = false ) {
	if ( $attachment_id ) {
		printf( '<div class="relative overflow-hidden %s" style="aspect-ratio:%s">', esc_attr( $class ), esc_attr( $aspect ) );
		echo wp_get_attachment_image(
			$attachment_id,
			$size,
			false,
			array(
				'class'   => 'absolute inset-0 h-full w-full object-cover',
				'loading' => $eager ? 'eager' : 'lazy',
				'fetchpriority' => $eager ? 'high' : 'auto',
			)
		);
		echo '</div>';
		return;
	}
	$tones = array(
		'paper'  => 'bg-paper-deep text-ink-soft',
		'dark'   => 'bg-carbon text-paper/70',
		'forest' => 'bg-forest text-paper/70',
	);
	$label = trim( $label, '[]' );
	?>
	<div role="img" aria-label="<?php echo esc_attr( 'Espacio reservado: ' . $label ); ?>" class="relative isolate flex items-center justify-center overflow-hidden <?php echo esc_attr( $tones[ $tone ] . ' ' . $class ); ?>" style="aspect-ratio:<?php echo esc_attr( $aspect ); ?>">
		<div aria-hidden="true" class="absolute inset-0 -z-10" style="background-image:repeating-linear-gradient(135deg,currentColor 0 1px,transparent 1px 14px);opacity:.08"></div>
		<div aria-hidden="true" class="absolute inset-3 border border-current opacity-25"></div>
		<div class="px-6 text-center">
			<span class="label block opacity-80">[<?php echo esc_html( $label ); ?>]</span>
			<span class="mt-2 block font-display text-sm italic opacity-70">Material pendiente de autorización</span>
		</div>
	</div>
	<?php
}

/** Encabezado de sección (kicker + título + intro). $title admite HTML seguro (em/span). */
function ev_section_heading( $args ) {
	$a      = wp_parse_args(
		$args,
		array(
			'kicker' => '',
			'title'  => '',
			'intro'  => '',
			'align'  => 'left',
			'tag'    => 'h2',
			'tone'   => 'light',
			'id'     => '',
			'class'  => '',
		)
	);
	$center = 'center' === $a['align'];
	$dark   = 'dark' === $a['tone'];
	$tag    = in_array( $a['tag'], array( 'h1', 'h2' ), true ) ? $a['tag'] : 'h2';
	?>
	<header class="reveal max-w-3xl <?php echo $center ? 'mx-auto text-center' : ''; ?> <?php echo esc_attr( $a['class'] ); ?>">
		<?php if ( $a['kicker'] ) : ?>
			<p class="label mb-5 flex items-center gap-3 <?php echo $center ? 'justify-center' : ''; ?> <?php echo $dark ? 'text-gold-soft' : 'text-terracotta'; ?>">
				<span aria-hidden="true" class="h-px w-8 bg-current"></span><?php echo esc_html( $a['kicker'] ); ?>
			</p>
		<?php endif; ?>
		<<?php echo $tag; ?> <?php echo $a['id'] ? 'id="' . esc_attr( $a['id'] ) . '"' : ''; ?> class="text-[2.5rem] leading-[1.02] sm:text-5xl lg:text-6xl <?php echo $dark ? '!text-ivory' : ''; ?>"><?php echo wp_kses( $a['title'], ev_inline_html() ); ?></<?php echo $tag; ?>>
		<?php if ( $a['intro'] ) : ?>
			<p class="mt-6 text-lg leading-relaxed max-w-2xl <?php echo $dark ? 'text-paper/80' : 'text-ink-soft'; ?> <?php echo $center ? 'mx-auto' : ''; ?>"><?php echo esc_html( $a['intro'] ); ?></p>
		<?php endif; ?>
	</header>
	<?php
}

function ev_inline_html() {
	return array(
		'span' => array( 'class' => true ),
		'em'   => array( 'class' => true ),
		'br'   => array(),
	);
}

/** Cabecera editorial de páginas internas. */
function ev_page_header( $args ) {
	$a = wp_parse_args(
		$args,
		array(
			'crumbs' => array(),
			'kicker' => '',
			'title'  => '',
			'intro'  => '',
			'note'   => '',
		)
	);
	?>
	<header class="paper-texture relative -mt-16 overflow-hidden border-b border-line pb-16 pt-24 lg:-mt-20 lg:pb-24 lg:pt-32">
		<div aria-hidden="true" class="archive-grid absolute inset-0 [mask-image:linear-gradient(to_bottom,black,transparent)]"></div>
		<div class="container-editorial relative">
			<?php ev_breadcrumbs( $a['crumbs'] ); ?>
			<?php if ( $a['kicker'] ) : ?>
				<p class="label animate-rise mt-10 text-terracotta"><?php echo esc_html( $a['kicker'] ); ?></p>
			<?php endif; ?>
			<h1 class="animate-rise mt-4 max-w-4xl text-[2.9rem] leading-[0.98] sm:text-6xl lg:text-7xl" <?php echo ev_delay( 100 ); ?>><?php echo wp_kses( $a['title'], ev_inline_html() ); ?></h1>
			<?php if ( $a['intro'] ) : ?>
				<p class="animate-rise mt-6 max-w-2xl text-lg leading-relaxed text-ink-soft" <?php echo ev_delay( 200 ); ?>><?php echo esc_html( $a['intro'] ); ?></p>
			<?php endif; ?>
			<?php if ( $a['note'] ) : ?>
				<p class="mt-6"><span class="placeholder-tag"><?php echo esc_html( $a['note'] ); ?></span></p>
			<?php endif; ?>
		</div>
	</header>
	<?php
}

/* ==========================================================================
   Tarjetas
   ========================================================================== */

/** Tarjeta editorial de una efeméride. */
function ev_ephemeris_card( $post, $heading = 'h3' ) {
	$e = ev_ephemeris( $post );
	?>
	<article class="group relative flex h-full flex-col border border-line bg-ivory p-6 transition-[background-color,border-color,transform] duration-500 hover:-translate-y-1 hover:border-ink/30 hover:bg-[#fffdf8] sm:p-7">
		<div class="flex items-start justify-between gap-4">
			<p class="flex items-baseline gap-2" aria-label="<?php echo esc_attr( ev_ephemeris_date_label( $e['id'] ) ); ?>">
				<span class="font-display text-6xl leading-none text-carbon transition-colors duration-500 group-hover:text-terracotta"><?php echo (int) $e['day']; ?></span>
				<span class="label text-ink-soft">de <?php echo esc_html( ev_month_name( $e['month'] ) ); ?></span>
			</p>
			<span class="label mt-2 text-right !text-[0.62rem] text-terracotta"><?php echo esc_html( $e['category'] ); ?></span>
		</div>
		<span aria-hidden="true" class="my-5 block h-px w-full bg-line"></span>
		<<?php echo 'h2' === $heading ? 'h2' : 'h3'; ?> class="font-display text-[1.6rem] leading-tight">
			<a href="<?php echo esc_url( $e['url'] ); ?>" class="after:absolute after:inset-0 focus-visible:outline-none"><?php echo esc_html( $e['title'] ); ?></a>
		</<?php echo 'h2' === $heading ? 'h2' : 'h3'; ?>>
		<p class="mt-3 text-[0.95rem] leading-relaxed text-ink-soft"><?php echo esc_html( $e['summary'] ); ?></p>
		<div class="mt-auto flex items-center justify-between gap-3 pt-6">
			<span class="label inline-flex items-center gap-2 text-carbon">Leer efeméride <?php echo ev_icon( 'arrow-right', 16, 'transition-transform duration-500 group-hover:translate-x-1.5' ); ?></span>
			<?php if ( $e['sample'] ) : ?><span class="placeholder-tag">Ejemplo</span><?php endif; ?>
		</div>
		<span aria-hidden="true" class="pointer-events-none absolute inset-0 ring-terracotta ring-offset-2 group-has-[a:focus-visible]:ring-2"></span>
	</article>
	<?php
}

/** Tarjeta de magazine (curiosidades y blog). */
function ev_article_card( $post, $variant = 'default', $heading = 'h3' ) {
	$post    = get_post( $post );
	$feature = 'feature' === $variant;
	$cat     = ev_primary_term_name( $post );
	$h       = 'h2' === $heading ? 'h2' : 'h3';
	?>
	<article class="group relative flex h-full flex-col">
		<div class="overflow-hidden">
			<div class="transition-transform duration-1000 group-hover:scale-[1.03]">
				<?php ev_media( get_post_thumbnail_id( $post ), 'Imagen destacada: ' . get_the_title( $post ), $feature ? '4/3' : '3/2', $feature ? 'forest' : 'paper', '', $feature ? 'ev-feature' : 'ev-card' ); ?>
			</div>
		</div>
		<div class="mt-5 flex items-center gap-3">
			<?php if ( $cat ) : ?><span class="label text-terracotta"><?php echo esc_html( $cat ); ?></span><span aria-hidden="true" class="h-px w-5 bg-line"></span><?php endif; ?>
			<time datetime="<?php echo esc_attr( get_the_date( 'c', $post ) ); ?>" class="text-xs text-ink-soft"><?php echo esc_html( get_the_date( 'j \d\e F \d\e Y', $post ) ); ?></time>
		</div>
		<<?php echo $h; ?> class="mt-3 leading-[1.05] <?php echo $feature ? 'text-4xl sm:text-5xl' : 'text-[1.7rem]'; ?>">
			<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="ev-underline after:absolute after:inset-0"><?php echo esc_html( get_the_title( $post ) ); ?></a>
		</<?php echo $h; ?>>
		<p class="mt-3 leading-relaxed text-ink-soft <?php echo $feature ? 'text-lg' : 'text-[0.95rem]'; ?>"><?php echo esc_html( get_the_excerpt( $post ) ); ?></p>
		<?php if ( get_post_meta( $post->ID, 'ev_sample', true ) ) : ?><span class="placeholder-tag mt-4 self-start">Contenido pendiente</span><?php endif; ?>
	</article>
	<?php
}

/** Tarjeta de producto (edición física / digital). */
function ev_product_card( $type, $tone = 'light', $heading = 'h3' ) {
	$p    = ev_product( $type );
	$dark = 'dark' === $tone;
	$h    = 'h2' === $heading ? 'h2' : 'h3';
	?>
	<article class="group flex h-full flex-col border <?php echo $dark ? 'border-paper/15 bg-carbon' : 'border-line bg-ivory'; ?>">
		<a href="<?php echo esc_url( $p['url'] ); ?>" aria-label="<?php echo esc_attr( 'Ver ' . $p['name'] ); ?>" class="relative flex aspect-[5/4] items-center justify-center overflow-hidden <?php echo $dark ? 'bg-forest' : 'paper-texture'; ?>">
			<div aria-hidden="true" class="absolute inset-x-0 bottom-0 h-1/4 <?php echo $dark ? 'bg-forest-deep' : 'bg-paper-deep'; ?>"></div>
			<div class="relative w-[38%] transition-transform duration-1000 group-hover:-translate-y-2">
				<?php 'physical' === $type ? ev_book_mockup() : ev_tablet_mockup( 'w-[125%] -translate-x-[10%]' ); ?>
			</div>
		</a>
		<div class="flex flex-1 flex-col p-6 sm:p-8">
			<p class="label <?php echo $dark ? 'text-gold-soft' : 'text-terracotta'; ?>"><?php echo 'physical' === $type ? 'Libro físico' : 'Formato digital'; ?></p>
			<<?php echo $h; ?> class="mt-3 text-4xl <?php echo $dark ? '!text-ivory' : ''; ?>"><?php echo esc_html( $p['name'] ); ?></<?php echo $h; ?>>
			<p class="mt-3 <?php echo $dark ? 'text-paper/75' : 'text-ink-soft'; ?>"><?php echo esc_html( $p['description'] ); ?></p>
			<div class="ev-price mt-6 font-display text-4xl <?php echo $dark ? 'text-ivory' : 'text-carbon'; ?>"><?php echo wp_kses_post( $p['price_html'] ); ?></div>
			<?php if ( $p['price_placeholder'] ) : ?><span class="placeholder-tag mt-2 self-start">Precio provisional</span><?php endif; ?>
			<ul class="mt-6 space-y-2.5 text-[0.95rem] <?php echo $dark ? 'text-paper/80' : 'text-ink'; ?>">
				<?php foreach ( array_slice( $p['features'], 0, 5 ) as $f ) : ?>
					<li class="flex gap-3"><?php echo ev_icon( 'check', 18, 'mt-0.5 shrink-0 ' . ( $dark ? 'text-gold-soft' : 'text-terracotta' ) ); ?><?php echo esc_html( $f ); ?></li>
				<?php endforeach; ?>
			</ul>
			<div class="mt-auto flex flex-col gap-3 pt-8">
				<a href="<?php echo esc_url( $p['buy_url'] ); ?>" class="btn <?php echo $dark ? 'btn-gold' : 'btn-primary'; ?>" rel="nofollow"><?php echo esc_html( $p['cta'] ); ?></a>
				<a href="<?php echo esc_url( $p['url'] ); ?>" class="text-center text-sm underline underline-offset-4 <?php echo $dark ? 'text-paper/70 hover:text-ivory' : 'text-ink-soft hover:text-carbon'; ?>">Ver detalles de la <?php echo esc_html( strtolower( $p['name'] ) ); ?></a>
			</div>
		</div>
	</article>
	<?php
}

/** Testimonios publicados o placeholders claramente identificados. */
function ev_testimonials( $limit = 4 ) {
	$posts = get_posts( array( 'post_type' => 'testimonio', 'posts_per_page' => $limit, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) );
	if ( $posts ) {
		return array_map(
			fn( $p ) => array(
				'quote'       => wp_strip_all_tags( $p->post_content ),
				'name'        => get_the_title( $p ),
				'role'        => get_post_meta( $p->ID, 'ev_role', true ),
				'placeholder' => false,
			),
			$posts
		);
	}
	$roles = array( '[Investigador / investigadora]', '[Periodista], [Medio]', '[Músico / compositor]', '[Lector / lectora]' );
	return array_map(
		fn( $r ) => array( 'quote' => '[Texto del testimonio — pendiente de autorización.]', 'name' => '[Nombre]', 'role' => $r, 'placeholder' => true ),
		array_slice( $roles, 0, $limit )
	);
}

function ev_testimonial_card( $t ) {
	?>
	<figure class="flex h-full flex-col border-t border-carbon pt-6">
		<span aria-hidden="true" class="font-display text-6xl leading-[0.6] text-terracotta">“</span>
		<blockquote class="mt-4 font-display text-2xl italic leading-snug text-carbon sm:text-[1.7rem]"><?php echo esc_html( $t['quote'] ); ?></blockquote>
		<figcaption class="mt-auto flex flex-wrap items-center gap-x-2 gap-y-2 pt-6 text-sm">
			<span class="font-semibold text-carbon">— <?php echo esc_html( $t['name'] ); ?></span>
			<span class="text-ink-soft"><?php echo esc_html( $t['role'] ); ?></span>
			<?php if ( $t['placeholder'] ) : ?><span class="placeholder-tag ml-auto">Placeholder</span><?php endif; ?>
		</figcaption>
	</figure>
	<?php
}

/** Notas de prensa publicadas o placeholders. */
function ev_press_items() {
	$posts = get_posts( array( 'post_type' => 'medio', 'posts_per_page' => 10, 'orderby' => array( 'menu_order' => 'ASC', 'date' => 'DESC' ) ) );
	if ( $posts ) {
		return array_map(
			fn( $p ) => array(
				'name'     => get_the_title( $p ),
				'url'      => get_post_meta( $p->ID, 'ev_url', true ),
				'headline' => get_post_meta( $p->ID, 'ev_headline', true ),
				'date'     => get_post_meta( $p->ID, 'ev_date', true ),
				'logo'     => get_post_thumbnail_id( $p ),
			),
			$posts
		);
	}
	return array_map(
		fn( $i ) => array( 'name' => "[Medio $i]", 'url' => '', 'headline' => '[Titular de la nota]', 'date' => '[Fecha]', 'logo' => 0 ),
		range( 1, 5 )
	);
}

function ev_press_logos( $detailed = false ) {
	$items = ev_press_items();
	if ( $detailed ) {
		echo '<ul class="divide-y divide-line border-y border-line">';
		foreach ( $items as $p ) {
			$tag = $p['url'] ? 'a href="' . esc_url( $p['url'] ) . '" target="_blank" rel="noopener"' : 'div';
			printf(
				'<li><%1$s class="group grid gap-2 py-6 sm:grid-cols-[12rem_1fr_auto] sm:items-baseline sm:gap-8"><span class="label text-ink-soft">%2$s</span><span class="font-display text-2xl text-carbon group-hover:text-terracotta">%3$s</span><span class="text-sm text-ink-soft">%4$s</span></%5$s></li>',
				$tag, // phpcs:ignore -- construido arriba con esc_url.
				esc_html( $p['name'] ),
				esc_html( $p['headline'] ),
				esc_html( $p['date'] ),
				$p['url'] ? 'a' : 'div'
			);
		}
		echo '</ul>';
		return;
	}
	echo '<ul class="grid grid-cols-2 border-l border-t border-line sm:grid-cols-3 lg:grid-cols-5">';
	foreach ( $items as $p ) {
		$inner = $p['logo'] ? wp_get_attachment_image( $p['logo'], 'medium', false, array( 'class' => 'max-h-10 w-auto grayscale', 'alt' => $p['name'] ) ) : esc_html( $p['name'] );
		$open  = $p['url'] ? '<a href="' . esc_url( $p['url'] ) . '" target="_blank" rel="noopener"' : '<div';
		$close = $p['url'] ? '</a>' : '</div>';
		echo '<li class="border-b border-r border-line">' . $open . ' class="flex h-28 items-center justify-center px-4 text-center font-display text-xl italic text-ink-soft transition-colors hover:bg-paper/60 hover:text-carbon">' . $inner . $close . '</li>'; // phpcs:ignore
	}
	echo '</ul>';
}

/* ==========================================================================
   Bloques
   ========================================================================== */

/** Ficha bibliográfica con estética de tarjeta de catálogo. */
function ev_book_specs( $detailed = false ) {
	$rows = array(
		'Autor'   => ev_opt( 'book_author' ),
		'Título'  => 'Efemérides Vallenatas',
		'Formato' => 'Físico / Digital',
		'Páginas' => ev_opt( 'book_pages' ),
		'Año'     => ev_opt( 'book_year' ),
		'ISBN'    => ev_opt( 'book_isbn' ),
	);
	if ( $detailed ) {
		$rows += array(
			'Editorial'      => ev_opt( 'book_publisher' ),
			'Idioma'         => ev_opt( 'book_language' ),
			'Encuadernación' => ev_opt( 'book_binding' ),
			'Dimensiones'    => ev_opt( 'book_dimensions' ),
			'Peso'           => ev_opt( 'book_weight' ),
		);
	}
	$has_placeholders = (bool) array_filter( $rows, 'ev_is_placeholder' );
	?>
	<div class="relative border border-line bg-ivory p-6 shadow-[0_18px_40px_-28px_rgb(23_23_23/.35)] sm:p-8">
		<div class="mb-5 flex items-center justify-between border-b-2 border-terracotta pb-3">
			<p class="label text-carbon">Ficha del libro</p>
			<p class="label text-ink-soft">Nº 001</p>
		</div>
		<dl class="text-[0.95rem]">
			<?php foreach ( $rows as $k => $v ) : ?>
				<div class="flex items-baseline gap-3 py-2">
					<dt class="shrink-0 text-ink-soft"><?php echo esc_html( $k ); ?></dt>
					<span aria-hidden="true" class="mb-1 flex-1 border-b border-dotted border-ink/25"></span>
					<dd class="text-right font-medium <?php echo ev_is_placeholder( $v ) ? 'text-terracotta' : 'text-carbon'; ?>"><?php echo esc_html( $v ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>
		<?php if ( $has_placeholders ) : ?>
			<p class="mt-4 text-xs text-ink-soft">Los valores entre [corchetes] son provisionales.</p>
		<?php endif; ?>
	</div>
	<?php
}

/** Línea de tiempo IDEA → … → PUBLICACIÓN (animada con el scroll por main.js). */
function ev_timeline() {
	$steps = array(
		'idea'          => 'Idea',
		'investigacion' => 'Investigación',
		'archivo'       => 'Archivo',
		'escritura'     => 'Escritura',
		'edicion'       => 'Edición',
		'publicacion'   => 'Publicación',
	);
	?>
	<ol class="ev-timeline relative" data-timeline>
		<span aria-hidden="true" class="absolute bottom-2 left-[1.15rem] top-2 w-px bg-line lg:left-1/2"></span>
		<span aria-hidden="true" data-timeline-bar class="absolute left-[1.15rem] top-2 w-px origin-top bg-terracotta lg:left-1/2" style="height:calc(100% - 1rem);transform:scaleY(0)"></span>
		<?php
		$i = 0;
		foreach ( $steps as $key => $label ) :
			$right = 1 === $i % 2;
			?>
			<li class="ev-timeline__step relative grid grid-cols-[2.3rem_1fr] gap-5 pb-14 last:pb-0 lg:grid-cols-[1fr_3rem_1fr] lg:gap-10">
				<span aria-hidden="true" class="ev-timeline__dot relative z-10 mt-1 flex h-9 w-9 items-center justify-center rounded-full border border-line bg-ivory font-display text-base text-ink-soft transition-all duration-700 lg:col-start-2 lg:row-start-1 lg:mx-auto"><?php echo (int) ( $i + 1 ); ?></span>
				<div class="ev-timeline__body translate-y-3 opacity-40 transition-all duration-700 lg:row-start-1 <?php echo $right ? 'lg:col-start-3' : 'lg:col-start-1 lg:text-right'; ?>">
					<p class="label text-terracotta"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?> · Etapa</p>
					<h3 class="mt-2 text-4xl"><?php echo esc_html( $label ); ?></h3>
					<p class="mt-3 max-w-md leading-relaxed text-ink-soft <?php echo $right ? '' : 'lg:ml-auto'; ?>"><?php echo esc_html( ev_opt( 'step_' . $key ) ); ?></p>
				</div>
			</li>
			<?php
			++$i;
		endforeach;
		?>
	</ol>
	<?php
}

/** Botones para compartir (WhatsApp, Facebook, X, copiar enlace). */
function ev_share_links( $url, $title ) {
	$enc   = 'rawurlencode';
	$links = array(
		array( 'WhatsApp', 'whatsapp', 'https://wa.me/?text=' . $enc( $title . ' — ' . $url ) ),
		array( 'Facebook', 'facebook', 'https://www.facebook.com/sharer/sharer.php?u=' . $enc( $url ) ),
		array( 'X', 'x', 'https://x.com/intent/post?text=' . $enc( $title ) . '&url=' . $enc( $url ) ),
	);
	?>
	<div class="flex flex-wrap items-center gap-2">
		<span class="label mr-2 text-ink-soft">Compartir</span>
		<?php foreach ( $links as list( $name, $icon, $href ) ) : ?>
			<a href="<?php echo esc_url( $href ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( 'Compartir en ' . $name ); ?>" class="flex h-11 w-11 items-center justify-center rounded-full border border-line transition-colors hover:border-terracotta hover:text-terracotta"><?php echo ev_icon( $icon, 18 ); ?></a>
		<?php endforeach; ?>
		<button type="button" data-copy="<?php echo esc_url( $url ); ?>" class="flex h-11 items-center gap-2 rounded-full border border-line px-4 text-sm transition-colors hover:border-terracotta hover:text-terracotta">
			<?php echo ev_icon( 'link', 16 ); ?><span aria-live="polite">Copiar enlace</span>
		</button>
	</div>
	<?php
}

/** Banda de llamada a la compra. */
function ev_book_cta( $kicker = 'Del archivo al libro', $text = 'Descubre las historias detrás de la música.' ) {
	?>
	<div class="on-dark grid items-center gap-8 bg-forest p-8 text-paper sm:grid-cols-[8rem_1fr_auto] sm:p-10">
		<?php ev_book_mockup( 'mx-auto w-28 sm:w-full' ); ?>
		<div>
			<p class="label text-gold-soft"><?php echo esc_html( $kicker ); ?></p>
			<p class="mt-2 font-display text-3xl text-ivory"><?php echo esc_html( $text ); ?></p>
		</div>
		<a href="<?php echo esc_url( ev_url( 'tienda' ) ); ?>" class="btn btn-gold">Conseguir el libro</a>
	</div>
	<?php
}
