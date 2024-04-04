<?php

add_action('wp_enqueue_scripts', 'iceberg_breakdance_custom_click_handler_enqueue_script');
if ( ! function_exists( 'iceberg_breakdance_custom_click_handler_enqueue_script' ) ) {
    function iceberg_breakdance_custom_click_handler_enqueue_script() {
        wp_enqueue_script('custom-click-handler-js', plugins_url('assets/js/iceberg-menu-click-handler.js', __FILE__), array('jquery'), '1.0', true);
        wp_enqueue_script('custom-activation-dropdown-js', plugins_url('assets/js/iceberg-menu-activation-dropdown.js', __FILE__), array('jquery'), '1.0', true);
    };
}