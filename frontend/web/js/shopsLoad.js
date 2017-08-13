$(function () {
    var shops = {

        init             : function () {
            this.cacheElements();
            this.bindEvents();
            this.hideBtnShowMore();
            this.hideUnwantedShops();
        },

        cacheElements    : function () {
            this.$btn_more = $('#btn-more-shops');
            this.$container = $("#shops-container");
            this.count_load = $('#btn-more-shops').attr('data-load-count');
        },

        bindEvents       : function () {
            this.$btn_more.on('click', this.showMoreShops.bind(this));
        },

        hideBtnShowMore  : function () {
            var limit = this.$btn_more.attr('data-count');
            var count = this.$btn_more.attr('data-all-count');

            if (parseInt(count) <= limit) {
                this.$btn_more.hide();
            }
        },

        hideUnwantedShops  : function () {
            var shopsCount = this.$btn_more.attr('data-count');
            $('.lonely-shop').each(function() {
                if ($(this).attr('data-shop-number') > shopsCount) {
                    $(this).hide();
                }
            });
        },

        showMoreShops: function () {
            var shopsCount =  parseInt(this.$btn_more.attr('data-count')) +  parseInt(this.count_load);
            this.$btn_more.attr('data-count', shopsCount);
            $('.lonely-shop').each(function() {
                if ($(this).attr('data-shop-number') <= shopsCount) {
                    $(this).show();
                }
            });

            this.hideBtnShowMore();
        }
    };

    shops.init();
});