var used_data = 0;
var default_max_data = 100 * 1024 * 1024;

function parse_num_by_type(val) {
    if (val == 0)
        return "0 file";
    else {
        if (val.value == 1)
            return "1 file";
        else {
            return val.value + ' files';
        }
    }
}

function show_dash_board() {
    $("#snackbar").css("visibility", "visible");

    $("#dash_board").css({ "background-color": "#90adff" });

    $("#fileList").empty();
    $("#folderList").empty();

    $.ajax({
        url: 'php/Business/GetDashBoard.php',
        cache: false,
        contentType: false,
        processData: false,
        type: 'post',
        dataType: 'json',
        success: function(json) {
            $("#dash_board_area").empty();
            var used_data = json[6] / default_max_data;
            used_data *= 100; 

            var color = "green";
            if (used_data > 80) {
                color = "red";
            }

            var str = "<div class='c100 p" + parseInt(used_data) + " " + color + " big'>\
            <span>" + Math.round(used_data * 10) / 10 + "%</span>\
            <div class='slice'>\
                <div class='bar'></div>\
                <div class='fill'></div>\
            </div></div>\
        <div id='dash_board_tooltip'>\
            <div>\
                <img src='img/used_data_color_" + color + ".jpg' width='15' height='15' style='margin-right: 10px;'>\
                <span>Used Data</span>\
            </div>\
            <div>\
                <img src='img/remain_data_color.jpg' width='15' height='15' style='margin-right: 10px;'>\
                <span>Remain Data</span>\
            </div>\
        </div>\
        <ul id='dash_board_info' class='list-inline'>\
            <li>\
                <div id='dash_board_doc' class='dash_board_child'>\
                    <img src='img/word-icon.png' width='80' height='80'>\
                    <span>Word: " + parse_num_by_type(json[0]) + "</span>\
                </div></li>\
            <li>\
                <div id='dash_board_excel' class='dash_board_child'>\
                    <img src='img/excel-icon.png' width='80' height='80'>\
                    <span>Excel: " + parse_num_by_type(json[1]) + "</span>\
                </div></li>\
            <li>\
                <div id='dash_board_image' class='dash_board_child'>\
                    <img src='img/image-icon.png' width='80' height='80'>\
                    <span>Image: " + parse_num_by_type(json[2]) + "</span>\
                </div></li>\
            <li>\
                <div id='dash_board_video' class='dash_board_child'>\
                    <img src='img/video-icon.png' width='80' height='80'>\
                    <span>Video: " + parse_num_by_type(json[3]) + "</span>\
                </div></li>\
            <li>\
                <div id='dash_board_audio' class='dash_board_child'>\
                    <img src='img/audio-icon.png' width='80' height='80'>\
                    <span>Audio: " + parse_num_by_type(json[4]) + "</span>\
                </div></li>\
            <li>\
                <div id='dash_board_others' class='dash_board_child'>\
                    <img src='img/others-icon.png' width='80' height='80'>\
                    <span>Others: " + parse_num_by_type(json[5]) + "</span>\
                </div></li>\
        </ul>";

            $("#dash_board_area").append(str);
            $("#snackbar").css("visibility", "hidden");
        }
    });
}
