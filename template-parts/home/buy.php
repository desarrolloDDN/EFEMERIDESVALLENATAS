<?php
/**
 * CTA de compra: dos ediciones.
 *
 * @package Efemerides
 */
?>
<section aria-labelledby="buy-t" class="on-dark relative overflow-hidden bg-carbon py-24 text-paper lg:py-36">
	<div aria-hidden="true" class="pointer-events-none absolute -left-48 top-1/3 h-[30rem] w-[30rem] rounded-full border border-gold/10"></div>
	<div class="container-editorial relative">
		<?php
		ev_section_heading(
			array(
				'tone'   => 'dark',
				'align'  => 'center',
				'kicker' => 'Tienda',
				'id'     => 'buy-t',
				'title'  => 'Consigue <span class="italic text-gold-soft">Efemérides Vallenatas</span>',
				'intro'  => 'Una obra para quienes quieren conocer, recordar y preservar.',
			)
		);
		?>
		<div class="mx-auto mt-16 grid max-w-5xl gap-6 md:grid-cols-2">
			<div class="reveal"><?php ev_product_card( 'physical', 'dark' ); ?></div>
			<div class="reveal" <?php echo ev_delay( 120 ); ?>><?php ev_product_card( 'digital', 'dark' ); ?></div>
		</div>
	</div>
</section>
