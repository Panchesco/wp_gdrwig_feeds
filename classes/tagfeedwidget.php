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
	}
	
	
	
	function widget($args,$instance)
	{
		
		extract( $args, EXTR_SKIP );
		$client_id	= ( $instance['client_id'] ) ? $instance['client_id'] : '';
		$hashtag	= ( $instance['hashtag'] ) ? $instance['hashtag'] : '';
		$response_count		= ( $instance['response_count'] ) ? $instance['response_count'] : 0;
		

		
		// Create TagFeed instance.
		$feed = new TagFeed($instance['client_id'],$instance['hashtag'],$instance['response_count']);
		
		$response = $feed->response();
		
		?>

		<div class="tag-feed"><?php echo ResponseHtml::thumbs($response->data,'standard_resolution'); ?></div>
		<?php echo ResponseHtml::paginationButton($response); ?>

		<?php
		
	}
		
	
	function form( $instance )
	{
		?>
		<p>Enter your Instagram provided <a target="_blank" href="http://instagram.com/developer/clients/manage/">Client ID</a>, a tag, and the number of images to return with each request.</p>
		
		<p>
		<label for="<?php echo $this->get_field_id('client_id');?>">
		Client ID:<br>
		<input id="<?php echo $this->get_field_id('client_id');?>" name="<?php echo $this->get_field_name('client_id');?>" value="<?php echo esc_attr($instance['client_id']);?>" />
		</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('hashtag');?>">
		Hashtag:<br>
		<input id="<?php echo $this->get_field_id('hashtag');?>" name="<?php echo $this->get_field_name('hashtag');?>" value="<?php echo esc_attr($instance['hashtag']);?>" />
		</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id('count');?>">
		Count (30 max):<br>
		<input id="<?php echo $this->get_field_id('response_count');?>" name="<?php echo $this->get_field_name('response_count');?>" value="<?php echo esc_attr($instance['response_count']);?>" />
		</label>
		</p>
		<?php
	}

}