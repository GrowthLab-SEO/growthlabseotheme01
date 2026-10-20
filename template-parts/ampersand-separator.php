<?php
if (!defined('ABSPATH')) {
    exit;
}
foreach ($args as $key => $value) $$key = $value;
$es = filterContentByLanguage() ? '_es' : '';
$options = get_field_options('options' . $es);
?>

<div class="ampersand-separator <?= esc_attr($classes); ?>">
    <hr>
    <?php
    if (isset($options["logo_symbol"]) && $options["logo_symbol"]) {
        img_print_picture_tag(img: $options["logo_symbol"], max_size: "medium");
    } else {
        echo get_theme_svg('assets/img/separator-symbol.svg');
    }
    ?>
    <hr>
</div>