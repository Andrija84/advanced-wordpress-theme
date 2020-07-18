jQuery(document).ready(function($){

    //ALWAYS SCROOL TO TOP ON PAGE LOAD
    $( "html" ).scrollTop( 0 );

    //AOS INIT
    AOS.init({
        // Global settings:
       // disable: 'mobile', // accepts following values: 'phone', 'tablet', 'mobile', boolean, expression or function
        //startEvent: 'DOMContentLoaded', // name of the event dispatched on the document, that AOS should initialize on
        ///initClassName: 'aos-init', // class applied after initialization
        //animatedClassName: 'aos-animate', // class applied on animation
        //useClassNames: false, // if true, will add content of `data-aos` as classes on scroll
        //disableMutationObserver: false, // disables automatic mutations' detections (advanced)
        //debounceDelay: 50, // the delay on debounce used while resizing window (advanced)
        //throttleDelay: 99, // the delay on throttle used while scrolling the page (advanced)
        
      
        // Settings that can be overridden on per-element basis, by `data-aos-*` attributes:
        //offset: 120, // offset (in px) from the original trigger point
        //delay: 0, // values from 0 to 3000, with step 50ms
        //duration: 400, // values from 0 to 3000, with step 50ms
        //easing: 'ease', // default easing for AOS animations
        //once: false, // whether animation should happen only once - while scrolling down
        //mirror: false, // whether elements should animate out while scrolling past them
        //anchorPlacement: 'top-bottom', // defines which position of the element regarding to window should trigger the animation
      
      });

    /*
    // https://developer.mozilla.org/en-US/docs/Web/API/Window/devicePixelRatio
    if(window.devicePixelRatio == 1.25 ){
        $("html").addClass("scale-down-125");
      }
    */


    //SWIPER SLIDE INIT
    var swiperHome = new Swiper('.swiper-container.homepage-slider', {

        effect: 'coverflow',
		autoplay: {
        delay: 5500,
        },
		coverflowEffect: {
	    rotate: 30,
	    slideShadows: false,
	  },

        pagination: {
            el: '.homepage-slider-pagination',
            clickable: true,
            renderBullet: function (index, className) {
                return '<span class="' + className + '">' + (index + 1) + '</span>';
            }
        },
        navigation: {
            nextEl: '.homepage-slider-next-slide'
        }
	});

    //Sub Menu
    var headerHeight = $('header').outerHeight();
    $('.main-menu li.menu-item-has-children' ).hover(
        function() {
    
          $( this ).find('.sub-menu').addClass( 'open' );
           //Sub Menu top is calculated on first hover 
          $( this ).find('.sub-menu').css( 'top', headerHeight );
        }, function() {
            $( this ).find('.sub-menu').removeClass( 'open');     
        }
      );


      //Add icon is menu item hass class
      $('.main-menu li').each(function(){
        if($(this).hasClass('menu-item-has-children')) {
            $(this).append('<i class="fas fa-angle-down"></i>');
        } 
     });

    //Add angle down if mobile menu item has sub menu
	$( '.mobile-menu li.menu-item-has-children' ).prepend( '<i class="fas fa-angle-down"></i>' );


    //Detect click on arrow
    $('.mobile-menu li.menu-item-has-children .fa-angle-down').on('click',function(){
        var parent_el =  $(this).parent('.mobile-menu li.menu-item-has-children');
        parent_el.find('.mobile-sub-menu').toggleClass('open');
    });


    var burger = $('.burger');
    var mobileMenu = $('.mobile-menu');
    var mobileMenuContainer = $('.mobile-menu-container');
    //Select only first ul, thats main menu ul list
    var mobileMenuUlist = $('.mobile-menu > ul');
    
        //Open/Close burger mobile menu


    $('.burger').click(function(){

            var burger = $(this);
            var mobileMenu = $('.mobile-menu');
            var mobileMenuUlist = $('.mobile-menu > ul');
          
            //Burger Menu OPEN
         if (mobileMenu.hasClass('closed')){

                mobileMenu.removeClass('closed');
                mobileMenu.removeClass('slide-out');
                mobileMenu.addClass('open');
                mobileMenu.addClass('slide-in');
                mobileMenuUlist.addClass('show');
                mobileMenuContainer.addClass('show');
        }
            //Burger Menu CLOSE
         else{
                mobileMenu.addClass('closed');
                mobileMenu.addClass('slide-out');
                mobileMenuUlist.removeClass('hide');
                mobileMenu.removeClass('open');
                mobileMenu.removeClass('slide-in');
                mobileMenuUlist.removeClass('show');
                mobileMenuContainer.removeClass('show');
        }
        
            burger.toggleClass('clicked');
    });

         

            //https://www.cssscript.com/detect-finger-swipe-events-javascript-pure-swipe/
            // swiped-left
            document.addEventListener('swiped-left', function(e) {

                burger.addClass('clicked');
                mobileMenu.removeClass('closed');
                mobileMenu.removeClass('slide-out');
                mobileMenu.addClass('open');
                mobileMenu.addClass('slide-in');
                mobileMenuUlist.addClass('show');
                mobileMenuContainer.addClass('show');

            });
            
      
            // swiped-right
            document.addEventListener('swiped-right', function(e) {

                if (mobileMenu.hasClass('open')){

                burger.removeClass('clicked');
                mobileMenu.addClass('closed');
                mobileMenu.addClass('slide-out');
                mobileMenuUlist.removeClass('hide');
                mobileMenu.removeClass('open');
                mobileMenu.removeClass('slide-in');
                mobileMenuUlist.removeClass('show');
                mobileMenuContainer.removeClass('show');
                }
            });
     

        
            
            // swiped-up
            document.addEventListener('swiped-up', function(e) {
                console.log('swiped-up');
            });
            
            // swiped-down
            document.addEventListener('swiped-down', function(e) {
                console.log('swiped-down');
            });
 
      

            // Hide header on on scroll down
            var lastScrollTop = 0;
            var navbarHeight = $('header').outerHeight();
            var header = $('header');

            $(window).scroll(function(event){
                hasScrolled();
            });

    
            function hasScrolled() {
            var st = $(this).scrollTop();
            //var scrollTop = $(this).scrollTop();



            // If they scrolled down and are past the navbar, add class .scrolled.
            // This is necessary so you never see what is "behind" the navbar.   
            if (st > lastScrollTop && st > 131 ){
                // Scroll Down
                $('header').addClass('scrolled');               
            } 
        
                // Scroll Up
                if(st + $(window).height() < $(document).height() && st < lastScrollTop) {
                    $('header').removeClass('scrolled');
                }
           
            lastScrollTop = st;
            }

}); //Document Ready END

