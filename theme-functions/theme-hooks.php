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
