<div class="modal__container" id="modal-downloads"  scrollbar>
	<?php
		$title = __('Scarica i cataloghi', 'catellani');
		$string = __('Catalogo', 'catellani');
		acf_set_language_to_default();
		if(have_rows('downloads', 'options')):?>
	<nav class="modal__nav modal__nav--shrink">
		<header class="modal__header modal__header--grow-md-bottom">
			<h4 class="modal__title modal__title--small modal__title--small-light"><?php echo $title; ?></h4>	
		</header>
		<?php while(have_rows('downloads', 'options')) : the_row(); ?>
		<a href="<?php the_sub_field('file');  ?>" class="modal__button download-activator" target="_blank"><span class="download"><?php //echo $string; ?><?php the_sub_field('year'); ?></span><i class="icon-arrow-download"></i></a>
		<?php endwhile; ?>
	</nav>
	<?php endif; acf_unset_language_to_default(); ?>
</div>