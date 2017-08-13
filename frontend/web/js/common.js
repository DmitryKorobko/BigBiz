function count(options){
    var $this = $(this);
    options = $.extend({}, options || {}, $this.data('countToOptions') || {});
    $this.countTo(options);
}

function get_width() {
    return $(window).width();
}

$(document).ready(function() {
    $(function(){
        $('#header').load('header.php');
        $('#footer').load('footer.php');
    });
    $('.more-btn').on('click', function () {
        $(this).children('i.fa').toggleClass('fa-spin');
    });

    $('.collapsible').collapsible();

    $(".button-collapse").sideNav({
        menuWidth: 250
    });

    $('.modal').modal({
        opacity: .6,
        starting_top: '4%',
        ending_top: '10%'
    });

    $('.save-review-btn').on('click', function () {
        $('#new-review-modal').modal('close');
    });


    $(window).scroll(function () {
        if ($(this).scrollTop() > 1000) {
            $('.scroll-up').fadeIn();
        } else {
            $('.scroll-up').fadeOut();
        }
    });

    $('.scroll-up').click(function (){
        $('body,html').animate({
            scrollTop:0
        }, 800);
        return false;
    });

    $(window).on('resize', function() {
        if (get_width() < 769) {
            $('.vip-shop ul.tabs').tabs('select_tab', 'vip-grid');
            $('.top-shop ul.tabs').tabs('select_tab', 'vip-grid');
        } else {
            $('.vip-shop ul.tabs').tabs('select_tab', 'vip-list');
        }
    });
    $(window).trigger('resize');

    var comEditBtn = $('.comment-edit-btn'),
        comSaveBtn = $('.comment-save-btn'),
        comDeleteBtn = $('.comment-delete-btn'),
        comAnswerBtn = $('.answer-btn'),
        newComment = $('.new-comment .materialize-textarea'),
        body = $('body'), 
        onlineBtn = $('.online-btn'),
        offlineBtn = $('.offline-btn'),
        avatarHeader = $('.dropdown-button .author-photo'),
        exitBtn = $('.exit-btn');

    comEditBtn.on('click', function () {
        $(this).toggleClass('active');
        $(this).prev('.comment-save-btn').toggleClass('hide');
        $(this).siblings('.comment-delete-btn').toggleClass('hide');
        $($(this).parents('.single-comment').eq(0)).find('li.add-document').toggleClass('hide');
    });

    comEditBtn.on('click', function () {
        if ($(this).hasClass('active')){
            $(this).parents('ul.author-info').next('.comments-text').children('.materialize-textarea').replaceWith( function () {
                $(this).replaceWith("<span>" + $(this).text() + "</span>");
            });
            // $(this).removeClass('active');
        } else{
            $(this).parents('ul.author-info').next('.comments-text').children('span').replaceWith( function () {
                $(this).replaceWith("<textarea class='materialize-textarea'>" + $(this).text() + "</textarea>");
                $('.materialize-textarea').trigger('autoresize');
            });
        }

        // $(this).addClass('disabled');

    });


    comSaveBtn.on('click', function (e) {
        e.preventDefault();
       // $(this).next('.comment-edit-btn').removeClass('disabled');
        $(this).prev('.comment-delete-btn').addClass('hide');
        $($(this).parents('.single-comment').eq(0)).find('li.add-document').addClass('hide');
        $(this).parents('ul.author-info').next('.comments-text').children('.materialize-textarea').replaceWith( function () {
            $(this).replaceWith("<span>" + $(this).text() + "</span>");
        });
       $(this).addClass('hide');
    });

    comDeleteBtn.on('click', function (e) {
        e.preventDefault();
        $(this).parents('.single-comment').remove(":first");
    });

    newComment.on('click', function () {
        $(this).next('.button-box').toggleClass('hide');
    });




    comAnswerBtn.on('click', function () {
        var val = $(this).parents('.author-info').find('.author-name').text();
        newComment.focus();
        newComment.val(val);
        $(this).parents('.comments-list').next('.new-comment').find('.reply-user-name').text(val)

    });

    var inputComments= $('.attachments-list input.input-file');
    inputComments.on('change', function (evt) {
        var file = evt.target.files;
            // console.log($($(this).parent().parent().parent()[0]));
            // console.log($('#second'));
            var reader = new FileReader();
            reader.onload = function() {
                return function (e) {
                    var li = $('<li/>');
                    var link = $('<a/>',{'class':'attach-gallery' ,'rel':'gal'});
                    var img = $('<img/>',{'class':'preview'});
                    img.attr('src', e.target.result);
                    link.attr('href', e.target.result);
                    $('#com').find('.add-document').before(li);
                    link.appendTo(li);
                    img.appendTo(link);
                }
            }(file[0]);
            reader.readAsDataURL(file[0]);

    });

    $(window).scroll(function() {
        $('#facts').each(function(){
            var itemPos = $(this).offset().top;
            var topOfWindow = $(window).scrollTop();
            var time = $('span.timer').text();

            if (itemPos < topOfWindow+400)
                if(time == '' || time == '0')
                    $('.timer').each(count);
        });
    });

    $('.dropdown-button').dropdown({
            inDuration: 300,
            outDuration: 225,
            constrainWidth: false,
            gutter: 0,
            belowOrigin: true,
            alignment: 'left',
            stopPropagation: false
        }
    );

    onlineBtn.on('click', function (e) {
        e.preventDefault();
        if($(this).hasClass('active')){

        } else {
            $(this).addClass('active');
            offlineBtn.removeClass('active');
            avatarHeader.removeClass('offline-sm');
            avatarHeader.addClass('online-sm');
        }
    });

    offlineBtn.on('click', function (e) {
        e.preventDefault();
        if($(this).hasClass('active')){
        } else {
            $(this).addClass('active');
            onlineBtn.removeClass('active');
            avatarHeader.addClass('offline-sm');
            avatarHeader.removeClass('online-sm');
        }
    });

    exitBtn.on('click', function (e) {
       e.preventDefault();
       body.find('ul.user-box').addClass('hide');
       body.find('ul.btn-box').removeClass('hide');
    });

    var halfText = $('.spoiler').innerHeight() / 2,
        textHeight = $('.spoiler').innerHeight();
    $('.spoiler').css('height', $('.spoiler').innerHeight() / 2);
    $('.show-hide-btn').on('click', function() {
        if($(this).prev('.spoiler').innerHeight() == halfText) {
            $(this).prev('.spoiler').animate({ height: textHeight }, 500);
            $(this).text('Скрыть');
        } else {
            $(this).prev('.spoiler').animate({ height: halfText }, 500);
            $(this).text('Показать полностью...');
        }
    });
});



