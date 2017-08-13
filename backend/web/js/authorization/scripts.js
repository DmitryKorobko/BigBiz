$(window).load(function() {
    $('#tt-preloader').find('.loader').fadeOut().end().delay(300).fadeOut('slow');
    $('select').material_select();
});