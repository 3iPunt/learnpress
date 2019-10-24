;(function ($) {
    const {debounce} = lodash;

    const fetchCourses = function (args) {
        var url = args.url || lpGlobalSettings.courses_url;
        var $wrapElement = args.wrapElement || '#lp-archive-courses';

        delete args.url;
        delete args.wrapElement;

        LP.setUrl(url);

        return new Promise((resolve, reject) => {
            $.ajax({
                url: url,
                data: $.extend({}, args || {}),
                type: 'post',
                success: (response) => {
                    var newEl = $(response).contents().find($wrapElement);

                    if (newEl.length) {
                        $($wrapElement).replaceWith(newEl)
                    } else {
                        $($wrapElement).html('')
                    }

                    bindEventCoursesLayout();
                    $.scrollTo($wrapElement);
                    resolve(newEl);
                },
                error: (response) => {
                    reject();
                }
            });
        })
    }

    /**
     * Ajax searching when user typing on search-box.
     *
     * @param event
     */
    const searchCourseHandler = debounce(function (event) {
        event.preventDefault();

        fetchCourses({
            s: $(this).find('input[name="s"]').val()
        });
    });

    /**
     * Switch layout between Grid and List.
     *
     * @param event
     */
    const switchCoursesLayoutHandler = function (event) {
        var $target;
        var $parent = $(this).parent();

        while (!$target || !$target.length) {
            $target = $parent.find('.learn-press-courses');
            $parent = $parent.parent();
        }

        $target.attr('data-layout', this.value);
        LP.Cookies.set('courses-layout', this.value);
    };

    const selectCoursesLayout = function () {
        var coursesLayout = LP.Cookies.get('courses-layout');
        var switches = $('.lp-courses-bar .switch-layout')
            .find('[name="lp-switch-layout-btn"]');

        if (coursesLayout) {
            switches
                .filter('[value="' + coursesLayout + '"]')
                .prop('checked', true)
                .trigger('change');
        }
    };

    const coursePaginationHandler = function (event) {
        event.preventDefault();

        var permalink = $(event.target).attr('href');

        if (!permalink) {
            return;
        }

        fetchCourses({
            url: permalink.addQueryVar('s', $('.search-courses input[name="s"]').val())
        })
    };

    const bindEventCoursesLayout = function () {
        $('#lp-archive-courses')
            .on('keyup', '.search-courses input[name="s"]', searchCourseHandler)
            .on('change', 'input[name="lp-switch-layout-btn"]', switchCoursesLayoutHandler)
            .on('click', '.learn-press-pagination .page-numbers', coursePaginationHandler);
    }

    $(document).ready(function () {
        bindEventCoursesLayout();

        //
        selectCoursesLayout();
    })

})(jQuery);