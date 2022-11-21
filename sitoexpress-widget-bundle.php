<?php

/*

Plugin Name: SE SiteOrigin Widgets Bundle
Description: Adds some Custom Widgets to SOPB
Author: Sito.Express
Author URI: https://sito.express
Version: 1.0
License: GPL v3
Text Domain: se-sopb-widget

*/

define('SPB_VER', 'v.1.0');
define('SPB_DIR', plugin_dir_path(__FILE__));
define('SPB_URL', plugin_dir_url(__FILE__));

/*
* Extending Siteorigin Widget Bundle
*/

function bp_sopb_widget_bundle($folders){
    $folders[] = SPB_DIR.'sopb-widgets/'; // important: Slash on end string is required
    return $folders;
}
add_filter('siteorigin_widgets_widget_folders', 'bp_sopb_widget_bundle');
