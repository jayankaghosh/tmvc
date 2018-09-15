(function ($) {
    $.fn.extend({
        menu: function (options) {
            var menu = $(this);
            var hideChildren = function () {
                menu.find('.child').hide();
            };
            menu.find('li a.nav-link').on('click', function () {
                var items = menu.find('li.nav-item');
                var item = $(this).closest('li.nav-item');
                var child = item.find('.child.level-'+item.data('level'));
                child.slideToggle(250, "swing");
                items.removeClass('active');
                item.addClass('active');
            });
            hideChildren();
        }
    });
})(window.jQuery);