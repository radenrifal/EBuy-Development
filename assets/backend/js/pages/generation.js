var GenerationMember = function() {

    var grid_list   = $('#member_gen_lists');
    var url_load    = $('#data_tree').data('url');

    // Handle Datatable Generation Member
    var handleDataGenerationMember = function(){
        var url             = grid_list.data( 'url' );
        var grid            = new Datatable();

        grid.init({
            src: grid_list,
            onSuccess: function(grid) {},
            onError: function(grid) {},
            dataTable: {  // here you can define a typical datatable settings from http://datatables.net/usage/options
                "aLengthMenu": [
                    [10, 20, 50, 100, -1],
                    [10, 20, 50, 100, "All"]                        // change per page values here
                ],
                "iDisplayLength": 10,                               // default record count per page
                "bServerSide": true,                                // server side processing
                "sAjaxSource": url,      // ajax source
                "aoColumnDefs": [
                  { 'bSortable': false, 'aTargets': [ -1, 0 ] },
                ]
            }
        });

        grid.getTableWrapper().on('click', '.filter-clear', function(e){
            e.preventDefault();
            $('textarea.form-filter, select.form-filter, input.form-filter').each(function(){
                if ( $(this).attr("name") !== 'search_sponsor') {
                    $(this).val("");
                }
            });
            $('input.form-filter[type="checkbox"]').each(function(){
                $(this).attr("checked", false);
            });   

            grid.getDataTable().fnDraw();
            grid.clearAjaxParams();
        });
    };

    //show all data package function
    var handleDataGenerationTree = function(){
        $("#data_tree")
        .on("changed.jstree", function (e, data) {
            if(data.selected.length) {
                var id_node     = data.selected[0];
                var info_node   = data.instance.get_node(data.selected[0]).text;  
                $( '#member-gen' ).html(' : ' + info_node);
                $( 'input[name=search_sponsor]', grid_list ).val(id_node);
                $( '#btn_member_gen_lists' ).trigger('click');
            }
        })
        .jstree({    
           "plugins" : ["themes", "json_data", "ui"],
            core:{
                data: {
                    "type": 'POST',
                    "dataType" : "json",
                    "url": function (node) {
                        var _url = "";
                        if ( node.id == '#' ) {
                            _url = url_load;
                        } else {
                            _url = url_load + "/" + node.id;
                            $( '#member-gen' ).html(' : ' + node.text);
                            $( 'input[name=search_sponsor]', grid_list ).val(node.id);
                            $( '#btn_member_gen_lists' ).trigger('click');
                        }
                        return _url;
                    },
                    data: function(response){
                        return response;
                    },
                    "success": function (new_data) {
                        return new_data;
                    }
                }
            },
        });
    };

    return {
        init: function() {
            handleDataGenerationMember();
            handleDataGenerationTree();
        }
    };
}();
    