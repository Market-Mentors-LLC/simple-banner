<?php

declare(strict_types=1);

namespace MarketMentors\SimpleBanner\test;

use \PHPUnit\Framework\TestCase;
use \Brain\Monkey\Functions;

define('ABSPATH', dirname(__FILE__) . '/');

final class BannerTest extends TestCase
{

  public function testCanBeInstantiated(): void
  {
    Functions\when('wp_timezone_string')->justReturn('America/New_York');
    Functions\when('get_theme_mod')->justReturn('false');

    $banner = \MarketMentors\SimpleBanner\src\Banner::getInstance();

    $this->assertInstanceOf(
      \MarketMentors\SimpleBanner\src\ASingleton::class,
      $banner
    );
    $this->assertInstanceOf(
      \MarketMentors\SimpleBanner\src\Banner::class,
      $banner
    );
  }

  public function testCanBeEnabled(): void
  {
    Functions\when('wp_timezone_string')->justReturn('America/New_York');
    Functions\when('get_theme_mod')->justReturn('false');
    $banner = \MarketMentors\SimpleBanner\src\Banner::getInstance();

    $this->assertEquals(
      $banner->enabled,
      false
    );

    Functions\when('set_theme_mod')->justReturn('true');
    $banner->setEnabled(true);

    $this->assertEquals(
      $banner->enabled,
      true
    );

    $banner->setEnabled(false);
  }

  public function testLocalDateTime(): void
  {
    Functions\when('wp_timezone_string')->justReturn('America/New_York');
    Functions\when('get_theme_mod')->justReturn('false');
    $banner = \MarketMentors\SimpleBanner\src\Banner::getInstance();

    $this->assertEquals(
      (new \DateTime(
        'NOW',
        new \DateTimeZone(\wp_timezone_string())
      ))->getTimestamp(),
      $banner->localDateTime()
    );

    $this->assertEquals(
      (new \DateTime(
        '2022-03-07T12:14',
        new \DateTimeZone(\wp_timezone_string())
      ))->getTimestamp(),
      $banner->localDateTime('2022-03-07T12:14')
    );

    // Perhaps needs more instances of bad strings.
    $filePath = realpath('./test/mockData/') . '/badDateTimes.txt';
    // Read in the test data
    $dtStrings = \MarketMentors\SimpleBanner\src\Utilities::fReadLines($filePath);

    if (empty($dtStrings)) throw new \Exception(
      'Failed to read test data.'
    );

    // Ensure proper handling of malformed datetime strings.
    foreach ($dtStrings as $dt) {
      $this->assertFalse(
        $banner->localDateTime($dt),
      );
    }
  }

  public function testMaybeDisable()
  {
    Functions\when('\wp_timezone_string')->justReturn('America/New_York');
    Functions\when('\get_theme_mod')->alias(function ($slug) {
      switch ($slug) {
        case 'header-banner-enabled':
          return 'true';
          break;
        case 'header-banner':
          return 'test test test';
          break;
        case 'header-banner-expire-date';
          return '2022-03-07T12:14';
          break;
        default:
          break;
      }
      return null;
    });
    Functions\when('\set_theme_mod')->justReturn();

    $banner = \MarketMentors\SimpleBanner\src\Banner::getInstance();

    $this->assertFalse($banner->enabled);
    $this->assertEquals(
      $banner->bannerContent,
      'false'
    );

    $banner->setEnabled(true);
    $banner->setBannerContent('tacos tacos tacos');
    $banner->setExpireDatetime('2022-03-07T12:14');

    $this->assertTrue($banner->enabled);
    $this->assertEquals(
      $banner->expireDatetime,
      '2022-03-07T12:14'
    );
    $this->assertEquals(
      $banner->bannerContent,
      'tacos tacos tacos'
    );

    $result = $banner->maybeDisable();

    $this->assertTrue($result);
    $this->assertFalse($banner->enabled);
    $this->assertFalse($banner->expireDatetime);
    $this->assertEquals(
      $banner->bannerContent,
      'tacos tacos tacos'
    );
  }
}
