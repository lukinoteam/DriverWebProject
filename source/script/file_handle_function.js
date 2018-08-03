const numberWithCommas = (x) => {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function triggerFileChoosedTools(file) {
    var fileId = '#' + file;
    choosedFile = file;
    type = 0;
    console.log($(fileId).find(".owner").text());
    $("#txtRename").val($(fileId).find(".fileCaption").find(".name").text());

    $(document).mouseup(function (e) {
        var container = $(fileId);

        // if the target of the click isn't the container nor a descendant of the container
        if (checkOutside(container, e)) {
            container.find(".fileCaption").css({
                "background-color": "white",
                "color": "black"
            });
            container.css({
                "border": "1px solid #919180"
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

    $(document).mouseup(function (e) {
        var container = $(folderId);

        // if the target of the click isn't the container nor a descendant of the container
        if (checkOutside(container, e)) {
            container.css({
                "background-color": "#f2f2f2"
            })

            $("#info p").text("");
            $("#infoTip").show();

            $("#infoFileType").show();
        }
    });

    $("#infoFileName").text($(folderId).find(".name").text());
    $("#infoFileType").hide();
    $("#infoFileSize").text("Size: " + $(folderId).find(".size").text());
    $("#infoFileDate").text("Date modified: " + $(folderId).find(".date").text());
    $("#infoFileDesc").text("Desciption: " + $(folderId).find(".desc").text());
    $("#infoTip").hide();

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
        success: function () {

            $('#folderName').val("");
            $('#txtFolderDesc').val("");

            getFolderList();
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
        success: function (json) {
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

    form_data.append('name', filename);
    form_data.append('desc', $('#txtFileDesc').val());
    form_data.append('current', currenFolder);
    $("#btnCloseUploadModal").click();

    $.ajax({
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {

                    $("#snackbar").css("visibility", "visible");
                }
            }, false);
            xhr.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {

                    $("#snackbar").css("visibility", "visible");

                }
            }, false);
            return xhr;
        },
        type: 'POST',
        url: "php/Business/UploadFile.php",
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        success: function (data) {
            getFileList();
            $("#inputFile").val("");
            $('#txtFileDesc').val("");
            $("#txtFileName").val("");

            $("#snackbar").css("visibility", "hidden");

        }
    });

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
        success: function (json) {
            $("#folderList").empty();
            $("#toolBar").hide();

            Object.values(json).forEach(function (data) {
                if (data[1] != null && (data[5] == 0 || data[5] == 1)) {

                    var str = '<li>\
                <div id="' + data[1].uuid + '" class="folderItem" onclick="triggerFolderChoosedTools(this.id);" ondblclick="setPath(this.id);">\
                <img src="img/folderic.png" style="display: inline;"><span class="name" style="display: inline;">' + data[0] + '</span>\
                    <p class="size" style="display: none;">' + parseSize(data[2].value) + '</p>\
                    <p class="date" style="display: none;">' + parseDate(new Date(data[3].seconds * 1000)) + '</p>\
                    <p class="desc" style="display: none;">' + data[4] + '</p>\
                </div>\
                </li>';
                    $("#folderList").append(str);
                }
            });
        }
    });
}

function getFileList() {
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
        success: function (json) {
            $("#fileList").empty();
            // $("#info p").text("");
            // $("#infoTip").show();
            $("#toolBar").hide();
            Object.values(json).forEach(function (data) {

                if (data[0] != null && (data[7] == 1 || data[7] == 0)) {

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
            success: function (json) {
                generateLink(json[2], json[1], json[0]);
                $("#snackbar").css("visibility", "hidden");
            }
        });
    }
}

function downShareFile(id) {

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
        success: function () {
            if (type == 0) {
                getFileList();
            } else {
                getFolderList();
            }
        }
    });
}

function deleteff(type, id) {
    var form_data = new FormData();
    form_data.append('id', id);
    form_data.append('type', type);

    $.ajax({
        url: 'php/Business/DeleteFile.php', // point to server-side PHP script 
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function (msg) {
            if (type == 0) {
                getFileList();
            } else {
                getFolderList();
            }
        }
    });
}

//get all deleted file:
function getDeletedFile() {
    $.ajax({
        url: 'php/Business/RecycleBin.php',
        cache: false,
        contentType: false,
        processData: false,
        type: 'post',
        success: function (json) {
            $("#fileList").empty();

            // $("#info p").text("");
            // $("#infoTip").show();
            $("#toolBar").hide();
            Object.values(json).forEach(function (data) {
                if (data[0] != null && (data[7] == -1 || data[7] == 0)) {

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
            $("#snackbar").css("visibility", "hidden");


        }
    });
}

function specialFolderAction(id) {

    var folderId = "#" + id;
    $(document).mouseup(function (e) {
        var container = $(folderId);
        // if the target of the click is neither the container nor a descendant of the container
        if (checkOutside(container, e)) {
            container.css({
                "background-color": "#f2f2f2"
            })

            $("#infoTip").show();
            $("#infoFileName").text("");
        }
    });

    $(folderId).css({
        "background-color": "#90adff"
    })

    $("#infoTip").hide();

    $("#infoFileName").text($(folderId).find(".name").text());


    if (id == "recycle_feature") {
        $("#snackbar").css("visibility", "visible");
        getDeletedFile();
    } else if (id == "share_feature") {
        $("#snackbar").css("visibility", "visible");
        get_shared_files();
    } else if (id == "home") {
        $("#snackbar").css("visibility", "visible");
        setPath(home);
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
        xhr: function () {
            var xhr = new window.XMLHttpRequest();
            xhr.upload.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {

                    $("#snackbar").css("visibility", "visible");
                }
            }, false);
            xhr.addEventListener("progress", function (evt) {
                if (evt.lengthComputable) {

                    $("#snackbar").css("visibility", "visible");

                }
            }, false);
            return xhr;
        },
        url: 'php/Business/MoveFile.php', // point to server-side PHP script 
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function (msg) {
            getFileList();
            $("#snackbar").css("visibility", "hidden");

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
        case 14:
            return "MP3";
        default:
            return "UNKNOWN";
    }
}