<?php

namespace MarketMentors\SimpleBanner\src\posttypes;

class PostType
extends \MarketMentors\SimpleBanner\src\ASingleton
implements \MarketMentors\SimpleBanner\src\posttypes\IPostType
{

  protected $postTypeSlug;

  protected $metaBoxes;

  protected function __construct(string $slug = 'general_post_type', array $metaBoxes = array())
  {
    $this->postTypeSlug = $slug;
    $this->metaBoxes = $metaBoxes;
  }

  public function register()
  {
    add_action(
      'init',
      array($this, 'registerPostType')
    );
    $this->registerMetaBoxes();
  }

  public function registerPostType()
  {
    register_post_type(
      $this->postTypeSlug,
      $this->options()
    );
  }

  public function options()
  {
    $name = $this->formattedName();
    return array(
      'label' => "{$name}s",
      'labels' => array(
        'name' => "{$name}s",
        'singular_name' => "$name",
        'add_new' => "Add New $name",
        'add_new_item' => "Add a new post of type $name",
        'edit_item' => "Edit $name",
        'new_item' => "New $name",
        'view_item' => "View $name",
        'search_items' => "Search {$name}s",
        'not_found' =>  "No {$name}s found",
        'not_found_in_trash' => "No {$name}s currently trashed",
        'parent_item_colon' => ''
      ),
      'description' => "{$name}s.",
      'public' => $this->public(),
      'hierarchical' => true,
      'exclude_from_search' => true,
      'publicly_queryable' => true,
      'show_ui' => true,
      'show_in_menu' => true,
      'show_in_nav_menus' => true,
      'show_in_admin_bar' => true,
      'show_in_rest' => true,
      'rest_base' => null,
      'rest_controller_class' => null,
      'menu_position' => null,
      'menu_icon' => null,
      'map_meta_cap' => true,
      'supports' => $this->supports(),
      'taxonomies' => $this->taxonomies(),
      'has_archive' => true,
    );
  }

  public function public()
  {
    return true;
  }

  public function supports()
  {
    return array(
      'title',
      'editor',
      'revisions',
      'page-attributes',
      'thumbnail',
      'author',
      'excerpt'
    );
  }

  public function taxonomies()
  {
    return array(
      'category',
      'post_tag'
    );
  }

  public function registerMetaBoxes()
  {
    foreach ($this->metaBoxes as $metaBox) {
      $metaBox->register($this->postTypeSlug);
    }
  }

  public function metaData(object $post)
  {
    $data = array();
    foreach ($this->metaBoxes as $metaBox) {
      $data[$metaBox->id()] = $metaBox->data($post);
    }
    return $data;
  }

  public function injectSchema(array $sData = [
    "@id" => "",
    "name" => "",
    "sku" => "",
    "brand" => ""
  ])
  {
    return;
  }

  public function render(object $post)
  {
    return '<p>Not implemented.</p> ' . __FILE__;
  }

  public function renderGroup(array $options)
  {
    return '<p>Not implemented.</p> ' . __FILE__;
  }

  public function formattedName()
  {
    return ucwords(str_replace(array('_', '-'), ' ', $this->postTypeSlug));
  }
}
