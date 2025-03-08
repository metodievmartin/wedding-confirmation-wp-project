<?php
$has_end_date_passed = wccf()->has_end_date_passed();
?>

<section class="contact-form-section page-section">
    <div class="container-xxl">
        <div class="row justify-content-center">
            <div class="padding-container col-lg-7 col-xl-6">
                <div class="form-wrapper contact-form-container d-flex align-items-center justify-content-center">

					<?php if ( ! $has_end_date_passed ): ?>

                        <form id="contact-form" class="needs-validation" novalidate>

							<?php if ( wccf_is_recaptcha_enabled() ): ?>
                                <input type="hidden" name="recaptcha_action" value="guest_confirmation">
                                <input type="hidden" name="recaptcha_token" value="">
							<?php endif; ?>

                            <!-- Section Title -->
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="section-tittle tittle-form text-center mb-3">
                                        <h2><?php esc_html_e( 'Are you attending?', 'wedc-domain' ); ?></h2>
										<?php get_template_part( 'template-parts/components/ornament-line-small' ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-lg-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="guest_first_name"
                                               name="guest_first_name"
                                               placeholder="<?php esc_attr_e( 'First Name', 'wedc-domain' ); ?>"
                                               required>
                                        <label for="guest_first_name">
                                            *<?php esc_html_e( 'First Name', 'wedc-domain' ); ?>
                                        </label>
                                        <div class="invalid-feedback">
											<?php esc_html_e( 'First name is a required field', 'wedc-domain' ); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="guest_last_name"
                                               name="guest_last_name"
                                               placeholder="<?php esc_attr_e( 'Last Name', 'wedc-domain' ); ?>"
                                               required>
                                        <label for="guest_last_name">
                                            *<?php esc_html_e( 'Last Name', 'wedc-domain' ); ?>
                                        </label>
                                        <div class="invalid-feedback">
											<?php esc_html_e( 'Last name is a required field', 'wedc-domain' ); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="guest_email" name="guest_email"
                                               placeholder="<?php esc_attr_e( 'Your Email', 'wedc-domain' ); ?>"
                                               required>
                                        <label for="guest_email">
                                            *<?php esc_html_e( 'Your Email', 'wedc-domain' ); ?>
                                        </label>
                                        <div class="invalid-feedback">
											<?php esc_html_e( 'This field should be a valid email', 'wedc-domain' ); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <select id="num_guests" name="num_guests"
                                            class="form-select nice-select form-select-lg"
                                            aria-label="Large select example">
                                        <option selected value="1">
                                            1 <?php esc_html_e( 'Guest', 'wedc-domain' ); ?>
                                        </option>
                                        <option value="2">2 <?php esc_html_e( 'Guests', 'wedc-domain' ); ?></option>
                                        <option value="3">3 <?php esc_html_e( 'Guests', 'wedc-domain' ); ?></option>
                                        <option value="4">4 <?php esc_html_e( 'Guests', 'wedc-domain' ); ?></option>
                                        <option value="5">5 <?php esc_html_e( 'Guests', 'wedc-domain' ); ?></option>
                                        <option value="6">6 <?php esc_html_e( 'Guests', 'wedc-domain' ); ?></option>
                                        <option value="7">7 <?php esc_html_e( 'Guests', 'wedc-domain' ); ?></option>
                                        <option value="8">8 <?php esc_html_e( 'Guests', 'wedc-domain' ); ?></option>
                                    </select>
                                </div>
                                <div class="col-7">
                                    <select id="rsvp_confirmation" name="rsvp_confirmation"
                                            class="form-select nice-select form-select-lg"
                                            aria-label="Large select example">
                                        <option selected value="true">
											<?php esc_html_e( 'Confirm', 'wedc-domain' ); ?>
                                        </option>
                                        <option value="false"><?php esc_html_e( 'Decline', 'wedc-domain' ); ?></option>
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-floating form-box message-icon mb-3">
                                        <textarea class="form-control additional-info"
                                                  id="additional_info" name="additional_info"
                                                  placeholder="<?php esc_attr_e( 'Add additional info', 'wedc-domain' ); ?>"
                                        ></textarea>
                                        <label for="additional_info">
											<?php esc_html_e( 'Additional info', 'wedc-domain' ); ?>
                                        </label>
                                    </div>

									<?php if ( wccf_is_recaptcha_enabled() ): ?>
                                        <div class="recaptcha-terms-container">
                                            This site is protected by reCAPTCHA and the Google
                                            <a href="https://policies.google.com/privacy">Privacy Policy</a> and
                                            <a href="https://policies.google.com/terms">Terms of Service</a> apply.
                                        </div>
									<?php endif; ?>

                                    <div class="form-submission-alert alert d-none alert-dismissible my-4">
                                        <div class="alert-message type-success">
											<?php esc_html_e( 'Submitted successfully!', 'wedc-domain' ); ?>
                                        </div>
                                        <div class="alert-message type-error">
											<?php esc_html_e( 'Sorry, there has been an error.', 'wedc-domain' ); ?>
                                        </div>
                                        <button type="button" class="btn-close" aria-label="Close"></button>
                                    </div>

                                    <div class="submit-info text-center mt-4">
                                        <button class="btn btn-wedc-primary btn-lg px-5" type="submit">
											<?php esc_html_e( 'Submit', 'wedc-domain' ); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>

					<?php else: ?>


                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="card-title">
									<?php esc_html_e( 'Confirmation is closed', 'wedc-domain' ); ?>
                                </h3>

								<?php get_template_part( 'template-parts/components/ornament-line-small' ); ?>

                                <p class="card-text mt-3">
									<?php esc_html_e(
										'We are no longer taking new confirmations. Thank you if you have already responded!',
										'wedc-domain'
									); ?>
                                </p>
                            </div>
                        </div>

					<?php endif; ?>

                    <!-- Shape Inner Flower -->
                    <div class="shape-inner-flower">
                        <img src="<?php echo get_asset_image_url( 'bg/contact-form-bg-shape.png' ) ?>" class="shpe2"
                             alt="">
                    </div>
                    <!-- Shape Outer Flower -->
                    <div class="shape-outer-flower">
                        <img src="<?php echo get_asset_image_url( 'flower/from-top.png' ) ?>" class="outer-top" alt="">
                        <img src="<?php echo get_asset_image_url( 'flower/from-bottom.png' ) ?>" class="outer-bottom"
                             alt="">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>