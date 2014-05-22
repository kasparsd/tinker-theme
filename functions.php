<?php

// After theme setup, basic enqueues, theme properties
include get_template_directory() . '/inc/theme-setup.php';

// Theme elements, post meta data, pagination
include get_template_directory() . '/inc/theme-parts.php';

// Only menus
include get_template_directory() . '/inc/theme-menus.php';

// Nice to have
include get_template_directory() . '/inc/theme-features.php';

// Theme options
include get_template_directory() . '/inc/theme-admin.php';
