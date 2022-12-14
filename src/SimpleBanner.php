<?php

namespace MarketMentors\SimpleBanner\src;

if (!defined('ABSPATH')) exit;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://marketmentors.com/
 * @since      0.1.0
 *
 * @package    Market_Mentors_Simple_Banner
 * @subpackage Market_Mentors_Simple_Banner/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.1.0
 * @package    Market_Mentors_Simple_Banner
 * @subpackage Market_Mentors_Simple_Banner/includes
 * @author     Tyler Seabury <tylerseabury@protonmail.com>
 */
class SimpleBanner
{

  /**
   * The loader that's responsible for maintaining and registering all hooks that power
   * the plugin.
   *
   * @since    0.1.0
   * @access   protected
   * @var      Market_Mentors_Simple_Banner_Loader    $loader    Maintains and registers all hooks for the plugin.
   */
  protected $loader;

  /**
   * The unique identifier of this plugin.
   *
   * @since    0.1.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The current version of the plugin.
   *
   * @since    0.1.0
   * @access   protected
   * @var      string    $version    The current version of the plugin.
   */
  protected $version;

  /**
   * Define the core functionality of the plugin.
   *
   * Set the plugin name and the plugin version that can be used throughout the plugin.
   * Load the dependencies, define the locale, and set the hooks for the admin area and
   * the public-facing side of the site.
   *
   * @since    0.1.0
   */
  public function __construct()
  {
    if (defined('MARKET_MENTORS_SIMPLE_BANNER_VERSION')) {
      $this->version = MARKET_MENTORS_SIMPLE_BANNER_VERSION;
    } else {
      $this->version = '0.1.0';
    }
    $this->plugin_name = 'market-mentors-simple-banner';

    $this->load_dependencies();
    $this->set_locale();
    $this->define_admin_hooks();
    $this->define_public_hooks();
  }

  /**
   * Load the required dependencies for this plugin.
   *
   * Include the following files that make up the plugin:
   *
   * - Market_Mentors_Simple_Banner_Loader. Orchestrates the hooks of the plugin.
   * - Market_Mentors_Simple_Banner_i18n. Defines internationalization functionality.
   * - Market_Mentors_Simple_Banner_Admin. Defines all hooks for the admin area.
   * - Market_Mentors_Simple_Banner_Public. Defines all hooks for the public side of the site.
   *
   * Create an instance of the loader which will be used to register the hooks
   * with WordPress.
   *
   * @since    0.1.0
   * @access   private
   */
  private function load_dependencies()
  {
    /**
     * The class responsible for orchestrating the actions and filters of the
     * core plugin.
     */
    $this->loader = new \MarketMentors\SimpleBanner\src\Loader();
  }

  /**
   * Define the locale for this plugin for internationalization.
   *
   * Uses the Market_Mentors_Simple_Banner_i18n class in order to set the domain and to register the hook
   * with WordPress.
   *
   * @since    0.1.0
   * @access   private
   */
  private function set_locale()
  {

    /**
     * The class responsible for defining internationalization functionality
     * of the plugin.
     */
    $plugin_i18n = new \MarketMentors\SimpleBanner\src\I18n();

    $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
  }

  /**
   * Register all of the hooks related to the admin area functionality
   * of the plugin.
   *
   * @since    0.1.0
   * @access   private
   */
  private function define_admin_hooks()
  {

    /**
     * The class responsible for defining all actions that occur in the admin area.
     */
    $plugin_admin = new \MarketMentors\SimpleBanner\src\admin\AdminAssets($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
    $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
  }

  /**
   * Register all of the hooks related to the public-facing functionality
   * of the plugin.
   *
   * @since    0.1.0
   * @access   private
   */
  private function define_public_hooks()
  {

    /**
     * The class responsible for defining all actions that occur in the public-facing
     * side of the site.
     */
    $plugin_public = new \MarketMentors\SimpleBanner\src\frontend\FrontendAssets($this->get_plugin_name(), $this->get_version());

    $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
    $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
  }

  /**
   * Run the loader to execute all of the hooks with WordPress.
   *
   * @since    0.1.0
   */
  public function run()
  {
    $updateChecker = \Puc_v4_Factory::buildUpdateChecker(
      'https://github.com/Market-Mentors-LLC/simple-banner.git',
      MARKET_MENTORS_SIMPLE_SLIDER_FILE_PATH,
      'market-mentors-simple-banner',
      4 // Check for updates every four hours.
    );
    $updateChecker->setBranch('master');

    $this->loader->run();

    // Basically just instantiating and setting up Blade here.
    $blade = \MarketMentors\SimpleBanner\src\Blade::getInstance();
    $blade->addViewPath(realpath(__DIR__ . '/frontend/views'));
    $blade->addViewPath(realpath(__DIR__ . '/admin/views'));

    \MarketMentors\SimpleBanner\src\posttypes\Banner::getInstance()->register();

    add_action('init', [
      \MarketMentors\SimpleBanner\src\ContentFilter::class,
      'startOutputBuffer'
    ], 1);
    add_action('template_redirect', [
      \MarketMentors\SimpleBanner\src\ContentFilter::class,
      'startOutputBuffer'
    ], 10000);
  }

  /**
   * The name of the plugin used to uniquely identify it within the context of
   * WordPress and to define internationalization functionality.
   *
   * @since     0.1.0
   * @return    string    The name of the plugin.
   */
  public function get_plugin_name()
  {
    return $this->plugin_name;
  }

  /**
   * The reference to the class that orchestrates the hooks with the plugin.
   *
   * @since     0.1.0
   * @return    Market_Mentors_Simple_Banner_Loader    Orchestrates the hooks of the plugin.
   */
  public function get_loader()
  {
    return $this->loader;
  }

  /**
   * Retrieve the version number of the plugin.
   *
   * @since     0.1.0
   * @return    string    The version number of the plugin.
   */
  public function get_version()
  {
    return $this->version;
  }
}
