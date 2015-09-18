<?php
/* Resize image
-------------------------------------------------*/
if(!function_exists('ikra_image')):
function ikra_image($url, $width = '', $height = '', $placeholder = true, $crop = true, $align = '', $retina = false){
	global $wpdb, $blog_id;
	
    $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$url'";
    $id = $wpdb->get_var($query);
	
	$the_image_name = basename($url);
	
	if(($id == true) && function_exists('mr_image_resize') && ($url != NULL)){
		return mr_image_resize($url, $width, $height, $crop, $align, $retina);
	}else{
		if($placeholder == true){
			return 'http://placehold.it/'.$width.'x'.$height;
		}else{
			return NULL;	
		}
	}
}
endif;


function ikra_string_limit($str, $maxlen){
	if($maxlen == false){
		return $str;
	}else{
		if (strlen($str) <= $maxlen) return $str;
	
		$newstr = substr($str, 0, $maxlen);
		if (substr($newstr, -1, 1) != ' ') $newstr = substr($newstr, 0, strrpos($newstr, " "));
	
		return $newstr;
	}
}