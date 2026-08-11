# Busly `demo-import/` — logic lives in `inc/admin/`, not here

The actual demo-import engine is `inc/admin/class-demo-import.php` (pages,
menus, theme options) and `inc/admin/class-elementor-homepage.php` (the
homepage layout) — both driven by `Busly_Setup_Wizard`
(`inc/admin/class-setup-wizard.php`) and its AJAX-stepped UI
(`assets/js/setup-wizard.js`).

This folder is kept as the documented drop-in location for any binary demo
assets a future release adds (e.g. a licensed hero photo pack) — Busly's
current demo content deliberately uses only Elementor's built-in placeholder
image and generated initials-avatars, so nothing is bundled here yet (see
PHASE 20: "Do not bundle copyrighted demo images").
