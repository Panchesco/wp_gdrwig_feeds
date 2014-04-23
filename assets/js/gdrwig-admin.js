

	// jQuery no conflict wrapper
	(function($) {
		
		$(document).ready(function(){
		

			   
					   userSearch = function(sel)
					   {
							  q = $(sel).val();
		
							  var url = '/wp-admin/admin-ajax.php?action=gdrwig_search_users';
							  
							  $.ajax({
								  type: 'POST',
								  url: url,
								  data:{q:q},
								  context: document.body
							  }).done(function(data){
								  $('#user-search-results').html(data);
							  });
						   
						   
					   }
					   
					   
					   
					   liveSearch = function()
					   {
					       $('input[data-search_value=q]').focus(function(){
					    	   
					    	   $(this).keyup(function(){
					    		   
					    		   l = $(this).val().length;
					    		   if(l>3)
					    		   {
					    			   userSearch('input[data-search_value=q]');
					    		   }
					    	   });
					    	   
					       });
					    }
					    
					    
					    
					   clickSearch = function()
					   {
						   		$('#find-user').on('click',function(){
							   		userSearch('input[data-search_value=q]');
						   		});
					    			   
					   }
			   
			   
			   			updateUsersApiData = function()
			   			{
			   
				  				$(".user-data").on("click",function(){
							
								$("#users-api-current").animate({opacity:0},300);
					   
								var id				= $(this).attr("data-id");
								var username		= $(this).attr("data-username");
								var full_name		= $(this).attr("data-full_name");
								var profile_picture = $(this).attr("data-profile_picture");
					  
								$.ajax({
									type: "POST",
									url: "/wp-admin/admin-ajax.php?action=gdrwig_update_users_api_data",
									data: {id:id,username:username,full_name:full_name,profile_picture:profile_picture}
									}
									
								).done(function(data){
								
								$("#users-api-current img").attr("src",profile_picture);
								$("#users-api-current a").attr("href","http://instagram.com/"+username);
								$("#users-api-current a").text(username);
								$("#users-api-current").animate({opacity:1},300);
								
								
								$(".users-api-id").val(id);
								$(".users-api-username").val(username);
								$(".users-api-full_name").val(full_name);
								$(".users-api-profile_picture").val(profile_picture);
									
								});
					   
					  		}); 
				  		
				  		}
				  		


				  		
				  		
				  		/* Add some classes to the default WP API Settings page table rows */
				  		$('.form-table input,.form-table textarea,.form-table select,.form-table email').each(function(){
					  		
					  		 var n = $(this).attr('name');
					  		 
					  		 
					  		 if( n != undefined)
					  		 {
					  		 
					  		 	n = n.replace('gdrwig_settings[','');
					  		 	n = n.replace(']','');
					  		 	
					  		 	$(this).closest('tr').addClass(n);

					  		 }

				  		});
				  		
				  		
				  		/* Show or hide ig_user_to_show depending on the selected feed */
				  		toggleSelectedUser = function()
				  		{
				  		
				  			if($("[id='gdrwig_settings[feed]']").val()=='user')
				  			{
					  			$('.ig_user_to_show').fadeIn();
					  		} else {
						  		
						  		$('.ig_user_to_show').fadeOut();
					  		}

				  		}
				  		
			   
				  		liveSearch();
				  		
				  		toggleSelectedUser();
				  		$("[id='gdrwig_settings[feed]']").change(function(){
					  		toggleSelectedUser();
				  		});
			   
		
			
		});
		
	})(jQuery);	
	