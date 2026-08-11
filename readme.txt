=== Busly ===

Contributors: magepeople
Requires at least: 6.0
Tested up to: 6.7
Requires PHP: 8.0
Version: 1.0.0
License: GNU General Public License v2 or later
License URI: LICENSE
Tags: e-commerce, blog, one-column, two-columns, right-sidebar, custom-menu, custom-logo, featured-images, rtl-language-support, threaded-comments, translation-ready, travel, transportation

Premium Bus Ticket Booking & Transportation WordPress Theme.

== Description ==

Busly is a premium WordPress theme for bus operators, coach and shuttle
services, and travel agencies. It is built around the "Bus Ticket Booking
with Seat Reservation" plugin (search, live seat maps, WooCommerce checkout,
customer bookings dashboard) and a 100% Elementor-editable homepage.

= Key features =

* Homepage is a real WordPress Page, fully editable section-by-section in Elementor.
* 14 custom Elementor widgets (Hero, Bus Search, Bus Listing, Featured Routes,
  Destinations, Features, Counter, Steps, Offers, Testimonials, Pricing, FAQ,
  CTA, Contact Info) under their own "Busly" category.
* Deep integration with the Bus Ticket Booking with Seat Reservation plugin —
  the theme never duplicates its booking logic, only restyles its real
  markup and renders its real shortcodes.
* Optional WooCommerce support (shop, cart, checkout, account) — the theme
  works normally with WooCommerce inactive too.
* Busly → Theme Settings: a single, tabbed settings screen for layout,
  header, typography, colors, footer, blog, bus booking, WooCommerce and
  performance options.
* Busly → Setup Wizard: plugin requirement checks + one-click, idempotent,
  non-destructive demo content import.
* Translation-ready, RTL-ready, keyboard-accessible, child-theme friendly.

= Required plugins =

* Elementor (page building)
* Bus Ticket Booking with Seat Reservation (booking engine — requires
  WooCommerce to be active for search results/checkout to function; see
  the plugin's own requirements)

= Optional plugins =

* WooCommerce (only needed if you also sell physical/digital products, or to
  power the bus booking plugin's checkout — see above)
* Yoast SEO / Rank Math (SEO — Busly defers all SEO meta output to whichever
  plugin is active)
* Contact Form 7 / WPForms (contact forms — Busly restyles either automatically)

== Installation ==

1. Install and activate Elementor.
2. Install and activate "Bus Ticket Booking with Seat Reservation".
3. If you plan to accept bookings, install and activate WooCommerce (required
   by the booking plugin itself for cart/checkout).
4. In wp-admin, go to Appearance → Themes → Add New → Upload Theme, upload
   busly.zip, then Activate.
5. Go to Busly → Setup Wizard and follow the 5 steps (Welcome → Required
   Plugins → Demo Import → Homepage → Finish).

See documentation/index.html for a full walkthrough.

== Frequently Asked Questions ==

= Does Busly work without WooCommerce? =

Yes, the theme itself never requires WooCommerce. However, the Bus Ticket
Booking with Seat Reservation plugin's own search results, seat maps and
checkout only register once WooCommerce is active — that is a limitation of
the plugin, not of Busly (see documentation/troubleshooting.html).

= Can I use a child theme? =

Yes — Busly follows the standard WordPress template hierarchy and exposes
`busly_before_*` / `busly_after_*` action hooks throughout. See
documentation/child-theme.html.

= Will a plugin update break my booking pages? =

No plugin files are ever modified. Busly's booking styling
(assets/css/booking.css) targets the plugin's own stable CSS classes; the
only plugin templates Busly overrides (templates/single_page/*.php) contain
zero business logic — see templates/README.md.

== Changelog ==

= 1.0.0 =
* Initial release.

== Credits ==

* Plus Jakarta Sans font — SIL Open Font License, https://fonts.google.com/specimen/Plus+Jakarta+Sans
* Demo destination/hero photography referenced in the design mockup is from
  Unsplash (unsplash.com) and is NOT bundled with this theme — the Setup
  Wizard's demo import uses local placeholder images only. Replace with your
  own licensed photography before launch.
