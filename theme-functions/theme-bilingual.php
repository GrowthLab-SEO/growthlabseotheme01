<?php
/**
 * Theme-01 is bilingual: English, and Spanish under the /es/ URL prefix, with its own Site Options group (options_es)
 * and without switching the WordPress locale. The shared helpers (helpers.php) handle languages through
 * get_languages_map() and get_current_language_options(): this file defines them for this setup, before helpers.php
 * loads, and adds the Spanish interface texts.
 */

if (!defined('ABSPATH')) {
    exit;
}

// URL language slugs and their names: Spanish under /es/
if (!function_exists('get_languages_map')) {
    function get_languages_map()
    {
        return ['es' => __('Spanish', 'growthlabseotheme01')];
    }
}

// Site Options of the current language: options_es on Spanish pages, options elsewhere
if (!function_exists('get_current_language_options')) {
    function get_current_language_options()
    {
        return get_field_options('options' . get_current_language_suffix());
    }
}

// Fixed interface strings: the Spanish text on Spanish pages, the (translatable) English text elsewhere
if (!function_exists('get_bilingual_text')) {
    function get_bilingual_text($english, $spanish)
    {
        return filterContentByLanguage() ? $spanish : $english;
    }
}

// Spanish fallback hero title of the search results
add_filter('theme_page_hero_title', function ($title) {
    if (!is_search() || !filterContentByLanguage()) return $title;
    /* translators: %s: search query */
    return sprintf('Resultados de búsqueda para: %s', get_search_query());
});

// Spanish texts of the shared templates and scripts (e.g. the blog filters) on Spanish pages: they use the theme's
// translatable strings, which this map replaces under /es/
add_filter('gettext_growthlabseotheme01', function ($translation, $text) {
    static $spanish = array(
        'Search posts'                              => 'Buscar entradas',
        'Title, keywords, content…'                 => 'Título, palabras clave, contenido…',
        'Category'                                  => 'Categoría',
        'All categories'                            => 'Todas las categorías',
        'Month'                                     => 'Mes',
        'All months'                                => 'Todos los meses',
        'Year'                                      => 'Año',
        'All years'                                 => 'Todos los años',
        'Apply filters'                             => 'Aplicar filtros',
        'Clear'                                     => 'Limpiar',
        'January'                                   => 'Enero',
        'February'                                  => 'Febrero',
        'March'                                     => 'Marzo',
        'April'                                     => 'Abril',
        'May'                                       => 'Mayo',
        'June'                                      => 'Junio',
        'July'                                      => 'Julio',
        'August'                                    => 'Agosto',
        'September'                                 => 'Septiembre',
        'October'                                   => 'Octubre',
        'November'                                  => 'Noviembre',
        'December'                                  => 'Diciembre',
        'No posts found for the selected filters.'  => 'No se encontraron entradas con los filtros seleccionados.',
        'Could not load posts. Please try again.'   => 'No se pudieron cargar las entradas. Inténtalo de nuevo.',
        'Read More'                                 => 'Leer más',
        'Read more about %s'                        => 'Leer más sobre %s',
        'Remove %s'                                 => 'Quitar %s',
        'Prev'                                      => 'Anterior',
        'Next'                                      => 'Siguiente',
    );

    if (!isset($spanish[$text]) || !filterContentByLanguage()) return $translation;
    return $spanish[$text];
}, 10, 2);
