<?php get_header(); ?>
<body <?php body_class(); ?>>
<?php get_template_part( 'nav' ); // Navigation bar (nav.php) ?>
<div class="container homepage-landing">
   <h1>This is index page</h1>
      <?php if (have_posts()) : ?>
        <div style="">
          <?php while (have_posts()) : the_post(); ?>
            <?php echo the_content(); ?>
          <?php endwhile; ?>
        </div>
      <?php endif; ?>
</div>  <!-- container-homepage END -->
<?php	get_footer(); ?>
