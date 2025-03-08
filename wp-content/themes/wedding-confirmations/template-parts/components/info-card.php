<?php
$card_icon    = $args['card_icon'] ?? '';
$card_title   = $args['card_title'] ?? '';
$card_date    = $args['card_date'] ?? '';
$card_time    = $args['card_time'] ?? '';
$card_place   = $args['card_place'] ?? '';
$card_address = $args['card_address'] ?? '';
$card_url     = $args['card_url'] ?? '';
?>

<div class="col-11 col-sm-8 col-md-6 col-lg-4 col-xl-4">
    <div class="single-card text-center ">
        <div class="card-top">
            <div class="icon-container d-flex justify-content-center align-items-center">
                <img src="<?php echo $card_icon; ?>" alt="">
            </div>
            <h4 class="my-4"><?php echo esc_html( $card_title ); ?></h4>
        </div>
        <div class="card-bottom">
            <ul class="list-unstyled">
                <li class="text-capitalize">
                    <i class="fas fa-calendar-alt"></i><?php echo esc_html( $card_date ); ?>
                </li>
                <li><i class="far fa-clock"></i><?php echo esc_html( $card_time ); ?></li>
                <li><i class="fas fa-map-marker-alt"></i><?php echo esc_html( $card_place ); ?></li>

				<?php if ( ! empty( $card_address ) ): ?>
                    <li class="address-line"><?php echo esc_html( $card_address ); ?></li>
				<?php endif; ?>

                <li>
                    <a href="<?php echo esc_url( $card_url ); ?>"
                       target="_blank"
                       class="mt-3 find-on-map-button text-decoration-none text-reset">
                        <i class="far fa-map"></i> <?php esc_html_e( 'Check out the map', 'wedc-domain' ); ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>