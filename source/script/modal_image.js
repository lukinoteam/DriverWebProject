function viewImg(id) {
    save = id;
    id = "#" + id;

    if ($(id).find(".type").text() == "JPG" || $(id).find(".type").text() == "PNG" || $(id).find(".type").text() == "BMP") {

        var form_data = new FormData();
        form_data.append('id', save);
        $.ajax({
            url: 'php/Business/DownFile.php',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            dataType: 'json',
            success: function(json) {

                if (json[3].value <= 5 * 1024 * 1024) {
                    $("#modalImg").css("display", "block");
                    $("#preview-img").attr("src", "data:image/png;base64," + json[1]);
                    $("#caption").find("p").text($(id).find(".name").text());
                    wheelzoom($("#preview-img"));
                } else {
                    alert("Image is too large! GO PREMIUM to view large image online!");
                }
            }
        });


        $("#preview-img").dblclick(function() {
            document.querySelector('img#preview-img').dispatchEvent(new CustomEvent('wheelzoom.reset'));
        })

        // When the user clicks on <span> (x), close the modal
        $("#close").click(function() {
            $("#modalImg").css("display", "none");
            $("#preview-img").attr("src", "");
            document.querySelector('img#preview-img').dispatchEvent(new CustomEvent('wheelzoom.destroy'));
        });


        // When the user press ESC close the modal
        $(document).keyup(function(e) {
            if (e.keyCode == 27) {
                $("#modalImg").css("display", "none");
                $("#preview-img").attr("src", "");
                document.querySelector('img#preview-img').dispatchEvent(new CustomEvent('wheelzoom.destroy'));
            }
        });
    }
}