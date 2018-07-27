$(document).ready(function() {
    $('#btnSearch').click(function() {
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
            success: function(msg) {
                console.log(msg);
            }
        });
    });
})
