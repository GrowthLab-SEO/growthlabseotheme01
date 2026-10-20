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
<article class="post-card <?= esc_attr($args["classes"] ?? '') ?>">
    <div class="post-card__wrapper">

        <?php
        if ($args['link_url']) {
            echo '<a href="' . esc_url($args['link_url']) . '" class="post-card__pic-wrapper" target="' . esc_attr($args['link_target']) . '"' . $link_rel . ' aria-label="' . esc_attr($args['title']) . '">';
        } else {
            echo '<div class="post-card__pic-wrapper">';
        }
        if (isset($args['picture']) && $args['picture']) {
            img_print_picture_tag(img: $args["picture"], max_size: "content", min_size: "featured-small", classes: "post-card__pic");
        } elseif ($default_image) {
            img_print_picture_tag(img: $default_image, max_size: "content", min_size: "featured-small", classes: "post-card__pic");
        } else {
            echo get_theme_svg('assets/icons/icon-file-image.svg');
        }
        if ($args['link_url']) {
            echo "</a>";
        } else {
            echo "</div>";
        }

        ?>

        <div class="post-card__inner">
            <span class="post-card__meta"><?= esc_html($args["meta"] ?? '') ?></span>

            <?php print_title($args["title"] ?? '', $tag, "post-card__title"); ?>

            <p class="post-card__content"><?= wp_kses_post($args["excerpt"] ?? '') ?></p>

            <?php if ($args['link_url']): ?>
                <div class="post-card__btn">
                    <a href="<?= esc_url($args['link_url']) ?>" target="<?= esc_attr($args['link_target']) ?>"<?= $link_rel ?> class="btn btn--secondary" aria-label="<?= esc_attr($read_more_label) ?>">
                        <span><?= esc_html(get_bilingual_text(__('Read More', 'growthlabseotheme01'), 'Leer más')) ?></span>
                    </a>
                </div>
            <?php endif ?>
        </div>
    </div>
</article>