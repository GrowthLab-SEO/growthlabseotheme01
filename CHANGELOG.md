# Changelog

All notable changes to GrowthLab Theme 01. The version is in `style.css` and goes up with every update that is pushed:
patch (1.0.x) for fixes, minor (1.x.0) for new features or changes editors can see.

## 1.2.0 - 2026-10-24

### Added
- Blog filters (search, category, month and year) on the blog, archive and search listings, in Spanish under `/es/`.
- Gravity Forms phone fields are masked with IMask.
- More font sizes in the WYSIWYG (up to 168px), like the other themes.
- Child themes can have their own `styles/`, `js/` and `blocks/` folders, like the theme: the theme's gulp builds them
  (`npm run build:child -- --child=../<child folder>`, or the folders listed in `child-themes.json`) and the theme loads the built
  files and registers the child blocks. `npm run create-child` starts a child theme. See `CHILD-THEMES.md`.
- `CHILD-THEMES.md`: guide to build sites and child themes (overrides, helpers, filters, SCSS tokens and classes,
  custom properties, CSS practices).
- `npm run verify`: local check of the theme (PHP syntax, build, pages of the local site and a test child theme).
- Accessibility: visible keyboard focus outline, reduced motion support, desktop submenus open on keyboard focus, the
  closed mobile menu is out of the tab order, hidden Gravity Forms labels stay available to screen readers, decorative
  symbols printed with CSS are hidden from them and the video close button is bigger.
- Font sizes chosen in the WYSIWYG for parts of a title scale down on small screens, never below the title's own size
  at that screen (unless the chosen size is smaller) nor below 12px.
- Filters for child themes: `theme_page_hero_title`, `theme_main_script_data`, `theme_styled_select_data`,
  `<theme>_splide_blocks` and `<theme>_request_uses_splide`.

### Changed
- ACF blocks are registered at `init` priority 11 (it was 5), like the other themes.
- The Spanish texts and helpers are in `theme-functions/theme-bilingual.php`; the behavior only this theme has (footer
  offices carousel, Spanish script texts) is in `theme-functions/theme-hooks.php`.
- Only the styles of the hero the page uses and of the cards it renders are printed, so the inline CSS is smaller.
- The theme functions are shared by the GrowthLab themes; the design data (default colors and fonts, excerpt lengths)
  is in `theme-functions/theme-config.php`.
- TinyMCE gets the theme colors as JSON, without the scripts that patched the color picker.
- "Clear block cache" is inside the "Theme Tools" menu of the admin bar.

### Fixed
- Items of ordered lists in formatted content are numbered.
- The first section after the hero and a floating CTA box render right away, without being clipped or jumping above
  the hero while the page loads. Sections further down keep rendering only when they get near the screen.

### Check after updating
- Purge the page cache of the site (the theme's own CSS cache updates by itself).
- Child themes with their own PHP (`functions.php`, template parts): the pages that use them render without errors.
- WYSIWYG fields in the editor: the color picker shows the theme colors and applies them.
- Titles with font sizes chosen in the WYSIWYG: they are now smaller on mobile and tablet.
- One page of each hero style and the blog, archive and search listings: styles look as before.
- Keyboard: Tab through the header menu; submenus open on focus.
- Blog, archive and search pages, in English and under `/es/`: the filters show, filter and paginate.
- Forms with a phone field: the mask works and the form submits.
- Footer offices carousel (more than one office).

## 1.1.0 - 2026-10-23

### Added
- Every Gravity Forms field (Contact Form block and footer form in Site Options) has a **Form Type** select:
  Gravity Form, Custom code or None. **Custom code** shows a **Form Code** textarea for the embed code or script of a
  third-party form (e.g. a CRM), printed as is. Forms saved before this keep their Gravity Form.

### Fixed
- Section content of blocks and the footer (Main Content, Text Content, FAQ answers, form description, etc.) is no
  longer passed through `wp_kses_post()`, which removed iframes, scripts and forms pasted by editors. Titles and short
  card texts keep it.
- Paragraphs with right aligned images float to the right.
- Cards and the CTA box use the `--text` color (they used `--text-color`, which doesn't exist).
- The Locations and Contact Form blocks no longer change the footer when they are on the page.
- Testimonial avatars are round and cropped.
- The CTA box shows right away instead of waiting for the page to finish loading.

### Changed
- Site Options: "Posts Default Title Tag" and "Posts Default Image" are now labeled "Posts Cards Default Title Tag"
  and "Posts Cards Default Image". They apply to the post cards of the listings, and to the cards of the Posts Grid
  and Posts Carousel blocks that don't set their own tag, not to the page title in the hero.

### Check after updating
- Pages with embeds in block content (forms, maps, videos): they print again. Remove any workaround that restored them.
- Contact forms: they keep their Gravity Form. To use a third-party form, set Form Type to Custom code.
- Card and CTA box text color: it now uses the Text color of the color scheme.
- Field groups stored in the database: ACF shows "Sync available" for Site Options and Block - Contact Form.
