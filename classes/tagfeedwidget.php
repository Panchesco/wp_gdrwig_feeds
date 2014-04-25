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
				$config = array(
								'client_id' => $client_id,
								'access_token' => $access_token,
								'id' => $user['id'],
								'count' => $count
								);
				
				new UsersFeed($config);
				
				$response= UsersFeed::mediaRecentClientId();
				
			break;
		
			default:
				
				// Create TagFeed instance.
				$config = array(
								'client_id' => $client_id,
								'hashtag'	=> $hashtag,
								'count'		=> $count
								);
								
				$api = new TagFeed($config);
				$response = $api::response();

			break;
		
		
		}
		
		?>

		<div class="tag-feed"><?php echo ResponseHtml::thumbs($response,'standard_resolution'); ?></div>

		<?php
		
	}
	
	
	function form( $instance )
	{
		$opts = get_option('gdrwig_settings');
		$feeds = array('hashtag'=>'Hashtag','user'=>'User','popular'=>'Popular');
		extract( $opts, EXTR_SKIP );
		
		/* Some logic here for pulling the correct feed */
		
		
		?>
		<h4>Current Feed Result</h4>
		
		<p>Selected Feed: <?php echo $feeds[$opts[feed]];?></p>
		
		<?php 
		
		if($opts[feed]=='user')
		{ ?>
			
		<div class="thumb">
			<p><img src="<?php echo $opts[user][profile_picture] ;?>"></p>
			<p>Selected User: <a target="_blank" href="http://instagram.com/<?php echo $opts[user][username] ;?>"><?php echo $opts[user][username] ;?></a></p>
		</div>
			
		<?php 
		
		}
		
		
		?>
		
		<p>Update this configuration in <a href="<?php ;?>/wp-admin/options-general.php?page=gdrwig_settings">settings</a></p>
		<div class="appearance widgets tag-feed clearfix">
		<?php
		
		switch ($feed)
		{
		
		
			case 'user':
				// Create TagFeed instance.
				
				$config['client_id']	= $client_id;
				$config['id']			= $user['id'];
				$config['count']		= $count;
				$api = new UsersFeed($config);
				
				echo ResponseHtml::thumbs($api::mediaRecentClientId($client_id,$user['id']));
				
			break;
		
			default:
				// Create TagFeed instance.
				$config['client_id']	= $client_id;
				$config['count']		= $count;
				$config['hashtag']	= $hashtag;
				$api = new TagFeed($config);
				
				echo ResponseHtml::thumbs($api::response());
				
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
