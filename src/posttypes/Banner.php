<?php

namespace MarketMentors\SimpleSlider\src\posttypes;

class Banner extends \MarketMentors\SimpleSlider\src\posttypes\PostType
{
  protected function __construct()
  {
    parent::__construct('banner', []);
  }

  public function public()
  {
    return true;
  }

  public function supports()
  {
    // Overrides default supports options in PostType.
    return array(
      'title',
      'editor',
      'revisions',
      'page-attributes',
    );
  }

  public function taxonomies()
  {
    // Overrides default taxonomies options in PostType.
    return array('post_tag');
  }

  public function injectSchema(array $sData = [])
  {
    return;
  }

  public function render($post)
  {
    if ($this->postTypeSlug !== $post->post_type) {
      return "<div>Post ID: {$post->ID} is not a post-type of {$this->postTypeSlug}, it is a {$post->post_type}!</div>";
    }

    return \MarketMentors\SimpleSlider\src\Blade::getInstance()->render(
      'posttype.Banner',
      [
        'post' => $post,
      ]
    );
  }

  /**
   * We will never need to render a group of Banners.
   */
  public function renderGroup($options)
  {
    return;
  }
}
