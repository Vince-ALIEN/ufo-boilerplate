Ufo Boilerplate
===============

A custom WordPress theme based on [\_tw](https://underscoretw.com/), extended
with a set of theme-specific customizations (see below) on top of the
standard \_tw quickstart/build workflow.

## Theme status

This is **Ufo Agency's client-project starter**, not a neutral/generic
boilerplate. Every new project is expected to start from it and customize it —
it is not meant to be used as-is.

Concretely, this means:

* `theme/index.php`, `theme/template-parts/content/content-single.php`,
  `theme/template-parts/content/content-page.php` and
  `theme/template-parts/content/content-frontpage.php` contain Ufo Agency's
  default blog/page layout (sticky hero, table-of-contents sidebar, social
  share buttons, `/contact/` CTA, front-page hero). This is **intentional
  starting content**, not leftover production debris — replace the copy,
  images and links per project, but the layout itself is the agency's
  standard starting point and doesn't need to be stripped out.
* Rebranding a project means changing `theme/theme.json`'s `primary` /
  `secondary` / `tertiary` color values and the `primary` / `secondary` font
  families — the rest of the theme (Tailwind classes, `theme.json` element
  styles) references those semantic slugs rather than hardcoded palette
  names, so a new brand only requires editing `theme.json` and
  `tailwind/tailwind-theme.css`, not searching the codebase for a color name.

## Project-specific customizations

Beyond the \_tw base, this theme adds (mostly under `theme/inc/features/`
and `theme/inc/`, booted by `theme/inc/class-loader.php` — see that file
for how services are wired up and loaded):

* **Header CTA** (`class-header-cta.php`) — Customizer section ("Ufo
  options" → "Header CTA") to edit the header's button text/URL; the button
  is hidden entirely when no URL is set.
* **Menu widget area** — a `menu_widget_area` sidebar (registered in
  `functions.php`), displayed next to the header CTA, meant to host things
  like a WooCommerce cart widget.
* **Social media links** (`class-social-media.php`) — Customizer URLs for
  Facebook, Instagram, X, GitHub and YouTube, rendered with inline SVG icons.
* **Front page hero** (`class-front-page-hero.php`) — Customizer fields for
  the front page's badge, heading, description, two CTAs and background
  image.
* **Custom logo** (`class-custom-logo.php`) — registers `custom-logo` theme
  support for the header, cropped to 50×50 pixels. The footer logo
  (`class-ufo-options.php`'s `ufo_footer_logo` setting) goes through the
  same `WP_Customize_Cropped_Image_Control` crop step, at the same size.
* **Breadcrumbs** (`class-breadcrumb.php`) — accessible, schema.org-annotated
  breadcrumb trail with Category Base support. Call `_ufo_the_breadcrumb()`
  to print it (run through `wp_kses()` with an allowlist that keeps the
  microdata); `_ufo_get_breadcrumb()` returns the raw string.
* **Footer** (`template-parts/layout/footer-content.php`) — a `bg-primary-950`
  body with a logo / Contact / Services / "Me suivre" grid and a copyright +
  legal-menu bar. Fed by the `menu-2` / `menu-3` nav menus, the `sidebar-2`
  (Footer Contact) / `sidebar-3` (Footer Newsletter) widget areas,
  `_Ufo_Social_Media` and a self-hosted Website Carbon badge (`#wcb`).
* **Contact Form 7 integration** (`class-contact-form-7.php`,
  `_Ufo_Contact_Form7`) — restyles CF7 markup with Tailwind (including a
  distinct newsletter-form variant), replaces the submit `<input>` with a
  `<button>` + Lucide icon, wraps the form in a `bg-slate-200 rounded-xl
  px-4` container, adds honeypot / timing / keyword spam protection, and only
  enqueues CF7's assets on pages that actually use it.
* **Speculative loading** (`inc/class-speculative.php`) — tunes WordPress
  6.8+ Speculation Rules: keeps the safe `prefetch` mode but raises eagerness
  to `moderate`, and excludes cart / checkout / account paths. No-op on
  WordPress < 6.8. Overridable via the `_UFO_SPECULATION_MODE` /
  `_UFO_SPECULATION_EAGERNESS` constants or the `_ufo_speculation_mode`,
  `_ufo_speculation_eagerness` and `_ufo_speculation_exclude_paths` filters.
* **Per-page CTA buttons** (`inc/cta-buttons.php`) — post meta for two
  call-to-action buttons in the page header, editable from the block
  editor's Document sidebar.
* **Ajax filter** (`inc/ajax-filter/`) — client-side filtering for archive
  listings. Extension points: the `_ufo_ajax_filter_allowed_templates`,
  `_ufo_ajax_filter_is_post_type_viewable`,
  `_ufo_ajax_filter_is_post_status_viewable`,
  `_ufo_ajax_filter_max_quantity` and `_ufo_ajax_filter_query_args`
  filters.
* **Image block** (`class-image-block.php`) — excludes `core/image` from
  Tailwind Typography's `.prose` styles.
* **Truncate helper** (`inc/truncate/`) — character-based text truncation.

### PHP architecture

* **Templates** (`theme/*.php` at the root — `index.php`, `single.php`,
  `page.php`, `archive.php`, `search.php`, `front-page.php`, `404.php`,
  `header.php`, `footer.php`) orchestrate rather than hold markup: they call
  `get_template_part('template-parts/...')` and leave the actual rendering
  to `template-parts/`. `template-parts/content/` renders the loop (one file
  per context — `content-single.php`, `content-page.php`,
  `content-frontpage.php`, plus `content.php` / `content-excerpt.php` /
  `content-none.php` for archives and search), `template-parts/layout/`
  renders the header/footer content, `template-parts/ajax-filter/` renders
  the ajax filter's markup.
* **`theme/inc/`** holds everything that isn't a template. Three files —
  `enqueue-scripts.php`, `template-tags.php`, `template-functions.php` —
  are `require`d directly from `functions.php`, alongside `class-loader.php`
  and `cta-buttons.php`.
* **`theme/inc/class-loader.php`** is the boot file for everything else
  (read its own docblock first when adding a feature). No autoloader: it
  does two things:
  - `require`s every file under `inc/features/`, `inc/navigation/`,
    `inc/ajax-filter/` and `inc/truncate/` (a class or plain functions,
    doesn't matter which) — drop a new file into one of those and it's
    loaded, no wiring needed. Anything else in `inc/` (currently just
    `class-speculative.php`) needs a line of its own in the file.
  - Boots services: a class implementing the `_Ufo_Service` interface
    (`inc/interface-service.php` — one `register()` method that wires that
    feature's WordPress hooks) is activated by adding its name to the
    `$_ufo_services` array — the one bit of wiring a new feature can't
    avoid, since loading the file only runs its top-level code.
* **`inc/features/`** holds the Customizer-registered services listed above
  (header CTA, social media, front-page hero, custom logo, image block,
  breadcrumbs, Contact Form 7). Despite the folder's previous name
  (`template-parts/custom/`), none of these are template parts.
* **`inc/navigation/`**, **`inc/ajax-filter/`**, **`inc/truncate/`** are
  self-contained feature folders; **`inc/class-speculative.php`** is a
  single-file feature at the top level of `inc/`.
* **Patterns** (`theme/patterns/`) are auto-registered by WordPress from
  that directory — no PHP wiring needed beyond the file's own
  `Title`/`Slug`/`Categories` header comment. The `ufo-boilerplate` pattern
  category is registered in `functions.php`.
* Naming follows the `_s` / `_tw` convention throughout — see Coding
  standards below.

### Performance

* Front-end scripts (`script.min.js`, `headroom.min.js`, `ajax-filter.min.js`)
  are enqueued with `{ strategy: 'defer', in_footer: true }`.
* Templates render post images through `the_post_thumbnail()` /
  `wp_get_attachment_image()`, so they carry `width` / `height` / `srcset` /
  `sizes` (no layout shift). The blog-index and front-page hero images are
  `loading="eager"` with `fetchpriority="high"` (LCP candidates); cards and
  mega-menu thumbnails stay lazy.
* Speculative loading (see above) for near-instant internal navigation.

### Internationalization

* Theme strings use the `ufo-boilerplate` text domain.
* `composer make-pot` regenerates `theme/languages/ufo-boilerplate.pot`
  with wp-cli. `npm run make-pot` does the same without wp-cli via
  `node_scripts/make-pot.php`, which scans `theme/` with the PHP tokenizer.

### Security

* Custom nav-menu fields verify `edit_theme_options` and the
  `update-nav_menu` nonce before saving.
* The ajax filter deep-sanitizes the posted `params` array and clamps the
  requested page size to 1–24 (or the site's "posts per page" setting, if
  higher; filterable via `_ufo_ajax_filter_max_quantity`), caps the tax
  filters to 5 taxonomies × 20 terms, rejects `orderby=rand` and no longer
  echoes the query args back; Contact Form 7 spam checks sanitize their
  `$_SERVER` / `$_POST` reads.
* `content-single.php` injects the table-of-contents `id` anchors with
  `WP_HTML_Tag_Processor` (not a regex) and strips stray inline `on*`
  handlers from headings.

### Coding standards

* Run `composer install`, then `composer php:lint` (or `npm run lint:php`);
  `composer php:lint:autofix` runs phpcbf. The theme is clean against
  `WordPress-Extra` + `WordPress-Docs` — see `phpcs.xml.dist`. Composer is
  dev tooling only (phpcs, wpcs, `wp i18n make-pot`) — it's never a runtime
  dependency of the theme itself, which has no autoloader (see
  `class-loader.php` above).
* The theme follows the `_s` / `_tw` leading-underscore prefix convention
  (`_ufo_*` functions, `_Ufo_*` classes); `phpcs.xml.dist` is configured to
  accept it, and `.git-blame-ignore-revs` hides the one-off phpcbf reformat
  commit from `git blame`.

### Build notes

* The WordPress `style.css` header is injected by `postcss-header`
  (`postcss.config.js`) so it stays the first comment in the compiled file,
  rather than being pulled in from a CSS partial.

## Quickstart

### Installation

1. Move this folder to `wp-content/themes` in your local development environment
2. Run `npm install && npm run dev` in this folder
3. Activate this theme in your local WordPress installation

Using WordPress Multisite? Don’t forget that your theme must first be enabled via the Network Admin in order to be available for activation on a network site.

### Development

4. Run `npm run watch`
5. Add [Tailwind utility classes](https://tailwindcss.com/docs/utility-first) with abandon

### Deployment

6. Run `npm run bundle`
7. Upload the resulting zip file to your site using the “Upload Theme” button on the “Add Themes” administration page

Or [deploy with the tool of your choice](https://underscoretw.com/docs/deployment/#h-other-deployment-options)!

## Full Documentation

### Fundamentals

* [Installation](https://underscoretw.com/docs/installation/)  
  Generate your custom theme, install it in WordPress and run your first Tailwind builds
* [Development](https://underscoretw.com/docs/development/)  
  Watch for changes, build for production and learn more about how _tw, WordPress and Tailwind work together
* [Deployment](https://underscoretw.com/docs/deployment/)  
  Share your new WordPress theme with the world
* [Troubleshooting](https://underscoretw.com/docs/troubleshooting/)  
  Find solutions to potential issues and answers to frequently asked questions

### In Depth

* [Using Tailwind Typography](https://underscoretw.com/docs/tailwind-typography/)  
  Customize front-end and back-end typographic styles
* [JavaScript Bundling with esbuild](https://underscoretw.com/docs/esbuild/)  
  Install and bundle JavaScript libraries (very quickly)
* [Adding custom fonts](https://underscoretw.com/docs/custom-fonts/)
  Host your fonts yourself or use a third party—and then add those fonts to your WordPress theme
* [Linting and Code Formatting](https://underscoretw.com/docs/linting-code-formatting/)  
  Catch bugs and stop thinking about formatting
* [Keeping your theme up-to-date](https://underscoretw.com/docs/updating/)
  How to update (and whether or not you should)

### Extras

* [On Tailwind and WordPress](https://underscoretw.com/docs/wordpress-tailwind/)  
  Understand how WordPress and Tailwind work together
* [Styling HTML from outside the theme](https://underscoretw.com/docs/styling-html-from-outside-the-theme/)
  Work with WordPress core, plugins and JavaScript libraries
* [Managing Styles for Custom Blocks](https://underscoretw.com/docs/custom-blocks/)  
  Learn strategies for using Tailwind in theme-specific custom blocks
* [Setting Up Browsersync](https://underscoretw.com/docs/browsersync/)  
  Add live reloads and synchronized cross-device testing to your workflow
