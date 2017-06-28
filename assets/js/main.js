(function($) {
'use strict';
	
	$(document).on('click','.remove-vr',function() {
		var post_id = $(this).data('postid');
		
		$.ajax({
			url: ajaxurl,  
		    type: 'POST',
		    data: {
				action: 'ara__post__removevideo',
				post_id: post_id,
		    },
		    success: function(data) {
		    	location.reload();
		    }
		 });
			
	});
	
})(jQuery);