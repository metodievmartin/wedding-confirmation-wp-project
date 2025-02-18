<?php

class WCCF_Confirmations_Page {
	private Confirmations_DB_Service $confirmations_service;
	private string $menu_page_slug = 'wedding-confirmations';

	public function __construct( Confirmations_DB_Service $confirmations_service, $menu_page_slug = '' ) {
		$this->confirmations_service = $confirmations_service;

		if ( ! empty( $menu_page_slug ) ) {
			$this->menu_page_slug = $menu_page_slug;
		}
	}

	public function initialise() {
		add_action( 'admin_post_wccf_export', array( $this, 'handle_confirmations_export' ) );
	}

	public function get_page_slug() {
		return $this->menu_page_slug;
	}

	public function add_page_as_menu_page( array $args = [] ): string {
		$defaults = [
			'page_title' => __( 'Wedding Confirmations', 'wccf_domain' ),
			'menu_title' => __( 'Confirmations', 'wccf_domain' ),
			'capability' => 'edit_pages',
			'icon_url'   => '',
			'position'   => 25,
		];

		$args = wp_parse_args( $args, $defaults );

		return add_menu_page(
			$args['page_title'],
			$args['menu_title'],
			$args['capability'],
			$this->get_page_slug(),
			array( $this, 'render_admin_page' ),
			$args['icon_url'],
			$args['position']
		);
	}

	public function add_page_as_submenu_page( string $parent_slug, array $args = [] ): string|false {
		// Define the default arguments
		$defaults = [
			'page_title' => __( 'Wedding Confirmations', 'wccf_domain' ),
			'menu_title' => __( 'All Confirmations', 'wccf_domain' ),
			'capability' => 'edit_pages',
		];

		// Merge the provided arguments with defaults
		$args = wp_parse_args( $args, $defaults );

		// Use the arguments to create the submenu page
		return add_submenu_page(
			$parent_slug,
			$args['page_title'],
			$args['menu_title'],
			$args['capability'],
			$this->get_page_slug(),
			array( $this, 'render_admin_page' )
		);
	}

	function handle_confirmations_export() {
		// TODO: consider getting the confirmations in chunks or with an iterator
		$results = $this->confirmations_service->get_all_confirmations();

		// Generate a filename and filepath
		$filename   = 'wedding_confirmations_' . date( 'Y-m-d' ) . '.csv';
		$upload_dir = wp_upload_dir();
		$filepath   = $upload_dir['path'] . '/' . $filename;
		$fileurl    = $upload_dir['url'] . '/' . $filename;

		// Write the CSV file
		$output = fopen( $filepath, 'w' );
		fputcsv( $output, [
			'First Name',
			'Last Name',
			'Email',
			'Guests',
			'Attendance Status',
			'Additional Info'
		], ',', '"', '\\' );

		if ( ! empty( $results ) ) {
			foreach ( $results as $row ) {
				fputcsv( $output, [
					$row['first_name'],
					$row['last_name'],
					$row['email'],
					$row['num_guests'],
					$row['attendance_status'],
					$row['additional_info']
				], ',', '"', '\\' );
			}
		}

		fclose( $output );

		// Redirect to the file URL
		wp_redirect( $fileurl );
		exit;
	}

	function render_admin_page() {
		$rows_per_page = 10; // Number of rows per page
		$current_page  = isset( $_GET['paged'] ) ? max( 1, intval( $_GET['paged'] ) ) : 1;
		$offset        = ( $current_page - 1 ) * $rows_per_page;

		$search_term = isset( $_GET['search'] ) ? sanitize_text_field( $_GET['search'] ) : '';
		$order_by    = isset( $_GET['orderby'] ) ? sanitize_text_field( $_GET['orderby'] ) : 'last_name';
		$order       = isset( $_GET['order'] ) && strtolower( $_GET['order'] ) === 'desc' ? 'DESC' : 'ASC';

		// Query arguments
		$query_args = [
			'search_term' => $search_term,
			'order_by'    => $order_by,
			'order'       => $order,
			'limit'       => $rows_per_page,
			'offset'      => $offset,
		];

		// Fetch results using the service
		$results = $this->confirmations_service->get_confirmations( $query_args );

		// Fetch total count for pagination
		$total_rows = $this->confirmations_service->get_total_confirmations_count( $search_term );

		echo '<div class="wrap">';
		echo '<h1>' . __( 'Wedding Confirmations', 'wccf_domain' ) . '</h1>';

		echo '</br>';

		echo '<div class="search-and-filter-container">';

		$this->render_search_form( $search_term );

		$this->render_reset_filters_form( $order_by, $order, $search_term );

		echo '</div>';

		echo '</br>';

		$this->render_confirmations_table( $order_by, $order, $results, $total_rows, $rows_per_page, $current_page );

		$this->render_export_button( $results );
	}

	/**
	 * @param string $search_term
	 *
	 * @return void
	 */
	public function render_search_form( string $search_term ): void {
		echo '<form method="get" class="confirmations-search-form">';
		echo '<input type="hidden" name="page" value="' . $this->get_page_slug() . '" />';
		echo '<input type="text" name="search" value="' . esc_attr( $search_term ) . '" placeholder="' . __( 'First Name, Last Name or Email...', 'wccf_domain' ) . '" class="search-input" />';
		echo '<input type="submit" value="' . __( 'Search', 'wccf_domain' ) . '" class="button button-primary submit-button" />';
		echo '</form>';

		if ( ! empty( $search_term ) ) {
			echo '<form method="get">';
			echo '<input type="hidden" name="page" value="' . $this->get_page_slug() . '" />';
			echo '<input type="submit" value="' . __( 'Clear Search', 'wccf_domain' ) . '" class="button" />';
			echo '</form>';
		}
	}

	/**
	 * @param string $order_by
	 * @param string $order
	 * @param string $search_term
	 *
	 * @return void
	 */
	public function render_reset_filters_form( string $order_by, string $order, string $search_term ): void {
		if ( $order_by !== 'last_name' || $order !== 'ASC' ) {
			$order_direction = $this->get_order_arrow_symbol( $order, array(
				'asc_prefix'  => 'Ascending',
				'desc_prefix' => 'Descending'
			) );
			$order_by_label  = $this->get_order_by_label( $order_by );

			echo '<form method="get" class="reset-filters-form">';
			echo '<input type="hidden" name="page" value="' . $this->get_page_slug() . '" />';

			if ( ! empty( $search_term ) ) {
				// add the search term so that the search is preserved when clearing the filters
				echo '<input type="hidden" name="search" value="' . esc_attr( $search_term ) . '" />';
			}

			echo '<input type="submit" value="' . __( 'Reset All Filters', 'wccf_domain' ) . '" class="button" />';

			echo '<span>';
			echo '<span class="badge text-bg-secondary"> ' . esc_html( $order_by_label ) . ' </span>';
			echo '<span class="badge text-bg-secondary"> ' . $order_direction . ' </span>';
			echo '</span>';

			echo '</form>';
		}
	}

	private function get_order_arrow_symbol( $order, $labels = array() ) {
		if ( $order === 'ASC' ) {
			$arrow = '&#9650;';

			if ( ! empty( $labels['asc_prefix'] ) ) {
				$arrow = $labels['asc_prefix'] . ' ' . $arrow;
			}

			if ( ! empty( $labels['asc_postfix'] ) ) {
				$arrow = $arrow . ' ' . $labels['asc_postfix'];
			}

			return $arrow;
		}

		$arrow = '&#9660;';

		if ( ! empty( $labels['desc_prefix'] ) ) {
			$arrow = $labels['desc_prefix'] . ' ' . $arrow;
		}

		if ( ! empty( $labels['desc_postfix'] ) ) {
			$arrow = $arrow . ' ' . $labels['desc_postfix'];
		}

		return $arrow;
	}

	private function render_table_anchor_tag( $label, $order_by, $order, $applied_order_by = '' ) {
		$order_arrow = '';
		$href        = $this->wccf_build_url( [
			'orderby' => $order_by,
			'order'   => $order === 'ASC' ? 'DESC' : 'ASC',
		] );

		if ( $applied_order_by === $order_by ) {
			$order_arrow = '<span> ' . esc_html( $this->get_order_arrow_symbol( $order ) ) . '</span>';
		}

		return '<a href="' . esc_url( $href ) . '">' . esc_html( $label ) . $order_arrow . '</a>';
	}

	/**
	 * @param string $order
	 * @param array $results
	 * @param string|null $total_rows
	 * @param int $rows_per_page
	 * @param mixed $current_page
	 *
	 * @return void
	 */
	public function render_confirmations_table( string $current_order_by, string $order, array $results, ?string $total_rows, int $rows_per_page, mixed $current_page ): void {
		echo '<table class="wp-list-table widefat fixed striped">';
		echo '<thead>
		        <tr>
		            <th style="width: 13%;">' . $this->render_table_anchor_tag( __( 'Last Name', 'wccf_domain' ), 'last_name', $order, $current_order_by ) . '</th>
		            <th style="width: 13%;">' . $this->render_table_anchor_tag( __( 'First Name', 'wccf_domain' ), 'first_name', $order, $current_order_by ) . '</th>
		            <th style="width: 20%;">' . __( 'Email', 'wccf_domain' ) . '</th>
		            <th style="width: 6%;">' . $this->render_table_anchor_tag( __( 'Guests', 'wccf_domain' ), 'num_guests', $order, $current_order_by ) . '</th>
		            <th style="width: 15%;">' . $this->render_table_anchor_tag( __( 'Attendance Status', 'wccf_domain' ), 'attendance_status', $order, $current_order_by ) . '</th>
		            <th>' . __( 'Additional Info', 'wccf_domain' ) . '</th>
		        </tr>
		      </thead>';
		echo '<tbody>';

		if ( $results ) {
			foreach ( $results as $row ) {
				echo '<tr>';
				echo '<td>' . esc_html( $row['last_name'] ) . '</td>';
				echo '<td>' . esc_html( $row['first_name'] ) . '</td>';
				echo '<td>' . esc_html( $row['email'] ) . '</td>';
				echo '<td>' . esc_html( $row['num_guests'] ) . '</td>';
				echo '<td>' . esc_html( $row['attendance_status'] ) . '</td>';
				echo '<td>' . esc_html( $row['additional_info'] ) . '</td>';
				echo '</tr>';
			}
		} else {
			echo '<tr><td colspan="6">' . __( 'No entries found.', 'wccf_domain' ) . '</td></tr>';
		}

		echo '</tbody>';
		echo '</table>';

		// Display pagination links
		$pagination_links = paginate_links( array(
			'base'      => $this->wccf_build_url( [ 'paged' => '%#%' ] ),
			'format'    => '',
			'prev_text' => __( '&#8592; Previous', 'wccf_domain' ),
			'next_text' => __( 'Next &#8594;', 'wccf_domain' ),
			'total'     => ceil( $total_rows / $rows_per_page ),
			'current'   => $current_page,
		) );

		if ( $pagination_links ) {
			echo '<div class="tablenav"><div class="tablenav-pages">' . $pagination_links . '</div></div>';
		}

		echo '</div>';
	}

	/**
	 * @param array $results
	 *
	 * @return void
	 */
	public function render_export_button( array $results ): void {
		if ( ! empty( $results ) ) {
			echo '<div class="wrap">';
			echo '<form method="post" action="' . admin_url( 'admin-post.php' ) . '" onsubmit="handleExport(this);">';
			echo '<input type="hidden" name="action" value="wccf_export" />';
			echo '<input type="submit" value="' . __( 'Export to CSV', 'wccf_domain' ) . '" class="button button-primary" />';
			echo '</form>';
			echo '</div>';

			echo '<script>
		            function handleExport(form) {
		                const button = form.querySelector("input[type=submit]");
		                button.disabled = true;
		                button.value = "' . __( 'Exporting...', 'wccf_domain' ) . '";
		                
		                // Re-enable button after a short delay
		                setTimeout(() => {
		                    button.disabled = false;
		                    button.value = "' . __( 'Export to CSV', 'wccf_domain' ) . '";
		                }, 600); // Small delay for UX
		            }
		        </script>';
		}
	}

	private function get_order_by_label( string $order_by ) {
		$order_by_labels = array(
			'last_name'         => __( 'Last Name', 'wccf_domain' ),
			'first_name'        => __( 'First Name', 'wccf_domain' ),
			'email'             => __( 'Email', 'wccf_domain' ),
			'num_guests'        => __( 'Guests', 'wccf_domain' ),
			'attendance_status' => __( 'Attendance Status', 'wccf_domain' ),
		);

		return $order_by_labels[ $order_by ];
	}

	function wccf_build_url( $args = [] ) {
		$defaults = [
			'page' => $this->get_page_slug(),
		];

		if ( isset( $_GET['search'] ) ) {
			$defaults['search'] = sanitize_text_field( $_GET['search'] );
		}

		$query_args = array_merge( $defaults, $args );

		return add_query_arg( $query_args, admin_url( 'admin.php' ) );
	}
}