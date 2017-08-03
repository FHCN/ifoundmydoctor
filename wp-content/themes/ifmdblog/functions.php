<?php

function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array('parent-style')
    );
}

/**
 * Removes images from the given content. This is used as a filter to remove images from a post.
 * @param $content
 * @return mixed
 */
function strip_images ($content) {
    return preg_replace('/<img[^>]+./', '', $content);
}


# Register Sidebars
# Note: In a child theme with custom theme_setup_theme() this function is not hooked to widgets_init
function theme_widgets_init() {
    //Default Sidebar
    register_sidebar(array(
        'name' => 'Default Sidebar',
        'id' => 'default-sidebar',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    ));
    //Default Blog Sidebar
    register_sidebar(array(
        'name' => 'Default Blog Sidebar',
        'id' => 'default-blog-sidebar',
        'before_widget' => '<li id="%1$s" class="widget-blog %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    ));
    //Home Top Sidebar
    register_sidebar(array(
        'name' => 'Home Top Widget Area',
        'id' => 'home-top-widget-area',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    ));
    //Sub Page Top Sidebar
    register_sidebar(array(
        'name' => 'Subpage Top Sidebar',
        'id' => 'subpage-top-widget-area',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    ));
    //Home Middle Sidebar
    register_sidebar(array(
        'name' => 'Home Middle Widget Area',
        'id' => 'home-middle-widget-area',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    ));
    //Home Right Sidebar
    register_sidebar(array(
        'name' => 'Home Right Widget Area',
        'id' => 'home-right-widget-area',
        'before_widget' => '<li id="%1$s" class="widget %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    ));
    //Footer Widget Area
    register_sidebar(array(
        'name' => 'Footer Widget Area',
        'id' => 'footer-widget-area',
        'before_widget' => '<li id="%1$s" class="widget-footer-nav %2$s">',
        'after_widget' => '</li>',
        'before_title' => '<h3 class="widgettitle">',
        'after_title' => '</h3>',
    ));
}

//Get the excerpt
function ifd_get_excerpt_max_charlength($old_excerpt, $charlength, $allowed_tags = '')
{
    // Make sure to run the excerpt through the shortcode filter or you will see shortcodes
    $excerpt = do_shortcode(strip_tags($old_excerpt, $allowed_tags));

    $charlength++;

    if (mb_strlen($excerpt) > $charlength) {
        $subex = mb_substr($excerpt, 0, $charlength - 5);
        $exwords = explode(' ', $subex);
        $excut = -(mb_strlen($exwords[count($exwords) - 2]));
        if ($excut < 0) {
            $excerpt = mb_substr($subex, 0, $excut);
        } else {
            $excerpt = $subex;
        }

        $excerpt .= '...';
    } else {
        $excerpt .= '...';
    }

    return $excerpt;
}


# Add Actions
add_action('widgets_init', 'theme_widgets_init');
// Add actions
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

include_once('lib/custom-widgets/widgets.php');
include_once('options/theme-widgets.php');
