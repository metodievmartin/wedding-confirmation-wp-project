<?php
$page_id                    = $args['page_id'] ?? get_the_ID();
$our_story_section_fields   = get_fields( $page_id );
$our_story_hide_section     = $our_story_section_fields['our_story_hide_section'] ?? '';
$our_story_section_subtitle = $our_story_section_fields['our_story_section_subtitle'] ?? '';
$our_story_card_title       = $our_story_section_fields['our_story_card_title'] ?? '';
$our_story_card_content     = $our_story_section_fields['our_story_card_content'] ?? '';
$our_story_card_image       = $our_story_section_fields['our_story_card_image'] ?? null;
?>

<?php if ( ! $our_story_hide_section ) : ?>

    <section id="our-story-section" class="our-story-section story-padding page-section">
        <div class="container-lg">

			<?php
			$section_title_args = array(
				'section_title'    => 'Нашата история',
				'section_subtitle' => $our_story_section_subtitle
			);

			get_template_part( 'template-parts/components/section-title', null, $section_title_args );

			?>

            <div class="row g-0 justify-content-center">
                <div class="col-10 col-sm-7 col-md-6 col-lg-5">
                    <div class="story-caption background-img"
                         style="background-image: url('<?php echo get_asset_image_url( '/bg/bg-pattern-main-25.png' ) ?>');">
                        <div class="story-details">
                            <h4><?php echo esc_html( $our_story_card_title ); ?></h4>

							<?php if ( ! empty( $our_story_card_content ) ): ?>

								<?php echo sanitize_allowed_html( $our_story_card_content ); ?>

							<?php endif; ?>

                        </div>

                        <img class="corner-ornament top right"
                             src="<?php echo get_asset_image_url( '/ornaments/corner-ornament-3.svg' ); ?>"
                             alt="corner-ornament-image">

                        <img class="corner-ornament bottom right"
                             src="<?php echo get_asset_image_url( '/ornaments/corner-ornament-3.svg' ); ?>"
                             alt="corner-ornament-image">

                        <img class="flower-ornament"
                             src="<?php echo get_asset_image_url( '/flower/main-flower.png' ); ?>"
                             alt="corner-ornament-image">

                    </div>


                </div>
                <div class="col-10 col-sm-7 col-md-6 col-lg-5">
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

<?php endif; ?>