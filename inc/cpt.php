<?php

/**
 * Register a VR Videos post type.
 */
 
function ara__register_posttype__vrvideos() {
	$labels = array(
		'name'               => _x( 'VR Videos', 'post type general name', 'vrvideos' ),
		'singular_name'      => _x( 'VR Videos', 'post type singular name', 'vrvideos' ),
		'menu_name'          => _x( 'VR Videos', 'admin menu', 'vrvideos' ),
		'name_admin_bar'     => _x( 'VR Videos', 'add new on admin bar', 'vrvideos' ),
		'add_new'            => _x( 'Add New', 'book', 'vrvideos' ),
		'add_new_item'       => __( 'Add New VR Video', 'vrvideos' ),
		'new_item'           => __( 'New VR Video', 'vrvideos' ),
		'edit_item'          => __( 'Edit VR Video', 'vrvideos' ),
		'view_item'          => __( 'View VR Video', 'vrvideos' ),
		'all_items'          => __( 'All VR Videos', 'vrvideos' ),
		'search_items'       => __( 'Search VR Videos', 'vrvideos' ),
		'parent_item_colon'  => __( 'Parent VR Video:', 'vrvideos' ),
		'not_found'          => __( 'No VR Videos found.', 'vrvideos' ),
		'not_found_in_trash' => __( 'No VR Videos found in Trash.', 'vrvideos' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'Description.', 'vrvideos' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'vrvideo' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'menu_icon'			 => 'dashicons-video-alt3',
		'supports'           => array( 'title', 'thumbnail' )
	);

	register_post_type( 'vrvideo', $args );
}

add_action( 'init', 'ara__register_posttype__vrvideos' );