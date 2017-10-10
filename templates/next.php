<?php 
	$post_type = get_post_type();
	$post_type_object = get_post_type_object( $post_type );
	$next = (is_singular('post')) ? get_next_post( ) : get_next_post( true, null, 'collezioni');
	if($next) :
?>
<a class="next next--grow-lg next--<?php echo $post_type; ?>" next-element href="<?php echo get_permalink($next->ID); ?>" ui-sref="app.page({slug : '<?php echo basename(get_permalink($next->ID)); ?>'})">
	<span class="next__cover" ng-style="{'background-image':'url(<?php echo get_the_post_thumbnail_url($next->ID, 'full'); ?>)'}"></span>
	<span class="next__container next__container--shrink-fw next__container--grow-md">
	<?php if(!is_singular('lampade')) : ?>
	<span class="next__label next__label--grow-md-bottom"><?php 
	$prossimo = ($post_type == 'post' || $post_type == 'progetti') ? __('Prossimo', 'catellani') : __('Prossima', 'catellani');
	echo $prossimo . ' ' .strtolower($post_type_object->labels->singular_name); ?></span>
	<?php endif; ?>
	<span class="next__title next__title--medium-alternate"><?php echo get_the_title($next->ID); ?>
	<?php echo (is_singular('progetti') || is_singular('installazioni')) ? '<br/><span class="next__city">'.get_field('city', $next->ID).'</span>' : '' ; ?>
	</span>
	</span>
</a>
<?php endif; ?>