$(document).ready(function () {
    history.scrollRestoration = "manual";

    var limit = 8;
    var start = 0;
    var action = "inactive";

    if (window.location.pathname == "/search") {
        var url = "shop/searchProduct" + window.location.search;
    } else {
        var url = "shop/getProducts";
    }

    function lazzy_loader(limit) {
        var output = "";
        output +=
            '<p><span class="content-placeholder" style="width:100%; height: 60px;">Loading Data..</span></p>';
        $(".load_data_message").html(output);
    }

    lazzy_loader(limit);

    function load_data(limit, start) {
        $.ajax({
            url: base_url + url,
            method: "POST",
            data: {
                limit: limit,
                start: start
            },
            cache: false,
            dataType: "json",
            success: function (response) {
                if (response.status == "failed") {
                    $("#total_rows").html(response.total);
                    $(".load_data_message").html('<center><h4 class="mt-5">No More Result Found</h4></center>');
                    action = "active";
                } else {
                    $("#total_rows").html(response.total);
                    $("#load_data1").append(response.data1);
                    $("#load_data2").append(response.data2);
                    $(".load_data_message").html("");
                    action = "inactive";
                }
            },
        });
    }

    if (action == "inactive") {
        action = "active";
        load_data(limit, start);
    }

    $(window).scroll(function () {
        if ($(window).scrollTop() > $(document).height() - $(window).height() - 300 && action == "inactive") {
            lazzy_loader(limit);
            action = "active";
            start = start + limit;
            setTimeout(function () {
                load_data(limit, start);
            }, 1000);
        }
    });
});

$(".sort select").on("change", function () {
    var val = $(this).val();
    var query = window.location.search;
    if (val) {
        if (query) {
            if (query.indexOf("sortby") >= 0) {
                var url = query.split("sortby")[0]; // remove everything after &sortby..
                window.location = url + val;
            } else {
                window.location = query + "&" + val;
            }
        } else {
            window.location = base_url + "search?" + val;
        }
    }
    return false;
});