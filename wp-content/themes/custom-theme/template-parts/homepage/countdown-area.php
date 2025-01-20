<?php
$confirmations_end_date = wccf()->get_confirmations_end_date();
$date_string            = wccf()->get_confirmations_end_date_formatted( 'c' );
$has_end_date_passed    = wccf()->has_end_date_passed();
?>

<section id="confirm-attendance-section" class="countdown-section">
    <div class="container-lg">
        <div class="row d-flex justify-content-center">
            <div class="col-lg-8">
                <div class="section-tittle text-center">
					<?php if ( ! $has_end_date_passed ) : ?>
                        <h2>Confirmation Closes In:</h2>
					<?php else: ?>
                        <h2>Confirmation Closed:</h2>
					<?php endif; ?>

                    <img src="<?php echo get_asset_image_url( 'gallery/tittle_img.png' ) ?>" alt="">

					<?php if ( ! empty( $confirmations_end_date ) && ! $has_end_date_passed ) : ?>
                        <p>
                            We kindly ask you to confirm or decline your attendance
                            until <?php echo $confirmations_end_date->format( 'H:i:s d/m/Y' ); ?>
                        </p>
					<?php else: ?>
                        <p>We are no longer accepting new confirmations. Thank you!</p>
					<?php endif; ?>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12 no-padding-until-lg">
                <div id="wccf-countdown"
                     class="count-down-wrapper"
                     data-end-date="<?php echo $date_string ?>">
                    <div class="digits-container d-flex justify-content-between flex-wrap">
                        <div class="single-counter text-center">
                            <span id="cd-days" class="counter">23</span>
                            <p>days</p>
                        </div>
                        <div class="single-counter text-center">
                            <span id="cd-hours" class="counter">15</span>
                            <p>hours</p>
                        </div>
                        <div class="single-counter text-center">
                            <span id="cd-minutes" class="counter">46</span>
                            <p>mins</p>
                        </div>
                        <div class="single-counter text-center">
                            <span id="cd-seconds" class="counter">20</span>
                            <p>secs</p>
                        </div>
                    </div>
                    <div><img class="background-img"
                              src="<?php echo get_asset_image_url( 'gallery/section_bg2.png' ) ?>" alt=""></div>
                </div>
            </div>
        </div>
    </div>
</section>