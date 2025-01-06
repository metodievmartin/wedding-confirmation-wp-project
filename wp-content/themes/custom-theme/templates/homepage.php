<?php
/**
 * Template Name: Homepage
 */
?>

<?php get_header(); ?>

<main>
    <div class="single-slider slider-height hero-overly d-flex align-items-center">
        <div class="container-xxl">
            <div class="row d-flex align-items-center">
                <div class="col-xl-8 col-lg-6 col-md-8 ">
                    <div class="hero__caption">
                        <span data-animation="fadeInLeft" data-delay=".3s">january 23</span>
                        <h1 data-animation="fadeInLeft" data-delay=".5s" data-duration="2000ms">Dennis<strong> & </strong> Judith</h1>
                        <p data-animation="fadeInLeft" data-delay=".9s">will get married</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<?php get_template_part( 'template-parts/banners/hero-banner' ); ?>


</main>

<?php get_footer(); ?>
