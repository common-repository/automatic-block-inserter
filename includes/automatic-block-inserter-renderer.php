<?php
/**
 * Renderer.
 *
 * @package AutomaticBlockInserter
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access' );
}
/**
 * Handles the rendering functionality.
 */
class ABISP_Automatic_Block_Inserter_Renderer {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp', array( $this, 'initialise_renderers' ) );
	}

	/**
	 * Initialises the rendering process.
	 *
	 * @return void
	 */
	public function initialise_renderers() {

		$current_post_type = get_post_type();

		// Step 1: Getting all reusable blocks.
		$reusable_blocks = get_posts(
			array(
				'per_page'  => -1,
				'post_type' => ABISP_Automatic_Block_Inserter_Core::$post_type,
			)
		);

		// Checking if working on a singular template.
		if ( ! is_singular() ) {
			return;
		}

		// Step 2: Initializing renderers based on the location type.
		foreach ( $reusable_blocks as $reusable_block ) {

			$position      = ABISP_Automatic_Block_Inserter_Position::get_position( $reusable_block );
			$location_type = isset( $position['locationType'] ) ? $position['locationType'] : null;

			/**
			 * Checking if the block requires this post type.
			 */
			$required_post_type_for_block = isset( $position['postType'] ) && '' !== $position['postType'] ? $position['postType'] : $current_post_type;

			// Exiting if the user plan allows CPT (if the cpt is used).
			if ( ! abisp_is_post_type_built_in( $required_post_type_for_block ) && ! automatic_block_inserter_fs()->can_use_premium_code() ) {
				continue;
			}

			if ( $current_post_type !== $required_post_type_for_block ) {
				continue;
			}

			// Skipping if cannot find type. Something's wrong.
			if ( is_null( $location_type ) ) {
				continue;
			}

			switch ( $location_type ) {
				case 'post':
					new ABISP_Automatic_Block_Inserter_Post_Area_Renderer( $reusable_block, $position );
					break;

				case 'block':
					new ABISP_Automatic_Block_Inserter_Block_Type_Renderer( $reusable_block, $position );
			}

			/**
			 * Handles the post processing of the blocks after they're rendered to make sure
			 * correct inline CSS are loaded from `wp_style_engine`.
			 */
			add_filter(
				'the_content',
				function( $content ) {

					/**
					 * Needs to check if the post is using classic editor, and reapply the automatic `p` generation.
					 *
					 * Note: The way we detect the classic editor here, is by checking if the blocks are parse-able (which will only be the case in gutenberg).
					 */
					$blocks = parse_blocks( get_post()->post_content );

					$is_classic_editor = 1 === count( $blocks ) && is_null( $blocks[0]['blockName'] );

					$content = do_blocks( $content );

					/**
					 * Converting the newlines into `p`.
					 */
					if ( $is_classic_editor ) {
						$content = wpautop( $content );
					}

					return $content;
				},
				10
			);
		}

	}

};

new ABISP_Automatic_Block_Inserter_Renderer();
