<?php
	$storia = get_posts(
		array(
			'post_type' => 'anni',
			'posts_per_page' => -1,
			'order_by' => 'title',
			'order' => 'ASC',
			'suppress_filters' => 0
		)
	);
	if($storia) :
?>
<div class="storia storia--grow-lg" ng-swiper>
<header class="storia__header storia__header--grow-lg storia__header--shrink-fw">
	<h3 class="storia__title storia__title--big-lighter-aligncenter"><?php _e('La Storia', 'catellani'); ?></h3>
</header>
<nav class="storia__nav storia__nav--shrink storia__nav--grow-md">
	<ul class="storia__items storia__items--grid-nowrap storia__items--shrink">
		<?php $i = 0; foreach($storia as $s): ?>
		<li class="storia__cell storia__cell--s2 storia__cell"<?php if($i==0) : ?> ng-init="isYears = <?php echo $s->ID; ?>"<?php endif; ?> ng-class="{'storia__cell--active' : isYears == <?php echo $s->ID; ?>}" ng-click="slideTo(<?php echo $i; ?>); isYears = <?php echo $s->ID;; ?>">
			<?php 
				$split_title = get_the_title($s->ID);  
				$split_title = explode('/', $split_title);
			?>
			<span class="storia__year storia__year--first"><span class="storia__date"><?php echo trim($split_title[0]); ?></span></span>
			<span class="storia__year storia__year--last"><span class="storia__date"><?php echo trim($split_title[1]); ?></span></span>
			<span class="storia__label" ng-click="isYearsActive = !isYearsActive"><?php _e('Esplora', 'catellani'); ?></span>
		</li>
		<?php $i++; endforeach; wp_reset_postdata();?>
	</ul>
</nav>
<ks-swiper-container class="storia__slider" swiper="main" override-parameters="{'effect':'fade', 'autoHeight' : true, 'fade':{'crossFade':true}}" ng-class="{'storia__slider--years-visible':isYearsActive}">
	<?php foreach($storia as $s): ?>
	<ks-swiper-slide class="swiper-slide swiper-slide--grow-md swiper-slide--shrink-fw">
		<figure class="swiper-slide__figure">
			<?php 
			$thumbnail_id = get_post_thumbnail_id( $s->ID );
					
			echo wp_get_attachment_image($thumbnail_id, 'large');
			 ?>
			<figcaption class="swiper-slide__alt">
				<?php 
					$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
					echo $alt;
				?>
			</figcaption>
		</figure>
		<article class="<?php echo join(' ', get_post_class('type-anni--shrink', $s->ID)); ?>">
			<h2 class="type-anni__title">
				<?php 
					$split_title = get_the_title($s->ID);  
					$split_title = explode('/', $split_title);
					echo trim($split_title[0]).'<br/>'.trim($split_title[1]);
				?>
			</h2>
			<?php include(locate_template( 'templates/content-single-'.$s->post_type .'.php', false, true)); ?>
			<footer class="type-anni__footer anni__footer--shrink type-anni__footer--grid type-anni__footer--grow-md-top">
				<span class="type-anni__send" ng-click="isYearsActive = !isYearsActive"><?php _e('Torna alla storia', 'catellani'); ?></span>
				<?php 
					$oldPost = $post;
					$post = $s;
					$next = get_next_post(); 
					if($next) : 
				?>
				<span class="type-anni__next" ng-click="next();isYears = <?php echo $next->ID; ?>"><?php echo get_the_title($next->ID); ?></span>
				<?php endif;
					$post = $oldPost;
				 ?>
			</footer>
		</article>
	</ks-swiper-slide>
	<?php endforeach; wp_reset_postdata(); ?>
</ks-swiper-container>
<?php endif; ?>