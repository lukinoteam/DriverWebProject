$(document).ready(function() {

    //Trigger login button when press Enter
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
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: '*The password is required and cannot be empty'
                        }
                    }
                },
            }
        })
		.on('success.form.bv', function(e) {
            // Prevent submit form
            e.preventDefault();

            var $form     = $(e.target),
                validator = $form.data('bootstrapValidator');
				getLogin();
            //$form.find('.alert').html('Thanks for signing up. Now you can sign in as ' + validator.getFieldElements('username').val()).show();
        });
});
function getLogin(){
	var email = document.getElementById("txtEmail").value;
	var pass = document.getElementById("txtPassword").value;
	var formData = new FormData();
	formData.append("fieldEmail",email);
	formData.append("fieldPass",pass);
	  $.ajax({
                url: 'php/Business/Login.php',   
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                      if(data=="true"){
						  window.name = email;
						  window.location="home.html";
					  }
					  else{
						alert(data);
					  }
						  
                }
            });
	
}
