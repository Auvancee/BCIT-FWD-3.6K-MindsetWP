<?php
function mindset_enqueues() {
	// Load style.css on the front-end
	// Parameters: Unique handle, Source, Dependencies, Version number, Media
	wp_enqueue_style( 
		'mindset-style',
		get_stylesheet_uri(),
		array(),
		wp_get_theme()->get( 'Version' ),
		'all'
	);

    wp_enqueue_style( 
        'mindset-normalize', 
        get_theme_file_uri( 'assets/css/normalize.css'), 
        array(), 
        '12.1.0'
    );

    wp_enqueue_script(
        'mindset-scroll-to-top',
        get_theme_file_uri('assets/js/scroll-to-top.js'),
        array(),
        '20250127',
        array( 'strategy' => 'defer' )
    );
}
add_action( 'wp_enqueue_scripts', 'mindset_enqueues' );

function mindset_setup() {
	add_editor_style( get_stylesheet_uri() );

    // Crop images to 400px by 500px
    add_image_size( '400x500', 400, 500, true );

    // Crop images to 200px by 250px
    add_image_size( '200x250', 200, 250, true );

    add_image_size('custom-400x200', 400, 200, true); // 400x200, cropped
    add_image_size('custom-800x400', 800, 400, true); // 800x400, cropped

    // Make the new sizes selectable in the WordPress UI
    add_filter('image_size_names_choose', function($sizes) {
        return array_merge($sizes, [
            'custom-400x200' => __('400x200 Cropped'),
            'custom-800x400' => __('800x400 Cropped'),
        ]);
    });

}
add_action( 'after_setup_theme', 'mindset_setup' );

// Make custom sizes selectable from WordPress admin.
function mindset_add_custom_image_sizes( $size_names ) {
	$new_sizes = array(
		'400x500' => __( '400x500', 'mindset-theme' ),
		'200x250' => __( '200x250', 'mindset-theme' ),
	);
	return array_merge( $size_names, $new_sizes );
}
add_filter( 'image_size_names_choose', 'mindset_add_custom_image_sizes' );


function enqueue_scroll_top_scripts() {
    if (is_page('contact')) { // Conditional tag to check if it's the Contact page
        // Enqueue the original scroll-to-top.js
        wp_enqueue_script(
            'scroll-top',
            get_template_directory_uri() . '/assets/js/scroll-to-top.js',
            [],
            '1.0',
            true
        );

        // Enqueue the new scroll-to-top-color.js, dependent on scroll-to-top.js
        wp_enqueue_script(
            'scroll-top-color',
            get_template_directory_uri() . '/assets/js/scroll-to-top-color.js',
            ['scroll-top'], // Dependency on scroll-to-top.js
            '1.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'enqueue_scroll_top_scripts');


// Load our Custom Blocks
require get_theme_file_path() . '/mindset-blocks/mindset-blocks.php';   

/* Custom Post Types & Custom Taxonomies
*/
require get_template_directory() . '/inc/post-types-taxonomies.php';
