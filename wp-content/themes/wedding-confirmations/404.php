<?php
get_header();
?>

    <section class="text-center page-section">
        <div class="container-xxl">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <h1 class="display-1 fw-bold text-primary">404</h1>
                    <h2 class="fs-3"><?php _e( 'Oops! Page Not Found', 'wedding_confirmations_domain' ); ?></h2>
                    <p class="lead text-muted">
						<?php _e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'wedding_confirmations_domain' ); ?>
                    </p>
                    <a href="<?php echo home_url(); ?>" class="btn btn-wedc-primary btn-lg px-5 mt-5">
						<?php _e( 'Go to Homepage', 'wedding_confirmations_domain' ); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

<?php
get_footer();
?>