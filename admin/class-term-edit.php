<?php
/**
 * Handles the functionality on the term edit screen.
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
 * Term edit screen class.
 *
 * @since  1.0.0
 * @access public
 */
final class Term_Edit {

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

		// Load on the edit tags screen.
		add_action( 'load-tags.php',      array( $this, 'load' ) );
		add_action( 'load-edit-tags.php', array( $this, 'load' ) );

		// Update term meta.
		add_action( 'create_term', array( $this, 'save' ) );
		add_action( 'edit_term',   array( $this, 'save' ) );
	}

	/**
	 * Runs when the page is loaded.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function load() {

		$screen = get_current_screen();

		// Add the form fields.
		add_action( "{$screen->taxonomy}_add_form_fields",  array( $this, 'add_form_fields'  ) );
		add_action( "{$screen->taxonomy}_edit_form_fields", array( $this, 'edit_form_fields' ) );
	}

	/**
	 * Displays the layout selector in the new term form.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function add_form_fields() { ?>

		<div class="form-field custom-body-class-wrap">

			<label for="custom-body-class"><?php esc_html_e( 'Body Class', 'custom-classes' ); ?></label>

			<?php $this->display_field(); ?>

		</div>
	<?php }

	/**
	 * Displays the layout selector on the edit term screen.
	 *
	 * @since  1.0.0
	 * @access public
	 * @return void
	 */
	public function edit_form_fields( $term ) { ?>

		<tr class="form-field custom-body-class-wrap">

			<th scope="row">
				<label for="custom-body-class"><?php esc_html_e( 'Body Class', 'custom-classes' ); ?></label>
			</th>

			<td><?php $this->display_field( $term ); ?></td>
		</tr>
	<?php }

	/**
	 * Function for outputting the radio image input fields.
	 *
	 * Note that this will most likely be deprecated in the future in favor of
	 * building an all-purpose field to be used in any form.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  object  $term
	 * @return void
	 */
	public function display_field( $term = '' ) {

		$body_class = $term ? get_term_meta( $term->term_id, '_custom_body_class', true ) : ''; ?>

		<?php wp_nonce_field( basename( __FILE__ ), 'cc-term-classes-nonce' ); ?>

		<input type="text" class="widefat" id="custom-body-class" name="custom-body-class" value="<?php echo esc_attr( $body_class ); ?>" />

	<?php }

	/**
	 * Saves the term meta.
	 *
	 * @since  1.0.0
	 * @access public
	 * @param  int     $term_id
	 * @return void
	 */
	public function save( $term_id ) {

		if ( ! isset( $_POST['cc-term-classes-nonce'] ) || ! wp_verify_nonce( $_POST['cc-term-classes-nonce'], basename( __FILE__ ) ) )
			return;

		$old_class = get_term_meta( $term_id, '_custom_body_class', true );
		$new_class = isset( $_POST['custom-body-class'] ) ? sanitize_html_class( $_POST['custom-body-class'] ) : '';

		if ( $old_class && '' === $new_class )
			delete_term_meta( $term_id, '_custom_body_class' );

		else if ( $old_class !== $new_class )
			update_term_meta( $term_id, '_custom_body_class', $new_class );
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
