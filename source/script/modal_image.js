function viewImg(id) {
    id = "#" + id;
    
    $("#modalImg").css("display", "block");
    $("#preview-img").attr("src", $(id).find("img").attr("src"));
    $("#caption").find("p").text($(id).find(".name").text());

    wheelzoom($("#preview-img"));

    
    $("#preview-img").dblclick(function(){
        document.querySelector('img#preview-img').dispatchEvent(new CustomEvent('wheelzoom.reset'));
    })

    // When the user clicks on <span> (x), close the modal
    $("#close").click(function(){
        $("#modalImg").css("display", "none");
        document.querySelector('img#preview-img').dispatchEvent(new CustomEvent('wheelzoom.destroy'));
    });


    // When the user press ESC close the modal
    $(document).keyup(function(e) {
        if (e.keyCode == 27) { 
            $("#modalImg").css("display", "none");
            document.querySelector('img#preview-img').dispatchEvent(new CustomEvent('wheelzoom.destroy'));
       }
   });
}