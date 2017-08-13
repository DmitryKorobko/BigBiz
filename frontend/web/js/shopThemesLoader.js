$(function () {
    var themes = {
        init             : function () {
            this.cacheElements();
            this.bindEvents();
            this.hideBtnShowMore();
        },
        cacheElements    : function () {
            this.$btn_more = $('#btn-more-themes');
            this.$container = $("#themes-container");
        },
        bindEvents       : function () {
            this.$btn_more.on('click', this.showMoreThemes.bind(this));
        },
        hideBtnShowMore  : function () {
            var limit = this.$btn_more.attr('data-count');
            var count = this.$btn_more.attr('data-all-count');

            if (parseInt(count) <= limit) {
                this.$btn_more.hide();
            }
        },
        showMoreThemes: function () {
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
                    themes.displayThemes(response.themes, shop);
                },
                error : function (response) {
                    alert('Error! ' + JSON.stringify(response, null, 4));
                }
            });
        },
        displayThemes : function (themes, shop) {
            var $container = this.$container;

            $.each(themes, function (keyName, theme) {
                var themeImage = $('<div class="card-image"><img height="300px" class="activator" src="' + theme.image +
                    '" alt="pic"></div>');
                var name = $('<span class="card-title">' + theme.name + '</span>');
                var dateOfPublication = $('<li><i class="fa fa-calendar fa-fw" aria-hidden="true"></i>' +
                    '<span class="post-data">' + theme.date_of_publication + '</span></li>');
                var viewCount = $('<li><i class="fa fa-eye fa-fw" aria-hidden="true"></i>' +
                    '<span class="post-viewer">' + theme.view_count + '</span></li>');
                var commentsCount = $('<li><i class="fa fa-comments fa-fw" aria-hidden="true"></i>' +
                    '<span class="post-comments">' + theme.comments_count + '</span></li>');
                var countLike = $('<li><i class="fa fa-thumbs-o-up fa-fw" aria-hidden="true"></i>' +
                    '<span class="post-like">' + theme.count_like + '</span></li>');
                var countDislike = $('<li><i class="fa fa-thumbs-o-down fa-fw" aria-hidden="true"></i>' +
                    '<span class="post-dislike">' + theme.count_dislike + '</span></li>');
                var themeHref = $('<div class="card-action center-align">' +
                    '<a href="/main/shop-profile/theme?id=' + theme.id + '" ' +
                    'class="waves-effect waves-green btn white read-more-btn">Подробнее</a></div>');
                var postLinks = $('<ul class="post-links"></ul>');
                var cardContent = $('<div class="card-content"></div>');
                var cardBox = $('<div class="card card-post z-depth-3"></div>');
                var themeBox = $('<div class="col s12 m6 l4"></div>');
                var themeNameBox = $('<div class="theme-name-box"></div>');
                var firstThemeName = $('<span class="first-theme-name">' + theme.main_category_name + ' /</span>');
                var themeName = $('<a class="green-text" href="/main/category?id=' + theme.category_id + '">' +
                    '<span class="theme-name"> ' + theme.category_name + '</span></a>');

                postLinks.append(dateOfPublication);
                postLinks.append(viewCount);
                postLinks.append(commentsCount);
                postLinks.append(countLike);
                postLinks.append(countDislike);
                themeNameBox.append(firstThemeName);
                themeNameBox.append(themeName);
                cardContent.append(themeNameBox);
                cardContent.append(name);
                cardContent.append(postLinks);
                cardBox.append(themeImage);
                cardBox.append(cardContent);
                cardBox.append(themeHref);
                themeBox.append(cardBox);
                $container.append(themeBox);
            });
        }
    };
    themes.init();
});