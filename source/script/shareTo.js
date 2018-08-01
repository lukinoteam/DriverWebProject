function shareTo(sharedEmail) {
    var sender = new FormData();
    sender.append('email', sharedEmail);
    sender.append('file', choosedFile);
    $.ajax({
        url: 'php/Business/ShareTo.php',
        cache: false,
        contentType: false,
        processData: false,
        data: sender,
        type: 'post',
        success: function(msg) {
            console.log(msg);
        }
    });
}