<div class="collections" ng-swiper>
	<?php $terms = get_terms(
			array(
				'taxonomy' => 'collezioni',
				'hide_empty' => true,
				'orderyb' => 'name'
			)
		);
		$loopedSlides = count($terms) * 2;
	?>
	<ks-swiper-container class="collections__slider collections__slider--home" swiper="main" override-parameters="{'speed' : 1000, 'loopedSlides':<?php echo $loopedSlides; ?>, 'grabCursor' : true}" on-ready="sliderReady(main)">
	<?php 
		
		foreach($terms as $term) :
	?>
		<ks-swiper-slide class="swiper-slide" ng-style="{'background-image':'url(<?php echo get_field('cover_image', 'collezioni_'.$term->term_id)['url']; ?>)'}">
		</ks-swiper-slide>
	<?php endforeach; ?>
	</ks-swiper-container>
	<div class="collections__nav collections__nav--shrink collections__nav--grow-md" ng-class="{'collections__nav--active' : navInit}">
	<i class="icon-arrow icon-arrow-prev"></i>
	<ks-swiper-container class="collections__container collections__container--shrink" swiper="nav" slides-per-view="'auto'" override-parameters="{'speed' : 1000, 'centeredSlides':true,'loopedSlides':<?php echo $loopedSlides; ?>, 'slideToClickedSlide' : true, 'nextButton' : '.collections__nav .icon-arrow-next', 'prevButton' : '.collections__nav .icon-arrow-prev', 'grabCursor' : true}">
		<?php $index = 0; foreach($terms as $term) : ?>
		<ks-swiper-slide class="swiper-slide">
			<span class="swiper-slide__item">
				<span class="swiper-slide__name"><?php echo $term->name; ?></span>
			</span>
			<a ui-sref="app.collection({name : '<?php echo $term->slug; ?>'})" class="swiper-slide__link"><span><?php echo $term->name; ?></span><span class="collections__loader"><span class="collections__spinner"></span></span>
			</a>
		</ks-swiper-slide>
		<?php $index++; endforeach; ?>
	</ks-swiper-container>
	<i class="icon-arrow icon-arrow-next"></i>
	</div>
</div>