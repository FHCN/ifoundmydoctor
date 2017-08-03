<?php

include_once('lib/specialty-search-helper.php');
include_once('lib/page-search-helper.php');

const SPECIALTY = 'specialty';
const DOCTOR = 'doctor';
const PRACTICE = 'practice';
const ARTICLE = 'article';
const BIOGRAPHY = 'biography';
const EDITION = 'edition';
const PAGE = 'page';
const POST = 'post';
const PRACTICE = 'practice';

global $query_string;

$results = array();

$query_args = explode("&", $query_string);
$search_query = array();

$query_split = explode("=", $query_args[0]);
$searchTerm = urldecode($query_split[1]);

// The search should include the following:
// 1. Specialty Taxonomy
// 2. Doctors
$specialtyArgs = array(
    'post_type' => DOCTOR,
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => SPECIALTY,
            'field' => 'slug',
            'terms' => $searchTerm
        )
    )
);

$specialtySearchHelper = new SpecialtySearchHelper($specialtyArgs);
$specialtyResult = $specialtySearchHelper->execute();


$pageArgs = array(
    'post_type' => array(DOCTOR, PRACTICE, ARTICLE, BIOGRAPHY, EDITION, PAGE, POST, PRACTICE),
    'posts_per_page' => -1
);

$pageSearchHelper = new PageSearchHelper($pageArgs);
$pageResults = $pageSearchHelper->execute();

// Loop through the specialty results and print them to the page
$results[SPECIALTY] = array();
foreach ($specialtyResult->posts as $specialtyPost) {
    $results[SPECIALTY][] = generate_search_result_item($specialtyPost);
}

$sectionTitles = array(
    'doctor' => 'Doctors Section',
    'practice' => 'Practice Section',
    'article' => 'Article Section',
    'biography' => 'Biography Section',
    'edition' => 'Edition Section',
    'page' => 'Page Section',
    'post' => 'Post Section',
    'practice' => 'Practice Section'
);

foreach ($sectionTitles as $key => $title) {
    $results[$key] = array();
}

// Loop through the page results and display the results
foreach ($pageResults->posts as $pagePost) {
    $results[$pagePost->post_type][] = generate_search_result_item($pagePost);
}

// All of the data is now stored in the $results array. Store it into a data attribute where it will be used
// by javascript.
$dataGlobalSearchResults = htmlspecialchars(json_encode($results), ENT_QUOTES, 'UTF-8');
$resultsHtml = <<<HTML
<section id='globalSearchResults' data-global-search-results='{$dataGlobalSearchResults}'>
    <ul class='search-menu'></ul>
    <ul class='search-entries'></ul>
</section>
HTML;

echo $resultsHtml;
