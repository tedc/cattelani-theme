<?php
	$query = new WP_Query(
		array(
			'post_type' => 'servizi',
			'posts_per_page' => -1,
			'oderby' => 'menu_order'
		)
	);
	if($query->have_posts()) : ?>
<div class="services services--shrink services--grid-align-start">
	<div class="services__cell services__cell--s6"></div>
	<?php 
		$count = 0; while($query->have_posts()) : $query->the_post();
		$scrollmagic = ($count%2==0) ? '"tween":{"y" : 80}, "duration" : "200vh", "triggerHook" : "onEnter", "triggerElement" : "#service_'.get_the_ID().'"' : '"tween":{"y" : -280}, "offset" : 80, "duration" : "200vh", "triggerHook" : "onEnter", "triggerElement" : "#service_'.get_the_ID().'"';
	 ?>
	<div class="services__cell services__cell--s6" id="service_<?php the_ID() ?>">
		<div class="services__content services__content--grow-md services__content--shrink"<?php scrollmagic($scrollmagic); ?>>
			<?php echo '<figure class="svg svg--grow-md-bottom">'. print_svg(get_bloginfo('url') . get_field('icon')) .'</figure>'; ?>
			<h4 class="services__title services__title--small">
				<strong><?php the_title(); ?></strong>
			</h4>
			<p><?php the_field('subtitle'); ?></p>
		</div>
	</div>
	<?php $count++; endwhile; wp_reset_query(); ?>
</div>
<?php endif; ?>