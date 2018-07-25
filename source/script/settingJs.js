$(document).ready(function() {
	showInfo();
	getIdEmail();
    // make drop-down menu effect smoother
    $('.dropdown').on('show.bs.dropdown', function(e) {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
    });

    $('.dropdown').on('hide.bs.dropdown', function(e) {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
    });
    // ///////////////////////////////////////////

    // Validate form for change account info tab
    $('#infoForm')
        .bootstrapValidator({
            message: '*This value is not valid',
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
            }
        })
			.on('success.form.bv', function(e) {
            // Prevent submit form
            e.preventDefault();
			
            var $form     = $(e.target),
                validator = $form.data('bootstrapValidator');
				changeAccount();
            //$form.find('.alert').html('Thanks for signing up. Now you can sign in as ' + validator.getFieldElements('username').val()).show();
        });
    // Validate form for change password tab  
    $('#passwordForm')
        .bootstrapValidator({
            message: '*This value is not valid',
            fields: {
                oldPassword: {
                    validators: {
                        notEmpty: {
                            message: '*The confirm password is required and can\'t be empty'
                        }
                    }
                },
                newPassword: {
                    validators: {
                        notEmpty: {
                            message: '*The password is required and can\'t be empty'
                        },
                        different: {
                            field: 'oldPassword',
                            message: '*The new password and old password cannot be the same as each other'
                        },
                    }
                },
                confirmPassword: {
                    validators: {
                        notEmpty: {
                            message: '*The confirm password is required and can\'t be empty'
                        },
                        identical: {
                            field: 'newPassword',
                            message: '*The password and its confirm are not the same'
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
				changePassWord();
            //$form.find('.alert').html('Thanks for signing up. Now you can sign in as ' + validator.getFieldElements('username').val()).show();
        });

    // Validate form for change profile picture tab  
    $('#profilePictureForm').bootstrapValidator({
        fields: {
            fileInput: {
                validators: {
                    file: {
                        extension: 'jpeg,png,jpg',
                        type: 'image/jpeg,image/png',
                        maxSize: 2048*1024,
                        message: '*Please choose a jpg or png image file less than 2M.'
                    }
                }
            },
        }
    })

    $("#accountInfoMenu").css({
        "background-color": "#90adff" // account info tab's background-color set blue by default
    });
    $("#changePassword").hide(); // hide by default
    $("#profilePicture").hide(); //

});

// Trigger change color effect when change setting tab
function changeSettingTab(tab1, tab2, tab3, text) {

    $(tab1).css({
        "background-color": "#90adff"
    });

    $(tab2).css({
        "background-color": "#f2f2f2"
    });
    $(tab3).css({
        "background-color": "#f2f2f2"
    });

    $(tab1.substring(0, tab1.length - 4)).show();
    $(tab2.substring(0, tab2.length - 4)).hide();
    $(tab3.substring(0, tab3.length - 4)).hide();

    $("#settingLabel").find("p").text(text);
}
function showInfo(){
	//take data from php file
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
        var myObj = JSON.parse(this.responseText);
        document.getElementById("txtFullName").value = myObj[0];
		var gen ;
		if(myObj[1]==1){
			gen = "MALE";
		}
		else if (myObj[1]==2){
			gen = "FEMALE";
		}
		else{
			gen = "OTHER";
		}
		document.getElementById("txtGenderChoice").value = gen;
		document.getElementById("txtBirthDate").value = myObj[2];
		}
	};
	xmlhttp.open("GET", "php/Business/getInfo.php", true);
	xmlhttp.send();
}
function logOut(){
	$.ajax({
		url :'php/Logout.php',
		type : "POST",
		contentType :false,
		processData :false,
		success :function(data){
			//alert(data);
		}
	});
}
function getIdEmail(){
	var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
        var myObj = JSON.parse(this.responseText);
		var email = myObj[0];
		var id = myObj[1];
		console.log(email);
		console.log(id);
		}
	};
	xmlhttp.open("GET", "php/getHomeInfo.php", true);
	xmlhttp.send();
}
function changePassWord(){
	var oldPass = document.getElementById("txtOldPassword").value;
	var newPass = document.getElementById("txtNewPassword").value;
	var formData = new FormData();
	formData.append("fieldNewPass",newPass);
	formData.append("fieldOldPass",oldPass);
	$.ajax({
                url: 'php/Business/UpdatePassword.php',   
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                      if(data==1){
						  //window.name = email;
						  window.location="login.html";
					  }
					  else{
						alert(data);
					  }
						  
                }
            });
}
function changeAccount(){
	var name = document.getElementById("txtFullName").value;
	var gender = document.getElementById("txtGenderChoice").value;
	var date = document.getElementById("txtBirthDate").value;
	var formData = new FormData();
	formData.append("fieldName",name);
	formData.append("fieldGender",gender);
	formData.append("fieldDate",date);
	$.ajax({
                url: 'php/Business/UpdateAccount.php',   
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function (data) {
                      
						alert(data);
						location = location;
					  	  
                }
            });
}
