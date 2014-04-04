<?php
/*
Plugin Name: GDRWIG Feeds Widget
Plugin URI:
Description: Display Instagram Items as widgets.
Author: Richard Whitmer
Version: 1
Author URI:
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


// Include CurlHelper and TagFeed classes for pulling in tagged items from IG.
require_once( plugin_dir_path( __FILE__ ) . 'helpers/curlhelper.php');
require_once( plugin_dir_path( __FILE__ ) . 'apis/instagram/responsehtml.php');
require_once( plugin_dir_path( __FILE__ ) . 'apis/instagram/tagfeed.php');
require_once( plugin_dir_path( __FILE__ ) . 'classes/tagfeedwidget.php');





function gdrwig_tag_feed_widget_init()
{
	register_widget('GdrwigTagFeedWidget');
}


add_action('widgets_init','gdrwig_tag_feed_widget_init');