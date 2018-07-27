$(document).ready(function() {
    $.ajax({
        url: 'php/Business/InitThumbnail.php', // point to server-side PHP script 
        cache: false,
        contentType: false,
        processData: false,
        type: 'post',
        success: function(msg) {
            if (msg != "")
                console.log("Initialize Default Thumbnail!");
        }
    });
})