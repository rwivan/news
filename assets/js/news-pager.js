
var newsPagerWidget = {
    init: function (options) {
        if (options.pageSizeParam == "undefined" || !(options.pageSizeParam)) {
            return;
        }

        if (options.pageParam == "undefined" || !(options.pageParam)) {
            return;
        }

        if (options.url == "undefined" || !(options.url)) {
            return;
        }

        this.bindEvent(options.pageSizeParam, options.pageParam, options.url);
    },
    bindEvent: function (pageSizeParam, pageParam, url) {
        $('select[name="' + pageSizeParam + '"]').on('change', function () {
            var selectedPageSize = $(this).find('option:selected').val();
            var pattern = new RegExp("\((&|\\?)" + pageSizeParam + "=\)\\d+", "gi");
            var newUrl = url + (url.indexOf('?') == -1 ? '?' : '&') + pageSizeParam + '=' + selectedPageSize;
            if (url.match(pattern)) {
                newUrl = url.replace(pattern, "$1"+selectedPageSize);
            }
            window.location.href = newUrl;
        });
    }
};
