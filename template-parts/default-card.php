<?php
if (!defined('ABSPATH')) {
    exit;
}

$es = filterContentByLanguage() ? '_es' : '';
$options = get_field_options('options' . $es);

$tag = ($args["tag"] ?? '') ?: (($options['posts_default_title_tag'] ?? null) ?: "p");

// The Spanish options fall back to the English default image when they have none
$default_image = ($options['posts_default_image'] ?? null) ?: (get_field_options('options')['posts_default_image'] ?? null);

$args["link_target"] = ($args["link_target"] ?? "") ?: "_self";
$link_rel = $args["link_target"] === "_blank" ? ' rel="noopener noreferrer"' : '';
$read_more_label = sprintf(
    /* translators: %s: post title */
    get_bilingual_text(__('Read more about %s', 'growthlabseotheme01'), 'Leer más sobre %s'),
    wp_strip_all_tags($args['title'] ?? '')
);
?>

<div class="default-card <?= esc_attr($args["classes"] ?? '') ?>">
    <div class="default-card__wrapper">

        <?php
        if ($args['link_url'] && $args['link_url'] !== '') {
            echo '<a href="' . esc_url($args['link_url']) . '" class="default-card__pic-wrapper" target="' . esc_attr($args['link_target']) . '"' . $link_rel . ' aria-label="' . esc_attr($args['title']) . '">';
        } else {
            echo '<div class="default-card__pic-wrapper">';
        }
        if (isset($args['picture']) && $args['picture']) {
            img_print_picture_tag(img: $args["picture"], max_size: "content", min_size: "featured-small", classes: "default-card__pic", context: 'card/default/picture');
        } elseif ($default_image) {
            img_print_picture_tag(img: $default_image, max_size: "content", min_size: "featured-small", classes: "default-card__pic", context: 'card/default/default-image');
        } else {
            echo get_theme_svg('assets/icons/icon-file-image.svg');
        }
        if ($args['link_url'] && $args['link_url'] !== '') {
            echo "<span>" . wp_kses_post($args['title']) . "</span></a>";
        } else {
            echo "</div>";
        }
        ?>

        <div class="default-card__inner tx-center">

            <?php print_title($args["title"] ?? '', $tag, "default-card__title"); ?>

            <p class="default-card__content"><?= wp_kses_post($args["content"] ?? '') ?></p>

            <?php if ($args['link_url'] && $args['link_url'] !== ''): ?>
                <div class="default-card__btn">
                    <a href="<?= esc_url($args['link_url']) ?>" target="<?= esc_attr($args['link_target']) ?>"<?= $link_rel ?> class="btn btn--secondary" aria-label="<?= esc_attr($read_more_label) ?>">
                        <span><?= esc_html(get_bilingual_text(__('Read More', 'growthlabseotheme01'), 'Leer más')) ?></span>
                    </a>
                </div>
            <?php endif ?>
        </div>
    </div>
</div>