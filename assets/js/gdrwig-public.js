(function($){
$(document).ready(function(){
		
		/* Paginated Feed Handling */
		nextUrl = function()
		{

						$.ajax({
						  		url:'/wp-admin/admin-ajax.php?action=gdrwig_paginated_feed',
						  		type:'POST',
						  		data:{next_url:endpoint}
					  		})
					  		
					  		.done(function(data){

						  		$(".tag-feed").append(data);

						  		paginationClickEvent();
					  		
					  		})
					  		
		}
				  		
				  		
		paginationClickEvent = function()
		{
			
		    $('a#next_url').on('click',function(){
		    	endpoint = $(this).attr('href');
		    	$(this).remove();
		    	nextUrl();
		  		return false;
		    });
		}

		paginationClickEvent();	 		
		
	});	
})(jQuery)