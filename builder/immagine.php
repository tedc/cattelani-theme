<?php 
	$image = get_sub_field('file');
	if(!get_sub_field('normal')) :$p = ( $image['height'] * 100 ) / $image['width']; endif;
?>
<?php if(!is_handheld()) :
	if(!get_sub_field('normal')) :
 ?>
<style>
	.desktop #image_<?php echo $row; ?> .image__item {
		background-image: url(<?php echo $image['url']; ?>);
	}
</style>
<?php endif; endif; ?>
<div class="image image--grow-lg image--shrink-fw" id="image_<?php echo $row; ?>">
		
	<figure class="image__item<?php echo (get_sub_field('normal')) ? ' image__item--normal': ''; ?>"<?php if(!get_sub_field('normal')) : ?> style="padding-top:<?php echo $p; ?>%"<?php scrollmagic('"tween":[{"backgroundPosition": "50% 200px"},{"backgroundPosition": "50% -200px"}],"triggerElement":"#image_'.$row.'","duration":"200vh","triggerHook":1'); endif; ?>>
		<?php $pattern = '/[\w\-]+\.(svg)/'; if(preg_match($pattern, $image['url'])) : ?>
		<?php echo print_svg(get_bloginfo('url').$image['url']); ?>
		<?php else : ?>
		<img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
		<?php endif; ?>
	</figure>
</div>