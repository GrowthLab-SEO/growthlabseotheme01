# Changelog

All notable changes to GrowthLab Theme 01. The version is in `style.css` and goes up with every update that is pushed:
patch (1.0.x) for fixes, minor (1.x.0) for new features or changes editors can see.

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
