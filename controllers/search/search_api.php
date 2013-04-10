<?php

class SearchAPI {
  var $category_id;
  var $category_slug;
  var $tags;
  var $tag_id;
  var $post_type;

  function __construct($params) {
    foreach($params as $key => $val) {
      if(property_exists($this, $key)) {
        $this->{$key} = $val;
      }
    }
  }

  function execute() {
    global $json_api;
    return $json_api->introspector->get_posts($this->query_params());
  }

  private function query_params() {
    $query = array();
    if(!empty($this->category_id)) {
      $query['cat'] = $this->category_id;
    }
    if(!empty($this->category_slug)) {
      $query['category_name'] = $this->category_slug;
    }
    if(!empty($this->tags)) {
      $query['tag_slug__in'] = $this->tags;
    }
    if(!empty($this->tag_id)) {
      $query['tag_id'] = $this->tag_id;
    }
    if(!empty($this->post_type)) {
      $query['post_type'] = $this->post_type;
    }
    return $query;
  }
}
