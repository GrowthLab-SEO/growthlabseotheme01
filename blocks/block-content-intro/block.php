<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    $es = filterContentByLanguage() ? '_es' : '';
    $options = get_field_options('options' . $es);
    foreach (get_block_fields($block) as $key => $value) $$key = $value;
    if (isset($featured_image) && is_array($featured_image)) $featured_image['position'] = get_valid_field_choice('featured_image.position', $featured_image['position'] ?? '');
?>

    <section<?= get_block_id_attribute($block_id ?? '') ?>
        class="block content-intro <?php if (!$background_image) echo "bg-bicolor"; ?><?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>"<?= block_style_attribute($block) ?>
        <?= get_block_extract_attribute($extract_block_from_content ?? false, $place ?? '') ?>>

        <?php
        if (isset($background_image) && $background_image) img_print_picture_tag(img: $background_image, is_cover: true, classes: "content-intro__bg bg-image gradient-overlay");
        ?>

        <div class="content-intro__wrapper container">
            <div class="content-intro__inner">

                <?php if ($title || $first_paragraph): ?>
                    <div class="content-intro__heading tx-center <?php if ($first_paragraph) echo "border-box"; ?>">
                        <?php
                        if (isset($title) && $title) {
                            print_title($title, $title_tag, "content-intro__title");
                            get_template_part('template-parts/ampersand', 'separator', array('classes' => 'content-intro__separator'));
                        }

                        if (isset($first_paragraph) && $first_paragraph):
                        ?>
                            <div class="content-intro__heading-text formatted-text tx-center">
                                <?= $first_paragraph;  ?>
                            </div>
                        <?php endif ?>
                    </div>
                <?php endif ?>

                <div class="content-intro__content formatted-text">

                    <?php if (
                        isset($featured_image['picture']) && $featured_image['picture']
                        && $featured_image['position'] !== "below"
                    ): ?>
                        <div class="content-intro__pic-wrapper content-intro__pic-wrapper--<?= esc_attr($featured_image['position']) ?>">
                            <?php
                            img_print_picture_tag(img: $featured_image['picture'], max_size: "large", min_size: "content", classes: "content-intro__pic");
                            if (isset($featured_image['picture_caption']) && $featured_image['picture_caption']):
                            ?>

                                <div class="content-intro__caption">
                                    <?php if (isset($options["logo_symbol"]) && $options["logo_symbol"]) {
                                        img_print_picture_tag(img: $options["logo_symbol"], max_size: "medium");
                                    } else {
                                        echo get_theme_svg('assets/img/separator-symbol.svg');
                                    } ?>
                                    <p><?= $featured_image['picture_caption'] ?></p>
                                </div>

                            <?php endif ?>
                        </div>
                    <?php endif ?>

                    <?= $text_content ?>

                    <?php if ($cta_link): ?>
                        <div class="content-intro__btn">
                            <a href="<?= esc_url($cta_link['url']) ?>" target="<?= esc_attr($cta_link['target']) ?>" class="btn btn--secondary">
                                <span><?= esc_html($cta_link['title']) ?></span>
                            </a>
                        </div>
                    <?php endif ?>

                    <?php if (
                        isset($featured_image['picture']) && $featured_image['picture']
                        && $featured_image['position'] === "below"
                    ): ?>
                        <div class="content-intro__pic-wrapper content-intro__pic-wrapper--<?= esc_attr($featured_image['position']) ?>">
                            <?php
                            img_print_picture_tag(img: $featured_image['picture'], max_size: "large", min_size: "medium", classes: "content-intro__pic");
                            if (isset($featured_image['picture_caption']) && $featured_image['picture_caption']):
                            ?>

                                <div class="content-intro__caption">
                                    <?php if (isset($options["logo_symbol"]) && $options["logo_symbol"]) {
                                        img_print_picture_tag(img: $options["logo_symbol"], max_size: "medium");
                                    } else {
                                        echo get_theme_svg('assets/img/separator-symbol.svg');
                                    } ?>
                                    <p><?= $featured_image['picture_caption'] ?></p>
                                </div>

                            <?php endif ?>
                        </div>
                    <?php endif ?>

                </div>

            </div>
        </div>

    </section>

<?php
endif;
?>