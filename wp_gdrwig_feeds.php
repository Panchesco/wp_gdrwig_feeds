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


// Include required files we'll be using.
require_once( plugin_dir_path( __FILE__ ) . 'helpers/curlhelper.php');
require_once( plugin_dir_path( __FILE__ ) . 'apis/instagram/responsehtml.php');
require_once( plugin_dir_path( __FILE__ ) . 'apis/instagram/tagfeed.php');
require_once( plugin_dir_path( __FILE__ ) . 'classes/tagfeedwidget.php');




	class GdrwigFeeds {
	
		
			public static function  Init()
			{
			
				register_setting(
					'GdrwigFeeds_Vars_Group',
					'GdrwigFeeds_Vars',
					array('GdrwigFeeds','Validate')
					);
					
					
				add_settings_section(
					'GdrwigFeeds_Vars_ID',
					'GdrwigFeeds Vars Title',
					array('Gdrwig Feeds','Overview'),
					'Gdrwig_Page_Title'
				);
				
				
				
					
			}
			
			
			
			public static function Admin_Menus()
			{
			    
			    if (!function_exists('current_user_can')
			        ||
			        !current_user_can('manage_options'))
			            return;
			
			    if (function_exists('add_options_page'))
			        add_options_page(
			            'GdrwigFeeds',
			            'GDRWIG Feeds',
			            'manage_options',
			            'gdrwig_feeds_api',
			            array('GdrwigFeeds', 'OptionsPage'));
			}
			
			

			
			public static function Overview()
			{
				?>This is a demo of the Settings API.<?php
			}
			
			
			
			public static function OptionsPage()
			{
			
                        $Demo_Vars = get_option('Demo_Vars');
                        
                        
                        
                        ?>
                                <div class="wrap">
                                        <?php screen_icon("options-general"); ?>
                                        <h2>GDRWIG Feeds Settings <?php echo $Demo_Vars['Version']; ?></h2>
                                        <p>Some instructions...</p>
                                        <form action="options.php" method="post">
                                                <?php settings_fields('Demo_Vars_Group'); ?>
                                                <?php do_settings_sections('Demo_Page_Title'); ?>
                                                <p class="submit">
                                                        <input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save 	Changes'); ?>" />
                                                </p>
                                        </form>
                                </div>
                        <?php
                }

	}
	
	

add_action('admin_init',
			array('GdrwigFeeds','Init'));
			
add_action('admin_menu',
    array('GdrwigFeeds', 'Admin_Menus'));
