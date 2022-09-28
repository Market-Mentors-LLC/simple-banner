<?php

namespace MarketMentors\SimpleBanner\src;

class ContentFilter
{

  public static function startOutputBuffer($bannerRenderer)
  {
    return function () use ($bannerRenderer) {
      if (!is_admin() || (function_exists("wp_doing_ajax") && wp_doing_ajax()) || (defined('DOING_AJAX') && DOING_AJAX)) {
        // note: "self::alterHtml" does for some reason not work on hhvm (#226)
        ob_start(function ($content) use ($bannerRenderer) {
          return static::alterHtml($content, $bannerRenderer());
        });
      }
    };
  }

  public static function alterHtml($content, $bannerHtml)
  {
    // Don't do anything with the RSS feed.
    if (is_feed()) {
      return $content;
    }

    if (is_admin()) {
      return $content;
    }

    // Exit if it doesn't look like HTML (see #228)
    if (!preg_match("#^\\s*<#", $content)) {
      return $content;
    }

    // Filter the content!
    return static::inserterFilter($content, $bannerHtml);
  }

  public static function inserterFilter($html, $theInsert)
  {
    if (class_exists('\\DOMDocument')) {
      $dom = new \DOMDocument();

      if (function_exists("mb_encode_numericentity")) {
        // I'm in doubt if I should add the following line (see #41)
        // $html = mb_convert_encoding($html, 'UTF-8');
        $html = mb_encode_numericentity($html, array(0x7f, 0xffff, 0, 0xffff));  // #41
      }

      @$dom->loadHTML($html);

      $header = $dom->getElementsByTagName('header')->item(0);

      $header->appendChild(new \DOMNode($theInsert));

      return $dom->saveHTML();
    } else {
      // Convert to UTF-8 because HtmlDomParser::str_get_html needs to be told the
      // encoding. As UTF-8 might conflict with the charset set in the meta, we must
      // encode all characters outside the ascii-range.
      $html = self::textToUTF8WithNonAsciiEncoded($html);
      $dom = \KubAT\PhpSimple\HtmlDomParser::str_get_html($html, false, false, 'UTF-8', false);
      if ($dom === false) {
        return $html;
      }

      $insertableNode =
        \KubAT\PhpSimple\HtmlDomParser::str_get_html($theInsert, false, false, 'UTF-8', false);
      if ($insertableNode === false) {
        return $html;
      }

      $header = $dom->find('header,HEADER', 0);
      $header->appendChild($insertableNode->root->nodes[0]);
      return $dom->root->text();
    }
  }

  /**
   *  Convert to UTF-8 and encode chars outside of ascii-range
   *
   *  Input: html that might be in any character encoding and might contain non-ascii characters
   *  Output: html in UTF-8 encding, where non-ascii characters are encoded
   *
   */
  private static function textToUTF8WithNonAsciiEncoded($html)
  {
    if (function_exists("mb_convert_encoding")) {
      $html = mb_convert_encoding($html, 'UTF-8');
      $html = mb_encode_numericentity($html, array(0x7f, 0xffff, 0, 0xffff), 'UTF-8');
    }
    return $html;
  }
}
