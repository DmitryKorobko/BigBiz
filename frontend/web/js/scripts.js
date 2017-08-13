$(window).load(function() {
    $('#tt-preloader').find('.loader').fadeOut().end().delay(400).fadeOut('slow');
    $('select').material_select();
});