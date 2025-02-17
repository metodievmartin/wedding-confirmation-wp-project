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
                                        <h2>Ще присъствате ли?</h2>
										<?php get_template_part( 'template-parts/components/ornament-line-small' ); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <div class="col-lg-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="guest_first_name"
                                               name="guest_first_name"
                                               placeholder="*Име"
                                               required>
                                        <label for="guest_first_name">
                                            *Име
                                        </label>
                                        <div class="invalid-feedback">
                                            Това поле е задължително
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="guest_last_name"
                                               name="guest_last_name"
                                               placeholder="*Фамилия"
                                               required>
                                        <label for="guest_last_name">
                                            *Фамилия
                                        </label>
                                        <div class="invalid-feedback">
                                            Това поле е задължително
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-floating">
                                        <input type="email" class="form-control" id="guest_email" name="guest_email"
                                               placeholder="*Имейл"
                                               required>
                                        <label for="guest_email">
                                            *Имейл
                                        </label>
                                        <div class="invalid-feedback">
                                            Това поле е задължително
                                        </div>
                                    </div>
                                </div>
                                <div class="col-5">
                                    <select id="num_guests" name="num_guests"
                                            class="form-select nice-select form-select-lg"
                                            aria-label="Large select example">
                                        <option selected value="1">1 Гост</option>
                                        <option value="2">2 Госта</option>
                                        <option value="3">3 Госта</option>
                                        <option value="4">4 Госта</option>
                                        <option value="5">5 Госта</option>
                                        <option value="6">6 Госта</option>
                                        <option value="7">7 Госта</option>
                                        <option value="8">8 Госта</option>
                                    </select>
                                </div>
                                <div class="col-7">
                                    <select id="rsvp_confirmation" name="rsvp_confirmation"
                                            class="form-select nice-select form-select-lg"
                                            aria-label="Large select example">
                                        <option selected value="true">Потвърждавам</option>
                                        <option value="false">Отказвам</option>
                                    </select>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-floating form-box message-icon mb-3">
                                        <textarea class="form-control additional-info"
                                                  id="additional_info" name="additional_info"
                                                  placeholder="Допълнителна информация"
                                        ></textarea>
                                        <label for="additional_info">
                                            Допълнителна информация
                                        </label>
                                    </div>

									<?php if ( wccf_is_recaptcha_enabled() ): ?>
                                        <div class="recaptcha-terms-container">
                                            Този сайт е защитен с reCAPTCHA и се прилагат
                                            <a href="https://policies.google.com/privacy">Политиката за
                                                поверителност</a>
                                            и <a href="https://policies.google.com/terms">Условията за ползване</a> на
                                            Google.
                                        </div>
									<?php endif; ?>

                                    <div class="form-submission-alert alert d-none alert-dismissible my-4">
                                        <div class="alert-message type-success">
                                            Изпратено успешно
                                        </div>
                                        <div class="alert-message type-error">
                                            Възникна грешка при изпращането
                                        </div>
                                        <button type="button" class="btn-close" aria-label="Close"></button>
                                    </div>

                                    <div class="submit-info text-center mt-4">
                                        <button class="btn btn-wedc-primary btn-lg px-5" type="submit">Изпрати</button>
                                    </div>
                                </div>
                            </div>
                        </form>

					<?php else: ?>


                        <div class="card text-center">
                            <div class="card-body">
                                <h3 class="card-title">Приемането на потвърждения приключи.</h3>

								<?php get_template_part( 'template-parts/components/ornament-line-small' ); ?>

                                <p class="card-text mt-3">
                                    Вече не приемаме нови потвърждения. Благодарим Ви, ако вече сте отговорили!
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