<?php
if (!defined('ABSPATH')) {
    exit;
}
$read_more_label = sprintf(
    /* translators: %s: testimonial author */
    get_bilingual_text(__('Read the full review by %s', 'growthlabseotheme01'), 'Leer la reseña completa de %s'),
    wp_strip_all_tags($args['author'] ?? '')
);
?>

<div class="testimonial-card <?= esc_attr($args["classes"] ?? "") ?>">
    <div class="testimonial-card__wrapper">
        <div class="testimonial-card__inner">

            <div class="testimonial-card__header">
                <?php if (isset($args['picture']) && $args['picture']) img_print_picture_tag(img: $args['picture'], max_size: "thumbnail", classes: "testimonial-card__pic", context: 'card/testimonial/picture'); ?>
                <?php if ($args["source"]): ?>
                    <div class="testimonial-card__src">
                        <?php
                        switch ($args["source"]) {
                            case 'fb':
                                echo get_theme_svg('assets/icons/icon-facebook.svg');
                                break;
                            case 'inst':
                                echo get_theme_svg('assets/icons/icon-instagram.svg');
                                break;
                            case 'gg':
                                echo get_theme_svg('assets/icons/icon-google.svg');
                                break;
                            case 'lin':
                                echo get_theme_svg('assets/icons/icon-linkedin.svg');
                                break;
                            case 'x':
                                echo get_theme_svg('assets/icons/icon-twitter-x.svg');
                                break;
                            default:
                                break;
                        }
                        ?>
                    </div>
                <?php endif ?>

                <div class="testimonial-card__quote">
                    <?php echo get_theme_svg('assets/icons/icon-quote.svg'); ?>
                </div>
            </div>

            <?php if (isset($args["condition"]) && !$args["condition"] && isset($args["content"]) && $args["content"] !== "") : ?>
                <blockquote class="testimonial-card__content">
                    <p>
                        <?= '"' . wp_kses_post($args["content"]) . '"' ?>

                        <?php if ($args['link_url']): ?>
                            <a href="<?= esc_url($args['link_url']) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= esc_attr($read_more_label) ?>"><?= esc_html(get_bilingual_text(__('Read More', 'growthlabseotheme01'), 'Leer más')) ?></a>
                        <?php endif ?>
                    </p>
                </blockquote>
            <?php endif ?>

            <div class=" testimonial-card__stars">
                <?php
                $i = 1;
                while ($i <= 5) {
                    echo get_theme_svg('assets/icons/icon-star.svg');
                    $i++;
                }
                ?>
            </div>

            <p class="testimonial-card__author">
                <?= wp_kses_post($args["author"] ?? "") ?>
                <span><?= wp_kses_post($args["role"] ?? "") ?></span>
            </p>

        </div>
    </div>
</div>