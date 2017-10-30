<?php 
	global $sitepress;
    $default = $sitepress->get_default_language();
?>
<div class="collections" ng-swiper is-home="true">
	<?php $terms = get_terms(
			array(
				'taxonomy' => 'collezioni',
				'hide_empty' => true,
				'orderby' => 'term_order'
			)
		);
		$loopedSlides = count($terms) * 2;
	?>
	<ks-swiper-container class="collections__slider collections__slider--home" swiper="main" slides-per-view="'auto'" override-parameters="{'speed' : 1000, 'loopedSlides':<?php echo $loopedSlides; ?>, 'grabCursor' : true,'mousewheelControl':true,'keyboardControl':true}" on-ready="sliderReady(main)">
	<?php 
		$c = 0;
		foreach($terms as $term) :
		$term_id = id_by_lang($term->term_id, $term->taxonomy, false, $default);
	?>
		<ks-swiper-slide class="swiper-slide" ng-style="{'background-image':'url(<?php echo get_field('cover_image', 'collezioni_'.$term_id)['url']; ?>)'}" ng-lazy-img="<?php echo get_field('cover_image', 'collezioni_'.$term_id)['url']; ?>" data-collection="<?php echo $term->term_id; ?>" data-index="<?php echo $c; ?>">
		</ks-swiper-slide>
	<?php $c++; endforeach; ?>
	</ks-swiper-container>
	<div class="collections__nav collections__nav--shrink collections__nav--grow-md" ng-class="{'collections__nav--active' : navInit}">
	<i class="icon-arrow icon-arrow-prev"></i>
	<ks-swiper-container class="collections__container collections__container--shrink" swiper="nav" slides-per-view="'auto'" override-parameters="{'speed' : 1000, 'centeredSlides':true,'loopedSlides':<?php echo $loopedSlides; ?>, 'slideToClickedSlide' : true, 'nextButton' : '.collections__nav .icon-arrow-next', 'prevButton' : '.collections__nav .icon-arrow-prev', 'grabCursor' : true,'mousewheelControl':true}">
		<?php $index = 0; foreach($terms as $term) : ?>
		<ks-swiper-slide class="swiper-slide">
			<span class="swiper-slide__item">
				<span class="swiper-slide__name"><?php echo $term->name; ?></span>
			</span>
			<a ui-sref="app.collection({name : '<?php echo $term->slug; ?>'})" class="swiper-slide__link"><span><?php echo $term->name; ?></span></a>
		</ks-swiper-slide>
		<?php $index++; endforeach; ?>
	</ks-swiper-container>
	<i class="icon-arrow icon-arrow-next"></i>
	</div>
</div>