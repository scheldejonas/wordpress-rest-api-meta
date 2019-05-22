<?php 
/**
 * Plugin Name: WP REST API Meta
 * Description: See all meta data on post types, terms and users
 * Author: Jonas Schelde
 * Version: 1.0.0
 * Author URI: https://www.jonasschelde.dk
 * Plugin URI: https://github.com/scheldejonas/wordpress-rest-api-meta
 */



/**
 * create_api_posts_meta_field function.
 * 
 * @access public
 * @return void
 */
function create_api_posts_meta_field() {
 
    register_rest_field( 'post', 'post_meta_fields', array(
           'get_callback'    => 'get_post_meta_for_api',
           'update_callback' => 'update_post_meta_for_api',
           'schema'          => null,
        )
    );
    
}
add_action( 'rest_api_init', 'create_api_posts_meta_field' );
 
 
/**
 * get_post_meta_for_api function.
 * 
 * @access public
 * @param mixed $object
 * @return void
 */
function get_post_meta_for_api( $object ) {
	
    $post_id = $object['id'];
 
    return get_post_meta( $post_id );
    
}


/**
 * update_post_meta_for_api function.
 * 
 * @access public
 * @param $object
 * @return void
 */
function update_post_meta_for_api( $value, $object ) {								

	foreach ( $value as $key => $inner_value ) {									
	
		$response = update_post_meta( $object->ID, $key, $inner_value );			
		
	}

}