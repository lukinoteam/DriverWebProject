var sm_current = home;
var sm_choosedFolder = "";
var save_title = "";
var sm_move_type = "";

function getSmallFolderList() {
    var form_data = new FormData();
    form_data.append('current', sm_current);

    $.ajax({
        url: 'php/Business/GetFolderList.php',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        dataType: 'json',
        success: function(json) {
            $("#small-folder-list").empty();
            $("#info p").text("");
            $("#infoTip").show();

            Object.values(json).forEach(function(data) {

                if (data[1] != null && (data[4] == 0 || data[4] == 1)) {

                    var str = '<li>\
                    <div id="' + data[1].uuid + '" class="small-folder-item" onclick="triggerMoveAction(this.id);" ondblclick="setSmallPath(this.id);">\
                    <img src="img/folderic.png" style="display: inline;"><span class="name" style="display: inline;">' + data[0] + '</span>\
                    </div>\
                    </li>';
                    $("#small-folder-list").append(str);

                }
            });

            if (sm_current == home) {
                $("#modalMove").find(".modal-title").text("Home");
                $("#btnBackMove").prop("disabled", true);
            } else {
                $("#modalMove").find(".modal-title").text(save_title);
                $("#btnBackMove").prop("disabled", false);
            }


        }
    });

}

function getSmallParentFolder() {
    var form_data = new FormData();
    form_data.append('current', sm_current);
    $.ajax({
        url: 'php/Business/GetParentFolder.php',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        dataType: 'json',
        success: function(json) {
            if (json[0] == "home")
                sm_current = "home";
            else
                sm_current = String(json[0].uuid);

            getSmallFolderList();
        }
    });
}

function triggerMoveAction(id) {

    var folderId = '#' + id;

    sm_choosedFolder = id;

    save_title = $("#modalMove").find("#" + sm_choosedFolder).find(".name").text();

    $("#modalMove").find(folderId).css({
        "background-color": "#b2b2b2"
    })

    $(document).mouseup(function(e) {
        var container = $("#modalMove").find(folderId);

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0 && !$("#moveConfirm").is(e.target)) {
            container.css({
                "background-color": "#f2f2f2"
            })

            sm_choosedFolder = sm_current;
        }
    });
}

function setSmallPath(id) {
    sm_choosedFolder = id;
    sm_current = id;

    $("#small-folder-list").empty();

    getSmallFolderList();
}
