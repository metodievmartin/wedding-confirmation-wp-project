<div class="count-down-area">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12 col-md-12">
                <div id="wccf-countdown"
                     class="count-down-wrapper"
                     data-end-date="<?php echo wccf()->get_countdown_date() ?>"
                     style="background-image: url('<?php echo get_asset_image_url( 'gallery/section_bg2.png' ) ?>')">
                    <div class="row justify-content-between">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <!-- Counter Up -->
                            <div class="single-counter text-center">
                                <span id="cd-days" class="counter">23</span>
                                <p>days</p>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <!-- Counter Up -->
                            <div class="single-counter active text-center">
                                <span id="cd-hours" class="counter">15</span>
                                <p>hours</p>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-6 col-sm-6">
                            <!-- Counter Up -->
                            <div class="single-counter text-center">
                                <span id="cd-minutes" class="counter">46</span>
                                <p>mins</p>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <!-- Counter Up -->
                            <div class="single-counter text-center">
                                <span id="cd-seconds" class="counter">20</span>
                                <p>secs</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>