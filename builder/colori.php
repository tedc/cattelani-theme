<?php
	global $sitepress;
    $default = $sitepress->get_default_language();

	$colors = wp_get_post_terms( $post->ID, 'colori_materiali', array( 'ordeby'=>'name' ) );
	if($colors) : ?>
<section class="colors colors--shrink colors--grow-md">
	<header class="colors__header colors__header--mw colors__header--aligncenter colors__header--grow-md-bottom">
		<h3 class="colors__title colors__title--small-lighter"><?php echo my_wpml_string('Varianti disponibili', 'catellani'); ?></h3>
	</header>
	<ul class="colors__list colors__list colors__list--grid colors__list--grow-bottom">
		<?php foreach ($colors as $color) :
			$color_id = id_by_lang($color->term_id, $color->taxonomy, false, $sitepress->get_default_language());
        ?>
		<li class="colors__item colors__item--shrink colors__item--grow-bottom">
			<div class="colors__color" ng-style="{<?php if(get_field('colore', 'colori_materiali_'.$color_id)):?>'background-color':'<?php the_field('colore', 'colori_materiali_'.$color_id); ?>'<?php endif; if(get_field('bg', 'colori_materiali_'.$color_id) && get_field('background', 'colori_materiali_'.$color_id)):?>,<?php endif; if(get_field('background', 'colori_materiali_'.$color_id)):?>'background-image':'url(<?php the_field('background', 'colori_materiali_'.$color_id); ?>)'<?php endif; ?>}"></div>
			<span class="colors__name colors__name--light"><?php echo $color->name; ?></span>
		</li>
		<?php endforeach; ?>
	</ul>
</section>
<?php endif; ?>