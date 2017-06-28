<?php
  
  //Remove Video from POST
  function ara__post__removevideo() {
	update_post_meta($_POST['post_id'],'ara_video_attachment_id','');
  }
  
  add_action('wp_ajax_ara__post__removevideo','ara__post__removevideo');
  
  // Add Meta Box to Post VRVIDEOS
  function ara_register_meta_boxes() {
	add_meta_box( 'meta-box-id', __( 'Upload VR Video', 'vrvideo' ), 'ara__upload_video__callback', 'vrvideo' );
  }
  
  add_action( 'add_meta_boxes', 'ara_register_meta_boxes' );
 
 // Save Video if success
 function ara__save_video() {
	global $post;
	
	if(array_key_exists('ara_video_attachment_id', $_POST) && !empty($_POST['ara_video_attachment_id'])) {
		if(ara__check_videoformat($_POST['ara_video_attachment_id'])) {
			update_post_meta($post->ID,'ara_video_attachment_id',$_POST['ara_video_attachment_id']);
		}
	}
 }

 add_action('save_post','ara__save_video');

 // Callback function from upload video.
 // Meta box via vrvideo
 function ara__upload_video__callback() {
	global $post;
	
	wp_enqueue_media();
	
	$get_video_id = get_post_meta($post->ID,'ara_video_attachment_id',true);
	$get_video_info = ara__check_videoformat($get_video_id);
	
	if($get_video_info) {
		$video_url =  wp_get_attachment_url($get_video_id,true);
		
		$poster = "";
		if(has_post_thumbnail($post->ID)) {
			$poster = ara__featured_image($post->ID);
		}
		?>
		<video id="videojs-panorama-player" class="video-js vjs-default-skin" <?php if(!empty($poster)) { ?> poster="<?php echo $poster; ?>" <?php } ?> controls autoplay>
		  <source src="<?php echo $video_url; ?>" type="video/mp4">
		  <source src="movie.ogg" type="video/ogg">
		  Your browser does not support the video tag.
		</video>
		
		<br />
		<span class="remove-vr" data-postid="<?php echo $post->ID; ?>"><?php _e('Remove This Video','vrvideo'); ?></span>
		<?php
	}
	else {
	?>
		<br />
		<input id="upload_image_button" type="button" class="button" value="<?php _e( 'Upload Video' ); ?>" />
		<br /><br />
		<span class="attach_filename"></span>
		<input type='hidden' name='ara_video_attachment_id' id='video_attachment_id' value='<?php echo get_option( 'media_selector_attachment_id' ); ?>'>
	<?php
	}
 }
 
 
 // Admin Enqueue scripts
 // Footer
 
 function media_selector_print_scripts() {
	$my_saved_attachment_post_id = get_option( 'media_selector_attachment_id', 0 );
	?>
	<script type='text/javascript'>
		jQuery( document ).ready( function( $ ) {
			// Uploading files
			var file_frame;
			var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
			var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
			
			jQuery('#upload_image_button').on('click', function( event ){
				event.preventDefault();
				// If the media frame already exists, reopen it.
				if ( file_frame ) {
					// Set the post ID to what we want
					file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					// Open frame
					file_frame.open();
					return;
				} else {
					// Set the wp.media post id so the uploader grabs the ID we want when initialised
					wp.media.model.settings.post.id = set_to_post_id;
				}
				
				// Create the media frame.
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a video to upload',
					button: {
						text: 'Use this video',
					},
					multiple: false	// Set to true to allow multiple files to be selected
				});
				
				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = file_frame.state().get('selection').first().toJSON();
					// Do something with attachment.id and/or attachment.url here
					//$( '#image-preview' ).attr( 'src', attachment.url ).css( 'width', 'auto' );
					$( '#video_attachment_id' ).val( attachment.id );
					$('.attach_filename').html("<strong>Filename:</strong> " + attachment.filename);
					// Restore the main post ID
					wp.media.model.settings.post.id = wp_media_post_id;
				});
				
				// Finally, open the modal
				file_frame.open();
				
			});
			
			// Restore the main ID when the add media button is pressed
			jQuery( 'a.add_media' ).on( 'click', function() {
				wp.media.model.settings.post.id = wp_media_post_id;
			});
		});
	</script>
	<?php
 }
 
 add_action( 'admin_footer', 'media_selector_print_scripts' );
 
 
 // Check if postformat is video ( mp4 )
 // And Return Attach Info
 function ara__check_videoformat($att_id) {
	
	$get_att_info = wp_get_attachment_metadata($att_id);
	if(is_array($get_att_info) && array_key_exists('fileformat', $get_att_info) && $get_att_info['fileformat'] == "mp4") {
		return $get_att_info;
	}
	
	return false;
 }
 

// ================= Add Admin Columns ( Reorder ) */

// ADD NEW COLUMN
function arawp__columns_head() {
	$new_columns = array();
	
	$new_columns['cb'] = '<input type="checkbox">';
	$new_columns['vr__featuredimage'] = __('Featured Image','vrvideos');
	$new_columns['title'] = __('Title','vrvideos');
	$new_columns['vr__shortcode'] = __('Get Your Shortcode','vrvideos');
	$new_columns['date'] = __('Date','vrvideos');
	
	return $new_columns;
}
add_filter('manage_vrvideo_posts_columns', 'arawp__columns_head');

// SHOW THE FEATURED IMAGE
function arawp__columns_content($column_name, $post_ID) {
	
	// Featured Image
    if ($column_name == 'vr__featuredimage') {
    	if(has_post_thumbnail($post_ID)) {
        	echo get_the_post_thumbnail($post_ID,'thumbnail');
		}
    }
	
	// ShortCode
	if($column_name == "vr__shortcode") {
		echo '[vrvideo id="'.$post_ID.'"]';
	}
	
}
add_action('manage_vrvideo_posts_custom_column', 'arawp__columns_content', 10, 2);


// ================= Add Settings Page */

function ara__vrvideo__submenupage_docs() {
    add_submenu_page( 
        'edit.php?post_type=vrvideo',   //or 'options.php'
        __('Documentation','vrvideo'),
        __('Documentation','vrvideo'),
        'manage_options',
        'vr-video-doc',
        'vr_video_doc__callback'
    );
}

add_action('admin_menu', 'ara__vrvideo__submenupage_docs',40);

function vr_video_doc__callback() {
	?>
	<div clas="wrap">
		<h2><?php echo __('VR Video | Doc','vrvideo'); ?></h2>
		<hr />
		
		<p>Doc Info.</p>
		
		<p>[vrvideo id="8" width="500px" autoplay="0" poster="1" drag="true" verticalcenter="false" horizontalcenter="true"]</p>
	</div>
	<?php
}

function wpdocs_register_my_custom_submenu_page() {
    add_submenu_page( 
        'edit.php?post_type=vrvideo',   //or 'options.php'
        __('Settings','vrvideo'),
        __('Settings','vrvideo'),
        'manage_options',
        'vr-video-settings',
        'vr_video_settings__callback'
    );
}

add_action('admin_menu', 'wpdocs_register_my_custom_submenu_page');

function vr_video_settings__callback() {
	$vr__settings = json_decode(get_option('vrvideo__settings'),true);
	if(empty($vr__settings)) {
		$vr__settings = array();
	}
	
	if($_POST['wp_capture_action'] == 1) { ?>
		<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
			<p><strong><?php _e('Settings saved.','vrvideo'); ?></strong></p>
		</div>
	<?php }	?>
	
	<div clas="wrap">
		<h2><?php echo __('VR Video | Main Settings','vrvideo'); ?></h2>
		<hr />
		
		<form action="" method="post" accept-charset="utf-8">
			
		  <input type="hidden" name="wp_capture_action" value="1" />
		  
			<table class="form-table">
		        <tbody>
		            <tr>
		                <th><label for="autoplay"><?php _e('Autoplay','vrvideo'); ?></label></th>
		                <td>
		                	<input name="vrvideo[autoplay]" id="autoplay" type="checkbox" <?php if(array_key_exists('autoplay',$vr__settings)) { ?>checked="checked"<?php } ?> class="regular-text code">
		                	Test Description
		                </td>
		            </tr>
		            <tr>
		                <th><label for="poster"><?php _e('Show Image Poster','vrvideo'); ?></label></th>
		                <td>
		                	<input name="vrvideo[poster]" id="poster" type="checkbox" <?php if(array_key_exists('poster',$vr__settings)) { ?>checked="checked"<?php } ?> class="regular-text code">
		                </td>
		            </tr>
		            <tr>
		                <th><label for="width"><?php _e('Width','vrvideo'); ?></label></th>
		                <td>
		                	<input name="vrvideo[width]" id="width" type="text" <?php if(array_key_exists('width', $vr__settings) && !empty($vr__settings['width'])) { ?> value="<?php echo $vr__settings['width']; ?>" <?php } ?> class="regular-text code">
		                </td>
		            </tr>
		            
		            <tr>
		                <th><label for="width"><?php _e('Height','vrvideo'); ?></label></th>
		                <td>
		                	<input name="vrvideo[height]" id="width" type="text" <?php if(array_key_exists('width', $vr__settings) && !empty($vr__settings['height'])) { ?> value="<?php echo $vr__settings['height']; ?>" <?php } ?> class="regular-text code">
		                </td>
		            </tr>
		            
		        </tbody>
		    </table>
		    
		    <br /><br />
		    <h2><?php echo __('VR Video | Advanced Settings','vrvideo'); ?></h2>
			<hr />
		    
		    <table class="form-table">
		        <tbody>
		            <tr>
		                <th><label for="drag"><?php _e('Click & Drag','vrvideo'); ?></label></th>
		                <td>
		                	<input name="vrvideo[drag]" id="drag" type="checkbox" <?php if(array_key_exists('drag',$vr__settings)) { ?>checked="checked"<?php } ?> class="regular-text code">
		                </td>
		            </tr>
		            <tr>
		                <th><label for="verticalcenter"><?php _e('Back To Vertical Center','vrvideo'); ?></label></th>
		                <td>
		                	<input name="vrvideo[verticalcenter]" id="verticalcenter" type="checkbox" <?php if(array_key_exists('verticalcenter',$vr__settings)) { ?>checked="checked"<?php } ?> class="regular-text code">
		                </td>
		            </tr>
		            <tr>
		                <th><label for="horizontalcenter"><?php _e('Back To Horizontal Center','vrvideo'); ?></label></th>
		                <td>
		                	<input name="vrvideo[horizontalcenter]" id="horizontalcenter" type="checkbox" <?php if(array_key_exists('horizontalcenter',$vr__settings)) { ?>checked="checked"<?php } ?> class="regular-text code">
		                </td>
		            </tr>
		            
		        </tbody>
		    </table>
		    <p class="submit">
		    	<input type="submit" name="ara__savevrsettings" id="submit" class="button button-primary" value="<?php _e('Save Changes','vrvideo'); ?>">
		    </p>
		    
		</form>
	</div>
	<?php
	
}

/**
 * Save VR Settings on admin dashboard
 * 
 * @package WordPress
 * @version 1.0
 */
 
function ara__savevrsettings() {
		
	if(isset($_POST['ara__savevrsettings'])) {
		
		$new_value = json_encode($_POST['vrvideo']);
		$option_name = 'vrvideo__settings';
		
		if ( get_option( $option_name ) !== false ) {
		
		    // The option already exists, so we just update it.
		    update_option( $option_name, $new_value );
		
		} else {
		
		    // The option hasn't been added yet. We'll add it with $autoload set to 'no'.
		    $deprecated = null;
		    $autoload = 'no';
		    add_option( $option_name, $new_value, $deprecated, $autoload );
		}

	}
}

add_action('admin_init','ara__savevrsettings');


/*
 * Default Settings JavaScripts on
 * Front End
 */

 function ara__vrvideo__settings_js() {
 	$vr__settings = json_decode(get_option('vrvideo__settings'),true);
	if(empty($vr__settings)) {
		$vr__settings = array();
	}
	?>
 	<script>
		// Main Settings
		<?php if(array_key_exists('autoplay', $vr__settings)) {?>
			var vr__autoplay = 1;
		<?php } ?>
		
		<?php if(array_key_exists('poster', $vr__settings)) {?>
			var vr__poster = 1;
		<?php } ?>
		
		<?php if(array_key_exists('width', $vr__settings)) {?>
			var vr__width = "500px";
		<?php } ?>
		
		<?php if(array_key_exists('height', $vr__settings)) {?>
			var vr__heigt = "350px";
		<?php } ?>
		
		// Advanced Settings
		var vr__drag = <?php if(array_key_exists('drag', $vr__settings)) { ?>true<?php }else{?>false<?php } ?>;
		var vr__verticalcenter = <?php if(array_key_exists('verticalcenter', $vr__settings)) { ?>true<?php }else{?>false<?php } ?>;
		var vr__horizontalcenter = <?php if(array_key_exists('horizontalcenter', $vr__settings)) { ?>true<?php }else{?>false<?php } ?>;
 	</script>
 	<?php
 }
 
 add_action('wp_head','ara__vrvideo__settings_js');
