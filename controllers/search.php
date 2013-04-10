<?php

require_once 'search/search_api.php';
require_once 'search/date_search_api.php';

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
   * Performs a faceted search on several parameters.
   * Replaces several of the previous search types
   * Params:
   * category_id
   * category_slug
   * tags
   * tag_id
   * post_type
  */
  public function param_search() {
    global $json_api;
    $args = array(
     'category_id' => $json_api->query->category_id,
     'category_slug' => $json_api->query->category_slug,
     'post_type' => $json_api->query->post_type,
     'tags' => $json_api->query->tags,
     'tag_id' => $json_api->query->tag_id,
    );
    $search = new SearchAPI($args);
    return array(
      'posts' => $search->execute(),
    );
  }
}
