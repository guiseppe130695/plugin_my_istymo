<?php
/**
 * Plugin Name: My Istymo
 * Plugin URI: https://myistymo.com
 * Description: Plugin officiel My Istymo intégrant les fonctionnalités DPE et SCI
 * Version: 1.0.0
 * Author: My Istymo
 * Author URI: https://myistymo.com
 * Text Domain: my-istymo
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('MYISTYMO_VERSION', '1.0.0');
define('MYISTYMO_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('MYISTYMO_PLUGIN_URL', plugin_dir_url(__FILE__));

// Activation and deactivation hooks
register_activation_hook(__FILE__, 'activate_my_istymo');
register_deactivation_hook(__FILE__, 'deactivate_my_istymo');

function activate_my_istymo() {
    // Activation tasks
}

function deactivate_my_istymo() {
    // Deactivation tasks
}

// Include the main plugin class
require_once MYISTYMO_PLUGIN_DIR . 'includes/class-my-istymo-loader.php';
require_once MYISTYMO_PLUGIN_DIR . 'includes/class-my-istymo.php';
require_once MYISTYMO_PLUGIN_DIR . 'admin/class-my-istymo-admin.php';
require_once MYISTYMO_PLUGIN_DIR . 'public/class-my-istymo-public.php';
require_once MYISTYMO_PLUGIN_DIR . 'includes/features/class-my-istymo-dpe.php';
require_once MYISTYMO_PLUGIN_DIR . 'includes/features/class-my-istymo-sci.php';

// Initialize the plugin
function run_my_istymo() {
    $plugin = new My_Istymo();
    $plugin->run();
}
run_my_istymo();