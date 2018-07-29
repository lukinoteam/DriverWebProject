$(document).ready(function(){
        $('#profilePictureFormButton').click(function(){
        var file_data = $("#avatarInput").prop("files")[0];
        var form_data = new FormData();
        form_data.append("file",file_data);
        console.log(file_data);
        $.ajax({
            url:'php/Business/UploadAvatar.php', 
            type: "POST",
            data: form_data,
            contentType: false,
            processData: false,
            success: function (msg) {   
            console.log(msg);
            alert("OK");              
                }
        });
        })
    })
