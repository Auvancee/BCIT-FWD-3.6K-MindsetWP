<?php

	/**
 * Registers blocks for the Mindset theme.
 */
function mindset_blocks_example_dynamic_block_init() {
    // Register block types
    register_block_type( __DIR__ . '/build/copyright' );
    register_block_type( __DIR__ . '/build/company-email' );
    register_block_type( __DIR__ . '/build/company-address' );
    register_block_type( __DIR__ . '/build/service-posts', array(
        'render_callback' => 'fwd_render_service_posts',  // This line adds the render callback
    ) );  // Keep this line to register block assets

    register_block_type( __DIR__ . '/build/testimonial-slider', array( 
        'render_callback' => 'fwd_render_testimonial_slider' ) );
}
add_action( 'init', 'mindset_blocks_example_dynamic_block_init' );

/**
 * Callback function to render service posts.
 */
function fwd_render_service_posts( $attributes ) {
    ob_start();
    ?>
    <div <?php echo get_block_wrapper_attributes(); ?>>
        <?php
        // Output the Service navigation
        $args = array(
            'post_type'      => 'fwd-service',
            'posts_per_page' => -1,
            'order'          => 'ASC',
            'orderby'        => 'title'
        );
        $query = new WP_Query( $args );
        if ( $query -> have_posts() ) :
            ?>
            <nav class="services-nav">
                <?php
                while ( $query -> have_posts() ) :
                    $query -> the_post();
                    ?>
                    <a href="#<?php echo esc_attr( get_the_ID() ); ?>"><?php echo esc_html( get_the_title() ); ?></a>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </nav>
            <?php
        endif;
        
        // Output the Service posts
        $taxonomy = 'fwd-service-type';
        $terms    = get_terms(
            array(
                'taxonomy' => $taxonomy
            )
        );
        if( $terms && ! is_wp_error( $terms ) ) :
            foreach( $terms as $term ) :
                $args = array(
                    'post_type'      => 'fwd-service',
                    'posts_per_page' => -1,
                    'order'          => 'ASC',
                    'orderby'        => 'title',
                    'tax_query'      => array(
                        array(
                            'taxonomy' => $taxonomy,
                            'field'    => 'slug',
                            'terms'    => $term->slug,
                        )
                    ),
                );
                $query = new WP_Query( $args );
                if ( $query -> have_posts() ) :
                    ?>
                    <section>
                        <h2><?php echo esc_html( $term->name ); ?></h2>
                        <?php
                        while ( $query -> have_posts() ) :
                            $query -> the_post();
                            ?>
                            <article id="<?php echo esc_attr( get_the_ID() ); ?>">
                                <h3><?php echo esc_html( get_the_title() ); ?></h3>
                                <?php the_content(); ?>
                            </article>
                            <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </section>
                    <?php
                endif;
            endforeach;
        endif;
        ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Register custom fields for blocks.
 */
function mindset_register_custom_fields() {
    register_post_meta(
        'page',
        'company_email',
        array(
            'type'         => 'string',
            'show_in_rest' => true,
            'single'       => true
        )
    );
    register_post_meta(
        'page',
        'company_address',
        array(
            'type'         => 'string',
            'show_in_rest' => true,
            'single'       => true
        )
    );
}
add_action( 'init', 'mindset_register_custom_fields' );


// Callback function for the Testimonial Slider
function fwd_render_testimonial_slider( $attributes, $content ) {
    ob_start();
    $swiper_settings = array(
        'pagination' => $attributes['pagination'],
        'navigation' => $attributes['navigation']
    );
    ?>
    <div <?php echo get_block_wrapper_attributes(); ?>>
        <script>
            const swiper_settings = <?php echo json_encode( $swiper_settings ); ?>;
        </script>
        <?php
        $args = array(
            'post_type'      => 'fwd-testimonial',
            'posts_per_page' => -1
        );
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) : ?>
            <div class="swiper">
                <div class="swiper-wrapper">
                    <?php while ( $query->have_posts() ) : $query->the_post(); ?>
                        <div class="swiper-slide">
                            <?php the_content(); ?>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
            <?php if ( $attributes['pagination'] ) : ?>
                <div class="swiper-pagination"></div>
            <?php endif; ?>
            <?php if ( $attributes['navigation'] ) : ?>
                <button class="swiper-button-prev"></button>
                <button class="swiper-button-next"></button>
            <?php endif; ?>
            <?php
            wp_reset_postdata();
        endif;
        ?>
    </div>
    <?php
    return ob_get_clean();
}