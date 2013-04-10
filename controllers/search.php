<?php

require_once 'search/category_search_api.php';
require_once 'search/date_search_api.php';
require_once 'search/tag_search_api.php';

/*
Controller name: Search
Controller description: PODS compatible search controller
 */
class JSON_API_Search_Controller {

  /**
    * Performs a daterange search on a given content type
    * TODO: Add category/tag param?
    * TODO: If date field isn't specified search by created on
    * Params:
    * from_date - Date, date format YYYY-MM-DD
    * to_date - Date, date format YYYY-MM-DD
    * date_field - String, custom field to search by.
    * post_type - String, type of post to search by
  */
  public function date_search() {
    global $json_api;
    $args = array(
      'from_date' => $json_api->query->from_date,
      'to_date' => $json_api->query->to_date,
      'date_field' => $json_api->query->date_field,
      'post_type' => $json_api->query->post_type,
    );
    $search = new DateSearchAPI($args);
    return array(
     'posts' => $search->execute(),
    );
  }

  /**
    * Post Type Search
    * Params:
    * post_type: String representing post type
  */
  public function type_search() {
    global $json_api;
    $args = array(
     'post_type' => $json_api->query->post_type,
     'category_slug' => $json_api->query->category_slug,
    );

    return array(
      'posts' => $json_api->introspector->get_posts($args),
    );
  }

  /**
    * PODs compatible category search
    * Params:
    * category_id - Integer, Category id to search by
    * category_slug - String, Category slug to search by
    * post_type - String, type of post to search by
  */
  //TODO: Support multiple categories
  public function category_search() {
    global $json_api;
    $args = array(
     'category_id' => $json_api->query->category_id,
     'category_slug' => $json_api->query->category_slug,
     'post_type' => $json_api->query->post_type
    );

    $search = new CategorySearchAPI($args);
    return array(
      'posts' => $search->execute(),
    );
  }

  /**
    * PODs compatible tag search
    * Params:
    * tags - String, csv seperated list of tags
    * tag_id - Integer, Tag id to search by
    * post_type - String, type of post to search by
  */
  public function tag_search() {
    global $json_api;
    $args = array(
      'tags' => $json_api->query->tags,
      'tag_id' => $json_api->query->tag_id,
      'post_type' => $json_api->query->post_type
    );
    $search = new TagSearchAPI($args);
    return array(
     'posts' => $search->execute(),
    );
  }
}




