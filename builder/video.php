<?php $sm = '{"class":{"element":".main","classes" : "main--dark"},"triggerElement":"#video_'.$row.'","triggerHook":0.5,"duration":"60%","durationElement" : "#video_'.$row.'"}'; ?>
<div class="video video--shrink-fw video--grow-lg<?php echo (get_sub_field('video_dark')) ? ' video--dark' : ''; ?>" id="video_<?php echo $row; ?>">
	<?php if(get_sub_field('video_title')) : ?>
	<header class="video__header video__header--shrink">
		<h3 class="video__title video__title--large-lighter"><?php the_sub_field('video_title'); ?></h3>
	</header>
	<?php endif;
		$file = preg_replace('/\\.[^.\\s]{3,4}$/', '', get_sub_field('video')['url']);
	?>
	<div class="video__container">
		<video loop class="video__item" ng-video muted>
			<source src="<?php echo $file; ?>.mp4" type="video/mp4"/>
			<source src="<?php echo $file; ?>.webm" type="video/webm"/>
		</video>
	</div>
	<?php if(get_sub_field('video_text')) : ?>
	<footer class="video__footer video__footer--grow-top">
		<?php if(get_sub_field('video_link')) : ?>
			<a href="<?php the_sub_field('video_link'); ?>" ui-sref="app.page({ slug : '<?php echo basename(get_sub_field('video_link')); ?>'})" class="video__send"><?php the_sub_field('video_text'); ?></a>	
		<?php else : ?>
			<?php the_sub_field('video_text'); ?>
		<?php endif; ?>
	</footer>
	<?php endif; ?>
</div>