<?php
abstract class SearchHelper
{
    private $_args;
    private $_results;

    function __construct($args) {
        $this->_args = $args;
        $this->_addQueryFilters();
    }

    public function execute() {
        if (empty($this->_args)) {
            return null;
        }

        $this->_results = new WP_Query($this->_args);

        // unregister the filters
        $this->_removeQueryFilters();

        return $this->_results;
    }

    private function _addQueryFilters() {
        add_filter('posts_where', array($this, 'searchWhere'));
        add_filter('posts_join', array($this, 'searchJoin'));
        add_filter('posts_groupby', array($this, 'searchGroupby'));
    }

    private function _removeQueryFilters() {
        remove_filter('posts_where', array($this, 'searchWhere'));
        remove_filter('posts_join', array($this, 'searchJoin'));
        remove_filter('posts_groupby', array($this, 'searchGroupby'));
    }

    abstract public function searchWhere($where);
    abstract public function searchJoin($join);
    abstract public function searchGroupby($groupby);
}