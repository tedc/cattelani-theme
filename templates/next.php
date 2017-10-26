<?php
	global $sitepress;
	$post_type = get_post_type();
	$post_type_object = get_post_type_object( $post_type );
	$next = ($post_type == 'post' || $post_type == 'progetti' || $post_type == 'installazioni' ) ? get_previous_post() : get_next_post( true, null, 'collezioni');
	$next = id_by_lang($next, get_post_type(), $sitepress->get_current_language());
	if($next) :

?>
<a class="next next--grow-lg next--<?php echo $post_type; ?>" next-element href="<?php echo get_permalink($next->ID); ?>" ui-sref="app.page({slug : '<?php echo basename(get_permalink($next->ID)); ?>', lang : '<?php echo $sitepress->get_current_language(); ?>'})">
	<span class="next__cover" ng-style="{'background-image':'url(<?php echo get_the_post_thumbnail_url($next->ID, 'full'); ?>)'}"></span>
	<span class="next__container next__container--shrink-fw next__container--grow-md">
	<span class="next__label next__label--grow-md-bottom"><?php 
	$prossimo = ($post_type == 'post' || $post_type == 'progetti') ? __('Prossimo', 'catellani') : __('Prossima', 'catellani');
	echo $prossimo . ' ' .strtolower(my_wpml_string($post_type_object->labels->singular_name)); ?></span>
	<span class="next__title next__title--medium-alternate"><?php echo get_the_title($next->ID); ?>
	<?php echo ($post_type == 'progetti' || $post_type == 'installazioni') ? '<br/><span class="next__city">'.get_field('city', $next->ID).'</span>' : '' ; ?>
	</span>
	</span>
</a>
<?php endif; ?>