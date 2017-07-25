<?php 

get_header(); 
$current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
$all_date_terms = get_terms('date_published', 'hide_empty=0&orderby=name&order=DESC&parent=0');

$is_first_column = TRUE;

?>
<div id="main" class="subpage">
	<div class="main-i">
		<div class="page-title">
			<?php echo apply_filters( 'the_title', $current_term->name ); ?>
		</div><!-- /.page-title -->
		<div id="content">
			<?php ifd_print_the_breadcrumb('edition'); ?>

			<div class="individual-edition editions">
			<?php
            foreach ($all_date_terms as $main_term) {

                $all_subterms = get_terms('date_published', 'hide_empty=0&orderby=name&child_of=' . $main_term->term_id);
                if (!empty($all_subterms) && ($main_term->name + 0) >= 2015) {
                    echo '<div class="col">';
                    echo '<h3>' . $main_term->name . '</h3><ul>';

                    foreach ($all_subterms as $sub_term) {
                        $old_url = get_term_link($sub_term);
                        $new_url = add_query_arg('edition_id', $current_term->slug, $old_url);

                        $pdf = get_posts(array(
                            'post_type' => 'edition-files',
                            'tax_query' => array(
                                'relation' => 'AND',
                                array(
                                    'taxonomy' => 'edition',
                                    'field' => 'id',
                                    'terms' => array($current_term->term_id)
                                ),
                                array(
                                    'taxonomy' => 'date_published',
                                    'field' => 'id',
                                    'terms' => array($sub_term->term_id)
                                )
                            ),
                            'numberposts' => 1
                        ));

                        $pdf_link = '';
                        if (!empty($pdf)) {
                            $pdf_link = get_post_meta($pdf['0']->ID, '_edition_pdf', true);
                            if (!empty($pdf_link)) {
                                $pdf_link = get_upload_url() . '/' . $pdf_link;
                                $pdf_link = '<a href="' . $pdf_link . '" target="_blank" class="pdf-link">Download</a>';
                            }
                        }

                        echo '<li><span><a href="' . $new_url . '">' . $sub_term->name . '</a>' . $pdf_link . '</span></li>';
                    }

                    echo '</ul></div>';
                }
            }
			?>
				<div class="cl">&nbsp;</div>
			</div><!-- /individual-eidion -->

			<ul class="widgets">
				<li class="widget-wide-ad">
					<?php echo get_option('ifc_google_ads'); ?>
				</li>
			</ul>
		</div><!-- /#content -->
		<?php get_sidebar(); ?>
		<div class="cl">&nbsp;</div>
	</div><!-- /.main-i -->
</div><!-- /#main -->

<?php get_footer(); ?>