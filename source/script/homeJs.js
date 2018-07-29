var home = '415fe87d-9b3b-4a4c-8f64-b380d2b39240';
var recycle = 'd2cea1a3-b55e-49ae-956f-1ea7ba9b33a1';
var currenFolder = home;
var choosedFile = "";
var choosedFolder = "";
var type = -1;

function setPath(id) {
    $("#toolBar").hide();
    $("#infoTip").show();
    $("#info p").text("");
    currenFolder = id;
    $("#folderList").empty();
    $("#fileList").empty();

    if (currenFolder == home) {
        $("#backIcon").hide();
    } else {
        $("#backIcon").show();
    }

    getFolderList();
    getFileList();
}

// Check if click event outside of div
function checkOutside(container, e) {
    return !container.is(e.target) &&
        container.has(e.target).length === 0 &&
        !$("#infoIcon").is(e.target) &&
        !$("#infoIcon").find("*").is(e.target) &&
        !$("#infoPanel").is(e.target) &&
        !$("#infoPanel").find("*").is(e.target) &&
        !$("#modalRename").is(e.target) &&
        !$("#modalRename").find("*").is(e.target) &&
        !$("#btnDownload").is(e.target) &&
        !$("#btnDelete").is(e.target) &&
        !$("#btnRename").is(e.target);
}

function triggerFileChoosedTools(file) {
    var fileId = '#' + file;
    choosedFile = file;
    type = 0;

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

    if (folder != recycle) {

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
    } else {
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

        $("#infoFileName").text($(folderId).find(".name").text());
        $("#infoTip").hide();
        //get all deleted file:
        $.ajax({
            url: 'php/Business/RecycleBin.php',
            cache: false,
            contentType: false,
            processData: false,
            type: 'post',
            dataType: 'json',
            success: function (json) {
                $("#fileList").empty();
                $("#info p").text("");
                $("#infoTip").show();
                $("#toolBar").hide();
                Object.values(json).forEach(function (data) {
                    if (data[0] != null && (data[7] == -1 || data[7] == 0)) {
                        console.log('got data');
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
}

$(document).ready(function () {

    getIdEmail();
    getFolderList();
    getFileList();

    $("#inputFile").change(function () {
        str = $("#inputFile").val()
        $("#txtFileName").val(str.substring(12, str.length));
    });

    $("#backIcon").hide();

    $("#btnDownload").click(function () {
        downFile(choosedFile);
    })

    $('.dropdown').on('show.bs.dropdown', function (e) {
        $(this).find('.dropdown-menu').first().stop(true, true).slideDown(300);
    });

    $('.dropdown').on('hide.bs.dropdown', function (e) {
        $(this).find('.dropdown-menu').first().stop(true, true).slideUp(200);
    });

    //Hide tool bar when click outside of it
    $(document).mouseup(function (e) {
        var container = $('#toolBar');

        // if the target of the click isn't the container nor a descendant of the container
        if (checkOutside(container, e)) {
            container.hide();
        }
    });

    $("#infoIcon").on("click", function () {

        if ($("#filePanel").attr("class") == "col-xs-7") {
            $("#filePanel").attr("class", "col-xs-10");
            $("#infoPanel").hide();
        } else {
            $("#filePanel").attr("class", "col-xs-7");
            $("#infoPanel").show();
        }
    });

    $('#modalNewFolder').on('shown.bs.modal', function () {
        $('#folderName').val("");
        $('#folderName').focus();
    });

    $(document).keypress(function (e) {
        if ($("#modalNewFolder").hasClass('in') && (e.keycode == 13 || e.which == 13)) {
            $('#createFolderConfirm').click();
        }
    });

    $(document).keypress(function (e) {
        if ($("#modalUploadFile").hasClass('in') && (e.keycode == 13 || e.which == 13)) {
            $('#uploadConfirm').click();
        }
    });

    $(document).keypress(function (e) {
        if ($("#modalRename").hasClass('in') && (e.keycode == 13 || e.which == 13)) {
            $('#renameConfirm').click();
        }
    });

    $("#createFolderConfirm").on("click", function () {
        createFolder();
    });

    $("#backIcon").click(function () {
        getParentFolder();
    })

    $("#uploadConfirm").click(function () {
        uploadFile();
    })

    $("#renameConfirm").click(function () {
        if (type == 0)
            rename(type, choosedFile);
        else
            rename(type, choosedFolder);
    })

    $("#btnDelete").click(function () {
        if (type == 0)
            deleteff(type, choosedFile);
        else
            deleteff(type, choosedFolder);
    })
});

function getIdEmail() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            var myObj = JSON.parse(this.responseText);
            var email = myObj[0];
            var id = myObj[1];
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
        success: function (data) {

        }
    });
}