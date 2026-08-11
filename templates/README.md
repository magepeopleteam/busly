# Busly `templates/` — Bus Ticket Booking plugin overrides

`WBTM_Functions::template_path( $file_name )` (in the Bus Ticket Booking with
Seat Reservation plugin) resolves every plugin template through
`locate_template( ['templates/' . $file_name] )` first, falling back to the
plugin's own `templates/$file_name` only when the theme doesn't provide one.
This directory is that **officially supported** override point — no plugin
files are ever modified.

## What Busly overrides here, and why

| File | Why it's safe to override |
|---|---|
| `single_page/single-bus.php` | Pure page chrome: calls `get_header()`/`get_footer()`, fires the plugin's own `wbtm_before_single_bus_search_page` / `wbtm_after_single_bus_search_page` / `woocommerce_before_single_product` hooks unchanged, and `require`s the plugin's own `layout/single_bus_details.php` + `layout/search_form.php`. Zero booking business logic — only the wrapper markup/classes change. |
| `single_page/bus-search-list.php` | Same story: page chrome around `do_shortcode('[wbtm-bus-search]')`, plugin hooks preserved. |

## What Busly deliberately does **not** override

Every other plugin template (`layout/search_form.php`, `layout/seat_plan.php`,
`layout/registration_seat_plan.php`, `layout/registration_without_seat_plan.php`,
`layout/selected_seat.php`, `layout/add_to_cart.php`, etc.) contains real
booking logic: nonce fields, hidden inputs the plugin's own JS binds to by
`id`/`name`, AJAX payload shape, seat-lock/availability markup. Copying those
into the theme would duplicate business logic the brief explicitly forbids
and would silently drift out of sync on every plugin update.

Busly re-skins that markup instead, purely with CSS, by targeting the
plugin's own stable class names (`.wbtm_search_area`, `.mp_seat`,
`.seat_available`/`.seat_booked`/`.seat_in_cart`, `.wbtm-bus-list`, …) — see
`assets/css/booking.css`. If a future plugin update changes those class
names, only the CSS needs a follow-up patch; booking logic itself is never at risk.

Because `template_path()` falls back per-file to the plugin's own copy when a
matching theme file doesn't exist, this directory can safely stay this small.
