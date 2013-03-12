<?php
/*
Controller name: Search
Controller description: PODS compatible search controller
 */
class JSON_API_Search_Controller {

  public function date_search() {
    global $json_api;
    $search_params = array(
      'from_date' => $json_api->query->from_date,
      'to_date' => $json_api->query->to_date,
      'date_field' => $json_api->query->date_field,
      'post_type' => $json_api->query->post_type,
    );
    $search = new DateSearchAPI($search_params);
    return array(
     'posts' => $search->execute_search(),
    );
  }

  public function type_search() {
    global $json_api;
    $args = array(
     'post_type' => $json_api->query->post_type,
    );

    return array(
      'posts' => $json_api->introspector->get_posts($args),
    );
  }

  //TODO: Faceted Taxonomy/Tag searches
}

//TODO: Move this to seperate file
class DateSearchAPI {
  var $from_date;
  var $to_date;
  var $date_field;
  var $post_type;

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

  function execute_search() {
    global $json_api;
    $query = null;
    $query_type = $this->query_type();
    if($query_type) {
      $query = call_user_func(array(get_class($this), $query_type));
    }
    return $json_api->introspector->get_posts($query);
  }

  private function query_type() {
    if(!empty($this->from_date) && !empty($this->to_date) && !empty($this->date_field)) {
      return 'date_meta_between_query';
    }
    elseif(!empty($this->from_date) && !empty($this->date_field)) {
      return 'date_meta_from_query';
    }
    elseif(!empty($this->to_date) && !empty($this->date_field)) {
      return 'date_meta_to_query';
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
