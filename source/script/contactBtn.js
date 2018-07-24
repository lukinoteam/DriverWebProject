$.ajax({
    url: 'php/Business/ContactUs.php',
    cache: false,
    contentType: false,
    processData: false,
    type: 'post',
    success: function(msg) {
        document.body.innerHTML += msg;
    }
});