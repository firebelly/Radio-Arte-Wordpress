<?php

if ( function_exists('register_sidebar') )
    register_sidebar();

	
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
	
	function get_page_image($page_id, $size = 'thumbnail'){
		$args = array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post_parent' => $page_id,
			'orderby' => 'menu_order',
			'order' => 'ASC',
			'post_mime_type' => 'image'
			);
		$attachments =  get_posts($args);
		return wp_get_attachment_image($attachments[0]->ID, $size);
		
	}

	function child_is_active($page){
		foreach(custom_get_pages($page->ID) as $sub) {
			 if(is_page($sub->post_title))
	 			return true;
		}
		return false;
	}

	function get_page_or_parent($post){
		if($post->post_parent == 0)
			return $post;
		pause_exclude_pages();
		$args = array("include"=>$post->post_parent);
		$pages = get_pages($args);
		$pages = $pages[0];
		while($pages->post_parent != 0){
			$pages = get_pages(array("include"=>$pages->post_parent));
			$pages = $pages[0];
		}
		resume_exclude_pages();
		return $pages;
	}

	function get_page_or_parent_id($post){

		if($post->post_parent == 0)
			return $post->ID;
		$args = array("include"=>$post->post_parent);
		$pages = get_pages($args);
		return $pages[0]->ID;
	}
	
	function get_children_of_page($page_id){
		return get_pages(array("parent"=>$page_id, "hierarchical"=>0));
	}
	
	function get_page_by_id($pageId) {
		if(!is_numeric($pageId)) {
			return;
		}
		global $wpdb;
		$sql_query = "SELECT DISTINCT * FROM ".$wpdb->posts ." WHERE ID= " . $pageId;
		$my_block = $wpdb->get_row($sql_query, ARRAY_A);
		if(!empty($my_block)) {
			return $my_block;
		} else {
			return;
		}
	}
	
	function add_page_tree_to_main_nav() {
		add_menu_page('Page Tree', 'Page Tree', 10, "", 'add_page_tree');
		add_submenu_page(__FILE__, 'Configure Harvester', 'Config', 10, __FILE__.'/config', 'dfenh_config');
	}
	
	function add_page_tree() {
		echo "HEY YO";
	}
	
	
	add_action("admin_menu", "add_page_tree_to_main_nav");
	
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