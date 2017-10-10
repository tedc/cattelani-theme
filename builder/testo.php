<div class="container container--grow-lg container--grow-lg-shrink<?php echo (get_sub_field('cover_image')) ? ' container--bg' : ''; ?>"<?php if(get_sub_field('cover_image')):?> ng-style="{'background-image':'url(<?php the_sub_field('cover_image'); ?>'}"<?php endif; ?><?php scrollmagic('"triggerHook":0.4,"class":"container--active","reverse":false'); ?>>
	<?php if(get_sub_field('titolo')) : ?>
	<h2 class="container__title container__title--huge"><?php the_sub_field('titolo'); ?></h2>
	<?php endif; ?>
	<div class="container__content container__content--mw<?php echo (get_sub_field('titolo')) ? ' container__content--grow-md-top': '';  echo (get_sub_field('font')>0) ? ' container__content--alternate': ''; ?>">
	<?php the_sub_field('testo'); ?>
	</div>
	<?php if(get_sub_field('firma')) : ?>
	<p class="container__sign container__sign--lighter"><?php the_sub_field('firma'); ?></p>
	<?php endif; ?>
</div>