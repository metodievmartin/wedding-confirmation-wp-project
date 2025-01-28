<?php

class WCCF_Info_Card_CPT {
	private $cpt_slug;

	public function __construct( $cpt_slug ) {
		$this->cpt_slug = $cpt_slug;

		$this->_initialise();
	}

	// ========== Init ==========

	private function _initialise() {
		//	Creates Custom Post Type
		add_action( 'init', array( $this, 'register_post_type' ) );
	}

	// ========== Setup Methods (Hook callbacks) ==========

	function register_post_type() {
		$label_args = array(
			'name'                     => __( 'Info Cards', 'wccf-domain' ),
			'singular_name'            => __( 'Info Card', 'wccf-domain' ),
			'add_new'                  => __( 'Add New', 'wccf-domain' ),
			'add_new_item'             => __( 'Add New Info Card', 'wccf-domain' ),
			'edit_item'                => __( 'Edit Info Card', 'wccf-domain' ),
			'new_item'                 => __( 'New Info Card', 'wccf-domain' ),
			'view_item'                => __( 'View Info Card', 'wccf-domain' ),
			'view_items'               => __( 'View Info Cards', 'wccf-domain' ),
			'search_items'             => __( 'Search Info Cards', 'wccf-domain' ),
			'not_found'                => __( 'No info cards found.', 'wccf-domain' ),
			'not_found_in_trash'       => __( 'No info cards found in trash.', 'wccf-domain' ),
			'all_items'                => __( 'All Info Cards', 'wccf-domain' ),
			'archives'                 => __( 'Info Card Archives', 'wccf-domain' ),
			'filter_items_list'        => __( 'Filter Info Cards list', 'wccf-domain' ),
			'items_list_navigation'    => __( 'Info Cards list navigation', 'wccf-domain' ),
			'items_list'               => __( 'Info Cards list', 'wccf-domain' ),
			'item_published'           => __( 'Info Card published.', 'wccf-domain' ),
			'item_published_privately' => __( 'Info Card published privately.', 'wccf-domain' ),
			'item_reverted_to_draft'   => __( 'Info Card reverted to draft.', 'wccf-domain' ),
			'item_scheduled'           => __( 'Info Card scheduled.', 'wccf-domain' ),
			'item_updated'             => __( 'Info Card updated.', 'wccf-domain' ),
		);

		$cpt_args = array(
			'supports'     => array(
				'title',
				'editor',
				'revisions',
				'custom-fields',
				'page-attributes'
			),
			'menu_icon'    => 'dashicons-info',
			'rewrite'      => array( 'slug' => 'info-cards' ),
			'has_archive'  => true,
			'public'       => true,
//			'publicly_queryable' => true,
//			'show_ui'            => true,
//			'show_in_menu'       => true,
//			'show_in_nav_menus'  => true,
			'show_in_rest' => true,
//			'can_export'         => true,
			'labels'       => $label_args,
		);

		register_post_type( $this->cpt_slug, $cpt_args );
	}
}
