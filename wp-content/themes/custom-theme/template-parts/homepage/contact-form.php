<section class="contact-form">
    <div class="container-xxl">
        <div class="row justify-content-center">
            <div class="col-xl-6 col-lg-6">
                <div class="form-wrapper">
                    <form id="contact-form" action="#" method="POST">
                        <!-- Section Title -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="section-tittle tittle-form text-center mb-3">
                                    <h2>Are you attending?</h2>
                                    <img src="<?php echo get_asset_image_url( 'gallery/tittle_img2.png' ) ?>" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-box mb-3">
                                    <input type="text" class="form-control" name="name" placeholder="Name">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-box subject-icon mb-3">
                                    <input type="email" class="form-control" name="subject" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-lg-12 mb-3">
                                <div class="select-itms">
                                    <select class="form-select nice-select" name="select" id="select2">
                                        <option value="">1 Guest</option>
                                        <option value="">2 Guests</option>
                                        <option value="">3 Guests</option>
                                        <option value="">4 Guests</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-box message-icon mb-3">
                                    <textarea name="message" id="message" class="form-control"
                                              placeholder="Your Message"></textarea>
                                </div>
                                <div class="submit-info">
                                    <button class="btn btn-primary" type="submit">Confirm now</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!-- Shape Inner Flower -->
                    <div class="shape-inner-flower">
                        <img src="<?php echo get_asset_image_url( 'gallery/shape2.png' ) ?>" class="shpe2" alt="">
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