<?php

namespace MarketMentors\SimpleBanner\src\posttypes;

interface IPostType
{
  public function registerPostType();
  public function registerMetaBoxes();
  public function register();
  public function formattedName();

  public function options();
  public function public();
  public function supports();
  public function taxonomies();

  public function injectSchema(array $sData);

  /**
   * These should only be called from within wp_query,
   * as they require a $post.
   */
  public function metaData(object $post);
  public function render(object $post);
  public function renderGroup(array $options);
}
