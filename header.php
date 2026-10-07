<?php
/**
 * Encabezado del sitio.
 *
 * @package Efemerides
 */
?><!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="theme-color" content="#FAF8F3">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a href="#contenido" class="sr-only focus:not-sr-only focus:fixed focus:left-4 focus:top-4 focus:z-[60] focus:bg-carbon focus:px-4 focus:py-3 focus:text-ivory">Saltar al contenido</a>

<header class="ev-header sticky top-0 z-40 transition-[background-color,box-shadow] duration-500" data-header>
	<div class="container-editorial flex h-16 items-center justify-between gap-6 lg:h-20">
		<?php get_template_part( 'template-parts/components/logo' ); ?>

		<nav aria-label="Principal" class="hidden lg:block">
			<ul class="flex items-center gap-5 xl:gap-7">
				<?php foreach ( ev_menu_items( 'primary' ) as list( $label, $url, $active ) ) : ?>
					<li>
						<a href="<?php echo esc_url( $url ); ?>" <?php echo $active ? 'aria-current="page"' : ''; ?> class="label link-editorial whitespace-nowrap !tracking-[0.14em] <?php echo $active ? 'text-terracotta' : 'text-carbon'; ?>"><?php echo esc_html( $label ); ?></a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>

		<div class="flex items-center gap-2">
			<a href="<?php echo esc_url( ev_url( 'tienda' ) ); ?>" class="btn btn-primary hidden whitespace-nowrap !min-h-11 !px-5 xl:inline-flex">Comprar el libro</a>
			<a href="<?php echo esc_url( ev_cart_url() ); ?>" class="relative inline-flex h-11 w-11 items-center justify-center text-carbon transition-colors hover:text-terracotta" aria-label="<?php echo esc_attr( sprintf( 'Carrito (%d)', ev_cart_count() ) ); ?>">
				<?php echo ev_icon( 'bag', 22 ); ?>
				<?php ev_cart_badge(); ?>
			</a>
			<button type="button" class="inline-flex h-11 w-11 items-center justify-center text-carbon lg:hidden" aria-expanded="false" aria-controls="mobile-menu" aria-label="Abrir menú" data-menu-toggle>
				<span data-icon-open><?php echo ev_icon( 'menu', 24 ); ?></span>
				<span data-icon-close hidden><?php echo ev_icon( 'close', 24 ); ?></span>
			</button>
		</div>
	</div>

	<div id="mobile-menu" hidden class="paper-texture fixed inset-x-0 bottom-0 top-16 z-40 overflow-y-auto lg:hidden">
		<nav aria-label="Principal móvil" class="container-editorial flex min-h-full flex-col py-10">
			<ul class="space-y-1">
				<?php
				$ev_mobile = array_merge( array( array( 'Inicio', home_url( '/' ), false ) ), ev_menu_items( 'primary' ), array( array( 'Blog', ev_url( 'blog' ), false ), array( 'Contacto', ev_url( 'contacto' ), false ) ) );
				foreach ( $ev_mobile as $i => list( $label, $url ) ) :
					?>
					<li class="animate-rise" <?php echo ev_delay( $i * 45 ); ?>>
						<a href="<?php echo esc_url( $url ); ?>" class="flex items-baseline justify-between border-b border-line py-4 font-display text-4xl text-carbon">
							<?php echo esc_html( $label ); ?>
							<span class="label text-ink-soft"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
			<a href="<?php echo esc_url( ev_url( 'tienda' ) ); ?>" class="btn btn-primary mt-10 w-full">Comprar el libro</a>
			<p class="mt-auto pt-10 font-display text-lg italic text-ink-soft">La memoria del vallenato, fecha por fecha.</p>
		</nav>
	</div>
</header>

<main id="contenido">
