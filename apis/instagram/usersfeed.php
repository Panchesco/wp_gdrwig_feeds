<?php
/**
 * Users
 *
 * Class for retrieving a user feeds from Instagram's API
 *
 * @package		InstaApi
 * @author		Richard Whitmer
 */



			class UsersFeed {
			
			public $client_id;
			public $user_id;
			public $access_token;
			public $q;
			public static $count = 1;
			public $min_id;
			public $max_id;
			public $min_timestamp;
			public $max_timestamp;
			public $endpoint;
			
			function __construct($config=array())
			{
				

			}
			
			
			/**
			 * Check if current access_token is valid.
			 * @return boolean
			 */
			 public static function accessTokenValid($access_token)
			 {

				 $endpoint = "https://api.instagram.com/v1/users/search?q=jacks&access_token=" . $access_token . "&count=1";
				 
				 $response = json_decode(CurlHelper::getCurl($endpoint));
				 
				 if(isset($response->meta->code))
				 {
					 
					 if($response->meta->code==200)
					 {
						 return true;
						 
					 }
				 }
				 
				 return false;
			 }
			 
			 
			 /**
			  * Return Self/Feed response (this is the feed of images a user is subscribed to.
			  * @param $access_token string
			  * @return object
			  */
			  public static function selfFeed($access_token)
			  {
				  
				  $endpoint = 'https://api.instagram.com/v1/users/self/feed?access_token=' . $access_token . '&count=' . self::$count;
				  
				  echo $access_token;
				  
				  return json_decode(CurlHelper::getCurl($endpoint));

				  
			  }
			  
			  
			  /**
			  * Return Self/Feed response (this is the feed of images a user is subscribed to.
			  * @param $access_token string
			  * @param $id integer
			  * @return object
			  */
			  public static function mediaRecent($access_token,$id)
			  {
				  
				  	$endpoint = 'https://api.instagram.com/v1/users/' . $id . '/media/recent/?access_token=' . $access_token . '&count=' . self::$count;
				  
				 return json_decode(CurlHelper::getCurl($endpoint));

				  
			  }
			  
			  
			  
			 
			 
			 
			 
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
	}