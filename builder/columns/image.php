<?php 
	$image = get_sub_field('immagine');
	if($image) :		
	$p = ( $image['height'] * 100 ) / $image['width'];
?>
<figure class="section__figure<?php echo (get_sub_field('move_up')) ? ' section__figure--move-up' : ''; ?>">
	<div class="section__figure-container"<?php scrollmagic('"tween":[{"y" : 40},{"y" : -40}], "duration" : "200vh", "triggerHook" : "onEnter", "triggerElement" : "#col_'.$col.'_'.$row.'"'); ?>><img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
	<div class="section__mask"></div>
	</div>
</figure>
<?php endif; ?>