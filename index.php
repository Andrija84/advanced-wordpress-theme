
<?php get_header(); ?>
<body <?php body_class(); ?>>
<?php get_template_part( 'nav' ); // Navigation bar (nav.php) ?>
<div class="main-container">
      <?php if (have_posts()) : ?>
          <?php while (have_posts()) : the_post(); ?>
            
    <div class="main-container-inner">
	

		</div>
		

          <?php endwhile; ?>
      <?php endif; ?>
</div>  <!-- main-container END-->
<?php	get_footer(); ?>

