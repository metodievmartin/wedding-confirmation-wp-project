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
        background-image: url("<?php echo $banner_background_image['sizes']['hero-banner'] ?>");
    }

    .wedding-date-heading::before {
        background-image: url(<?php echo get_asset_image_url('/hero/hero_shape1.png') ?>);
    }

    .main-hero-title .name.second::after {
        background-image: url(<?php echo get_asset_image_url('/hero/hero_shape2.png') ?>);
    }

</style>

<section class="hero-banner page-section py-6 my-6 mt-0">
    <div class="container-lg hero-banner-container">
        <div class="row align-items-center">
            <div class="title-container">

				<?php if ( ! empty( $hero_banner_wedding_date ) ): ?>
                    <h3 class="wedding-date-heading">
						<?php echo $hero_banner_wedding_date; ?>
                    </h3>
				<?php endif; ?>

				<?php if ( ! empty( $hero_banner_first_name ) && ! empty( $hero_banner_second_name ) ): ?>
                    <h1 class="main-hero-title">
                        <span class="name first"><?php echo $hero_banner_first_name; ?></span>
                        <span class="ampersand"> & </span>
                        <span class="name second"><?php echo $hero_banner_second_name; ?></span>
                    </h1>
				<?php endif; ?>

				<?php if ( ! empty( $banner_secondary_title ) ): ?>
                    <h5 class="text-uppercase secondary-hero-title m-0 py-3">
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