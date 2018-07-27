const numberWithCommas = (x) => {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
        success: function() {

            $('#folderName').val("");
            $('#txtFolderDesc').val("");

            getFolderList();
        }
    });
}

function getParentFolder() {
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

    form_data.append('name', filename);
    form_data.append('desc', $('#txtFileDesc').val());
    form_data.append('current', currenFolder);
    $.ajax({
        url: 'php/Business/UploadFile.php',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        success: function(msg) {

            $("#inputFile").val("");
            $('#txtFileDesc').val("");
            $("#txtFileName").val("");

            getFileList();

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
        success: function(json) {
            $("#folderList").empty();

            Object.values(json).forEach(function(data) {
                if (data[1] != null && data[1].uuid != recycle && (data[5] == 0 || data[5] == 1)) {

                    var str = '<li>\
                <div id="' + data[1].uuid + '" class="folderItem" onclick="triggerFolderChoosedTools(this.id);" ondblclick="setPath(this.id);">\
                    <span class="name">' + data[0] + '</span>\
                    <p class="size" style="display: none;">' + parseSize(data[2].value) + '</p>\
                    <p class="date" style="display: none;">' + parseDate(new Date(data[3].seconds * 1000)) + '</p>\
                    <p class="desc" style="display: none;">' + data[4] + '</p>\
                </div>\
                </li>';

                    $("#folderList").append(str);
                }
            });

            if (currenFolder == home) {
                var str = '<li>\
                <div id="' + recycle + '" class="folderItem" onclick="triggerFolderChoosedTools(this.id);" ondblclick="setPath(this.id);">\
                    <span class="name">Recycle Bin</span>\
                </div>\
                </li>';

                $("#folderList").append(str);
            }

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
        success: function(json) {
            $("#fileList").empty();
            Object.values(json).forEach(function(data) {

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
        success: function(msg) {
            console.log(msg);
            if (type == 0) {
                getFileList();
            } else {
                getFolderList();
            }
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
        default:
            return "UNKNOWN";
    }
}