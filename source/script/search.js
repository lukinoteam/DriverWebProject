$(document).ready(function() {
    $('#searchBar').keypress(function(event) {
        if (event.keyCode == 13) {
            $('#btnSearch').click();
        }
    });

    $("input[name$='optradio']").click(function() {
        $("#adv-search").find(".dropdown-toggle").click();

        if ($('#radioName').is(':checked')) {
            $('#searchBar').attr("placeholder", "Name...")
        }
        if ($('#radioSize').is(':checked')) {
            $('#searchBar').attr("placeholder", "Size...")
        }
        if ($('#radioDate').is(':checked')) {
            $('#searchBar').attr("placeholder", "Date...")
        }
        if ($('#radioExt').is(':checked')) {
            $('#searchBar').attr("placeholder", "Extension...")
        }
        if ($('#radioContent').is(':checked')) {
            $('#searchBar').attr("placeholder", "Content...")
        }
    });

    $('#btnSearch').click(function() {
        $("#snackbar").css("visibility", "visible");
        search();
    });
})

function search() {
    var sender = new FormData();
    var content = ($("#searchBar").val());
    sender.append('key', content);
    if ($('#radioName').is(':checked')) {
        sender.append('type', 'Name');
    }
    if ($('#radioSize').is(':checked')) {
        sender.append('type', 'Size');
    }
    if ($('#radioDate').is(':checked')) {
        sender.append('type', 'Date');
    }
    if ($('#radioExt').is(':checked')) {
        sender.append('type', 'Extension');
    }
    if ($('#radioContent').is(':checked')) {
        sender.append('type', 'Content');
    }
  
    $.ajax({
        url: 'php/Business/Search.php',
        cache: false,
        contentType: false,
        processData: false,
        data: sender,
        type: 'post',
        dataType: 'json',
        success: function(json) {
            $("#fileList").empty();
            $("#folderList").empty();

            Object.values(json).forEach(function(data) {

                if (data[0] != null && (data[7] == 1 || data[7] == 0)) {

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
