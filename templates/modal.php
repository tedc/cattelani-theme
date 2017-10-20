<div class="modal" ng-class="{'modal--visible' : isPopup}">
	<span class="modal__close modal__close--shrink modal__close--grow-md-top" ng-click="closeModal()">
		<span class="modal__label"><?php _e('Chiudi', 'catellani'); ?></span>
		<span class="modal__close-sign"><span class="modal__line"></span></span>
	</span>
	<?php get_template_part('templates/modal', 'contact'); ?>
	<?php get_template_part('templates/modal', 'search'); ?>
	<?php get_template_part('templates/modal', 'downloads'); ?>
	<?php get_template_part('templates/modal', 'languages'); ?>
</div>