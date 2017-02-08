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
            $('#back-to-top').tooltip('hide');
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
        side: 'right',
       body: '.site-container'
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


//Make tabs closeable
//
jQuery(document).ready(function($) {

    $('.filter-sort-tabs li a').click(function (e) {

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
