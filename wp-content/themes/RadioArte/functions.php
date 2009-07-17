<?php

if ( function_exists('register_sidebar') )
    register_sidebar(array(
        'before_widget' => '',
        'after_widget' => '',
        'before_title' => '<div class="title">',
        'after_title' => '</div>',
    ));
	
	function get_category_id_by_name($name){
		$cat_array = array("WRTE Productions"=>4,
							"First Voice"=>6,
							"Homofrecuencia"=>3,
							"Primera Voz"=>7,
							"Sin Papeles"=>8,
							"Without Border"=>9);
		if(in_array($name, array_keys($cat_array)))
			return $cat_array[$name];
		else
			return null;
						
	}
	
	function get_categories_by_id($ids){
		$ids = flatten_array(array($ids));
		$args = array("include"=>join($ids, ", "));
		$categories = get_categories("include=3,5,7");
		return $categories;
	}

	function get_category_image($cat_id){
		$image = ciii_category_images(array("category_ids" => $cat_id));
		return $image["categories"][0]["thumbnail"];
	}

	
	//utility
	
	function flatten_array(array $a) {
	    $i = 0;
	    while ($i < count($a)) {
	        if (is_array($a[$i])) {
	            array_splice($a, $i, 1, $a[$i]);
	        } else {
	            $i++;
	        }
	    }
	    return $a;
	}
	


?>