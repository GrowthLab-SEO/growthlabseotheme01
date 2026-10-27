<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    foreach (get_block_fields($block) as $key => $value) $$key = $value;
    $background_type = get_valid_field_choice('background_type', $background_type ?? '');
    $title_tag = get_valid_field_choice('title_tag', $title_tag ?? '');
    $reverse = !empty($reverse_order) ? "reverse" : "";
    $has_text = !empty($text_content) || !empty($cta_link['url']);
    $has_image = !empty($image);
?>

    <section<?= get_block_id_attribute($block_id ?? '') ?>
        class="block two-columns <?= esc_attr($background_type) ?><?= $reverse ? ' reverse' : '' ?><?= !empty($show_top_separator) ? ' separator' : '' ?><?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>"<?= block_style_attribute($block) ?>
        <?= get_block_extract_attribute($extract_block_from_content ?? false, $place ?? '') ?>>

        <?php
        if ($background_type === "img" && !empty($background_image)) img_print_picture_tag(img: $background_image, is_cover: true, classes: "two-columns__bg bg-image gradient-overlay", context: 'block/two-columns/background');
        if (!empty($show_top_separator)) get_template_part("template-parts/ampersand", "separator", ["classes" => "two-columns__separator container"]);
        ?>

        <div class="two-columns__wrapper container <?= $reverse ?>">
            <?php print_title($title ?? '', $title_tag, "two-columns__title"); ?>

            <?php if ($has_text): ?>
                <div class="two-columns__col">
                    <div class="formatted-text two-columns__content">
                        <?= $text_content ?? "" ?>

                        <?php if (!empty($cta_link['url'])):
                            $cta_target = !empty($cta_link['target']) ? $cta_link['target'] : '_self';
                            $cta_title = $cta_link['title'] ?? '';
                        ?>
                            <a href="<?= esc_url($cta_link['url']) ?>" target="<?= esc_attr($cta_target) ?>" <?= $cta_target === '_blank' ? 'rel="noopener noreferrer" ' : '' ?>class="two-columns__btn btn btn--tertiary">
                                <span><?= esc_html($cta_title) ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($has_image): ?>
                <div class="two-columns__col two-columns__col--image">
                    <?php
                    img_print_picture_tag(
                        img: $image,
                        max_size: "large",
                        min_size: "content",
                        classes: "two-columns__image $reverse",
                        context: 'block/two-columns/image'
                    );
                    ?>
                </div>
            <?php endif; ?>
        </div>

    </section>

<?php
endif;
?>
