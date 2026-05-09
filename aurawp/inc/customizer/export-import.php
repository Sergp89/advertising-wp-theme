<?php
/**
 * AuraWP Customizer - Export/Import Settings
 * 
 * Allows exporting and importing theme settings as JSON
 * 
 * @package AuraWP
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add Export/Import section to Customizer
 * 
 * @param WP_Customize_Manager $wp_customize The Customizer object.
 * @return void
 */
function aurawp_customize_export_import_section($wp_customize) {
    
    // Add Export/Import Section
    $wp_customize->add_section('aurawp_export_import', array(
        'title'       => esc_html__('Export / Import', 'aurawp'),
        'priority'    => 100,
        'panel'       => 'aurawp_theme_options',
        'description' => esc_html__('Export your theme settings or import previously saved settings.', 'aurawp')
    ));
    
    // Export Settings
    $wp_customize->add_setting('export_settings', array(
        'sanitize_callback' => '__return_null'
    ));
    
    $wp_customize->add_control('export_settings', array(
        'label'       => esc_html__('Export Settings', 'aurawp'),
        'description' => esc_html__('Download all theme settings as a JSON file', 'aurawp'),
        'section'     => 'aurawp_export_import',
        'type'        => 'button',
        'settings'    => array(),
        'input_attrs' => array(
            'data-action' => 'aurawp-export'
        )
    ));
    
    // Import Settings
    $wp_customize->add_setting('import_settings', array(
        'sanitize_callback' => '__return_null'
    ));
    
    $wp_customize->add_control(new AuraWP_Import_Control($wp_customize, 'import_settings', array(
        'label'       => esc_html__('Import Settings', 'aurawp'),
        'description' => esc_html__('Upload a JSON file with theme settings', 'aurawp'),
        'section'     => 'aurawp_export_import',
        'settings'    => array()
    )));
}
add_action('customize_register', 'aurawp_customize_export_import_section');

/**
 * Custom control for file import
 */
class AuraWP_Import_Control extends WP_Customize_Control {
    public $type = 'aurawp-import';
    
    public function render_content() {
        ?>
        <label>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php if (!empty($this->description)) : ?>
                <span class="description customize-control-description"><?php echo esc_html($this->description); ?></span>
            <?php endif; ?>
            
            <input type="file" id="<?php echo esc_attr($this->id); ?>" accept=".json" />
            
            <button type="button" class="button button-secondary" onclick="aurawpImportSettings()">
                <?php esc_html_e('Import', 'aurawp'); ?>
            </button>
            
            <span class="spinner" style="float: none; margin-left: 10px;"></span>
            <div class="aurawp-import-message"></div>
        </label>
        
        <script>
        function aurawpImportSettings() {
            const fileInput = document.getElementById('<?php echo esc_js($this->id); ?>');
            const file = fileInput.files[0];
            
            if (!file) {
                showMessage('<?php echo esc_js(__('Please select a JSON file', 'aurawp')); ?>', 'error');
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const settings = JSON.parse(e.target.result);
                    
                    // Send to server via AJAX
                    fetch(ajaxurl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams({
                            action: 'aurawp_import_settings',
                            settings: JSON.stringify(settings),
                            nonce: '<?php echo wp_create_nonce('aurawp_import_nonce'); ?>'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showMessage('<?php echo esc_js(__('Settings imported successfully! Refreshing...', 'aurawp')); ?>', 'success');
                            setTimeout(() => location.reload(), 1500);
                        } else {
                            showMessage(data.data || '<?php echo esc_js(__('Import failed', 'aurawp')); ?>', 'error');
                        }
                    })
                    .catch(error => {
                        showMessage('<?php echo esc_js(__('An error occurred', 'aurawp')); ?>', 'error');
                        console.error(error);
                    });
                } catch (error) {
                    showMessage('<?php echo esc_js(__('Invalid JSON file', 'aurawp')); ?>', 'error');
                }
            };
            reader.readAsText(file);
        }
        
        function showMessage(message, type) {
            const messageEl = document.querySelector('.aurawp-import-message');
            if (messageEl) {
                messageEl.textContent = message;
                messageEl.className = 'aurawp-import-message ' + type;
            }
        }
        </script>
        
        <style>
        .aurawp-import-message {
            margin-top: 10px;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 13px;
        }
        .aurawp-import-message.success {
            background: #d4edda;
            color: #155724;
        }
        .aurawp-import-message.error {
            background: #f8d7da;
            color: #721c24;
        }
        </style>
        <?php
    }
}

/**
 * Handle AJAX import request
 * 
 * @return void
 */
function aurawp_ajax_import_settings() {
    // Verify nonce
    if (!check_ajax_referer('aurawp_import_nonce', 'nonce', false)) {
        wp_send_json_error(array('message' => __('Security check failed', 'aurawp')));
    }
    
    // Check capabilities
    if (!current_user_can('edit_theme_options')) {
        wp_send_json_error(array('message' => __('Insufficient permissions', 'aurawp')));
    }
    
    // Get and decode settings
    $settings_json = isset($_POST['settings']) ? $_POST['settings'] : '';
    $settings = json_decode($settings_json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        wp_send_json_error(array('message' => __('Invalid JSON data', 'aurawp')));
    }
    
    // List of valid theme mods to import
    $valid_mods = array(
        'color_primary',
        'color_secondary',
        'glass_transparency',
        'glow_intensity',
        'animation_type',
        'animation_duration',
        'animation_easing',
        'animation_stagger',
        'camera_speed',
        'fog_density',
        'lod_level',
        'enable_3d_background',
        'reduced_motion',
        'enable_hero_animation',
        'enable_cards_animation',
        'enable_footer_animation'
    );
    
    // Update each setting
    foreach ($valid_mods as $mod) {
        if (isset($settings[$mod])) {
            set_theme_mod($mod, $settings[$mod]);
        }
    }
    
    wp_send_json_success(array('message' => __('Settings imported successfully', 'aurawp')));
}
add_action('wp_ajax_aurawp_import_settings', 'aurawp_ajax_import_settings');

/**
 * Output export button script in customizer
 * 
 * @return void
 */
function aurawp_export_script() {
    if (!is_customize_preview()) {
        return;
    }
    
    $export_url = wp_nonce_url(admin_url('admin-post.php?action=aurawp_export_settings'), 'aurawp_export_nonce');
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('[data-action="aurawp-export"]').on('click', function(e) {
            e.preventDefault();
            window.location.href = '<?php echo esc_url($export_url); ?>';
        });
    });
    </script>
    <?php
}
add_action('customize_controls_print_footer_scripts', 'aurawp_export_script');

/**
 * Handle export action
 * 
 * @return void
 */
function aurawp_handle_export() {
    // Verify nonce
    if (!check_admin_referer('aurawp_export_nonce')) {
        wp_die(__('Security check failed', 'aurawp'));
    }
    
    // Check capabilities
    if (!current_user_can('edit_theme_options')) {
        wp_die(__('Insufficient permissions', 'aurawp'));
    }
    
    // Get all theme mods
    $all_mods = get_theme_mods();
    
    // Filter to only include AuraWP settings
    $aurawp_mods = array();
    $valid_mods = array(
        'color_primary',
        'color_secondary',
        'glass_transparency',
        'glow_intensity',
        'animation_type',
        'animation_duration',
        'animation_easing',
        'animation_stagger',
        'camera_speed',
        'fog_density',
        'lod_level',
        'enable_3d_background',
        'reduced_motion',
        'enable_hero_animation',
        'enable_cards_animation',
        'enable_footer_animation'
    );
    
    foreach ($valid_mods as $mod) {
        if (isset($all_mods[$mod])) {
            $aurawp_mods[$mod] = $all_mods[$mod];
        }
    }
    
    // Add metadata
    $export_data = array(
        'version'     => AURAWP_VERSION,
        'exported_at' => current_time('mysql'),
        'site_url'    => get_site_url(),
        'settings'    => $aurawp_mods
    );
    
    // Set headers for download
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="aurawp-settings-' . date('Y-m-d') . '.json"');
    header('Pragma: no-cache');
    header('Expires: 0');
    
    echo json_encode($export_data, JSON_PRETTY_PRINT);
    exit;
}
add_action('admin_post_aurawp_export_settings', 'aurawp_handle_export');
