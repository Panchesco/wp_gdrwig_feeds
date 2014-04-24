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
	require_once(__DIR__ . '/apis/instagram/usersfeed.php');
	require_once(__DIR__ . '/apis/instagram/clientinfo.php');
	require_once(__DIR__ . '/classes/tagfeedwidget.php');


	class GdrwigFeeds {
		
		
		function __construct()
		{
			/** Set Defaults **/
			add_option( 'gdrwig_settings', 
				array(	'ig_username'=>'',
						'client_id'=>'',
						'client_secret'=>'',
						'redirect_uri'=>'http://localhost:8888/wp-admin/options-general.php?page=gdrwig_settings',
						'feed'=>'hashtag',
						'user_to_show'=>'',
						'user'=>array('id'=>'','username'=>'','full_name'=>'','profile_picture'),
						'hashtag'=>'modigliani',
						'resolution'=>'thumbnail',
						'access_token'=>'',
						'count'=>0));

		}
		
		
		
		/** Settings Initialization **/
		public static function Init() 
		{
		
		
			// Check URL for access Token
			if( isset($_GET['code']))
			{
				GdrwigFeeds::accessTokenCurl($_GET['code']);
			}
			
			
			// Get the current options.
			$opts = get_option('gdrwig_settings');

		     /** Setting section 1. **/
		    add_settings_section(
		       'gdrwig_settings_section_1',
		       '1. Instagram Client Info',
		       '',
		       'gdrwig_settings'
		    );
		    
		     
		    
		    
		    
		    // IG Username.
		    add_settings_field(
				'ig_username',
		       'Instagram Username',
		       'GdrwigFeeds::ig_username_input',
		       'gdrwig_settings',
		       'gdrwig_settings_section_1'
		    );
		     
		    // Client ID.
		    add_settings_field(
			
		    'client_id',
		       'Client ID',
		       'GdrwigFeeds::client_id_input',
		       'gdrwig_settings',
		       'gdrwig_settings_section_1'
		    );
		    
		    // Client Secret.
		    add_settings_field(

		       'client_secret',
		       'Client Secret',
		       'GdrwigFeeds::client_secret_input',
		       'gdrwig_settings',
			   'gdrwig_settings_section_1'
		    );
		    
		    // Client Secret.
		    add_settings_field(

		       'redirect_uri',
		       'Redirect URI',
		       'GdrwigFeeds::redirect_uri_input',
		       'gdrwig_settings',
			   'gdrwig_settings_section_1'
		    );
		    
		    
		    // We only want this next section if the user has registered client with Instagram.
		    if(self::clientInfoValid($opts))
		    {
		    
			    /** Setting section 2. **/
			    add_settings_section(
			       'gdrwig_settings_section_2',
			       '2. Instagram Feed Settings',
			       '',
			       'gdrwig_settings'
			    );
			    
			    
			    // Feed
			    add_settings_field(
	
			    'feed',
			       'Which Feed?',
			       'GdrwigFeeds::feed_dropdown',
			       'gdrwig_settings',
				   'gdrwig_settings_section_2'
			    );
			    
			    // Ig user to show
			    add_settings_field(
	
			    'ig_user_to_show',
			       '<span id="user-select">Which User?</span>',
			       'GdrwigFeeds::ig_user_to_show_input',
			       'gdrwig_settings',
			       'gdrwig_settings_section_2'
			    );
			    
			    
			    // Hashtag.
			    add_settings_field(
	
			    'hashtag',
			       'Which Hashtag?',
			       'GdrwigFeeds::hashtag_input',
			       'gdrwig_settings',
				   'gdrwig_settings_section_2'
			    );
			    
			    // Image resolution.
			    add_settings_field(
	
			       'resolution',
			       'Resolution',
			       'GdrwigFeeds::resolution_dropdown',
			       'gdrwig_settings',
			       'gdrwig_settings_section_2'
			    );
			    
			    
			    // Count.
			    add_settings_field(
			       'count',
			       'Count (20 Max)',
			       'GdrwigFeeds::count_input',
			       'gdrwig_settings',
				   'gdrwig_settings_section_2'
			    );
		    
		    }
		    

		 
		    // Register the fields field with our settings group.
		    register_setting( 'gdrwig_settings_group', 'gdrwig_settings');
		    
		    // Register the stylesheet.
		    wp_register_style( 'gdrwigStylesheet', plugins_url('assets/css/gdrwig-admin.css', __FILE__) );
		    
		    // Register the script.
		    wp_register_script( 'gdrwigAdminJs', plugins_url('assets/js/gdrwig-admin.js', __FILE__) );
		    
		}
		
		
		/**
		 * If code for getting access token exists, call for it.
		 * @param $code string
		 */
		 public static function accessTokenCurl($code)
		 {
			 
			 $opts = get_option('gdrwig_settings');
			 
			 
			 
			 
			 $url	= 'https://api.instagram.com/oauth/access_token';
			 
			 $fields['client_id']			= urlencode($opts['client_id']);
			 $fields['client_secret']		= urlencode($opts['client_secret']);
			 $fields['grant_type']			= 'authorization_code';
			 $fields['redirect_uri']		= urlencode($opts['redirect_uri']);
			 $fields['code']				= urlencode($code);
			 
			 
			 $response = json_decode(CurlHelper::postCurl($url,$fields));
			 
			 if(isset($response->access_token))
			 {
				 $opts['access_token']		= $response->access_token;
				 $opts['user']['id']		= $response->user->id;
				 $opts['user']['username']	= $response->user->username;
				 $opts['user']['full_name']	= $response->user->full_name;
				 $opts['user']['profile_picture']	= $response->user->profile_picture;

				 update_option('gdrwig_settings',$opts);
				 wp_redirect($opts['redirect_uri']); exit;
			 	
			 	} else {
				 
			 }

		 }
		 


		/** Add Settings Page **/
		public static function settingsMenu() {
			
			   		add_options_page(

			   		   'GDRWIG Settings',
			   		   'GDRWIG',
			   		   'manage_options',
			   		   'gdrwig_settings',
			   		   'GdrwigFeeds::settingsPage'
			   		);
 
			}
			
			
		/** IG Username Input **/
		function ig_username_input() {
		
			$option = get_option('gdrwig_settings');
		 
		    echo( '<input type="text" name="gdrwig_settings[ig_username]" id="gdrwig_settings[ig_username]" value="' . $option['ig_username']  .'" />' );
		}
			
		/** Client ID Input **/
		function client_id_input() {
		
			$option = get_option('gdrwig_settings');
		 
		    echo( '<input type="text" name="gdrwig_settings[client_id]" id="gdrwig_settings[client_id]" value="' . $option['client_id']  .'" />' );
		}
		
		/** Redirect URL **/
		function redirect_uri_input() {
		
			$option = get_option('gdrwig_settings');
		 
		    echo( '<input  type="text" name="gdrwig_settings[redirect_uri]" id="gdrwig_settings[redirect_uri]" value="' . $option['redirect_uri']  .'" />' );
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
		
		/** Feed **/
		function feed_dropdown() {
		 
		 	$html		= '<select name="gdrwig_settings[feed]" id="gdrwig_settings[feed]">
		 	';
		    $opts		= get_option('gdrwig_settings');
		    

		    $options	= array('user'=>'User','hashtag'=>'Hashtag');

		    foreach($options as $key=>$row)
		    {
			   	$selected = ($opts['feed']==$key) ? ' selected':''; 
			   	$html.=		'	<option value="' . $key . '"' . $selected . '>' . $row . '</option>
			   	';
		    }
		    
		    $html.= '</select>
		    ';
		    
		    echo $html;
		    
		}
		
		
		/** IG User to show Input **/
		function ig_user_to_show_input() {
		
			$option = get_option('gdrwig_settings');


		   ?>
		   
		   <h4>Currently Selected User</h4>
		   
		   <ul id="users-api-current">
		   	<li><img id="users-api-current-img"  src="<?php echo $option['user']['profile_picture'];?>"></li>
		   	<li><a target="_blank" id="users-api-current-profile" href="http://instagram.com/<?php echo $option['user']['username'] ;?>"><?php echo $option['user']['username'] ;?></a></li>
		   </ul>
		   
		   <h4>User Search:
		   
		   <input data-search_value="q" type="text" placeholder="Enter Username" name="gdrwig_settings[ig_user_to_show]" id="gdrwig_settings[ig_user_to_show]" value="" /></h4>
		   <div id="user-search-results"></div>

		   <?php

		}
		
		
		/** Count **/
		function count_input() {
		 
		    $option = get_option('gdrwig_settings');
		    echo( '<input type="text" name="gdrwig_settings[count]" id="gdrwig_settings[count]" value="'. $option['count']. '" />' );
		}
		
		
		
		
		/** Settings Page Content **/
		function settingsPage() {
		
			$opts = get_option('gdrwig_settings');
			

		    ?>
		     
		    <div class="wrap">
				 	
				     <h2>GDRWIG</h2>
				     <p>Some text describing what the plugin settings do.</p>
				      
				     <form method="post" action="options.php">

				     
				     <input type="hidden" name="gdrwig_settings[access_token]" id="gdrwig_settings[access_token]" value="<?php echo $opts['access_token'];?>">
				     <input type="hidden" name="gdrwig_settings[user][id]" id="gdrwig_settings[user][id]" value="<?php echo $opts['user']['id'];?>">
				     <input type="hidden" name="gdrwig_settings[user][username]" id="gdrwig_settings[user][username]" value="<?php echo $opts['user']['username'];?>">
				     <input type="hidden" name="gdrwig_settings[user][full_name]" id="gdrwig_settings[user][full_name]" value="<?php echo $opts['user']['full_name'];?>">
				     <input type="hidden" name="gdrwig_settings[user][profile_picture]" id="gdrwig_settings[user][profile_picture]" value="<?php echo $opts['user']['profile_picture'];?>">

				 
				      <?php
				       
				      // Output the settings sections.
				      do_settings_sections( 'gdrwig_settings' );
				 
				      // Output the hidden fields, nonce, etc.
				      settings_fields( 'gdrwig_settings_group' );


				     ?>
				     

				     <input type="hidden" name="gdrwig_settings[access_token]" id="gdrwig_settings[access_token]" value="<?php echo $opts['access_token'];?>">
				     <input type="hidden" name="gdrwig_settings[user][id]" id="gdrwig_settings[user][id]" value="<?php echo $opts['user']['id'];?>">
				     <input type="hidden" name="gdrwig_settings[user][username]" id="gdrwig_settings[user][username]" value="<?php echo $opts['user']['username'];?>">
				     <input type="hidden" name="gdrwig_settings[user][full_name]" id="gdrwig_settings[user][full_name]" value="<?php echo $opts['user']['full_name'];?>">
				     <input type="hidden" name="gdrwig_settings[user][profile_picture]" id="gdrwig_settings[user][profile_picture]" value="<?php echo $opts['user']['profile_picture'];?>">
				     

				     <?php 
				     
				 
				      // Submit button.
				      submit_button();
				      
				      
				      if(ClientInfo::validClientId($opts['client_id']))
				      {
				       
				      ?>
				      
				      <h3>3. Instagram Authentication</h3>
				      
				      <?php if(false === UsersFeed::accessTokenValid($opts['access_token'])) {?>
				     <div id="ig-auth">
				     <p>It looks like you need to re/authorize this application.<br /> 
				     <a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo $opts['client_id'] ;?>&redirect_uri=<?php echo $opts['redirect_uri'] ;?>&response_type=code">Authorize on Instagram</a></p></div>

				     <?php } else { ?>
				     
				     <p>Your authorization is current.</p>
				     
				     <?php } ?>
				     
				     
				    <div class="thumbs-wrapper">
				    
				    </div><!-- /.thumbs-wrapper -->
				 
				     </form>
				     

			</div><!-- /.wrap -->
		    <?php
		    
		    		} else {
			    	
			 ?>	
			    		
			    	<p>It looks like you need to update your <a target="_blank" href="http://instagram.com/developer/clients/manage/">Instagram client info</a>.</p>
			    	<ul>
			    		<li>Website url: <?php echo '';?></li>
			    		<li>Redirect uri: <?php echo '';?></li>
			    	</ul>
			    		
		    <?php
		    		}

		}
		
		
		public static function tagThumbs()
		{

					$opts = get_option('gdrwig_settings');
			
					$feed = new TagFeed($opts['client_id'],$opts['hashtag'],$opts['count']);
					
				return ResponseHtml::thumbs($feed->response()->data,$opts['resolution']);
			
		}
		
		
		public static function thumbs($response,$resolution)
		{
			
			return ResponseHtml::thumbs($response->data,$resolution);
			
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
	   	
	   	
	   	public static function adminJs() 
		{
        
        /*
        * It will be called only on your plugin admin page, enqueue our script here
        */
			wp_enqueue_script( 'gdrwigAdminJs');
	   	}
	   	

	   	public static function searchUsers()
	   	{
		   	$opts = get_option('gdrwig_settings'); 
		   	
		   	$q = addslashes($_POST['q']);
		   	
		   	new UsersFeed(array('count'=>'15'));
		   	$api = UsersFeed::search($opts['access_token'],$q);
		   	
		   	
		   	if(isset($api->meta->code) && $api->meta->code==200)
		   	{
		   	
		   		
		   		
		   		$html = '	<ul>
		   						<li>
		   				';
		   				
			   	
				foreach($api->data as $key=>$row)
				{
					
					$selected = ($row->id==$opts[user][id]) ? " checked" : "";
				
					$html.= '		<ul>
										<li><a target="_blank" href="http://instagram.com/' . $row->username . '"><img src="' . $row->profile_picture . '" /></a></li>
										<li><input class="user-data" type="radio" value="' . $row->id . '" name="gdrwig_settings[user][id]" id="gdrwig_settings[user][id]"' . $selected . ' data-id="' . $row->id . '" data-username="' . $row->username . '" data-full_name="' . $row->full_name . '" data-profile_picture="' . $row->profile_picture . '"> <a target="_blank" href="http://instagram.com/' . $row->username . '">' . $row->username . '</a>
										</li>
									</ul>
							';
					
				}
				$html.= '		</li>
							</ul>
				';
				
				$html.= '
				<script>
				
					(function($){
					
						updateUsersApiData();
					
					})(jQuery);
				
				</script>
				';

		   	
		   	} elseif(isset($api->meta->error_message)) {
			   	
			   	$html = '<p><br>' . $api->meta->error_message . '</p>';
		   	}
		   	
		   	
		   	if(isset($api->data) && empty($api->data))
		   	{
			   	$html = '<p><br>No results found for <em>' . $q . '</em></p>
			   	';
		   	}
		   	
		   	echo $html;
		   	
		   	die();
		   	
	   	}
	   	
	   	
	   	
	   	
	   	public static function updateUsersApiData()
	   	{
		   	
		   // Get current options.
		   $opts = get_option('gdrwig_settings'); 
		   
		   // Add the data about the selected user to the mix.
		   $opts['user']['id']				= $_POST['id'];
		   $opts['user']['username']		= $_POST['username'];
		   $opts['user']['full_name']		= $_POST['full_name'];
		   $opts['user']['profile_picture']	= $_POST['profile_picture'];
		   
		   // Update.
		   update_option('gdrwig_settings',$opts);

		   	die();
	   	}
	   	
	   	
	   	/**
	   	 * Check that current client info is valid.
	   	 * @param $client_info = array
	   	 * @return boolean
	   	 */
	   	 public static function clientInfoValid($client_info)
	   	 {
		   
		   
		   	$keys = array('client_id','client_secret','redirect_uri');
		   	
		   	foreach($keys as $key)
		   	{

			   	// Let's check the passed client_info param against the items we need for IG.
			   	if( ! in_array($key,array_keys($client_info)))
			   	{
				   	return false;
			   	}
			   	
			   	// Make sure the setting isn't empty.
			   	if( null == $client_info[$key] )
			   	{
				   	return false;
			   	}

		   	}
		   	 
		   	 
		   	 // Call Instagram and check that the client_id is correct.
		   	 return ClientInfo::validClientId($client_info['client_id']);

	   	 }
		
	}

// Add register iframe
add_action( 'admin_menu', array('GdrwigFeeds','settingsMenu' ));
add_action( 'admin_init', array('GdrwigFeeds','Init'));
add_action('wp_ajax_gdrwig_search_users', array('GdrwigFeeds','searchUsers'));
add_action('wp_ajax_gdrwig_update_users_api_data', array('GdrwigFeeds','updateUsersApiData'));
add_action( 'admin_init', array('GdrwigFeeds','adminStyles'));
add_action( 'admin_init', array('GdrwigFeeds','adminJs'));
add_filter('plugin_action_links', 'GdrwigFeeds::actionLinks', 10, 2);


