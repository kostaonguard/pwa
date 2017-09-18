<?php

namespace pwa;

/**
 * The main plugin class.
 */
class Plugin
{

    private $loader;
    private $plugin_slug;
    private $version;
    private $option_name;

    public function __construct() {
        $this->plugin_slug = Info::SLUG;
        $this->version     = Info::VERSION;
        $this->option_name = Info::OPTION_NAME;
        $this->load_dependencies();
        $this->define_admin_hooks();
        $this->define_frontend_hooks();
    }

    private function load_dependencies() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-loader.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-admin.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'frontend/class-frontend.php';
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-manifest.php';
        $this->loader = new Loader();
    }

    private function define_admin_hooks() {
        $plugin_admin = new Admin($this->plugin_slug, $this->version, $this->option_name);
        $plugin_manifest = new Manifest($this->plugin_slug, $this->version, $this->option_name);
        // do_action when option save create image size for Manifest

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'assets');
        $this->loader->add_action('admin_init', $plugin_admin, 'register_settings');
        $this->loader->add_action('admin_menu', $plugin_admin, 'add_menus');
    }

    private function define_frontend_hooks() {
        $plugin_frontend = new Frontend($this->plugin_slug, $this->version, $this->option_name);
        $this->loader->add_action('wp_enqueue_scripts', $plugin_frontend, 'assets');
        $this->loader->add_action('wp_footer', $plugin_frontend, 'render');
        $this->loader->add_action('wp_head', $plugin_frontend,'meta');
    }

    public function run() {
        $this->loader->run();
    }
}
