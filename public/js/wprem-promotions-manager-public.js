(function($) {
    'use strict';
    function promos() {
        setTimeout(function() {
            $(".wprem-promotions").each(function() {
                var h = 0;
                var target = $(".col-sm-6", this);
                $(target).css("height", "auto");
                target.each(function() {
                    if ($(this).outerHeight() > h) {
                        h = $(this).outerHeight();
                    }
                });
                var btn = parseInt(($(".col-sm-6 .wprem-btn", this).outerHeight() / 2)) * -1;
                $(".col-sm-6 .wprem-btn", this).css("margin-top", btn+"px");
                target.height(h);
            });
        }, 150);
    }
    promos();
    $(window).resize(function() {
        promos();
    });
})(jQuery);