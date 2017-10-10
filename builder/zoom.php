<div class="zoom zoom--shrink-fw" ng-click="isZoom[<?php echo $row; ?>]=!isZoom[<?php echo $row; ?>];updateScrollbar(isZoom[<?php echo $row; ?>], <?php echo get_sub_field('zoom_image')['width'] ?>, <?php echo get_sub_field('zoom_image')['height']; ?>)" ng-class="{'zoom--active' : isZoom[<?php echo $row; ?>]}" ng-zoom id="zoom_<?php echo $row; ?>" ng-mousemove="cursor($event)">
	<div class="zoom__container">
		<figure class="zoom__figure" ng-style="{'background-image' : 'url(<?php echo get_sub_field('zoom_preview') ? get_sub_field('zoom_preview')['url'] : get_sub_field('zoom_image')['url']; ?>)'}">
			<div class="zoom__scroll" scrollbar="zoom">
				<?php echo wp_get_attachment_image( get_sub_field('zoom_image')['ID'], 'full', false, array('class' => 'zoom__image')); ?>
			</div>
			<figcaption class="zoom__cursor">
				<i class="zoom__icn"></i>
				<span class="zoom__open"><?php _e('Clicca ed esplora', 'catellani'); ?></span>
				<span class="zoom__close"><?php _e('Chiudi', 'catellani'); ?></span>
			</figcaption>	
		</figure>
	</div>
</div>