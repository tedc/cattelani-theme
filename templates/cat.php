<?php 
	$lang = ICL_LANGUAGE_CODE;
	acf_set_language_to_default();
	$store = apply_filters('wpml_object_id', get_field('store_locator', 'options'), 'page', false );
	//$store_obj = get_post($store->ID);
    acf_unset_language_to_default(); 
?>
<aside class="cat"<?php scrollmagic('"class":"cat--active", "triggerElement": "body", "triggerHook" : "onLeave", "offset" : "50%", "fixed" : true'); ?>>
	<div class="cat__container cat__container--store">
	<h4 class="cat__title cat__title--light"><?php _e('Trova il rivenditore più vicino', 'catellani'); ?></h4>
	<a class="cat__button" href="<?php echo get_permalink($store_id); ?>" ui-sref="app.page({slug : '<?php echo basename(get_permalink($store)); ?>'})">
		<span>
		<?php
		echo get_the_title($store); ?>
		</span>
		<i class="icon-arrow"></i>
	</a>
	</div>
	<div class="cat__container cat__container--contact">
	<h4 class="cat__title cat__title--light"><?php _e('Contattaci per un preventivo', 'catellani'); ?></h4>
	<a class="cat__button" href="#contact" ng-click="$event.preventDefault(); modal('contact');">
		<span>
		<?php
		_e('Contattaci', 'catellani' ); ?>
		</span>
		<i class="icon-arrow"></i>
	</a>
	</div>
</aside>