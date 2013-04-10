<?php

require_once 'search/search_api.php';

/*
Controller name: Search
Controller description: PODS compatible search controller
 */
class JSON_API_Search_Controller {

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
     'date_field' => $json_api->query->date_field,
     'from_date' => $json_api->query->from_date,
     'to_date' => $json_api->query->to_date,
     'order' => $json_api->query->order,
     'orderby' => $json_api->query->orderby,
    );
    $search = new SearchAPI($args);
    return array(
      'posts' => $search->execute(),
    );
  }
}
