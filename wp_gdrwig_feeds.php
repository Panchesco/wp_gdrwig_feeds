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



/** Set Defaults **/
//add_option( 'gdrwig_settings', array('client_id'=>'','client_secret'=>'','count'=>0));


	class GdrwigFeeds {
		
		
		function __construct()
		{
			/** Set Defaults **/
			add_option( 'gdrwig_settings', 
				array(	'client_id'=>'',
						'client_secret'=>'',
						'count'=>0));
						
						
			$options = extract(get_option('gdrwig_settings'));
			
			print_r('<pre>');
			print_r($options['client_id']);
			print_r('</pre>');
			
			
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
		
		
		/** Client Secret Input **/
		function client_secret_input() {
		 
		 	$option = get_option('gdrwig_settings');
		    echo( '<input type="text" name="gdrwig_settings[client_secret]" id="gdrwig_settings[client_secret]" value="'. $option['client_secret'] .'" />' );
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
		
		    <?php
		
		    
		}
		
		
		
			function myplugin_settings_section_1_callback() 
			{
			
			   echo( 'Some info about this section.' );
			}
			
 
		
		
		
		
		
	}



add_action( 'admin_menu', array('GdrwigFeeds','settingsMenu' ));
add_action( 'admin_init', array('GdrwigFeeds','Init'));


