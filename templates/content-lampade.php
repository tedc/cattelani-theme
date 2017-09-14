<?php
	$bp = '';
	if($count == 1) {
		$size = '12';
	} elseif($count == 2) {
		$size = '6';
	} else {
		$size = '4';
	}
?>
<div class="collections__cell collections__cell--grow-md collections__cell--s<?php echo $size; ?>" data-carousel-item="<?php echo $c; ?>" ng-click="goto(<?php echo $c; ?>, {slug : '<?php echo basename(get_permalink($post->ID)); ?>'})" clicked-element data-item-background="<?php echo get_the_post_thumbnail_url($post->ID, 'full'); ?>" data-item-size="<?php echo $size; ?>" data-item-total="<?php echo ($count - 1); ?>" data-item-slug="<?php echo basename(get_permalink($post->ID)); ?>" ng-class="{'collections__cell--masking' : (isState && currentState != '<?php echo basename(get_permalink($post->ID)); ?>'), 'collections__cell--current' : (isState && currentState == '<?php echo basename(get_permalink($post->ID)); ?>'), }">
	<span class="collections__cover" ng-style="{'background-image':'url(<?php echo get_the_post_thumbnail_url($post->ID, 'full'); ?>)'}"></span>
	<span class="collections__title collections__title--medium"><span><?php echo get_the_title($post->ID); ?></span></span>
	<span class="collections__mask"></span>
</div>