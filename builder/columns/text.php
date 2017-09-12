<div class="section__content section__content--grow-lg section__content--shrink-<?php echo ($col%2==0) ? 'right' : 'left'; ?>-only">
	<?php if(get_sub_field('random_quote')) :
	include(locate_template( 'builder/columns/aforisma.php', false, true ));
	else :?>
	<?php if(get_sub_field('title_text')) { ?>
		<h4 class="<?php the_sub_field('title_size'); the_sub_field('title_weight'); echo (get_sub_field('uppercase')) ? ' section__title--upper' : ''; ?>">
			<?php the_sub_field('title_text'); ?>
		</h4>
	<?php } if(get_sub_field('text')) {?>
	<div class="section__text<?php echo (get_sub_field('title_text')) ? ' section__text--grow-md-top' : ''; echo (get_sub_field('col_font')>0) ? ' section__text--alternate': ''; ?>">
		<?php the_sub_field('text'); ?>
	</div>
	<?php } if(get_sub_field('col_link')) : ?>
	<div class="section__link section__link--grow-md-top">
		<a href="<?php the_sub_field('col_link'); ?>" ui-sref="app.page({slug : '<?php echo basename(get_sub_field('col_link')); ?>'})" class="section__send"><?php the_sub_field('link_text'); ?></a>
	</div>
	<?php endif; if(get_sub_field('col_sign')) : ?>
		<p class="section__sign section__sign--grow-md-top section__sign--lighter"><?php the_sub_field('col_sign'); ?></p>
	<?php endif; endif; ?>
</div>