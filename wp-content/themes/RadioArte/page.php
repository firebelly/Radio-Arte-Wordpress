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

	<div id="main">
		<div class="padding_left">
			<?php if(have_posts()) : ?>
			<?php while(have_posts()) : the_post(); ?>
				
				<?php the_content(); ?>
				
			<?php endwhile; endif; ?>
		</div>
	</div>

	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>