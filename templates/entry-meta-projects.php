<aside class="meta meta--grow meta--gray meta--grid meta--shrink-fw">
	<time class="meta__cell meta__cell--grow meta__cell--shrink-right-only meta__cell--s3" pudate datetime="<?php the_time('Y:m:d'); ?>">
		<?php echo ucfirst(get_the_time('F Y', get_the_ID())); ?>
	</time>
	<div class="meta__cell meta__cell--grow meta__cell--shrink-right-only meta__cell--s3">
		<?php the_field('luogo'); ?>, <?php the_field('citta'); ?>
	</div>
	<div class="meta__cell meta__cell--grow meta__cell--shrink-right-only meta__cell--s3">
		<?php the_field('by'); ?>
	</div>
	<div class="meta__cell meta__cell--grow meta__cell--shrink-right-only meta__cell--s3">
		<?php _e('Photo by', 'catellani'); ?> <?php the_field('photo_credits'); ?>
	</div>
</aside>