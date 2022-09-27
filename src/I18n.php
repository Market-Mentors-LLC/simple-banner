<?php

namespace MarketMentors\SimpleSlider\src;

if (!defined('ABSPATH')) exit;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://marketmentors.com/
 * @since      1.0.0
 *
 * @package    Market_Mentors_Simple_Banner
 * @subpackage Market_Mentors_Simple_Banner/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Market_Mentors_Simple_Banner
 * @subpackage Market_Mentors_Simple_Banner/includes
 * @author     Tyler Seabury <tylerseabury@protonmail.com>
 */
class i18n
{


  /**
   * Load the plugin text domain for translation.
   *
   * @since    1.0.0
   */
  public function load_plugin_textdomain()
  {

    load_plugin_textdomain(
      'market-mentors-simple-banner',
      false,
      dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
    );
  }
}
