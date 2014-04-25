<?php
/**
 * TagFeed
 *
 * Class for retrieving a tag feed response from Instagram's API
 *
 * @package		InstaApi
 * @author		Richard Whitmer
 */


			class TagFeed {
			
					
			public	static $endpoint		= "https://api.instagram.com/v1/tags/%s/media/recent?client_id=%s&count=%s";
			public	static $max_tag_id		= null;
			public	static $min_tag_id		= null;
			public	static $next_url		= null;
			public	static $media_count		= null;
			public	static $client_id		= null;
			public	static $hashtag			= null;
			public	static $count			= 0;
			
			
			
			
			/**
			 * Create instance of class with client_id  and tag data.
			 * @param $id string - Instagram client_id
			 * @param $tag - hashtag of feed.
			 * @param $count - number of results to return per request.
			 * @return void
			 */
			public function __construct($config = array())
			{
			
					foreach($config as $key => $row)
						{
						
							self::$$key = $row;
							
						}
						
						
						if( self::$client_id == null)
						{
						
							exit('<p>You need a Client ID. You can get one at <a href="http://instagram.com/developer">http://instagram.com/developer</a></p>');
							
						}

					
					
					
					// If there's no tag, exit.
					if( self::$hashtag == null )
					{
						exit('<p>You need to include the hashtag parameter. Log into the admin area and update the settings.</p>');
					}
					
					
					// If there's no tag, exit.
					if( self::$count == 0)
					{
						exit('<p>You\'re requesting zero results.</p>');
					}
					
					// Set some default properties.
				
					//###$this->endpoint		= $this->endpoint();
					
					
					
					
				}
				
				/**
				* Create the endpoint based on default or instance-assigned properties.
				* @return string
				*/
				public function endpoint()
				{
					     return sprintf(self::$endpoint,self::$hashtag,self::$client_id,self::$count);
				}
				

				
				/**
				 * Return response as a php object.
				 * @return mixed str/obj
				 */
				 public static function response()
				 {	
						$response = self::tagsMediaRecent();

						if(isset($response->meta->error_message))
						{
						  	exit($response->meta->error_message);
						}
						
					return $response;
				 }
				

				/**
				 * Get a list of recently tagged media.
				 * @param $tag string
				 * @client_id string
				 * @param $max_tag_id string
				 * @return array
				 */
				 public static function tagsMediaRecent()
				 {
				 
				 				$endpoint = self::endpoint();

								// Get the response
								$response = json_decode(CurlHelper::getCurl($endpoint));

							return $response;
				 }
				 
				 
				 /**
				  ** Set tag data for current tag to instance.
				  ** @return integer
				  */
				  public function mediaCount()
				  {
					  
					  $response = json_decode(self::tagData());
					  

				   	 if(isset($response->meta->error_message))
					  {
					  	exit($response->meta->error_message);
					  }	
				   			
					  
					  if(isset($response->data->media_count))
					  {
						  return $response->data->media_count;
					  }
					  
					  return 0;

				  }
				 
				 
				 
				 /**
				   * Get information about current tag object.
				   * @return string
				   */
				   public function tagData()
				   {
				   			
				   							   			
				   			$endpoint = 'https://api.instagram.com/v1/tags/'. self::$hashtag . '?client_id=' . self::$client_id;
				   			
				   			$response = CurlHelper::getCurl($endpoint);
				   			

				   		return json_decode($response);
				   		
				   }

					   
			
	}
			
			

			
			
			
			
			
			
			
			
			
			
			
			
			
			
			