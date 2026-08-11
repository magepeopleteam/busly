# Busly `elementor/templates/` — generated, not stored as static JSON

Busly's Elementor Template Library content (the homepage layout) is built
**programmatically** by `inc/admin/class-elementor-homepage.php`, which
writes a fresh `_elementor_data` array directly onto the demo "Home" page
during Setup Wizard → Demo Import, using each Busly widget's own built-in
defaults for content.

This keeps the generated layout always in sync with the widgets' current
default settings (change a widget's default array once, the next import
picks it up automatically) instead of maintaining a separate, easily
stale static `.json` export that would need manual re-generation every time
a widget's controls change.

If you want to ship additional pre-built Elementor templates (e.g. alternate
homepage layouts, a landing page), export them from Elementor's own
Template Library as `.json` and drop them here — `Busly_Setup_Wizard`/
`Busly_Demo_Import` can be extended to import them the same way WordPress
core imports content, via `Elementor\TemplateLibrary\Source_Local::import_template()`.
