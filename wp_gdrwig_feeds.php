<?php
/**
 * Plugin Name: GDRWIG Feeds Widget
 * Plugin URI:
 * Description: Display Instagram Items as widgets.
 * Author: Richard Whitmer
 * Version: 1
 * Author URI:


	Copyright 2014  Richard Whitmer  (email : panchesco@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA


*/


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


	// Include CurlHelper and TagFeed classes for pulling in tagged items from IG.
	require_once(__DIR__ . '/helpers/curlhelper.php');
	require_once(__DIR__ . '/apis/instagram/responsehtml.php');
	require_once(__DIR__ . '/apis/instagram/tagfeed.php');
	require_once(__DIR__ . '/classes/tagfeedwidget.php');





	class GdrwigFeeds {
		
		
		function __construct()
		{
			/** Set Defaults **/
			add_option( 'gdrwig_settings', 
				array(	'client_id'=>'',
						'client_secret'=>'',
						'hashtag'=>'modigliani',
						'resolution'=>'thumbnail',
						'count'=>0));

		}
		
		
		
		/** Settings Initialization **/
		public static function Init() 
		{
		 
		     /** Setting section 1. **/
		    add_settings_section(
		    /*1*/   'gdrwig_settings_section_1',
		    /*2*/   'Instagram Client App Settings',
		    /*3*/   'gdrwig_settings_section_1_callback',
		    /*4*/   'gdrwig_settings'
		    );
		     
		    // Client ID.
		    add_settings_field(
		    /*1*/   'client_id',
		    /*2*/   'Client ID',
		    /*3*/   'GdrwigFeeds::client_id_input',
		    /*4*/   'gdrwig_settings',
		    /*5*/   'gdrwig_settings_section_1'
		    );
		    
		    // Client Secret.
		    add_settings_field(
		    /*1*/   'client_secret',
		    /*2*/   'Client Secret',
		    /*3*/   'GdrwigFeeds::client_secret_input',
		    /*4*/   'gdrwig_settings',
		    /*5*/   'gdrwig_settings_section_1'
		    );
		    
		    
		    // Hashtag.
		    add_settings_field(
		    /*1*/   'hashtag',
		    /*2*/   'Tag',
		    /*3*/   'GdrwigFeeds::hashtag_input',
		    /*4*/   'gdrwig_settings',
		    /*5*/   'gdrwig_settings_section_1'
		    );
		    
		    // Image resolution.
		    add_settings_field(
		    /*1*/   'resolution',
		    /*2*/   'Resolution',
		    /*3*/   'GdrwigFeeds::resolution_dropdown',
		    /*4*/   'gdrwig_settings',
		    /*5*/   'gdrwig_settings_section_1'
		    );
		    
		    
		    // Count.
		    add_settings_field(
		    /*1*/   'count',
		    /*2*/   'Count (20 Max)',
		    /*3*/   'GdrwigFeeds::count_input',
		    /*4*/   'gdrwig_settings',
		    /*5*/   'gdrwig_settings_section_1'
		    );
		    
		 
		    // Register the fields field with our settings group.
		    register_setting( 'gdrwig_settings_group', 'gdrwig_settings');
		    
		    // Register the stylesheet.
		    wp_register_style( 'gdrwigStylesheet', plugins_url('assets/css/gdrwig-admin.css', __FILE__) );
		    
		}


		/** Add Settings Page **/
		public static function settingsMenu() {
			
			   		add_options_page(
			   		/*1*/   'GDRWIG Settings',
			   		/*2*/   'GDRWIG',
			   		/*3*/   'manage_options',
			   		/*4*/   'gdrwig_settings',
			   		/*5*/   'GdrwigFeeds::settingsPage'
			   		);
 
			}
			
			
		/** Client ID Input **/
		function client_id_input() {
		
			$option = get_option('gdrwig_settings');
		 
		    echo( '<input type="text" name="gdrwig_settings[client_id]" id="gdrwig_settings[client_id]" value="' . $option['client_id']  .'" />' );
		}
		
		/** Hashtag Input **/
		function hashtag_input() {
		 
		 	$option = get_option('gdrwig_settings');
		    echo( '<input type="text" name="gdrwig_settings[hashtag]" id="gdrwig_settings[hashtag]" value="'. $option['hashtag'] .'" />' );
		}
		
		
		/** Client Secret Input **/
		function client_secret_input() {
		 
		 	$option = get_option('gdrwig_settings');
		    echo( '<input type="text" name="gdrwig_settings[client_secret]" id="gdrwig_settings[client_secret]" value="'. $option['client_secret'] .'" />' );
		}
		
		
		/** Resolution **/
		function resolution_dropdown() {
		 
		 	$html		= '<select name="gdrwig_settings[resolution]" id="gdrwig_settings[resolution]">
		 	';
		    $opts		= get_option('gdrwig_settings');
		    
		    $options	= array('thumbnail'=>'Thumbnail','low_resolution'=>'Low Resolution','standard_resolution'=>'Standard Resolution');
		    foreach($options as $key=>$row)
		    {
			   	$selected = ($opts['resolution']==$key) ? ' selected':''; 
			   	$html.=		'	<option value="' . $key . '"' . $selected . '>' . $row . '</option>
			   	';
		    }
		    
		    $html.= '</select>
		    ';
		    
		    echo $html;
		    
		}
		
		
		/** Count **/
		function count_input() {
		 
		    $option = get_option('gdrwig_settings');
		    echo( '<input type="text" name="gdrwig_settings[count]" id="gdrwig_settings[count]" value="'. $option['count']. '" />' );
		}
		
		
		/** Settings Page Content **/
		function settingsPage() {
		 
		    ?>
		     
		    <div class="wrap">
		
		 
		     <h2>GDRWIG</h2>
		     <p>Some text describing what the plugin settings do.</p>
		      
		     <form method="post" action="options.php">
		 
		      <?php
		       
		      // Output the settings sections.
		      do_settings_sections( 'gdrwig_settings' );
		 
		      // Output the hidden fields, nonce, etc.
		      settings_fields( 'gdrwig_settings_group' );
		 
		      // Submit button.
		      submit_button();
		       
		      ?>
		 
		     </form>
		    </div>
		    
		    <style>

		    </style>
		
			<div class="thumbs-wrapper">
		    <?php
		    
		    echo GdrwigFeeds::tagThumbs();
		    
		    ?>
			</div>
		    <?php

		}
		
		
		public static function tagThumbs()
		{
			
					
					$opts = get_option('gdrwig_settings');
			
					$feed = new TagFeed($opts['client_id'],$opts['hashtag'],$opts['count']);
					
					
					return ResponseHtml::thumbs($feed->response()->data,$opts['resolution']);
			
		}
		
		
		/**
		 * Add settings link on activation page.
		 * VIA http://www.wphub.com/adding-plugin-action-links/
		 */
		public static function actionLinks($links, $file) {
		    static $this_plugin;
		    
		    if (!$this_plugin) {
		        $this_plugin = plugin_basename(__FILE__);
		    }
		 
		    // check to make sure we are on the correct plugin
		    if ($file == $this_plugin) {
		        // the anchor tag and href to the URL we want. For a "Settings" link, this needs to be the url of your settings page
		        $settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=gdrwig_settings">Settings</a>';
		        // add the link to the list
		        array_unshift($links, $settings_link);
		    }
		 
		    return $links;
		}

		
		public static function adminStyles() 
		{
       /*
        * It will be called only on your plugin admin page, enqueue our stylesheet here
        */
			wp_enqueue_style( 'gdrwigStylesheet' );
	   	}
		


			
		
	}



add_action( 'admin_menu', array('GdrwigFeeds','settingsMenu' ));
add_action( 'admin_init', array('GdrwigFeeds','Init'));
add_action( 'admin_init', array('GdrwigFeeds','adminStyles'));
add_filter('plugin_action_links', 'GdrwigFeeds::actionLinks', 10, 2);


