    $('.grid').masonry({
        itemSelector: '.grid-item',
        columnWidth: '.grid-sizer',
        gutter: '.gutter-sizer',
        percentPosition: true
    });
    var bannerSlider = $('#banner-slider');
    bannerSlider.owlCarousel({
        center: true,
        loop: true,
        nav: false,
        autoplay: true,
        autoplayTimeout: 2000,
        autoplaySpeed: 1500,
        animateOut: 'fadeOut',
        dots: false,
        responsive:{
            0:{
                items:1
            },
            601:{
                items: 3
            }
        }
    });

    var partnerSlider = $('#partner-slider');
    partnerSlider.owlCarousel({
        center: true,
        loop: true,
        nav: false,
        autoplay: true,
        autoplayTimeout: 4000,
        autoplaySpeed: 2000,
        animateOut: 'fadeOut',
        dots: false,
        responsive:{
            0:{
                items:1
            },
            601:{
                items: 3
            }
        }
    });

    $('.partnerNextBtn').click(function() {
        partnerSlider.trigger('next.owl.carousel', [3000]);
    });

    $('.partnerPrevBtn').click(function() {
        partnerSlider.trigger('prev.owl.carousel', [3000]);
    });

    $('.sliderNextBtn').click(function() {
        bannerSlider.trigger('next.owl.carousel', [3000]);
    });

    $('.sliderPrevBtn').click(function() {
        bannerSlider.trigger('prev.owl.carousel', [3000]);
    });

    var topUser = $('#top-user');
    topUser.owlCarousel({
        startPosition: 1,
        center: true,
        loop:true,
        autoplay: true,
        autoplayTimeout: 3000,
        autoplaySpeed: 1500,
        animateOut: 'fadeOut',
        nav:false,
        dots: false,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:3
            }
        }
    });

    $('.topUserNextBtn').click(function() {
        topUser.trigger('next.owl.carousel', [1500]);
    });

    $('.topUserPrevBtn').click(function() {
        topUser.trigger('prev.owl.carousel', [1500]);
    });

    var statSlider = $('#stat-slider');
    statSlider.owlCarousel({
        animateOut: 'fadeOut',
        nav:false,
        dots: false,
        autoplay: true,
        autoplayTimeout: 2000,
        autoplaySpeed: 1000,
        responsive:{
            0:{
                items:1,
                loop:true
            },
            768:{
                items:4
            }
        }
    });

    $('.statNextBtn').click(function() {
        statSlider.trigger('next.owl.carousel', [1000]);
    });

    $('.statPrevBtn').click(function() {
        statSlider.trigger('prev.owl.carousel', [1000]);
    });

    $(function () {
        $('input[type="file"]').styler({
            fileBrowse:'+'
        });
        $(".attach-gallery").colorbox({rel: 'firstGroup'});
    });

    $('a.attach-gallery').fancybox({
        "overlayShow" : true,
        "overlayOpacity" : 0.8
    });