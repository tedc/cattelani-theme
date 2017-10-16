<?php 
	$lang = ICL_LANGUAGE_CODE;
	acf_set_language_to_default();
	$store = apply_filters('wpml_object_id', get_field('store_locator', 'options'), 'page', false );
	//$store_obj = get_post($store->ID);
    acf_unset_language_to_default(); 
?>
<aside class="cat cat--grow-md-bottom" ng-sm='[{"class":"cat--active", "triggerElement": "body", "triggerHook" : "onLeave", "offset" : "50%", "fixed" : true},{"class":"cat--inactive", "triggerElement": ".footer__brand svg", "triggerHook" : "onEnter"}]' ng-class="{'cat--visible':isCat}">
	<?php acf_set_language_to_default(); ?>
	<a class="cat__dot" href="tel:<?php echo preg_replace('/[^0-9,.]/','',str_replace('+', '00', get_field('phone', 'options'))); ?>">
		<span class="cat__label">
		<?php
		the_field('phone', 'options'); ?>
		</span>
	</a>
	<?php acf_unset_language_to_default(); ?>
	<a class="cat__dot" href="<?php echo get_permalink($store_id); ?>" ui-sref="app.page({slug : '<?php echo basename(get_permalink($store)); ?>'})">
		<span class="cat__label">
		<?php
		echo get_the_title($store); ?>
		</span>
	</a>
	<a class="cat__dot" ui-sref="{'#':'prova'}">
		<span class="cat__label">
		<?php
		_e('Contattaci', 'catellani' ); ?>
		</span>
	</a>
	<span class="cat__main" ng-click="isCat=!isCat">
	</span>
</aside>