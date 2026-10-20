# GrowthLab Theme 01

Base WordPress theme that GrowthLab SEO uses to build client sites. It is a classic PHP theme (PHP templates plus `theme.json` for editor settings) whose page sections are ACF PRO blocks.

- Text domain: `growthlabseotheme01`
- Repository: <https://github.com/GrowthLab-SEO/growthlabseotheme01>
- License: GPL-3.0-or-later (see [LICENSE](LICENSE))

## Contents

- [Overview](#overview)
- [Requirements](#requirements)
- [Installation and child themes](#installation-and-child-themes)
- [Development workflow](#development-workflow)
- [Project structure](#project-structure)
- [Blocks](#blocks)
- [Theme helpers reference](#theme-helpers-reference)
- [Customizer: colors and fonts](#customizer-colors-and-fonts)
- [Multilingual (English/Spanish)](#multilingual-englishspanish)
- [Media and performance](#media-and-performance)
- [Security notes](#security-notes)
- [Troubleshooting](#troubleshooting)
- [License](#license)

## Overview

- **Classic theme + `theme.json`.** Templates are PHP (`header.php`, `footer.php`, `page.php`, `single.php`, `home.php`, `archive.php`, ...). `theme.json` sets the layout widths (content 780px, wide 1240px), fluid font sizes and spacing/border controls. The color palette and font families are added from PHP (`wp_theme_json_data_theme` filter in `functions.php`) so they follow the Customizer.
- **ACF PRO blocks.** Every section block in `blocks/` is an ACF block registered from its `block.json` and rendered by `block.php`. Field groups live in `acf-json/`.
- **Custom post types** (registered through ACF, `acf-json/post_type_*.json`): Case Results (`case-result`), Team Members (`team`) and Testimonials (`testimonial`).
- **Site Options** page (ACF options page `site_options`) for logo symbol, header, offices, contact data, social links, default internal-page hero and footer sections.
- **Per-page options** (pages and posts): custom header and menu, hero style (`home`, `home_2` ... `home_6`, `default`, `nohero`), hero properties and footer section toggles.

### Theme 01 identity

Colors and fonts are what makes each GrowthLab theme different; the core features are shared with themes 02 and 03.

| Setting | Default | Defined in |
| --- | --- | --- |
| Primary font | Open Sans | `theme-functions/fonts-selector.php` (`$default_fonts`) |
| Secondary font | Fraunces | `theme-functions/fonts-selector.php` |
| Primary (default / dark / light) | `#15253f` / `#08182f` / `#2C3D5B` | `theme-functions/color-scheme.php` (`$default_colors`) |
| Secondary | `#F4F3EE` / `#E7E5DF` / `#FFFFFF` | `theme-functions/color-scheme.php` |
| Tertiary | `#BC9061` / `#9D7A55` / `#DCAB77` | `theme-functions/color-scheme.php` |
| Text | `#15253f` | `theme-functions/color-scheme.php` |

Theme 01 is **intentionally bilingual (English and Spanish only)**. See [Multilingual](#multilingual-englishspanish).

## Requirements

From the `style.css` header:

- **PHP 8.3+**
- **WordPress 6.9+** (tested up to 7.1)

Plugins:

| Plugin | Status | Why |
| --- | --- | --- |
| Advanced Custom Fields PRO | Required | Blocks, options page, repeaters, clone fields, CPTs and field groups. Blocks are registered on `init` and templates call ACF functions directly. |
| LuckyWP ACF Menu Field | Required | Provides the ACF `menu` field type used in Page - Options (custom header menu, footer links menu), Content Box Grid ("Is a Menu") and the sidebar menus. |
| Gravity Forms | Recommended | Contact Form block and footer form section. Forms are rendered through `render_gravity_form()`, which outputs nothing when the plugin is inactive. The theme disables the Gravity Forms CSS and loads its init scripts in the footer. |
| WP Pusher | Optional | Deploys from GitHub; the theme hooks into its update/install actions to sync ACF JSON (see below). |
| A WebP plugin (e.g. EWWW Image Optimizer) | Optional | The picture helpers serve a WebP `<source>` only when a `file.jpg.webp` copy exists next to the original. |

Build tools: Node.js and npm (no `engines` field is set; use a current LTS release). Gulp 5 is installed locally as a dev dependency, so `npx gulp` works without a global install.

## Installation and child themes

1. Install and activate ACF PRO and LuckyWP ACF Menu Field (plus Gravity Forms if the site uses forms).
2. Copy or deploy the theme to `wp-content/themes/growthlabseotheme01/` and activate **GrowthLab Theme 01** in Appearance > Themes. Compiled CSS/JS are committed, so no build is needed on the server.
3. Fill in **Site Options** (left admin menu) and assign the `main` / `main_es` menus.

### ACF JSON

Configured in `theme-functions/acf-functions.php`:

- **Load:** field groups are loaded from the parent theme's `acf-json/` and, when a child theme is active, also from the child theme's `acf-json/` (the default ACF load path is removed).
- **Save:** saves go to the **active** theme's `acf-json/` (`get_stylesheet_directory()`), so with a child theme active, edited field groups are written to the child theme. The folder is created automatically if it does not exist.

### WP Pusher deploy sync

An ACF sync runs on the `wppusher_theme_was_updated` and `wppusher_theme_was_installed` actions:

- It only runs when this theme is the active theme or the parent of the active child theme, and only if ACF is loaded.
- It hashes every `group_*.json`, `post_type_*.json` and `taxonomy_*.json` file in the **parent** `acf-json/` and compares the hash with the `acf_json_parent_sync_hash` option. If nothing changed, it stops.
- Otherwise it imports, in this order, post types, taxonomies and field groups into the database, updating existing posts by key (no duplicates). Field groups that were set inactive in JSON stay disabled.
- JSON saving is turned off during the import so the sync does not write files back.
- **Skipped:** field groups that have an override with the same key in the child theme's `acf-json/`, field groups not loaded from JSON, and unreadable or invalid JSON files (counted as warnings). The sync stops after a 30-second time budget.
- The result (synced / skipped / warnings, memory, time) is written to the PHP error log as `[ACF sync:growthlabseotheme01] ...`.
- The new hash is stored before importing, so a sync interrupted by the time budget is not retried until the JSON files change again; finish it with ACF > Field Groups > Sync.
- Multisite is not supported.

### Child themes

- Blocks are registered from `blocks/*/block.json` in the parent **and** the child theme; a child block with the same `name` replaces the parent one.
- The inlined critical CSS includes the active theme's `style.css` (`get_stylesheet_directory()`), so child theme styles in `style.css` are inlined too.
- `body` gets `theme-child-{child}` and `theme-parent-{parent}` classes when a child theme is active.

## Development workflow

```bash
npm install
npx gulp
```

`gulpfile.js` defines a single **default** task. It runs every build once in parallel and then keeps watching for changes (stop it with Ctrl+C). There are no separate `sass`, `minify`, `images` or `clean` tasks.

| Source | Output | Notes |
| --- | --- | --- |
| `styles/**/*.scss` | `styles/**/*-min.css` + `.map` | Compressed Sass, Autoprefixer. Partials (`_*.scss`) are imported, not output. |
| `blocks/**/*.scss` | `blocks/**/block-min.css` + `.map` | Same pipeline. Rebuilt also when `styles/**/*.scss` changes (blocks import the globals). |
| `js/**/*.js` | `js/**/*-min.js` + `.map` | Terser. Files already ending in `-min.js` / `.min.js` are ignored. |
| `blocks/**/*.js` | `blocks/**/block-min.js` + `.map` | Terser. |

- **Compiled files are committed.** Run the build and commit the `*-min.css`, `*-min.js` and `.map` files together with their sources.
- Each block's `block.json` points to `file:./block-min.css` (and `file:./block-min.js` when the block has a script).
- Theme assets get a `filemtime()` version query string automatically, so browsers pick up rebuilt files.

### Conventions

- **Commit messages:** commitlint types, `type: Description` in English, e.g. `feat: Add Two Columns block`, `fix: Require a nonce to clear the block cache`, `perf: ...`, `chore: ...`, `docs: ...`.
- **One commit per logical change.**
- **Code comments in English.**
- Keep themes 01/02/03 in parity for core features; only colors and fonts (and theme 01's bilingual setup) differ.

### Adding a block

1. Create `blocks/block-{name}/` with `block.json` (`"name": "acf/{name}"`, `"category": "growthlabseotheme01-blocks"`, `"acf": { "renderTemplate": "block.php" }`, `"style": ["file:./block-min.css"]`), `block.php` and `block.scss` (plus `block.js` if needed).
2. Create the field group in ACF with the location "Block is equal to ..." and save it (written to `acf-json/`).
3. Run `npx gulp` and commit sources, compiled files and the JSON.
4. The block appears automatically (see [block list cache](#troubleshooting)).

### Adding a helper file

Create `theme-functions/{file}.php` and add it to the `$includes` array in `functions.php` (files are loaded with `require_once` from the parent theme directory, in array order).

## Project structure

```text
growthlabseotheme01/
├── acf-json/            ACF field groups (group_*.json) and CPTs (post_type_*.json)
├── assets/
│   ├── icons/           SVG icons
│   └── img/             Logo and separator symbol (separator-symbol.svg)
├── blocks/              ACF blocks, one folder each (block.json, block.php, block.scss, block.js)
├── fonts/               Bundled default fonts (Fraunces, Open Sans; woff2)
├── js/
│   ├── main.js                     Menus, accordions, block extraction, Splide loader, lazy maps, video player
│   ├── picture-optimization.js     Lazy loading for <picture>/<img class="lazy-image">
│   ├── styled-select-fields.js     Custom dropdown UI for <select> fields
│   └── vendor/          Splide, TinyMCE lineheight plugin
├── languages/           Translation files (.po/.mo) for the growthlabseotheme01 text domain
├── page-templates/
│   └── template-full-width.php     "Template Full Width" (no sidebar, no default page wrapper)
├── styles/
│   ├── main.scss        Global styles (globals/ + template-parts/), inlined in <head>
│   ├── globals/         Variables, mixins, normalize, forms, globals
│   ├── template-parts/  Component partials (header, footer, hero, cards, ...)
│   ├── page-templates/  Default page, blog and team member styles (enqueued per template)
│   └── vendor/          Splide core CSS, TinyMCE editor styles
├── template-parts/      Heroes (default, homepage v1-v6), cards, location card, pagination,
│                        Splide navigation, social networks, separator, Google Maps
├── theme-functions/
│   ├── acf-functions.php           Block registration and cache, block CSS inlining, options page,
│   │                               ACF JSON paths, WP Pusher sync, unsafe HTML, color picker palette
│   ├── color-scheme.php            Default colors, Customizer color settings, CSS variables
│   ├── fonts-selector.php          Default fonts, Customizer font settings, bundled font faces
│   ├── helpers.php                 Template helpers (see reference below), [phonenumber] shortcode
│   ├── picture-optimization.php    Responsive <picture> generation with WebP and lazy loading
│   ├── security-functions.php      Generator tag removal, XML-RPC off
│   ├── svg-support.php             SVG uploads, sanitization, inline SVG output
│   ├── theme-optimization.php      Head cleanup, emojis, dashicons, i18n, font preload, lazy maps
│   └── tiny-mce.php                Classic editor / ACF WYSIWYG toolbar, fonts, colors
├── functions.php        Includes, theme setup, theme.json filter, critical CSS, scripts, sidebars
├── header.php / footer.php         Site header + hero / footer form, locations and copyright sections
├── page.php, single.php, single-team.php, home.php, archive.php, index.php, 404.php
├── sidebar.php, sidebar-blog.php   Widget areas (+ Spanish variants) and per-page sidebar menus
├── style.css            Theme header (inlined into the critical CSS as well)
├── theme.json           Editor settings (layout, font sizes, spacing, borders)
├── gulpfile.js, package.json       Build
└── screenshot.png
```

Registered image sizes (`functions.php`, no hard crop unless noted): `cover-desktop` 1920x1080, `cover-tablet` 1280x960, `cover-mobile` 768x432, `cover-tablet-portrait` 960x1280 (crop), `cover-mobile-portrait` 768x1100 (crop), `content` 800x600, `featured-small` 480x360. `medium_large`, `1536x1536` and `2048x2048` are not generated.

## Blocks

All blocks are in the **"GrowthLab Theme 01 Blocks"** inserter category and share these fields: **Toggle Block** (the block renders nothing unless it is on), **Block ID**, **Extract Block From Content** and **Place** (before/after content).

| Block (`name`) | Folder | What it does | Notable options |
| --- | --- | --- | --- |
| Contact Form (`acf/contact-form`) | `block-contact-form` | Title, content and a Gravity Forms form box, optional side picture | Background image, side picture, form title/description, message before submit, "show only contact form" |
| Content Box Grid (`acf/content-box`) | `block-content-box-grid` | Grid of text boxes | Repeater items (title, title tag, title link, text) or "Is a Menu" to build the boxes from a menu (LuckyWP menu field) |
| Content Intro (`acf/content-intro`) | `block-content-intro` | Intro section: heading box, first paragraph, text with featured image and caption, CTA | Background image; featured image position (incl. below the text); caption uses the Site Options logo symbol |
| CTA Box (`acf/cta-box`) | `block-cta-box` | Call-to-action box with title, text and button | Box position: floating between sections / within content / full-height section; style dark or light; pretitle and background + side images for full height |
| FAQ (`acf/faq`) | `block-faq` | Accordion of questions grouped in sections | Repeater of sections (heading + tag) with FAQ items (question + tag, answer); background image; CTA |
| Featured Video (`acf/featured-video`) | `block-featured-video` | Cover image with a play button | YouTube URL or uploaded video file; cover image (falls back to the cached YouTube thumbnail); lightbox or inline playback; editor notice when the YouTube URL is not a single video |
| Office Locations (`acf/office-locations`) | `block-locations` | Office cards (Splide carousel or grid) | All offices from Site Options or a selection; view structure grid/carousel; first card (logo + content); CTA |
| Logos Carousel (`acf/logos-carousel`) | `block-logos-carousel` | Splide carousel of client logos | Optional link per logo; background color (hex or rgba) |
| Posts Carousel (`acf/posts-carousel`) | `block-posts-carousel` | Splide carousel of selected posts or custom items | Type: custom items, case results, team members, blog posts, testimonials; custom type can select posts of any type or create items; hide testimonial text |
| Posts Grid (`acf/posts-grid`) | `block-posts-grid` | Paginated grid of posts or custom items | Same types as the carousel; "show all" or a selection; 12 per page (9 for case results, ordered by `numerical_amount`); each grid on a page paginates independently (`grid-2-page`, ...) |
| Selling Points (`acf/selling-points`) | `block-selling-points` | Icon cards in a Splide carousel | Items with icon, title (+ tag, link) and content; CTA |
| Trust Cards (`acf/trust-cards`) | `block-trust-cards` | Heading card, intro card and numbered cards in a Splide carousel | Background image; CTA |
| Two Columns (`acf/two-columns`) | `block-two-columns` | Text column (WYSIWYG + CTA) next to an image | Background light/dark/image; reverse order; top separator; title tag |

Posts Carousel and Posts Grid render nothing for visitors (and show an editor notice) when nothing is selected or created.

### Shared block conventions

Follow these when writing or editing a `block.php`:

- **Guard and fields:**

  ```php
  if (get_field('toggle_block')):
      foreach (get_block_fields($block) as $key => $value) $$key = $value;
  ```

  `get_block_fields($block)` returns every field of the block's field groups; fields that were never saved get their ACF default (`[]` for repeaters/galleries/relationships/checkboxes, sub field defaults for groups, booleans for true/false, otherwise the default or `null`), so `$$key` extraction does not cause undefined variable warnings.
- **Repeater rows and groups are arrays.** Read them as `$item['title'] ?? ''`; do not use `have_rows()` / `get_sub_field()` inside blocks.
- **Wrapper attributes:** `<section<?= get_block_id_attribute($block_id ?? '') ?> class="block ..."<?= get_block_extract_attribute($extract_block_from_content ?? false, $place ?? '') ?>>`.
- **Select values:** pass any ACF select/radio value that ends up in markup through `get_valid_field_choice('field_name', $value)` (use `group.sub_field` for nested fields). Invalid values fall back to the field default.
- **Headings:** `print_title($title, $title_tag, 'classes')` limits the tag to `h1`-`h6`, `p`, `span`, `div` and filters the title with `wp_kses_post()`.
- **Escaping:** `esc_url()` for URLs, `esc_attr()` for attributes and classes, `esc_html()` for plain text such as link titles. WYSIWYG/text content fields are printed as-is on purpose (see [unsafe HTML](#security-notes)).
- **Empty states:** use `print_block_notice($message)` for editor-only notices (visible to users who can `edit_posts`, nothing for visitors).
- **Images:** `img_print_picture_tag()` (see [Media](#media-and-performance)).

### Verified limitations

- Block templates do not use `get_block_wrapper_attributes()` or any equivalent, so the editor **spacing controls (margin, padding, block gap) declared in `block.json` have no effect on the front end**.
- The "Additional CSS class(es)" setting is output only by **Featured Video** and **Two Columns**.
- Block extraction (moving a block before/after the content) is done by `main.js` and only works on pages using the default template (`.page-template-default .page__main`).

## Theme helpers reference

`theme-functions/helpers.php`:

| Function | Description |
| --- | --- |
| `get_text_domain()` | Returns the theme text domain (`growthlabseotheme01`). |
| `get_field_options($field_name, $format_value = true)` | `get_field()` on the options page, cached per request. |
| `filterContentByLanguage($lang = 'es')` | `true` when the request path is `/es` or starts with `/es/`. |
| `get_flat_number($phone)` | Strips everything but digits from a phone number. |
| `get_wrapped_title($title, $tag = 'p', $classes = '', $is_hero = false)` | Returns the title wrapped in a safe tag (falls back to `h1` for heroes, `p` otherwise). |
| `print_title(...)` | Echoes `get_wrapped_title()`. |
| `get_block_id_attribute($block_id)` | Escaped ` id="..."` or `''`. |
| `print_block_notice($message)` | Editor-only notice in place of a block. |
| `get_valid_field_choice($field_name, $value)` | Validates a value against an ACF field's choices. |
| `get_block_extract_attribute($enabled, $place)` | ` data-extract="before\|after"` or `''`. |
| `get_current_post_blocks($flatten = false)` | Parsed blocks of the current singular post, parsed once per request; `true` includes inner blocks in a flat list. |
| `get_next_posts_grid_page_arg()` | Pagination query arg for the next Posts Grid on the page (`''` for the first one). |
| `get_block_fields($block)` | Block field values merged over ACF defaults. |
| `get_acf_field_default($field)` | Default value of an ACF field as templates expect it. |
| `fill_acf_group_defaults($value, $selector, $post_id = false)` | Merges a group value (e.g. Site Options, `hero_properties`) over its sub field defaults. |
| `get_post_fields_with_defaults($post_id = false)` | Post field values merged over the defaults of its field groups. |
| `render_gravity_form(...$args)` | Calls `gravity_form()` only if Gravity Forms is active. |
| `format_number_abbreviated($number)` | `1500` -> `1.5K`, also `M` and `B`. |
| `get_yt_code($url = false)` | 11-character YouTube video ID, or `false` for channels, playlists and other non-video URLs. Mirrors `extractYouTubeID()` in `main.js`. |
| `get_youtube_thumbnail(string $video_id, string $size = 'hqdefault')` | Best available thumbnail URL, cached in a transient (a month; a day for the `hqdefault` fallback). |
| `[phonenumber]` shortcode | Main phone number from Site Options as a `tel:+1...` link. Attributes: `target`, `link_id`, `link_class`, `wrapper`, `text` (fallback). |

Other files:

| Function | File | Description |
| --- | --- | --- |
| `img_print_picture_tag(...)` / `img_generate_picture_tag(...)` | `picture-optimization.php` | Echo / return a responsive `<picture>`. Named args: `img`, `mobile_img`, `tablet_img`, `max_size`, `min_size`, `classes`, `id`, `alt_text`, `is_cover`, `img_attr`, `is_priority`. |
| `image_to_svg(array\|string $image, string $classes = '')` | `svg-support.php` | Inlines a local SVG (wrapped in a `<div>`), or prints an `<img>` for other formats. |
| `theme_get_customizer_css()` | `color-scheme.php` | `:root` color variables. |
| `theme_get_fonts_css()` | `fonts-selector.php` | Selected bundled `@font-face` rules + `--font-primary` / `--font-secondary`. |
| `theme_get_default_font_faces()` | `fonts-selector.php` | Bundled font files per family. |
| `get_theme_color_palette_for_acf()` | `acf-functions.php` | Customizer colors used for the ACF color picker palette. |

## Customizer: colors and fonts

### Colors (Appearance > Customize > Colors)

- `color-scheme.php` registers a color control for each value in `$default_colors`: `primary_color`, `primary_color_dark`, `primary_color_light`, the same for `secondary` and `tertiary`, and `text_color` (sanitized with `sanitize_hex_color`).
- They are printed as RGB triplets in the inlined CSS: `--primary`, `--primary-dark`, `--primary-light`, `--secondary*`, `--tertiary*`, `--text` (e.g. `--primary: 21, 37, 63`), so styles use `rgb(var(--primary))` or `rgba(var(--primary), .5)`.
- The same values feed the block editor palette (`primary-color`, `primary-color-dark`, ... `text-color`; WordPress default palette, gradients, font sizes and spacing sizes are disabled), the **ACF color picker palette** (`get_theme_color_palette_for_acf()`) and the TinyMCE text colors. All use the `color-scheme.php` defaults, so changing a default there updates every palette. (`tiny-mce.php` repeats the default hex values as fallbacks; keep them in sync.)

### Fonts (Appearance > Customize > Fonts)

- `font_primary` and `font_secondary` are select controls. Choices are the bundled theme fonts (Open Sans, Fraunces) plus any font installed through the WordPress **Font Library** (listed first).
- The selection becomes `--font-primary` / `--font-secondary` and the `primary` / `secondary` font families in `theme.json`.
- **Bundled fonts are only loaded while selected.** `@font-face` rules for a bundled family are inlined, and its faces marked `preload` get `<link rel="preload">`, only when that family is the primary or secondary font. Selecting Font Library fonts loads no bundled files.
- A `wp_theme_json_data_user` filter drops the copy of the theme font families that the Site Editor's font manager saves in global styles, which would otherwise make WordPress print every bundled font face. Font Library fonts are kept.

To add a bundled font face:

1. Put the `.woff2` files in `fonts/{family-folder}/`.
2. Add the faces to `theme_get_default_font_faces()` in `theme-functions/fonts-selector.php`, keyed by family name: `['weight' => '400', 'style' => 'normal', 'file' => '{family-folder}/{file}.woff2', 'display' => 'swap', 'preload' => true]`. Preload only the weights used above the fold.
3. For a new family, also add it to the base list in `theme_get_all_fonts()` so it appears as a Customizer choice (and to `font_formats` in `tiny-mce.php` if editors should pick it in WYSIWYG fields).

## Multilingual (English/Spanish)

Theme 01 supports exactly two languages without a translation plugin. English is the default; Spanish content lives under the **`/es` URL prefix** (e.g. `/es/contacto/`).

`filterContentByLanguage()` (default `'es'`) checks the request path and the templates switch on it:

| Item | English | Spanish (`/es/...`) |
| --- | --- | --- |
| Site Options group | `options` | `options_es` (ACF clone of the English fields; the "Options by Language" select only switches which group is shown in the admin) |
| Header menu location | `main` (Main Menu) | `main_es` (Main Menu Spanish) |
| Page sidebar | `sidebar-default` | `sidebar-default-es` |
| Blog sidebar | `sidebar-blog` | `sidebar-blog-es` |

Pages with "Custom Header" enabled use the menu chosen in Page - Options instead of the menu location. A few hardcoded strings switch by language in PHP (e.g. "Get Directions" / "Obtener Direcciones" in `template-parts/location-card.php`); `main.js` and `styled-select-fields.js` pick their EN/ES labels (video close button, select placeholder) from `<html lang>`.

Translations: `load_theme_textdomain('growthlabseotheme01', get_template_directory() . '/languages')` runs on `after_setup_theme`. Put `growthlabseotheme01-{locale}.po/.mo` files in `languages/` (currently empty apart from `.gitkeep`).

Known limitations:

- `header.php` hardcodes `<html lang="en">`, so on Spanish pages the JavaScript labels above stay in English.
- The header only builds the theme-location menu when the `main` location has a menu assigned (`has_nav_menu('main')`), even on Spanish pages.

## Media and performance

### SVG

`theme-functions/svg-support.php`:

- Allows SVG uploads and reports their dimensions to the media library.
- Sanitizes every SVG on upload and sideload (detected by extension or MIME type): DOM-based allowlist of SVG elements (shapes, text, gradients, filters, animations), removes comments, processing instructions, `on*` handlers, `javascript:`/`data:text/html` values, external `href`s (only `#id` and embedded raster `data:image/...` are kept) and unsafe CSS (`@import`, `expression()`, external `url()`). Files with `<!ENTITY>`, svgz files or invalid XML are rejected.
- SVGs passed to the picture helpers are inlined via `image_to_svg()`; `<img>` tags pointing to local SVGs in `the_content` are replaced by the inline SVG (skipped when the content has more than 20 images).

### Pictures

`img_print_picture_tag()` builds a `<picture>` with `<source>` elements per breakpoint (`$GLOBALS['breakpoints']` in `functions.php`: tablet 768px, ldpi 1024px, mdpi 1280px, hdpi 1920px), picking registered sizes between `min_size` and `max_size`. `is_cover` uses the cover sizes and optional tablet/mobile images.

- WebP `<source>` elements are added when a `.webp` copy exists (`image.jpg.webp`).
- Non-priority images use `data-src` / `data-srcset` and the `lazy-image` class; `js/picture-optimization.js` swaps them in with an IntersectionObserver (100px margin). Priority images (`is_priority: true`) load eagerly with `fetchpriority="high"`; the header logo is one.

### CSS and JavaScript

- **Inlined critical CSS** (`inline_main_critical_css()` in `functions.php`): one `<style id="main-css">` with the bundled `@font-face` rules, font and color variables, `styles/main-min.css`, the active theme's `style.css`, the CSS of the blocks used on the current page (read from each block's `block-min.css`; the block stylesheets are not enqueued) and the Splide core CSS when needed. The result is minified. Template stylesheets (`styles/page-templates/*-min.css`) are enqueued per template.
- **Splide only when needed.** `growthlabseotheme01_request_uses_splide()` returns true when the page contains Office Locations, Logos Carousel, Posts Carousel, Selling Points or Trust Cards, or when the footer locations carousel renders (locations section not hidden in Site Options or the page, and more than one office). Only then is `splide-core.min.css` inlined. The Splide script itself is loaded on demand by `window.loadSplide()` (in `main.js` and the block scripts), which retries after a failed load.
- **Block assets:** styles and scripts of blocks not on the page are dequeued; block scripts are moved to the footer with `defer`.
- **Deferred scripts:** `main-min.js`, `styled-select-fields-min.js` and `picture-optimization-min.js` load in the footer with `strategy: defer`. jQuery is moved to the footer and jQuery Migrate is deregistered.
- **Styled select fields:** `js/styled-select-fields.js` replaces native single `<select>` elements (except `.posts-filters__select` and `multiple`) with an accessible custom dropdown, including selects added later (e.g. Gravity Forms AJAX).
- **Video player** (`main.js`): any element with `data-videourl` or `data-videoid` opens YouTube, Instagram (`.ig-video`) or self-hosted videos in a lightbox (default) or inline (`data-mode="inline"`, rendered into `data-target`). Used by Featured Video.
- **YouTube thumbnails:** `get_youtube_thumbnail()` caches the best existing size in a transient instead of querying YouTube on every page view.
- **Fonts:** only the selected bundled fonts are declared and preloaded (`theme-optimization.php`, priority 1 in `wp_head`).
- **Google Maps:** Google Maps iframes in the content are replaced by `.gmap-lazy` placeholders loaded when visible; common Google Maps API script handles are dequeued. The `google_maps_embed_code` ACF field is base64-encoded in the browser before saving to avoid ModSecurity false positives; decode values that start with `base64:` before output.
- **Head cleanup** (`theme-optimization.php`): RSD, wlwmanifest, shortlink, REST and oEmbed discovery links, feed links, emojis, front-end dashicons (kept for users who can `update_core`), `wp-i18n` (unless the page has `core/search`, `core/query` or `core/navigation`) and the WooCommerce blocks style are removed.

## Security notes

- `security-functions.php` removes the WordPress generator meta tag and disables XML-RPC.
- **Admin bar:** administrators (`manage_options`) get a **Clear Block Cache** link. It is protected by a nonce (`clear_block_cache`) and the cache is only cleared in the admin for a user with `manage_options` and a valid nonce.
- **Escaping:** see the [block conventions](#shared-block-conventions). `print_title()` and the `[phonenumber]` shortcode restrict tags and escape attributes; `phonenumber` also allowlists the wrapper tag and target.
- **ACF "unsafe HTML" is intentionally allowed.** `acf-functions.php` returns `true` for `acf/shortcode/allow_unsafe_html`, `acf/the_field/allow_unsafe_html` and `acf/the_sub_field/allow_unsafe_html` and hides the ACF escaped-HTML notice, and block templates echo text, textarea and WYSIWYG values without filtering, so editors can use HTML such as `<span>`, `<a>` or `<strong>` in titles and texts. **Implication: anyone who can edit those fields can output arbitrary HTML, including scripts. Only give editing access to trusted users.**
- `import_theme_images_to_folder()` in `functions.php` (copies `assets/icons/*.svg` to the media library) is only callable manually; its `?import_theme_images=1` trigger is commented out.

## Troubleshooting

**A new or renamed block does not appear in the editor.**
The list of block folders is cached in transients (`theme_block_files_cache_{stylesheet}`, 24 hours). It is rebuilt automatically when any `block.json` path or modification time changes, when switching themes, on every admin request while `WP_DEBUG` is on, or with **Clear Block Cache** in the admin bar. Also check that `block.json` has a `name` and that ACF PRO is active.

**ACF shows "Sync available" for field groups.**
The JSON in `acf-json/` is newer than the field group in the database (e.g. after a `git pull` or a deploy without WP Pusher, or after a WP Pusher sync that hit its time budget). Review and run the sync in ACF > Field Groups. With a child theme, remember that saving writes to the child theme's `acf-json/`, and the WP Pusher sync then skips that group.

**CSS changes do not show up.**
`styles/main-min.css` and block CSS are inlined, so rebuild with `npx gulp` and clear any page cache. Template stylesheets and scripts are versioned by modification time.

## License

GPL-3.0-or-later. See [LICENSE](LICENSE).
