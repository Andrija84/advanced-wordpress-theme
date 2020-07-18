	<header class="">

				<div class="">
					<!-- show_cart_fixed function -->

						  <div class="logo">
							  <a href="<?php echo home_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/csc_logo.svg" alt=""></img></a>

						  </div>

						<!-- Main Menu -->
							<?php
							wp_nav_menu( array(
								'walker'          => new Main_Menu_Sublevel_Walker(),
							    'theme_location'  => 'main_menu',
								'container_class' => 'main-menu',
								'menu_class'      => 'main-menu' ) );
							?>
				
                            <div class="header-sm-container">
							  <div class="header-sm-item">
							     <i class="fal fa-search"></i>
							  </div>
							</div>
						  <div class="burger"> <span></span></div>

				</div>
	</header>

    <!-- Mobile Menu -->
	<?php
	wp_nav_menu( array(
			'walker'          => new Mobile_Main_Menu_Sublevel_Walker(),
			'theme_location' => 'mobile_menu',
			//'container_class' => 'mobile-menu-container',
			'menu_class'      => 'mobile-menu closed' ) );
	?>



