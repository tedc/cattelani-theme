<header class="banner" ng-class="{opened : isPopup, 'menu-opened' : isMenu, 'video-opened' : isVideo && isVideo != false}">
    <a class="banner__brand" href="<?= esc_url(home_url('/')); ?>">
        <?php 
        $custom_logo_id = get_theme_mod( 'custom_logo' );
        $image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
        echo print_svg(get_home_url() . $image[0]);
        ?>
    </a>
    <p class="breadcrumbs" bind-html-compile="breadcrumbs" ng-if="breadcrumbs" ng-class="{'breadcrumbs--hidden':isYearsActive}">
    </p>
    <p class="breadcrumbs" ng-if="!breadcrumbs" ng-class="{'breadcrumbs--hidden':isYearsActive}">
        <?php $term_obj = is_tax() ? get_queried_object() : false; $post_obj = (is_single() || is_page()) ? $post : false; $lang = ICL_LANGUAGE_CODE; include(locate_template( 'templates/breadcrumb.php', false, true )); ?>
    </p>
    <nav class="banner__tools banner__tools--shrink banner__tools--grow-md-top">
        <span class="banner__btn banner__btn--search" ng-click="modal('search'); startSearch();">
            <span class="banner__label">
                <?php _e('Cerca', 'catellani'); ?>
            </span>
            <i class="icon-search"></i>
        </span>
        <?php
            $strings = array(__('Menu', 'catellani'), __('Chiudi', 'catellani'));
            $mapped = array_map('strlen', $strings);
            $attr = $strings[array_search(max($mapped), $mapped)];
        ?>
        <span class="banner__btn banner__btn--toggle" menu="true">
            <span class="banner__label banner__label--menu">
                <?php _e('Menu', 'catellani'); ?>
            </span>
            <span class="banner__burger">
                <span class="banner__line banner__line--top"></span>
                <span class="banner__line banner__line--center"></span>
                <span class="banner__line banner__line--bottom"></span>
            </span>
        </span>
    </nav>
    <nav class="banner__nav banner__nav--grid">
         <span class="banner__btn banner__btn--shrink banner__btn--grow-md-top banner__btn--close" menu="false">
            <span class="banner__label banner__label--close">
                <?php _e('Chiudi', 'catellani'); ?>
            </span>
            <span class="banner__burger">
                <span class="banner__line banner__line--top"></span>
                <span class="banner__line banner__line--center"></span>
                <span class="banner__line banner__line--bottom"></span>
            </span>
        </span>
        <?php get_template_part( 'templates/aside', null ); ?>  
        <?php
        if (has_nav_menu('primary_navigation')) :
            echo '<div class="banner__menu banner__menu--grid banner__menu--shrink" scrollbar>';
            bem_menu('primary_navigation', 'menu', 'menu--grid menu--grow-lg');
            echo '</div>';
        endif;
        ?>
        <span class="banner__lang banner__lang--upper banner__lang--grow-md banner__lang--shrink" ng-click="modal('languages');">
            <?php echo ICL_LANGUAGE_CODE; ?>
        </span>
    </nav>
</header>
<?php get_template_part('templates/modal'); ?>