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
                email: {
                    validators: {
                        emailAddress: {
                            message: '*The input is not a valid email address'
                        },
                        notEmpty: {
                            message: '*The email is required and cannot be empty'
                        }

                    }
                }
            }
        })
})