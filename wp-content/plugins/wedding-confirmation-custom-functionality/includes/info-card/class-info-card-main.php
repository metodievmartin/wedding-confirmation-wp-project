<?php

class WCCF_Info_Card_Main {
	const INFO_CARD_CPT_SLUG = 'wccf-info-card';
	const INFO_CARDS_PER_PAGE = 6;

	private $info_card_cpt;

	// ========== Constructor ==========

	public function __construct() {
		$this->_initialise();
	}

	// ========== Init ==========

	private function _initialise() {
		//	Init Custom Post Types
		wccf_include( 'includes/info-card/class-info-card-cpt.php' );
		$info_card_cpt = new WCCF_Info_Card_CPT( self::INFO_CARD_CPT_SLUG );
	}

	function get_info_cards_query( $query_args = array() ) {
		// Set default arguments
		$query_defaults = array(
			'posts_per_page' => self::INFO_CARDS_PER_PAGE,
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
		);

		$parsed_query_args = wp_parse_args( $query_args, $query_defaults );

		// default overwrite for info card post type
		$parsed_query_args['post_type'] = self::INFO_CARD_CPT_SLUG;

		// Perform the query
		return new WP_Query( $parsed_query_args );
	}
}