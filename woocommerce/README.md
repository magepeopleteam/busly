# Busly `woocommerce/` — intentionally near-empty

WooCommerce looks in this folder first (`wc_locate_template()`) before
falling back to its own plugin templates. Busly restyles WooCommerce purely
through **hooks and CSS** — `inc/integrations/woocommerce.php` (wrapper hooks,
button classes, layout filters) and `assets/css/woocommerce.css` — instead of
copying WooCommerce's own template files in here.

Per PHASE 16 ("do not unnecessarily override WooCommerce templates… do not
break WooCommerce updates"): every WooCommerce template file WooCommerce
ships is already themeable via hooks/filters/CSS classes, so duplicating them
here would only create maintenance risk (silent breakage on a WooCommerce
core update that changes a template's structure) for no visual benefit.

If a future customization genuinely needs a structural template override
(not just style), add the specific file here following WooCommerce's own
`woocommerce/templates/` folder structure — WooCommerce will pick it up
automatically once present.
