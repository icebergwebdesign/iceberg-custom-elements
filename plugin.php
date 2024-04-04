<?php

/**
 * Plugin Name: Iceberg Custom Elements
 * Plugin URI: https://www.icebergwebdesign.com/
 * Description: Iceberg's custom elements for Breakdance
 * Author: Iceberg Web Design
 * Author URI: https://www.icebergwebdesign.com/
 * License: GPLv2
 * Text Domain: iceberg
 * Domain Path: /languages/
 * Version: 2.0.1
 * @github-updater
 * GitHub Plugin URI: icebergwebdesign/iceberg-custom-elements
 * GitHub Plugin URI: https://github.com/icebergwebdesign/iceberg-custom-elements
 * Primary Branch: main
 */
namespace IcebergCustomElements;

use function Breakdance\Util\getDirectoryPathRelativeToPluginFolder;

add_action('breakdance_loaded', function () {
    \Breakdance\ElementStudio\registerSaveLocation(
        getDirectoryPathRelativeToPluginFolder(__DIR__) . '/elements',
        'IcebergCustomElements',
        'element',
        'Iceberg Custom Elements',
        false
    );

    \Breakdance\ElementStudio\registerSaveLocation(
        getDirectoryPathRelativeToPluginFolder(__DIR__) . '/macros',
        'IcebergCustomElements',
        'macro',
        'Iceberg Custom Macros',
        false,
    );

    \Breakdance\ElementStudio\registerSaveLocation(
        getDirectoryPathRelativeToPluginFolder(__DIR__) . '/presets',
        'IcebergCustomElements',
        'preset',
        'Iceberg Custom Presets',
        false,
    );
},
    // register elements before loading them, below is not a typo
    9
);

define( 'ICEBERG_BREAKDANCE_CUSTOM_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'ICEBERG_BREAKDANCE_CUSTOM_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
include_once ICEBERG_BREAKDANCE_CUSTOM_PLUGIN_DIR . 'includes/breakdance_enhanced_menu.php';