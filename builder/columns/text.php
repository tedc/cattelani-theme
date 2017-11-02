<?php 
	$terms = get_terms(array('post_types' => get_sub_field('post_type'), 'taxonomy' => 'collezioni'));
	$simple = '';
	if(get_sub_field('random_quote') || get_sub_field('title_size') == 'section__title section__title--giant' || get_sub_field('col_sign')) {
		$simple = ' section__content--simple';
	}
?>
<div class="section__content section__content--grow-lg<?php echo $simple; ?>">
	<?php if(get_sub_field('random_quote')) :
	include(locate_template( 'builder/columns/aforisma.php', false, true ));
	else :?>
	<?php if(get_sub_field('title_text')) { ?>
		<h4 class="<?php the_sub_field('title_size'); the_sub_field('title_weight'); echo (get_sub_field('uppercase')) ? ' section__title--upper' : ''; ?>">
			<?php the_sub_field('title_text'); ?>
		</h4>
	<?php } if(get_sub_field('text')) {?>
	<div class="section__text section__text--shrink-<?php echo ($col%2==0) ? 'right' : 'left'; ?>-only<?php echo (get_sub_field('title_text')) ? ' section__text--grow-md-top' : ''; echo (get_sub_field('col_font')>0) ? ' section__text--alternate': ''; ?>">
		<?php the_sub_field('text'); ?>
	</div>
	<?php } 
		$cat = '';
		if(get_post_type() == 'lampade') {
			$term = wp_get_post_terms( $post->ID, 'collezioni' )[0];
			$cat = (in_array($term, $terms)) ? ', term : {id : '.$term->term_id .', name : \''.addslashes($term->name).'\'}' : ''; 
		}
		if(get_sub_field('link_text')) :
	?>
	<div class="section__link<?php echo (get_sub_field('text')) ? ' section__link--grow-md-top' : ''; ?> section__link--shrink-<?php echo ($col%2==0) ? 'right' : 'left'; ?>-only">
		<a href="<?php the_sub_field('col_link'); ?>" ui-sref="app.page({slug : '<?php echo basename(get_sub_field('col_link')); ?>'<?php echo $cat; ?>})" class="section__send"><?php the_sub_field('link_text'); ?></a>
	</div>
	<?php endif; if(get_sub_field('col_sign')) : ?>
		<p class="section__sign<?php echo (get_sub_field('text')) ? ' section__sign--grow-md-top' : ''; ?> section__sign--lighter section__link--shrink-<?php echo ($col%2==0) ? 'right' : 'left'; ?>-only"><?php the_sub_field('col_sign'); ?></p>
	<?php endif; endif; ?>
<div class="section__mask"></div>
</div>