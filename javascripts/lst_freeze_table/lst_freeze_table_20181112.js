(function ($) {
    $.fn.lst_freeze_table = function (options) {
        var opts = $.extend({}, $.fn.lst_freeze_table.defaults, options);

        return this.each(function () {
            var _this = this;

            var _thisContainer;

            if (!jQuery(_this).hasClass('lst_freeze_table')) {
                jQuery(_this).wrap(function () {
                    return $('<div/>').addClass('lst_freeze_table_container');
                });

                _thisContainer = jQuery(_this).closest(
                    '.lst_freeze_table_container'
                );

                convert_element(
                    _thisContainer.find('table'),
                    'lst_freeze_table table'
                );
                convert_element(_thisContainer.find('.table thead'), 'thead');
                convert_element(
                    _thisContainer.find('.table .thead tr'),
                    'tr flexthis'
                );
                convert_element(
                    _thisContainer.find('.table .thead .tr th'),
                    'th'
                );
                convert_element(_thisContainer.find('.table tbody'), 'tbody');
                convert_element(
                    _thisContainer.find('.table .tbody tr'),
                    'tr flexthis'
                );
                convert_element(
                    _thisContainer.find('.table .tbody .tr td'),
                    'td'
                );
            } else {
                _thisContainer = jQuery(_this).closest(
                    '.lst_freeze_table_container'
                );
            }

            _thisContainer.css('width', opts.tbl_max_width);
            _thisContainer.css('height', opts.tbl_max_height);

            if (opts.column_width.length > 0) {
                apply_width(_thisContainer, opts.column_width);
            }

            if (
                opts.left_fixed_column_index.length > 0 &&
                opts.right_fixed_column_index.length > 0 &&
                opts.column_width.length > 0
            ) {
                jQuery(_thisContainer)
                    .find('.table .thead .tr .th')
                    .removeClass('fixedcol');
                jQuery(_thisContainer)
                    .find('.table .tbody .tr .td')
                    .removeClass('fixedcol');

                apply_fixed_column(
                    _thisContainer,
                    opts.left_fixed_column_index,
                    opts.column_width,
                    'left'
                );
                apply_fixed_column(
                    _thisContainer,
                    opts.right_fixed_column_index,
                    opts.column_width,
                    'right'
                );
            }

            //if right only
            if (
                opts.left_fixed_column_index.length <= 0 &&
                opts.right_fixed_column_index.length > 0 &&
                opts.column_width.length > 0
            ) {
                jQuery(_thisContainer)
                    .find('.table .thead .tr .th')
                    .removeClass('fixedcol');
                jQuery(_thisContainer)
                    .find('.table .tbody .tr .td')
                    .removeClass('fixedcol');

                apply_fixed_column(
                    _thisContainer,
                    opts.right_fixed_column_index,
                    opts.column_width,
                    'right'
                );
            }

            //if left only
            if (
                opts.left_fixed_column_index.length > 0 &&
                opts.right_fixed_column_index.length <= 0 &&
                opts.column_width.length > 0
            ) {
                jQuery(_thisContainer)
                    .find('.table .thead .tr .th')
                    .removeClass('fixedcol');
                jQuery(_thisContainer)
                    .find('.table .tbody .tr .td')
                    .removeClass('fixedcol');

                apply_fixed_column(
                    _thisContainer,
                    opts.left_fixed_column_index,
                    opts.column_width,
                    'left'
                );
            }
        });
    };

    function convert_element(tableObj, tableObjClass) {
        jQuery(tableObj).each(function () {
            var attrs = {};

            $.each(jQuery(this)[0].attributes, function (index, attr) {
                attrs[attr.nodeName] = attr.nodeValue;
            });

            return jQuery(this).replaceWith(function () {
                return $('<div />', attrs)
                    .addClass(tableObjClass)
                    .append(jQuery(this).contents());
            });
        });
    }

    function apply_width(tableObjContainer, arrOptsWidth) {
        $(tableObjContainer.find('.table .thead .tr')).each(function (
            tableObjIndex
        ) {
            $(this)
                .find('.th:visible')
                .each(function (subTableObjIndex) {
                    jQuery(this).css('width', arrOptsWidth[subTableObjIndex]);
                });
        });

        $(tableObjContainer.find('.table .tbody .tr')).each(function (
            tableObjIndex
        ) {
            $(this)
                .find('.td:visible')
                .each(function (subTableObjIndex) {
                    jQuery(this).css('width', arrOptsWidth[subTableObjIndex]);
                });
        });

        if (
            tableObjContainer.width() >
            // tableObjContainer.find('.tr.flexthis:first').width()
            tableObjContainer.find('.tr.flexthis').first().width()
        ) {
            var _width_diff =
                tableObjContainer.width() -
                // tableObjContainer.find('.tr.flexthis:first').width();
                tableObjContainer.find('.tr.flexthis').first().width();

            var _last_th_width = tableObjContainer
                .find('.tr .th:last-child')
                .width();

            tableObjContainer
                .find('.tr .th:last-child')
                .css('width', _width_diff + _last_th_width);
            tableObjContainer
                .find('.tr .td:last-child')
                .css('width', _width_diff + _last_th_width);
        }
    }

    function apply_fixed_column(
        tableObjContainer,
        fixed_column_index,
        column_width,
        fixed_position
    ) {
        var fixedcol_left = 0;
        var fixedcol_right = 0;

        if (fixed_position == 'left') {
            //sort fixed_column_index to ascending order
            fixed_column_index.sort(function (a, b) {
                return a - b;
            });
        } else {
            //sort fixed_column_index to descending order
            fixed_column_index.sort(function (a, b) {
                return b - a;
            });
        }

        $.each(fixed_column_index, function (index, value) {
            var fix_col_index_offset = value + 1;
            // console.log(fix_col_index_offset,index,value);
            // console.log($(tableObjContainer).find('.table .thead .tr .th'));
            // console.log($(tableObjContainer).find('.table .thead .tr .th:nth-child('+fix_col_index_offset+')').get(0));

            $(tableObjContainer)
                .find(
                    '.table .thead .tr .th:nth-child(' +
                        fix_col_index_offset +
                        ')'
                )
                .addClass('fixedcol')
                .css('left', fixedcol_left)
                .css('right', fixedcol_right);

            $(tableObjContainer)
                .find('.table .tbody .tr')
                .each(function () {
                    // console.log("td",jQuery(this).find('.td:visible').length);
                    // console.log("td",jQuery(this).find('.td:visible').eq(value).get(0));
                    // console.log("td hidden_td",jQuery(this).find('.td,.hidden_td').eq(value).get(0));

                    jQuery(this)
                        .find('.td:visible')
                        .eq(value)
                        .addClass('fixedcol')
                        .css('left', fixedcol_left)
                        .css('right', fixedcol_right); //for style display none

                    jQuery(this)
                        .find('.td,.hidden_td')
                        .eq(value)
                        .addClass('fixedcol')
                        .css('left', fixedcol_left)
                        .css('right', fixedcol_right); //for class hidden_td

                    if (fixed_position == 'left') {
                        jQuery(this)
                            .find('.td:visible')
                            .eq(value)
                            .addClass('fixedcol_left');
                        jQuery(this)
                            .find('.td,.hidden_td')
                            .eq(value)
                            .addClass(
                                'fixedcol_left pager_action_btn_container'
                            );
                        // jQuery(this).find('.td,.hidden_td').eq(value).addClass('pager_action_btn_container');
                    } else {
                        jQuery(this)
                            .find('.td:visible')
                            .eq(value)
                            .addClass('fixedcol_right');
                        jQuery(this)
                            .find('.td,.hidden_td')
                            .eq(value)
                            .addClass(
                                'fixedcol_right pager_action_btn_container'
                            );
                        // jQuery(this).find('.td,.hidden_td').eq(value).addClass('pager_action_btn_container');
                    }

                    var nothing = '';
                    nothing = jQuery(this).find('.td:visible').eq(value).text();
                    if (nothing != '') {
                        // console.log("header_width",jQuery(this).find('.td:visible').eq(value).closest('.pager_tbody').siblings('.pager_thead').find('.pager_tr').width());
                        var header_width = jQuery(this)
                            .find('.td:visible')
                            .eq(value)
                            .closest('.pager_tbody')
                            .siblings('.pager_thead')
                            .find('.pager_tr')
                            .width();
                        jQuery(this)
                            .find('.td:visible')
                            .eq(value)
                            .width(header_width - 10);
                        jQuery(this)
                            .find('.td:visible')
                            .eq(value)
                            .removeClass(
                                'fixedcol fixedcol_left fixedcol_right pager_action_btn_container'
                            );
                        jQuery(this)
                            .find('.td:visible')
                            .eq(value)
                            .addClass('pager_action_btn_container');
                    }
                });

            if (fixed_position == 'left') {
                fixedcol_left = fixedcol_left + column_width[value];
            } else {
                fixedcol_right = fixedcol_right + column_width[value];
            }

            // $(tableObjContainer).find('.table .tbody .tr .td:nth-child('+fix_col_index_offset+')')
            // 				   .addClass('fixedcol')
            // 				   .css('left',fixedcol_left)
            // 				   .css('right',fixedcol_right);
        });
    }

    // Plugin defaults – added as a property on our plugin function.
    $.fn.lst_freeze_table.defaults = {
        column_width: [],
        left_fixed_column_index: [],
        right_fixed_column_index: [],
        tbl_max_width: 'auto',
        tbl_max_height: 'auto',
    };
})(jQuery);
