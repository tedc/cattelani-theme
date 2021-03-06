<div class="successes">
	<?php if(get_sub_field('titolo_successi')) : ?>
	<h3 class="successes__title successes__title--big-lighter"><?php the_sub_field('titolo_successi'); ?></h3>
	<?php endif; ?>
	<div class="successes__items successes__items--grid<?php echo (get_sub_field('inverti_successi')) ? ' successes__items--grid-inverted' : ''; ?>"<?php scrollmagic('"triggerHook":0.4,"class":"successes__items--active","reverse":false'); ?>>
		<?php 
		$image = get_sub_field('immagine_successi');
		$horse = get_sub_field('cavallo_successi');
		?>
		<div class="successes__cell successes__cell--image successes__cell--s6">
			<?php 
			$alt = get_post_meta($image['ID'], '_wp_attachment_image_alt', true) ? get_post_meta($image['ID'], '_wp_attachment_image_alt', true) : get_bloginfo('name') . ': '.get_the_title();
			echo wp_get_attachment_image($image['ID'], 'large'); ?>
			<div class="successes__contain"  ng-style="{'background-image':'url(<?php echo wp_get_attachment_image_src($image['ID'], 'full')[0]; ?>'}"></div>
			<div class="successes__contain successes__contain--hover" ng-style="{backgroundImage:'url(<?php echo wp_get_attachment_image_src($horse['ID'], 'full')[0]; ?>'}"></div>
		</div>
		<div class="successes__cell successes__cell--grow-lg successes__cell--content successes__cell--s6">
			<?php if(get_sub_field('testo_successi')) : ?>
			<div class="successes__content successes__content--shrink successes__content--grow-lg">
				<?php the_sub_field('testo_successi'); ?>
			</div>
			<?php 
				endif; 
				if(get_sub_field('firma_successi')) :
			?>
			<div class="successes__sign successes__sign--shrink successes__sign--grow-md-top">
				<span><?php the_sub_field('firma_successi'); ?></span>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>