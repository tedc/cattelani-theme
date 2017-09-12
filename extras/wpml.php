<?php
    // LANG

    if ( !function_exists( 'acf_get_language_default' ) )
    {
        function acf_get_language_default()
        {
            return acf_get_setting( 'default_language' );
        }
    }

    if ( !function_exists( 'acf_set_language_to_default' ) )
    {
        function acf_set_language_to_default()
        {
            add_filter( 'acf/settings/current_language', 'acf_get_language_default', 100 );
        }
    }

    if ( !function_exists( 'acf_unset_language_to_default' ) )
    {
        function acf_unset_language_to_default()
        {
            remove_filter( 'acf/settings/current_language', 'acf_get_language_default', 100 );
        }
    }
    function icl_selector() {
        global $post;
        $langs = '<ul class="menu-language">';
        $other_langs = '';
        $languages = icl_get_languages('skip_missing=0');
        $lang = "'lang'";
        if(!empty($languages)){
            $langs .= '<li class="menu-item lang-menu-item menu-item-has-children">';
            foreach($languages as $l){
                $language_link = $l['url'];
                $language_code = $l['language_code'];
                if($language_code != ICL_LANGUAGE_CODE) {
                    if(icl_object_id( $post->ID,  get_post_type(), false, $language_code) ) {
                        $other_langs .= '<li class="menu-item" ><a href="'.$language_link.'">'.$language_code.'</a></li>';
                    }
                }
            }
            foreach($languages as $l){
                $language_link = $l['url'];
                $language_code = $l['language_code'];
                if($language_code == ICL_LANGUAGE_CODE) {
                    $langs .= '<a href="'.$language_link.'">'.$language_code.'</a><ul class="sub-menu">'.$other_langs.'</ul></li>';
                }
            }
        }
        $langs.="</ul>";
        echo $langs;
    }

    function lang_nav() {
        $languages = icl_get_languages('skip_missing=0');
        $lang = "'lang'";
        $langs = '<div class="banner__lang banner__lang--upper">';
        $count = 0;
        foreach($languages as $l){
            $language_link = $l['url'];
            $language_code = $l['language_code'];
            $sep = ($count == 0) ? '<span class="sep">|</span>' : '';
            $active = ($language_code == ICL_LANGUAGE_CODE) ? ' active' : '';
            $langs .= '<a href="'.$language_link.'" class="lang'.$active.'">'.$language_code.'</a>'.$sep;
            $count++;
        }
        $langs .= '</div>';
        echo $langs;
    }

    define('ICL_DONT_LOAD_NAVIGATION_CSS', true);
    define('ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true);
    define('ICL_DONT_LOAD_LANGUAGES_JS', true);