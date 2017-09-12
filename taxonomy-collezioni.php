<?php
	$obj = get_queried_object();
	$collezioni = new WP_Query(
		array(
			'post_type' => 'lampade',
			'posts_per_page' => -1,
			'tax_query' => array(
				array(
					'taxonomy' => $obj->taxonomy,
					'field' => 'term_id',
					'terms' => array($obj->term_id)
				)
			)
		)
	);
	$count = $collezioni->found_posts;
?>
<div class="collections collections--slider-h" ng-scroll-carousel>
	<div class="collections__slider collections__slider--archive" scrollbar="carousel" axis-x="true">
		<?php 
			$c = 0;while($collezioni->have_posts()): $collezioni->the_post();
		?>
		<?php include(locate_template( 'templates/content-lampade.php', false, false )); ?>
		<?php $c++;endwhile;
		wp_reset_query();
		wp_reset_postdata();
		?>
	</div>
	<i class="icon-arrow icon-arrow-prev" ng-click="move(false)" ng-class="{hide : !isVisible, inactive : !isPrev}"></i>
	<i class="icon-arrow icon-arrow-next" ng-click="move(true)" ng-class="{hide : !isVisible, inactive : !isNext}"></i>
</div>