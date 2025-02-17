<?php
$confirmations_end_date = wccf()->get_confirmations_end_date();
$date_string            = wccf()->get_confirmations_end_date_formatted( 'c' );
$has_end_date_passed    = wccf()->has_end_date_passed();
?>

<section id="confirm-attendance-section" class="countdown-section">
    <div class="container-lg">

		<?php
		$countdown_section_title = $has_end_date_passed
			? 'Затворено за потвърждения'
			: 'Потвърждение до:';

		if ( ! empty( $confirmations_end_date ) && ! $has_end_date_passed ) {
			$countdown_section_subtitle = sprintf(
				"Молим Ви учтиво да потвърдите своето присъствие до %s часа на %s",
				$confirmations_end_date->format( 'H:i' ),
				$confirmations_end_date->format( 'd.m.Y' ),
			);
		} else {
			$countdown_section_subtitle = 'Приемането на нови потвърждения приключи. Благодарим Ви за разбирането!';
		}

		$section_title_args = array(
			'section_title'    => $countdown_section_title,
			'section_subtitle' => $countdown_section_subtitle
		);

		get_template_part( 'template-parts/components/section-title', null, $section_title_args );

		?>

        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12 no-padding-until-lg">
                <div id="wccf-countdown"
                     class="count-down-wrapper"
                     style="background-image: url('<?php echo get_asset_image_url( '/bg/bg-pattern-main-25.png' ) ?>');"
                     data-end-date="<?php echo $date_string ?>">
                    <div class="digits-container d-flex justify-content-between flex-wrap">
                        <div class="single-counter text-center">
                            <span id="cd-days" class="counter">23</span>
                            <p>дни</p>
                        </div>
                        <div class="single-counter text-center">
                            <span id="cd-hours" class="counter">15</span>
                            <p>часа</p>
                        </div>
                        <div class="single-counter text-center">
                            <span id="cd-minutes" class="counter">46</span>
                            <p>мин</p>
                        </div>
                        <div class="single-counter text-center">
                            <span id="cd-seconds" class="counter">20</span>
                            <p>сек</p>
                        </div>
                    </div>

                    <img class="corner-ornament top right"
                         src="<?php echo get_asset_image_url( '/ornaments/corner-ornament-2.svg' ); ?>"
                         alt="corner-ornament-image">

                    <img class="corner-ornament bottom right"
                         src="<?php echo get_asset_image_url( '/ornaments/corner-ornament-2.svg' ); ?>"
                         alt="corner-ornament-image">

                    <img class="corner-ornament bottom left"
                         src="<?php echo get_asset_image_url( '/ornaments/corner-ornament-2.svg' ); ?>"
                         alt="corner-ornament-image">

                    <img class="corner-ornament top left"
                         src="<?php echo get_asset_image_url( '/ornaments/corner-ornament-2.svg' ); ?>"
                         alt="corner-ornament-image">
                </div>
            </div>
        </div>
    </div>
</section>