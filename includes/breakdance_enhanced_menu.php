<?php

add_action('wp_enqueue_scripts', 'iceberg_breakdance_custom_click_handler_enqueue_script');
if ( ! function_exists( 'iceberg_breakdance_custom_click_handler_enqueue_script' ) ) {
    function iceberg_breakdance_custom_click_handler_enqueue_script() {
        wp_enqueue_script('custom-click-handler-js', ICEBERG_BREAKDANCE_CUSTOM_PLUGIN_URL . 'assets/js/iceberg-menu-click-handler.js', array('jquery'), '1.0', true);
        wp_enqueue_script('custom-activation-dropdown-js', ICEBERG_BREAKDANCE_CUSTOM_PLUGIN_URL . 'assets/js/iceberg-menu-activation-dropdown.js', array('jquery'), '1.0', true);
    };
}