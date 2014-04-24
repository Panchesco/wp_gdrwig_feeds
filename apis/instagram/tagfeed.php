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
			
					
			public	$endpoint		= "https://api.instagram.com/v1/tags/%s/media/recent?client_id=%s&count=%s";
			public	$max_tag_id		= null;
			public	$min_tag_id		= null;
			public	$next_url		= null;
			public	$media_count	= null;
			
			/**
			 * Create instance of class with client_id  and tag data.
			 * @param $id string - Instagram client_id
			 * @param $tag - hashtag of feed.
			 * @param $count - number of results to return per request.
			 * @return void
			 */
			public function __construct($id=false,$tag=false,$count=0)
			{
					
					// If there's no client id, user will need to get one.
					if(false === $id || empty($id))
					{
						
						if(empty($id))
						{
						
							exit('<p>You need a Client ID. You can get one at <a href="http://instagram.com/developer">http://instagram.com/developer</a></p>');
							
						}
					}
					
					
					
					// If there's no tag, exit.
					if(false === $tag || empty($tag))
					{
						exit('<p>You need to include the hashtag parameter. Log into the admin area and update the settings.</p>');
					}
					
					
					// If there's no tag, exit.
					if(0 == $count)
					{
						exit('<p>You\'re requesting zero results.</p>');
					}
					
					// Set some default properties.
					$this->client_id 	= $id;
					$this->tag			= $tag;
					$this->count		= $count;
					$this->endpoint		= $this->endpoint();
					
					
					
					
				}
				
				/**
				* Create the endpoint based on default or instance-assigned properties.
				* @return string
				*/
				public function endpoint()
				{
					     return sprintf($this->endpoint,$this->tag,$this->client_id,$this->count);
				}
				

				
				/**
				 * Return response as a php object.
				 * @return mixed str/obj
				 */
				 public function response()
				 {	
						$response = $this->tagsMediaRecent();

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
				 public function tagsMediaRecent()
				 {
				 
				 				$endpoint = $this->endpoint();
					 			
								if($this->max_tag_id !== null)
								{
									$endpoint.= '&max_tag_id=' . $max_tag_id;	
								}
								
								// Get the response
								$response = json_decode(CurlHelper::getCurl($endpoint));
								
								// Set pagination properties if they exist.
								if(isset($response->pagination->max_tag_id))
								{
									$this->max_tag_id = $response->pagination->max_tag_id;
									
								}
								
								if(isset($response->pagination->min_tag_id))
								{
									$this->min_tag_id = $response->pagination->min_tag_id;
									
								}
								
								if(isset($response->pagination->next_url))
								{
									$this->next_url = $response->pagination->next_url;
									
								}

							return $response;
				 }
				 
				 
				 /**
				  ** Set tag data for current tag to instance.
				  ** @return integer
				  */
				  public function mediaCount()
				  {
					  
					  $response = json_decode($this->tagData());
					  

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
				   			
				   							   			
				   			$endpoint = 'https://api.instagram.com/v1/tags/'. $this->tag . '?client_id=' . $this->client_id;
				   			
				   			$response = CurlHelper::getCurl($endpoint);
				   			

				   		return json_decode($response);
				   		
				   }

					   
			
	}
			
			

			
			
			
			
			
			
			
			
			
			
			
			
			
			
			