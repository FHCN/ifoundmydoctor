<?php $doctorAuthor = get_post_meta(get_the_ID(), '_doctor_author', true);
$theAuthor = get_the_author();

if ($theAuthor !== 'admin' || !empty($doctorAuthor)) {
    echo '<h4>Authors:</h4>';
}

if (!empty($doctorAuthor)) {
    $authorPost = get_post($doctorAuthor);
    echo 'Author: <a href="' . $authorPost->guid . '">' . $authorPost->post_title . '</a>';
}

if ($theAuthor !== 'admin') {
    $theAuthorInfo = get_the_author_link();
    echo $theAuthorInfo;
}
?>
