<?php
/**
 * Licensing.
 *
 * @package AutomaticBlockInserter
 */

/**
 * Adds in the following missing licensing functionalities from freemius:
 *
 * 1. Adds a way to re-optin the user from the wp plugin menu screen.
 * 2. Customises the optin screen with additional message.
 * 3. Displays a notice to unregistered users.
 */
class ABISP_Licensing {

	/**
	 * Premium Checkout Link.
	 *
	 * @var string
	 */
	private $premium_checkout_link = 'https://www.smallplugins.com/get-started/automatic-block-inserter/';

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {

		if ( automatic_block_inserter_fs()->is_premium() ) {
			add_action( 'init', array( $this, 'handle_optin_request' ), 10 );
			add_filter( 'admin_notices', array( $this, 'render_optin_notice' ), 10, 0 );

			automatic_block_inserter_fs()->add_filter( 'connect-header_on-update', array( $this, 'get_optin_header' ) );
			automatic_block_inserter_fs()->add_filter( 'connect_message_on_update', array( $this, 'get_optin_message' ) );

			automatic_block_inserter_fs()->add_filter( 'connect-header', array( $this, 'get_optin_header' ) );
			automatic_block_inserter_fs()->add_filter( 'connect_message', array( $this, 'get_optin_message' ) );
		}

		if ( ! automatic_block_inserter_fs()->is_premium() ) {
			add_filter( 'plugin_action_links_' . ABISP_PLUGIN_BASE_NAME, array( $this, 'add_get_premium_link' ), 10, 1 );
		}

	}

	/**
	 * Provides the custom opt-in header.
	 *
	 * @param string $message - Current opt-in header.
	 *
	 * @return string
	 */
	public function get_optin_header( $header ) {
		return sprintf( '<h2>%1$s</h2>', __( 'Get access to premium features', 'automatic-block-inserter' ) );
	}

	/**
	 * Provides the custom opt-in message.
	 *
	 * @param string $message - Current opt-in message.
	 *
	 * @return string
	 */
	public function get_optin_message( $message ) {
		return __( 'In order to use premium features, we need to authenticate your license. This requires our plugin to have certain access to your site (which you can review by clicking on the button below, explaining what you’re allowing). If you skip this, you’ll be limited to the free features of the plugin and we’ll display a non-dismissible admin notice about opting in so you can easily re-start this process at a later date.', 'automatic-block-inserter' );
	}

	/**
	 * Adds a custom link in the plugin menu screen to the plugin checkout page.
	 *
	 * @param array $links - Existing plugin links.
	 *
	 * @return array - Modified plugin links.
	 */
	public function add_get_premium_link( $links ) {

		$get_premium_link = sprintf(
			'<a href="%2$s" target="_blank">%1$s</a>',
			__( 'Get Premium', 'automatic-block-inserter' ),
			$this->premium_checkout_link
		);

		$links[] = $get_premium_link;

		return $links;
	}

	/**
	 * A method to check if the user is requesting another optin.
	 *
	 * @return bool - True if requesting, otherwise false.
	 */
	private function is_requesting_optin() {
		return isset( $_GET['abi-request-opt-in'] );
	}

	/**
	 * Handles the optin request.
	 *
	 * @return void
	 */
	public function handle_optin_request() {
		$is_requesting = $this->is_requesting_optin();

		if ( $is_requesting ) {
			automatic_block_inserter_fs()->connect_again();
		}
	}

	/**
	 * Renders an optin notice if the user is not registered.
	 *
	 * @return void
	 */
	public function render_optin_notice() {

		$is_registered = automatic_block_inserter_fs()->is_registered();

		if ( ! $is_registered ) {
			?>
				<div class="notice notice-warning">
					<p>
						<?php _e( 'Thank you for installing <strong>Automatic Block Inserter</strong>. Please complete the opt-in process to continue using the plugin.', 'automatic-block-inserter' ); ?>
						<a href="?abi-request-opt-in=true">
							<?php _e( 'Complete Opt-In', 'automatic-block-inserter' ); ?>
						</a>
					</p>
				</div>
			<?php
		}

	}
}

new ABISP_Licensing();
