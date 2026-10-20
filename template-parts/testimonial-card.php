<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="testimonial-card <?= esc_attr($args["classes"] ?? "") ?>">
    <div class="testimonial-card__wrapper">
        <div class="testimonial-card__inner">

            <div class="testimonial-card__header">
                <?php if (isset($args['picture']) && $args['picture']) img_print_picture_tag(img: $args['picture'], max_size: "thumbnail", classes: "testimonial-card__pic"); ?>
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
                            <a href="<?= esc_url($args['link_url']) ?>" target="_blank" rel="noopener noreferrer" aria-label="Read More">Read More</a>
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