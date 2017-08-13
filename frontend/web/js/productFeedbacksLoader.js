$(function () {
    var reviews = {
        init             : function () {
            this.cacheElements();
            this.bindEvents();
            this.hideBtnShowMore();
        },
        cacheElements    : function () {
            this.$btn_more = $('#btn-more-feedbacks');
            this.$container = $(".feedbacks-container");
        },
        bindEvents       : function () {
            this.$btn_more.on('click', this.showMoreFeedbacks.bind(this));
        },
        hideBtnShowMore  : function () {
            var limit = this.$btn_more.attr('data-count');
            var count = this.$btn_more.attr('data-all-count');

            if (parseInt(count) <= limit) {
                this.$btn_more.hide();
            }
        },
        showMoreFeedbacks: function () {
            var $btn = this.$btn_more;
            var limit = $btn.attr('data-count');
            var product = $btn.attr('data-product-id');
            var csrfToken = $('meta[name="csrf-token"]').attr("content");

            $.ajax({
                type    : 'POST',
                dataType: "json",
                url     : $btn.attr('data-href'),
                data    : {
                    '_csrf'       : csrfToken,
                    'product_id'  : product,
                    'limit'       : limit
                },
                success : function (response) {
                    var count = $btn.attr('data-all-count');
                    if (parseInt(count) <= parseInt(response.limit)) {
                        $btn.hide();
                    }
                    $btn.attr('data-count', response.limit);
                    reviews.displayFeedbacks(response.reviews, product);
                },
                error : function (response) {
                    alert('Error! ' + JSON.stringify(response, null, 4));
                }
            });
        },
        displayFeedbacks : function (reviews, product) {
            var $container = this.$container;
            var userId = this.$container.attr('data-user-id');

            $.each(reviews, function (keyName, review) {
                var mainBlock = $('<div id="com" class="single-comment">');
                var authorBlock = $('<ul class="author-info flex-box">');
                var authorBegin = $('<li></li>');
                var authorOnline = $('<span class="author-photo online-sm"></span>');
                var authorOffline = $('<span class="author-photo offline-sm"></span>');
                var authorPhoto = $('<img style="width: 36px; height: 36px" class="circle" src="' + review.author.avatar + '" alt="user">');
                var authorName = $('<span class="author-name">' + review.author.name +'</span>');
                var authorStarList = $('<ul class="star-list"></ul>');
                var authorProductRating = '';
                var yellowStar = '<li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>';
                var greyStar = '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
                var editFeedbackLinks = $('<li class="comments-links small-links"></li>');
                var deleteFeedback = $('<a class ="comment-delete-btn hide" title="Удалить"><i class="fa fa-trash-o" aria-hidden="true"></i></a>');
                var saveFeedback = $('<a class ="comment-save-btn hide" title="Сохранить"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>');
                var editFeedback = $('<a class ="comment-edit-btn" title="Редактировать"><i class="fa fa-pencil" aria-hidden="true"></i></a>');
                var feedbackDate = $('<span class="date"><i class="fa fa-calendar fa-fw" aria-hidden="true"></i>&nbsp&nbsp' + review.productFeedback.created_at + '</span>');
                var feedbackText = $('<div class="comments-text"><span>' + review.productFeedback.text + '</span></div>');

                for (var i = 0; i < Math.round(review.productFeedback.rating); i++) {
                    authorProductRating += yellowStar;
                }

                for (i = 0; i < (5 - Math.round(review.product_rating)); i++) {
                    authorProductRating += greyStar;
                }

                authorProductRating = $('' + authorProductRating + '');

                if (review.author.status_online === true) {
                    authorOnline.append(authorPhoto);
                    authorBegin.append(authorOnline);
                } else {
                    authorOffline.append(authorPhoto);
                    authorBegin.append(authorOffline);
                }

                authorBegin.append(authorName);
                authorStarList.append(authorProductRating);
                authorBegin.append(authorStarList);
                authorBlock.append(authorBegin);

                if (review.productFeedback.user_id === userId) {
                    editFeedbackLinks.append(deleteFeedback);
                    editFeedbackLinks.append(saveFeedback);
                    editFeedbackLinks.append(editFeedback);
                }

                editFeedbackLinks.append(feedbackDate);
                authorBlock.append(editFeedbackLinks);
                mainBlock.append(authorBlock);
                mainBlock.append(feedbackText);
                $container.append(mainBlock);
            });
        }
    };
    reviews.init();
});