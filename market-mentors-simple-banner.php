<?php

namespace MarketMentors\SimpleSlider;

require __DIR__ . '/vendor/autoload.php';

if (!defined('ABSPATH')) exit;

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://marketmentors.com/
 * @since             0.1.0
 * @package           Market_Mentors_Simple_Banner
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Banner
 * Plugin URI:        https://github.com/Market-Mentors-LLC/simple-banner.git
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           0.2.0
 * Author:            Tyler Seabury
 * Author URI:        https://marketmentors.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       market-mentors-simple-banner
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
  die;
}

/**
 * The path to the main plugin file.
 */
define('MARKET_MENTORS_SIMPLE_SLIDER_FILE_PATH', __FILE__);

/**
 * Currently plugin version.
 * Start at version 0.1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('MARKET_MENTORS_SIMPLE_BANNER_VERSION', '0.2.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-market-mentors-simple-banner-activator.php
 */
register_activation_hook(
  __FILE__,
  [
    \MarketMentors\SimpleSlider\src\Activator::class,
    'activate'
  ]
);

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-market-mentors-simple-banner-deactivator.php
 */
register_deactivation_hook(
  __FILE__,
  [
    \MarketMentors\SimpleSlider\src\Deactivator::class,
    'deactivate'
  ]
);


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
$plugin = new \MarketMentors\SimpleSlider\src\SimpleBanner();

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
$plugin->run();
