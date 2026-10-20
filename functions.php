<?php

/**
 * Functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage growthlabseotheme01
 * 
 */

// Theme text domain global
$GLOBALS['theme_text_domain'] = 'growthlabseotheme01';

$GLOBALS['breakpoints'] = [
    'mobile' => '0px',
    'tablet' => '768px',   // differs from the default
    'ldpi'   => '1024px',
    'mdpi'   => '1280px',  // differs from the default
    'hdpi'   => '1920px',  // differs from the default
];


// Include Theme Functions
$includes = [
    'theme-functions/theme-optimization.php',
    'theme-functions/color-scheme.php',
    'theme-functions/fonts-selector.php',
    'theme-functions/acf-functions.php',
    'theme-functions/helpers.php',
    'theme-functions/svg-support.php',
    'theme-functions/picture-optimization.php',
    'theme-functions/tiny-mce.php',
    'theme-functions/security-functions.php',
];

foreach ($includes as $file) {
    if (file_exists(get_template_directory() . '/' . $file)) {
        require_once get_template_directory() . '/' . $file;
    }
}


if (!function_exists('growthlabseotheme01_setup')) {
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     *
     * @return void
     */

    function growthlabseotheme01_setup()
    {
        // Load the theme translations from the languages folder
        load_theme_textdomain('growthlabseotheme01', get_template_directory() . '/languages');

        /*
		* Let WordPress manage the document title.
		* This theme does not use a hard-coded <title> tag in the document head,
		* WordPress will provide it for us.
		*/
        add_theme_support('title-tag');

        /*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
        add_theme_support('post-thumbnails');

        // Custom Logo Support
        $defaults = array(
            'height'               => 200,
            'width'                => 360,
            'flex-height'          => true,
            'flex-width'           => true,
            'unlink-homepage-logo' => true,
        );

        add_theme_support('custom-logo', $defaults);

        // Disable hard cropping for all image sizes (only write when the stored value differs)
        if ((int) get_option('thumbnail_crop') !== 0) {
            update_option('thumbnail_crop', 0);
        }


        //Remove Sizes from default WordPress Image Sizes
        add_filter('intermediate_image_sizes', function ($sizes) {
            return array_diff($sizes, [
                'medium_large',
                '1536x1536',
                '2048x2048'
            ]);
        });

        add_filter('intermediate_image_sizes_advanced', function ($sizes) {
            unset($sizes['medium_large']);
            return $sizes;
        });

        //Add custom sized images (match theme-03)
        // Covers
        add_image_size('cover-desktop', 1920, 1080, false);
        add_image_size('cover-tablet', 1280, 960, false);
        add_image_size('cover-mobile', 768, 432, false);

        // Covers portrait
        add_image_size('cover-tablet-portrait', 960, 1280, true);
        add_image_size('cover-mobile-portrait', 768, 1100, true);

        // Content
        add_image_size('content', 800, 600, false);

        // Cards
        add_image_size('featured-small', 480, 360, false);

        // Add custom image sizes to the media selector
        add_filter('image_size_names_choose', function ($sizes) {
            return array_merge([
                'thumbnail'    => __('Thumbnail'),
                'medium'       => __('Medium'),
                'large'        => __('Large'),
                'full'         => __('Full Size'),
                'cover-desktop'  => __('Cover Desktop (1920×1080)', 'growthlabseotheme01'),
                'cover-tablet'   => __('Cover Tablet (1280x960)', 'growthlabseotheme01'),
                'cover-mobile'   => __('Cover Mobile (768×432)', 'growthlabseotheme01'),
                'cover-tablet-portrait'   => __('Cover Tablet (960x1280)', 'growthlabseotheme01'),
                'cover-mobile-portrait'   => __('Cover Mobile (768×1100)', 'growthlabseotheme01'),
                'content'   => __('Content (800×600)', 'growthlabseotheme01'),
                'featured-small' => __('Featured Small (480×360)', 'growthlabseotheme01'),
            ], $sizes);
        });

        // Register Navigation Menus
        register_nav_menus(
            array(
                'main' => esc_html__('Main Menu', 'growthlabseotheme01')
            )
        );
        register_nav_menus(
            array(
                'main_es' => esc_html__('Main Menu Spanish', 'growthlabseotheme01')
            )
        );
    }
}
add_action('after_setup_theme', 'growthlabseotheme01_setup');

add_filter('wp_theme_json_data_theme', function ($theme_json) {
    global $default_colors, $default_fonts;

    $data = $theme_json->get_data();

    $data['settings']['color']['defaultPalette'] = false;
    $data['settings']['color']['defaultGradients'] = false;
    $data['settings']['typography']['defaultFontSizes'] = false;
    $data['settings']['spacing']['defaultSpacingSizes'] = false;

    $data['settings']['color']['palette'] = [
        ['name' => __('Primary Color', 'growthlabseotheme01'), 'slug' => 'primary-color', 'color' => get_theme_mod('primary_color', $default_colors['primary']['default'])],
        ['name' => __('Primary Color Dark', 'growthlabseotheme01'), 'slug' => 'primary-color-dark', 'color' => get_theme_mod('primary_color_dark', $default_colors['primary']['dark'])],
        ['name' => __('Primary Color Light', 'growthlabseotheme01'), 'slug' => 'primary-color-light', 'color' => get_theme_mod('primary_color_light', $default_colors['primary']['light'])],
        ['name' => __('Secondary Color', 'growthlabseotheme01'), 'slug' => 'secondary-color', 'color' => get_theme_mod('secondary_color', $default_colors['secondary']['default'])],
        ['name' => __('Secondary Color Dark', 'growthlabseotheme01'), 'slug' => 'secondary-color-dark', 'color' => get_theme_mod('secondary_color_dark', $default_colors['secondary']['dark'])],
        ['name' => __('Secondary Color Light', 'growthlabseotheme01'), 'slug' => 'secondary-color-light', 'color' => get_theme_mod('secondary_color_light', $default_colors['secondary']['light'])],
        ['name' => __('Tertiary Color', 'growthlabseotheme01'), 'slug' => 'tertiary-color', 'color' => get_theme_mod('tertiary_color', $default_colors['tertiary']['default'])],
        ['name' => __('Tertiary Color Dark', 'growthlabseotheme01'), 'slug' => 'tertiary-color-dark', 'color' => get_theme_mod('tertiary_color_dark', $default_colors['tertiary']['dark'])],
        ['name' => __('Tertiary Color Light', 'growthlabseotheme01'), 'slug' => 'tertiary-color-light', 'color' => get_theme_mod('tertiary_color_light', $default_colors['tertiary']['light'])],
        ['name' => __('Text Color', 'growthlabseotheme01'), 'slug' => 'text-color', 'color' => get_theme_mod('text_color', $default_colors['text'])],
    ];

    $font_primary = get_theme_mod('font_primary', $default_fonts['primary']);
    $font_secondary = get_theme_mod('font_secondary', $default_fonts['secondary']);

    $data['settings']['typography']['fontFamilies'] = [
        ['name' => $font_primary, 'slug' => 'primary', 'fontFamily' => '"' . $font_primary . '", sans-serif'],
        ['name' => $font_secondary, 'slug' => 'secondary', 'fontFamily' => '"' . $font_secondary . '", serif'],
    ];

    $theme_json->update_with($data);
    return $theme_json;
});

/**
 * Remove link from custom logo
 */
function growthlabseotheme01_remove_custom_logo_link($html)
{
    // Extract just the <img> tag from the logo HTML
    preg_match('/<img[^>]+>/', $html, $matches);

    if (!empty($matches[0])) {
        return $matches[0];
    }

    return $html;
}
add_filter('get_custom_logo', 'growthlabseotheme01_remove_custom_logo_link');

/*Custom Excerpt Size*/
function growthlabseotheme01_custom_excerpt_length($length)
{
    return 15;
}
add_filter('excerpt_length', 'growthlabseotheme01_custom_excerpt_length', 999);

/*custom excerpt more*/
function wpdocs_excerpt_more($more)
{
    return '...';
}
add_filter('excerpt_more', 'wpdocs_excerpt_more');

/**
 * Add scripts and styles.
 *
 *
 * @return void
 */

/**
 * Whether the current request renders something that uses Splide: a carousel block in the current post, or the
 * footer locations carousel (same conditions as footer.php: section visible and more than one office).
 *
 * @return bool
 */
function growthlabseotheme01_request_uses_splide()
{
    $splide_blocks = array(
        'acf/office-locations',
        'acf/logos-carousel',
        'acf/posts-carousel',
        'acf/selling-points',
        'acf/trust-cards',
    );

    foreach (get_current_post_blocks(true) as $block) {
        if (in_array($block['blockName'], $splide_blocks, true)) {
            return true;
        }
    }

    $es = filterContentByLanguage() ? '_es' : '';
    $options = get_field_options('options' . $es);
    $options = fill_acf_group_defaults($options, 'options', 'option');
    $locations_section = $options['locations_section'] ?? array();

    return empty($locations_section['hide_section'])
        && !get_page_field('hide_locations_section')
        && !empty($options['offices'])
        && count($options['offices']) > 1;
}

function inline_main_critical_css()
{
    // Block CSS files per block type, collected on wp_enqueue_scripts (theme-functions/acf-functions.php)
    global $block_critical_css_files;
    $block_files = is_array($block_critical_css_files) ? $block_critical_css_files : array();

    // Dynamic Fonts
    $fonts_css = function_exists('theme_get_fonts_css') ? theme_get_fonts_css() : '';

    // Dynamic Color Scheme
    $color_scheme = function_exists('theme_get_customizer_css') ? theme_get_customizer_css() : '';

    $main_css_file = get_template_directory() . "/styles/main-min.css";
    $style_css = get_stylesheet_directory() . '/style.css';
    $splide_css_file = get_template_directory() . '/styles/vendor/splide/splide-core.min.css';
    // Splide critical CSS is only added when carousels are present
    $include_splide = growthlabseotheme01_request_uses_splide() && file_exists($splide_css_file);
    $theme_uri = get_template_directory_uri();

    // The cache key covers everything the output depends on: theme URI, file mtimes, included files and dynamic CSS
    $files = array($main_css_file, $style_css);
    if ($include_splide) $files[] = $splide_css_file;
    foreach ($block_files as $type_files) {
        foreach ($type_files as $file) $files[] = $file;
    }
    $mtimes = array();
    foreach ($files as $file) {
        $mtimes[$file] = file_exists($file) ? filemtime($file) : 0;
    }
    $cache_key = 'growthlabseotheme01_critical_css_' . md5(serialize(array(
        // This file builds the CSS, so changing it invalidates the cache too
        @filemtime(__FILE__),
        $theme_uri,
        $mtimes,
        $block_files,
        $include_splide,
        md5(serialize(array($fonts_css, $color_scheme))),
    )));

    // Customizer previews change fonts/colors on every refresh, so don't fill the cache from them
    $use_cache = !is_customize_preview();
    $critical_css = $use_cache ? get_transient($cache_key) : false;

    if (!is_string($critical_css)) {
        $critical_css = '';
        if (file_exists($main_css_file)) {
            $critical_css .= file_get_contents($main_css_file);
        }

        // Add theme stylesheet
        if (file_exists($style_css)) {
            $critical_css .= file_get_contents($style_css);
        }

        $critical_css = $fonts_css . $color_scheme . $critical_css;

        if ($include_splide) {
            $critical_css .= "\n/* Splide Critical CSS */\n" . file_get_contents($splide_css_file);
        }

        // Add block critical CSS if any, wrapping each block type in a comment for debugging
        $block_critical_css = '';
        foreach ($block_files as $type => $type_files) {
            $block_critical_css .= "/* Block type: {$type} */\n";
            foreach ($type_files as $file) {
                $block_critical_css .= file_get_contents($file) . "\n";
            }
        }
        if (!empty($block_critical_css)) {
            $critical_css .= "\n/* Block Critical CSS */\n" . $block_critical_css;
        }

        $critical_css = preg_replace('/\{theme-path\}/', $theme_uri, $critical_css);
        // Minify CSS: remove comments, extra spaces, and newlines
        $critical_css = preg_replace('/\/\*.*?\*\//s', '', $critical_css); // Remove CSS comments
        $critical_css = preg_replace('/\s+/', ' ', $critical_css); // Collapse whitespace runs into one space
        // Spaces around braces and semicolons are never significant; the ones around ":" and "," can be (".a :hover", strings)
        $critical_css = preg_replace('/\s*([{};])\s*/', '$1', $critical_css);
        $critical_css = trim($critical_css); // Trim leading/trailing whitespace

        // An expiration keeps the transient out of the autoloaded options
        if ($use_cache) set_transient($cache_key, $critical_css, WEEK_IN_SECONDS);
    }

    echo '<style id="main-css">' . $critical_css . '</style>';
}
add_action('wp_head', 'inline_main_critical_css', 10);

function growthlabseotheme01_scripts()
{
    if (is_admin()) return;

    // Move jQuery to footer (safe for GF)
    wp_scripts()->add_data('jquery', 'group', 1);
    wp_scripts()->add_data('jquery-core', 'group', 1);

    // Drop jQuery Migrate from the jquery dependencies (not needed for modern GF).
    // Deregistering it instead would make WordPress skip jquery and every script that depends on it.
    $jquery = wp_scripts()->query('jquery', 'registered');
    if ($jquery) {
        $jquery->deps = array_values(array_diff($jquery->deps, ['jquery-migrate']));
    }

    // Gravity Forms - remove maps
    wp_dequeue_script('gform_gravityforms_maps');

    // Main JS scripts.
    wp_enqueue_script(
        'growthlabseotheme01-main-scripts',
        get_template_directory_uri() . '/js/main-min.js',
        array(),
        filemtime(get_template_directory() . '/js/main-min.js'),
        true
    );
    wp_script_add_data('growthlabseotheme01-main-scripts', 'strategy', 'defer');
    wp_localize_script('growthlabseotheme01-main-scripts', 'siteData', [
        'homeURL' => home_url(),
    ]);

    // Styled select fields.
    wp_enqueue_script(
        'growthlabseotheme01-styled-select-fields',
        get_template_directory_uri() . '/js/styled-select-fields-min.js',
        array(),
        filemtime(get_template_directory() . '/js/styled-select-fields-min.js'),
        true
    );
    wp_script_add_data('growthlabseotheme01-styled-select-fields', 'strategy', 'defer');

    // Picture Optimization scripts.
    if (file_exists(get_template_directory() . '/js/picture-optimization-min.js')) {
        wp_enqueue_script(
            'growthlabseotheme01-picture-optimization',
            get_template_directory_uri() . '/js/picture-optimization-min.js',
            array(),
            filemtime(get_template_directory() . '/js/picture-optimization-min.js'),
            true
        );
        wp_script_add_data('growthlabseotheme01-picture-optimization', 'strategy', 'defer');
    }

    // Third party JS scripts.
    wp_localize_script('growthlabseotheme01-main-scripts', 'splideData', [
        'url' => get_template_directory_uri() . '/js/vendor/splide/splide-min.js',
    ]);

    // Load specific template stylesheet
    if (is_page() || is_single()) {
        if (!is_page_template('page-templates/template-full-width.php')) {
            wp_enqueue_style('growthlabseotheme01-template-default', get_template_directory_uri() . '/styles/page-templates/template-default-min.css', array(),  filemtime(get_template_directory() . '/styles/page-templates/template-default-min.css'));
        }
        if (is_singular('team')) {
            wp_enqueue_style('growthlabseotheme01-template-team-member', get_template_directory_uri() . '/styles/page-templates/template-team-member-min.css', array(),  filemtime(get_template_directory() . '/styles/page-templates/template-team-member-min.css'));
        }
    }
    if (is_listing_page()) {
        wp_enqueue_style('growthlabseotheme01-template-default', get_template_directory_uri() . '/styles/page-templates/template-default-min.css', array(),  filemtime(get_template_directory() . '/styles/page-templates/template-default-min.css'));
        wp_enqueue_style('growthlabseotheme01-blog', get_template_directory_uri() . '/styles/page-templates/template-blog-min.css', array(),  filemtime(get_template_directory() . '/styles/page-templates/template-blog-min.css'));
    }
}

add_action('wp_enqueue_scripts', 'growthlabseotheme01_scripts');

// Add theme and parent/child theme classes to body
add_filter('body_class', function ($classes) {
    if (is_child_theme()) {
        $theme = wp_get_theme();
        $classes[] = 'theme-child-' . sanitize_html_class($theme->get_stylesheet());
        $classes[] = 'theme-parent-' . sanitize_html_class($theme->get_template());
    }
    return $classes;
});


/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 *
 * @return void
 */
function growthlabseotheme01_widgets_init()
{

    register_sidebar(
        array(
            'name'          => esc_html__('Default Sidebar', 'growthlabseotheme01'),
            'id'            => 'sidebar-default',
            'description'   => esc_html__('Add widgets here to appear in the page sidebar.', 'growthlabseotheme01'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<p class="widget-title">',
            'after_title'   => '</p>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Blog Sidebar', 'growthlabseotheme01'),
            'id'            => 'sidebar-blog',
            'description'   => esc_html__('Add widgets here to appear in the Blog sidebar.', 'growthlabseotheme01'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<p class="widget-title">',
            'after_title'   => '</p>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Default Sidebar Spanish', 'growthlabseotheme01'),
            'id'            => 'sidebar-default-es',
            'description'   => esc_html__('Add widgets here to appear in the page Spanish sidebar.', 'growthlabseotheme01'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<p class="widget-title">',
            'after_title'   => '</p>',
        )
    );

    register_sidebar(
        array(
            'name'          => esc_html__('Blog Sidebar Spanish', 'growthlabseotheme01'),
            'id'            => 'sidebar-blog-es',
            'description'   => esc_html__('Add widgets here to appear in the Blog Spanish sidebar.', 'growthlabseotheme01'),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<p class="widget-title">',
            'after_title'   => '</p>',
        )
    );
}

add_action('widgets_init', 'growthlabseotheme01_widgets_init');


// Gravity Forms 
add_filter('gform_disable_css', '__return_true');
add_filter('gform_disable_theme_editor_styles', '__return_true');
add_filter('gform_init_scripts_footer', '__return_true');


//Import All Theme Icons to the Media Library
//Run only once after theme installation
function import_theme_images_to_folder()
{
    // Path to your theme's images folder
    $image_folder = get_template_directory() . '/assets/icons/';

    // Create custom folder in uploads
    $upload_dir = wp_upload_dir();
    $custom_folder = $upload_dir['basedir'] . '/theme-icons/';
    $custom_url = $upload_dir['baseurl'] . '/theme-icons/';

    // Create folder if it doesn't exist
    if (!file_exists($custom_folder)) {
        wp_mkdir_p($custom_folder);
    }

    // Get all image files
    $images = glob($image_folder . '*.{svg}', GLOB_BRACE);

    foreach ($images as $image_path) {
        $filename = basename($image_path);

        // Check if file already exists
        $existing = get_posts([
            'post_type' => 'attachment',
            'meta_query' => [[
                'key' => '_wp_attached_file',
                'value' => 'theme-icons/' . $filename,
                'compare' => '='
            ]]
        ]);

        if (!empty($existing)) continue;

        // Copy file to custom folder
        $new_file = $custom_folder . $filename;
        copy($image_path, $new_file);

        // Create attachment
        $attachment = [
            'post_mime_type' => mime_content_type($new_file),
            'post_title' => sanitize_file_name(pathinfo($filename, PATHINFO_FILENAME)),
            'post_content' => '',
            'post_status' => 'inherit'
        ];

        $attach_id = wp_insert_attachment($attachment, $new_file);

        // Update attachment metadata with correct path
        update_attached_file($attach_id, 'theme-icons/' . $filename);

        // Generate metadata
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $new_file);
        wp_update_attachment_metadata($attach_id, $attach_data);
    }
}

// Run once by visiting: yoursite.com/?import_theme_images=1
/* if (isset($_GET['import_theme_images']) && current_user_can('manage_options')) {
    import_theme_images_to_folder();
    wp_die('Images imported to /uploads/theme-icons/!');
} */

// Force the widget sections to stay available
add_action('customize_register', function ($wp_customize) {
    // Check for and force the widgets panel
    $widgets_panel = $wp_customize->get_panel('widgets');
    if ($widgets_panel) {
        $widgets_panel->active_callback = '__return_true';
    }

    // Force the specific sections to always be active
    $sidebar_default = $wp_customize->get_section('sidebar-widgets-sidebar-default');
    if ($sidebar_default) {
        $sidebar_default->active_callback = '__return_true';
    }

    $sidebar_blog = $wp_customize->get_section('sidebar-widgets-sidebar-blog');
    if ($sidebar_blog) {
        $sidebar_blog->active_callback = '__return_true';
    }

    $sidebar_default_es = $wp_customize->get_section('sidebar-widgets-sidebar-default-es');
    if ($sidebar_default_es) {
        $sidebar_default_es->active_callback = '__return_true';
    }

    $sidebar_blog_es = $wp_customize->get_section('sidebar-widgets-sidebar-blog-es');
    if ($sidebar_blog_es) {
        $sidebar_blog_es->active_callback = '__return_true';
    }
}, 999);

// Prevent the Customizer from dynamically hiding widget sections
add_action('customize_controls_print_footer_scripts', function () {
?>
    <script>
        (function($) {
            wp.customize.bind('ready', function() {
                // Force the widget panels to stay visible
                var widgetsPanel = wp.customize.panel('widgets');
                if (widgetsPanel) {
                    widgetsPanel.active.set(true);

                    // Prevent it from being hidden
                    widgetsPanel.active.validate = function() {
                        return true;
                    };
                }

                // Force specific sections
                ['sidebar-widgets-sidebar-default', 'sidebar-widgets-sidebar-blog', 'sidebar-widgets-sidebar-default-es', 'sidebar-widgets-sidebar-blog-es'].forEach(function(sectionId) {
                    var section = wp.customize.section(sectionId);
                    if (section) {
                        section.active.set(true);
                        section.active.validate = function() {
                            return true;
                        };
                    }
                });
            });
        })(jQuery);
    </script>
<?php
}, 999);


// Add dynamic versioning to theme scripts and styles
add_filter('script_loader_src', 'add_dynamic_version_to_theme_assets', 10, 2);
add_filter('style_loader_src', 'add_dynamic_version_to_theme_assets', 10, 2);

/**
 * Sets ?ver= to the file's mtime for scripts and styles served from the theme directory, or from the active child
 * theme directory when there is one, so browsers only re-download them when they change.
 *
 * @param string $src    Asset URL.
 * @param string $handle Asset handle.
 * @return string
 */
function add_dynamic_version_to_theme_assets($src, $handle)
{
    // Child theme first: its directory name usually starts with the parent's, so the parent prefix would match it too
    $theme_dirs = array();
    if (get_stylesheet_directory() !== get_template_directory()) {
        $theme_dirs[get_stylesheet_directory_uri()] = get_stylesheet_directory();
    }
    $theme_dirs[get_template_directory_uri()] = get_template_directory();

    // Remove scheme for comparison
    $src_normalized = preg_replace('(^https?:)', '', $src);

    foreach ($theme_dirs as $theme_dir_uri => $theme_dir) {
        $theme_dir_normalized = preg_replace('(^https?:)', '', $theme_dir_uri);
        if (strpos($src_normalized, $theme_dir_normalized . '/') !== 0) continue;

        // Extract path without query string
        $path_parts = parse_url($src);
        $path = $path_parts['path'] ?? '';

        // Remove the theme directory from path to get relative path
        $relative_path = str_replace(parse_url($theme_dir_uri, PHP_URL_PATH), '', $path);
        $file_path = $theme_dir . $relative_path;

        if (file_exists($file_path)) {
            $version = filemtime($file_path);
            // Remove existing ver parameter and add new one
            $src = remove_query_arg('ver', $src);
            $src = add_query_arg('ver', $version, $src);
        }
        break;
    }
    return $src;
}
