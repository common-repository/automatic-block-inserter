<?php
/**
 * Assets.
 *
 * @package AutomaticBlockInserter
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access' );
}

/**
 * Handles the necessary assets loading
 */
class ABISP_Automatic_Block_Inserter_Assets {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'load_editor_assets' ) );
	}

	/**
	 * Provides an asset metadata.
	 *
	 * @param string $name - Asset name.
	 * @return array - Metadata.
	 */
	public static function get_metadata( $name ) {
		// Used in case of invalid path.
		$default_metadata = array(
			'version'      => 'unknown',
			'dependencies' => array(),
		);

		// Check 1: Checking if the given parametre is actually an string.
		if ( ! is_string( $name ) ) {
			return $default_metadata;
		}

		$asset_path = ABISP_DIR_PATH . 'dist/' . $name . '.asset.php';

		return is_readable( $asset_path ) ? require $asset_path : $default_metadata;
	}

	/**
	 * Load Assets.
	 *
	 * @return void
	 */
	public function load_editor_assets() {

		$metadata = static::get_metadata( 'app' );
		$post     = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

		if ( ! is_null( $post ) && ABISP_Automatic_Block_Inserter_Core::$post_type === $post->post_type ) {
			wp_enqueue_script(
				'automatic-block-inserter-app',
				ABISP_PLUGIN_URL . 'dist/app.js',
				$metadata['dependencies'],
				$metadata['version'],
				true
			);

			wp_enqueue_style(
				'automatic-block-inserter-app',
				ABISP_PLUGIN_URL . 'dist/app.css',
				array(),
				filemtime( ABISP_DIR_PATH . 'dist/app.css' )
			);

			wp_localize_script(
				'automatic-block-inserter-app',
				'automaticBlockInserter',
				array(
					'supportedPostTypes' => abisp_get_supported_post_types(),
					'isPremium'          => automatic_block_inserter_fs()->can_use_premium_code() === true ? 'true' : 'false',
				)
			);
		}

	}
};

new ABISP_Automatic_Block_Inserter_Assets();
