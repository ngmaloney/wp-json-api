<?php

class DateSearchAPI {
  var $from_date;
  var $to_date;
  var $date_field;
  var $post_type;

  const BETWEEN_QUERY = 'date_meta_between_query';
  const FROM_QUERY = 'date_meta_from_query';
  const TO_QUERY =  'date_meta_to_query';

  function __construct($params) {
    if(isset($params['from_date'])) {
      $this->from_date = $params['from_date'];
    }

    if(isset($params['to_date'])) {
      $this->to_date = $params['to_date'];
    }

    if(isset($params['date_field'])) {
      $this->date_field = $params['date_field'];
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
    if(!empty($this->from_date) && !empty($this->to_date) && !empty($this->date_field)) {
      return BETWEEN_QUERY;
    }
    elseif(!empty($this->from_date) && !empty($this->date_field)) {
      return FROM_QUERY;
    }
    elseif(!empty($this->to_date) && !empty($this->date_field)) {
      return TO_QUERY;
    }
    else {
      return false;
    }
  }

  private function meta_query($values, $operator) {
    $query = array(
      'post_type' => $this->post_type,
      'orderby' => $this->date_field,
      'order' => 'ASC',
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

  private function date_meta_from_query() {
    return $this->meta_query($this->from_date, '>=');
  }

  private function date_meta_to_query() {
    return $this->meta_query($this->to_date, '<=');
  }

  private function date_meta_between_query() {
    return $this->meta_query(array($this->from_date, $this->to_date), 'BETWEEN');
  }
}
