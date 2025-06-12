<?php
/**
 * Plugin Name: Menu Cleaner
 * Description: Clean invalid and missing items from WordPress navigation menus
 * Version: 1.0.0
 * Author: TerraChad / Vladyslav Olshevskyi
 * License: GPL v2 or later
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class MenuCleaner {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_ajax_clean_menus', array($this, 'clean_menus_ajax'));
    }
    
    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_management_page(
            'Menu Cleaner',
            'Menu Cleaner',
            'manage_options',
            'menu-cleaner',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Admin page content
     */
    public function admin_page() {
        $menus = wp_get_nav_menus();
        ?>
        <div class="wrap">
            <h1>Menu Cleaner</h1>
            <p>Select which navigation menus to scan and clean for invalid items.</p>
            
            <?php if (!empty($menus)): ?>
            <div style="margin: 20px 0; padding: 15px; background: #f9f9f9; border: 1px solid #ddd;">
                <h3>Select Menus to Clean:</h3>
                <?php foreach ($menus as $menu): ?>
                <label style="display: block; margin: 8px 0;">
                    <input type="checkbox" name="selected_menus[]" value="<?php echo esc_attr($menu->term_id); ?>" checked>
                    <strong><?php echo esc_html($menu->name); ?></strong> 
                    <span style="color: #666;">(<?php echo wp_get_nav_menu_items($menu->term_id) ? count(wp_get_nav_menu_items($menu->term_id)) : 0; ?> items)</span>
                </label>
                <?php endforeach; ?>
                <div style="margin-top: 15px;">
                    <button type="button" id="select-all" class="button button-small">Select All</button>
                    <button type="button" id="select-none" class="button button-small">Select None</button>
                </div>
            </div>
            <?php else: ?>
            <div class="notice notice-warning">
                <p>No navigation menus found. Please create some menus first in <a href="<?php echo admin_url('nav-menus.php'); ?>">Appearance > Menus</a>.</p>
            </div>
            <?php endif; ?>
            
            <div id="menu-cleaner-results" style="margin: 20px 0;"></div>
            
            <?php if (!empty($menus)): ?>
            <button id="scan-menus" class="button button-secondary">Scan Selected Menus</button>
            <button id="clean-menus" class="button button-primary" style="display:none;">Clean Invalid Items</button>
            <?php endif; ?>
            
            <div id="menu-items-list" style="margin-top: 20px;"></div>
        </div>
        
        <script>
        jQuery(document).ready(function($) {
            $("#select-all").click(function() {
                $("input[name='selected_menus[]']").prop("checked", true);
            });
            
            $("#select-none").click(function() {
                $("input[name='selected_menus[]']").prop("checked", false);
            });
            
            $("#scan-menus").click(function() {
                var selectedMenus = [];
                $("input[name='selected_menus[]']:checked").each(function() {
                    selectedMenus.push($(this).val());
                });
                
                if (selectedMenus.length === 0) {
                    alert("Please select at least one menu to scan.");
                    return;
                }
                
                $(this).prop("disabled", true).text("Scanning...");
                $("#menu-cleaner-results").html("<p>Scanning selected menus...</p>");
                
                var data = {
                    action: "clean_menus",
                    operation: "scan",
                    selected_menus: selectedMenus,
                    nonce: "<?php echo wp_create_nonce('menu_cleaner_nonce'); ?>"
                };
                
                $.post(ajaxurl, data, function(response) {
                    if (response.success) {
                        var validCount = response.data.valid_count;
                        var invalidCount = response.data.invalid_items.length;
                        var scannedMenus = response.data.scanned_menus;
                        
                        $("#menu-cleaner-results").html(
                            "<div class=\"notice notice-info\"><p><strong>Scan Complete:</strong> Scanned " + 
                            scannedMenus + " menu(s). Found " + validCount + " valid items and " + 
                            invalidCount + " invalid items.</p></div>"
                        );
                        
                        if (invalidCount > 0) {
                            $("#clean-menus").show();
                            var html = "<h3>Invalid Menu Items Found:</h3><ul style=\"background: #f9f9f9; padding: 15px; border-left: 4px solid #dc3232;\">";
                            $.each(response.data.invalid_items, function(i, item) {
                                html += "<li><strong>" + item.title + "</strong> (Menu: " + item.menu_name + ") - " + item.reason + "</li>";
                            });
                            html += "</ul>";
                            $("#menu-items-list").html(html);
                        } else {
                            $("#menu-items-list").html("<p><strong>Great!</strong> All menu items in selected menus are valid.</p>");
                        }
                    } else {
                        $("#menu-cleaner-results").html("<div class=\"notice notice-error\"><p>Error: " + response.data + "</p></div>");
                    }
                    $("#scan-menus").prop("disabled", false).text("Scan Selected Menus");
                });
            });
            
            $("#clean-menus").click(function() {
                if (!confirm("Are you sure you want to remove all invalid menu items? This action cannot be undone.")) {
                    return;
                }
                
                var selectedMenus = [];
                $("input[name='selected_menus[]']:checked").each(function() {
                    selectedMenus.push($(this).val());
                });
                
                $(this).prop("disabled", true).text("Cleaning...");
                
                var data = {
                    action: "clean_menus",
                    operation: "clean",
                    selected_menus: selectedMenus,
                    nonce: "<?php echo wp_create_nonce('menu_cleaner_nonce'); ?>"
                };
                
                $.post(ajaxurl, data, function(response) {
                    if (response.success) {
                        $("#menu-cleaner-results").html(
                            "<div class=\"notice notice-success\"><p><strong>Success:</strong> Removed " + 
                            response.data.removed_count + " invalid menu items.</p></div>"
                        );
                        $("#clean-menus").hide();
                        $("#menu-items-list").html("");
                    } else {
                        $("#menu-cleaner-results").html("<div class=\"notice notice-error\"><p>Error: " + response.data + "</p></div>");
                    }
                    $("#clean-menus").prop("disabled", false).text("Clean Invalid Items");
                });
            });
        });
        </script>
        <?php
    }
    
    /**
     * AJAX handler for menu cleaning operations
     */
    public function clean_menus_ajax() {
        if (!wp_verify_nonce($_POST['nonce'], 'menu_cleaner_nonce')) {
            wp_die('Security check failed');
        }
        
        if (!current_user_can('manage_options')) {
            wp_die('Insufficient permissions');
        }
        
        $operation = sanitize_text_field($_POST['operation']);
        $selected_menus = isset($_POST['selected_menus']) ? array_map('intval', $_POST['selected_menus']) : array();
        
        if ($operation === 'scan') {
            $result = $this->scan_menus($selected_menus);
        } elseif ($operation === 'clean') {
            $result = $this->clean_invalid_items($selected_menus);
        } else {
            wp_send_json_error('Invalid operation');
            return;
        }
        
        wp_send_json_success($result);
    }
    
    /**
     * Scan selected menus for invalid items
     */
    private function scan_menus($selected_menu_ids = array()) {
        $menus = wp_get_nav_menus();
        $invalid_items = array();
        $valid_count = 0;
        $scanned_menus = 0;
        
        foreach ($menus as $menu) {
            if (!empty($selected_menu_ids) && !in_array($menu->term_id, $selected_menu_ids)) {
                continue;
            }
            
            $scanned_menus++;
            $menu_items = wp_get_nav_menu_items($menu->term_id);
            
            if (!$menu_items) continue;
            
            foreach ($menu_items as $item) {
                $is_valid = $this->is_menu_item_valid($item);
                
                if ($is_valid['valid']) {
                    $valid_count++;
                } else {
                    $invalid_items[] = array(
                        'id' => $item->ID,
                        'title' => $item->title,
                        'menu_name' => $menu->name,
                        'menu_id' => $menu->term_id,
                        'reason' => $is_valid['reason']
                    );
                }
            }
        }
        
        return array(
            'invalid_items' => $invalid_items,
            'valid_count' => $valid_count,
            'scanned_menus' => $scanned_menus
        );
    }
    
    /**
     * Check if a menu item is valid
     */
    private function is_menu_item_valid($item) {
        switch ($item->type) {
            case 'post_type':
                if ($item->object_id) {
                    $post = get_post($item->object_id);
                    if (!$post || $post->post_status === 'trash') {
                        return array('valid' => false, 'reason' => 'Post/Page deleted or trashed');
                    }
                }
                break;
                
            case 'taxonomy':
                if ($item->object_id) {
                    $term = get_term($item->object_id, $item->object);
                    if (is_wp_error($term) || !$term) {
                        return array('valid' => false, 'reason' => 'Category/Tag deleted');
                    }
                }
                break;
                
            case 'custom':
                if (empty($item->url) || $item->url === '#') {
                    return array('valid' => false, 'reason' => 'Empty or invalid URL');
                }
                break;
        }
        
        if (empty($item->title)) {
            return array('valid' => false, 'reason' => 'Empty menu title');
        }
        
        return array('valid' => true, 'reason' => '');
    }
    
    /**
     * Remove invalid items from selected menus
     */
    private function clean_invalid_items($selected_menu_ids = array()) {
        $scan_result = $this->scan_menus($selected_menu_ids);
        $invalid_items = $scan_result['invalid_items'];
        $removed_count = 0;
        
        foreach ($invalid_items as $item) {
            if (wp_delete_post($item['id'], true)) {
                $removed_count++;
            }
        }
        
        return array('removed_count' => $removed_count);
    }
}

new MenuCleaner();
