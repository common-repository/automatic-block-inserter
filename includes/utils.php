<?php
/**
 * Utils.
 *
 * @package AutomaticBlockInserter
 */

/**
 * Prepends or appends the given content based on the argument.
 *
 * @param string $content - Content to merge.
 * @param string $target - The target content to merge on.
 * @param string $placement - Can either be 'before' or 'after'.
 *
 * @return string - Merged content.
 */
function abisp_prepend_or_append( $content, $target, $placement ) {

	// Check 1: Type validation.
	if ( ! is_string( $content ) || ! is_string( $target ) || ! is_string( $placement ) ) {
		return '';
	}

	// Check 2: Making sure the placement is expected.
	$expected_placements = array( 'before', 'after' );

	if ( ! in_array( $placement, $expected_placements, true ) ) {
		$placement = 'before';
	}

	$new_content = '';

	switch ( $placement ) {
		case 'before':
			$new_content = $content . $target;
			break;

		case 'after':
			$new_content = $target . $content;
			break;
	}

	return $new_content;
}

/**
 * Provides a list of supported post types for the automatic block inserter.
 *
 * Note: The post type needs to be public in order to be supported.
 */

function abisp_get_supported_post_types() {
	$post_types = array_values( get_post_types() );

	/**
	 * Allows the modification of supported post types.
	 *
	 * @since 1.0.1
	 */
	$post_types = apply_filters( 'automatic_block_inserter_supported_post_types', $post_types );

	// Removing post types that are not public.
	$post_types = array_filter(
		$post_types,
		function ( $post_type ) {
			$is_viewable = is_post_type_viewable( $post_type );
			$is_allowed  = $is_viewable;

			if ( ! automatic_block_inserter_fs()->can_use_premium_code() ) {
				$is_allowed = $is_viewable && abisp_is_post_type_built_in( $post_type );
			}

			return $is_allowed;
		}
	);

	return array_values( $post_types );
}

/**
 * Provides the count for the given block type in the provided post content.
 *
 * @param array[] $blocks - List of parsed blocks.
 * @param string  $block_type - The block type to count.
 * @param bool    $nested - If need to count the nested blocks.
 *
 * @return int - Number of block types.
 */
function abisp_count_block_types( $blocks, $block_type, $nested ) {

	$count = 0;

	// Iterating sequentially if the nested block type count is not required.
	foreach ( $blocks as $block ) {
		$current_block_type = isset( $block['blockName'] ) ? $block['blockName'] : '';

		if ( $current_block_type === $block_type ) {
			$count++;
		}

		// Adding the nested count if needed.
		if ( $nested ) {
			$inner_blocks = array( $block['innerBlocks'] ) ? $block['innerBlocks'] : array();

			// Recursively calling the method again to count nested block types.
			$count += abisp_count_block_types( $inner_blocks, $block_type, $nested );
		}
	}

	return $count;
}

/**
 * Checks if the given post type is built in or not.
 *
 * @param string $post_type - Post type slug.
 *
 * @return bool - True if built in, otherwise false.
 */
function abisp_is_post_type_built_in( $post_type ) {
	$built_in_post_types = array( 'post', 'page', 'attachment' );
	return in_array( $post_type, $built_in_post_types, true );
}
