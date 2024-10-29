<?php

/**
 * Plugin Name: Automatic Block Inserter
 * Description: This plugin allows you to easily insert blocks into specific post areas.
 * Author: Small Plugins
 * Author URI: https://www.smallplugins.com/
 * Version: 1.0.5
 * Requires at least: 5.8.3
 * Requires PHP: 5.7
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: automatic-block-inserter
 * Domain Path: /languages
 * Tested up to: 6.6
 *
 * @package AutomaticBlockInserter
 */
if ( !defined( 'ABSPATH' ) ) {
    die( 'No direct access' );
}
if ( !defined( 'ABISP_DIR_PATH' ) ) {
    define( 'ABISP_DIR_PATH', \plugin_dir_path( __FILE__ ) );
}
if ( !defined( 'ABISP_PLUGIN_URL' ) ) {
    define( 'ABISP_PLUGIN_URL', \plugins_url( '/', __FILE__ ) );
}
if ( !defined( 'ABISP_PLUGIN_BASE_NAME' ) ) {
    define( 'ABISP_PLUGIN_BASE_NAME', \plugin_basename( __FILE__ ) );
}
if ( !class_exists( 'ABISP_Automatic_Block_Inserter' ) ) {
    /**
     * Main plugin class
     */
    final class ABISP_Automatic_Block_Inserter {
        /**
         * Var to make sure we only load once
         *
         * @var boolean $loaded
         */
        public static $loaded = false;

        /**
         * Constructor
         *
         * @return void
         */
        public function __construct() {
            if ( !static::$loaded ) {
                static::$loaded = true;
                if ( !function_exists( 'automatic_block_inserter_fs' ) ) {
                    // Create a helper function for easy SDK access.
                    function automatic_block_inserter_fs() {
                        global $automatic_block_inserter_fs;
                        if ( !isset( $automatic_block_inserter_fs ) ) {
                            // Include Freemius SDK.
                            require_once dirname( __FILE__ ) . '/freemius/start.php';
                            $automatic_block_inserter_fs = fs_dynamic_init( array(
                                'id'             => '13825',
                                'slug'           => 'automatic-block-inserter',
                                'type'           => 'plugin',
                                'public_key'     => 'pk_ab6de007995acde299c00d59dd9c6',
                                'is_premium'     => false,
                                'premium_suffix' => 'Pro',
                                'has_addons'     => false,
                                'has_paid_plans' => true,
                                'menu'           => array(
                                    'slug'    => 'edit.php?post_type=automatic-block',
                                    'support' => false,
                                ),
                                'is_live'        => true,
                            ) );
                        }
                        return $automatic_block_inserter_fs;
                    }

                    // Init Freemius.
                    automatic_block_inserter_fs();
                    // Signal that SDK was initiated.
                    do_action( 'automatic_block_inserter_fs_loaded' );
                }
                // Utils.
                require_once ABISP_DIR_PATH . 'includes/utils.php';
                // Renderers.
                require_once ABISP_DIR_PATH . 'includes/renderers/automatic-block-inserter-post-area-renderer.php';
                require_once ABISP_DIR_PATH . 'includes/renderers/automatic-block-inserter-block-type-renderer.php';
                // Core.
                require_once ABISP_DIR_PATH . 'includes/automatic-block-inserter-core.php';
                require_once ABISP_DIR_PATH . 'includes/automatic-block-inserter-assets.php';
                require_once ABISP_DIR_PATH . 'includes/automatic-block-inserter-position.php';
                require_once ABISP_DIR_PATH . 'includes/automatic-block-inserter-renderer.php';
                // Licensing.
                require_once ABISP_DIR_PATH . 'includes/licensing/automatic-block-inserter-licensing.php';
            }
        }

    }

    new ABISP_Automatic_Block_Inserter();
}
