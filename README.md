WordPress Menu Cleaner Plugin
A lightweight WordPress plugin that helps maintain clean navigation menus by identifying and removing invalid menu items automatically.
Features

Smart Scanning: Automatically detects invalid menu items across all your WordPress navigation menus
Selective Cleaning: Choose which menus to scan and clean with an intuitive checkbox interface
Multiple Validation Types: Identifies various types of broken menu items:

Deleted or trashed posts/pages
Missing categories and tags
Empty or invalid custom URLs
Menu items with missing titles


Safe Operations: Scan first, then decide what to clean with clear confirmation prompts
Detailed Reporting: Shows exactly what was found and provides clear reasons for each invalid item
User-Friendly Interface: Clean, responsive admin interface integrated into WordPress Tools menu

Installation

Upload the plugin files to /wp-content/plugins/menu-cleaner/
Activate the plugin through the 'Plugins' screen in WordPress
Navigate to Tools > Menu Cleaner in your WordPress admin

Usage

Select Menus: Choose which navigation menus you want to scan
Scan: Click "Scan Selected Menus" to identify invalid items
Review: Check the detailed report of found issues
Clean: Remove invalid items with the "Clean Invalid Items" button

What Gets Cleaned

Broken Post/Page Links: Menu items pointing to deleted or trashed content
Missing Taxonomy Links: Categories or tags that no longer exist
Invalid Custom URLs: Empty URLs or placeholder links (#)
Empty Menu Items: Items without titles or proper configuration

Requirements

WordPress 4.0 or higher
PHP 5.6 or higher
Administrator privileges

Security Features

Nonce verification for all AJAX operations
Capability checks to ensure only administrators can use the tool
Sanitized input handling

Contributing
Contributions are welcome! Please feel free to submit a Pull Request.
License
This plugin is licensed under the GPL v2 or later.

Note: Always backup your website before making bulk changes to your menus. While this plugin is designed to be safe, it's always better to be cautious with automated cleanup tools.
