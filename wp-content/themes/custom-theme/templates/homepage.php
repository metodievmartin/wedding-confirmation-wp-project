<?php
/**
 * Template Name: Homepage
 */
?>

<?php get_header(); ?>

<main>
	<?php get_template_part( 'template-parts/banners/hero-banner' ); ?>

	<?php get_template_part( 'template-parts/homepage/wedding-info' ); ?>

	<?php get_template_part( 'template-parts/homepage/countdown-area' ); ?>

</main>

<?php get_footer(); ?>
