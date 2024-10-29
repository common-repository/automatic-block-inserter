<?php
/**
 * Position.
 *
 * @package AutomaticBlockInserter
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access' );
}
/**
 * Handles the registeration of positions.
 */
class ABISP_Automatic_Block_Inserter_Position {

	/**
	 * Post Type Slug.
	 *
	 * @var string
	 */
	public static $meta_key = 'automatic-block-position';

	/**
	 * Default Position.
	 *
	 * @var array
	 */
	public static $default_position = array(
		'locationType' => 'post',
		'placement'    => 'before',
		'location'     => 'post-content',
		'offset'       => 1,
		'postType'     => '',
		'allowNested'  => true,
	);

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'automatic_block_inserter_after_post_type_register', array( $this, 'register' ) );
	}

	/**
	 * Provides the position of the given reusable block.
	 *
	 * @param \WP_Post|int $post - Post, or post id.
	 *
	 * @return array|false - Position if found, otherwise false.
	 */
	public static function get_position( $post ) {

		if ( is_int( $post ) ) {
			$post = get_post( $post );
		}

		// Check 1: Checking if the post is actually valid.
		if ( is_null( $post ) || ! ( $post instanceof \WP_Post ) ) {
			return false;
		}

		$position = get_post_meta( $post->ID, static::$meta_key, true );

		// Falling back to the default position.
		if ( '' === $position ) {
			$position = static::$default_position;
		} else {
			$position = json_decode( $position, true );
		}

		return $position;
	}

	/**
	 * Registerations.
	 *
	 * @return void
	 */
	public function register() {
		register_post_meta(
			ABISP_Automatic_Block_Inserter_Core::$post_type,
			static::$meta_key,
			array(
				'default'      => wp_json_encode( static::$default_position ),
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);
	}
};

new ABISP_Automatic_Block_Inserter_Position();
