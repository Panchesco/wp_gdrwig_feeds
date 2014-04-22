<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
} 

class TagFeedWidget extends WP_Widget
{


	function __construct()
	{
		$widget_options = array(
								'classname'	=> 'gdrwig-tag-feed-widget',
								'description' => 'Display items from Instagram\'s tag feed. API'
		);
		
		parent::WP_Widget('gdrwid_tag_feed_widget','Tag Feed Widget',$widget_options);
		
		// Register the stylesheet.
		wp_register_style( 'gdrwigStylesheet', plugins_url('wp_gdrwig_feeds/assets/css/gdrwig-admin.css') );
	}
	
	
	
	function widget($args,$instance)
	{
	

		$opts = get_option('gdrwig_settings');

		
		extract( $opts, EXTR_SKIP );

		switch ($feed)
		{
		
		
			case 'user':
				// Create TagFeed instance.
				
				new UsersFeed(array('count'=>$opts['count']));
				
				$api = UsersFeed::mediaRecentClientId($client_id,$user['id']);
				
				$data = $api->data;
				
				
			break;
		
			default:
				// Create TagFeed instance.
				$api = new TagFeed($client_id,$hashtag,$count);
				$response = $api->response();
				$data = $response->data;
			break;
		
		
		}
		
		?>

		<div class="tag-feed"><?php echo ResponseHtml::thumbs($data,'standard_resolution'); ?></div>

		<?php
		
	}
	
	
	function form( $instance )
	{
		$opts = get_option('gdrwig_settings');
		extract( $opts, EXTR_SKIP );
		
		/* Some logic here for pulling the correct feed */
		
		print_r('<pre>');
		print_r($opts);
		print_r('</pre>');;
		?>
		<h4>Current Feed Result</h4>
		<p>Update this configuration in <a href="<?php ;?>/wp-admin/options-general.php?page=gdrwig_settings">settings</a></p>
		<div class="appearance widgets tag-feed clearfix">
		<?php
		
		switch ($feed)
		{
		
		
			case 'user':
				// Create TagFeed instance.
				
				new UsersFeed(array('count'=>$opts['count']));
				
				$api = UsersFeed::mediaRecentClientId($client_id,$user['id']);
				
				
				echo ResponseHtml::thumbs($api->data,'standard_resolution');
				
			break;
		
			default:
				// Create TagFeed instance.
				$api = new TagFeed($client_id,$hashtag,$count);
				$response = $api->response();
				echo ResponseHtml::thumbs($response->data,'standard_resolution');
			break;
		
		
		}

		
		?>
		</div>
		<?php
	}


	public static function register()
	{
		register_widget('TagFeedWidget');
		
	}
	
	
		public static function adminStyles() 
		{
       /*
        * It will be called only on your plugin admin page, enqueue our stylesheet here
        */
			wp_enqueue_style( 'gdrwigStylesheet' );
	   	}

}


add_action('widgets_init',array('TagFeedWidget','register'));
//add_action( 'admin_init', array('TagFeedWidget','adminStyles'));
