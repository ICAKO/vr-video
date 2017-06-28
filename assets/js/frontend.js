(function($) {
'use strict';
	
	$(document).ready(function() {
		
		// Set Logo
		$.ajax({
			url: ajaxurl,  
		    type: 'POST',
		    data: {
				action: 'ara__showlogo',
		    },
		    success: function(data) {
		    	$('.vjs-poster').append(data);
		    }
	 	});
		
		
		// Show Banner
		$.ajax({
			url: ajaxurl,  
		    type: 'POST',
		    data: {
				action: 'ara__showbanner',
		    },
		    success: function(data) {
		    	
		    	$('.vr_container').append(data);
		    	
		    	$(document).on('click','.close',function() {
		    		$('.banner').remove();
		    	});
		    }
	 	});
		
	});
	
})(jQuery);	