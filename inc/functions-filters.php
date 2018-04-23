<?php
/**
 * Functions and filters.
 *
 * @package   CustomClasses
 * @version   1.1.0
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2012-2017, Justin Tadlock
 * @link      https://themehybrid.com/plugins/custom-classes
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-1.0.html
 */

namespace Custom_Classes;

# Register post type support.
add_action( 'init', __NAMESPACE__ . '\post_type_support' );

# Register metadata.
add_action( 'init', __NAMESPACE__ . '\register_meta' );

# Filter classes on the front end.
add_filter( 'body_class', __NAMESPACE__ . '\body_class'        );
add_filter( 'post_class', __NAMESPACE__ . '\post_class', 10, 3 );

/**
 * Adds post type support for 'custom-classes' to all 'public' post types registered.
 * To unregister support for custom classes for a specific post type, use the
 * `remove_post_type_support()` function.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function post_type_support() {

	$post_types = get_post_types( array( 'public' => true ) );

	foreach ( $post_types as $type )
		add_post_type_support( $type, 'custom-classes' );
}

/**
 * Registers the '_custom_body_class' and '_custom_post_class' meta keys for posts.
 *
 * @since  1.0.0
 * @access public
 * @return void
 */
function register_meta() {

	$args = array(
		'type'              => 'string',
		'single'            => true,
		'sanitize_callback' => __NAMESPACE__ . '\sanitize_meta',
		'auth_callback'     => '__return_false',
		'show_in_rest'      => true
	);

	\register_meta( 'post', '_custom_body_class', $args );
	\register_meta( 'post', '_custom_post_class', $args );
	\register_meta( 'term', '_custom_body_class', $args );
}

/**
 * Checks if viewing a single post and if the post has any custom body classes registered.  If so, it adds the classes
 * to the array of body classes.
 *
 * @since  1.0.0
 * @access public
 * @param  array $classes
 * @return array
 */
function body_class( $classes ) {

	if ( is_singular() ) {

		$custom_classes = get_post_meta( get_queried_object_id(), '_custom_body_class', true );

		if ( $custom_classes ) {
			$terms = explode( ' ', sanitize_token_list( $custom_classes ) );
			if ( is_array( $terms ) ) {
				$classes = array_merge( $classes, $terms );
			}
		}

	} else if ( is_tax() || is_category() || is_tag() ) {

		$custom_classes = get_term_meta( get_queried_object_id(), '_custom_body_class', true );

		if ( $custom_classes ) {
			$terms = explode( ' ', sanitize_token_list( $custom_classes ) );
			if ( is_array( $terms ) ) {
				$classes = array_merge( $classes, $terms );
			}
		}
	}

	return $classes;
}

/**
 * Checks if a post has any custom post classes and adds them to the post wrapper element's classes.
 *
 * @since  1.0.0
 * @access public
 * @param  array   $classes
 * @param  string  $class
 * @param  int     $post_id
 * @return array
 */
function post_class( $classes, $class, $post_id ) {

	$custom_classes = get_post_meta( $post_id, '_custom_post_class', true );

	if ( $custom_classes ) {
		$terms = explode( ' ', sanitize_token_list( $custom_classes ) );
		if ( is_array( $terms ) ) {
			$classes = array_merge( $classes, $terms );
		}
	}

	return $classes;
}

/**
 * Callback function for sanitizing the meta value when add_post_meta() or update_post_meta() are called.
 *
 * @since  1.0.0
 * @access public
 * @param  string  $meta_value
 * @param  string  $meta_key
 * @param  string  $meta_type
 * @return string
 */
function sanitize_meta( $meta_value, $meta_key, $meta_type ) {

	return sanitize_token_list( $meta_value );
}

/**
 * Sanitize a token list string, such as used in HTML rel and class attributes.
 *
 * @since 1.1.0
 * @access public
 * @param string|array $tokens List of tokens separated by spaces, or an array of tokens.
 * @return string Sanitized token string list.
 */
function sanitize_token_list( $tokens ) {
	if ( is_string( $tokens ) ) {
		$tokens = preg_split( '/\s+/', trim( $tokens ) );
	}
	$tokens = array_map( 'sanitize_html_class', $tokens );
	$tokens = array_filter( $tokens );
	return join( ' ', $tokens );
}