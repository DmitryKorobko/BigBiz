$(function () {
    var products = {
        init             : function () {
            this.cacheElements();
            this.bindEvents();
            this.hideBtnShowMore();
        },
        cacheElements    : function () {
            this.$btn_more = $('#btn-more-products');
            this.$container = $("#products-container");
        },
        bindEvents       : function () {
            this.$btn_more.on('click', this.showMoreProducts.bind(this));
        },
        hideBtnShowMore  : function () {
            var limit = this.$btn_more.attr('data-count');
            var count = this.$btn_more.attr('data-all-count');

            if (parseInt(count) <= limit) {
                this.$btn_more.hide();
            }
        },
        showMoreProducts: function () {
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
                    products.displayProducts(response.products, shop);
                },
                error : function (response) {
                    alert('Error! ' + JSON.stringify(response, null, 4));
                }
            });
        },
        displayProducts : function (products, shop) {
            var $container = this.$container;

            $.each(products, function (keyName, product) {
                var productImage = $('<div class="card-image"><img height="232" src="' + product.image +
                    '" alt="pic"></div>');
                var name = $('<span class="card-title">' + product.name + '</span>');
                var hrefWrapper = $('<div class="col s12 m6 l3"></div>');
                var productHref = $('<a href="/main/shop-profile/product?id=' + product.id + '"></a>');
                var cardShopItem = $('<div class="card card-shop-item hoverable"></div>');
                var cardContent = $('<div class="card-content"></div>');
                var numberPlusMinus = $('<ul class="number-plus-minus"></ul>');
                var productRating = $('<ul class="rating-list-big flex-box"></ul>');
                var yellowStar = '<li class="yellow-text"><i class="fa fa-star" aria-hidden="true"></i></li>';
                var greyStar = '<li><i class="fa fa-star" aria-hidden="true"></i></li>';
                var productPrice = $('<li class="price-box center-align"><span class="price green-text">'
                    + product.price + ' UAH</span></li>');
                var isAvailable = $('<li class="center-align"><span class="border on">в наличии</span></li>');
                var isNotAvailable = $('<li class="center-align"><span class="border off red-bg">нет в наличии</span>' +
                    '</li>');
                var starRating = '';

                cardContent.append(name);

                for (var i = 0; i < Math.round(product.average_rating); i++) {
                    starRating += yellowStar;
                }

                for (i = 0; i < (5 - Math.round(product.average_rating)); i++) {
                    starRating += greyStar;
                }

                starRating = $('' + starRating + '');
                productRating.append(starRating);
                numberPlusMinus.append(productRating);
                numberPlusMinus.append(productPrice);

                if (product.availability === true) {
                    numberPlusMinus.append(isAvailable);
                } else {
                    numberPlusMinus.append(isNotAvailable);
                }

                cardContent.append(numberPlusMinus);
                cardShopItem.append(productImage);
                cardShopItem.append(cardContent);
                productHref.append(cardShopItem);
                hrefWrapper.append(productHref);
                $container.append(hrefWrapper);
            });
        }
    };
    products.init();
});