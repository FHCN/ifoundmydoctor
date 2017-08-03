<?php
/*
Plugin Name: Update Doctor Locations
Plugin URI: http://ifoundmydoctor.com
Description: Updates all the doctors locations in the database
Author: Will
Version: 1.0
Author URI: http://ifoundmydoctor.com
*/

class UpdateDoctorLocations
{
    /**
     * Activates the plugin
     */
    public static function activatePlugin() {
        // Get the list of doctors posts
        $args = array(
            'post_type' => 'location',
            'posts_per_page' => -1 // get all posts, not just 5
        );

        $posts = get_posts($args);

        foreach ($posts as $post) {
            sleep(1);
            wp_update_post($post);
        }
    }
}

// on the plugin activation hook activate the plugin
register_activation_hook( __FILE__, array('UpdateDoctorLocations', 'activatePlugin'));

