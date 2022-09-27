<?php

namespace MarketMentors\SimpleSlider\src;

if (!defined('ABSPATH')) {
  exit;
}

class Utilities
{
  public static function fReadLines(string $filename): array
  {
    $contents = [];
    if ($file = fopen($filename, "r")) {
      while (!feof($file)) {
        $line = fgets($file);
        if (!empty($line)) {
          $contents[] = $line;
        }
      }
      fclose($file);
    }
    return $contents;
  }
}
