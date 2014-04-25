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
			
				public static $client_id;
				public static $id;
				public static $access_token;
				public static $q;
				public static $count = 1;
				public static $endpoint = false;
			
			function __construct($config=array())
			{
				foreach($config as $key=>$row)
				{
					self::$$key = $row;
				};

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
			  * Return /users/user-id response.
			  * Get basic information about a user. 
			  * @param $access_token string
			  * @param $id integer
			  * @return object
			  */
			  public static function userId()
			  {
				  
				  	$endpoint = 'https://api.instagram.com/v1/users/' . self::$id . '/?access_token=' . self::$access_token;
				  
				 return json_decode(CurlHelper::getCurl($endpoint));

				  
			  }
			 
			 
			 /**
			  * Return /users/self/feed response 
			  * This is the feed of media (images & video) a user is subscribed to.
			  * @param $access_token string
			  * @return object
			  */
			  public static function selfFeed()
			  {
				  
				  $endpoint = 'https://api.instagram.com/v1/users/self/feed?access_token=' . self::$access_token . '&count=' . self::$count;
				  
				  return json_decode(CurlHelper::getCurl($endpoint));

			  }
			  
			  
			  /**
			  * Return /users/user-id/media/recent response  using access_token
			  * This is the feed of media (images & video) for a given user-id.
			  * @param $access_token string
			  * @param $id integer
			  * @return object
			  */
			  public static function mediaRecent()
			  {
				  
				  	$endpoint = 'https://api.instagram.com/v1/users/' . self::$id . '/media/recent/?access_token=' . self::$access_token . '&count=' . self::$count;
				  
				 return json_decode(CurlHelper::getCurl($endpoint));

				  
			  }
			  
			  
			  /**
			  * Return /users/user-id/media/recent response using client_id
			  * This is the feed of media (images & video) for a given user-id.
			  * @param $client_id string
			  * @param $id integer
			  * @return object
			  */
			  public static function mediaRecentClientId()
			  {
				  
				  	$endpoint = ( self::$endpoint === false ) ? 'https://api.instagram.com/v1/users/' . self::$id . '/media/recent/?client_id=' . self::$client_id . '&count=' . self::$count : self::$endpoint;
				  
				 return json_decode(CurlHelper::getCurl($endpoint));

				  
			  }
			  
			  
			  /**
			  * Return /users/search response 
			  * Search for a user by name
			  * @param $access_token string
			  * @param $q string
			  * @return array
			  */
			  public static function search()
			  {
				  
				  	$endpoint = 'https://api.instagram.com/v1/users/search?q=' . self::$q . '&access_token=' . self::$access_token . '&count=' . self::$count;
				  
				 return json_decode(CurlHelper::getCurl($endpoint));

				  
			  }

			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
	}