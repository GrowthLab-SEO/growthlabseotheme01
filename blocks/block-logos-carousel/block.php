<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    foreach (get_block_fields($block) as $key => $value) $$key = $value;

    // Accept hex colors, or rgba() since the color picker has opacity enabled
    $raw_background_color = trim((string) ($background_color ?? ''));
    $background_color = sanitize_hex_color($raw_background_color)
        ?: (preg_match('/^rgba?\(\s*[\d.\s,%]+\)$/i', $raw_background_color) ? $raw_background_color : '');

    // The background color shares the style attribute with the editor's spacing
    $style_block = $block;
    if ($background_color) {
        $block_style = $block['style'] ?? [];
        $style_block['style'] = is_array($block_style) ? $block_style : ['spacing' => (string) $block_style];
        $style_block['style']['background-color'] = $background_color;
    }
?>

    <section<?= get_block_id_attribute($block_id ?? '') ?>
        class="block logos-carousel<?php if (!$background_color) echo ' bg-bicolor'; ?><?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>"<?= block_style_attribute($style_block) ?>
        <?php
        echo get_block_extract_attribute($extract_block_from_content ?? false, $place ?? '');
        ?>>

        <div class="logos-carousel__wrapper container tx-center">
            <?php print_title($title, $title_tag, "logos-carousel__title") ?>

            <?php
            if (!empty($logos)):
            ?>
                <div class="logos-carousel__carousel">
                    <div class="splide">
                        <div class="splide__track">
                            <div class="splide__list">
                                <?php foreach ($logos as $logo):
                                    if (empty($logo)) continue;
                                    $has_link = !empty($logo['link']['url']);
                                ?>
                                    <div class="logo splide__slide">
                                        <?php if ($has_link): ?>
                                            <a href="<?= esc_url($logo['link']['url']); ?>"
                                                <?= !empty($logo['link']['target']) ? 'target="' . esc_attr($logo['link']['target']) . '"' . ($logo['link']['target'] === '_blank' ? ' rel="noopener noreferrer"' : '') : '' ?>
                                                class="logo__link">
                                            <?php endif; ?>

                                            <?= img_generate_picture_tag(
                                                img: $logo['logo'],
                                                max_size: count($logos) >= 5 ? "featured-small" : "medium",
                                                classes: "logo__pic"
                                            ); ?>


                                            <?php if ($has_link): ?>
                                                <span><?= esc_html($logo['link']['title'] ?? '') ?></span>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            endif;
            ?>
        </div>
    </section>

<?php
endif;
?>