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

            if (opts.hoverable_rows) {
                // alert('pass');
                jQuery(_thisContainer).find('.table .tr').addClass('hoverable');
                jQuery(_thisContainer)
                    .find('.table .tr .td')
                    .addClass('hoverable');
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
        var th_visible = $(tableObjContainer)
            .find('.table .thead .tr')
            .first()
            .find('.th:visible');

        // console.log(th_visible);

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
            var td_visible = $(this).find('.td:visible');
            td_visible.each(function (subTableObjIndex) {
                if (jQuery(this).attr('colspan') == undefined) {
                    jQuery(this).attr('colspan', 1);
                }
                var max_colspan =
                    jQuery(this).attr('colspan') > th_visible.length
                        ? th_visible.length
                        : jQuery(this).attr('colspan');

                var colspan_ctr = 1;

                var maxWidth = 0;

                for (var i = subTableObjIndex; i < arrOptsWidth.length; i++) {
                    if (colspan_ctr <= max_colspan) {
                        maxWidth += arrOptsWidth[i];
                    } else {
                        break;
                    }

                    colspan_ctr++;
                }

                jQuery(this).css('width', maxWidth);
            });
        });
        
        if (
            tableObjContainer.outerWidth() >
            // tableObjContainer.find('.tr.flexthis:first').outerWidth()
            tableObjContainer.find('.tr.flexthis').first().outerWidth()
        ) {
            var _width_diff =
                tableObjContainer.outerWidth() -
                // tableObjContainer.find('.tr.flexthis:first').outerWidth();
                tableObjContainer.find('.tr.flexthis').first().outerWidth();

            var _last_th_width = tableObjContainer
                .find('.tr .th:visible:last-child')
                .outerWidth();

            tableObjContainer.find('.thead .tr').each(function () {
                jQuery(this)
                    .find('.th:visible')
                    .last()
                    .css('width', _width_diff + _last_th_width);
            });

            tableObjContainer.find('.tbody .tr').each(function () {
                var last_td_visible = jQuery(this).find('.td:visible').last();
                var length_td_visible =
                    jQuery(this).find('.td:visible').length - 1;

                if (last_td_visible.attr('colspan') > 1) {
                    var last_td_width = 0;

                    tableObjContainer
                        .find('.thead .tr')
                        .first()
                        .find('.th:visible')
                        .each(function (th_index) {
                            if (th_index >= length_td_visible) {
                                last_td_width += jQuery(this).outerWidth();
                            }
                        });

                    last_td_visible.css('width', last_td_width);
                } else {
                    last_td_visible.css('width', _width_diff + _last_th_width);
                }
            });
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
            var fix_col_index_offset = value;

            // console.log(fix_col_index_offset,index,value);
            // console.log($(tableObjContainer).find('.table .thead .tr .th'));
            // console.log($(tableObjContainer).find('.table .thead .tr .th:nth-child('+fix_col_index_offset+')').get(0));

            // $(tableObjContainer).find('.table .thead .tr .th:visible:nth-child('+fix_col_index_offset+')')
            // 				   .addClass('fixedcol')
            // 				   .css('left',fixedcol_left)
            // 				   .css('right',fixedcol_right);

            jQuery(tableObjContainer)
                .find('.table .thead .tr .th:visible')
                .eq(fix_col_index_offset)
                .addClass('fixedcol')
                .css('left', fixedcol_left)
                .css('right', fixedcol_right);

            if (fixed_position == 'left') {
                $(tableObjContainer)
                    .find('.table .thead .tr .th:visible')
                    .eq(fix_col_index_offset)
                    .addClass('fixedcol_left');
            } else {
                $(tableObjContainer)
                    .find('.table .thead .tr .th:visible')
                    .eq(fix_col_index_offset)
                    .addClass('fixedcol_right');
            }

            $(tableObjContainer)
                .find('.table .tbody .tr')
                .each(function () {
                    jQuery(this)
                        .find('.td:visible')
                        .eq(value)
                        .addClass('fixedcol')
                        .css('left', fixedcol_left)
                        .css('right', fixedcol_right); //for style display none

                    // jQuery(this).find('.td,.hidden_td').eq(value).addClass('fixedcol')
                    // 			   .css('left',fixedcol_left)
                    // 			   .css('right',fixedcol_right); //for class hidden_td

                    if (fixed_position == 'left') {
                        jQuery(this)
                            .find('.td:visible')
                            .eq(value)
                            .addClass('fixedcol_left');
                        // jQuery(this).find('.td,.hidden_td').eq(value).addClass('fixedcol_left pager_action_btn_container');
                    } else {
                        jQuery(this)
                            .find('.td:visible')
                            .eq(value)
                            .addClass('fixedcol_right');
                        // jQuery(this).find('.td,.hidden_td').eq(value).addClass('fixedcol_right pager_action_btn_container');
                    }

                    var nothing = '';
                    nothing = jQuery(this).find('.td:visible').eq(value).text();

                    if (nothing != '') {
                        // console.log('nothing false');
                        // console.log("header_width",jQuery(this).find('.td:visible').eq(value).closest('.pager_tbody').siblings('.pager_thead').find('.pager_tr').width());
                        // var header_width = jQuery(this).find('.td:visible').eq(value).closest('.pager_tbody').siblings('.pager_thead').find('.pager_tr').width();
                        // jQuery(this).find('.td:visible').eq(value).width(header_width-10);
                        // jQuery(this).find('.td:visible').eq(value).removeClass('fixedcol fixedcol_left fixedcol_right pager_action_btn_container');
                        // jQuery(this).find('.td:visible').eq(value).addClass('pager_action_btn_container');
                    } else {
                        // console.log('nothing true');
                    }
                });

            if (fixed_position == 'left') {
                fixedcol_left = fixedcol_left + column_width[value];
            } else {
                fixedcol_right = fixedcol_right + column_width[value];
            }
        });
    }

    // Plugin defaults – added as a property on our plugin function.
    $.fn.lst_freeze_table.defaults = {
        column_width: [],
        left_fixed_column_index: [],
        right_fixed_column_index: [],
        tbl_max_width: 'auto',
        tbl_max_height: 'auto',
        hoverable_rows: true,
    };
})(jQuery);
