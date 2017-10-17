<?php if(get_field('related')) : 
?>
<div class="related related--shrink-fw related--grow-lg related--gray" id="related_<?php the_ID(); ?>" ng-swiper>
	<header class="related__header related__header--grow-lg">
		<h4 class="related__title related__title--aligncenter-medium-lighter"><?php _e('Le lampade utilizzate', 'catellani'); ?></h4>
	</header>
	<div class="related__items related__items--grow-lg-bottom related__items--grow-md-top">
		<ks-swiper-container slides-per-view="'auto'" show-nav-buttons="true" swiper="main" override-parameters="{'nextButton' : '.related .icon-arrow-next', 'prevButton' : '.related .icon-arrow-prev'}">
		<?php 
			$rels = get_posts(
				array(
					'post_type' => 'lampade',
					'post__in' => get_field('related'),
					'posts_per_page' => count(get_field('realted')),
					'orderby' => 'post__in',
					'suppress_filters' => 0
				)
			);
			$count = $rels;
			foreach($rels as $rel) :
			if($count == 1) {
				$size = 'swiper-slide--s12';
			} elseif($count == 2) {
				$size = 'swiper-slide--s6';
			} else {
				$size = 'swiper-slide--s4';
			}
		?>
		<ks-swiper-slide class="swiper-slide swiper-slide--grow-md <?php echo $size; ?>">
			<figure class="swiper-slide__image">
				<?php 
				echo get_the_post_thumbnail( $rel->ID, 'vertical-thumb', '' );
				 ?>
			</figure>
			<h2 class="swiper-slide__title swiper-slide__title--alternate"><a class="swiper-slide__link" ui-sref="app.page({slug : '<?php echo basename(get_permalink($rel->ID)); ?>'})"><?php echo get_the_title($rel->ID); ?></a></h2>
			</a>
		</ks-swiper-slide>
		<?php endforeach; ?>
		</ks-swiper-container>
		<i class="icon-arrow icon-arrow--prev"></i>
		<i class="icon-arrow icon-arrow--next"></i>
	</div>
</div>
<?php endif; ?>