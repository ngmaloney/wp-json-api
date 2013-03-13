<?php

class CategorySearchAPI {
  var $category_id;
  var $category_slug;
  var $post_type;

  const CATEGORY_ID_SEARCH = 'category_id_search';
  const CATEGORY_SLUG_SEARCH = 'category_slug_search';

  function __construct($params) {
    if(isset($params['category_id'])) {
      $this->category_id = $params['category_id'];
    }

    if(isset($params['category_slug'])) {
      $this->category_slug = $params['category_slug'];
    }

    if(isset($params['post_type'])) {
      $this->post_type = $params['post_type'];
    }
    return $this;
  }

  function execute() {
    global $json_api;
    $query = [];
    $query_type = $this->query_type();
    if($query_type) {
      $query_args = call_user_func(array(get_class($this), $query_type));
      $query = $json_api->introspector->get_posts($query_args);
    }
    return $query;
  }

  private function category_id_search() {
    $query = array(
      'cat' => $this->category_id,
      'post_type' => $this->post_type,
    );
    return $query;
  }

  private function category_slug_search() {
    $query = array(
      'category_name' => $this->category_slug,
      'post_type' => $this->post_type,
    );
    return $query;
  }
  private function query_type() {
    if(!empty($this->category_id)) {
      return CATEGORY_ID_SEARCH;
    }
    elseif(!empty($this->category_slug)) {
      return CATEGORY_SLUG_SEARCH;
    }
  }
}

