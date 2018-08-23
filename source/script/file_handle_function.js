const numberWithCommas = (x) => {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function triggerFileChoosedTools(file) {
    var fileId = '#' + file;
    choosedFile = file;
    type = 0;

    $("#txtRename").val($(fileId).find(".fileCaption").find(".name").text());

    $(document).mouseup(function(e) {
        var container = $(fileId);

        // if the target of the click isn't the container nor a descendant of the container
        if (checkOutside(container, e)) {
            container.find(".fileCaption").css({
                "background-color": "white",
                "color": "black"
            });
            container.css({
                "border": "1px solid #d3d3d3"
            });

            $("#info p").text("");
            $("#infoTip").show();
        }
    });

    $("#toolBar").css({
        "display": "block"
    });

    $(fileId).find(".fileCaption").css({
        "background-color": "#90adff",
        "color": "#0035d5"
    });
    $(fileId).css({
        "border": "1px solid #0066ff"
    });

    $("#infoFileName").text($(fileId).find(".fileCaption").find(".name").text());
    $("#infoFileType").text("Type: " + $(fileId).find(".fileCaption").find(".type").text());
    $("#infoFileSize").text("Size: " + $(fileId).find(".fileCaption").find(".size").text());
    $("#infoFileDate").text("Date modified: " + $(fileId).find(".fileCaption").find(".date").text());
    $("#infoFileDesc").text("Desciption: " + $(fileId).find(".fileCaption").find(".desc").text());

    $("#infoTip").hide();

    if ($(fileId).hasClass('deletedFile')) {
        $('#regular_tool').hide();
        $('#recycle_tool').show();
    } else {
        $('#regular_tool').show();
        $('#recycle_tool').hide();
    }

    if ($(fileId).hasClass('shared')) {
        $("#infoFileOwner").show();
        $("#infoFileOwner").text("Owner: " + $(fileId).find(".fileCaption").find(".owner").text());
    } else {
        $("#infoFileOwner").hide();
    }

    if ($(fileId).hasClass('favorited')) {
        $("#btnUnFav").show();
        $("#btnFav").hide();
    } else {
        $("#btnUnFav").hide();
        $("#btnFav").show();
    }
}

function triggerFolderChoosedTools(folder) {
    var folderId = '#' + folder;

    choosedFolder = folder;
    type = 1;

    $("#txtRename").val($(folderId).find(".name").text());

    $("#toolBar").css({
        "display": "block"
    });
    $(folderId).css({
        "background-color": "#90adff"
    })

    $(document).mouseup(function(e) {
        var container = $(folderId);

        // if the target of the click isn't the container nor a descendant of the container
        if (checkOutside(container, e)) {
            container.css({
                "background-color": "#f2f2f2"
            })

            $("#info p").text("");
            $("#infoTip").show();

            $("#infoFileType").show();
            $("#infoFileSize").show();
        }
    });

    $("#infoFileName").text($(folderId).find(".name").text());
    $("#infoFileType").hide();
    $("#infoFileSize").hide();
    $("#infoFileDate").text("Date modified: " + $(folderId).find(".date").text());
    $("#infoFileDesc").text("Desciption: " + $(folderId).find(".desc").text());
    $("#infoTip").hide();

    $('#regular_tool').show();
    $('#recycle_tool').hide();
}

function createFolder() {
    var folderName = $("#folderName").val();
    var desc = $("#txtFolderDesc").val();
    var form_data = new FormData();
    form_data.append('folderName', folderName);
    form_data.append('desc', desc);
    form_data.append('current', currenFolder);
    $.ajax({
        url: 'php/Business/NewFolder.php',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        dataType: 'json',
        success: function(data) {

            $('#folderName').val("");
            $('#txtFolderDesc').val("");

            if ($("#home").css("background-color") == "rgb(144, 173, 255)") {
                var str = '<li>\
                <div id="' + data[1].uuid + '" class="folderItem" onclick="triggerFolderChoosedTools(this.id);" ondblclick="setPath(this.id);">\
                <img src="img/folderic.png" style="display: inline;"><span class="name" style="display: inline;">' + data[0] + '</span>\
                    <p class="date" style="display: none;">' + parseDate(new Date(data[2].seconds * 1000)) + '</p>\
                    <p class="desc" style="display: none;">' + data[3] + '</p>\
                </div>\
                </li>';
                $("#folderList").append(str);
                $("#snackbar").css("visibility", "hidden");
            } else {
                specialFolderAction("home");
            }
        }
    });

}

function getParentFolder() {
    $("#fileList").empty();
    $("#folderList").empty();
    var form_data = new FormData();
    form_data.append('current', currenFolder);
    $.ajax({
        url: 'php/Business/GetParentFolder.php',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        dataType: 'json',
        success: function(json) {
            currenFolder = String(json[0].uuid);
            if (currenFolder == home) {
                $("#backIcon").hide();
            } else {
                $("#backIcon").show();
            }

            getFolderList();
            getFileList();
        }
    });
}

function uploadFile() {
    var data = $("#inputFile").prop("files")[0];
    var form_data = new FormData();
    form_data.append('file', data);

    var filename = $("#txtFileName").val();

    var file_ext = $("#inputFile").val();
    file_ext = file_ext.substring(12, file_ext.length);
    file_ext = getExt(file_ext);

    filename = filename + "." + file_ext;

    form_data.append('name', filename);
    form_data.append('desc', $('#txtFileDesc').val());
    form_data.append('current', currenFolder);
    $("#btnCloseUploadModal").click();

    if ((data.size * 100) / default_max_data + used_data > 100) {
        $('#modal-out-of-data').modal('show');
    } else {
        $.ajax({
            type: 'POST',
            url: "php/Business/UploadFile.php",
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            dataType: 'json',
            success: function(data) {

                $("#inputFile").val("");
                $('#txtFileDesc').val("");
                $("#txtFileName").val("");

                if ($("#home").css("background-color") == "rgb(144, 173, 255)") {
                    var str = '<li>\
                    <div id="' + data[0].uuid + '" class="fileItem" onclick="triggerFileChoosedTools(this.id);" ondblclick="viewImg(this.id);">\
                        <div class="img-wrapper">\
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
                    $("#snackbar").css("visibility", "hidden");
                } else {
                    specialFolderAction("home");
                }


            }
        });
    }


}

function getFolderList() {
    var form_data = new FormData();
    form_data.append('current', currenFolder);
    $.ajax({
        url: 'php/Business/GetFolderList.php',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        dataType: 'json',
        success: function(json) {
            $("#folderList").empty();
            $("#toolBar").hide();

            Object.values(json).forEach(function(data) {
                if (data[1] != null && (data[4] == 0 || data[4] == 1)) {

                    var str = '<li>\
                <div id="' + data[1].uuid + '" class="folderItem" onclick="triggerFolderChoosedTools(this.id);" ondblclick="setPath(this.id);">\
                <img src="img/folderic.png" style="display: inline;"><span class="name" style="display: inline;">' + data[0] + '</span>\
                    <p class="date" style="display: none;">' + parseDate(new Date(data[2].seconds * 1000)) + '</p>\
                    <p class="desc" style="display: none;">' + data[3] + '</p>\
                </div>\
                </li>';
                    $("#folderList").append(str);
                }
            });
        }
    });
}

function getFileList() {
    $("#snackbar").css("visibility", "visible");
    var form_data = new FormData();
    form_data.append('current', currenFolder);
    $.ajax({
        url: 'php/Business/GetFileList.php',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        dataType: 'json',
        success: function(json) {
            $("#fileList").empty();
            $("#toolBar").hide();
            Object.values(json).forEach(function(data) {

                if (data[0] != null && (data[7] == 1 || data[7] == 0 || data[7] == 2)) {

                    var str = '<li>\
                <div id="' + data[0].uuid + '" class="fileItem" onclick="triggerFileChoosedTools(this.id);" ondblclick="viewImg(this.id);">\
                    <div class="img-wrapper">\
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
            $("#snackbar").css("visibility", "hidden");
        }
    });
}

function parseSize(tmpSize) {
    if (tmpSize < 1024) {
        return size = numberWithCommas(tmpSize) + " bytes";
    } else if (tmpSize < 1024 * 1024) {
        return parseInt(tmpSize / 1024) + " KB (" + numberWithCommas(tmpSize) + " bytes)";
    } else if (tmpSize < 1024 * 1024 * 1024) {
        return parseInt(tmpSize / (1024 * 1024)) + " MB (" + numberWithCommas(tmpSize) + " bytes)";
    } else {
        return parseInt(tmpSize / (1024 * 1024 * 1024)) + " GB (" + numberWithCommas(tmpSize) + " bytes)";
    }
}

function parseDate(tmpDate) {
    var day = tmpDate.getDate();
    var month = (tmpDate.getMonth() + 1 < 10) ? ('0' + (tmpDate.getMonth() + 1)) : (tmpDate.getMonth() + 1);
    var year = tmpDate.getFullYear();
    var hour = (tmpDate.getHours() < 10) ? ('0' + tmpDate.getHours()) : tmpDate.getHours();
    var minute = (tmpDate.getMinutes() < 10) ? ('0' + tmpDate.getMinutes()) : tmpDate.getMinutes();
    return year + '-' + month + '-' + day + ' at ' + hour + ':' + minute;
}

function downFile(id) {
    if (id != "") {
        var form_data = new FormData();
        form_data.append('id', id);
        $.ajax({
            url: 'php/Business/DownFile.php',
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            type: 'post',
            dataType: 'json',
            success: function(json) {
                generateLink(json[2], json[1], json[0]);
                $("#snackbar").css("visibility", "hidden");
            }
        });
    }
}

function rename(type, id) {
    var form_data = new FormData();
    var newName = $("#txtRename").val();
    form_data.append('newName', newName);
    form_data.append('id', id);
    form_data.append('type', type);

    $.ajax({
        url: 'php/Business/RenameFile.php', // point to server-side PHP script 
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function() {
            if (type == 0) {
                getFileList();
            } else {
                getFolderList();
                $("#snackbar").css("visibility", "hidden");
            }
        }
    });
}

function deleteff(type, id) {
    var form_data = new FormData();
    form_data.append('id', id);
    form_data.append('type', type);

    $("#" + id).remove();

    $.ajax({
        url: 'php/Business/DeleteFile.php', // point to server-side PHP script 
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(msg) {
            if (type == 0) {
                getFileList();
            } else {
                getFolderList();
                $("#snackbar").css("visibility", "hidden");
            }
            $("#info p").text("");
            $("#infoTip").show();
        }
    });
}

//get all deleted file:
function getDeletedFile() {
    $("#fileList").empty();
    $("#folderList").empty();

    $("#snackbar").css("visibility", "visible");

    $.ajax({
        url: 'php/Business/RecycleBin.php',
        cache: false,
        contentType: false,
        processData: false,
        type: 'post',
        dataType: 'json',
        success: function(json) {
            $("#fileList").empty();
            $("#toolBar").hide();
            Object.values(json).forEach(function(data) {
                if (data[0] != null && (data[7] == -1 || data[7] == 0)) {

                    var str = '<li>\
            <div id="' + data[0].uuid + '" class="fileItem deletedFile" onclick="triggerFileChoosedTools(this.id);" ondblclick="viewImg(this.id);">\
                <div class="img-wrapper">\
                    <img src=' + data[5] + '>\
                </div>\
                <div class="fileCaption">\
                    <p class="name">' + data[2] + '</p>\
                    <p class="size" style="display: none;">' + parseSize(data[3].value) + '</p>\
                    <p class="date" style="display: none;">' + parseDate(new Date(data[1].seconds * 1000)) + '</p>\
                    <p class="type" style="display: none;">' + extIntToStr(data[6]) + '</p>\
                    <p class="desc" style="display: none;">' + data[4] + '</p>\
                    <p class="thumb_id" style="display: none;">' + data[8].uuid + '</p>\
                </div>\
            </div>\
        </li>';
                    $("#fileList").append(str);
                }
            });
            $("#snackbar").css("visibility", "hidden");
        }
    });
}

function check_out_for_special_feature(container, e) {
    return $("#specialFolderList").find("*").is(e.target);
}

function specialFolderAction(id) {
    $("#backIcon").hide();
    var folderId = "#" + id;
    $(document).mouseup(function(e) {
        var container = $(folderId);
        // if the target of the click is neither the container nor a descendant of the container
        if (check_out_for_special_feature(container, e)) {
            container.css({
                "background-color": "#f2f2f2"
            })

            if (id == "home" || id == "recycle_feature" || id == "share_feature" || id == "fav_feature") {
                $("#infoTip").show();
                $("#infoFileName").text("");
            }
        }
    });

    $(folderId).css({
        "background-color": "#90adff"
    })

    $("#dash_board_area").hide();
    $("#dash_board").css({ "background-color": "#f2f2f2" });

    $("#infoTip").hide();

    $("#infoIcon").show();
    $("#filePanel").attr("class", "col-xs-7");
    $("#infoPanel").show();

    if (id == "recycle_feature") {

        getDeletedFile();
    } else if (id == "share_feature") {
        $("#snackbar").css("visibility", "visible");
        get_shared_files();
    } else if (id == "home") {
        setPath(home);
    } else if (id == "fav_feature") {
        $("#snackbar").css("visibility", "visible");
        get_fav_files()
    } else if (id == "dash_board") {
        if ($("#filePanel").attr("class") == "col-xs-7") {
            $("#filePanel").attr("class", "col-xs-10");
            $("#infoPanel").hide();
        }
        $("#infoIcon").hide();

        $("#snackbar").css("visibility", "visible");
        $("#dash_board_area").show();
        show_dash_board();
    }
}

function move(type, id, destination) {
    var form_data = new FormData();
    form_data.append('id', id);
    form_data.append('type', type);
    form_data.append('dest', destination);
    form_data.append('move_type', sm_move_type);

    $("#btnCloseMoveModal").click();

    $.ajax({
        url: 'php/Business/MoveFile.php', // point to server-side PHP script 
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(msg) {
            getFileList();
        }
    });
}

function set_favorite(id) {
    var form_data = new FormData();
    form_data.append('id', id);

    $.ajax({
        url: 'php/Business/SetFavorite.php', // point to server-side PHP script 
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(msg) {
            $("#snackbar").css("visibility", "hidden");
        }
    });
}

function un_favorite(id) {
    $("#" + id).remove();

    var form_data = new FormData();
    form_data.append('id', id);

    $.ajax({
        url: 'php/Business/UnFavorite.php', // point to server-side PHP script 
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(msg) {
            $("#toolBar").hide();
            $("#snackbar").css("visibility", "hidden");
        }
    });
}

function restore(id) {
    var form_data = new FormData();
    form_data.append('id', id);

    $.ajax({
        url: 'php/Business/RestoreFile.php', // point to server-side PHP script 
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(msg) {
            getDeletedFile();
            $("#info p").text("");
            $("#infoTip").show();
        }
    });
}

function remove(id) {


    var form_data = new FormData();
    form_data.append('id', id);
    form_data.append('thumb_id', $("#" + id).find(".thumb_id").text());

    $("#" + id).remove();

    $.ajax({
        url: 'php/Business/RemoveFile.php', // point to server-side PHP script 
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(msg) {
            $("#toolBar").hide();
            $("#info p").text("");
            $("#infoTip").show();
        }
    });
}

function dataURItoBlob(dataURI) {
    // convert base64 to raw binary data held in a string
    // doesn't handle URLEncoded DataURIs - see SO answer #6850276 for code that does this
    var byteString = atob(dataURI.split(',')[1]);
    // separate out the mime component
    var mimeString = dataURI.split(',')[0].split(':')[1].split(';')[0]
        // write the bytes of the string to an ArrayBuffer
    var ab = new ArrayBuffer(byteString.length);
    // create a view into the buffer
    var ia = new Uint8Array(ab);
    // set the bytes of the buffer to the correct values
    for (var i = 0; i < byteString.length; i++) {
        ia[i] = byteString.charCodeAt(i);
    }
    // write the ArrayBuffer to a blob, and you're done
    var blob = new Blob([ab], { type: mimeString });
    return blob;
}

function extIntToStr($ext) {
    switch ($ext) {
        case 1:
        case 2:
        case 3:
            return "DOC";
        case 4:
        case 12:
            return "SHEET";
        case 5:
            return "JS";
        case 6:
            return "PDF";
        case 7:
            return "JPG";
        case 8:
            return "PNG";
        case 9:
            return "BMP";
        case 10:
            return "HTML";
        case 11:
            return "CSS";
        case 13:
            return "MP4";
        case 14:
            return "MP3";
        default:
            return "UNKNOWN";
    }
}