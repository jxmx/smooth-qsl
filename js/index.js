// Add a custom rule called "callsign"
const callsignRegex = /^(?:[A-Z]{1,2}|[0-9][A-Z])\d{1,2}[A-Z]{1,4}$/i;
$.validator.addMethod("callsign", function(value, element) {
    return this.optional(element) || callsignRegex.test(value);
}, "Please enter a valid ITU-format amateur radio callsign.");

$("#indexsearch").validate({
    //debug: true,
    rules: {
        "call": {
            required: true,
            callsign: true
        },
    },
    messages: {
        logfile: {
            extension: "Please upload a .adi or .adif file"
        }
    },
    highlight: function (element) {
        $(element).addClass("is-invalid").removeClass("is-valid");
    },
    unhighlight: function (element) {
        $(element).addClass("is-valid").removeClass("is-invalid");
    },
    errorPlacement: function (error, element) {
        error.addClass("invalid-feedback");
        error.insertAfter(element);
    }
});