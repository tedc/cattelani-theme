<div class="zoom zoom--shrink-fw" ng-class="{'zoom--active' : isZoom[<?php echo $row; ?>]}" ng-zoom id="zoom_<?php echo $row; ?>">
	<header class="zoom__header zoom__header--shrink"><h4 class="zoom__title zoom__title--larger-light"><?php _e('Guarda i dettagli'); ?></h4></header>
	<div class="zoom__container" ng-mouseenter="isCursor=true" ng-mousemove="cursor($event)" ng-mouseleave="leave()" ng-click="isZoom[<?php echo $row; ?>]=true;updateScrollbar(isZoom[<?php echo $row; ?>], <?php echo get_sub_field('zoom_image')['width'] ?>, <?php echo get_sub_field('zoom_image')['height']; ?>)">
		<figure class="zoom__figure" ng-style="{'background-image' : 'url(<?php echo get_sub_field('zoom_preview') ? get_sub_field('zoom_preview')['url'] : get_sub_field('zoom_image')['url']; ?>)'}">
			<div class="zoom__scroll">
				<?php echo wp_get_attachment_image( get_sub_field('zoom_image')['ID'], 'full', false, array('class' => 'zoom__image')); ?>
			</div>
			<figcaption class="zoom__cursor" ng-class="{'zoom__cursor--hidden':closeHover}">
				<i class="zoom__icn"></i>
				<span class="zoom__open"><?php _e('Clicca ed esplora', 'catellani'); ?></span>
				<span class="zoom__drag"><?php _e('Trascina ed esplora', 'catellani'); ?></span>
			</figcaption>
			<span class="zoom__close zoom__close--shrink zoom__close--grow-md" ng-click="$event.stopPropagation();isZoom[<?php echo $row; ?>]=false" ng-mouseenter="closeHover = true" ng-mouseleave="closeHover = false">
				<span class="zoom__label"><?php _e('Chiudi', 'catellani'); ?></span>
				<span class="zoom__close-sign"><span class="zoom__line"></span></span>
			</span>
		</figure>
	</div>
</div>