<?php
/**
 * Behavior that only this theme has (its hero text fields, its bilingual setup). The rest of the theme functions are
 * shared by the GrowthLab themes (growthlab-theme-core). Loaded last.
 */

if (!defined('ABSPATH')) {
    exit;
}

// One-time migration of the hero title tag. Its default was "p" and the selector was ignored for a while (every hero
// title printed as h1), so most pages saved "p" without anyone choosing it. Turn those values into h1 once, so the
// restored selector doesn't remove the page h1; h2-h6 and later choices of "p" are kept.
add_action('init', function () {
    $option = 'growthlabseotheme01_hero_title_tag_h1_migration';
    if (get_option($option)) return;

    global $wpdb;
    $post_ids = $wpdb->get_col($wpdb->prepare(
        "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s",
        'hero_properties_hero_title_tag',
        'p'
    ));

    if ($post_ids) {
        $wpdb->update(
            $wpdb->postmeta,
            array('meta_value' => 'h1'),
            array('meta_key' => 'hero_properties_hero_title_tag', 'meta_value' => 'p')
        );
        foreach ($post_ids as $post_id) {
            wp_cache_delete((int) $post_id, 'post_meta');
        }
    }

    update_option($option, gmdate('c'), true);
});

// The footer offices are a carousel when there is more than one office
add_filter('growthlabseotheme01_request_uses_splide', function ($uses_splide) {
    if ($uses_splide) return true;

    $options = fill_acf_group_defaults(get_current_language_options(), 'options', 'option');
    $locations_section = $options['locations_section'] ?? array();

    return empty($locations_section['hide_section'])
        && !get_page_field('hide_locations_section')
        && !empty($options['offices'])
        && count($options['offices']) > 1;
});

// Spanish texts of the theme scripts on Spanish pages
add_filter('theme_main_script_data', function ($data) {
    $data['i18n']['closeVideo']  = esc_attr(get_bilingual_text(__('Close video', 'growthlabseotheme01'), 'Cerrar video'));
    $data['i18n']['videoDialog'] = esc_attr(get_bilingual_text(__('Video player', 'growthlabseotheme01'), 'Reproductor de video'));
    return $data;
});

add_filter('theme_styled_select_data', function ($data) {
    $data['placeholder'] = get_bilingual_text(__('Select an option', 'growthlabseotheme01'), 'Selecciona una opción');
    return $data;
});
