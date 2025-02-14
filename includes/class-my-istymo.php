<?php
class My_Istymo {
    protected $loader;
    protected $plugin_name;
    protected $version;

    public function __construct() {
        $this->plugin_name = 'my-istymo';
        $this->version = MYISTYMO_VERSION;
        
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    private function load_dependencies() {
        $this->loader = new My_Istymo_Loader();
    }

    private function define_admin_hooks() {
        $plugin_admin = new My_Istymo_Admin($this->get_plugin_name(), $this->get_version());
        
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_plugin_admin_menu');
    }

    private function define_public_hooks() {
        $plugin_public = new My_Istymo_Public($this->get_plugin_name(), $this->get_version());
        
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        
        // Initialize features
        $dpe_feature = new My_Istymo_DPE($this->get_plugin_name(), $this->get_version());
        $sci_feature = new My_Istymo_SCI($this->get_plugin_name(), $this->get_version());
        
        // Add shortcodes
        add_shortcode('my_istymo_dpe', array($dpe_feature, 'display_dpe_list'));
        add_shortcode('my_istymo_sci', array($sci_feature, 'display_sci_list'));
    }

    public function run() {
        $this->loader->run();
    }

    public function get_plugin_name() {
        return $this->plugin_name;
    }

    public function get_version() {
        return $this->version;
    }
}