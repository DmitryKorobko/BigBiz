$(function () {
    var feedback = {
        init             : function () {
            this.cacheElements();
            this.bindEvents();
        },
        cacheElements    : function () {
            this.$btn_save = $('#save-product-feedback');
        },
        bindEvents       : function () {
            this.$btn_save.on('click', this.saveShopReview.bind(this));
        },
        saveShopReview   : function () {
            var $btn              = this.$btn_save;
            var csrfToken         = $('meta[name="csrf-token"]').attr("content");
            var productRating     = $('#product-rating').children('.fa-star').length;
            var creatorId         = this.$btn_save.attr('data-feedback-creator-id');
            var productId         = this.$btn_save.attr('data-feedback-product-id');
            var textFeedback      = $('#comment-text-field').text();
            var errorMessage      = $('<span class="red-text"><h3>Гости не могут оставлять отзывы.</h3></span>');
            var successMessage    = $('<span class="green-text"><h3>Ваш отзыв успешно добавлен.</h3></span>');

            if (creatorId === 'quest') {
                $('#rating-message').text('');
                $('#rating-message').append(errorMessage);
                $('.modal-close').click();
            } else {
                $.ajax({
                    type    : 'POST',
                    url     : $btn.attr('data-href'),
                    data    : {
                        '_csrf'              : csrfToken,
                        'product_rating'     : productRating,
                        'user_id'            : creatorId,
                        'product_id'         : productId,
                        'text'               : textFeedback
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

                $('.modal-close').click();
            }


        }
    };
    feedback.init();
});