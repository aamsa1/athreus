$(document).ready(function(){
    /*$(".dropdown").hover(            
        function() {
            $('.dropdown-menu', this).not('.dropdown-menu').stop(true,true).slideDown("400");
            $(this).toggleClass('open');        
        },
        function() {
            $('.dropdown-menu', this).not('.dropdown-menu').stop(true,true).slideUp("400");
            $(this).toggleClass('open');       
        }
    );*/

    // fix sub nav on scroll
    var $win = $(window)
      , $nav = $('.navbar')
      , navHeight = $('.navbar').first().height()
      , navTop = $('.navbar').length && $('.navbar').offset().top - navHeight
      , isFixed = 0

    processScroll()

    $win.on('scroll', processScroll)

    function processScroll() {
      var i, scrollTop = $win.scrollTop()
      if (scrollTop >= navTop && !isFixed) {
        isFixed = 1
        $nav.addClass('navbar-fixed-top')
      } else if (scrollTop <= navTop && isFixed) {
        isFixed = 0
        $nav.removeClass('navbar-fixed-top')

      }
    }

    $("#myTable").tablesorter();
});