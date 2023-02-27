/*
|--------------------------------------------------------------------------
| Complete reset form
|--------------------------------------------------------------------------
*/
function resetForm() {
	$("form").each(function () {
		this.reset();
		$("input[type=hidden]").val("");
		$("input[type=file]").val("");
		//$("input").attr("readonly", false);
		$("textarea").val("");
		$("select").prop("selectedIndex", 0);
		$("select").empty();
		$(".form-group").removeClass("error");
		$(".help-block").empty();
		$(".hide-show").hide();
		$("select.select2").val(null).trigger("change");
		$("#temp-image").hide();
	});
}

// reset ckeditor
$(".addData").on("click", function (e) {
	if ($(CKEDITOR.instances).length) {
		for (var key in CKEDITOR.instances) {
			var instance = CKEDITOR.instances[key];
			if (
				$(instance.element.$).closest("form").attr("name") ==
				$(e.target).attr("name")
			) {
				instance.setData(instance.element.$.defaultValue);
			}
		}
	}
});

/*
|--------------------------------------------------------------------------
| Reload Datatables
|--------------------------------------------------------------------------
*/
function reloadDatatable() {
	dTable.ajax.reload(null, false);
}

/*
|--------------------------------------------------------------------------
| Sweetalert
|--------------------------------------------------------------------------
*/
function swalert(method) {
	swal("Success", "Data berhasil " + method, "success");
}

/*
|--------------------------------------------------------------------------
| Get only youtube ID | https://www.youtube.com/watch?v=XXXXX(ID)
|--------------------------------------------------------------------------
*/
function youtubeId(url) {
	var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
	var match = url.match(regExp);

	if (match && match[2].length == 11) {
		return match[2];
	} else {
		return "error";
	}
}

/*
|--------------------------------------------------------------------------
| Call php function on js | Encrypt Parameter
|--------------------------------------------------------------------------
*/
function encryptParam(val) {
	var response = null;

	$.ajax({
		type: "POST",
		dataType: "json",
		async: false,
		url: base_url + "general/encryptParam/" + val,
		success: function (result) {
			response = result.data;
		},
	});
	return response;
}

/*
|--------------------------------------------------------------------------
| Call php function on js | Decrypt Parameter
|--------------------------------------------------------------------------
*/
function decryptParam(val) {
	var response = null;

	$.ajax({
		type: "POST",
		dataType: "json",
		async: false,
		url: base_url + "general/decryptParam/" + val,
		success: function (result) {
			console.log.result;
			response = result.data;
		},
	});
	return response;
}

/*
|--------------------------------------------------------------------------
| Call php function on js | Get Category name
|--------------------------------------------------------------------------
*/
function getCategory(val) {
	var response = null;

	$.ajax({
		type: "POST",
		dataType: "json",
		async: false,
		url: base_url + "general/getCategory/" + val,
		success: function (result) {
			response = result.data;
		},
	});
	return response;
}

/*
|--------------------------------------------------------------------------
| Number Formating
|--------------------------------------------------------------------------
*/
function numberFormat(x) {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
}

/*
|--------------------------------------------------------------------------
| Show price tag and numbering format
|--------------------------------------------------------------------------
*/
function accounting(amount, $prefix = 'Rp') {
	return $prefix + " " + numberFormat(amount);
}

/*
|--------------------------------------------------------------------------
| Unserialize Data
|--------------------------------------------------------------------------
*/
function unserialize(val) {
	var response = null;

	$.ajax({
		type: "POST",
		dataType: "json",
		async: false,
		data: {
			val: val,
		},
		url: base_url + "general/unserialize/",
		success: function (result) {
			response = result.data;
		},
	});
	return response;
}

/*
|--------------------------------------------------------------------------
| Unserialize Data
|--------------------------------------------------------------------------
*/
function getProductName(id) {
	var response = null;

	$.ajax({
		type: "POST",
		dataType: "json",
		async: false,
		data: {
			id: id,
		},
		url: base_url + "general/getProductName/",
		success: function (result) {
			response = result.data;
		},
	});
	return response;
}

/*
|--------------------------------------------------------------------------
| Get session user ID
|--------------------------------------------------------------------------
*/
function sessUserid() {
	var response = null;

	$.ajax({
		type: "POST",
		dataType: "json",
		async: false,
		url: base_url + "backend/sessUserId/",
		success: function (result) {
			response = result.data;
		},
	});
	return response;
}

/*
|--------------------------------------------------------------------------
| Call php function on js | Count Total Cart
|--------------------------------------------------------------------------
*/
function infoCart() {
	response = '';
	$.ajax({
		type: "POST",
		dataType: "json",
		async: false,
		url: base_url + "general/infoCart",
		success: function (result) {
			response = result.data;
		},
	});
	return response;
}

/*
|--------------------------------------------------------------------------
| Currency Format auto dot
|--------------------------------------------------------------------------
*/
$(".currency-format").keyup(function (event) {
	if (event.which >= 37 && event.which <= 40) return;
	$(this).val(function (index, value) {
		return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ".");
	});
});

/*
|--------------------------------------------------------------------------
| Image Preview Before Upload
|--------------------------------------------------------------------------
*/
$('[name="image"]').change(function () {
	var reader = new FileReader();
	reader.onload = function (e) {
		// get loaded data and render thumbnail.
		$("#temp-image").show();
		document.getElementById("temp-image").src = e.target.result;
	};
	// read the image file as a data URL.
	reader.readAsDataURL(this.files[0]);
});

/*
|--------------------------------------------------------------------------
| Show/Hide product (toggle)
|--------------------------------------------------------------------------
*/
$(".datatables").on("click", ".product-order .toggle", function () {
	$(this).closest(".product-order").find(".list").toggle("show");
});

/*
|--------------------------------------------------------------------------
| Show/Hide password
|--------------------------------------------------------------------------
*/
$(".show-hide-password button").on("click", function (event) {
	event.preventDefault();
	if ($(".show-hide-password input").attr("type") == "text") {
		$(".show-hide-password input").attr("type", "password");
		$(".show-hide-password i.icon-eye").addClass("fa-eye-slash");
		$(".show-hide-password i.icon-eye").removeClass("fa-eye");
	} else if ($(".show-hide-password input").attr("type") == "password") {
		$(".show-hide-password input").attr("type", "text");
		$(".show-hide-password i.icon-eye").removeClass("fa-eye-slash");
		$(".show-hide-password i.icon-eye").addClass("fa-eye");
	}
});

/*
|--------------------------------------------------------------------------
| Remove first character
|--------------------------------------------------------------------------
*/
$("input.no-phone").keyup(function () {
	if (this.value.substring(0, 1) == "0") {
		this.value = this.value.replace(/^0+/g, "");
	}
});

$("input.remove-at-sign").keyup(function () {
	if (this.value.substring(0, 1) == "@") {
		this.value = this.value.replace(/^@+/g, "");
	}
});

/*
|--------------------------------------------------------------------------
| Set Notification Seen
|--------------------------------------------------------------------------
*/
$(document).on('click', '.notification-message', function () {
	var el = $(this);
	var idNotif = el.data('id');

	$.ajax({
		url: base_url + 'backend/notificationRead',
		method: "POST",
		dataType: "json",
		data: {
			id: idNotif
		},
		success: function (result) {
			if (result.status == 'success') {
				$(el).empty();
				$('.notification-count').html(result.data);
			}
		},
		error: function () {
			swal("Maaf!", "There was an error..", "error");
		},
	});
	return false; // blocks redirect after submission via ajax
});

/*
|--------------------------------------------------------------------------
| Set Notification All Seen
|--------------------------------------------------------------------------
*/
$(document).on('click', '.read-all-notification', function () {
	$.ajax({
		url: base_url + 'backend/notificationRead',
		method: "POST",
		dataType: "json",
		success: function (result) {
			if (result.status == 'success') {
				$('.notification-message').empty();
				$('.notification-count').html(result.data);
			}
		},
		error: function () {
			swal("Maaf!", "There was an error..", "error");
		},
	});
	return false; // blocks redirect after submission via ajax
});


/*
|--------------------------------------------------------------------------
| toggle sidebar session
|--------------------------------------------------------------------------
*/
$('.modern-nav-toggle').on('click', function() {
	if ($.session.get("collapse")) {
		$.session.clear("collapse");
		$.session.set("expanded", "menu-expanded")
	} else {
		$.session.clear("expanded");
		$.session.set("collapse", "menu-collapsed")
	}
});

$(function() {
	if ($.session.get("collapse")) {
		$('body').removeClass("menu-expanded").addClass($.session.get("collapse"));
	} else {
		$('body').removeClass("menu-collapse").addClass($.session.get("expanded"));
	}
});