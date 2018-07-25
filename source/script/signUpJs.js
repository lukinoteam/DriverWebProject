$(document).ready(function() {

    //Trigger create button when press Enter
    $(document).keypress(function(e) {
        if (e.which == 13) {
            $("#createButton").click();
        }
    });

    $('#defaultForm')
        .bootstrapValidator({
            message: 'This value is not valid',
            fields: {
                fullName: {
                    validators: {
                        notEmpty: {
                            message: '*The full name is required and cannot be empty'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z\s]+$/,
                            message: '*The full name can only consist of alphabetical and space'
                        },
                    }
                },
                email: {
                    validators: {
                        emailAddress: {
                            message: '*The input is not a valid email address'
                        },
                        notEmpty: {
                            message: '*The email is required and cannot be empty'
                        },
                        different: {
                            field: 'password',
                            message: '*The email and password cannot be the same as each other'
                        }
                    }
                },
                password: {
                    validators: {
                        notEmpty: {
                            message: '*The password is required and can\'t be empty'
                        },
                        different: {
                            field: 'email',
                            message: '*The email and password cannot be the same as each other'
                        }
                    }
                },
                confirmPassword: {
                    validators: {
                        notEmpty: {
                            message: '*The confirm password is required and can\'t be empty'
                        },
                        identical: {
                            field: 'password',
                            message: '*The password and its confirm are not the same'
                        }
                    }
                },
                gender: {
                    validators: {
                        notEmpty: {
                            message: '*The gender is required and cannot be empty'
                        }
                    }
                },
                bdate: {
                    validators: {
                        notEmpty: {
                            message: '*The date is required and cannot be empty'
                        }
                    }
                },
                agreement: {
                    validators: {
                        notEmpty: {
                            message: '*You have to agree to license!'
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
				getInfo();
            //$form.find('.alert').html('Thanks for signing up. Now you can sign in as ' + validator.getFieldElements('username').val()).show();
        });
});
function getInfo(){ 
	var name = document.getElementById("txtFullName").value;
	var email = document.getElementById("txtEmail").value;
	var pass = document.getElementById("txtPassword").value;
	var gender = document.getElementById("txtGenderChoice").value;
	var date = document.getElementById("txtBirthDate").value;
	var formData = new FormData();
	formData.append("fieldName",name);
	formData.append("fieldEmail",email);
	formData.append("fieldPass",pass);
	formData.append("fieldGender",gender);
	formData.append("fieldDate",date);
	 $.ajax({
                url: 'php/Business/Signup.php',   
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                      if(data=="true"){
						  window.location="login.html";
					  }
					  else{
						alert(data);
						
					  }		  
                }
            });	
}
