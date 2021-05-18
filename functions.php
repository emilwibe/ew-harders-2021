<?php
function harders_enqueues()
{
    wp_enqueue_style( 'production-styles', get_template_directory_uri() . '/dist/core.min.css' );

    if ( is_user_logged_in() ) {
        wp_enqueue_style('child-theme-styles', get_stylesheet_directory_uri() . '/dist/core.dev.css', 'production-styles');
    } else {
        wp_enqueue_style('child-theme-styles', get_stylesheet_directory_uri() . '/dist/core.min.css', 'production-styles');
    }
}

add_action('wp_enqueue_scripts', 'harders_enqueues', 'core-styles', 99);

add_image_size('thumbnail', 300, 300, true);

//DISABLE DEFAULT GUTENBERG STYLES
add_action('wp_print_styles', 'p_deactivate_gutenberg_stylesheet', 1);

function p_deactivate_gutenberg_stylesheet()
{
    wp_dequeue_style('wp-block-library');
    wp_deregister_style('wp-block-library');
}

if (!function_exists('cpt_c3_calendar')) {

    // Register Custom Post Type
    function cpt_c3_calendar()
    {

        $labels = array(
            'name'                  => _x('Spilledatoer', 'Post Type General Name', 'cs3rio'),
            'singular_name'         => _x('Spilledato', 'Post Type Singular Name', 'cs3rio'),
            'menu_name'             => __('Spilledatoer', 'cs3rio'),
            'name_admin_bar'        => __('Spilledatoer', 'cs3rio'),
            'archives'              => __('Item Archives', 'cs3rio'),
            'attributes'            => __('Item Attributes', 'cs3rio'),
            'parent_item_colon'     => __('Parent Item:', 'cs3rio'),
            'all_items'             => __('All Items', 'cs3rio'),
            'add_new_item'          => __('Add New Item', 'cs3rio'),
            'add_new'               => __('Add New', 'cs3rio'),
            'new_item'              => __('New Item', 'cs3rio'),
            'edit_item'             => __('Edit Item', 'cs3rio'),
            'update_item'           => __('Update Item', 'cs3rio'),
            'view_item'             => __('View Item', 'cs3rio'),
            'view_items'            => __('View Items', 'cs3rio'),
            'search_items'          => __('Search Item', 'cs3rio'),
            'not_found'             => __('Not found', 'cs3rio'),
            'not_found_in_trash'    => __('Not found in Trash', 'cs3rio'),
            'featured_image'        => __('Featured Image', 'cs3rio'),
            'set_featured_image'    => __('Set featured image', 'cs3rio'),
            'remove_featured_image' => __('Remove featured image', 'cs3rio'),
            'use_featured_image'    => __('Use as featured image', 'cs3rio'),
            'insert_into_item'      => __('Insert into item', 'cs3rio'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'cs3rio'),
            'items_list'            => __('Items list', 'cs3rio'),
            'items_list_navigation' => __('Items list navigation', 'cs3rio'),
            'filter_items_list'     => __('Filter items list', 'cs3rio'),
        );
        $args = array(
            'label'                 => __('Spilledato', 'cs3rio'),
            'description'           => __('Spilledatoer', 'cs3rio'),
            'labels'                => $labels,
            'supports'              => array('title', 'editor', 'thumbnail', 'revisions'),
            'hierarchical'          => false,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-calendar-alt',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
        );
        register_post_type('cs3_calendar', $args);
    }

    add_action('init', 'cpt_c3_calendar', 0);
}


if (!function_exists('cpt_c3_udgivelse')) {

    // Register Custom Post Type
    function cpt_c3_udgivelse()
    {

        $labels = array(
            'name'  =>  'Udgivelser',
            'singular_name' =>  'Udgivelse',
            'add_new' =>  'Tilføj ny',
            'add_new_item'  =>  'Tilføj ny udgivelse',
            'edit_item' =>  'Rediger udgivelse',
            'new_item'  =>  'Ny udgivelse',
            'view_item' => 'Se udgivelse',
            'view_items'  =>  'Se udgivelser',
            'search_items'  => 'Søg udgivelse',
            'not_found' =>  'Ingen udgivelser fundet',
            'not_found_in_trash'  =>  'Ingen udgivelser fundet i papirkurven',
        );
        $args = array(
            'labels' => $labels,
            'public'  =>  true,
            'show_in_admin_bar' =>  true,
            'menu_position' =>  6,
            'menu_icon' =>  'dashicons-format-audio',
            'supports' => array('title', 'editor', 'author', 'thumbnail')
        );
        register_post_type('cs3_udgivelse', $args);
    }

    add_action('init', 'cpt_c3_udgivelse', 0);
}

/**
 * Rewrite post type slug
 */
add_filter('cs3_udgivelse_post_type_args', '_cs3_rewrite_slug');
function _cs3_rewrite_slug($args)
{
    $args['rewrite']['slug'] = 'udgivelse';
    return $args;
}

/**
 * Change "Enter title here"
 */
if (is_admin()) {
    add_filter('enter_title_here', function ($input) {
        if ('cs3_udgivelse' === get_post_type()) {
            return __('Indtast titel på udgivelse', 'textdomain');
        } else {
            return $input;
        }
    });
}

/**
 * Replace embedded YouTube video with nocookie version
 */
function filter_cstrio_youtube_nocookie($youtube_embed_code)
{
    return str_replace('https://www.youtube.com', 'https://www.youtube-nocookie.com', $youtube_embed_code);
}

//Widgets
add_action('widgets_init', 'cstrio_register_widgets');

function cstrio_register_widgets()
{
    register_sidebar(array(
        'name' => __('Footer Widgets 1', '_production'),
        'id' => 'cstrio_footer_widgets_1',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget_heading">',
        'after_title' => '</h2>'
    ));
    register_sidebar(array(
        'name' => __('Footer Widgets 2', '_production'),
        'id' => 'cstrio_footer_widgets_2',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget_heading">',
        'after_title' => '</h2>'
    ));
    register_sidebar(array(
        'name' => __('Footer Widgets 3', '_production'),
        'id' => 'cstrio_footer_widgets_3',
        'before_widget' => '<div>',
        'after_widget' => '</div>',
        'before_title' => '<h2 class="widget_heading">',
        'after_title' => '</h2>'
    ));
}