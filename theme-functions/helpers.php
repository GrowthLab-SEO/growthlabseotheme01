<?php
if (!function_exists('get_text_domain')) {
    function get_text_domain()
    {
        return $GLOBALS['theme_text_domain'];
    }
}


// Helper function to get ACF option fields
if (!function_exists('get_field_options')) {
    function get_field_options($field_name, $format_value = true)
    {
        if (empty($field_name) || !is_string($field_name)) return null;

        static $cache = [];
        $key = $field_name . '|' . ($format_value ? '1' : '0');
        if (array_key_exists($key, $cache)) {
            return $cache[$key];
        }

        $value       = get_field($field_name, 'option', $format_value);
        $cache[$key] = $value;
        return $value;
    }
}

// Language Filter
if (!function_exists('filterContentByLanguage')) {
    function filterContentByLanguage($lang = 'es')
    {
        if (empty($lang) || !is_string($lang)) return false;

        $uri    = $_SERVER['REQUEST_URI'] ?? '/';
        $path   = parse_url($uri, PHP_URL_PATH) ?: '/';
        $prefix = '/' . ltrim($lang, '/');

        if ($path === $prefix) return true;
        if (strpos($path, $prefix . '/') === 0) return true;

        return false;
    }
}

//Phone number format remover
function get_flat_number($phone)
{
    if (! $phone) return;
    return preg_replace("/[^0-9]/", '', $phone);
}

// Wraps a title in a heading/paragraph tag. The tag is limited to a safe list (falling back to h1 for
// hero titles and p otherwise) and the title keeps only post-safe HTML, so ACF values can't inject scripts.
if (!function_exists('get_wrapped_title')) {
    function get_wrapped_title($title, $tag = 'p', $classes = '', $is_hero = false)
    {
        if (!$title) return;

        $allowed_tags = array('h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div');
        $tag = strtolower(trim((string) $tag));
        if (!in_array($tag, $allowed_tags, true)) {
            $tag = $is_hero ? 'h1' : 'p';
        }

        $class_attr = $classes ? ' class="' . esc_attr($classes) . '"' : '';

        return "<$tag$class_attr>" . wp_kses_post($title) . "</$tag>";
    }
}

if (!function_exists('print_title')) {
    function print_title($title, $tag = 'p', $classes = '', $is_hero = false)
    {
        if (!$title) return;
        echo get_wrapped_title($title, $tag, $classes, $is_hero);
    }
}

// Returns the escaped id attribute for a block wrapper (with a leading space), or an empty string when no id is set
if (!function_exists('get_block_id_attribute')) {
    function get_block_id_attribute($block_id)
    {
        $block_id = trim((string) $block_id);
        return $block_id !== '' ? ' id="' . esc_attr($block_id) . '"' : '';
    }
}

// Prints a notice in place of a block's content (e.g. when nothing is selected).
// Only users who can edit posts see it; visitors get no output.
if (!function_exists('print_block_notice')) {
    function print_block_notice($message)
    {
        if (!current_user_can('edit_posts')) return;
        echo '<div class="block-notice container" style="margin-block:2rem;padding:2rem;border:2px dashed currentColor;text-align:center;"><p>' . esc_html($message) . '</p></div>';
    }
}

// Returns $value when it is one of the choices of an ACF select/radio/button group field, otherwise the field's
// default value ('' when it has none). Choices are read from the field definition in the current block or post,
// so adding an option in ACF needs no code change. Use "group.sub_field" for fields inside a group.
if (!function_exists('get_valid_field_choice')) {
    function get_valid_field_choice($field_name, $value)
    {
        $path = explode('.', $field_name);
        $field = get_field_object(array_shift($path), false, false, false);

        foreach ($path as $sub_name) {
            $sub_fields = is_array($field) ? ($field['sub_fields'] ?? []) : [];
            $field = current(array_filter($sub_fields, fn($sub) => ($sub['name'] ?? '') === $sub_name)) ?: null;
        }

        $choices = is_array($field) && is_array($field['choices'] ?? null) ? $field['choices'] : [];
        if (is_scalar($value) && $value !== '' && array_key_exists((string) $value, $choices)) {
            return (string) $value;
        }

        $default = $field['default_value'] ?? '';
        return is_scalar($default) && array_key_exists((string) $default, $choices) ? (string) $default : '';
    }
}

// Returns the data-extract attribute that main.js uses to move a block before/after the content, or '' when disabled
if (!function_exists('get_block_extract_attribute')) {
    function get_block_extract_attribute($enabled, $place)
    {
        if (!$enabled) return '';
        $place = get_valid_field_choice('place', $place);
        return $place !== '' ? ' data-extract="' . esc_attr($place) . '"' : '';
    }
}

//Render a Gravity Forms form only if the plugin is active
if (!function_exists('render_gravity_form')) {
    function render_gravity_form(...$args)
    {
        if (!function_exists('gravity_form')) return;
        return gravity_form(...$args);
    }
}

//Debug helper
function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}

//Format Numerical Amount
function format_number_abbreviated($number)
{
    if ($number >= 1000000000) {
        return round($number / 1000000000, 1) . 'B';
    } elseif ($number >= 1000000) {
        return round($number / 1000000, 1) . 'M';
    } elseif ($number >= 1000) {
        return round($number / 1000, 1) . 'K';
    }
    return $number;
}

// Returns the 11-character ID of a single YouTube video, or false for any URL that is not a video
// (channels, playlists, search results, the homepage, etc.). Mirrors extractYouTubeID() in main.js
if (!function_exists('get_yt_code')) {
    function get_yt_code($url = false)
    {
        if (!$url || !is_string($url)) return false;

        $url = trim($url);
        if (!preg_match('#^[a-z][a-z0-9+.-]*://#i', $url)) $url = 'https://' . ltrim($url, '/');

        $parts = wp_parse_url($url);
        if (empty($parts['host'])) return false;

        $host = strtolower(preg_replace('/^(www\.|m\.|music\.)/i', '', $parts['host']));
        $segments = array_values(array_filter(explode('/', $parts['path'] ?? '')));
        $id = null;

        if ($host === 'youtu.be') {
            $id = $segments[0] ?? null;                                  // youtu.be/ID
        } elseif (in_array($host, ['youtube.com', 'youtube-nocookie.com'], true)) {
            if (($segments[0] ?? '') === 'watch') {
                parse_str($parts['query'] ?? '', $query);                // youtube.com/watch?v=ID
                $id = $query['v'] ?? null;
            } elseif (in_array($segments[0] ?? '', ['embed', 'shorts', 'live', 'v', 'e'], true)) {
                $id = $segments[1] ?? null;                              // /embed/ID, /shorts/ID, /live/ID
            }
        }

        // "videoseries" is the 11-character placeholder used by playlist embeds, not a video
        return is_string($id) && $id !== 'videoseries' && preg_match('/^[A-Za-z0-9_-]{11}$/', $id) ? $id : false;
    }
}

// [phonenumber] shortcode: prints the main phone number from the site options as a tel: link.
// All attributes are escaped because any user who can edit posts (including contributors) can use it.
function phonenumber($atts, $content = null)
{
    $atts = shortcode_atts(array(
        'target' => '_self',
        'link_id' => '',
        'link_class' => '',
        'wrapper' => '',
        'text' => ''
    ), $atts, 'phonenumber');

    $es = filterContentByLanguage() ? '_es' : '';
    $options = get_field_options('options' . $es);
    $phone = $options['main_phone_number'] ?? '';

    // Fall back to the "text" attribute when no phone number is set in the options
    $link_text = $phone ?: $atts['text'];
    $link_number = get_flat_number($link_text);

    if (!$link_number) {
        return '';
    }

    $allowed_wrappers = array('span', 'div', 'p', 'strong', 'em', 'small', 'li', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6');
    $wrapper = strtolower(trim($atts['wrapper']));
    $wrapper = in_array($wrapper, $allowed_wrappers, true) ? $wrapper : '';

    $target = in_array($atts['target'], array('_self', '_blank', '_parent', '_top'), true) ? $atts['target'] : '_self';

    // id and class go on the wrapper when there is one, otherwise on the link
    $id_class = '';
    if ($atts['link_id'] !== '') {
        $id_class .= ' id="' . esc_attr($atts['link_id']) . '"';
    }
    if ($atts['link_class'] !== '') {
        $id_class .= ' class="' . esc_attr($atts['link_class']) . '"';
    }

    $link = sprintf(
        '<a href="%s" target="%s"%s%s>%s</a>',
        esc_url('tel:+1' . $link_number, array('tel')),
        esc_attr($target),
        $target === '_blank' ? ' rel="noopener"' : '',
        $wrapper ? '' : $id_class,
        esc_html($link_text)
    );

    return $wrapper ? "<$wrapper$id_class>$link</$wrapper>" : $link;
}
add_shortcode('phonenumber', 'phonenumber');