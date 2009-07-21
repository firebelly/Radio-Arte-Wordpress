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

				
				<p class="postmetadata alt">
					<small>
						This entry was posted
						<?php /* This is commented, because it requires a little adjusting sometimes.
							You'll need to download this plugin, and follow the instructions:
							http://binarybonsai.com/wordpress/time-since/ */
							/* $entry_datetime = abs(strtotime($post->post_date) - (60*120)); echo time_since($entry_datetime); echo ' ago'; */ ?>
						on <?php the_time('l, F jS, Y') ?> at <?php the_time() ?>
						and is filed under <?php the_category(', ') ?>.
						You can follow any responses to this entry through the <?php post_comments_feed_link('RSS 2.0'); ?> feed.

						<?php if ( comments_open() && pings_open() ) {
							// Both Comments and Pings are open ?>
							You can <a href="#respond">leave a response</a>, or <a href="<?php trackback_url(); ?>" rel="trackback">trackback</a> from your own site.

						<?php } elseif ( !comments_open() && pings_open() ) {
							// Only Pings are Open ?>
							Responses are currently closed, but you can <a href="<?php trackback_url(); ?> " rel="trackback">trackback</a> from your own site.

						<?php } elseif ( comments_open() && !pings_open() ) {
							// Comments are open, Pings are not ?>
							You can skip to the end and leave a response. Pinging is currently not allowed.

						<?php } elseif ( !comments_open() && !pings_open() ) {
							// Neither Comments, nor Pings are open ?>
							Both comments and pings are currently closed.

						<?php } edit_post_link('Edit this entry','','.'); ?>

					</small>
				</p>

	<?php comments_template(); ?>
				
				
			<?php endwhile; ?>
			
			<?php else : ?>
				
				<p>There are currently no posts for <?php single_cat_title('', true); ?></p>
			<?php endif; ?>
			
		</div>
	</div>

	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>