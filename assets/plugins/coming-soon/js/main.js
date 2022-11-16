(function($) {
	$('.close').click(function() {
		$('li').find('a').removeClass('active');
		$('.pop-window').hide();
	});
   $('li').click(function(){
		$('.pop-window').show();
		var className=$(this).attr('class');
		$('li').find('a').removeClass('active');
		$('.'+className).find('a').addClass('active');
		if($(this).hasClass( "about-nav" )){
			var about=$('#about').html();
			$('#pop-res').html(about);
		}
		else if($(this).hasClass( "services-nav" )){
			var features=$('#features').html();
			$('#pop-res').html(features);
		}
		else if($(this).hasClass( "contact-nav" )){
			var contact=$('#contact').html();
			$('#pop-res').html(contact);
		}
   });
})(jQuery);