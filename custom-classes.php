<?php
/**
 * Plugin Name: Custom Classes
 * Plugin URI: http://justintadlock.com/archives/2012/02/06/custom-classes-wordpress-plugin
 * Description: Allows users to input custom post and <code>&lt;body></code> classes on a per-post basis.
 * Version: 0.1
 * Author: Justin Tadlock
 * Author URI: http://justintadlock.com
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU 
 * General Public License version 2, as published by the Free Software Foundation.  You may NOT assume 
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without 
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @package CustomClasses
 * @version 0.1.0
 * @author Justin Tadlock <justin@justintadlock.com>
 * @copyright Copyright (c) 2012, Justin Tadlock
 * @link http://justintadlock.com/archives/2012/02/06/custom-classes-wordpress-plugin
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

/* Set up the plugin on the 'plugins_loaded' hook. */
add_action( 'plugins_loaded', 'custom_classes_setup' );

/**
 * Plugin setup function.  Loads the translation files and adds each action and filter to their appropriate hook.
 *
 * @since 0.1.0
 */
function custom_classes_setup() {

	/* Loads the plugin translation files. */
	load_plugin_textdomain( 'custom-classes', false, 'custom-classes/languages' );

	/* Register post type support for custom classes. */
	add_action( 'init', 'custom_classes_post_type_support' );

	/* Register metadata. */
	add_action( 'init', 'custom_classes_register_meta' );

	/* Filter the <body> class. */
	add_filter( 'body_class', 'custom_classes_body_class' );

	/* Filter the post class. */
	add_filter( 'post_class', 'custom_classes_post_class', 10, 3 );

	/* Register the meta box. */
	add_action( 'add_meta_boxes', 'custom_classes_add_meta_boxes', 10, 2 );

	/* Save the post meta. */
	add_action( 'save_post', 'custom_classes_save_meta', 10, 2 );
}

/**
 * Adds post type support for 'custom-classes' to all 'public' post types registered.  To unregister support 
 * for custom classes for a specific post type, use the remove_post_type_support() function.
 *
 * @since 0.1.0
 */
function custom_classes_post_type_support() {

	/* Get all available 'public' post types. */
	$post_types = get_post_types( array( 'public' => true ) );

	/* Loop through each of the public post types and add support for custom classes. */
	foreach ( $post_types as $type )
		add_post_type_support( $type, 'custom-classes' );
}

/**
 * Registers the '_custom_body_class' and '_custom_post_class' meta keys for posts.
 *
 * @since 0.1.0
 */
function custom_classes_register_meta() {

	/* Register custom body class meta. */
	register_meta(
		'post',
		'_custom_body_class',
		'custom_classes_sanitize_meta',
		'custom_classes_auth_post_meta'
	);

	/* Register custom post class meta. */
	register_meta(
		'post',
		'_custom_post_class',
		'custom_classes_sanitize_meta',
		'custom_classes_auth_post_meta'
	);
}

/**
 * Checks if viewing a single post and if the post has a custom body class registered.  If so, it adds the class 
 * to the array of body classes.
 *
 * @since 0.1.0
 * @param array $classes Array of HTML classes used in the <body> tag.
 * @return array $classes
 */
function custom_classes_body_class( $classes ) {

	if ( is_singular() ) {
		$custom_class = get_post_meta( get_queried_object_id(), '_custom_body_class', true );

		if ( !empty( $custom_class ) )
			$classes[] = sanitize_html_class( $custom_class );
	}

	return $classes;
}

/**
 * Checks if a post has a custom post class and adds it to the post wrapper element's classes.
 *
 * @since 0.1.0
 * @param array $classes Array of HTML classes used in the post wrapper.
 * @param string $class Custom class added by the user.
 * @param int $post_id The ID of the post currently being shown.
 * @return array $classes
 */
function custom_classes_post_class( $classes, $class, $post_id ) {

	$custom_class = get_post_meta( $post_id, '_custom_post_class', true );

	if ( !empty( $custom_class ) )
		$classes[] = sanitize_html_class( $custom_class );

	return $classes;
}

/**
 * Callback function for sanitizing the meta value when add_post_meta() or update_post_meta() are called.
 *
 * @since 0.1.0
 * @param string $meta_value The custom body or post class.
 * @param string $meta_key The current meta key ('_custom_body_class' or '_custom_post_class').
 * @param string $meta_type The type of meta (post, comment, user, etc.).
 * @return string $meta_value
 */
function custom_classes_sanitize_meta( $meta_value, $meta_key, $meta_type ) {
	return sanitize_html_class( $meta_value );
}

/**
 * Checks if the user can edit the post meta.  By default, any user who can edit the post can also edit its meta.  To
 * change this, add a filter on the "auth_post_meta_{$meta_key}" hook.
 *
 * @since 0.1.0
 * @param bool $allowed Whether the user can edit the meta. True | False.
 * @param string $meta_key The current meta key being checked against.
 * @param string $post_id The current post being edited.
 * @param string $cap The capability being checked (edit_post_meta, add_post_meta, delete_post_meta).
 * @param array $caps An array of capabilities the user must have to edit the meta.
 * @return bool $allowed
 */
function custom_classes_auth_post_meta( $allowed, $meta_key, $post_id, $user_id, $cap, $caps ) {

	if ( '_custom_body_class' == $meta_key || '_custom_post_class' == $meta_key ) {

		$post_type = get_post_type_object( get_post_type( $post_id ) );

		if ( user_can( $user_id, $post_type->cap->edit_post, $post_id ) )
			$allowed = true;
	}

	return $allowed;
}

/**
 * Adds the custom classes meta box to the edit post screen for any post type that supports it and if the user can 
 * edit the post meta.
 *
 * @since 0.1.0
 * @param string $post_type The post type of the current post.
 * @param object $post The current post object.
 */
function custom_classes_add_meta_boxes( $post_type, $post ) {

	if ( post_type_supports( $post_type, 'custom-classes' ) && ( current_user_can( 'edit_post_meta', $post->ID, '_custom_body_class' ) || current_user_can( 'edit_post_meta', $post->ID, '_custom_post_class' ) ) )
		add_meta_box( 'custom-classes', __( 'Classes', 'custom-classes' ), 'custom_classes_meta_box', $post_type, 'side', 'default' );
}

/**
 * Displays the custom classes meta box with input fields for the body and post classes.
 *
 * @since 0.1.0
 * @param object $post The current post object.
 * @param array $metabox An array of arguments for the metabox.
 */
function custom_classes_meta_box( $post, $metabox ) { ?>

	<?php wp_nonce_field( basename( __FILE__ ), 'custom-classes-nonce' ); ?>

	<?php if ( current_user_can( 'edit_post_meta', $post->ID, '_custom_body_class' ) ) { ?>
		<p>
			<label for="custom-body-class"><?php _e( 'Body Class', 'custom-classes' ); ?></label>
			<input type="text" name="custom-body-class" id="custom-body-class" value="<?php echo esc_attr( get_post_meta( $post->ID, '_custom_body_class', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
		</p>
	<?php } ?>

	<?php if ( current_user_can( 'edit_post_meta', $post->ID, '_custom_post_class' ) ) { ?>
		<p>
			<label for="custom-post-class"><?php _e( 'Post Class', 'custom-classes' ); ?></label>
			<input type="text" name="custom-post-class" id="custom-post-class" value="<?php echo esc_attr( get_post_meta( $post->ID, '_custom_post_class', true ) ); ?>" size="30" tabindex="30" style="width: 99%;" />
		</p>
	<?php }
}

/**
 * Saves the custom classes post meta when the 'save_post' hook is fired.
 *
 * @since 0.1.0
 * @param int $post_id The ID of the post being saved.
 * @param array $post An array of the post object.
 */
function custom_classes_save_meta( $post_id, $post ) {

	/* Verify the nonce before proceeding. */
	if ( !isset( $_POST['custom-classes-nonce'] ) || !wp_verify_nonce( $_POST['custom-classes-nonce'], basename( __FILE__ ) ) )
		return $post_id;

	/* Add the posted metadata to an array. */
	$metadata = array(
		'_custom_body_class' => $_POST['custom-body-class'],
		'_custom_post_class' => $_POST['custom-post-class']
	);

	/* Loop through the posted meta and add, update, or delete. */
	foreach ( $metadata as $meta_key => $new_meta_value ) {

		/* Get the meta value of the custom field key. */
		$meta_value = get_post_meta( $post_id, $meta_key, true );

		/* If there is no new meta value but an old value exists, delete it. */
		if ( current_user_can( 'delete_post_meta', $post_id, $meta_key ) && '' == $new_meta_value && $meta_value )
			delete_post_meta( $post_id, $meta_key, $meta_value );

		/* If a new meta value was added and there was no previous value, add it. */
		elseif ( current_user_can( 'add_post_meta', $post_id, $meta_key ) && $new_meta_value && '' == $meta_value )
			add_post_meta( $post_id, $meta_key, $new_meta_value, true );

		/* If the old layout doesn't match the new layout, update the post meta. */
		elseif ( current_user_can( 'edit_post_meta', $post_id, $meta_key ) && $meta_value !== $new_meta_value )
			update_post_meta( $post_id, $meta_key, $new_meta_value );
	}
}

?>