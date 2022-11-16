(function($){
	$(document).ready(function(){
			jQuery.validator.setDefaults({
			debug: true,
			success: "valid"
			});
				/*subscribe form validation*/
				var subform=$("#sub-form");
				subform.validate({
				  errorPlacement: function(error, element) {
					$("#summary").html(error);
				  },
				  highlight: function(element, errorClass) {
						$(element).fadeOut(function() {
						  $(element).fadeIn();
						});
					  },
					  success: function(error) {
						$("#summary").css('background','green');
					  }
				});
				
				
				/*submit subscribe form*/
				$( "#sub-go" ).click(function() {
				  //alert( "Valid: " + form.valid() );
				  if(subform.valid()){
				  
						var sub_email = $("#email").val();
						  var myData =  '&postEmail=' + sub_email ;
						  jQuery.ajax({
								type: "POST",
								url: "subscribe.php",
								dataType:"html",
								data:myData,
								success:function(response){
									$("#summary").html(response);
									//$("#summary").slideDown("slow"); //show Result 
									$("#sub-form").hide(); //hide form div slowly
								},
								error:function (xhr, ajaxOptions, thrownError){
									$("#summary").html(thrownError);
								}    
							});
				  }
				  event.preventDefault();
				}); /*ends subs form submission*/
				
				/*contact form validation*/
				
				var bmform=$("#bm-form");
				bmform.validate({
				 rules: {
					// simple rule, converted to {required:true}
						name: "required",
						message: "required",
						// compound rule
						email: {
						  required: true,
						  email: true
						}
				  },
				  errorPlacement: function(error, element) {
					$("#cont-summary").html(error);
				  },
				  highlight: function(element, errorClass) {
						$(element).fadeOut(function() {
						  $(element).fadeIn();
						});
					  },
					  success: function(error) {
					  } 
					  
				});
				
				
				/*submit contact form*/
				
				$("#bm-submit" ).find("#submit").click(function() {
				  
				  if(bmform.valid()){
						var cont_name = $("#cont-name").val();
						var cont_email = $("#cont-email").val();
						var cont_message = $("#cont-message").val();
						  var myData =  '&cont-name=' + cont_name+'&cont-email=' + cont_email+'&cont-message=' + cont_message ;
						  jQuery.ajax({
								type: "POST",
								url: "subscribe.php",
								dataType:"html",
								data:myData,
								success:function(response){
									$("#cont-summary").html(response);
								} 
							}); 
					event.preventDefault();
				  }
				  
				}); /*ends cont form submission*/
	});			
})(jQuery);				