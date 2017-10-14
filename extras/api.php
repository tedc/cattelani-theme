<?php
    include( locate_template( 'extras/vendor/autoload.php', false, false) );
    
    add_action( 'rest_api_init', 'api_init' );
    
    function api_init() {
        register_rest_route('api/v1', '/instagram', array(
            "methods" => 'GET',
            "callback" => 'instagram_posts'
        ));
        function instagram_posts() {
            $client_id = get_field('instagram_client_id', 'options');
            $client_secret = get_field('instagram_client_secret', 'options');
            $access_token = get_field('instagram_access_token', 'options');
            $user_id = get_field('instagram_user_id', 'options');
            $count = get_field('instagram_count', 'options');
            $api = new Instaphp\Instaphp([
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                    'redirect_uri' => get_bloginfo('url'),
                    'http_timeout' => 6000,
                    'http_connect_timeout' => 2000
                ]);
            if(!$api) {
                return;
            }
            $api->setAccessToken($access_token);
            $items = $api->Users->Recent($user_id, array('count'=>$count));
            $cached = get_transient($user_id);
            if($cached !== false) {
                return $cached;
            } else {
                $expiration_time = 60*60*2;
                set_transient($user_id, $items, $expiration_time);
                return $items;
            }
        }
       //  register_api_field(  array('post', 'lampade', 'progetti', 'installazioni', 'page'),
       //     'post_thumbnail',
       //     array(
       //         'get_callback'    => __NAMESPACE__.'\\add_post_thumbnail_src',
       //         'update_callback' => null,
       //         'schema'          => null,
       //     )
       // );
       // function add_post_thumbnail_src($object) {
       //     $id = get_post_thumbnail_id($object['id']);
       //     return str_replace('http://cdn.ferretticasa.it/http://cdn.ferretticasa.it', 'http://cdn.ferretticasa.it',wp_get_attachment_image_src($id, 'golden'));
       // }// POST THUMBNAIL
       // register_api_field(  array('post', 'builidings'),
       //     'post_class',
       //     array(
       //         'get_callback'    => __NAMESPACE__.'\\add_post_class',
       //         'update_callback' => null,
       //         'schema'          => null,
       //     )
       // );
       // function add_post_class($object) {
       //     return join(' ', get_post_class(null, $object['id']));
       // }
        register_rest_field(
            array('post', 'lampade', 'progetti', 'installazioni', 'page'),
            'post_class',
            array(
                'get_callback' => 'add_post_class'
            )
        );

        register_rest_field(
            array('progetti', 'installazioni'),
            'acf',
            array(
                'get_callback' => 'add_acf_projects'
            )
        );

        function add_acf_projects($object) {
          return  array(
                'citta' => get_field('citta', $object['id']),
                'stato' => get_field('stato', $object['id'])
              );
        }

        function add_post_class($object) {
          return join(' ',get_post_class( array( 'type-'.$object['type'].'--grow-md' ), $object['id']));
        }
        register_rest_field(
            array('post', 'lampade', 'progetti', 'installazioni', 'page'),
            'post_date',
            array(
                'get_callback' => 'post_date'
            )
        );

        function post_date($object) {
          return get_the_time('d F Y', $object['id']);
        }

        

        register_rest_field(
          array('collezioni'),
          'content',
          array(
            'get_callback' => 'collezioni_add_shortcode'
          )
        );
        function collezioni_add_shortcode($object) {
          return do_shortcode( '[collezioni id="'.$object['id'].'"]' );
        }

        register_rest_field(
          array(get_option('glossary-settings')['slug-cat']),
          'content',
          array(
            'get_callback' => 'glossary_add_shortcode'
          )
        );
        function glossary_add_shortcode($object) {
          return do_shortcode( '[glossary_cat id="'.$object['id'].'"]' );
        }

        register_rest_field(
            array('post', 'lampade', 'progetti', 'installazioni', 'page', 'category', 'collezioni', get_option('glossary-settings')['slug-cat']),
            'yoats_title',
            array(
                'get_callback' => 'yoats_title'
            )
        );

        register_rest_field(
            array('post', 'lampade', 'progetti', 'installazioni', 'page', 'category', 'collezioni', get_option('glossary-settings')['slug-cat']),
            'breadcrumbs',
            array(
                'get_callback' => 'my_breadcrumbs'
            )
        );


        function my_breadcrumbs($object, $field_name, $request) {
            global $sitepress;
            $default = $sitepress->get_default_language();
            $id = $object['id'];
            $type = (isset($object['taxonomy'])) ? 'taxonomy' : 'post';
            if($_GET) {
              $lang = (isset($_GET['lang'])) ? $_GET['lang'] : $default;
            } else {
              $lang = $default;
            }
            
            return do_shortcode( '[breadcrumb id="'.$id.'" type="'.$type.'" lang="'.$lang.'"]', false );
        }


        register_rest_field(
            array('post', 'lampade', 'progetti', 'installazioni', 'page', 'category', 'collezioni',  get_option('glossary-settings')['slug-cat']),
            'wpml_menu',
            array(
                'get_callback' => 'wpmlrestapi_slug_get_translations'
            )
        );

        register_rest_field(
            array('post', 'lampade', 'progetti', 'installazioni', 'page', 'category', 'collezioni',  get_option('glossary-settings')['slug-cat']),
            'cover',
            array(
                'get_callback' => 'cover_image'
            )
        );

        register_rest_field(
            array( 'lampade'),
            'class_size',
            array(
                'get_callback' => 'class_size'
            )
        );
        function class_size() {
          $lampade = get_posts(array('post_type'=>'lampade','posts_per_page'=>-1));
          $count = count($lampade) >= 3 ? 3 : count($lampade);
          $count = 12 / $count;
          return $count;
        }
        register_rest_field(
            array('post', 'lampade', 'progetti', 'installazioni', 'page', 'category', 'collezioni',  get_option('glossary-settings')['slug-cat']),
            'body_class',
            array(
                'get_callback' => 'add_body_class'
            )
        );

        register_rest_field(
            array('post', 'progetti', 'installazioni'),
            'post_thumbnail',
            array(
              'get_callback' => 'add_post_thumb'
            )
        );

        function add_post_thumb($object) {
           $id = get_post_thumbnail_id($object['id']);
           $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
           return array('id'=>$id, 'alt' => $alt, 'magazine' => get_the_post_thumbnail_url( $object['id'], 'magazine' ), 'large' => get_the_post_thumbnail_url( $object['id'], 'large' ), 'full' => get_the_post_thumbnail_url( $object['id'], 'full' ));
        }// POST THUMBNAIL
    

        function add_body_class($object, $field_name, $request)
        {
          global $sitepress;
          $body_class = join(' ',mobble_body_class(''));
          $body_class .= preg_match('/(wordpress_logged_in_)/', $request['cookie'][0]) ? ' logged-in admin-bar' : '';
          if(isset($object['taxonomy'])) {
            $body_class .= ' tax-'.$object['taxonomy'];
          } else {
            if($object['type'] == 'page') {
              $the_id = apply_filters('wpml_object_id', $object['id'], 'page', false, $sitepress->get_default_language());
              $white = (get_field('header_kind', $object['id']) == 0) ? '' : ' white-header';
              $white = ($the_id == get_option('page_on_front')) ? '' : $white;
              $contactBar = (get_field('is_contact_bar', $object['id'])) ? ' contact-bar' : '';
              $body_class .=  ' '.$object['slug'].$contactBar.$white;
            } else {
              $body_class .=  ' single-'.$object['type'];
            }
          }
          return $body_class;
        }

        function cover_image($object, $field_name, $request) {
          $the_id = (isset($object['taxonomy'])) ? 'collezioni_'.$object['id']: $object['id'];
          $img = get_field('featured_image',  $the_id) ? get_the_post_thumbnail_url($object['id'], 'full') : get_field('cover_image', $the_id)['url'];
          return $img;
        }
        
        function wpmlrestapi_slug_get_translations( $object, $field_name, $request ) {
            global $sitepress;
            $languages = apply_filters('wpml_active_languages', null);
            $translations = [];
            foreach ($languages as $language) {
                $type = (isset($object['taxonomy'])) ? $object['taxonomy'] : $object['type'];
                $post_id = apply_filters('wpml_object_id', $object['id'], $type, false, $language['language_code']);
                $href = (isset($object['taxonomy'])) ? get_term_link($post_id, $object['taxonomy']) : get_permalink($post_id);
                if(!empty($post_id)) {
                   $translations[] = array('lang' => $language['language_code'], 'href' => $href, 'id' => $post_id);
                } 
            }
            return $translations;
        }


        function yoats_title($object, $field_name, $request) {
            $title = (isset($object['taxonomy'])) ? WPSEO_Taxonomy_Meta::get_term_meta( $object['id'], $object['taxonomy'], 'title' ) : get_post_meta( $object['id'], '_yoast_wpseo_title', true );
            if(empty($title)) {
                $title = (isset($object['taxonomy'])) ? get_bloginfo('name') . ' | ' . $object['name'] : get_bloginfo('name') . ' | ' . $object['title']['rendered'];
            }
            return $title;
        }

        // GLOSSARY

        register_rest_field(
            array(get_option('glossary-settings')['slug']),
            'cat_slug',
            array(
                'get_callback' => 'add_glossry_cat_slug'
            )
        );
        function add_glossry_cat_slug($object) {
          $term = wp_get_post_terms( $object['id'], get_option('glossary-settings')['slug-cat'] )[0];
          return $term->slug;
        }
        // STORE LOCATOR

        register_rest_field( 'wpsl_stores',
            'address',
            array(
                'get_callback'    => 'slug_get_address'
            )
        );
        register_rest_field( 'wpsl_stores',
            'address2',
            array(
                'get_callback'    => 'slug_get_address2'
            )
        );
        register_rest_field( 'wpsl_stores',
            'city',
            array(
                'get_callback'    => 'slug_get_city'
            )
        );
        register_rest_field( 'wpsl_stores',
            'state',
            array(
                'get_callback'    => 'slug_get_state'
            )
        );
        register_rest_field( 'wpsl_stores',
          'zip',
          array(
              'get_callback'    => 'slug_get_zip'
          )
        );
        register_rest_field( 'wpsl_stores',
            'lat',
            array(
                'get_callback'    => 'slug_get_lat'
            )
        );
        register_rest_field( 'wpsl_stores',
          'lng',
          array(
              'get_callback'    => 'slug_get_lng'
          )
        );
        register_rest_field( 'wpsl_stores',
          'phone',
          array(
              'get_callback'    => 'slug_get_phone'
          )
        );
        register_rest_field( 'wpsl_stores',
          'fax',
          array(
              'get_callback'    => 'slug_get_fax'
          )
        );
        register_rest_field( 'wpsl_stores',
          'email',
          array(
              'get_callback'    => 'slug_get_email'
          )
        );
        register_rest_field( 'wpsl_stores',
          'url',
          array(
              'get_callback'    => 'slug_get_url'
          )
        );

        register_rest_field( 'wpsl_stores',
          'category',
          array(
              'get_callback'    => 'slug_get_category'
          )
        );

    }

    add_action( 'rest_api_init', 'wpml_wp_rest_api_init' );

    function wpml_wp_rest_api_init( $server ) {
        global $sitepress;
        $default = $sitepress->get_default_language();
        $langs = array_keys(icl_get_languages('skip_missing=0&orderby=KEY&order=DIR&link_empty_to=str'));//Get all available langauges (only the keys, en,ja,fr, etc).
        $cur_lang = (isset($_GET['lang'])) ? $_GET['lang'] : $default;
        if( !in_array($cur_lang, $langs ) ) {
          $cur_lang = $default;
        }
        $sitepress->switch_lang( $cur_lang );
    }

    function my_cat_query() {
        if ( array_key_exists( $tax, $args) ) {
           $tax_query = array(
               'relation' => 'AND'
           );
           $terms = explode( ',', $args[ $tax ] );  // NOTE: Assumes comma separated taxonomies
           for ( $i = 0; $i < count( $terms ); $i++) {
               array_push( $tax_query, array(
                   'taxonomy' => $args[ $tax ],
                   'field' => 'slug',
                   'terms' => array( $terms[ $i ] )
               ));            
           }
           unset( $args[ 'taxonomy' ] );  // We are replacing with our tax_query
           $args[ 'tax_query' ] = $tax_query;
       }
    }
    // Adding API access to registration locations

add_action( 'init', 'wpsl_stores_rest_support', 25 );
function wpsl_stores_rest_support() {
    global $wp_post_types;

    $post_type_name = 'wpsl_stores';
    if( isset( $wp_post_types[ $post_type_name ] ) ) {
        $wp_post_types[$post_type_name]->show_in_rest = true;
        $wp_post_types[$post_type_name]->rest_base = 'locations';
        $wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
    }
}

add_action( 'init', 'wpsl_store_category_rest_support', 25 );
function wpsl_store_category_rest_support() {
    global $wp_taxonomies;

    //be sure to set this to the name of your taxonomy!
    $taxonomy_name = 'wpsl_store_category';

    if ( isset( $wp_taxonomies[ $taxonomy_name ] ) ) {
        $wp_taxonomies[ $taxonomy_name ]->show_in_rest = true;

        // Optionally customize the rest_base or controller class
        $wp_taxonomies[ $taxonomy_name ]->rest_base = 'stores';
        $wp_taxonomies[ $taxonomy_name ]->rest_controller_class = 'WP_REST_Terms_Controller';
    }
}

add_action( 'init', 'glossary_rest_support', 25 );
function glossary_rest_support() {
    global $wp_post_types;

    $post_type_name = get_option('glossary-settings')['slug'];
    if( isset( $wp_post_types[ $post_type_name ] ) ) {
        $wp_post_types[$post_type_name]->show_in_rest = true;
        $wp_post_types[$post_type_name]->rest_base = 'glossary';
        $wp_post_types[$post_type_name]->rest_controller_class = 'WP_REST_Posts_Controller';
    }
}


add_action( 'init', 'glossary_category_rest_support', 25 );
function glossary_category_rest_support() {
    global $wp_taxonomies;

    //be sure to set this to the name of your taxonomy!
    $taxonomy_name = get_option('glossary-settings')['slug-cat'];

    if ( isset( $wp_taxonomies[ $taxonomy_name ] ) ) {
        $wp_taxonomies[ $taxonomy_name ]->show_in_rest = true;

        // Optionally customize the rest_base or controller class
        $wp_taxonomies[ $taxonomy_name ]->rest_base = get_option('glossary-settings')['slug-cat'];
        $wp_taxonomies[ $taxonomy_name ]->rest_controller_class = 'WP_REST_Terms_Controller';
    }
}

//Remove Fields from API response
function remove_content( $data, $post, $request ) {
    $_data = $data->data;
    //unset ($_data ['content']);
    unset ($_data ['excerpt']);
    unset ($_data ['link']);
    unset ($_data ['author']);
    unset ($_data ['slug']);
    unset ($_data ['date']);
    unset ($_data ['date_gmt']);
    unset ($_data ['guid']);
    unset ($_data ['modified']);
    unset ($_data ['modified_gmt']);
    unset ($_data ['type']);
    unset ($_data ['featured_media']);
    unset ($_data ['template']);
    $_data ['title'] = $_data ['title']['rendered'];
    $data = $_data;
    return $data;
}
add_filter( 'rest_prepare_wpsl_stores', 'remove_content', 12, 3 );


// Get the value of the "address" field
function slug_get_address( $object, $field_name, $request ) {
    return get_post_meta( $object['id'], 'wpsl_address', true );
}

// Get the value of the "address2" field
function slug_get_address2( $object, $field_name, $request ) {
    return get_post_meta( $object['id'], 'wpsl_address2', true );
}

// Get the value of the "city" field
function slug_get_city( $object, $field_name, $request ) {
    return get_post_meta( $object['id'], 'wpsl_city', true );
}

// Get the value of the "state" field
function slug_get_state( $object, $field_name, $request ) {
    return get_post_meta( $object['id'], 'wpsl_state', true );
}

// Get the value of the "zip" field
function slug_get_zip( $object, $field_name, $request ) {
    return get_post_meta( $object['id'], 'wpsl_zip', true );
}

// Get the value of the "latitude" field
function slug_get_lat( $object, $field_name, $request ) {
    return get_post_meta( $object['id'], 'wpsl_lat', true );
}

// Get the value of the "longitude" field
function slug_get_lng( $object, $field_name, $request ) {
    return get_post_meta( $object['id'], 'wpsl_lng', true );
}

// Get the value of the "phone" field
function slug_get_phone( $object, $field_name, $request ) {
    return get_post_meta( $object['id'], 'wpsl_phone', true );
}

// Get the value of the "phone" field
function slug_get_fax( $object, $field_name, $request ) {
    return get_post_meta( $object['id'], 'wpsl_fax', true );
}

// Get the value of the "email" field
function slug_get_email( $object, $field_name, $request ) {
    return get_post_meta( $object['id'], 'wpsl_email', true );
}

// Get the value of the "email" field
function slug_get_url( $object, $field_name, $request ) {
    return get_post_meta( $object['id'], 'wpsl_url', true );
}
function slug_get_category($object) {
    $terms = wp_get_post_terms( $object['id'], 'wpsl_store_category' );
    $value = '';
    $i = 0;
    if($terms):
    foreach ($terms as $term) {
        $comma = ($i>0) ? ', ' : '';
        $value .= $comma.$term->name;
        $i++;
    }
    endif;
    return $value;
}

function reigel_rest_query_vars( $vars ) {
    $vars[] = 'order_location';
    return $vars;
}

add_filter( 'rest_query_vars', 'reigel_rest_query_vars' );

function reigel_rest_post_query( $args, $request ) {

    $parameters = $request->get_query_params();
    
    if (isset($parameters['order_location'])) {
        $args['order_location'] = $parameters['order_location'];
    }
    
    return $args;
}
add_filter('rest_wpsl_stores_query', 'reigel_rest_post_query', 10, 2 );

add_filter( 'posts_fields', 'my_geo_fields', 10, 2 );
add_filter( 'posts_join', 'my_geo_join', 10, 2 );
add_filter( 'posts_orderby', 'my_geo_orderby', 10, 2 );
add_filter( 'posts_where', 'my_geo_where', 10, 2 );
//add_filter( 'posts_clauses', 'my_geo_clauses', 10, 5 );


// filter functions for our query
function my_geo_fields( $fields, $query ) {
    if (isset($query->query_vars['order_location'])) {
        $coords = explode(',', $query->query_vars['order_location']);
        $lat = $coords[0];
        $lon = $coords[1];    
        $fields .= ", pm1.meta_value as lat, pm2.meta_value as lon, ACOS(SIN(RADIANS($lat))*SIN(RADIANS(pm1.meta_value))+COS(RADIANS($lat))*COS(RADIANS(pm1.meta_value))*COS(RADIANS(pm2.meta_value)-RADIANS($lon))) * 6371 AS distance";
    }
    return $fields;
}

function my_geo_join( $join, $query ) {
    if (isset($query->query_vars['order_location'])) {
        global $wpdb;
        $join .=    " INNER JOIN $wpdb->postmeta pm1 ON $wpdb->posts.id = pm1.post_id AND pm1.meta_key = 'wpsl_lat'
            INNER JOIN $wpdb->postmeta pm2 ON $wpdb->posts.id = pm2.post_id AND pm2.meta_key = 'wpsl_lng' ";
    }
    return $join;
}

function my_geo_orderby( $orderby, $query ) {
    if (isset($query->query_vars['order_location'])) {
        $orderby = 'distance ASC';
    }
    return $orderby;
}
function my_geo_where( $where, $query ) {
    if (isset($query->query_vars['order_location'])) {
        $radius =  preg_match('/\[(.*?)\]/', get_option('wpsl_settings')['search_radius'], $matches);
        $radius = str_replace(array('[',']'), '', $matches[0]);
        $where .= " HAVING distance < ".$radius;
    }
    return $where;
}
    
function reigel_rest_prepare_post( $response, $post, $request ) {
    $parameters = $request->get_query_params();
    
    if (isset($parameters['order_location'])) {
        $response->data['geolocation'] = array(
            'latitude'      => isset($post->lat)?$post->lat:'', 
            'longitude'     => isset($post->lon)?$post->lon:'', 
            'distance'      => isset($post->distance)?$post->distance:''
        );
    }
    return $response;
}
add_filter( 'rest_prepare_wpsl_stores', 'reigel_rest_prepare_post', 10, 3 );


function my_rest_prepare_post( $data, $post, $request ) {
  $_data = $data->data;
  $params = $request->get_params();
  if ( !isset($_GET['slug'])  ) {
    unset( $_data['content'] );
    unset($_data['yoats_title']);
    unset($_data['breadcrumbs']);
    unset($_data['body_class']);
    unset($_data['wpml_menu']);
    unset($_data['cover']);
  } else {
    unset( $_data['post_thumbnail'] ); 
  }
  $data->data = $_data;
  return $data;
}
add_filter( 'rest_prepare_lampade', 'my_rest_prepare_post', 10, 3 );
add_filter( 'rest_prepare_post', 'my_rest_prepare_post', 10, 3 );
add_filter( 'rest_prepare_progetti', 'my_rest_prepare_post', 10, 3 );
add_filter( 'rest_prepare_installazioni', 'my_rest_prepare_post', 10, 3 );

function search_by_title_only( $search, &$wp_query )
{
    global $wpdb;
    if ( empty( $search ) )
        return $search; // skip processing - no search term in query
    $q = $wp_query->query_vars;
    $n = ! empty( $q['exact'] ) ? '' : '%';
    $search = '';
    $searchand = '';
    foreach ( (array) $q['search_terms'] as $term ) {
        $term = esc_sql( like_escape( $term ) );
        $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
        $searchand = ' AND ';
    }
    if ( ! empty( $search ) ) {
        $search = " AND ({$search}) ";
        if ( ! is_user_logged_in() )
            $search .= " AND ($wpdb->posts.post_password = '') ";
    }
    return $search;
}
add_filter( 'posts_search', 'search_by_title_only', 500, 2 );

add_filter( 'rest_endpoints', function( $endpoints ){
    if ( ! isset( $endpoints['/wp/v2/lampade'] ) ) {
        return $endpoints;
    }
    unset( $endpoints['/wp/v2/lampade'][0]['args']['per_page']['maximum'] );
    return $endpoints;
});