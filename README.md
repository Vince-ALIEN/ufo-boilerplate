Ufo Boilerplate
===============

A custom WordPress theme based on [\_tw](https://underscoretw.com/), extended
with a set of theme-specific customizations (see below) on top of the
standard \_tw quickstart/build workflow.

## Project-specific customizations

Beyond the \_tw base, this theme adds (mostly under
`theme/template-parts/custom/` and `theme/inc/`, auto-loaded by
`theme/inc/class-loader.php`):

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
  support for the header.
* **Breadcrumbs** (`class-breadcrumb.php`) — accessible, schema.org-annotated
  breadcrumb trail with Category Base support.
* **Footer navigation** (`class-footer-nav.php`) — applies Tailwind classes
  to the footer's `wp_nav_menu()` links.
* **Contact Form 7 integration** (`class-contact-form-7.php`) — restyles CF7
  markup with Tailwind (including a distinct newsletter-form variant),
  replaces the submit `<input>` with a `<button>` + Lucide icon, wraps the
  form in a `bg-slate-200 rounded-xl px-4` container, adds honeypot/timing
  spam protection, and only enqueues CF7's assets on pages that actually use
  it.
* **Per-page CTA buttons** (`inc/cta-buttons.php`) — post meta for two
  call-to-action buttons in the page header, editable from the block
  editor's Document sidebar.
* **Ajax filter** (`inc/ajax-filter/`) — client-side filtering for archive
  listings.
* **Image block** (`class-image-block.php`) — excludes `core/image` from
  Tailwind Typography's `.prose` styles.
* **Truncate helper** (`inc/truncate/`) — character-based text truncation.

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
