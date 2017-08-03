<?php
/*
Plugin Name: Manage Edition Pdfs
Plugin URI: http://ifoundmydoctor.com
Description: Lists all edition pdf's and allows you to delete them.
Author: Will Johnson
Version: 1.0
Author URI: http://ifoundmydoctor.com
*/


add_action('admin_menu', 'manage_edition_pdfs_menu');

function manage_edition_pdfs_menu () {
    add_options_page('Manage Edition Pdfs', 'Manage Edition Pdfs', 'manage_options', 'manage-edition-pdfs', 'manage_edition_pdfs_options');
}

function get_pdf_data () {
  $pdfs = get_posts(array(
      'post_type' => 'edition-files',
      'numberposts' => -1
  ));

  $name_to_pdf = array();
  foreach($pdfs as $pdf) {
    $pdf_link = get_post_meta($pdf->ID, '_edition_pdf', true);
    $pdf_name = substr($pdf_link, 13);

    $name_to_pdf[$pdf_name] = (object) array(
      'post_title' => $pdf->post_title,
      'link' => $pdf_link
    );
  }

  // Get all edition pdf files
  $edition_pdfs = array();
  if ($handle = opendir('../wp-content/uploads/_edition_pdf/')) {
    while (false !== ($entry = readdir($handle))) {
      if ($entry != '.' && $entry != '..') {
        $edition_pdfs[] = $entry;
      }
    }
  }

  $zombie_pdfs = array_map(function ($pdf) {
    return substr($pdf, 0, stripos($pdf, '.pdf'));
  }, array_diff($edition_pdfs, array_keys($name_to_pdf)));

  return array($name_to_pdf, $zombie_pdfs);
}

function manage_edition_pdfs_options () {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    list($name_to_pdf, $zombie_pdfs) = get_pdf_data();
    $pdfs_to_delete = array_intersect(array_keys($_POST), $zombie_pdfs);
    if ($pdfs_to_delete) {
      array_map(function ($pdf) {
        $path = "../wp-content/uploads/_edition_pdf/${pdf}.pdf";
        echo "<div>Deleted ${path}</div>";
        unlink($path);
      }, $pdfs_to_delete);

      list($name_to_pdf, $zombie_pdfs) = get_pdf_data();
    }

    $template = <<<HTML
<form name="manage-edition-pdfs" method="post" action="">
    <fieldset class="well">
        <div class="form-group">
HTML;

    $template .= '<h4>All Registered Editions</h4>';
    foreach($name_to_pdf as $name => $pdf) {
      $template .= $pdf->post_title . ': ' . $pdf->link . '<br />';
    }

    $template .= '<br />';

    $template .= '<h4>Zombie PDF Files NOT Associated with any Registered Edition</h4>';
    $template .= '<div class="row"><div class="col-lg-3">';
    foreach($zombie_pdfs as $zombie) {
      $template .= '<div class="input-group">';
      $template .= '<span class="input-group-addon">';
      $template .= "<input type='checkbox' name='${zombie}' value='${zombie}'>";
      $template .= '</span>';
      $template .= "<span class='input-group-addon'>wp-content/uploads/_edition_pdf/${zombie}.pdf</span>";
      $template .= '</div>';
    }
    $template .= '</div></div>';

    $template .= <<<HTML
        </div>
        <br />
        <button type="submit" class="btn btn-primary" id="delete" name="delete">Delete Selected Zombie PDF's</button>
    </fieldset>
</form>
HTML;

  echo $template;
}
