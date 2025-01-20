<?php
$page_id                    = $args['page_id'] ?? get_the_ID();
$our_story_section_fields   = get_fields( $page_id );
$our_story_section_subtitle = $our_story_section_fields['our_story_section_subtitle'] ?? '';
$our_story_card_title       = $our_story_section_fields['our_story_card_title'] ?? '';
$our_story_card_content     = $our_story_section_fields['our_story_card_content'] ?? '';
$our_story_card_image       = $our_story_section_fields['our_story_card_image'] ?? null;
?>

<section id="our-story-section" class="our-story-section story-padding page-section">
    <div class="container-lg">
        <!-- Section Tittle -->
        <div class="row justify-content-center">
            <div class="col-xl-7 col-lg-8">
                <div class="section-tittle text-center">
                    <h2>Our Love Story</h2>
                    <img src="<?php echo get_asset_image_url( 'gallery/tittle_img.png' ) ?>" alt="">


					<?php if ( ! empty( $our_story_section_subtitle ) ): ?>

                        <p><?php echo esc_html( $our_story_section_subtitle ); ?></p>

					<?php endif; ?>
                </div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <div class="story-caption background-img"
                     style="background-image: url(<?php echo get_asset_image_url( 'gallery/story2.jpg' ) ?>);">
                    <div class="story-details">
                        <h4><?php echo esc_html( $our_story_card_title ); ?></h4>

						<?php if ( ! empty( $our_story_card_content ) ): ?>

							<?php echo sanitize_allowed_html( $our_story_card_content ); ?>

						<?php endif; ?>

                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="story-img">
					<?php if ( ! empty( $our_story_card_image ) && isset( $our_story_card_image['sizes'] ) ): ?>

                        <img src="<?php echo $our_story_card_image['sizes']['hero-banner-portrait'] ?>"
                             class="story2 img-thumbnail"
                             alt="">

					<?php else: ?>

                        <img class="story2 img-thumbnail"
                             src="<?php echo get_asset_image_url( 'gallery/the-proposal.jpg' ) ?>"
                             alt="">
					<?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>