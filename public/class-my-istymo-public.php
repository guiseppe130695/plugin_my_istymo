<?php
class My_Istymo_Public {
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version) {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles() {
        wp_enqueue_style($this->plugin_name, MYISTYMO_PLUGIN_URL . 'public/css/my-istymo-public.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts() {
        wp_enqueue_script($this->plugin_name . '-dpe', MYISTYMO_PLUGIN_URL . 'public/js/my-istymo-dpe.js', array('jquery'), $this->version, true);
        wp_enqueue_script($this->plugin_name . '-sci', MYISTYMO_PLUGIN_URL . 'public/js/my-istymo-sci.js', array('jquery'), $this->version, true);
        
        wp_localize_script($this->plugin_name . '-dpe', 'myIstymoAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('my_istymo_nonce')
        ));
        
        wp_localize_script($this->plugin_name . '-sci', 'myIstymoAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('my_istymo_nonce')
        ));
    }
}