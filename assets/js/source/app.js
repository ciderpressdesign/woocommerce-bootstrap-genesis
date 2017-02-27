(function($){
	$(document).ready(function(){
		//Tooltip
		$('[data-toggle="tooltip"]').tooltip();

		// Popover
		$('[data-toggle="popover"]').popover();

		// Back to top 
		// @link http://bootsnipp.com/snippets/featured/link-to-top-page
		$(window).scroll(function () {
            if ($(this).scrollTop() > 50) {
                $('#back-to-top').fadeIn();
            } else {
                $('#back-to-top').fadeOut();
            }
        });
        // scroll body to 0px on click
        $('#back-to-top').click(function () {
            $(this).tooltip('hide');
            $('body,html').animate({
                scrollTop: 0
            }, 800);
            return false;
        });

        $('#back-to-top').tooltip('show');
	});


	// Window load event with minimum delay
	// @https://css-tricks.com/snippets/jquery/window-load-event-with-minimum-delay/
	(function fn() {
		fn.now = +new Date;
		$(window).load(function() {
			if (+new Date - fn.now < 500) {
				setTimeout(fn, 500);
			}
		});
	})();
})(jQuery);


jQuery(document).ready(function($) {
    $('.menu-link').sidr({
        side: 'right'
        // body: '.site-container'
    });

    $('.sidr-close-link').click( function() {
       $.sidr('close','sidr');
    });
});

jQuery(document).ready(function($) {

 //   var $grid = $('.products').isotope({
 //        // options
 //        itemSelector: '.product',
 //        layoutMode: 'fitRows',
 //        filter: '.pa_stone-ruby'
 //    });

  //  console.log('isotope');


    
});

jQuery(document).ready(function($) {

    $("body").removeClass('no-js');

});

jQuery(document).ready(function($) {

    $('.search-expanding .search-submit').prop('disabled',true);


    // $('.search-expanding').focusout( function() {
    //     $('.search-expanding .search-submit').prop('disabled',true);
    // });

    $('.search-expanding .search-field').on( 'input', function() {
        var contents = $(this).val();
        if( contents === '' ) {
            console.log('empty');
            $('.search-expanding .search-submit').prop('disabled', true);
        } else {
            $('.search-expanding .search-submit').prop('disabled', false);
        }

    });

    // $('.search-expanding').focusin( function() {
    //     $('.search-expanding .search-field').focus();
    // });


});


// Make tabs closeable

jQuery(document).ready(function($) {

    $('.filter-sort-tabs li a').click(function () {

        var tab = $(this);
        //var tabpane = tab.data('')
        if(tab.parent('li').hasClass('active')){
            window.setTimeout(function(){
                $(".tab-pane").removeClass('active');
                tab.parent('li').removeClass('active');
            },1);
        }
    });

});


// Add class to menu on scroll
jQuery(document).ready(function ($) {
    $(window).on("scroll", function () {
        if ($(window).scrollTop() > 50) {
            $(".site-header").addClass("active");
        } else {
            //remove the background property so it comes transparent again (defined in your css)
            $(".site-header").removeClass("active");
        }
    });
});


jQuery(document).ready(function ($) {
    $('.product-slides').bxSlider({
        pagerCustom: '.slide-pager',
        mode: 'fade',
        // video: 'true',
        controls: false,
        responsive: true,
        touchEnabled: false
    });
});


jQuery(document).ready(function ($) {
    // init Isotope
    var $grid = $('.archive .products').isotope({
        itemSelector: '.product',
        layoutMode: 'fitRows',
        percentPosition: true,
        fitRows: {
            columnWidth: '.product'
        }


    });
// filter items on button click
    $('.filter-shapes--shape-link').on('click', function () {
        var filterValue = $(this).attr('data-filter');
        console.log($(this));
        console.log(filterValue);
        $grid.isotope({filter: filterValue});
    });
});

jQuery(document).ready(function ($) {
    //
    // $(".slide-product-image").ezPlus({
    //     cursor: 'pointer',
    //     imageCrossfade: true,
    //     zoomType: 'lens',
    //     lensSize: 200,
    //     containLensZoom: true
    // });

    initEZPlus();

    //Triggered when window width is changed.
    $(window).on("resize", function () {
        var windowWidth = $(window).width(), // get window width
            imgWidth = $(".slide-product-image").width(); // get image width
        //Init elevateZoom
        $('.zoomContainer').remove();
        initEZPlus();
        //display status
        console.log("Status: Window resized!.");
        //display image and window width
        console.log("Image width: " + imgWidth + "px----Window width: " + windowWidth + "px");
    });

    function initEZPlus() {
        $(".slide-product-image").ezPlus({
            responsive: true,
            zoomType: 'lens',
            lensSize: 200,
            containLensZoom: true,
            cursor: 'crosshair',
            scrollZoom: false,

            respond: [
                {
                    range: '768-992',
                    tintColour: '#F00',
                    lensSize: 150
                },
                {
                    range: '993-1200',
                    tintColour: '#00F',
                    zoomWindowHeight: 200,
                    zoomWindowWidth: 200
                },
                {
                    range: '100-768',
                    enabled: false,
                    showLens: false
                }
            ]
        });
    }

// Toggle on click
    $(".slide-product-image").on('click', function () {
        if ($('.zoom-enabled').length === 0) {
            $('.zoomContainer').show();
            $(this).toggleClass('zoom-enabled');
        } else {
            $('.zoomContainer').hide();
            $(this).toggleClass('zoom-enabled');
        }
    });

});



