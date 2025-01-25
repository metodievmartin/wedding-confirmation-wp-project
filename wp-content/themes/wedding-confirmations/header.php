<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php echo wccf()->get_colour_data_attribute() ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.0/css/all.css">-->
    <script src="https://kit.fontawesome.com/730c4d423b.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:ital,wght@0,100..900;1,100..900&family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap"
          rel="stylesheet">
	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<?php wp_body_open(); ?>

<header class="header">
    <div class="container-lg">
        <nav class="navbar navbar-light navbar-expand-lg">
            <a href="<?php echo esc_url( site_url( '/' ) ); ?>" class="navbar-brand">
				<?php get_template_part( 'template-parts/header/logo' ); ?>
            </a>

            <button class="navbar-toggler py-2 px-3" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                <span class="fa fa-bars text-primary"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarCollapse">

				<?php
				// Check if the 'header-menu' location has a menu assigned
				if ( has_nav_menu( 'header-menu' ) ) {
					wp_nav_menu( array(
						'theme_location' => 'header-menu',
						'depth'          => 2,
						'walker'         => new WP_Bootstrap_Navwalker(),
						'menu_class'     => 'navbar-nav ms-auto',
						'container'      => false
					) );
				}
				?>

            </div>
        </nav>
    </div>
</header>
