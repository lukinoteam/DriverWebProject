function get_shared_files() {
    $.ajax({
        url: 'php/Business/GetSharedFile.php',
        cache: false,
        contentType: false,
        processData: false,
        type: 'post',
        dataType: 'json',
        success: function (json) {
            $("#fileList").empty();

            // $("#info p").text("");
            // $("#infoTip").show();
            $("#toolBar").hide();
            Object.values(json).forEach(function (data) {
                if (data[0] != null && (data[7] == 12 || data[7] == 1)) {

                    var str = '<li>\
            <div id="' + data[0].uuid + '" class="fileItem" onclick="triggerFileChoosedTools(this.id);" ondblclick="viewImg(this.id);">\
                <div>\
                    <img src=' + data[5] + '>\
                </div>\
                <div class="fileCaption">\
                    <p class="name">' + data[2] + '</p>\
                    <p class="size" style="display: none;">' + parseSize(data[3].value) + '</p>\
                    <p class="date" style="display: none;">' + parseDate(new Date(data[1].seconds * 1000)) + '</p>\
                    <p class="type" style="display: none;">' + extIntToStr(data[6]) + '</p>\
                    <p class="desc" style="display: none;">' + data[4] + '</p>\
                </div>\
            </div>\
        </li>';
                    $("#fileList").append(str);
                }
            });
        }
    });
}