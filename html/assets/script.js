jQuery(document).ready(function($) {
 





   $("#owl-products").owlCarousel(
    {
        autoplay:true,
        autoplayTimeout:2500,
          loop:true,
         nav : true,
         responsiveClass:true,
         margin: 30,        
        responsiveClass:true,
        responsive:{
        0:{
            items:1
        },
        767:{
            items:2
        },
        992:{
            items:2
        }
        }
    });






$('.carousel').swipe( {
     swipeLeft: function() {
         $(this).carousel('next');
     },
     swipeRight: function() {
         $(this).carousel('prev');
     },
     allowPageScroll: 'vertical'
 });


 });