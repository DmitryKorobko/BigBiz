$(function () {
    var review = {
        init             : function () {
            this.cacheElements();
            this.bindEvents();
        },
        cacheElements    : function () {
            this.$btn_save = $('#save-shop-review');
        },
        bindEvents       : function () {
            this.$btn_save.on('click', this.saveShopReview.bind(this));
        },
        saveShopReview   : function () {
            var $btn              = this.$btn_save;
            var csrfToken         = $('meta[name="csrf-token"]').attr("content");
            var productRating     = $('#product-rating').children('.fa-star').length;
            var operatorRating    = $('#operator-rating').children('.fa-star').length;
            var reliabilityRating = $('#reliability-rating').children('.fa-star').length;
            var markerRating      = $('#marker-rating').children('.fa-star').length;
            var creatorId         = this.$btn_save.attr('data-rating-creator-id');
            var recipientId       = this.$btn_save.attr('data-rating-recipient-id');
            var errorMessage      = $('<span class="red-text"><h3>Гости не могут оставлять отзывы.</h3></span>');
            var successMessage    = $('<span class="green-text"><h3>Ваш отзыв успешно добавлен.</h3></span>');

            if (creatorId === 'quest') {
                $('#rating-message').text('');
                $('#rating-message').append(errorMessage);
            } else {
                $.ajax({
                    type    : 'POST',
                    url     : $btn.attr('data-href'),
                    data    : {
                        '_csrf'              : csrfToken,
                        'product_rating'     : productRating,
                        'operator_rating'    : operatorRating,
                        'reliability_rating' : reliabilityRating,
                        'marker_rating'      : markerRating,
                        'created_by'         : creatorId,
                        'shop_id'            : recipientId
                    },
                    success : function () {
                        $('#rating-message').text('');
                        $('#rating-message').append(successMessage);
                    },
                    error : function (response) {
                        errorMessage = $('<span class="red-text"><h3>Возникла ошибка. Отзыв не добавлен.</h3></span>');
                        $('#rating-message').text('');
                        $('#rating-message').append(errorMessage);
                        // alert('Error! ' + JSON.stringify(response, null, 4));
                    }
                });
            }


        }
    };
    review.init();
});