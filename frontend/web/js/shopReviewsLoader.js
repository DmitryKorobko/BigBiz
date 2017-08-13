$(function () {
    var reviews = {
        init             : function () {
            this.cacheElements();
            this.bindEvents();
            this.hideBtnShowMore();
        },
        cacheElements    : function () {
            this.$btn_more = $('#btn-more-reviews');
            this.$container = $("#reviews-container");
        },
        bindEvents       : function () {
            this.$btn_more.on('click', this.showMoreReviews.bind(this));
        },
        hideBtnShowMore  : function () {
            var limit = this.$btn_more.attr('data-count');
            var count = this.$btn_more.attr('data-all-count');

            if (parseInt(count) <= limit) {
                this.$btn_more.hide();
            }
        },
        showMoreReviews: function () {
            var $btn = this.$btn_more;
            var limit = $btn.attr('data-count');
            var shop = $btn.attr('data-shop-id');
            var csrfToken = $('meta[name="csrf-token"]').attr("content");

            $.ajax({
                type    : 'POST',
                dataType: "json",
                url     : $btn.attr('data-href'),
                data    : {
                    '_csrf' : csrfToken,
                    'shop'  : shop,
                    'limit' : limit
                },
                success : function (response) {
                    var count = $btn.attr('data-all-count');
                    if (parseInt(count) <= parseInt(response.limit)) {
                        $btn.hide();
                    }
                    $btn.attr('data-count', response.limit);
                    reviews.displayReviews(response.reviews, shop);
                },
                error : function (response) {
                    alert('Error! ' + JSON.stringify(response, null, 4));
                }
            });
        },
        displayReviews : function (reviews, shop) {
            var $container = this.$container;

            $.each(reviews, function (keyName, review) {
                var shopReviewItem = $('<li class="shop-review-item"></li>');
                var collapsibleHeader = $('<div class="collapsible-header flex-box"></div>');
                var collapsibleBody = $('<div class="collapsible-body"></div>');
                var author = $('<div class="author">');
                var authorHref = $('<a href="/main/user-profile?id=' + review.creator.id + '">');
                var authorOnlinePhoto = $('<div class="author-photo left online-sm"><img src="' +
                    review.creator.avatar + '" alt="User avatar" class="circle comment-author-avatar"></div>');
                var authorOfflinePhoto = $('<div class="author-photo left offline-sm"><img src="' +
                    review.creator.avatar + '" alt="User avatar" class="circle comment-author-avatar"></div>');
                var authorName = $('<div class="author-name"><span>' + review.creator.name + '</span></div>');
                var reviewDate = $('<div class="review-date"><span>' + review.created_at + '</span></div>');
                var bigRating = $('<div class="rating-list-big flex-box"></div>');
                var smallProductRating = $('<div class="rating-list-big flex-box"></div>');
                var smallOperatorRating = $('<div class="rating-list-big flex-box"></div>');
                var smallReliabilityRating = $('<div class="rating-list-big flex-box"></div>');
                var smallMarkerRating = $('<div class="rating-list-big flex-box"></div>');
                var yellowStar = '<span class="shop-star"></span>';
                var greyStar = '<span class="shop-star light-grey-text"></span>';
                var starBigRating = '';
                var starProductRating = '';
                var starOperatorRating = '';
                var starReliabilityRating = '';
                var starMarkerRating = '';
                var productRating = $('<div class="review-rating-small">' +
                    '<span class="review-name">Качество товара</span></div>');
                var operatorRating = $('<div class="review-rating-small">' +
                    '<span class="review-name">Качество работы оператора</span></div>');
                var reliabilityRating = $('<div class="review-rating-small">' +
                    '<span class="review-name">Надежность магазина</span></div>');
                var markerRating = $('<div class="review-rating-small">' +
                    '<span class="review-name">Качество доставки</span></div>');

                if (review.creator.is_online === true) {
                    authorHref.append(authorOnlinePhoto);
                } else {
                    authorHref.append(authorOfflinePhoto);
                }

                author.append(authorHref);
                author.append(authorName);
                author.append(reviewDate);
                collapsibleHeader.append(author);

                for (var i = 0; i < Math.round(review.average_rating); i++) {
                    starBigRating += yellowStar;
                }

                for (i = 0; i < (5 - Math.round(review.average_rating)); i++) {
                    starBigRating += greyStar;
                }

                starBigRating = $('' + starBigRating + '');
                bigRating.append(starBigRating);
                collapsibleHeader.append(bigRating);
                shopReviewItem.append(collapsibleHeader);

                for (var i = 0; i < Math.round(review.product_rating); i++) {
                    starProductRating += yellowStar;
                }

                for (i = 0; i < (5 - Math.round(review.product_rating)); i++) {
                    starProductRating += greyStar;
                }

                for (var i = 0; i < Math.round(review.operator_rating); i++) {
                    starOperatorRating += yellowStar;
                }

                for (i = 0; i < (5 - Math.round(review.operator_rating)); i++) {
                    starOperatorRating += greyStar;
                }

                for (var i = 0; i < Math.round(review.reliability_rating); i++) {
                    starReliabilityRating += yellowStar;
                }

                for (i = 0; i < (5 - Math.round(review.reliability_rating)); i++) {
                    starReliabilityRating += greyStar;
                }

                for (var i = 0; i < Math.round(review.marker_rating); i++) {
                    starMarkerRating += yellowStar;
                }

                for (i = 0; i < (5 - Math.round(review.marker_rating)); i++) {
                    starMarkerRating += greyStar;
                }

                starProductRating = $('' + starProductRating + '');
                starOperatorRating = $('' + starOperatorRating + '');
                starReliabilityRating = $('' + starReliabilityRating + '');
                starMarkerRating = $('' + starMarkerRating + '');
                smallProductRating.append(starProductRating);
                productRating.append(smallProductRating);
                smallOperatorRating.append(starOperatorRating);
                operatorRating.append(smallOperatorRating);
                smallReliabilityRating.append(starReliabilityRating);
                reliabilityRating.append(smallReliabilityRating);
                smallMarkerRating.append(starMarkerRating);
                markerRating.append(smallMarkerRating);
                collapsibleBody.append(productRating);
                collapsibleBody.append(operatorRating);
                collapsibleBody.append(reliabilityRating);
                collapsibleBody.append(markerRating);
                shopReviewItem.append(collapsibleBody);
                $container.append(shopReviewItem);
            });
        }
    };
    reviews.init();
});