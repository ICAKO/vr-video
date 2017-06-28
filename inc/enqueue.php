<?php

// Admin Enqueue Scripts
function ara__vr_admin_enqueue_scripts() {
	
	global $post;
	
	$get_video_id = get_post_meta($post->ID,'ara_video_attachment_id',true);
	$get_video_info = ara__check_videoformat($get_video_id);
	
	wp_enqueue_style( 'vrmain', ARA_VR_PLUGIN_URL . 'assets/css/main.css');
	wp_enqueue_script( 'mainjs', ARA_VR_PLUGIN_URL . 'assets/js/main.js', array('jquery'),'',true);
		
	if($get_video_info) {
	
		// CSS
		wp_enqueue_style( 'videojscss', ARA_VR_PLUGIN_URL . 'assets/vr/videojs/v5/video-js.min.css');
		wp_enqueue_style( 'videojs-panorama', ARA_VR_PLUGIN_URL . 'assets/vr/dist/videojs-panorama.min.css');
		
		// JS
		wp_enqueue_script( 'vjs', ARA_VR_PLUGIN_URL . 'assets/vr/videojs/v5/video.min.js', array('jquery'),'',true);
		wp_enqueue_script( 'threejs', 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r76/three.js', array('jquery'),'',true);
		wp_enqueue_script( 'videojs-panorama', ARA_VR_PLUGIN_URL . 'assets/vr/dist/videojs-panorama.v5.min.js', array('jquery'),'',true);
		wp_enqueue_script( 'vrscripts', ARA_VR_PLUGIN_URL . 'assets/vr/vr_script.js', array('jquery'),'',true);
	
	}
}

add_action( 'admin_enqueue_scripts', 'ara__vr_admin_enqueue_scripts' );


// Front End Enqueue Scripts
function ara__vr__enqueue_scripts() {
	
	// CSS
	wp_enqueue_style( 'vrmain', ARA_VR_PLUGIN_URL . 'assets/css/main.css');
	wp_enqueue_style( 'videojscss', ARA_VR_PLUGIN_URL . 'assets/vr/videojs/v5/video-js.min.css');
	wp_enqueue_style( 'videojs-panorama', ARA_VR_PLUGIN_URL . 'assets/vr/dist/videojs-panorama.min.css');
	wp_enqueue_style('fawesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
		
		
	// JS
	wp_enqueue_script( 'vjs', ARA_VR_PLUGIN_URL . 'assets/vr/videojs/v5/video.min.js', array('jquery'),'',true);
	wp_enqueue_script( 'threejs', 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r76/three.js', array('jquery'),'',true);
	wp_enqueue_script( 'videojs-panorama', ARA_VR_PLUGIN_URL . 'assets/vr/dist/videojs-panorama.v5.min.js', array('jquery'),'',true);
	wp_enqueue_script( 'vrscripts', ARA_VR_PLUGIN_URL . 'assets/vr/vr_script.js', array('jquery'),'',true);
	wp_enqueue_script( 'frontend', ARA_VR_PLUGIN_URL . 'assets/js/frontend.js', array('jquery'),'',true);
	
}

add_action('wp_enqueue_scripts','ara__vr__enqueue_scripts');
