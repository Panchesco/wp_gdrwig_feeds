<?php
/**
 * CurlHelper
 *
 * @package		InstaApi
 * @author		Richard Whitmer
 */

 
	class CurlHelper {
		
		
					/**
				    * CURL handling.
				    * @param $uri string
				    * @return object
				    */
				    public static function getCurl($url) {
						    if(function_exists('curl_init')) {
						        $ch = curl_init();
						        curl_setopt($ch, CURLOPT_URL,$url);
						        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						        curl_setopt($ch, CURLOPT_HEADER, 0);
						        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
						        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
						        $output = curl_exec($ch);
						        echo curl_error($ch);
						        curl_close($ch);
						        return $output;
						    } else{
						        return file_get_contents($url);
						    }
						}
		
		
	}