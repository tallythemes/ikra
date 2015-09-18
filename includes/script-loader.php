<?php
function ikra_script_loader(){
	wp_enqueue_script('jquery-masonry');
	
	wp_enqueue_style('magnific-popup', IKRA_URL.'assets/css/magnific-popup.css');
	wp_enqueue_script('magnific-popup', IKRA_URL.'assets/js/jquery.magnific-popup.min.js' , array('jquery'), '', true);
	wp_enqueue_script('fitvids', IKRA_URL.'assets/js/jquery.fitvids.js' , array('jquery'), '', true);
	
	wp_enqueue_style('ikra', IKRA_URL.'assets/css/ikra.css');
	wp_enqueue_script('ikra', IKRA_URL.'assets/js/ikra.js' , array('jquery'), '', true);
}
add_action('wp_enqueue_scripts', 'ikra_script_loader');