$(document).ready(function() {
    $('#btnSearch').click(function() {
        console.log($("#searchBar").val());
        
        if ($('#radioName').is(':checked')) { 
            console.log("Name Search!"); 
        }
        if ($('#radioSize').is(':checked')) { 
            console.log("Size Search!"); 
        }
        if ($('#radioDate').is(':checked')) { 
            console.log("Date Search!"); 
        }
        if ($('#radioExt').is(':checked')) { 
            console.log("Extension Search!"); 
        }
        if ($('#radioContent').is(':checked')) { 
            console.log("Content Search!"); 
        }
    });
})