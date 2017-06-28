<?php

/*
 * Register vrvideo Shortcode
 */
 
function arawp__vrvideo__shortcode( $atts, $content = null ){
	$vr__settings = json_decode(get_option('vrvideo__settings'),true);
	
	$vr_poster = 0;
	$return = '';
	
	if(empty($vr__settings)) {
		$vr__settings = array(
			'width' => '',
			'height' => ''
		);
	}

	if(array_key_exists('poster', $vr__settings)) {
		$vr_poster = 1;
	}
	
	$default_settings = array(
		'width'	=>	'100%',
		'height' => 'auto',
	);
	
	if(!empty($vr__settings['width'])) { $default_settings['width'] = $vr__settings['width']; }
	if(!empty($vr__settings['height'])) { $default_settings['height'] = $vr__settings['height']; }
	
	if(array_key_exists('width', $atts)) { $default_settings['width'] = $atts['width']; }
	if(array_key_exists('height', $atts)) { $default_settings['height'] = $atts['height']; }
		
	if(array_key_exists('id', $atts)) {
		$video_post_id = $atts['id'];
		
		$video_post = get_post($atts['id']);
		$video_media_id = get_post_meta($video_post->ID,'ara_video_attachment_id',true);
		$get_video_info = ara__check_videoformat($video_media_id);
		$video_url =  wp_get_attachment_url($video_media_id,true);
		$content_logo = json_decode(file_get_contents(PARENTURL.'?vrlogo=1'),true);
		
		if($vr_poster == 1) {
			$poster = "";
			if(has_post_thumbnail($video_post->ID)) {
				$poster = ara__featured_image($video_post->ID,'large');
			}
		}
	
		if(array_key_exists('poster', $vr__settings) AND $atts['poster'] == 1) {
			if(has_post_thumbnail($video_post->ID)) {
				$vr_poster = 1;
				$poster = ara__featured_image($video_post->ID,'large');
			}	
		}
		
		$return .='<script>';
			
			if(array_key_exists('autoplay', $atts)) {
				$return .= 'var vr__autoplay = '.$atts['autoplay'].';';
			}
			
			if(array_key_exists('poster', $atts)) {
				$return .= 'var vr__poster = '.$atts['poster'].';';
			}

			if(array_key_exists('drag', $atts) AND ($atts['drag'] == "true" || $atts['drag'] == "false")) {
				$return .= 'var vr__drag = '.$atts['drag'].';';
			}
			
			if(array_key_exists('verticalcenter', $atts) AND ($atts['verticalcenter'] == "true" || $atts['verticalcenter'] == "false")) {
				$return .= 'var vr__verticalcenter = '.$atts['verticalcenter'].';';
			}
			
			if(array_key_exists('horizontalcenter', $atts) AND ($atts['horizontalcenter'] == "true" || $atts['horizontalcenter'] == "false")) {
				$return .= 'var vr__horizontalcenter = '.$atts['horizontalcenter'].';';
			}
			
			$logo_url = $content_logo['logo_url'];
			$logo_img = $content_logo['logo_img'];
			
			$return .= "var vr__logo = '" . $logo_url."';";
			$return .= "var vr__logo_url = '".$logo_img."';";
			
		$return .='</script>';
		$return .= '<div class="vr_container" style="width: '.$default_settings['width'].'">';
			$return .='<video id="videojs-panorama-player" width="'.$default_settings['width'].' !important;" height="'.$default_settings['height'].'" class="video-js vjs-default-skin" controls';
				if(!empty($poster) && $vr_poster == 1) { $return .=' poster="'.$poster.'"'; } 
			$return .='>';
				$return .='<a href="'.$logo_url.'" target="_blank" class="insert_logo"><img src="'.$logo_img.'" /></a>';
				$return .='<source src="'.$video_url.'" type="video/mp4">';
				$return .='<source src="movie.ogg" type="video/ogg">';
				$return .='Your browser does not support the video tag.';
			$return .= '</video>';
			$return .='<a href="'.$logo_url.'" target="_blank" class="insert_logo"><img src="'.$logo_img.'" /></a>';
		$return .= '</div>';
		
		return $return;
	}
	
}

add_shortcode( 'vrvideo', 'arawp__vrvideo__shortcode');