<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$frame = $this->createFrame()->begin();
CJSCore::Init(array("ajax"));
?>

     
<div id="sotbit_reg_panel_form_info_click" style="display: none;">
    <div class="reg_bottom_pannel_in_wrap_text">
        <?=$arParams["~MODAL_TEXT"]?>
    </div>
    <div class="reg_bottom_pannel_in_wrap">                    
        <div class="bottom_pannel_in">
            <label><?=$arParams["PANEL_TEXT"]?></label>
            <form id="sotbit_reg_panel_form_time_open" method="post" action="#">
                <input type="text" value="<?=$arResult['SUBSCRIBER_INFO']['EMAIL_TO']?>" name="EMAIL_TO" title="<?=GetMessage('PANNEL_EMAIL')?>" placeholder="<?=GetMessage('PANNEL_EMAIL')?>">
                <input type="submit" value="<?=GetMessage('PANNEL_SEND')?>" onclick="form_time_open();return false;">
            </form>
        </div>
    </div>  
</div>

   
   
 
    
      
<script type="text/javascript"> 

$(document).on('click', '<?=$arParams["ELEMENT_CLICK"]?>', function(event) {
    sotbit_modal_show($('#sotbit_reg_panel_form_info_click').html(), 'sotbit_reg_form');          
});

function form_time_open(){
    var email = $(".sotbit_reg_form #sotbit_reg_panel_form_time_open input[name=EMAIL_TO]").val();
    var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
    if(email == "<?=GetMessage('AUTH_REGISTER_PANNEL_EMAIL')?>") {
         var email = "";
    }
    
    if(pattern.test(email)) {

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
                    $('.bottom_pannel_in form').remove();
                    $('.reg_bottom_pannel_in_wrap_text').html('<?=GetMessage('SUBSCRIBE')?>');
                }else {
                    $('.bottom_pannel_in form').remove();
                    $('.reg_bottom_pannel_in_wrap_text').html('<?=$EMAIL_SEND_END?>');
                }
            
            },
        });          
        
    } else {
         var email = "";       
    }
    
    return false;    
} 
</script>



         
<style> 
.sotbit_reg_form .modal-block  {
   background-color: #<?=$arParams["COLOR_MODAL_BORDER"]?>; 
   padding: <?=$arParams["MODAL_BORDER_PADDING"]?>px;
   max-width: <?=$arParams["MODAL_BG_WIDTH"]?>px  
}
.sotbit_reg_form .modal-block-inner {
   background-color: #<?=$arParams["COLOR_MODAL_BG"]?>; 
   padding: <?=$arParams["MODAL_BG_PADDING"]?>px    
}
</style>
<?$frame->end();?>