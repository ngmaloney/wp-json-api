<?php

class SearchAPI {
  var $from_date;
  var $to_date;
  var $date_field;
  var $post_type;
  var $category_id;
  var $category_slug;
  var $tags;
  var $tag_id;
  var $order = 'DESC';
  var $orderby = 'post_date';

  const BETWEEN_QUERY = 'date_meta_between_query';
  const FROM_QUERY = 'date_meta_from_query';
  const TO_QUERY =  'date_meta_to_query';
  const DEFAULT_QUERY = 'default_query';

  function __construct($params) {
    foreach($params as $key => $val) {
      if(property_exists($this, $key)) {
        $this->{$key} = $val;
      }
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
    if(!empty($this->from_date) && !empty($this->to_date) && !empty($this->date_field)) {
      return self::BETWEEN_QUERY;
    }
    elseif(!empty($this->from_date) && !empty($this->date_field)) {
      return self::FROM_QUERY;
    }
    elseif(!empty($this->to_date) && !empty($this->date_field)) {
      return self::TO_QUERY;
    }
    else {
      return self::DEFAULT_QUERY;
    }
  }

  private function meta_params($values, $operator) {
    $query = array(
      'meta_key' => $this->date_field,
      'meta_query' => array(
        array(
          'key' => $this->date_field,
          'value' => $values,
          'compare' => $operator,
          'type' => 'DATE'
        ),
      ),
    );
    return $query;
  }

  private function default_params() {
    $query = array();

    if(!empty($this->date_field)) {
      $query['orderby'] = $this->date_field;
    }
    else {
      $query['orderby'] = $this->orderby;
    }

    $query['order'] = $this->order;

    if(!empty($this->post_type)) {
      $query['post_type'] = $this->post_type;
    }

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

    return $query;
  }

  private function date_meta_from_query() {
    return $this->default_params() + $this->meta_params($this->from_date, '>=');
  }

  private function date_meta_to_query() {
    return $this->default_params() +  $this->meta_params($this->to_date, '<=');
  }

  private function date_meta_between_query() {
    return $this->default_params() + $this->meta_params(array($this->from_date, $this->to_date), 'BETWEEN');
  }

  private function default_query() {
    return $this->default_params();
  }
}
