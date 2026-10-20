<?php
if (!defined('ABSPATH')) {
    exit;
}
get_header();
?>

<main id="main-content" class="not-found bg-bicolor" style="padding-block: 200px;">

    <div class="container">

        <p class="h1 tx-center">404:</p>
        <h1 class="h2 tx-center"><?= esc_html(get_bilingual_text(__('Page Not Found', 'growthlabseotheme01'), 'Página no encontrada')) ?></h1>

    </div>

</main>

<?php get_footer() ?>