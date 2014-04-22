<?php


	class ResponseHtml {

	
	/**
	 * Return formatted html for response thumbnails.
	 * @param $data array - image data array
	 * @return string
	 */
	 public static function thumbs($data,$resolution='thumbnail')
	 {
	 		$data = (is_array($data)) ? $data : array();

	 		if( ! empty($data) && ! in_array($resolution,ResponseHtml::availableResolutions($data)))
	 		{
		 		
		 		return 'Available resolutions are:<br>' . implode('<br>',ResponseHtml::availableResolutions($data));
	 		}
	 		
	 		$html = '';
	 		
		 	$i=1;

		 	foreach($data as $row)
		 	{
		 			 	
			 	$id				= ( isset( $row->id ) ) ? $row->id : '';
			 	$tags			= ( isset( $row->tags ) ) ? implode(" ",$row->tags) : "";
			 	$username		= ( isset( $row->user->username ) ) ? $row->user->username : '';
			 	$filter			= ( isset( $row->filter ) ) ? $row->filter : '';
			 	$link			= ( isset( $row->link ) ) ? $row->link : '';
			 	$likes_count	= ( isset( $row->likes->count ) ) ? $row->likes->count : '';
			 	$comments_count	= ( isset( $row->comments->count ) ) ? $row->comments->count : '';
			 	$caption		= ( isset( $row->caption->text ) ) ? $row->caption->text : '';
			 	$type			= ( isset( $row->type ) ) ? $row->type : '';
			 	$images			= $row->images;

			 	
			 	$html.= '
			 			<div id="id-' . $id . '" class="thumb' . ' thumb-' . $i . ' ' . $tags . ' ' . $username . ' ' . $filter . ' ' . $type . '" data-likes="' . $likes_count . '" data-comments="' . $comments_count . '">
			 				<a href="' . $link . '" title="' . $caption . '"><img src="' . $images->{$resolution}->url .'" alt="" /></a>
			 			</div>
			 			';
			$i++;
			
		 	}

		 	
		 	return $html;

	 }
	 
	 
	 /**
	 * Return formatted html for /users/search response.
	 * @param $data array - users data array
	 * @return string
	 */
	 public static function searchProfiles($data,$options=array())
	 {
	 		
	 		$html = '<ul class="users-search">
	 		';
	 		
	 		
	 		if(isset($options['user']['id']))
	 		{
		 		
		 		$user_id = $options['user']['id'];
		 		
	 		} else {
		 		
		 		$user_id = NULL;

	 		}
	 		
	 		
	 		foreach($data as $key=>$row)
	 		{
		 		
		 		$html.=	'	<li>
		 						<ul id="' . $row->username .'" class="profile profile-' . ($key+1) . '">
		 							<li class="profile-image"><img src="' . $row->profile_picture  . '" alt="' . $row->username .'"></li>
		 							<li><a target="_blank" href="http://instagram.com/' . $row->username  . '">' . $row->username . '</a></li>
		 							<li>' . $row->full_name . '</li>
		 							<li>' . $row->id . '</li>
		 						</ul>
		 					</li>
		 		';
		 		
	 		}
	 		
	 		$html.="</ul>";
	 		
	 		return $html;

	 }
	 

	 
	 /** 
	  * If there are additional results available, return a "Show more" button.
	  * @param $responseObject - response object
	  * @return string;
	  */
	  public static function paginationButton($response,$btnId='next_url',$btnClass='btn show-more',$btnText='Show more')
	  {
	  		if(isset($response->pagination->next_url))
	  		{
		  		
		  		$html = '<a id="' . $btnId . '" class="' . $btnClass . '" href="javascript:void(0);" data-next_url="' . $response->pagination->next_url . '">' . $btnText . '</a>';
		  		
	  		} else {
		  		
		  		$html = '';
	  		}
	  		
	  		return $html;
		  
	  }
	 
	 
	 /**
	  * Return array of available resolutions.
	  * @param $data - image data array
	  * @return array
	  */
	  public static function availableResolutions($data=array())
	  {
	  
	  	  $result = array();
	  	  
		   
		  if( isset($data[0]->images))
		  {
		  
		  foreach($data[0]->images as $key=>$row)
			 {
				 $result[] = $key;
			 }
			  
		  }
		  
		  return $result;
		  
	  }
	 
	 
	 
	 
	 
	 
	 
}