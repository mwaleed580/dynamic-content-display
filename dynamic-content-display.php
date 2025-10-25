<?php
/**
 * Plugin Name: Dynamic Content Display
 * Plugin URI: https://github.com/mwaleed580/dynamic-content-display/
 * Description: Professional WordPress plugin for dynamic content management with URL-based tab ordering, ACF integration, and persistent user preferences via cookies.
 * Version: 1.2.0
 * Author: Muhammad Waleed
 * Author URI: https://www.linkedin.com/in/mwaleed580/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: dynamic-content-display
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.3
 * Requires PHP: 7.4
 * Network: false
 *
 * @package DynamicContentDisplay
 * @author Muhammad Waleed
 * @license GPL-2.0-or-later
 */

namespace DynamicContentDisplay;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit('Direct access denied.');
}

// Define plugin constants
if (!defined('DCD_VERSION')) {
    define('DCD_VERSION', '1.2.0');
}
if (!defined('DCD_PLUGIN_DIR')) {
    define('DCD_PLUGIN_DIR', plugin_dir_path(__FILE__));
}
if (!defined('DCD_PLUGIN_URL')) {
    define('DCD_PLUGIN_URL', plugin_dir_url(__FILE__));
}
if (!defined('DCD_PLUGIN_FILE')) {
    define('DCD_PLUGIN_FILE', __FILE__);
}

/**
 * Load Composer autoloader
 */
$autoloader = DCD_PLUGIN_DIR . 'vendor/autoload.php';
if (file_exists($autoloader)) {
    require_once $autoloader;
}

/**
 * Initialize the plugin
 */
function init_plugin(): void
{
    Core\Plugin::get_instance();
}

// Initialize plugin
add_action('plugins_loaded', __NAMESPACE__ . '\\init_plugin');

/**
 * Plugin activation
 */
function activate_plugin(): void
{
    flush_rewrite_rules();
}

/**
 * Plugin deactivation
 */
function deactivate_plugin(): void
{
    flush_rewrite_rules();
}

// Register hooks
register_activation_hook(__FILE__, __NAMESPACE__ . '\\activate_plugin');
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\\deactivate_plugin');
