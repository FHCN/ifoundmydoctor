<?php
include_once ('search-helper.php');

class SpecialtySearchHelper extends SearchHelper {

    function __construct($args) {
        parent::__construct($args);
    }

    public function searchWhere($where){
        global $wpdb;
        $keywords = split_keywords(get_search_query());

        if (is_search())
          foreach ($keywords as $entry => $keyword) {
            $keyword_regexp = implode('.*', $keyword);
            $where .= "OR (t.name REGEXP '" . $keyword_regexp . "' AND {$wpdb->posts}.post_status = 'publish')";
          }

        return $where;
    }

    public function searchJoin($join){
        global $wpdb;
        if (is_search())
            $join .= "LEFT JOIN {$wpdb->term_relationships} tr ON {$wpdb->posts}.ID = tr.object_id INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id=tr.term_taxonomy_id INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id";
        return $join;
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
