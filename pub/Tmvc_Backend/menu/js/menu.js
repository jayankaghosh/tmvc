(function ($) {
    $.fn.extend({
        menu: function (options) {
            options = $.extend({
                initialState: ""
            }, options);
            var menu = $(this);
            var hideChildren = function () {
                menu.find('.child').hide();
            };
            var fetchSection = function(sectionUrl, pushState) {
                $.loader('show');
                $.get(sectionUrl.replace('isAjax=false', 'isAjax=true'), function (data) {
                    if (data.status) {
                        $('#content').html(data.message);
                        if (pushState && window.location.href !== sectionUrl) {
                            window.history.pushState({urlPath: sectionUrl}, "", sectionUrl);
                        }
                    }
                    $.loader('hide');
                });
            };
            $(window).on("popstate", function (e) {
                if (e.originalEvent.state) {
                    fetchSection(e.originalEvent.state.urlPath);
                } else {
                    fetchSection(options.initialState);
                }
            });
            menu.find('li a.nav-link').on('click', function (e) {
                e.preventDefault();
                if ($(this).attr("href") === "#") {
                    var items = menu.find('li.nav-item');
                    var item = $(this).closest('li.nav-item');
                    var child = item.find('.child.level-' + item.data('level'));
                    child.slideToggle(250, "swing");
                    items.removeClass('active');
                    item.addClass('active');
                } else {
                    fetchSection($(this).attr("href"), true);
                }
            });
            hideChildren();
        }
    });
})(window.jQuery);