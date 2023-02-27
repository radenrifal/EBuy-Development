// =========================================================================
// Global Function
// =========================================================================

// Grid Data
var gridTable = function (el, action = false, target = '', limit = '') {
    var url = el.data('url');
    var grid = new Datatable();
    var tgt = (target != "" ? target : [-1, 0]);
    var lmt = (limit != "" ? limit : 10);

    grid.init({
        src: el,
        onSuccess: function (grid) {
            $('.btn-tooltip').tooltip({ html: true });
        },
        onError: function (grid) { },
        dataTable: {
            "aLengthMenu": [
                [10, 20, 50, 100, -1],
                [10, 20, 50, 100, "All"]                        // change per page values here
            ],
            "iDisplayLength": lmt,                               // default record count per page
            "bServerSide": true,                                // server side processing
            "sAjaxSource": url,       // ajax source
            "aoColumnDefs": [
                { 'bSortable': false, 'aTargets': tgt }
            ]
        }
    });

    grid.getTableWrapper().on('draw', function () {
        $('.btn-tooltip').tooltip({
            html: true
        });
        var _tooltip = $('[data-toggle="tooltip"]');
        if (_tooltip.length) {
            _tooltip.tooltip();
        }
        var _popover = $('[data-toggle="popover"]');
        if (_popover.length) {
            _popover.each(function () {
                ! function (e) {
                    e.data("color") && (a = "popover-" + e.data("color"));
                    var t = {
                        trigger: "focus",
                        template: '<div class="popover ' + a + '" role="tooltip"><div class="arrow"></div><h3 class="popover-header"></h3><div class="popover-body"></div></div>'
                    };
                    _popover.popover(t)
                }($(this))
            });
        }
    });

    if (action) {
        gridExport(grid, '.table-export-excel', url);
    }
}

// Export Grid Data
var gridExport = function (dataTable, selectorBtn, sUrl, sAction, parameter = '') {
    // handle group actionsubmit button click
    dataTable.getTableWrapper().on('click', selectorBtn, function (e) {
        e.preventDefault();

        if (typeof sAction == 'undefined') {
            sAction = 'export_excel';
        }

        var base_url = window.location.origin + '/';
        var params = 'export=' + sAction;
        var table = $(selectorBtn).closest('.table-container').find('table');

        // get all typeable inputs
        $('textarea.form-filter, select.form-filter, input.form-filter:not([type="radio"],[type="checkbox"])', table).each(function () {
            params += '&' + $(this).attr("name") + '=' + $(this).val();
        });

        // get all checkable inputs
        $('input.form-filter[type="checkbox"]:checked, input.form-filter[type="radio"]:checked', table).each(function () {
            params += '&' + $(this).attr("name") + '=' + $(this).val();
        });

        if (parameter) {
            params += '&' + parameter;
        }

        var link_export = sUrl + '?' + params;

        $("div#mask").fadeIn();
        document.location.href = (link_export);
        setTimeout(function () {
            $("div#mask").fadeOut();
            URL.revokeObjectURL(link_export);
        }, 100);
    });
};

// Grid DatePicker
var initPickers = function () {
    //init date pickers
    $('.date-picker').datepicker({
        // rtl: App.isRTL(),
        autoclose: true
    });

    $('.date-picker-month').datepicker({
        // rtl: App.isRTL(),
        autoclose: true,
        viewMode: 'years',
        minViewMode: 'months'
    });
};

// =========================================================================
// Member List Function
// =========================================================================
var TableAjaxMemberList = function () {
    var handleRecordsMemberList = function () {
        gridTable($("#list_table_member"), false);
    };

    var handleRecordsGenerationMemberList = function () {
        gridTable($("#list_table_generation_member"), false);
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsMemberList();
            handleRecordsGenerationMemberList();
        }
    };
}();

// =========================================================================
// Product Manage List Function
// =========================================================================
var TableAjaxProductManageList = function () {
    var handleRecordsProductManageList = function () {
        gridTable($("#list_table_product"), false);
    };

    var handleRecordsProductCategoryList = function () {
        gridTable($("#list_table_category"), false);
    };

    var handleRecordsProductPointList = function () {
        gridTable($("#list_table_product_point"), false);
    };

    return {
        //main function to initiate the module
        init: function () {
            handleRecordsProductManageList();
            handleRecordsProductCategoryList();
            handleRecordsProductPointList();
        }
    };
}();

// =========================================================================
// Promo Code List Function
// =========================================================================
var TableAjaxPromoCodeList = function () {
    var handleRecordsPromoCodeList = function () {
        gridTable($("#list_table_promo_code"), false);
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsPromoCodeList();
        }
    };
}();

// =========================================================================
// Commission List Function
// =========================================================================
var TableAjaxCommissionList = function () {
    var handleRecordsTotalBonusList = function () {
        gridTable($("#list_table_total_bonus"), false);
    };
    var handleRecordsHistoryBonusList = function () {
        gridTable($("#list_table_history_bonus"), false);
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsTotalBonusList();
            handleRecordsHistoryBonusList();
        }
    };
}();

// =========================================================================
// Deposite List Function
// =========================================================================
var TableAjaxDepositeList = function () {
    var handleRecordsDepositeList = function () {
        gridTable($("#list_table_deposite"), false);
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsDepositeList();
        }
    };
}();

// =========================================================================
// Withdraw List Function
// =========================================================================
var TableAjaxWithdrawList = function () {
    var handleRecordsWithdrawList = function () {
        gridTable($("#list_table_withdraw"), true);
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsWithdrawList();
        }
    };
}();

// =========================================================================
// Shop Order Product List Function
// =========================================================================
var TableAjaxShopOrderList = function () {
    var handleRecordsShopOrderList = function () {
        gridTable($("#list_table_shop_order"), false);
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsShopOrderList();
        }
    };
}();

// =========================================================================
// Reward List Function
// =========================================================================
var TableAjaxOmzetList = function () {
    var handleRecordsOmzetPersonalList = function () {
        gridTable($("#list_personal_omzet"), false);
        gridTable($("#list_personal_omzet_total"), false);

        $("body").delegate("#tabs-total_omzet-tab", "click", function () {
            $('#btn_reset_list_personal_omzet_total').trigger('click');
        });

        $("body").delegate("#tabs-history_omzet-tab", "click", function () {
            $('#btn_reset_list_personal_omzet').trigger('click');
        });
    };

    var handleRecordsOmzetDailyList = function () {
        gridTable($("#list_table_omzet_daily"), false);
    };

    var handleRecordsOmzetMonthlyList = function () {
        gridTable($("#list_table_omzet_monthly"), false);
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsOmzetPersonalList();
            handleRecordsOmzetDailyList();
            handleRecordsOmzetMonthlyList();
        }
    };
}();

// =========================================================================
// Reward List Function
// =========================================================================
var TableAjaxRewardList = function () {
    var handleRecordsRewardList = function () {
        gridTable($("#list_table_reward"), false);
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsRewardList();
        }
    };
}();

// =========================================================================
// Commission List Function
// =========================================================================
var TableAjaxCommissionssList = function () {
    var handleRecordsCommissionList = function () {
        var table = $("#commission_list");
        var url = table.data('url');
        var grid = new Datatable();
        grid.addAjaxParam("search_date_commission", $('input[name=search_date_commission]').val());
        grid.init({
            src: table,
            onSuccess: function (grid) { },
            onError: function (grid) { },
            dataTable: {  // here you can define a typical datatable settings from http://datatables.net/usage/options 
                "aLengthMenu": [
                    [10, 20, 50, 100, -1],
                    [10, 20, 50, 100, "All"]                        // change per page values here
                ],
                "iDisplayLength": 10,                               // default record count per page
                "bServerSide": true,                                // server side processing
                "sAjaxSource": url,                                 // ajax source
                "aoColumnDefs": [
                    { 'bSortable': false, 'aTargets': [-1, 0] }
                ]
            }
        });

        grid.getTableWrapper().on('click', '.filter-submit', function (e) {
            e.preventDefault();
            grid.addAjaxParam("search_date_commission", $('input[name=search_date_commission]').val());

            // get all typeable inputs
            $('textarea.form-filter, select.form-filter, input.form-filter:not([type="radio"],[type="checkbox"])').each(function () {
                grid.addAjaxParam($(this).attr("name"), $(this).val());
            });

            grid.getDataTable().fnDraw();
            grid.clearAjaxParams();
        });

        $('.date-picker-commission').datepicker().on('changeDate', function (e) {
            grid.getTableWrapper().find('.filter-submit').click();
        });
    };

    return {
        //main function to initiate the module
        init: function () {
            initPickers();
            handleRecordsCommissionList();
        }
    };
}();

// =========================================================================
// Setting Staff List Function
// =========================================================================
var TableAjaxStaffList = function () {
    var handleRecordsStaffList = function () {
        gridTable($("#list_table_staff"));
    };

    return {
        //main function to initiate the module
        init: function () {
            handleRecordsStaffList();
        }
    };
}();

// =========================================================================
// Setting Notification List Function
// =========================================================================
var TableAjaxNotifList = function () {
    var handleRecordsNotificationList = function () {
        gridTable($("#notification_list"));
    };

    return {
        //main function to initiate the module
        init: function () {
            handleRecordsNotificationList();
        }
    };
}();

// =========================================================================
// Setting Reward List Function
// =========================================================================
var TableAjaxSettingRewardList = function () {
    var handleRecordsSettingRewardList = function () {
        gridTable($("#list_table_setting_reward"));
    };

    return {
        //main function to initiate the module
        init: function () {
            handleRecordsSettingRewardList();
        }
    };
}();

// =========================================================================
// Setting Intro List Function
// =========================================================================
var TableAjaxIntroList = function () {
    var handleRecordsIntroList = function () {
        gridTable($("#list_table_setting_intro"));
    };

    return {
        //main function to initiate the module
        init: function () {
            handleRecordsIntroList();
        }
    };
}();


// =========================================================================
// Product List Function
// =========================================================================
var TableAjaxProductList = function () {
    var handleRecordsProductHistoryList = function () {
        gridTable($("#list_report_history_product"));
    };

    var handleRecordsProductHistoryDetailList = function () {
        gridTable($("#list_report_history_product_detail"));
    };

    var handleRecordsProductUsedList = function () {
        gridTable($("#list_report_product_used"));
    };

    var handleRecordsProductTransferList = function () {
        gridTable($("#list_report_transfer_product"));
    };

    var handleRecordsProductTransferINList = function () {
        gridTable($("#list_report_transfer_product_in"));
    };

    var handleRecordsProductTransferOUTList = function () {
        gridTable($("#list_report_transfer_product_out"));
    };

    return {
        //main function to initiate the module
        init: function () {
            handleRecordsProductHistoryList();
            handleRecordsProductHistoryDetailList();
            handleRecordsProductUsedList();
            handleRecordsProductTransferList();
            handleRecordsProductTransferINList();
            handleRecordsProductTransferOUTList();
        }
    };
}();
