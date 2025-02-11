<footer class="main-footer-section">
    <div class="container-xxl">
        <div class="row justify-content-between pt-5 pb-5">
            <div class="footer-column col-lg-3 col-md-6">
                <div class="footer-item">
                    <h2 class="mb-4">
						<?php get_template_part( 'template-parts/header/logo' ); ?>
                    </h2>
                    <p class="lh-lg mb-4">

                    </p>

					<?php if ( is_active_sidebar( 'footer-social-icons' ) ) : ?>
                        <div class="footer-logo-widget-area">
							<?php dynamic_sidebar( 'footer-social-icons' ); ?>
                        </div>
					<?php endif; ?>
                </div>
            </div>
            <div class="footer-column col-xl-3 col-lg-3 col-md-6 mb-30">
                <h3 class="footer-section-heading">Меню</h3>

				<?php

				if ( has_nav_menu( 'footer-page-links-menu' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'footer-page-links-menu',
						'walker'         => new WP_Bootstrap_Navwalker(),
						'menu_class'     => 'nav flex-column footer-menu',
						'container'      => false
					) );
				}

				?>

            </div>
            <div class="footer-column col-lg-3 col-md-6">
                <div class="footer-item">
                    <h3 class="footer-section-heading">Контакти</h3>
                    <div class="d-flex flex-column align-items-start">
                        <p><i class="fa fa-map-marker-alt me-2"></i> Test</p>
                        <p><i class="fa fa-phone-alt me-2"></i> Test</p>
                        <p><i class="fas fa-envelope me-2"></i> Test</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="copyright-area">
        <div class="container-xxl text-center">
            <p class="copyright-text">
                Copyright &copy; <?php echo date( "Y" ); ?> Wedding Confirmations. All Rights Reserved
            </p>
        </div>
    </div>

</footer>

<?php wp_footer(); ?>

</body>
</html>
