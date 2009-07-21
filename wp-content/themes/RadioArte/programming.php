<?php
/*
Template Name: Programming
*/
?>

<?php get_header(); ?>
<div class="clearfix">
<div class="firstmenu" class="clearfix">
<?php $parent = get_page_or_parent($post); ?>
<?php $sub_pages = get_pages(array("parent"=>$parent->ID, "hierarchical"=>0)); ?>
<?php if(count($sub_pages) > 0) : ?>
	<ul>
		<li class="first <?php if(is_page($parent->post_title)) echo 'active'; ?>"><a href="<?php echo get_page_link($parent->ID); ?>">Overview</a></li>
	<?php foreach($sub_pages as $sub_page) : ?>
		<li class="<?php if(is_page($sub_page->post_title)) echo 'active'; ?> <?php if($sub_page == $sub_pages[count($sub_pages) - 1]) echo 'last'; ?>"><a href="<?php echo get_page_link($sub_page->ID); ?>"><?= $sub_page->post_title; ?></a></li>
	<?php endforeach; ?>
	</ul>
	<?php endif; ?>

</div>
</div>


<div id="content" class="clearfix">

	<div id="main" class="full-width">
		<div class="padding_left">
			<?php $wrte_productions_page = get_page_by_id(165); ?>
			<h1><?= $wrte_productions_page["post_title"]; ?></h1>
			
			<?= $wrte_productions_page["post_content"]; ?>

			<div id="homepage-productions" class="clearfix">

			<ul class="clearfix">
				<?php $categories = get_categories(array("hide_empty"=>0, "include"=>$cat_ids, "order_by"=>"order", "child_of"=>4));?>
				<?php $i = 0; ?>
				<?php foreach($categories as $production) : ?>
					<?php $i++; ?>
					<li class="<?php if(($i%3)==0) echo 'last'; ?>">
					   <a class="show_image" href="<?= get_category_link($production->term_id); ?>"><img src="<?= get_category_image($production->term_id); ?>" alt="<?= $production->name; ?>"></a>
					   <h3 class="<?= $production->slug; ?>"><?= $production->name; ?></h3>
					   <a href="<?= get_category_link($production->term_id); ?>">Read more</a>
					</li>

				<?php endforeach; ?>





			</ul>

			</div>
			
		<?php $ra_shows = get_page_by_id(19); ?>	
			<h1><?= $ra_shows["post_title"]; ?></h1>


	<div id="programming-shows" class="clearfix">

	  <?php echo($ra_shows["post_content"]); ?>
<?php $ra_show_pages = get_children($ra_shows["ID"]); ?>
	  		<div id="shows-list">
			
	  		  <ul class="pod-entries clearfix">
			<?php foreach($ra_show_pages as $show) : ?>
	  		    <li>
	  		      <div class="pod-image"><?php echo get_page_image($show->ID); ?></div>
	  		      <div class="pod-description"><p><strong><?php echo $show->post_title; ?></strong><br/><?php echo get_post_meta($show->ID, "time", true); ?><br/><em><a href="<?php echo get_page_link($show->ID); ?>">See more</a></em>
	            </p></div>
	  		    </li>
	         <?php endforeach; ?>

	  	    </ul>
	      </div>

	</div>

	<div id="syndicated-shows" class="clearfix">
<?php $syndicated_shows = get_page_by_id(65); ?>
	<h1><?= $syndicated_shows["post_title"]; ?></h1>

	  <div id="shows-list">
		  
			  <?php echo($syndicated_shows["post_content"]); ?>
		<?php $syndicated_show_pages = get_children($syndicated_shows["ID"]); ?>
			  		<div id="shows-list">

			  		  <ul class="pod-entries clearfix">
					<?php foreach($syndicated_show_pages as $show) : ?>
			  		    <li>
			  		      <div class="pod-image"><?php echo get_page_image($show->ID); ?></div>
			  		      <div class="pod-description"><p><strong><?php echo $show->post_title; ?></strong><br/><?php echo get_post_meta($show->ID, "time", true); ?><br/><em><a href="<?php echo get_page_link($show->ID); ?>">See more</a></em>
			            </p></div>
			  		    </li>
			         <?php endforeach; ?>

			  	    </ul>
		</div>
	</div>
	</div>
			
		</div>
	</div>

	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>