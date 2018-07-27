var currenFolder = '415fe87d-9b3b-4a4c-8f64-b380d2b39240';
var choosedFile = "";
var choosedFolder = "";
var type = -1;

function setPath(id) {
    currenFolder = id;
    $("#folderList").empty();
    $("#fileList").empty();

    getFolderList();
    getFileList();
}

// Check if click event outside of div
function checkOutside(container, e) {
    return !container.is(e.target) &&
        container.has(e.target).length === 0 &&
        !$("#infoPanel").is(e.target) &&
        !$("#infoPanel").find("*").is(e.target) &&
        !$("#modalRename").is(e.target) &&
        !$("#modalRename").find("*").is(e.target) &&
        !$("#btnRename").is(e.target);
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
        }
    });

    $("#infoFileName").text($(folderId).find(".name").text());
    $("#infoFileType").hide();
    $("#infoFileSize").text("Size: " + $(folderId).find(".size").text());
    $("#infoFileDate").text("Date modified: " + $(folderId).find(".date").text());
    $("#infoFileDesc").text("Desciption: " + $(folderId).find(".desc").text());
    $("#infoTip").hide();
}

// $(window).scroll(function() {

//     if ($(this).scrollTop() > 0) {
//         $('#searchBar').fadeOut();
//     } else {
//         $('#searchBar').fadeIn();
//     }
// });

$(document).ready(function() {
    if (currenFolder == '415fe87d-9b3b-4a4c-8f64-b380d2b39240'){
        $("#folderLabel").hide();
    }
    else{
        $("#folderLabel").show();
    }

    
    getIdEmail();
    getFolderList();
    getFileList();

    $("#download").click(function() {
        downFile(choosedFile);
    })

    $('.dropdown').on('show.bs.dropdown', function(e) {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
    });

    $('.dropdown').on('hide.bs.dropdown', function(e) {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
    });

    //Hide tool bar when click outside of it
    $(document).mouseup(function(e) {
        var container = $('#toolBar');

        // if the target of the click isn't the container nor a descendant of the container
        if (checkOutside(container, e)) {
            container.hide();
        }
    });

    $("#infoIcon").on("click", function() {
        if ($("#filePanel").attr("class") == "col-xs-7") {
            $("#filePanel").attr("class", "col-xs-10");
            $("#infoPanel").hide();
        } else {
            $("#filePanel").attr("class", "col-xs-7");
            $("#infoPanel").show();
        }
    });

    $('#modalNewFolder').on('shown.bs.modal', function() {
        $('#folderName').val("");
        $('#folderName').focus();
    });

    $(document).keypress(function(e) {
        if ($("#modalNewFolder").hasClass('in') && (e.keycode == 13 || e.which == 13)) {
            $('#createFolderConfirm').click();
        }
    });

    $(document).keypress(function(e) {
        if ($("#modalUploadFile").hasClass('in') && (e.keycode == 13 || e.which == 13)) {
            $('#uploadConfirm').click();
        }
    });

    $(document).keypress(function(e) {
        if ($("#modalRename").hasClass('in') && (e.keycode == 13 || e.which == 13)) {
            $('#renameConfirm').click();
        }
    });

    $("#createFolderConfirm").on("click", function() {
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
    });

    $("#backIcon").click(function() {
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
                getFolderList();
                getFileList();
            }
        });
    })

    $("#uploadConfirm").click(function() {
        var data = $("#inputFile").prop("files")[0];
        var form_data = new FormData();
        form_data.append('file', data);

        var fullPath = $("#inputFile").val();

        var startIndex = (fullPath.indexOf('\\') >= 0 ? fullPath.lastIndexOf('\\') : fullPath.lastIndexOf('/'));
        var filename = fullPath.substring(startIndex);
        if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0) {
            filename = filename.substring(1);
        }
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
                console.log(msg);
                $("#inputFile").val("");
                $('#txtFileDesc').val("");

                getFileList();

            }
        });
    })

    $("#renameConfirm").click(function() {
        if (type == 0)
            rename(type, choosedFile);
        else
            rename(type, choosedFolder);
    })
});

function getIdEmail() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var myObj = JSON.parse(this.responseText);
            var email = myObj[0];
            var id = myObj[1];
            console.log(email);
            console.log(id);
        }
    };
    xmlhttp.open("GET", "php/Business/getUserInfo.php", true);
    xmlhttp.send();
}

function logOut() {
    $.ajax({
        url: 'php/Business/Logout.php',
        type: "POST",
        contentType: false,
        processData: false,
        success: function(data) {

        }
    });
}

const numberWithCommas = (x) => {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
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
                if (data[1] != null) {

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

                if (data[0] != null) {
                    var type;
                    switch (data[6]) {
                        case 7:
                            type = "JPG";
                            break;
                        case 8:
                            type = "PNG";
                            break;
                        default:
                            type = "UNKNOWN";
                    }
                    var str = '<li>\
                <div id="' + data[0].uuid + '" class="fileItem" onclick="triggerFileChoosedTools(this.id);" ondblclick="viewImg(this.id);">\
                    <div>\
                        <img src=' + data[5] + '>\
                    </div>\
                    <div class="fileCaption">\
                        <p class="name">' + data[2] + '</p>\
                        <p class="size" style="display: none;">' + parseSize(data[3].value) + '</p>\
                        <p class="date" style="display: none;">' + parseDate(new Date(data[1].seconds * 1000)) + '</p>\
                        <p class="type" style="display: none;">' + type + '</p>\
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
                var url;
                var fileName;
                if (json[2] == 8) {
                    url = URL.createObjectURL(dataURItoBlob("data:image/png;base64," + json[1]));
                    fileName = json[0] + ".png";
                } else if (json[2] == 7) {
                    url = URL.createObjectURL(dataURItoBlob("data:image/jpg;base64," + json[1]));
                    fileName = json[0] + ".png";
                }
                $("#link").attr({
                    "download": fileName,
                    "href": url
                }).get(0).click();

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
