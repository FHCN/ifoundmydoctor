<?php
include_once('search-helper.php');

class PageSearchHelper extends SearchHelper
{
    function __construct($args) {
        parent::__construct($args);
    }

    public function searchWhere($where){
        global $wpdb;
        $keywords = split_keywords(get_search_query());
        
        if (is_search())
          foreach ($keywords as $entry => $keyword) {
            $keyword_regexp = implode('.*', $keyword);
            $where .= "AND ({$wpdb->posts}.post_title REGEXP '" . $keyword_regexp . "' OR {$wpdb->posts}.post_content REGEXP '" . $keyword_regexp . "')";
          }

        return $where;
    }

    public function searchJoin($join){

    }

    public function searchGroupby($groupby){
        global $wpdb;

        // we need to group on post ID
        $groupby_id = "{$wpdb->posts}.ID";
        if(!is_search() || strpos($groupby, $groupby_id) !== false) return $groupby;

        // groupby was empty, use ours
        if(!strlen(trim($groupby))) return $groupby_id;

        // wasn't empty, append ours
        return $groupby.", ".$groupby_id;
    }
}
