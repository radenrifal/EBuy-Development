var API = function() {
	var host = window.document.location.hostname;
	
	var url = function( scheme, method ) {
		return '//' + host + '/api/' + scheme + '/' + method;
	};
	
	var call = function( scheme, method, data, callback ) {
		$.ajax({
            type: "POST",
            data: data,
            url: url( scheme, method ),
            beforeSend: function (){
                $("div#mask").fadeIn();
            },
            success: function( response ){
            	$("div#mask").fadeOut();
                response = $.parseJSON( response );
                if ( typeof( callback ) == "function" ) {
                	return callback( response );
                }
                return response;
            },
            error: function( jqXHR, textStatus, errorThrown ) {
            	$("div#mask").fadeOut();
            	if ( typeof( callback ) == "function" ) {
                	return callback( false );
                }
            	return false;
            }
        });
	};
	
	return {
		get: function( scheme, data, callback ) {
			return call( scheme, 'get', data, callback );
		},
		put: function( scheme, data, callback ) {
			return call( scheme, 'put', data, callback );
		}
	};
}();
