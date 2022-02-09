$(document).ready(function() {
    if($('#kit_reg_pannel').length) {
        var window_width = $(window).width();
        if(window_width < 880) {
            if(window_width >= 640) {
               $('#kit_reg_pannel .kit_hide_880').hide();
            } else {
               $('#kit_reg_pannel').hide();
            }   
        } 
        
       /* for the input form */
       $('#kit_reg_pannel input[title]').each(function() {
          if($(this).val() === '') {$(this).val($(this).attr('title'));}

          $(this).focus(function() {
              if($(this).val() === $(this).attr('title')) {$(this).val('');}
          });

          $(this).blur(function() {
              if($(this).val() === '') {$(this).val($(this).attr('title'));}
          });
       });
       
    $(window).bind("resize", function(){
            var window_width = $(window).width();
            if(window_width < 880) {
                if(window_width >= 640) {
                    $('#kit_reg_pannel').show();
                    $('#kit_reg_pannel .kit_hide_880').hide();
                } else {
                    $('#kit_reg_pannel').hide();
                }
            } else {
                $('#kit_reg_pannel').show();
                $('#kit_reg_pannel .kit_hide_880').show();
            }        
    }); 
   }          
});


function KitPannelSlide() {
    var pannel_h = $("#kit_reg_pannel").outerHeight();
    if($("#kit_reg_pannel").hasClass("open")) {
        $("#kit_reg_pannel").animate({bottom: "-"+pannel_h}, 500, function(){
            $("#kit_reg_pannel").removeClass("open");
            $.cookie("kit_reg_pannel_open", 'N', {path: '/'});
        });
    } else {
        $("#kit_reg_pannel").animate({bottom: 0}, 500, function(){
            $("#kit_reg_pannel").addClass("open");
            $.cookie("kit_reg_pannel_open", 'Y', {path: '/'});
        });
    };
};