<?php
namespace IcebergCustomElements;

// Hook into Breakdance admin menu
add_action('breakdance_admin_menu', __NAMESPACE__ . '\\iceberg_breakdance_add_settings_page');
 
function iceberg_breakdance_add_settings_page() {
    add_submenu_page(
        'breakdance',        // Parent slug (Breakdance main menu)
        'Image Alt Updater',            // Page title
        'Alt Tag Updater',              // Menu title (shorter for menu space)
        'manage_options',               // Capability
        'breakdance-alt-updater',       // Menu slug
        __NAMESPACE__ . '\\iceberg_breakdance_render_settings_page'    // Callback function
    );
}
 
function iceberg_breakdance_render_settings_page() {
    ?>
    <div class="breakdance-admin-page breakdance-admin-page--tools">
        <div class="breakdance-admin-page__header">
            <div class="breakdance-admin-page__title">
                <h1>Image Alt Tag Updater</h1>
            </div>
        </div>
        <div class="breakdance-admin-page__content">
            <?php display_breakdance_update_notice(); ?>
            <div class="breakdance-admin-page__section">
                <div class="breakdance-admin-page__section-content">
                    <form method="post" action="" id="bd-update-form">
                        <?php
                        wp_nonce_field('iceberg_breakdance_update_alt_nonce', 'iceberg_breakdance_update_alt_nonce_field');
                        ?>
                        <div class="breakdance-admin-page__section-group">
                            <div class="breakdance-admin-page__section-group-title">
                                <h3>Database Backup Confirmation</h3>
                            </div>
                            <div class="breakdance-admin-page__section-group-content">
                                <label class="breakdance-admin-page__checkbox">
                                    <input type="checkbox" id="backup-confirmation" />
                                    <span>I have created a backup of my database</span>
                                </label>
                                <p class="breakdance-admin-page__description">
                                    Click the button below to update the alt tags for images in the Breakdance meta data.
                                </p>
                                <div class="breakdance-admin-page__button-group">
                                    <input type="submit"
                                           name="update_alt_tags"
                                           class="button"
                                           id="update-button"
                                           value="Update Image Alt Tags"
                                           disabled />
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('backup-confirmation').addEventListener('change', function() {
            document.getElementById('update-button').disabled = !this.checked;
        });
    </script>
    <?php
}
 
add_action('admin_init', __NAMESPACE__ . '\\iceberg_breakdance_check_update_alt_tags');
 
function iceberg_breakdance_check_update_alt_tags() {
    if (isset($_POST['update_alt_tags']) && check_admin_referer('iceberg_breakdance_update_alt_nonce', 'iceberg_breakdance_update_alt_nonce_field')) {
        updateImageAltTagsInBreakdanceData();
        add_action('admin_notices', __NAMESPACE__ . '\\iceberg_breakdance_update_success_notice');
    }
}
 
function iceberg_breakdance_update_success_notice() {
    // Store the notice in a transient to display it in our admin page
    set_transient('breakdance_alt_updater_notice', 'success', 45);
}

// Add this function to display the notice in our template
function display_breakdance_update_notice() {
    $notice = get_transient('breakdance_alt_updater_notice');
    if ($notice === 'success') {
        ?>
        <div class="breakdance-admin-page__notice breakdance-admin-page__notice--success">
            <div class="breakdance-admin-page__notice-content">
                <p>Image alt tags have been successfully updated in all Breakdance elements.</p>
            </div>
        </div>
        <?php
        delete_transient('breakdance_alt_updater_notice');
    }
}
 
function updateImageAltTagsInBreakdanceData() {
    global $wpdb;
 
    $metaKey = '_breakdance_data';
    $imageMetaKey = '_wp_attachment_image_alt';
 
    $postMetaData = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT post_id, meta_value FROM $wpdb->postmeta WHERE meta_key = %s",
            $metaKey
        )
    );
 
    foreach ($postMetaData as $metaData) {
        $metaValue = json_decode($metaData->meta_value, true);
 
        if (isset($metaValue['tree_json_string'])) {
            $treeData = json_decode($metaValue['tree_json_string'], true);
 
            if ($treeData) {
                updateAltTagsRecursively($treeData['root'], $imageMetaKey, $wpdb);
 
                $metaValue['tree_json_string'] = json_encode($treeData);
                update_post_meta($metaData->post_id, $metaKey, wp_slash(json_encode($metaValue)));
            }
        }
    }
}
 
function updateAltTagsRecursively(&$node, $imageMetaKey, $wpdb) {
    // Check for Gallery element
    if (!empty($node['data']['type']) && $node['data']['type'] === 'EssentialElements\\Gallery') {
        if (!empty($node['data']['properties']['content']['content']['images'])) {
            foreach ($node['data']['properties']['content']['content']['images'] as &$image) {
                updateImageAlt($image['image'], $imageMetaKey, $wpdb);
            }
        }
    }
 
    // Check for Image element
    if (!empty($node['data']['properties']['content']['content']['image'])) {
        updateImageAlt($node['data']['properties']['content']['content']['image'], $imageMetaKey, $wpdb);
    }
 
    // Check for ImageBox element
    if (!empty($node['data']['type']) && $node['data']['type'] === 'EssentialElements\\ImageBox') {
        if (!empty($node['data']['properties']['content']['content']['image'])) {
            updateImageAlt($node['data']['properties']['content']['content']['image'], $imageMetaKey, $wpdb);
        }
    }
 
    // Check for ImageHoverCard element
    if (!empty($node['data']['type']) && $node['data']['type'] === 'EssentialElements\\ImageHoverCard') {
        if (!empty($node['data']['properties']['content']['image']['image'])) {
            updateImageAlt($node['data']['properties']['content']['image']['image'], $imageMetaKey, $wpdb);
        }
    }
 
    // Check for ImageWithZoom element
    if (!empty($node['data']['type']) && $node['data']['type'] === 'EssentialElements\\ImageWithZoom') {
        if (!empty($node['data']['properties']['content']['controls']['image'])) {
            updateImageAlt($node['data']['properties']['content']['controls']['image'], $imageMetaKey, $wpdb);
        }
    }
 
    // Check for ImageAccordion element
    if (!empty($node['data']['type']) && $node['data']['type'] === 'EssentialElements\\ImageAccordion') {
        if (!empty($node['data']['properties']['content']['content']['images'])) {
            foreach ($node['data']['properties']['content']['content']['images'] as &$accordionImage) {
                if (!empty($accordionImage['image'])) {
                    updateImageAlt($accordionImage['image'], $imageMetaKey, $wpdb);
                }
            }
        }
    }
 
    // Recursively process children
    if (!empty($node['children'])) {
        foreach ($node['children'] as &$child) {
            updateAltTagsRecursively($child, $imageMetaKey, $wpdb);
        }
    }
}
 
function updateImageAlt(&$image, $imageMetaKey, $wpdb) {
    if (!empty($image['id'])) {
        $imageId = $image['id'];
        $newAltText = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT meta_value FROM $wpdb->postmeta WHERE post_id = %d AND meta_key = %s",
                $imageId,
                $imageMetaKey
            )
        );
        if ($newAltText !== null && $newAltText !== '') {
            $image['alt'] = $newAltText;
        } else {
            // Remove the 'alt' key if the new alt text is empty or null
            unset($image['alt']);
        }
    }
}