<?php 
/**
 * Plugin Name: WP REST API Meta
 * Description: See all meta data on post types, terms and users
 * Author: Jonas Schelde
 * Version: 2.0.0
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
    
    register_rest_field( 'category', 'term_meta_fields', [
	    'get_callback'	=> 'get_term_meta_for_api',
	    'update_callback' => 'update_term_meta_for_api',
	    'schema' => null,
    ]);
    
    register_rest_field( 'user', 'user_meta_fields', [
	    'get_callback'	=> 'get_user_meta_for_api',
	    'update_callback' => 'update_user_meta_for_api',
	    'schema' => null,	    
    ])
    
}
add_action( 'rest_api_init', 'create_api_posts_meta_field' );



/**
 * get_user_meta_for_api function.
 * 
 * @access public
 * @param mixed $object
 * @return void
 */
function get_user_meta_for_api( $object ) {
	
	
	// Validation
	if (
		! is_user_logged_in()
	) {
		
		return [];
		
	}
	
	
	// Pass - all fields
	$id = $object['id'];
	
	return get_user_meta( $id );
	
} 



/**
 * update_user_meta_for_api function.
 * 
 * @access public
 * @param mixed $data
 * @param mixed $object
 * @return void
 */
function update_user_meta_for_api( $data, $object ) {
	
	
	// Validation
	if (
		! is_user_logged_in()
	) {

		return;
		
	}
	

	// Update - all fields
	$id = $object->ID;
		
	foreach ( $data as $key => $value ) {
		
		$response = update_user_meta( $id, $key, $value );
		
	}
		
}
 

/**
 * get_term_meta_for_api function.
 * 
 * @access public
 * @param mixed $object
 * @return void
 */
function get_term_meta_for_api( $object ) {
	
	
	// Validation
	if (
		! is_user_logged_in()
	) {
		
		return [];
		
	}
	
	
	// Pass - all fields
	$id = $object['id'];
	
	return get_term_meta( $id );
	
} 


/**
 * update_term_meta_for_api function.
 * 
 * @access public
 * @param mixed $data
 * @param mixed $object
 * @return void
 */
function update_term_meta_for_api( $data, $object ) {
	
	
	// Validation
	if (
		! is_user_logged_in()
	) {

		return;
		
	}
	

	// Update - all fields
	$id = $object->term_id;
		
	foreach ( $data as $key => $value ) {
		
		$response = update_term_meta( $id, $key, $value );
		
	}
		
}

 
/**
 * get_post_meta_for_api function.
 * 
 * @access public
 * @param mixed $object
 * @return void
 */
function get_post_meta_for_api( $object ) {
	
	
	// Validation
	if (
		! is_user_logged_in()
	) {
		
		return [];
		
	}
	
	
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
function update_post_meta_for_api( $data, $object ) {		


	// Validation
	if (
		! is_user_logged_in()
	) {

		return;
		
	}
	
	
	// Prepare - post id
	$post_id = $object->ID;
	
	
	// Update - all fields
	if (
		array_key_exists('single', $data)
		|| array_key_exists('multiple', $data)
		|| array_key_exists('advanced_fields', $data)
	) {
		
// 		delete_post_meta( $post_id, 'single' );
		
// 		delete_post_meta( $post_id, 'multiple' );
		
		foreach ( $data as $key => $value ) {									
			
			if (
				$key == 'multiple'
			) {
				
				foreach ( $value as $multi_key => $multi_value ) {
				
					delete_post_meta( $object->ID, $multi_key );
				
					foreach ( $multi_value as $inner_value ) {
				
						$response = add_post_meta( $post_id , $multi_key, $inner_value, false );
						
					}	
					
				}
				
			}
			else if (
				$key == 'single'
			) {
				
				foreach ( $value as $single_key => $single_value ) {
				
					$response = update_post_meta( $post_id, $single_key, $single_value );					
					
				}
			
			}
			else if (
				$key === 'advanced_fields'
			) {
				
				foreach ( $value as $single_key => $single_value ) {
					
					$response = update_field( $single_key, $single_value, $post_id );
					
				}
				
			}
			else {
				
				$response = update_post_meta( $post_id, $key, $value );		
				
			}
			
			
		}
			
	}
	else {
		
		foreach ( $data as $key => $value ) {									
			
			if (
				is_array( $value )
			) {
				
				delete_post_meta( $post_id, $key );
				
				foreach ( $value as $inner_value ) {
			
					$response = add_post_meta(  $post_id, $key, $inner_value, false );
					
				}
				
			}
			else {
				
				$response = update_post_meta( $post_id, $key, $value );			
			
			}
			
		}
		
	}

}