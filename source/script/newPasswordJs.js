$(document).ready(function() {

    //Trigger button when press Enter
    $(document).keypress(function(e) {
        if (e.which == 13) {
            $("#confirmButton").click();
        }
    });

    $('#defaultForm')
        .bootstrapValidator({
            message: 'This value is not valid',
            fields: {
                password: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required and can\'t be empty'
                        },
                        identical: {
                            field: 'confirmPassword',
                            message: 'The password and its confirm are not the same'
                        }
                    }
                },
                confirmPassword: {
                    validators: {
                        notEmpty: {
                            message: 'The confirm password is required and can\'t be empty'
                        },
                        identical: {
                            field: 'password',
                            message: 'The password and its confirm are not the same'
                        }
                    }
                },
            }
        });
})