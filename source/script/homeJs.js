var currenFolder = '415fe87d-9b3b-4a4c-8f64-b380d2b39240';

function setPath(id) {

    currenFolder = id;
    $("#folderList").empty();
    $("#fileList").empty();

    getFolderList();
    getFileList();

}

function triggerFileChoosedTools(file) {
    var fileId = '#' + file;


    $(document).mouseup(function(e) {
        var container = $(fileId);

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.find(".fileCaption").css({
                "background-color": "#ebebd7",
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

    $("#infoFileName").text("Name: " + $(fileId).find(".fileCaption").find(".name").text());
    $("#infoFileType").text("Type: " + $(fileId).find(".fileCaption").find(".type").text());
    $("#infoFileSize").text("Size: " + $(fileId).find(".fileCaption").find(".size").text());
    $("#infoFileDate").text("Date modified: " + $(fileId).find(".fileCaption").find(".date").text());
    $("#infoFileDesc").text("Desciption: " + $(fileId).find(".fileCaption").find(".desc").text());

    $("#infoTip").hide();

    $("#btnDelete").click(function() {
        $(fileId).parent().remove();
    });
}

function triggerFolderChoosedTools(folder) {
    var folderId = '#' + folder;

    $("#toolBar").css({
        "display": "block"
    });
    $(folderId).css({
        "background-color": "#90adff"
    })

    $(document).mouseup(function(e) {
        var container = $(folderId);

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.css({
                "background-color": "#f2f2f2"
            })

            $("#info p").text("");
            $("#infoTip").show();

            $("#infoFileType").show();

        }
    });


    $("#infoFileName").text("Name: " + $(folderId).find(".name").text());
    $("#infoFileType").hide();
    $("#infoFileSize").text("Size: " + $(folderId).find(".size").text());
    $("#infoFileDate").text("Date modified: " + $(folderId).find(".date").text());
    $("#infoFileDesc").text("Desciption: " + $(folderId).find(".desc").text());

    $("#infoTip").hide();
}

$(window).scroll(function() {

    if ($(this).scrollTop() > 0) {
        $('#searchBar').fadeOut();
    } else {
        $('#searchBar').fadeIn();
    }
});

$(document).ready(function() {
    getIdEmail();

    getFolderList();
    getFileList();



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
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            container.hide();
        }
    });

    $("#infoIcon").on("click", function() {
        if ($("#filePanel").attr("class") == "col-lg-7") {
            $("#filePanel").attr("class", "col-lg-10");
            $("#infoPanel").hide();
        } else {
            $("#filePanel").attr("class", "col-lg-7");
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
    })



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
            success: function() {

                $("#inputFile").val("");
                $('#txtFileDesc').val("");

                getFileList();
            }
        });
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
    xmlhttp.open("GET", "php/getHomeInfo.php", true);
    xmlhttp.send();
}

function logOut() {
    $.ajax({
        url: 'php/Logout.php',
        type: "POST",
        contentType: false,
        processData: false,
        success: function(data) {
            //alert(data);
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
                    var size;
                    var tmpSize = data[2].value;
                    if (tmpSize < 1024) {
                        size = numberWithCommas(tmpSize) + " bytes";
                    } else if (tmpSize < 1024 * 1024) {
                        size = parseInt(tmpSize / 1024) + " KB (" + numberWithCommas(tmpSize) + " bytes)";
                    } else if (tmpSize < 1024 * 1024 * 1024) {
                        size = parseInt(tmpSize / (1024 * 1024)) + " MB (" + numberWithCommas(tmpSize) + " bytes)";
                    } else {
                        size = parseInt(tmpSize / (1024 * 1024 * 1024)) + " GB (" + numberWithCommas(tmpSize) + " bytes)";
                    }

                    var date;
                    var tmpDate = new Date(data[3].seconds * 1000);
                    var day = tmpDate.getDate();
                    var month = (tmpDate.getMonth() + 1 < 10) ? ('0' + (tmpDate.getMonth() + 1)) : (tmpDate.getMonth() + 1);
                    var year = tmpDate.getFullYear();
                    var hour = (tmpDate.getHours() < 10) ? ('0' + tmpDate.getHours()) : tmpDate.getHours();
                    var minute = (tmpDate.getMinutes() < 10) ? ('0' + tmpDate.getMinutes()) : tmpDate.getMinutes();
                    date = year + '-' + month + '-' + day + ' at ' + hour + ':' + minute;


                    var str = '<li>\
                <div id="' + data[1].uuid + '" class="folderItem" onclick="triggerFolderChoosedTools(this.id);" ondblclick="setPath(this.id);">\
                    <span class="name">' + data[0] + '</span>\
                    <p class="size" style="display: none;">' + size + '</p>\
                    <p class="date" style="display: none;">' + date + '</p>\
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
                    var size;
                    var tmpSize = data[3].value;
                    if (tmpSize < 1024) {
                        size = numberWithCommas(tmpSize) + " bytes";
                    } else if (tmpSize < 1024 * 1024) {
                        size = parseInt(tmpSize / 1024) + " KB (" + numberWithCommas(tmpSize) + " bytes)";
                    } else if (tmpSize < 1024 * 1024 * 1024) {
                        size = parseInt(tmpSize / (1024 * 1024)) + " MB (" + numberWithCommas(tmpSize) + " bytes)";
                    } else {
                        size = parseInt(tmpSize / (1024 * 1024 * 1024)) + " GB (" + numberWithCommas(tmpSize) + " bytes)";
                    }

                    var date;
                    var tmpDate = new Date(data[1].seconds * 1000);
                    var day = tmpDate.getDate();
                    var month = (tmpDate.getMonth() + 1 < 10) ? ('0' + (tmpDate.getMonth() + 1)) : (tmpDate.getMonth() + 1);
                    var year = tmpDate.getFullYear();
                    var hour = (tmpDate.getHours() < 10) ? ('0' + tmpDate.getHours()) : tmpDate.getHours();
                    var minute = (tmpDate.getMinutes() < 10) ? ('0' + tmpDate.getMinutes()) : tmpDate.getMinutes();
                    date = year + '-' + month + '-' + day + ' at ' + hour + ':' + minute;

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
                        <p class="size" style="display: none;">' + size + '</p>\
                        <p class="date" style="display: none;">' + date + '</p>\
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
