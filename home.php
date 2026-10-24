<?php
if (!defined('ABSPATH')) {
    exit;
}
get_header();
$post_id = (int) get_option('page_for_posts');
// Blog listings follow the sidebar setting of the posts page
$hide_sidebar = $post_id && get_field("hide_sidebar", $post_id);

$es = filterContentByLanguage() ? '_es' : '';
$options = get_field_options('options' . $es);
?>

<section class="blog__inner bg-bicolor">

    <div class="blog__wrapper container">

        <?php get_template_part("template-parts/posts", "filters", array("classes" => "blog__filters")) ?>

        <main id="main-content" role="main" class="blog__main border-box">

            <div class="blog__loop">

                <?php while (have_posts()) {

                    the_post();

                    $card_args = array(
                        "classes" => "blog__card ",
                        "picture" => get_the_post_thumbnail_url(),
                        "meta" => get_the_date(),
                        "title" => get_the_title(),
                        "tag" => ($options['posts_default_title_tag'] ?? null) ?: "p",
                        "excerpt" => get_the_excerpt(),
                        "link_url" => get_the_permalink(),
                        "link_target" => '_self',
                    );
                    get_template_part('template-parts/post', 'card', $card_args);

                    // The blog filters build their cards from this one (placeholders instead of the post values)
                    if ($GLOBALS['wp_query']->current_post === 0) theme_print_posts_filters_card_template($card_args);
                }
                ?>

            </div>

            <?php get_template_part('template-parts/posts', 'pagination', array(
                'classes'    => 'blog__pagination',
                'paged'      => max(1, get_query_var('paged', 1)),
                'query'      => $wp_query,
            )); ?>
        </main>

        <?php
        if (!$hide_sidebar) {
            $args = array('ID' => $post_id, 'classes' => 'blog__sidebar');
            get_sidebar('blog', $args);
        }
        ?>

    </div>

</section>

<?php get_footer() ?>