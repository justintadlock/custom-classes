<?php
/**
 * Handles the functionality on the edit post screen.
 *
 * @package   CustomClasses
 * @version   1.0.0
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2012-2017, Justin Tadlock
 * @link      https://themehybrid.com/plugins/custom-classes
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-1.0.html
 */

namespace Custom_Classes;

/**
 * Post edit screen class.
 *
 * @since  1.0.0
 * @access public
 */
final class Post_Edit {

	/**
	 * Constructor method.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function __construct() {}

	/**
	 * Sets up necessary actions.
	 *
	 * @since  1.0.0
	 * @access private
	 * @return void
	 */
	private function setup_actions() {

		add_action( 'load-post.php',     array( $this, 'load' ) );
		add_action( 'load-post-new.php', array( $this, 'load' ) );
	}

	/**
	 * Runs when the page is loaded.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function load() {

		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

		add_action( 'save_post', array( $this, 'save' ) );
	}

	/**
	 * Adds the custom classes meta box to the edit post screen for any post type
	 * that supports it.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  string  $post_type
	 * @return void
	 */
	public function add_meta_boxes( $post_type ) {

		if ( post_type_supports( $post_type, 'custom-classes' ) )
			add_meta_box( 'custom-classes', __( 'Classes', 'custom-classes' ), array( $this, 'meta_box' ), $post_type, 'side', 'default' );
	}

	/**
	 * Displays the custom classes meta box with input fields for the body and post classes.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $post
	 * @param  array   $metabox
	 * @return void
	 */
	function meta_box( $post, $metabox ) { ?>

		<?php wp_nonce_field( basename( __FILE__ ), 'cc-post-classes-nonce' ); ?>

		<p>
			<label>
				<?php esc_html_e( 'Body Class', 'custom-classes' ); ?>
				<input type="text" class="widefat" name="custom-body-class" value="<?php echo esc_attr( get_post_meta( $post->ID, '_custom_body_class', true ) ); ?>" />
			</label>
		</p>

		<p>
			<label>
				<?php esc_html_e( 'Post Class', 'custom-classes' ); ?>
				<input type="text" class="widefat" name="custom-post-class" value="<?php echo esc_attr( get_post_meta( $post->ID, '_custom_post_class', true ) ); ?>" />
			</label>
		</p>
	<?php }

	/**
	 * Saves the custom classes post meta when the 'save_post' hook is fired.
	 *
	 * @since  1.0.0
	 * @param  int   $post_id
	 * @return void
	 */
	public function save( $post_id ) {

		if ( ! isset( $_POST['cc-post-classes-nonce'] ) || ! wp_verify_nonce( $_POST['cc-post-classes-nonce'], basename( __FILE__ ) ) )
			return;

		$metadata = array(
			'_custom_body_class' => isset( $_POST['custom-body-class'] ) ? sanitize_html_class( $_POST['custom-body-class'] ) : '',
			'_custom_post_class' => isset( $_POST['custom-post-class'] ) ? sanitize_html_class( $_POST['custom-post-class'] ) : ''
		);

		foreach ( $metadata as $meta_key => $new_meta_value ) {

			$meta_value = get_post_meta( $post_id, $meta_key, true );

			if ( '' == $new_meta_value && $meta_value )
				delete_post_meta( $post_id, $meta_key, $meta_value );

			elseif ( $meta_value !== $new_meta_value )
				update_post_meta( $post_id, $meta_key, $new_meta_value );
		}
	}

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return object
	 */
	public static function get_instance() {

		static $instance = null;

		if ( is_null( $instance ) ) {
			$instance = new self;
			$instance->setup_actions();
		}

		return $instance;
	}
}
