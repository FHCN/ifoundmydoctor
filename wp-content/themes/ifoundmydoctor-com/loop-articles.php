<?php 
global $year;
global $min_date;
global $max_date;
global $keywords;
global $months;

function ifd_filter_where_keywords($where = '') {
	global $keywords;
	$where .= " AND (";
	$where .= "post_content LIKE '%" . $keywords[0] . "%' OR post_title LIKE '%" . $keywords[0] . "%'";

	foreach ($keywords as $keyword) {
		$where .= " OR post_content LIKE '%" . trim($keyword) . "%' OR post_title LIKE '%" . trim($keyword) . "%'";
	}
	$where .= ')';

	return $where;
}
$selected_term = 0;
$selected_year = 0;
$selected_quarter = 0;

$post_request = FALSE;
if (($_SERVER['REQUEST_METHOD'] == 'GET') && (isset($_GET['ifd_action']))) {
	$post_request = TRUE;	
	//If Edition has been selected
	if (is_numeric($_GET['ifd_edition'])) {
		$selected_term = intval($_GET['ifd_edition']);
	}

	//If Year has been selected
	if (is_numeric($_GET['ifd_year'])) {
		$selected_year = intval($_GET['ifd_year']);
	}

	//If Qurtar has been selected
	if ($_GET['ifd_quarter'] != 'quarter') {
		$selected_quarter = filter_var($_GET['ifd_quarter'], FILTER_SANITIZE_STRING);
	}

}

$tax_args = array(
	'relation'	=>	'AND'
);

//The Final Query, rest queries are appended to it
$final_args = array(
						'post_type' => 'article',
						'relation'	=> 'AND'
			);
$final_args['post_status'] = array('publish', 'inherit');

if ($selected_year) {

	$tax_args[] = array(
		'taxonomy'	=>	'date_published',
		'field'		=>	'slug',
		'include_children' => false,
		'terms'		=>	array($selected_year)
	);

}

if ($selected_quarter) {

	$quarter_terms = array();

	if ($selected_year) {
		$term = get_term_by('slug', $selected_quarter . '-issue-' . $selected_year, 'date_published');
		if (!$term || is_wp_error($term)) {
			$term = get_term_by('slug', $selected_quarter . '-issue', 'date_published');
		}

		if ($term && !is_wp_error($term)) {
			$quarter_terms[] = $term->slug;
		}		
	}

	if ($quarter_terms) {
		$tax_args[] = array(
			'taxonomy'	=>	'date_published',
			'field'		=>	'slug',
			'terms'		=>	$quarter_terms,
			'operator' => 'IN'
		);		

	}
}

//Buildung the "Select Term" Query
if ($selected_term) {
	$tax_args[] = array(
		'taxonomy'	=>	'edition',
		'field'		=>	'id',
		'terms'		=>	array($selected_term)
	);	
}	

$final_args['tax_query'] = $tax_args;

//If keywords
if (!empty($_GET['ifd_keywords'])) {
	$keywords = explode(',', $_GET['ifd_keywords']);
	add_filter('posts_where', 'ifd_filter_where_keywords');
}
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$final_args['paged'] = $paged;

$the_query = new WP_query($final_args);

//var_dump($the_query->request);

global $more;
$now = new DateTime();
	if ($the_query->have_posts()) : ?>
	<?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
        <?php
            // Get the expiration date from the current article if it exists in the article's meta
            $expirationDateString = get_post_meta(get_the_ID(), '_expiration', true);

            // Split the date string into parts. Take into account that the string could be split on '-',
            // '/', or '.'.
            $dateParts = preg_split("/(-|\/|\.)/", $expirationDateString);

            // If a date was set there will be three items in the $dateParts array
            if (count($dateParts) === 3) {
                $expirationDate = new DateTime();
                $expirationDate->setDate($dateParts[0], $dateParts[1], $dateParts[2]);
                $expirationDate->setTime(0, 0, 0);

                // If today is past the expiration date then just continue to the next article.
                if ($now > $expirationDate) {
                    continue;
                }
            }

        ?>
        <li>
			<h4><?php the_title(); ?></h4>
			<p class="meta">
			<?php //getting the editions 
				  $article_editions = get_the_terms(get_the_id(), 'edition');
				  if ($article_editions) :			
			?>
			<strong>Edition:</strong>
			<?php $output = '<span>';
				  foreach ($article_editions as $edition) {
					$output .= $edition->name . ', ';
				  } 
				  $output[strlen($output) - 2] = '';
				  $output .= '</span>';	
				  echo $output;
			?>
			<?php endif; ?>	
			<?php 
			$terms = wp_get_object_terms($post->ID, 'date_published');
			$month_number = get_the_time('n', $post->ID);
			$season_name = count($terms) > 1 ? $terms[count($terms)-1]->name : ifd_get_season($month_number);
			$season_name = str_ireplace(' issue', '', $season_name);
			$year = !empty($terms[0]) ? $terms[0]->name : get_the_time('Y', $post->ID); 
			?>
			<span><strong>Published:</strong> <?php echo $season_name . ' ' . $year; ?></span></p>
			<a href="<?php echo get_permalink(get_the_id()); ?>" class="result-thumb">
				<?php 
				$thumb = ''; 
				$thumbnail = wp_get_attachment_image_src ( get_post_thumbnail_id ( get_the_id() ), 'thumbnail' ) ; 
				if (empty($thumbnail)) {
					$thumb = '<img src="' . get_bloginfo('stylesheet_directory') . '/images/' . 'no_image.jpg' . '" alt="' . esc_attr(get_the_title()) . '"/>'; 
				} else {
					$thumb = $thumbnail[0]; 
				}

                // If a mini_thumb is associated with the id then set the thumbnail to be the mini thumbnail.
                $mini_thumb = get_post_meta(get_the_id(), '_mini_thumb', true);
                if (!empty($mini_thumb)) {
                    $thumb = get_upload_url() . '/' . $mini_thumb;
                }
			?>
			<?php if (!empty($thumbnail)): ?>
			<img src="<?php bloginfo('stylesheet_directory'); ?>/scripts/tt.php?src=<?php echo $thumb; ?>&q=100&zc=1&w=103&h=103" /> 
			<?php else: ?>
			<?php echo $thumb; ?>
			<?php endif; ?>
			</a>
			<div class="result-body">
				<?php echo ifd_get_excerpt_max_charlength(apply_filters('the_content', $post->post_content), 750, '<h2>'); ?>
				<p></p>
				<p><a href="<?php echo get_permalink(get_the_id()); ?>" class="button"><span>Read More</span></a></p>
			</div>
			<div class="cl">&nbsp;</div>
		</li>
	<?php endwhile; ?>

	<?php if (  $the_query->max_num_pages > 1 ) : ?>
		<div class="pagination right">
			<span class="number">Page <?php echo max(1, get_query_var('paged')); ?> of <?php echo $the_query->max_num_pages; ?></span>
			<div class="all-pages">
			<?php 
					$big = 999999999; // This needs to be an unlikely integer
					echo paginate_links(array(
										'base' => str_replace( $big, '%#%', get_pagenum_link($big) ),
										'format' => '?paged=%#%',
										'total' => $the_query->max_num_pages,
										'current' => max(1, get_query_var('paged')),
										'prev_text' => '< Previous',
										'next_text' => 'Next >',
										'mid_size' => 5	
			)); ?>
			</div><!-- end of all pages -->
		</div>
		<div class="cl">&nbsp;</div>
	<?php endif; ?>
	
<?php else : ?>
	<li class="not-found">
		<?php if ($post_request): ?>
		<h4>No results found. Please try again.</h4>
		<?php else: ?>
		<h4>Enter Search Parameters</h4>
		<div class="etnry">
			<p>Enter the parameters of your search - above.</p>
		</div>
		<?php endif ?>
		
	</li>
<?php endif; ?>
<?php wp_reset_postdata(); ?>