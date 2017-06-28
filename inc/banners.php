<?php

// find Banners
add_action('wp_ajax_ara__showbanner','ara__showbanner');
add_action('wp_ajax_nopriv_ara__showbanner','ara__showbanner');

add_action('wp_ajax_ara__showlogo','ara__showlogo');
add_action('wp_ajax_nopriv_ara__showlogo','ara__showlogo');


function ara__showlogo() {
	$content = json_decode(file_get_contents(PARENTURL.'?vrlogo=1'),true);
	?>
	<a href="<?php echo esc_url($content['logo_url']); ?>" target="_blank" class="general_logo">
		<img src="<?php echo esc_url($content['logo_img']); ?>" />
	</a>
	<?php
	die();
}

function ara__showbanner() {
	
	$content = json_decode(file_get_contents(PARENTURL.'?vrinfo='.$_SERVER['HTTP_HOST']),true);
	if($content['premium'] == 0) {
	?>
	
		<div class="banners">
			<div class="banner">
				<span class="close"><i class="fa fa-times" aria-hidden="true"></i></span>
				<a href="<?php echo $content['anchor']; ?>" target="_blank">
			    	<img src="<?php echo $content['banner']; ?>" alt="<?php echo $content['alt']; ?>" />
			    </a>
			</div>
		</div>
	
	<?php
	}
	die();
	
}
