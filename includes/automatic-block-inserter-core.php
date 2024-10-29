<?php
/**
 * Core.
 *
 * @package AutomaticBlockInserter
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access' );
}

/**
 * Handles the Core functionality and registeration.
 */
class ABISP_Automatic_Block_Inserter_Core {

	/**
	 * Post Type Slug.
	 *
	 * @var string
	 */
	public static $post_type = 'automatic-block';

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Registerations.
	 *
	 * @return void
	 */
	public function register() {
		$labels = array(
			'name'                  => _x( 'Automatic Blocks', 'Post type general name', 'automatic-block-inserter' ),
			'singular_name'         => _x( 'Automatic Block', 'Post type singular name', 'automatic-block-inserter' ),
			'menu_name'             => _x( 'Automatic Blocks Inserter', 'Admin Menu text', 'automatic-block-inserter' ),
			'name_admin_bar'        => _x( 'Automatic Blocks', 'Add New on Toolbar', 'automatic-block-inserter' ),
			'add_new'               => __( 'Add New', 'automatic-block-inserter' ),
			'add_new_item'          => __( 'Add New Block', 'automatic-block-inserter' ),
			'new_item'              => __( 'New Block', 'automatic-block-inserter' ),
			'edit_item'             => __( 'Edit Block', 'automatic-block-inserter' ),
			'view_item'             => __( 'View Block', 'automatic-block-inserter' ),
			'all_items'             => __( 'All Blocks', 'automatic-block-inserter' ),
			'search_items'          => __( 'Search Blocks', 'automatic-block-inserter' ),
			'parent_item_colon'     => __( 'Parent Blocks:', 'automatic-block-inserter' ),
			'not_found'             => __( 'No blocks found.', 'automatic-block-inserter' ),
			'not_found_in_trash'    => __( 'No blocks found in Trash.', 'automatic-block-inserter' ),
			'featured_image'        => _x( 'Block Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'automatic-block-inserter' ),
			'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'automatic-block-inserter' ),
			'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'automatic-block-inserter' ),
			'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'automatic-block-inserter' ),
			'archives'              => _x( 'Block archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'automatic-block-inserter' ),
			'insert_into_item'      => _x( 'Insert into block', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'automatic-block-inserter' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this block', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'automatic-block-inserter' ),
			'filter_items_list'     => _x( 'Filter block list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'automatic-block-inserter' ),
			'items_list_navigation' => _x( 'Blocks list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'automatic-block-inserter' ),
			'items_list'            => _x( 'Blocks list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'automatic-block-inserter' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'publicly_queryable' => false,
			'show_ui'            => true,
			'menu_icon'          => 'dashicons-layout',
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => static::$post_type ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => array( 'title', 'editor', 'author', 'custom-fields' ),
		);

		register_post_type( static::$post_type, $args );

		/**
		 * Hook that fires after the registeration of core post type.
		 *
		 * @since 1.0.0
		 */
		do_action( 'automatic_block_inserter_after_post_type_register' );
	}
};

new ABISP_Automatic_Block_Inserter_Core();
