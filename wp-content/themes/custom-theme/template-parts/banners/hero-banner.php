<?php
$page_id                  = $args['page_id'] ?? get_the_ID();
$banner_fields            = get_fields( $page_id );
$hero_banner_first_name   = $banner_fields['hero_banner_first_name'] ?? '';
$hero_banner_second_name  = $banner_fields['hero_banner_second_name'] ?? '';
$hero_banner_wedding_date = $banner_fields['hero_banner_wedding_date'] ?? '';
$hero_banner_wedding_date = $banner_fields['hero_banner_wedding_date'] ?? '';
$banner_secondary_title   = $banner_fields['hero_banner_secondary_title'] ?? '';
$banner_background_image  = $banner_fields['hero_banner_background_image'] ?? null;
$banner_image             = $banner_fields['hero_banner_image'] ?? null;

?>

<style>
    .hero-banner {
        position: relative;
        background-image: url("<?php echo $banner_background_image['sizes']['hero-banner'] ?>");
        background-size: cover;
        background-position: center;
        min-height: 700px;
        display: flex;
        align-items: center;
        color: white;
    }

    .hero-banner::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background: rgba(0, 0, 0, 0.3); /* Semi-transparent black overlay */
        z-index: 1; /* Ensure it sits on top of the background image */
    }

    .hero-banner-container {
        z-index: 2;
    }

</style>

<section class="hero-banner page-section py-6 my-6 mt-0">
    <div class="container-xxl hero-banner-container">
        <div class="row align-items-center">
            <div class="title-container col-lg-7 col-md-12">

				<?php if ( ! empty( $hero_banner_wedding_date ) ): ?>
                    <h3 class="mb-4">
						<?php echo $hero_banner_wedding_date; ?>
                    </h3>
				<?php endif; ?>

				<?php if ( ! empty( $hero_banner_first_name ) && ! empty( $hero_banner_second_name ) ): ?>
                    <h1 class="display-1 "><?php echo $hero_banner_first_name; ?><strong>
                            & </strong> <?php echo $hero_banner_second_name; ?></h1>
				<?php endif; ?>

				<?php if ( ! empty( $banner_secondary_title ) ): ?>
                    <h5 class="text-uppercase fw-lighter m-0 py-3">
						<?php echo esc_html( $banner_secondary_title ); ?>
                    </h5>
				<?php endif; ?>

            </div>

			<?php if ( ! empty( $banner_image ) && isset( $banner_image['sizes'] ) ): ?>

                <div class="image-container col-lg-5 col-md-12">
                    <img src="<?php echo $banner_image['sizes']['hero-banner-portrait'] ?>"
                         class="img-fluid img-thumbnail" alt="">
                </div>

			<?php endif; ?>

        </div>
    </div>
</section>