function sotbit_modal_show(content, class_) {
        var block = "<div class='modal-window "+class_+"'><div class='modal-window-bg'>&nbsp;</div>"+
        "<div class='wrap-out'><div class='container'><div class='row'><div class='modal-block'>"+
        "<div class='modal-block-inner'><span class='close'></span><div class='modal-content'>"+content+
        "</div></div></div></div></div></div></div>";
        $("body").append(block);
        var document_height = $(document).height();
        var scrollTop = $(window).scrollTop() + 200;
        $('.modal-window').height(document_height);
        $('.wrap-out').css("top", scrollTop);
        $('.modal-window').animate({opacity: 1}, 400 );
        sotbit_modal_close(class_);
}

function sotbit_modal_close(class_) {
    $(document).on('click', '.modal-block .close', function(){
            if($("."+class_).length) {
                  $("."+class_).fadeOut(400,function(){
                  $(this).remove();
              });
            }
    });

    $(document).on('click', '.'+class_, function(event) {
            if ($(event.target).closest(".modal-block").length) return;
                $("."+class_).fadeOut(400,function(){
                $(this).remove();
            });
    });
}


