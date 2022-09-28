<?php

namespace MarketMentors\SimpleBanner\src;

if (!defined('ABSPATH')) exit;

class Banner extends \MarketMentors\SimpleBanner\src\ASingleton
{

  protected function __construct()
  {
    $this->bannerEnabledSlug = 'header-banner-enabled';
    $this->bannerSlug = 'header-banner';
    $this->expireDatetimeSlug = 'header-banner-expire-date';
    $this->enabled = $this->getEnabled();
    $this->bannerContent = $this->getBannerContent();
    $this->expireDatetime = $this->getExpireDateTime();
    $this->maybeDisable();
  }

  public function getEnabled(): bool
  {
    return filter_var(
      get_theme_mod($this->bannerEnabledSlug),
      FILTER_VALIDATE_BOOLEAN
    );
  }
  public function setEnabled(bool $state): bool
  {
    try {
      set_theme_mod(
        $this->bannerEnabledSlug,
        $state ? 'true' : 'false'
      );
      $this->enabled = $state;
    } catch (\Throwable $th) {
      return false;
    }
    return true;
  }

  public function getBannerContent(): string
  {
    return get_theme_mod($this->bannerSlug);
  }
  public function setBannerContent(string $val): bool
  {
    try {
      set_theme_mod($this->bannerSlug, $val);
      $this->bannerContent = $val;
    } catch (\Throwable $th) {
      return false;
    }
    return true;
  }

  public function getExpireDatetime()
  {
    return get_theme_mod($this->expireDatetimeSlug);
  }
  public function setExpireDatetime($dateTime): bool
  {
    try {
      set_theme_mod($this->expireDatetimeSlug, $dateTime);
      $this->expireDatetime = $dateTime;
    } catch (\Throwable $th) {
      return false;
    }
    return true;
  }

  /**
   * Localizes the datetime string to the local timezone.
   * @return int Unix timestamp or false
   */
  public function localDateTime(string $dateTime = null)
  {
    if (empty($dateTime)) return (new \DateTime(
      'NOW',
      new \DateTimeZone(\wp_timezone_string())
    ))->getTimestamp();
    try {
      $dateTime = (new \DateTime(
        $dateTime,
        new \DateTimeZone(\wp_timezone_string())
      ))->getTimestamp();
    } catch (\Throwable $th) {
      // The datetime string was probably invalid.
      // Lets just return false, as it is the value used by wordpress for unset datetime settings.
      return false;
    }
    return $dateTime;
  }

  public function maybeDisable(): bool
  {
    if (empty($this->expireDatetime)) return false;

    $datetimeNow = $this->localDateTime();
    $expireDatetime = $this->localDateTime($this->expireDatetime);

    if ($datetimeNow > $expireDatetime) {
      $this->setEnabled(false);
      $this->setExpireDatetime(false);
    }
    return true;
  }

  public function registerSettings($wp_customize)
  {
    /*
        * Header Options
        */
    $wp_customize->add_panel(
      'cornerstone_bank_header_options',
      [
        'priority' => 100,
        'title' => 'Header Options',
        'description' => 'Theme header fields and options.',
      ]
    );

    /*
        * Header Banner
        */
    $wp_customize->add_section(
      'header-banner-section',
      [
        'title' => 'Header Banner',
        'priority' => 99,
        'panel' => 'cornerstone_bank_header_options',
      ]
    );

    $wp_customize->add_setting(
      $this->bannerEnabledSlug,
      [
        'default' => 'true'
      ]
    );
    $wp_customize->add_control(new \WP_Customize_Control(
      $wp_customize,
      $this->bannerEnabledSlug,
      [
        'label' => 'Banner Enabled?',
        'section' => 'header-banner-section',
        'type' => 'select',
        'choices' => [
          'true' => 'Yes',
          'false' => 'No'
        ]
      ]
    ));

    /**
     * @TODO Replace this with the posttype content!
     */
    // $wp_customize->add_setting($this->bannerSlug);
    // $wp_customize->add_control(new \Text_Editor_Custom_Control(
    //   $wp_customize,
    //   $this->bannerSlug,
    //   [
    //     'label' => 'Header Banner',
    //     'section' => 'header-banner-section',
    //     'type' => 'textarea',
    //     'description' => "Enter your banner content here."
    //   ]
    // ));

    $wp_customize->add_setting($this->expireDatetimeSlug);
    $wp_customize->add_control(new \WP_Customize_Control(
      $wp_customize,
      $this->expireDatetimeSlug,
      [
        'label' => 'Expiration Date and Time',
        'section' => 'header-banner-section',
        'type' => 'datetime-local',
        'description' => 'Enter the date and time this banner will automatically expire, or leave this field empty to disable expiration.'
      ]
    ));
  }

  public function render()
  {
    if ($this->enabled && !empty($this->bannerContent)) {
      return \MarketMentors\SimpleBanner\src\Blade::getInstance()->render(
        'Banner',
        [
          "bannerContent" => $this->getBannerContent()
        ]
      );
    }

    return "";
  }
}
