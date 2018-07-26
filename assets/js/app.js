const $ = require('jquery');

$(document).ready( function () {
    $(".content-profil").hide();
    
    $(".menu-profil span.name-profil a").click( function () {
        var obj = $(this).closest('.menu-profil').find('.content-profil');
        var icone = $(this).closest('.menu-profil').find('.icone');
        console.log('ok');
        if (obj.css('display') == 'none') {
          var show = 1;
          
        }
        
        else {
          show = 0;
          console.log('no ok');
          icone.removeClass('fa-chevron-up').addClass('fa-chevron-down');
        }
        $('.content-profil').hide(500);
        $('.icone').removeClass('fa-chevron-up').addClass('fa-chevron-down');
        
        if (show) {
          
          obj.show(500);
          icone.removeClass('fa-chevron-down').addClass('fa-chevron-up');
        }
        return false;
    });
});