$(document).ready(function() { alert();
    $("#registerForm").validate({
        rules: {
            first_name: {
                required: true,
                alphanumeric: true,
                minlength: 3,
                maxlength: 25,

            },
            last_name: {
                required: true,
                minlength: 3,
                maxlength: 25
            },
            username: {
                required: true,
                minlength: 3,
                maxlength: 25
            },
            password: {
                required: true,
                minlength: 8,
                maxlength: 15,
            },
            password_confirmation: {
                required: true,
                minlength: 8,
                maxlength: 15,
                equalTo: "#password"
            },
        },
        messages: {
        "password_confirmation": {
            equalTo: "Please enter same as the password",
        }
    }

    });
});