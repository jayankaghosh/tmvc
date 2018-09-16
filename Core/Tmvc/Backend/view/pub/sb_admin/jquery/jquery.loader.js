(function ($) {
    $(document).ready(function () {
        var loader = $('<div><div></div></div>');
        loader.css({
            "display": "flex",
            "position": "absolute",
            "top": "0",
            "bottom": "0",
            "right": "0",
            "left": "0",
            "overflow": "hidden",
            "z-index": "999999",
            "background": "rgba(0, 0, 0, 0.3)"
        });
        loader.find('div').css({
            "margin": "auto",
            "border": "10px solid #f3f3f3",
            "border-top": "10px solid #212529",
            "border-radius": "50%",
            "width": "80px",
            "height": "80px",
            "display":"inline",
            "animation": "spin 1s linear infinite"
        });
        loader.hide();
        $('body').append(loader);
        $('body').append('<style>' +
            '@keyframes spin {\n' +
            '    0% { transform: rotate(0deg); }\n' +
            '    100% { transform: rotate(360deg); }\n' +
            '}'+
            '</style>');
        $.loader = function (state) {
            if (state === "hide") {
                loader.hide();
            } else {
                loader.show();
            }
        }
    });
})(window.jQuery);