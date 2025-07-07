<?php

/*
Plugin Name: Website Info
Description: The plugin that helps you to get quick information about the WordPress version, the PHP version, all plugins and all themes.
Version: 1.0
Author: Ilya Bashlyaev
*/

add_action('admin_menu', 'website_info_init');
function website_info_init() {
    add_menu_page('Website Info', 'Website Info', 'edit_posts', 'website_info', 'website_info_main', 'dashicons-info', 2);
}

function website_info_main() {
    if (!get_option('WEBSITE_INFO_API')) {
        $api_key = strtoupper(substr(sha1(microtime()), 0, 40));
        $api_key = implode('-', str_split($api_key, 8));
        update_option('WEBSITE_INFO_API', $api_key);
    }

    require 'main.php';
}

function website_info_themes() {
    $wp_themes = wp_get_themes();
    $themes = array();

    foreach ($wp_themes as $theme) {
        $themes[] = array(
            'name' => $theme['Name'], 'version' => $theme['Version'],
            'description' => $theme['Description'], 'author' => $theme['Author']
        );
    }

    return $themes;
}

function website_info_plugins() {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';

    $wp_plugins = get_plugins();
    $plugin_path = array_keys($wp_plugins);
    $plugins = array();
    $i = 0;

    foreach ($wp_plugins as $plugin) {
        $plugins[] = array(
            'name' => $plugin['Name'], 'version' => $plugin['Version'], 'description' => $plugin['Description'],
            'author' => $plugin['Author'], 'author_uri' => $plugin['AuthorURI'], 'state' => (int) is_plugin_active($plugin_path[$i])
        );
        $i++;
    }

    return $plugins;
}

add_action('rest_api_init', function () {
    register_rest_route('website-info', '/index', array(
        'methods' => 'POST',
        'callback' => 'website_info_index'
    ));

    register_rest_route('website-info', '/get', array(
        'methods' => 'POST',
        'callback' => 'website_info_get'
    ));
});

function website_info_index() {
    if ($_POST['api'] == get_option('WEBSITE_INFO_API')) wp_send_json_success(array(), 200);
    else wp_send_json_error(array(), 400);
}

function website_info_get() {
    if ($_POST['api'] != get_option('WEBSITE_INFO_API')) wp_send_json_error(array(), 400);
    $field = $_POST['field'];

    if ($field == 'title') wp_send_json_success(array('title' => get_bloginfo('name')), 200);
    else if ($field == 'description') wp_send_json_success(array('description' => get_bloginfo('description')), 200);
    else if ($field == 'email') wp_send_json_success(array('email' => get_bloginfo('admin_email')), 200);
    else if ($field == 'icon') wp_send_json_success(array('icon' => get_site_icon_url()), 200);
    else if ($field == 'wp_version') wp_send_json_success(array('wp_version' => get_bloginfo('version')), 200);
    else if ($field == 'php_version') wp_send_json_success(array('php_version' => PHP_VERSION), 200);
    else if ($field == 'themes') wp_send_json_success(array('themes' => website_info_themes()), 200);
    else if ($field == 'plugins') wp_send_json_success(array('plugins' => website_info_plugins()), 200);

    else {
        wp_send_json_success(array(
            'title' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'email' => get_bloginfo('admin_email'),
            'icon' => get_site_icon_url(),
            'wp_version' => get_bloginfo('version'),
            'php_version' => PHP_VERSION,
            'themes' => website_info_themes(),
            'plugins' => website_info_plugins()
        ), 200);
    }
}

?>