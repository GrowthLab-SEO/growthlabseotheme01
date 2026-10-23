<?php
if (!defined('ABSPATH')) {
    exit;
}

if (get_field('toggle_block')):
    foreach (get_block_fields($block) as $key => $value) $$key = $value;
?>

    <section<?= get_block_id_attribute($block_id ?? '') ?>
        class="block contact-form 
        <?php
        if (isset($side_picture) && $side_picture && !$show_only_contact_form) {
            echo "contact-form--side-pic ";
            if (($title || $main_content) && !$background_image) {
                echo "bg-bicolor";
            }
        } else if (!isset($background_image) || !$background_image) {
            echo "bg-bicolor";
        }
        ?><?= !empty($block['className']) ? ' ' . esc_attr($block['className']) : '' ?>"<?= block_style_attribute($block) ?>
        <?= get_block_extract_attribute(($extract_block_from_content ?? false) && !$show_only_contact_form, $place ?? '') ?>>

        <div class="contact-form__layer">

            <div class="contact-form__wrapper
            <?php
            if (isset($side_picture) && $side_picture && !$title && !$main_content && !$background_image) echo "bg-bicolor ";
            ?>
            ">
                <?php if (isset($background_image) && $background_image && !$show_only_contact_form) img_print_picture_tag(img: $background_image, is_cover: true, classes: "contact-form__bg bg-image gradient-overlay"); ?>

                <div class="contact-form__inner container <?php if (!$show_only_contact_form) echo "border-box" ?>">

                    <?php
                    if (!$show_only_contact_form):
                        if (isset($title) && $title) {
                            print_title($title, $title_tag, "contact-form__title");
                            get_template_part('template-parts/ampersand', 'separator', array('classes' => 'contact-form__separator'));
                        }

                        if (isset($main_content) && !empty($main_content)):
                    ?>
                            <div class="contact-form__content formatted-text tx-center">
                                <?= $main_content ?? '' ?>
                            </div>
                    <?php
                        endif;
                    endif;
                    ?>


                    <?php
                    $form_html = get_selected_form_html([
                        'contact_form'      => $contact_form ?? null,
                        'contact_form_type' => $contact_form_type ?? null,
                        'contact_form_code' => $contact_form_code ?? null,
                    ]);
                    if ($form_html !== ''):
                    ?>
                        <div class="form-box shadow-box">

                            <?php if (isset($contact_form_title) && $contact_form_title): ?>
                                <?php
                                print_title($contact_form_title, $contact_form_title_tag, "form-box__title tx-center");
                                get_template_part('template-parts/ampersand', 'separator', array('classes' => 'form-box__separator'));
                                ?>
                            <?php endif; ?>

                            <?php if (isset($contact_form_description) && $contact_form_description): ?>
                                <div class="form-box__description formatted-text tx-center">
                                    <?php echo wpautop($contact_form_description); ?>
                                </div>
                            <?php endif ?>

                            <div class="form-box__form">
                                <?= $form_html ?>
                            </div>

                            <?php if (isset($message_before_submit) && $message_before_submit): ?>
                                <div class="form-box__message formatted-text tx-center flex-center">
                                    <?= $message_before_submit ?? '' ?>
                                </div>
                            <?php endif; ?>

                        </div>
                    <?php endif; ?>

                    <?php if (isset($side_picture) && $side_picture && !$show_only_contact_form) img_print_picture_tag(img: $side_picture, max_size: "large", min_size: "medium", classes: "contact-form__side-pic shadow-box"); ?>
                </div>

            </div>

        </div>

    </section>

<?php
endif;
?>