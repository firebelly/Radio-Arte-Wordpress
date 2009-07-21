<?php get_header(); ?>

<div class="clearfix">
<div class="firstmenu" class="clearfix">
<?php $parent = get_page_by_id(13); ?>
<?php $sub_pages = get_pages(array("parent"=>$parent["ID"], "hierarchical"=>0)); ?>
<?php if(count($sub_pages) > 0) : ?>
	<ul>
		<li class="first <?php if(is_page($parent["post_title"])) echo 'active'; ?>"><a href="<?php echo get_page_link($parent["ID"]); ?>">Overview</a></li>
	<?php foreach($sub_pages as $sub_page) : ?>
		<li class="<?php if(is_page($sub_page->post_title)) echo 'active'; ?> <?php if($sub_page == $sub_pages[count($sub_pages) - 1]) echo 'last'; ?>"><a href="<?php echo get_page_link($sub_page->ID); ?>"><?= $sub_page->post_title; ?></a></li>
	<?php endforeach; ?>
	</ul>
	<?php endif; ?>

</div>
</div>


<div id="content" class="clearfix">

	<div id="main">
		<div class="padding_left">
			<?php $this_category = get_category($cat); ?>
			<?php $header = ""; ?>
			<?php if($this_category->parent != 0) : ?>
				<?php $header .= get_category($this_category->parent)->name . ": "; ?>
			<?php endif; ?>
				<?php $header .= single_cat_title('', false); ?>
			
			
			<h1><?= $header; ?></h1>
			<?php if(have_posts()) : ?>
			<?php while(have_posts()) : the_post(); ?>
				<h2><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a></h2>
				<small><?php the_time('F jS, Y') ?> <!-- by <?php the_author() ?> --></small>

				<div class="entry">
					<?php the_content('Read the rest of this entry &raquo;'); ?>
				</div>

				<p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?>  <?php edit_post_link('Edit', '', ' | '); ?>  <?php comments_popup_link('No Comments &#187;', '1 Comment &#187;', '% Comments &#187;'); ?></p>
			<?php endwhile; ?>
			
			<div class="navigation">
				<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
				<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
			</div>
			
			<?php else : ?>
				
				<p>There are currently no posts for <?php single_cat_title('', true); ?></p>
			<?php endif; ?>
			
		</div>
	</div>

	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>