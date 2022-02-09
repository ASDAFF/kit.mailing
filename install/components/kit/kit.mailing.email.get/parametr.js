

function ModalTextArea() {
    var textfield = $('input[name="MODAL_TEXT"]'); 
        textfield.after($('<textarea name="' + textfield.attr('name') + '">' + textfield.val() + '</textarea>')); 
        textfield.remove()  
        
    var textfield_EMAIL_SEND_END = $('input[name="EMAIL_SEND_END"]'); 
        textfield_EMAIL_SEND_END.after($('<textarea name="' + textfield_EMAIL_SEND_END.attr('name') + '">' + textfield_EMAIL_SEND_END.val() + '</textarea>')); 
        textfield_EMAIL_SEND_END.remove() 
                
}


