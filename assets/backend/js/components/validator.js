/*
|--------------------------------------------------------------------------
| Jquery validation set default config
|--------------------------------------------------------------------------
*/
jQuery.validator.setDefaults({
    event: 'blur',
    ignore: [], // required for ckeditor
    highlight: function (element) {
        $(element)
            .closest(".form-group")
            .addClass("error");
        $(element)
            .closest(".form-row")
            .addClass("error");
    },
    unhighlight: function (element) {
        $(element)
            .closest(".form-group")
            .removeClass("error");
        $(element)
            .closest(".form-row")
            .removeClass("error");
    },
    errorElement: "span",
    errorClass: "help-block",
    errorPlacement: function (error, element) {
        if (element.parent(".form-control").length) {
            error.insertAfter(element.parent());
        } else if (element.parent(".input-group").length) {
            error.insertAfter(element.parent('.input-group'));
        } else {
            error.insertAfter(element);
        }
    },
    invalidHandler: function (form, validator) {
        var errors = validator.numberOfInvalids();
        if (errors) {
            validator.errorList[0].element.focus();
        }
    }
});

jQuery.validator.addMethod('ckrequired', function (value, element, params) {
    var idname = jQuery(element).attr('id');
    var messageLength = jQuery.trim(CKEDITOR.instances[idname].getData());
    return !params || messageLength.length !== 0;
}, "This field is required");

jQuery.validator.addMethod("nospace", function (value, element) {
    return value.indexOf(" ") < 0;
}, "No space");

jQuery.validator.addMethod("lettersonly", function (value, element) {
    return this.optional(element) || /^[a-z\s]+$/i.test(value);
}, "Only alphabetical characters");