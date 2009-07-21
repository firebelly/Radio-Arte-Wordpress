<?php get_header(); ?>

<!--[if lte IE 6]>
<style type="text/css">
	#homepage-marquee h3{
		background-image: none;
		filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='/images/image_overlay.png', sizingMethod='image');
	}
</style>	
	
<![endif]-->
<script type="text/javascript">

jQuery(function() {
	jQuery('#homepage-marquee').rockinSlideshow();
});


(function($){
	
	$.fn.slideRight = function(target, callback){
		this.animate({left: target}, callback);
	}
	
	$.fn.slideLeft = function(target, callback){
		this.animate({right: target}, callback);
	}
	
	$.fn.startLoading = function() {
		if(!$(this).data("isLoading")){
			$('body').append($("<div class='loading'></div>").css({position: "absolute", width: $(this).width(), height: $(this).height(), top: $(this).offset().top, left: $(this).offset().left, opacity: .5}));
			$(this).data("isLoading", true)
		}
	}
	
	$.fn.stopLoading = function() {
		$(".loading").remove();
		$(this).data("isLoading", false);
	}
	
	$.fn.rockinSlideshow = function(photos_json) {
		var offset = 0;
		var photos = [];
		var self = this;
		
		
		return this.each(function(i, el){
				new Slideshow(el);
			
		});
		
		
	}
	
	function Slideshow(el){
		this.element = el;
		this.photos = $("#homepage-marquee li");
		this.pointer = 0;
		var self = this;
		this.main_img = "#main_img_container img";
		this.timer = setInterval(function(){
		self.next();
		}, 3000);
		this.initEvents();
	}
	
	$.extend(Slideshow.prototype, {
		initEvents: function() {
			var self = this;
			$(this.element).find("#next").click(function(e){
				e.preventDefault();
				self.next();
			});
			
			$(this.element).find("#prev").click(function(e){
				e.preventDefault();
				self.prev();
			});
			$(this.main_img).live("click", function(e){
				e.preventDefault();
				self.next();
			});
			
			$(document).keyup(function(e){
				switch(e.keyCode){
					case 39:
						self.next();
						break;
					case 37:
						self.prev();
						break;
				}
			});
		},
		next: function() {
			this.pointer++;
			this.switchPhoto(this.photos[Math.abs(this.pointer%this.photos.length)], "left");
		},
		
		prev: function() {
			this.pointer--;
			this.switchPhoto(this.photos[Math.abs(this.pointer%this.photos.length)], "right");
		},
		
		switchPhoto: function(photo){
			var dir = "left";
			var image = photo;
			var self = this;
			
			$(this.element).find("ul").animate({left: -$(photo).position().left}, {queue: false, duration: 500, easing: "easeInBack", complete: function() {//console.log($(this.element).find("ul").append($(this.element).find("ul li:first")));
			}});
			
			self.switchCaption(photo);
			
		},	
		
		switchCaption: function(photo){
			$("#photo_caption span").fadeOut(function(){
				$(this).text($(photo).find("img").attr("title")).fadeIn("fast");
			})
		}
	})
	
})(jQuery);
</script>

<div id="content" class="clearfix">
	<div id="main" class="full-width">
	  <div class="padding_left">
	  <div id="homepage-marquee">
		<ul id="slideshow" class="clearfix">
	    	<li><img src="<?php bloginfo('template_url'); ?>/images/header_homepage.jpg" alt="Header Homepage" title="Youth-Driven Public Radio"></li>
			<li><img src="<?php bloginfo('template_url'); ?>/images/banner2.jpg" alt="Header Homepage" title="Exclusive Latin And Urban Alternative Programming"></li>
			<li><img src="<?php bloginfo('template_url'); ?>/images/banner3.jpg" alt="Header Homepage" title="Empowering Youth Through Media/Journalism Training"></li>
		</ul>
		<h3 id="photo_caption"><span>Youth-Driven Public Radio</span></h3>
	  </div>
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
		
		<h1>Events/News</h1>



	<div id="homepage-news-and-events">
		<div id="events">
		  <p class="caption" style="height:35px"><em class="homepage">Upcoming events from Radio Arte&rsquo;s community calendar. We want to help promote you! Be sure to add your events.</em></p>
  		<div class="event-list">
    		<h2 class="events">EVENTS</h2>
			<?php SidebarEventsList(3);?>
    		<ul>
				
    		  <li><a class="link-with-arrow" href="/calendar">View Full Calendar</a></li>
    		</ul>
      </div>
    </div>
    
    <div id="news">
      <p class="caption" style="height:35px"><em class="homepage">Today&rsquo;s Headlines pulled from cnn.com  </em></p>
  		<div class="event-list">
  		  <h2 class="news">NEWS</h2>
		<?php $news = new WP_Query(array("category_name"=>"News")); ?>
		<ul>
			<?php while($news->have_posts()) : $news->the_post(); ?>
				
				<li><a href="<?php echo(get_post_meta($news->post->ID, 'link_url', true)); ?>"><?= $news->post->post_title; ?></a></li>
				
			<?php endwhile; ?>
		
    		</ul>
    	</div>
  	</div>
</div>	


</div>
	</div>

	<?php get_sidebar(); ?>
</div>

<?php get_footer(); ?>