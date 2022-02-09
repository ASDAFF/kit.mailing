<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$frame = $this->createFrame()->begin();
if($_COOKIE['MAILING_USER_MAIL_GET'] == 'Y') {
    return false;    
}
?>
      
<div class="sotbit_reg_pannel<?if($_COOKIE['sotbit_reg_pannel_open'] == "N"):?> <?else:?> open<?endif;?>" id="sotbit_reg_pannel">
    <div class="f-sub-close toClose" onclick="SotbitPannelSlide()">
        <div class="f-actions"></div>
        <div class="f-actions-hor"></div>
    </div>
    <div class="reg_bottom_pannel_in_wrap">                    
        <div class="bottom_pannel_in">
            <label><?=$arParams["PANEL_TEXT"]?></label>
            <form id="sotbit_reg_panel_form" method="post" action="#">
                <input type="text" value="<?=$arResult['SUBSCRIBER_INFO']['EMAIL_TO']?>" name="EMAIL_TO" title="<?=GetMessage('PANNEL_EMAIL')?>" placeholder="<?=GetMessage('PANNEL_EMAIL')?>">
                <input type="submit" value="<?=GetMessage('PANNEL_SEND')?>">
            </form>
        </div>
    </div>                      
</div>
      
      
 
 
                                           
<script type="text/javascript">   
$("#sotbit_reg_panel_form").on('submit', function(){                                                                        
    var email = $("#sotbit_reg_panel_form input[name=EMAIL_TO]").val();
    var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
    if(email == "<?=GetMessage('AUTH_REGISTER_PANNEL_EMAIL')?>") {
         var email = "";
    }
    
    if(pattern.test(email)) {

        var class_ = "sotbit_reg_form";
        var params = "<?=$arResult['STR_PARAMS']?>";
                     
       
        <?
        if($arParams["EMAIL_SEND_END"]){
            $EMAIL_SEND_END = $arParams["~EMAIL_SEND_END"];
        } else{
            $EMAIL_SEND_END = GetMessage('EMAIL_SEND_END');
        }
        ?>       
                             
        BX.ajax({
            url: "<?=$this->GetFolder() ?>/ajax.php",                                
            method: 'POST',
            dataType: 'html',
            data: 'getemail=Y&EMAIL_TO=' + email +'&'+ params,
            onsuccess: function(data){
                data = JSON.parse(data);
                if(data.error == 'subscribed'){
                    document.querySelector('.bottom_pannel_in form').remove();
                    document.querySelector('.bottom_pannel_in label').innerHTML ="<?=GetMessage('SUBSCRIBE')?>";
                }else{
                    $('.bottom_pannel_in form').remove();
                    $('.bottom_pannel_in label').html('<?=$EMAIL_SEND_END?>');
                }

            
            },
        });          
        
    }  else {
         var email = "";       
    }

    return false;
});

  

</script>
    
<style>
#sotbit_reg_pannel {
    background-color: #<?=$arParams["COLOR_PANEL"]?>;
    box-shadow: 0 -2px 10px 0 #<?=$arParams["COLOR_PANEL"]?>;    
}
#sotbit_reg_pannel .reg_bottom_pannel_in_wrap {
    padding-top: 8px;
}
.f-sub-close {
    background-color: #<?=$arParams["COLOR_PANEL"]?>;
    box-shadow:  0px -2px 10px 0px #<?=$arParams["COLOR_PANEL"]?>;
    -webkit-box-shadow:  0px -2px 10px 0px #<?=$arParams["COLOR_PANEL"]?>;
    -moz-box-shadow:  0px -2px 10px 0px #<?=$arParams["COLOR_PANEL"]?>;    
}
.reg_bottom_pannel_in_wrap,
.f-sub-close {
    border-color: #<?=$arParams["COLOR_BORDER_PANEL"]?>;    
}
.f-sub-close.toClose .f-actions,
.f-sub-close.toClose .f-actions-hor {
   background-color: #<?=$arParams["COLOR_PANEL_OPEN"]?>; 
}
</style>

<?$frame->end();?>