<aside class="banner__aside banner__aside--shrink">
	<div class="banner__quote">
		<?php
			$aforismi = get_posts(array('post_type' => 'aforismi', 'posts_per_page' => -1));
			$total = count($aforismi) - 1;
			$aforisma = $aforismi[rand(0, $total)];
			$text = get_field('testo_aforisma', $aforisma->ID);
			$sign = (get_field('default_sign', $aforisma->ID)) ? 'Enzo Catellani' : get_field('firma_aforisma', $aforisma->ID);
		?>
		<p><?php echo $text; ?></p>
		<em><?php echo $sign; ?></em>	
		<?php acf_set_language_to_default();get_template_part( 'templates/social', null );acf_unset_language_to_default(); ?>
	</div>
	<footer class="banner__footer banner__footer--grid banner__footer--grow-md">
		<div class="banner_cell banner__cell--s6">
    		<?php acf_set_language_to_default(); 
		        the_field('info', 'options'); 
		      acf_unset_language_to_default(); ?>
	    </div>
	    <div class="banner__cell banner__cell--s6">
	   		<a href="http://www.bspkn.it" target="_blank" class="icon-credits"></a>
	    </div>
	</footer>
</aside>