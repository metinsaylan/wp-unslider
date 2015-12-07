<li class="wp-unslider-slide">
	<?php if( has_post_thumbnail() ){
		// the_post_thumbnail();
		
		$image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), "full" ); ?>
		
		<img src="<?php echo $image[0]; ?>" alt="<?php the_title(); ?>" width="" height="" />
		
	<?php
	} ?>
	
	<h3 class="slide-title"><?php the_title(); ?></h3>
	<p class="slide-text"><?php the_content(); ?></p>
</li>