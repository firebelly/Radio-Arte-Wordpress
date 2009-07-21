<?php /*
Plugin Name: Simple Thumb
Plugin URI: http://www.seoadsensethemes.com/wp-post-thumbnail-wordpress-plugin/
Description: WP Post Thumbnail enable bloggers to upload images, crop and save it as post thumbnails without manually copy-n-paste custom field values. For theme developers, this plugin can be configured for multiple thumbnails assigned to each posts.
Version: 0.1.8
Author: Stanley Yeoh
Author URI: http://www.seoadsensethemes.com
*/?>
<?php 
add_action('admin_menu', 'add_custom_thumb_box');

function add_custom_thumb_box() {
	if (function_exists('add_meta_box'))  {
		add_meta_box( 'wp-post-thumbnail', 'Thumbnail', 'custom_thumb_input', 'page', 'normal' );
		add_meta_box( 'wp-post-order', 'Order', 'post_order_box', 'page', 'normal' );
	}
}

function on_post_save() {
	add_post_meta($_POST['post_id'], "the_thumb_id", "hey look", true)
	or update_post_meta($_POST['post_id'], "the_thumb_id", "if is set" . basename( $_FILES['file']['name']));
}

function get_featured_video_thumbs() {
	$args = array(
		'numberposts' => -1,
		'post_status' => null,
		'post_parent' => null, // any parent
		'category_name'	=> 'featured',
		'orderby' => 'menu_order',
		'order' => 'ASC'
		); 

	$attachments = get_posts($args);
	if ($attachments) {
		echo '<ul class="clearfix" id="thumbs">';
		foreach ($attachments as $attachment) {
			setup_postdata($attachment);
			
			$args = array(
				'post_type' => 'attachment',
				'numberposts' => -1,
				'post_status' => null,
				'post_parent' => $attachment->ID,
				'orderby' => 'menu_order',
				'order' => 'ASC'
				); 
			$images = get_posts($args);
			if ($images) {
				
				foreach ($images as $image) {
					echo "<li><a href='";
					echo get_permalink($attachment->ID);
					echo "'>";
					echo  wp_get_attachment_image($image->ID);
					echo "</a>";
					echo "</li>";
				}
				
			}
			
		}
		echo "</ul>";
	}
}

function post_order_box() {
	global $post;
	echo '<input id="menu_order" type="text" size="4" name="menu_order" value="' . $post->menu_order . '"/>';
}

function custom_thumb_input() {
	global $post;
	
		$args = array(
			'post_type' => 'attachment',
			'numberposts' => -1,
			'post_status' => null,
			'post_parent' => $post->ID,
			'orderby' => 'menu_order',
			'order' => 'ASC'
			); 
		$attachments = get_posts($args);
		if ($attachments) {
		
			echo '<ul id="attachments">';
			foreach ($attachments as $attachment) {
				setup_postdata($attachment);
				echo "<li id='attachment_" . $attachment->ID . "'>" . get_the_attachment_link($attachment->ID, true, array(100, 200));
				if ( current_user_can('delete_post', $attachment->ID) )
					$actions['delete'] = "<a class='submitdelete' href='" . wp_nonce_url("post.php?action=delete&amp;post=$attachment->ID", 'delete-post_' . $attachment->ID) . "' onclick=\"if ( confirm('" . js_escape(sprintf( ('draft' == $attachment->post_status) ? __("You are about to delete this attachment '%s'\n  'Cancel' to stop, 'OK' to delete.") : __("You are about to delete this attachment '%s'\n  'Cancel' to stop, 'OK' to delete."), $attachment->post_title )) . "') ) { return true;}return false;\">" . __('Delete') . "</a>";
				$actions['view'] = '<a href="' . get_permalink($attachment->ID) . '" title="' . attribute_escape(sprintf(__('View "%s"'), $title)) . '" rel="permalink">' . __('View') . '</a></li>';
				
				$i = 0;
				foreach ( $actions as $action => $link ) {
					++$i;
					( $i >= count($actions) -1 ) ? $sep = ' ' : $sep = ' | ';
					echo "<span class='$action'>$link$sep</span>";
				}
			
				
			}
			echo '</ul>';
			
			echo '
			<script type="text/javascript">
			jQuery(function(){
				jQuery("#attachments").sortable({
					update: function(){
						jQuery.get("/wp-admin/sort.php?" + jQuery("#attachments").sortable("serialize"), function(req){
							
						});
					}
				});
			});
			</script>';
			
			
		}
		
	else {
		
	 ?>
	<p>no thumbmail uploaded</p>
<?php }}?>