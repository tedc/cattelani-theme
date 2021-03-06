<?php 
	$lang = ICL_LANGUAGE_CODE;
	acf_set_language_to_default();
	$store = get_field('store_locator', 'options');
	//$store_obj = get_post($store->ID);
    acf_unset_language_to_default(); 
    $store = apply_filters('wpml_object_id', $store, 'page', ICL_LANGUAGE_CODE );
?>
<aside class="cat cat--grow-md-bottom" ng-footer ng-class="{'cat--closed': isCatClosed}">
	<?php acf_set_language_to_default(); ?>
	<a class="cat__send" href="tel:<?php echo preg_replace('/[^0-9,.]/','',str_replace('+', '00', get_field('phone', 'options'))); ?>">
		T. <?php
		the_field('phone', 'options'); ?>
	</a>
	<?php acf_unset_language_to_default(); ?>
	<a class="cat__send" href="#contact">
		<?php
		_e('Contattaci', 'catellani' ); ?>
	</a>
	<a class="cat__send cat__send--store" href="<?php echo get_permalink($store); ?>" ui-sref="app.page({slug : '<?php echo basename(get_permalink($store)); ?>'})">
		<?php
		echo get_the_title($store); ?>
	</a>
	<span class="cat__close" ng-click="isCatClosed=!isCatClosed">
		<span class="cat__close-label"><i class="icon-arrow"></i><?php _e('Contatti', 'catellani'); ?></span>
		<span class="cat__close-sign"><span class="cat__line"></span></span>
	</span>
</aside>