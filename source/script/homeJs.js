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



$(document).ready(function() {

    getIdEmail();
    getFolderList();
    getFileList();

    $("#home").click(function(){
        setPath(home);
    })

    $("#inputFile").change(function() {
        str = $("#inputFile").val()
        $("#txtFileName").val(str.substring(12, str.length));
    });

    $("#backIcon").hide();

    $("#btnDownload").click(function() {
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
        createFolder();
    });

    $("#backIcon").click(function() {
        getParentFolder();
    })

    $("#uploadConfirm").click(function() {
        uploadFile();
    })

    $("#renameConfirm").click(function() {
        if (type == 0)
            rename(type, choosedFile);
        else
            rename(type, choosedFolder);
    })

    $("#btnDelete").click(function() {
        if (type == 0)
            deleteff(type, choosedFile);
        else
            deleteff(type, choosedFolder);
    })
});

function getIdEmail() {
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
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
        success: function(data) {

        }
    });
}