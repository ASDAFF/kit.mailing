<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$frame = $this->createFrame()->begin();
?>

<div class="sotbit_mailing_subscr_wrap">                    
    <div class="sotbit_mailing_subscr_wrap_in">
        <label><?=$arParams["INFO_TEXT"]?></label>
        <form id="sotbit_mailing_subsrib" method="post" action="#">   
            <input type="text" value="<?=$arResult['SUBSCRIBER_INFO']['EMAIL_TO']?>" name="EMAIL_TO" title="<?=GetMessage('PANNEL_EMAIL')?>" placeholder="<?=GetMessage('PANNEL_EMAIL')?>" class="sotbit_mailing_subscr_wrap_email">
            <?if($arParams["CATEGORIES_SHOW"]=='Y' && count($arParams["CATEGORIES_ID"])>0):?>
                <div class="sotbit_mailing_subsrib_categories">
                <p><?=GetMessage('CATEGORIES_TITLE');?></p>
                <?foreach($arParams["CATEGORIES_ID"] as $category_id):?> 
                <input name="CATEGORIES_ID_CHECK[]" value="<?=$arResult['CATEGORIES'][$category_id]['ID']?>" type="checkbox" <?if($arResult['CATEGORIES'][$category_id]['CHECKED']=='Y'):?>checked="checked"<?endif;?>/> <?=$arResult['CATEGORIES'][$category_id]['NAME']?> <br />    
                <?endforeach;?> 
                </div> 
            <?endif;?>  
                      
            <input type="submit" value="<?=GetMessage('PANNEL_SEND')?>" >

        </form>
        <div class="sotbit_mailing_subscr_wrap_answer"></div>
    </div>
</div>                      


      
                                             
<script type="text/javascript">   
$("#sotbit_mailing_subsrib").on('submit', function(){                                                                        
    var email = $("#sotbit_mailing_subsrib input[name=EMAIL_TO]").val();
    var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
    if(email == "<?=GetMessage('AUTH_REGISTER_PANNEL_EMAIL')?>") {
         var email = "";
    }
    
    if(pattern.test(email)) {

        var params = "<?=$arResult['STR_PARAMS']?>";
        var form_send = $(this).serialize(); 
          
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
            data: 'getemail=Y&'+ params + '&'+ form_send,
            onsuccess: function(data){
                data = JSON.parse(data);
                if(data.error == 'subscribed'){
                    document.querySelector('.sotbit_mailing_subscr_wrap_in form').remove();
                    document.querySelector('.sotbit_mailing_subscr_wrap_answer').innerHTML ="<?=GetMessage('SUBSCRIBE')?>";
                }else {
                    $('.sotbit_mailing_subscr_wrap_in form').remove();
                    $('.sotbit_mailing_subscr_wrap_answer').html('<?=$EMAIL_SEND_END?>');
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
.sotbit_mailing_subscr_wrap_in input[type="submit"]{   
    background-color: #<?=$arParams["COLOR_BUTTON"]?>;    
}
</style>
<?$frame->end();?>