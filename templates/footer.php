<footer class="footer footer--grow footer--shrink-fw" id="footer">
    <a class="footer__brand" href="<?= esc_url(home_url('/')); ?>">
    <?php 
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
        echo print_svg(get_home_url() . $image[0]);
        ?>
    </a>
    <div class="footer__container footer__container--grow-md-bottom footer__container--grid">
        <div class="footer__cell footer__cell--s4">
            <?php get_template_part( 'templates/social'); ?>
        </div>
        <div class="footer__cell footer__cell--s4">
            <?php acf_set_language_to_default(); 
            the_field('info', 'options'); 
            acf_unset_language_to_default(); ?>
        </div>
        <div class="footer__cell footer__cell--s4">
        	<a href="http://www.bspkn.it" target="_blank" class="icon-credits"></a>
        </div>
    </div>
</footer>
<div class="transitioner">
    <div class="transitioner__wrapper">
        <div class="transitioner__cover"></div>
    </div>
</div>
<?php get_template_part( 'templates/cat', null );