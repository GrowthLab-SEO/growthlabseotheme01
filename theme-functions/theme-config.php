<?php
/**
 * Design data of this theme. The theme functions shared by the GrowthLab themes (growthlab-theme-core) read it, so
 * this file is the one place where the themes differ in these values. It is not synced between themes.
 */

if (!defined('ABSPATH')) {
    exit;
}

// Default colors of the color scheme (Customizer > Colors), read by color-scheme.php, the editor palettes and TinyMCE
$default_colors = [
    'primary' => [
        'default' => '#15253f',
        'dark'    => '#08182f',
        'light'   => '#2C3D5B'
    ],
    'secondary' => [
        'default' => '#F4F3EE',
        'dark'    => '#E7E5DF',
        'light'   => '#FFFFFF'
    ],
    'tertiary' => [
        'default' => '#BC9061',
        'dark'    => '#9D7A55',
        'light'   => '#DCAB77'
    ],
    'text' => '#15253f'
];

// Default fonts (Customizer > Fonts), read by fonts-selector.php
$default_fonts = [
    'primary' => 'Open Sans',
    'secondary' => 'Fraunces'
];

if (!function_exists('theme_get_config')) {
    /**
     * Design values of this theme, by key:
     * - font_faces: font files bundled in /fonts, by family (see theme_get_default_font_faces())
     * - above_fold_font_variants: font variants the header and the hero render, measured on this theme's templates;
     *   optional keys 'hero_styles', 'media' and 'when' (see theme_get_above_fold_font_variants())
     * - first_block_font_variants: font variants of the first block after the hero, by block name ("core" for core
     *   blocks, "default" for the rest). Codes: "s600" = secondary font weight 600, "p400" = primary font weight 400
     * - excerpt_length: words of the excerpts, 'default' and optionally by post type
     * - splide_blocks: blocks that render a Splide carousel (its scripts and CSS load only when one is on the page)
     */
    function theme_get_config(string $key)
    {
        $homepage_heroes = ['home', 'home_2', 'home_3', 'home_4', 'home_5', 'home_6'];

        $config = [
            'font_faces' => [
                'Fraunces' => [
                    ['weight' => '400', 'style' => 'normal', 'file' => 'fraunces-v38-latin/fraunces-v38-latin-regular.woff2', 'display' => 'swap'],
                    ['weight' => '300', 'style' => 'normal', 'file' => 'fraunces-v38-latin/fraunces-v38-latin-300.woff2', 'display' => 'optional'],
                    ['weight' => '500', 'style' => 'normal', 'file' => 'fraunces-v38-latin/fraunces-v38-latin-500.woff2', 'display' => 'optional'],
                    ['weight' => '600', 'style' => 'normal', 'file' => 'fraunces-v38-latin/fraunces-v38-latin-600.woff2', 'display' => 'swap'],
                    ['weight' => '700', 'style' => 'normal', 'file' => 'fraunces-v38-latin/fraunces-v38-latin-700.woff2', 'display' => 'swap'],
                ],
                'Open Sans' => [
                    ['weight' => '400', 'style' => 'normal', 'file' => 'open-sans-v44-latin/open-sans-v44-latin-regular.woff2', 'display' => 'swap'],
                    ['weight' => '500', 'style' => 'normal', 'file' => 'open-sans-v44-latin/open-sans-v44-latin-500.woff2', 'display' => 'optional'],
                    ['weight' => '600', 'style' => 'normal', 'file' => 'open-sans-v44-latin/open-sans-v44-latin-600.woff2', 'display' => 'swap'],
                    ['weight' => '700', 'style' => 'normal', 'file' => 'open-sans-v44-latin/open-sans-v44-latin-700.woff2', 'display' => 'swap'],
                ],
            ],
            'above_fold_font_variants' => [
                // Hero title
                ['role' => 'secondary', 'weight' => '600', 'style' => 'normal'],
                // Header menu, callout and hero text
                ['role' => 'primary', 'weight' => '400', 'style' => 'normal'],
                ['role' => 'primary', 'weight' => '700', 'style' => 'normal'],
            ],
            'first_block_font_variants' => [
                'acf/contact-form'      => ['s600', 'p400', 'p700'],
                'acf/content-box'       => ['s600', 'p400'],
                'acf/content-intro'     => ['s600', 'p400', 'p700'],
                'acf/cta-box'           => ['s600', 'p400', 'p700'],
                'acf/faq'               => ['s600', 'p400', 'p700'],
                'acf/featured-video'    => ['s600', 'p400'],
                'acf/logos-carousel'    => ['s600'],
                'acf/office-locations'  => ['s600', 'p400', 'p700', 'p600'],
                'acf/posts-carousel'    => ['s600', 'p400', 'p700'],
                'acf/posts-grid'        => ['s600', 'p400', 'p700'],
                'acf/selling-points'    => ['s600', 'p700'],
                'acf/trust-cards'       => ['s600', 's400', 'p400', 'p700'],
                'acf/two-columns'       => ['s600', 'p400', 'p700'],
                'core'                  => ['p400', 'p700'],
                'default'               => ['s600', 'p400', 'p700'],
            ],
            'excerpt_length' => ['default' => 15],
            'splide_blocks' => ['acf/office-locations', 'acf/logos-carousel', 'acf/posts-carousel', 'acf/selling-points', 'acf/trust-cards'],
        ];

        return $config[$key] ?? null;
    }
}
