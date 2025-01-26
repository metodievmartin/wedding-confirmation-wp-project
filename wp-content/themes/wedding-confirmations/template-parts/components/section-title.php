<?php
$section_title    = $args['section_title'] ?? __( 'Section Title', 'wedding-confirmations' );
$section_subtitle = $args['section_subtitle'] ?? '';
?>

<div class="row justify-content-center">
    <div class="col-xl-7 col-lg-8">
        <div class="section-tittle text-center mb-5">
            <h2 class="mb-0"><?php echo esc_html( $section_title ); ?></h2>

			<?php get_template_part( 'template-parts/components/ornament-line' ); ?>

			<?php if ( ! empty( $section_subtitle ) ): ?>

                <p class="mt-2"><?php echo esc_html( $section_subtitle ); ?></p>

			<?php endif; ?>
        </div>
    </div>
</div>