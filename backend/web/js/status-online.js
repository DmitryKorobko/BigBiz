$(function () {

    var online, roleUrl;
    var form = {
        init             : function () {
            this.cacheElements();
            this.bindEvents();
        },
        cacheElements    : function () {
            this.$status = $('#status-online');
        },
        bindEvents       : function () {
            this.$status.on('switchChange.bootstrapSwitch', this.changeStatus.bind(this));
        },
        changeStatus         : function (e) {
            e.preventDefault();

            if ($('.bootstrap-switch-wrapper').hasClass('bootstrap-switch-off')) {
                online = 0;
                $('#status-message')
                    .addClass('alert alert-success alert-dismissible fade in').text('Статус успешно изменён.');
                $('#status-message').append("<i class='fa fa-check'></i>");
                setTimeout(function(){
                    $('#status-message').removeClass('alert alert-success alert-dismissible fade in').text('');
                }, 3000);
            } else {
                online = 1;
                $('#status-message')
                    .addClass('alert alert-success alert-dismissible fade in').text('Статус успешно изменён.');
                $('#status-message').append("<i class='fa fa-check'></i>");
                setTimeout(function(){
                    $('#status-message').removeClass('alert alert-success alert-dismissible fade in').text('');
                }, 3000);
            }

            if ($('.status-online-container').hasClass('admin')) {
                roleUrl = '/admin/manage/home/dashboard/update-status-online';
            } else if ($('.status-online-container').hasClass('moder')) {
                roleUrl = '/admin/moderator/home/dashboard/update-status-online';
            } else if ($('.status-online-container').hasClass('shop')) {
                roleUrl = '/admin/shop/profile/shop-profile/update-status-online';
            }

            $.ajax({
                type: 'POST',
                url: roleUrl,
                data: {
                    status_online: online
                }
            });
        }
    };
    form.init();
});
