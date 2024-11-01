<?php

/*
  Plugin Name: WP Intelligist
  Plugin URI: http://innovativephp.com/wp-intelligist
  Description: Embed codes from github directly into your blog posts or pages using jquery-intelligist and chosen.
  Jquery Intelligist is a plugin created by Scott Robbin on https://github.com/srobbin/jquery-intelligist
  Chosen is a plugin created by Patrick Filler on https://github.com/harvesthq/chosen
  Version: 1.0
  Author: Rakhitha Nimesh
  Author URI: http://innovativephp.com/about/
  License: GPLv2 or later
 */


/*
 * Load css and script files for chosen and intelligist plugins
 */
add_filter('the_posts', 'wp_ingist_scripts_and_styles');

function wp_ingist_scripts_and_styles($posts) {
    if (empty($posts))
        return $posts;

    $wp_ingist_shortcode_found = false; // use this flag to see if styles and scripts need to be enqueued
    foreach ($posts as $post) {
        if (stripos($post->post_content, '[wp_intelligist') !== false) {
            $$wp_ingist_shortcode_found = true; // bingo!
            break;
        }
    }

    if ($$wp_ingist_shortcode_found) {
        // enqueue here
        wp_enqueue_style('inttelist-chosen', plugins_url('/chosen/chosen.css', __FILE__));
        wp_enqueue_script('jy-min', 'https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js');
        wp_enqueue_script('chosen-jquery-min', plugins_url('/chosen/chosen.jquery.min.js', __FILE__));
        wp_enqueue_script('intelligist-min', plugins_url('/intelligist/jquery.intelligist.min.js', __FILE__));
    }

    return $posts;
}

/*
 * Extract git codes and titles from shortcode and call the intelligist plugin
 */
function wp_ingist_embed_codes($atts, $content = "") {


    $codes = $atts['codes'];
    $desc = $atts['desc'];


    $codes = explode(",", $codes);
    $desc = explode(",", $desc);

    foreach ($codes as $key => $git_code) {
        $content.= "'$git_code':'" . $desc[$key] . "',";
    }
    $content = substr($content, 0, -1);
    
    $randomId = rand(1, 10);

    return '<script>
                $(document).ready(function(){                
                    $("#demo'.$randomId.'").intelligist({' . $content . '}, { exec: true });
            });

	</script><p id="demo'.$randomId.'"></p>';
    
}

add_shortcode("wp_intelligist", "wp_ingist_embed_codes");


/*
 * Add shortcode button to TinyMCE Editor
 */

$wp_ingist_base_dir = WP_PLUGIN_URL . '/' . str_replace(basename(__FILE__), "", plugin_basename(__FILE__));

// registers the buttons for use
function wp_ingist_register_buttons($buttons) {
    array_push($buttons, "|", "git_button");
    return $buttons;
}

// filters the tinyMCE buttons and adds our custom buttons
function wp_ingist_editor_buttons() {
    // Don't bother doing this stuff if the current user lacks permissions
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
        return;

    // Add only in Rich Editor mode
    if (get_user_option('rich_editing') == 'true') {
        // filter the tinyMCE buttons and add our own
        add_filter("mce_external_plugins", "wp_ingist_tinymce_plugin");
        add_filter('mce_buttons', 'wp_ingist_register_buttons');
    }
}

// init process for button control
add_action('init', 'wp_ingist_editor_buttons');

// add the button to the tinyMCE bar
function wp_ingist_tinymce_plugin($plugin_array) {
    global $wp_ingist_base_dir;
    $plugin_array['git_button'] = $wp_ingist_base_dir . 'git-shortcode.js';
    return $plugin_array;
}

?>
