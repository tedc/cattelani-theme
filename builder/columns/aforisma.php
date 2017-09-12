<?php
	if(!empty($aforisma)) :
		$aforismi = get_posts(array('post_type' => 'aforismi', 'posts_per_page' => -1, 'supress_filters' => 0, 'exclude' => $aforisma));
	else :
		$aforismi = get_posts(array('post_type' => 'aforismi', 'posts_per_page' => -1, 'supress_filters' => 0));
	endif;
	$total = count($aforismi) - 1;
	$afo = $aforismi[rand(0, $total)];
	array_push($aforisma, $afo->ID);
	$text = get_field('testo_aforisma', $afo->ID);
	$sign = (get_field('default_sign', $afo->ID)) ? 'Enzo Catellani' : get_field('firma_aforisma', $afo->ID);
?>
<div class="section__text section__text--alternate">
	<p><?php echo $text; ?></p>
</div>
<p class="section__sign section__sign--grow-md-top section__sign--lighter"><?php echo $sign; ?></p>