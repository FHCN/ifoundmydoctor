<?php
/*
Plugin Name: Set Practice Status
Plugin URI: http://ifoundmydoctor.com
Description: Sets the visibility state of a practice and all of its associated doctors, articles,
and locations to private.
Author: Will Johnson
Version: 1.0
Author URI: http://ifoundmydoctor.com
*/




add_action('admin_menu', 'set_practice_status_menu');

function set_practice_status_menu () {
    add_options_page('Set Practice Status Options', 'Set Practice Status', 'manage_options', 'set-practice-status', 'set_practice_status_options');
}

function set_practice_status_options () {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // Get the list of practices
    $args = array(
        'post_type' => 'practice',
        'post_status' => 'any',
        'posts_per_page' => -1 // get all posts, not just 5
    );

    $posts = get_posts($args);

    $postMap = array_map(function ($post) {
        return (object) array(
            'id' => $post->ID,
            'title' => $post->post_title
        );
    }, $posts);

    usort($postMap, function ($postA, $postB) {
        return strcmp($postA->title, $postB->title);
    });


    $template = <<<HTML
<form name="set-practice-private" method="post" action="">
    <fieldset class="well">
        <div class="form-group">
            <label for="practiceName">Practice:</label>
            <select class="form-control" id="practiceName" name="practiceName">
HTML;
    foreach($postMap as $post) {
        $selected = (isset($_POST['practiceName']) && $_POST['practiceName'] == $post->id) ? ' selected="selected" ' : '';
        $template .= '<option id="' . $post->id . '" value="' . $post->id . '" ' . $selected . '>' . $post->title . '</option>';
    }

    $template .= <<<HTML
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Preview</button>
        <button type="submit" class="btn btn-primary" id="setPrivate" name="setPrivate">Set To Private</button>
    </fieldset>
</form>
HTML;

    echo $template;


    if (isset($_POST['practiceName'])) {
        $practiceId = $_POST['practiceName'];

        $practiceArticles = ifg_get_practice_articles($practiceId);
        $practiceDoctors = ifg_get_practice_doctors($practiceId);
        $practiceLocations = ifg_get_practice_locations($practiceId);

        echo "<strong>***Simulation Only***</strong>";

        echo "<h4>Practice Articles To Be Made Private</h4>";
        echo "<ul>";
        foreach($practiceArticles as $article) {
            update_and_render_post_type ($article);
        }
        echo "</ul>";

        echo "<h4>Practice Doctors To Be Made Private</h4>";
        echo "<ul>";
        foreach($practiceDoctors as $doctor) {
            update_and_render_post_type ($doctor);
        }
        echo "</ul>";

        echo "<h4>Practice Locations To Be Made Private</h4>";
        echo "<ul>";
        foreach($practiceLocations as $location) {
            update_and_render_post_type ($location);
        }
        echo "</ul>";

        set_practice_to_private($practiceId);
    }
}

function set_practice_to_private ($practiceId) {
    $post = get_post($practiceId);
    update_post_if_setting_private($post);
    $post = get_post($practiceId);
    $statusClass = get_status($post);
    echo "<p>Practice Status: <span class='{$statusClass}'>$post->post_status</span></p>";
}

function update_and_render_post_type ($curPost) {
    $post = get_post($curPost);
    $statusClass = get_status($post);
    update_post_if_setting_private($post);
    $updatedPost = get_post($curPost);
    $updatedStatusClass = get_status($updatedPost);
    print_title_and_status ($post, $statusClass, $updatedPost, $updatedStatusClass);
}

function print_title_and_status ($post, $statusClass, $updatedPost, $updatedStatusClass) {
    echo "<li>" . $post->post_title . " - <span class='{$statusClass}'>*Previous Status* " . $post->post_status . "</span> --> <span class='{$updatedStatusClass}'>*Updated Status*" . $updatedPost->post_status . "</span></li>";
}

function get_status ($post) {
    return ($post->post_status === 'publish') ? 'text-success' : 'text-danger';
}

function update_post_if_setting_private ($post) {
    if (isset($_POST['setPrivate']) && $post->post_status === 'publish') {
        $currentPost = array(
            'ID'           => $post->ID,
            'post_status'  => 'private'
        );

        wp_update_post($currentPost);
    }
}