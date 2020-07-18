<div id="footer">

  <div class="footer-content padding-left-130">
  <div class="footer-content-top-links padding-15">
    <a href="#">Kontakt</a>
    <a href="#">Impressum</a>
    <a href="#">Datenschutzerklärung</a>

  </div>
  <div class="footer-content-left padding-left-130">
            <?php
							wp_nav_menu( array(
								'walker'          => new Main_Menu_Sublevel_Walker(),
							  'theme_location'  => 'footer_menu',
								'container_class' => 'footer-menu padding-15',
								'menu_class'      => 'footer-menu' ) );
						?>
  </div>
    <div class="footer-content-right padding-right-130">
      <div class="footer-content-inner-left">
        <h5>Kontakt</h5>
        <p>Csc Group GmbH<br> 
           Nürtinger Str. 11<br>
           D-73257 Köngen
        </p>
        <div class="footer-content-right-sm">
          <a href="/"><span><i class="fab fa-linkedin-in"></i></span></a>
          <a href="/" href=""><span><i class="fab fa-facebook-square"></i></span></a>
          <a href="/"><span><i class="fab fa-youtube"></i></span></a>
          <a href="/"><span><i class="fab fa-twitter"></i></span></a>
        </div>


        
      </div>
      <div class="footer-content-inner-right">
        <h5 class="main-green-color">Kontaktiere Uns</h5>
        <p>info@csc.de<br> 
        Telefon: 07024 409220<br>
        Fax: 07024 409220
        </p>
        <a class="footer-content-inner-right-icon" href="/"><span><i class="fal fa-envelope-open-text"></i></span></a>
        <img src="http://csc.oompa.de/wp-content/uploads/2020/07/footer-tervis-gmbh.png" alt="">
      </div>
    </div>
  </div>

        
  <div class="footer-bottom">
    <div>

      <div class="footer-bottom-left padding-left-130 padding-15">
            Copyright <?php echo wpb_copyright(); ?> CSC Group GmbH
      </div>
      <div class="footer-bottom-menu">
            <?php
							wp_nav_menu( array(
								'walker'          => new Main_Menu_Sublevel_Walker(),
							  'theme_location'  => 'footer_menu',
								'container_class' => 'footer-menu',
								'menu_class'      => 'footer-menu' ) );
						?>
      </div>
      <div class="footer-bottom-right padding-right-130 padding-15">
            Website by <a href="https://oompa.de">OOMPA Design</a>
      </div>

    </div>
  </div>



</div>


<?php wp_footer(); ?>
</body>
</html>

