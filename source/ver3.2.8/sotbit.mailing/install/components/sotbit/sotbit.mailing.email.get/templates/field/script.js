$(document).ready(function() {
    
    if($('#sotbit_reg_pannel').length) {      
        var window_width = $(window).width();
        if(window_width < 880) {
            if(window_width >= 640) {
               $('#sotbit_reg_pannel .sotbit_hide_880').hide(); 
            } else {
               $('#sotbit_reg_pannel').hide();  
            }   
        } 
        
       /* for the input form */
       $('#sotbit_reg_pannel input[title]').each(function() {
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
                    $('#sotbit_reg_pannel').show();
                    $('#sotbit_reg_pannel .sotbit_hide_880').hide(); 
                } else {
                    $('#sotbit_reg_pannel').hide();  
                }
            } else {
                $('#sotbit_reg_pannel').show(); 
                $('#sotbit_reg_pannel .sotbit_hide_880').show();  
            }        
    }); 
   }
             
});


function SotbitPannelSlide() {
    var pannel_h = $("#sotbit_reg_pannel").outerHeight();
    if($("#sotbit_reg_pannel").hasClass("open")) {
        $("#sotbit_reg_pannel").animate({bottom: "-"+pannel_h}, 500, function(){
            $("#sotbit_reg_pannel").removeClass("open");
            $.cookie("sotbit_reg_pannel_open", 'N', {path: '/'});
        });
    } else {
        $("#sotbit_reg_pannel").animate({bottom: 0}, 500, function(){
            $("#sotbit_reg_pannel").addClass("open");
            $.cookie("sotbit_reg_pannel_open", 'Y', {path: '/'});
        });
    };
};