<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    foreach (get_block_fields($block) as $key => $value) $$key = $value;
?>

    <section<?= get_block_id_attribute($block_id ?? '') ?>
        class="block content-box-grid bg-bicolor"
        <?= get_block_extract_attribute($extract_block_from_content ?? false, $place ?? '') ?>>

        <div class="content-box-grid__wrapper container">

            <?php
            if (isset($title) && $title) {
                print_title($title, $title_tag, "content-box-grid__title tx-center");
                get_template_part('template-parts/ampersand', 'separator', array('classes' => 'content-box-grid__separator'));
            }
            ?>

            <?php if ($text_content): ?>
                <div class="content-box-grid__content formatted-text tx-center">
                    <?= $text_content ?>
                </div>
            <?php endif ?>

            <ul class="content-box-grid__grid">
                <?php
                if (!$is_a_menu && $items && count($items) > 0):

                    foreach ($items as $item):
                        $item_title = $item['title'] ?? '';
                        $item_title_link = $item['title_link'] ?? null;
                        $item_text_content = $item['text_content'] ?? '';
                ?>

                        <li class="content-box">
                            <div class="content-box__wrapper">
                                <div class="content-box__inner">

                                    <?php if ($item_title || $item_text_content): ?>

                                        <div class="content-box__content formatted-text">

                                            <?php
                                            $title_html = $item_title;
                                            if ($item_title_link) $title_html = "<a href='" . esc_url($item_title_link['url']) . "' target='" . esc_attr($item_title_link['target']) . "'>" . $item_title . "</a>";
                                            print_title($title_html, $item['title_tag'] ?? 'p', "content-box__title");
                                            ?>
                                            <?= $item_text_content; ?>

                                        </div>

                                    <?php endif ?>

                                </div>
                            </div>
                        </li>

                    <?php
                    endforeach;

                elseif ($is_a_menu && $menu):
                    $menu_items = wp_get_nav_menu_items($menu);
                    foreach ($menu_items as $menu_item):
                    ?>

                        <li class="content-box content-box--menu-item">
                            <a href="<?= esc_url($menu_item->url) ?>" target="<?= esc_attr($menu_item->target) ?>" class="content-box__wrapper">
                                <div class="content-box__inner">

                                    <div class="content-box__content formatted-text">

                                        <strong class="content-box__title">
                                            <?= esc_html($menu_item->title) ?>
                                        </strong>

                                    </div>

                                </div>
                            </a>
                        </li>
                <?php
                    endforeach;
                endif;
                ?>
            </ul>

        </div>

    </section>

<?php
endif;
?>