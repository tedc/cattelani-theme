<div class="successes">
	<?php if(get_sub_field('titolo_successi')) : ?>
	<h3 class="successes__title successes__title--big-lighter"><?php the_sub_field('titolo_successi'); ?></h3>
	<?php endif; ?>
	<div class="successes__items successes__items--grid<?php echo (get_sub_field('inverti_successi')) ? ' successes__items--grid-inverted' : ''; ?>">
		<?php $image = get_sub_field('immagine_successi'); ?>
		<div class="successes__cell successes__cell--image successes__cell--s6" ng-style="{'background-image':'url(<?php echo wp_get_attachment_image_src($image['ID'], 'full')[0]; ?>'}">
			<?php echo wp_get_attachment_image($image['ID'], 'large'); ?>
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
			<div class="successes__sign successes__sign--shrink successes__sign--grow-lg-top">
				<span><?php the_sub_field('firma_successi'); ?></span>
			</div>
			<?php endif; ?>
		</div>
	</div>
</div>