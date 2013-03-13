<?php

class TagSearchAPI {
  var $tags;
  var $tag_id;
  var $post_type;

  const TAGS_SEARCH = 'tags_search';
  const TAG_ID_SEARCH = 'tag_id_search';

  function __construct($params) {
    if(isset($params['tags'])) {
      $this->tags = explode(',',$params['tags']);
    }

    if(isset($params['tag_id'])) {
      $this->tag_id = $params['tag_id'];
    }

    if(isset($params['post_type'])) {
      $this->post_type = $params['post_type'];
    }
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

  private function query_type() {
    if(!empty($this->tags)) {
      return self::TAGS_SEARCH;
    }
    elseif(!empty($this->tag_id)) {
      return self::TAG_ID_SEARCH;
    }
  }

  private function tags_search() {
    $query = array(
      'tag_slug__in' => $this->tags,
      'post_type' => $this->post_type,
    );
    return $query;
  }

  private function tag_id_search() {
    $query = array(
      'tag_id' => $this->tag_id,
      'post_type' => $this->post_type
    );
    return $query;
  }
}

