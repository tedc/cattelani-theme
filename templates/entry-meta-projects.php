<?php 
global $sitepress;
$lang = $_GET['lang'] ? $_GET['lang'] : 'it';
$sitepress->switch_lang($lang, true);
?>
<aside class="meta meta--grow meta--gray meta--grid meta--shrink-fw">
	<time class="meta__cell meta__cell--grow meta__cell--shrink-right-only meta__cell--s3" pudate datetime="<?php the_time('Y:m:d'); ?>">
		<?php echo ucfirst(get_the_time('F Y')); ?>
	</time>
	<?php if(get_field('luogo') || get_field('citta')) : ?>
	<div class="meta__cell meta__cell--grow meta__cell--shrink-right-only meta__cell--s3">
		<?php the_field('luogo'); ?>, <?php the_field('citta'); ?>
	</div>
	<?php endif; ?>
	<?php if(get_field('by')) : ?>
	<div class="meta__cell meta__cell--grow meta__cell--shrink-right-only meta__cell--s3">
		<?php the_field('by'); ?>
	</div>
	<?php endif; ?>
	<?php if(get_field('photo_credits')) : ?>
	<div class="meta__cell meta__cell--grow meta__cell--shrink-right-only meta__cell--s3">
		<?php _e('Photo by', 'catellani'); ?> <?php the_field('photo_credits'); ?>
	</div>
	<?php endif; ?>
</aside>