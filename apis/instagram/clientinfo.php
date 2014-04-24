<?php

	class ClientInfo {
		
		function __construct()
		{
			
			
		}

		
		/**
		 * Check a client_id with Instagram.
		 * @param client_id.
		 * @return boolean
		 */
		 public static function validClientId($client_id)
		 {
			// Call Instagram and check that the client_id is correct.
			$endpoint = 'https://api.instagram.com/v1/tags/kittens?client_id=' . $client_id;
				   			
			$response = json_decode(CurlHelper::getCurl($endpoint));

		   	 if( isset($response->meta->code) )
		   	 {
			   	 
			   	 if($response->meta->code==200)
			   	 {
				   	return true; 
				   	
				   	} else {
				   
				   return false;	 
			   	 }
			   	 
		   	 } 
		 }
		 
		 
		 
		 
		 
		 
		 
		 
		 
		
	}