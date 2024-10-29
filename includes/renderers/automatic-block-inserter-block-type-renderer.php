<?php
/**
 * Block Type Renderer.
 *
 * @package AutomaticBlockInserter
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access' );
}
/**
 * Handles the rendering functionality of block type.
 */
class ABISP_Automatic_Block_Inserter_Block_Type_Renderer {

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
	 * Offset Count.
	 *
	 * Note: This keeps track of the actual block offset.
	 *
	 * @var int
	 */
	private $offset_count = 1;

	/**
	 * Tracks if the block is rendered to the location.
	 *
	 * Note: This is only intended to be used internally.
	 *
	 * @var bool
	 */
	private $is_rendered = false;

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

		$this->mark_content_boundary();
		$this->init();
	}

	/**
	 * Counts the total instances of the block on the current post.
	 *
	 * @param string $location - Block type name.
	 * @param bool   $nested - Will count the nested block types if true.
	 *
	 * @return int - Total number of block occurence.
	 */
	private function count_blocks( $location, $nested ) {

		$post_content = get_the_content();
		$blocks       = parse_blocks( $post_content );

		return abisp_count_block_types( $blocks, $location, $nested );
	}

	/**
	 * A function that adds helpful hooks/filters that creates a global variable within the WordPress post content.
	 *
	 * This allows us to differentiate a block if it's rendered within the post content or not.
	 *
	 * @return void
	 */
	private function mark_content_boundary() {
		add_filter(
			'the_content',
			function( $content ) {
				$GLOBALS['abi_is_content'] = true;
				return $content;
			},
			-1,
			1
		);

		add_filter(
			'the_content',
			function( $content ) {

				if ( isset( $GLOBALS['abi_is_content'] ) ) {
					unset( $GLOBALS['abi_is_content'] );
				}

				return $content;
			},
			999,
			1
		);
	}

	/**
	 * Initialises the renderer.
	 *
	 * @return void
	 */
	public function init() {
		$placement    = isset( $this->position['placement'] ) ? $this->position['placement'] : 'before';
		$location     = isset( $this->position['location'] ) ? $this->position['location'] : null;
		$offset       = isset( $this->position['offset'] ) ? (int) $this->position['offset'] : null;
		$allow_nested = isset( $this->position['allowNested'] ) ? $this->position['allowNested'] : false;

		// Skipping if the location or offset is null.
		if ( is_null( $location ) || is_null( $offset ) ) {
			return;
		}

		// Defaulting to true in the free version.
		if ( ! automatic_block_inserter_fs()->can_use_premium_code() ) {
			$allow_nested = true;
		}

		$is_reversed = $offset < 0;

		/**
		 * Setting the offset based on the total count of the block in order to count from bottom.
		 *
		 * Note: Dis-allowing inserting the reversed offset block if the plugin is not premium.
		 */
		if ( $is_reversed ) {

			if ( automatic_block_inserter_fs()->can_use_premium_code() ) {
				$calculated_offset = $this->count_blocks( $location, $allow_nested ) - abs( $offset );
				$offset            = $calculated_offset;

				// Allowing the count to start from 0.
				$this->offset_count = 0;
			} else {
				return;
			}
		}

		add_filter(
			'render_block_data',
			/**
			 * A utility hook that marks the block as nested which is later used in the renderer below.
			 *
			 * Note: Doing it from here, since we cannot access parent block from the hook below.
			 *
			 * @param array $parsed_block - The block being rendered.
			 * @param array $source_block - An un-modified copy of $parsed_block, as it appeared in the source content.
			 * @param \WP_Block $parent_block - The parent block.
			 *
			 * @return array - Modified parsed block.
			 */
			function( $parsed_block, $source_block, $parent_block ) {
				if ( $parent_block instanceof WP_Block ) {
					$parsed_block['abi_is_nested'] = true;
				}
				return $parsed_block;
			},
			10,
			3
		);

		add_filter(
			'render_block_' . $location,
			/**
			 * Maybe Renders the block content merged with the reusable block content if all conditions are satisfied.
			 *
			 * @param string $block_content - Current Block Content.
			 * @param array  $block - Parsed block.
			 * @param \WP_Block  $block_instance - Block instance.
			 *
			 * @return string - Block content.
			 */
			function( $block_content, $block, $block_instance ) use ( $offset, $placement, $allow_nested ) {

				global $abi_is_content;

				if ( ! $abi_is_content || ! in_the_loop() ) {
					return $block_content;
				}

				$is_nested_block = isset( $block['abi_is_nested'] );

				if ( ! $allow_nested && $is_nested_block ) {
					return $block_content;
				}

				// Step 1: Skipping if the block is inserted from FSE (we can check that by verifying the global $post object).
				$current_post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

				if ( is_null( $current_post ) || $this->is_rendered ) {
					return $block_content;
				}

				$is_offset_matched = $this->offset_count === $offset;

				if ( $is_offset_matched ) {
					$this->is_rendered = true;
					return abisp_prepend_or_append( $this->block->post_content, $block_content, $placement );
				}

				// Incrementing offset for the next block.
				$this->offset_count++;
				return $block_content;
			},
			10,
			3
		);
	}

};

