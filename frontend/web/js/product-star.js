$(document).ready(function () {
    $('select').material_select();
    $('.collapsible').collapsible();
});


$('.starrr').starrr({
    change: function(e, value){
        if (value) {
            $('.user-star').removeClass('invisible');
            $('.choice').text(value);
        } else {
            $('.user-star').addClass('invisible');
        }
    }
});