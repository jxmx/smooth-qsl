// Add a custom rule called "pwcheckclasses"
$.validator.addMethod("pwcheckclasses", function(value, element) {
let classes = 0;
if (/[a-z]/.test(value)) classes++;        // lowercase
if (/[A-Z]/.test(value)) classes++;        // uppercase
if (/[0-9]/.test(value)) classes++;        // digit
if (/[^A-Za-z0-9]/.test(value)) classes++; // special character

return classes >= 3; // require at least 3 of the 4
}, "Password must contain at least three of the following: lowercase, uppercase, number, special character.");

// Add a custom rule called "callsign"
const callsignRegex = /^(?:[A-Z]{1,2}|[0-9][A-Z])\d{1,2}[A-Z]{1,4}$/i;
$.validator.addMethod("callsign", function(value, element) {
    return this.optional(element) || callsignRegex.test(value);
}, "Please enter a valid ITU-format amateur radio callsign.");

$("#loadform").validate({
    //debug: true,
    rules: {
        "csign": {
            required: true,
            callsign: true
        },
        "loadkey": {
            required: true,
            minlength: 8,
            pwcheckclasses: true
        },
        "adiffile": {
            required: true,
            extension: "adi|adif"
        },
        "county": {
            required: true,
            maxlength: 30
        }
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