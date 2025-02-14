<?php
class My_Istymo_Admin {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, MYISTYMO_PLUGIN_URL . 'admin/css/my-istymo-admin.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name, MYISTYMO_PLUGIN_URL . 'admin/js/my-istymo-admin.js', array('jquery'), $this->version, false);
    }

    public function add_plugin_admin_menu() {
        add_menu_page(
            'My Istymo', 
            'My Istymo', 
            'manage_options', 
            $this->plugin_name, 
            array($this, 'display_plugin_admin_page'),
            'dashicons-admin-generic',
            20
        );

        add_submenu_page(
            $this->plugin_name,
            'DPE',
            'DPE',
            'manage_options',
            $this->plugin_name . '-dpe',
            array($this, 'display_dpe_admin_page')
        );

        add_submenu_page(
            $this->plugin_name,
            'SCI',
            'SCI',
            'manage_options',
            $this->plugin_name . '-sci',
            array($this, 'display_sci_admin_page')
        );
    }

    public function display_plugin_admin_page() {
        include_once MYISTYMO_PLUGIN_DIR . 'admin/partials/my-istymo-admin-display.php';
    }

    public function display_dpe_admin_page() {
        include_once MYISTYMO_PLUGIN_DIR . 'admin/partials/my-istymo-dpe-display.php';
    }

    public function display_sci_admin_page() {
        include_once MYISTYMO_PLUGIN_DIR . 'admin/partials/my-istymo-sci-display.php';
    }
}