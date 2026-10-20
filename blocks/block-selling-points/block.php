<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    foreach (get_block_fields($block) as $key => $value) $$key = $value;
?>

    <section<?= get_block_id_attribute($block_id ?? '') ?>
        class="block selling-points bg-bicolor<?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>"<?= block_style_attribute($block) ?>
        <?php
        echo get_block_extract_attribute($extract_block_from_content ?? false, $place ?? '');
        ?>>

        <div class="selling-points__wrapper container">

            <?php
            if (isset($title) && $title) {
                print_title($title, $title_tag, "selling-points__title");
                get_template_part('template-parts/ampersand', 'separator', array('classes' => 'selling-points__separator'));
            }
            if (isset($text_content) && $text_content):
            ?>
                <div class="selling-points__content formatted-text tx-center">
                    <?= $text_content ?>
                </div>
            <?php
            endif
            ?>

            <?php if (!empty($items)): ?>
                <div class="selling-points__carousel">
                    <div class="splide">
                        <div class="splide__track">
                            <div class="splide__list selling-points__grid">

                                <?php
                                foreach ($items as $item) :
                                    if (!empty($item)):
                                        $item_title = $item['title'] ?? '';
                                ?>

                                        <div class="item-card splide__slide">
                                            <div class="item-card__inner">
                                                <div class="item-card__icon">
                                                    <?php img_print_picture_tag(img: $item['icon'] ?? '', classes: 'item-card__icon-img', max_size: "thumbnail") ?>
                                                </div>

                                                <?php
                                                $title_html = !empty($item['link'])
                                                    ? "<a href='" . esc_url($item['link']["url"]) . "' target='" . esc_attr($item['link']["target"]) . "' class='item-card__link'>" . $item_title . "</a>"
                                                    : $item_title;
                                                print_title($title_html, $item['title_tag'] ?? 'p', "item-card__title");
                                                ?>

                                                <?php if (!empty($item['content'])): ?>
                                                    <p class="item-card__description"><?= $item['content'] ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                <?php
                                    endif;
                                endforeach;
                                ?>

                            </div>
                        </div>

                        <?php
                        get_template_part('template-parts/splide', 'navigation', array(
                            'nav_link' => isset($cta_link) && $cta_link ? $cta_link : null,
                            'classes' => 'selling-points__arrows'
                        ));
                        ?>

                    </div>
                </div>

            <?php endif; ?>
        </div>
    </section>

<?php
endif;
?>