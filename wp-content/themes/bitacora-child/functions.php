<?php

add_action( 'wp_enqueue_scripts', 'my_theme_enqueue_styles' );
function my_theme_enqueue_styles() {
	$parenthandle = 'bitacora-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
	$theme        = wp_get_theme();
	wp_enqueue_style( $parenthandle,
		get_template_directory_uri() . '/style.css',
		array(),  // If the parent theme code has a dependency, copy it to here.
		$theme->parent()->get( 'Version' )
	);
	wp_enqueue_style( 'child-style',
		get_stylesheet_uri(),
		array( $parenthandle ),
		$theme->get( 'Version' ) // This only works if you have Version defined in the style header.
	);
}

add_shortcode('events', 'show_upcoming_events');

function show_upcoming_events() {
	$query = new WP_Query(array(
    'post_type' => 'events',
    'post_status' => 'publish'
));

$post_ids = [];
	
while ($query->have_posts()) {
    $query->the_post();
    $post_ids[] = get_the_ID();
}

wp_reset_query();
	
ob_start();
	foreach($post_ids as $post) {
		$title = get_the_title($post);
		$date = get_field('date', $post);
		$time = get_field('start', $post);
		$location = get_field('location', $post);
		$link = get_field('link', $post);
		
		$today = date('d.m.Y');
		if($date >= $today){
				?> 
		<div class='container-event'>
			<div class='row-1'>
				<h3> <?php echo $title ?></h3>
				<span class='date'> <?php echo $date ?></span>
			</div>
			<div class='row-2'>
				<span>Wann:</span>
				<span>Wo:</span>
				<span>Link:</span>
				<span class='time'><?php echo $time ?> </span>
				<span class='location'><?php echo $location ?></span>
				<a href='<?php echo $link?>' class ='link' target='_blank'><?php echo $link ?></span>
			</div>
			
		</div>
		<?php
			
		}
	
	}
	


	return ob_get_clean();
}