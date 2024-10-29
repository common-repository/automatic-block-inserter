<?php
/**
 * Post Area Renderer.
 *
 * @package AutomaticBlockInserter
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access' );
}
/**
 * Handles the rendering functionality of post area location types.
 */
class ABISP_Automatic_Block_Inserter_Post_Area_Renderer {

	/**
	 * Position.
	 *
	 * @var array
	 */
	public $position;

	/**
	 * Reusable block post.
	 *
	 * @var WP_Post
	 */
	public $block;

	/**
	 * Constructor.
	 *
	 * @param WP_Post $block - Reusable block post.
	 * @param array   $position - Required position.
	 *
	 * @return void
	 */
	public function __construct( $block, $position ) {
		$this->block    = $block;
		$this->position = $position;

		$this->init();
	}

	/**
	 * Initialises the renderer.
	 *
	 * @return void
	 */
	public function init() {
		$location = isset( $this->position['location'] ) ? $this->position['location'] : null;

		// Skipping if the location is null.
		if ( is_null( $location ) ) {
			return;
		}

		// Location 1: Post Content.
		if ( 'post-content' === $location ) {
			add_filter(
				'the_content',
				array( $this, 'post_location_handler' ),
				1
			);
		}
	}

	/**
	 * Handles rendering in the post content area.
	 *
	 * @param string $post_content - Current post content.
	 *
	 * @return string - Merged content.
	 */
	public function post_location_handler( $post_content ) {

		$is_public_post_type = is_post_type_viewable( get_post_type() );

		if ( ! $is_public_post_type || defined( 'REST_REQUEST' ) || ! in_the_loop() ) {
			return $post_content;
		}

		$placement = isset( $this->position['placement'] ) ? $this->position['placement'] : 'before';

		$post_content = abisp_prepend_or_append(
			$this->block->post_content,
			$post_content,
			$placement
		);

		/**
		 * Removing the filter/hook once the job is done to avoid recursive calls
		 * when `the_content` hook is called again in the post content.
		 *
		 * Note: This fixes the memory issue with dynamic connector block.
		 */
		remove_filter( 'the_content', array( $this, 'post_location_handler' ), 1 );

		return $post_content;
	}

};

