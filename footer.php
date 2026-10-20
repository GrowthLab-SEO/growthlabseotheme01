  <?php
  if (!defined('ABSPATH')) {
    exit;
  }
  ?>

  <footer role="contentinfo" id="site-footer" class="site-footer">

    <?php
    $es = filterContentByLanguage() ? '_es' : '';
    $options = get_field_options('options' . $es);
    $options = fill_acf_group_defaults($options, 'options', 'option');
    foreach ($options as $key => $value) $$key = $value;
    $phone_number = $contact_phone ?: $main_phone_number;
    ?>

    <?php
    if (!$form_section['hide_section'] && !get_page_field('hide_form_section')):
    ?>
      <section class="contact-form-footer">

        <div class="contact-form-footer__wrapper">
          <?php if (!empty($form_section['background_image'])) img_print_picture_tag(img: $form_section['background_image'], is_cover: true, classes: "contact-form-footer__bg bg-image gradient-overlay"); ?>

          <div class="contact-form-footer__inner container">

            <div class="contact-form shadow-box">

              <?php
              print_title($form_section['contact_form_title'] ?? '', $form_section['contact_form_title_tag'] ?? '', "contact-form__title tx-center");
              get_template_part('template-parts/ampersand', 'separator', array('classes' => 'contact-form__separator'));
              ?>

              <div class="contact-form__description formatted-text tx-center">
                <?php echo wp_kses_post(wpautop($form_section['contact_form_description'] ?? '')); ?>
              </div>

              <div class="contact-form__form">
                <?php render_gravity_form($form_section['contact_form'] ?? null, display_title: false, display_description: false); ?>
              </div>

              <?php if (!empty($form_section['message_before_submit'])): ?>
                <div class="contact-form__message formatted-text tx-center flex-center">
                  <?= wp_kses_post($form_section['message_before_submit']) ?>
                </div>
              <?php endif; ?>

            </div>

            <?php if (!empty($form_section['side_picture'])) img_print_picture_tag(img: $form_section['side_picture'], max_size: "large", min_size: "content",  classes: "contact-form-footer__side-pic shadow-box"); ?>
          </div>

        </div>
      </section>
    <?php
    endif;
    ?>

    <?php
    if (!$locations_section['hide_section'] && !get_page_field('hide_locations_section')):
    ?>

      <section class="locations-footer bg-bicolor">
        <div class="locations-footer__wrapper container border-box">

          <div class="locations-footer__content tx-center">
            <?php
            print_title($locations_section['locations_title'] ?? '', $locations_section['locations_title_tag'] ?? '', "locations-footer__title");
            get_template_part('template-parts/ampersand', 'separator', array('classes' => 'locations-footer__separator'));
            echo wp_kses_post($locations_section['locations_main_content'] ?? '');
            ?>
          </div>

          <div class="locations-cards">

            <?php if (empty($locations_section['hide_first_card']) && !empty($locations_section['first_card']['content'])): ?>

              <div class="location-card location-card--first">
                <div class="location-card__wrapper">
                  <div class="location-card__inner flex-center">
                    <?php if (!empty($locations_section['first_card']['logo'])) img_print_picture_tag(img: $locations_section['first_card']['logo'], max_size: "thumbnail",  classes: "location-card__logo"); ?>
                    <div class="location-card__content tx-center">
                      <?= wp_kses_post($locations_section['first_card']['content']) ?>
                    </div>
                  </div>
                </div>
              </div>

            <?php endif; ?>

            <?php
            $locations = $options['offices'];
            if (!empty($locations)):
            ?>
              <div class="locations-cards__carousel">

                <?php if (count($locations) > 1): ?>
                  <div class="splide">
                    <div class="splide__track">
                      <div class="splide__list">
                      <?php endif; ?>

                      <?php
                      foreach ($locations as $location) {
                        get_template_part('template-parts/location', 'card', array('location' => $location, 'classes' => 'splide__slide'));
                      }
                      ?>

                      <?php if (count($locations) > 1): ?>
                      </div>
                    </div>
                    <?php
                        get_template_part('template-parts/splide', 'navigation', array(
                          'nav_link' => $locations_section['locations_page_link'] ?? null,
                          'classes' => 'locations-cards__arrows'
                        ));
                    ?>
                  </div>
                <?php endif ?>

              </div>
            <?php
            endif;
            ?>

          </div>

        </div>
      </section>

    <?php
    endif;
    ?>

    <?php
    if (!$copyright_section['hide_section'] && !get_page_field("hide_copyright_section")):
    ?>
      <section class="copyright-footer">
        <div class="copyright-footer__wrapper container">

          <?php if (!get_page_field("hide_social_networks")): ?>
            <div class="copyright-footer__social">
              <?php get_template_part('template-parts/social', 'networks'); ?>
            </div>
          <?php endif ?>

          <?php
          $footer_links_menu = get_page_field("footer_links_menu") ?: ($copyright_section['footer_links_menu'] ?? null);

          if (isset($footer_links_menu) && $footer_links_menu && !get_page_field("hide_menu") && !empty($footer_links_menu)) {
            wp_nav_menu(
              array(
                'menu'  => $footer_links_menu,
                'container'          => 'nav',
                'container_class' => 'footer-nav',
                'menu_class'      => 'footer-nav__menu tx-center',
                'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
                'link_before'          => '<span>',
                'link_after'              => '</span>'
              )
            );
            get_template_part('template-parts/ampersand', 'separator', array('classes' => 'copyright-footer__separator'));
          }
          ?>

          <a href="https://growthlabseo.com/" target="_blank" rel="noopener noreferrer" class="copyright-footer__logo">
            <img src="<?= esc_url(get_template_directory_uri() . "/assets/img/Growth-Lab-Logo.png") ?>" alt="Growth Lab SEO Logo" width="270" height="50">
          </a>

          <p class="copyright-footer__advertisement tx-center">
            <?= wp_kses_post($copyright_section['copyright'] ?? '') ?>
          </p>

        </div>
      </section>
    <?php
    endif;
    ?>

  </footer>

  <?php wp_footer(); ?>

  </body>

  </html>