<?php
/*
 * Plugin Name: VR Video
 * Version: 1.1
 */
 

 define( 'ARA_VR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
 define( 'ARA_VR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
 define('PARENTURL','http://vr.asitora.com/');
 
 // Register Custom Post Type
 require_once 'inc/cpt.php';
 
 // Add Enqueue Scripts
 require_once 'inc/enqueue.php';
 
 // Admin Settings and Meta Boxes
 require_once 'inc/admin.php';
 
 // Video Shortcode
 require_once 'inc/shortcode.php';
  
 // Banners
 require_once 'inc/banners.php';
 
 // Update Plugin
 require 'inc/plugin-update-checker.php';
 
 /**
  * 
  * Get url of post thumbnail
  * 
  * @param number $post_id		ID of post
  * @param string $thumbnail	Image size
  */
  
 function ara__featured_image($post_id,$thumbnail="thumbnail") {
 	$post_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post_id), $thumbnail );
	
	if($post_image) {
		return esc_url($post_image[0]);
	}
	
	return false;
 }
 
 // Loading WP Ajax
 function ara__custom_js() {
	echo '<script type="text/javascript">
			var ajaxurl = "' . admin_url('admin-ajax.php') . '";
		</script>';
 }
 add_action('wp_head', 'ara__custom_js');
 
 
 // Trace DEBUG
 function trace($data) {
 	echo '<pre>';
	print_r($data);
	echo '</pre>';
 }
 
 /*
  * VR Switch Plugin
  * Add / Remove site from Manager. 
  */
  
 class VrSwitchPlugin {
    
	// run code when activated plugin
    public static function plugin_activated(){
    	$host = $_SERVER['HTTP_HOST'];
		if($host != "localhost") {
			ara__vr__curl(PARENTURL.'?vrvideo=add&url='.$host);
		}
    }
	
	// run code when deactivate plugin
    public static function plugin_deactivated(){
    	$host = $_SERVER['HTTP_HOST'];
		if($host != "localhost") {
			ara__vr__curl(PARENTURL.'?vrvideo=remove&url='.$host);		
		}
    }
}

register_activation_hook( __FILE__, array('VrSwitchPlugin', 'plugin_activated' ));
register_deactivation_hook( __FILE__, array('VrSwitchPlugin', 'plugin_deactivated' ));

function ara__vr__curl($url) {
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_HEADER, false);
	$data = curl_exec($curl);
	curl_close($curl);
}
 

$vrvideo_update = PucFactory::buildUpdateChecker(
    'http://asitora.com/vrvideo.json',
    __FILE__,
    'vrvideo'
);
