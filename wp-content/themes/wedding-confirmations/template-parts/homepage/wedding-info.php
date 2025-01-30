<?php
$info_cards_query = wccf()->get_info_cards();
?>

<section id="wedding-info-section" class="wedding-info-section section-padding30 section-bg"
         style="background-image: url('<?php echo get_asset_image_url( 'gallery/section_bg1.png' ) ?>')">
    <div class="container-lg">

		<?php
		$section_title_args = array(
			'section_title' => __( 'Wedding Info', 'wedding-confirmations' ),
		);

		get_template_part( 'template-parts/components/section-title', null, $section_title_args );

		?>

        <div class="row g-3 justify-content-center pt-4">
			<?php

			if ( $info_cards_query->have_posts() ) {
				while ( $info_cards_query->have_posts() ) {
					$info_cards_query->the_post();

					$card_fields = get_fields( get_the_ID() );

					$args = array(
						'card_icon'    => $card_fields['info_card_icon'],
						'card_title'   => $card_fields['info_card_title'],
						'card_date'    => custom_format_datetime( $card_fields['info_card_date'] ),
						'card_time'    => custom_format_datetime( $card_fields['info_card_time'], 'H:i' ),
						'card_place'   => $card_fields['info_card_place'],
						'card_address' => $card_fields['info_card_address'],
						'card_url'     => $card_fields['info_card_map_url'],
					);

					get_template_part( 'template-parts/components/info-card', null, $args );

				}
			} else {
				echo '<div class="text-center">' . esc_html( __( 'Please, add wedding information', 'wedding-confirmations' ) ) . '</div>';
			}

			?>

        </div>
    </div>
</section>