<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$frame = $this->createFrame()->begin();
CJSCore::Init(array("ajax"));

if($_COOKIE['MAILING_USER_MAIL_GET'] == 'Y')
{
    return false;
}
?>



<div id="sotbit_reg_panel_form_info" style="display: none;">
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
    function sotbit_modal_time()
    {
        sotbit_modal_show($('#sotbit_reg_panel_form_info').html(), 'sotbit_reg_form');
        $('#sotbit_reg_panel_form_info').remove();
    }

    <?/*if(($_COOKIE["MAILING_EMAIL_POPUP_TIME"] != 'Y') && (!empty($_COOKIE["MAILING_EMAIL_POPUP_TIME_FIRST"])) && (mktime() > $_COOKIE["MAILING_EMAIL_POPUP_TIME_FIRST"] + $arParams['MODAL_TIME_SECOND_OPEN'])):*/?>
        //setTimeout(sotbit_modal_time, 2000);
        <?/*SetCookie("MAILING_EMAIL_POPUP_TIME", 'Y', time() + $arParams['MODAL_TIME_DAY_NOW'] * 24 * 60 * 60, '/', $_SERVER['SERVER_NAME']);*/?>
    <?/*elseif(empty($_COOKIE["MAILING_EMAIL_POPUP_TIME_FIRST"])):*/?>
        <?/*SetCookie("MAILING_EMAIL_POPUP_TIME_FIRST", mktime(), time() + $arParams['MODAL_TIME_DAY_NOW'] * 24 * 60 * 60, '/', $_SERVER['SERVER_NAME']);*/?>
    <?/*endif;*/?>

    <?if((empty($_COOKIE["MAILING_EMAIL_POPUP_TIME"]) || empty($_COOKIE["MAILING_EMAIL_POPUP_TIME_FIRST"])) || (mktime() > $_COOKIE["MAILING_EMAIL_POPUP_TIME_FIRST"] + $arParams['MODAL_TIME_DAY_NOW'] * 24 * 60 * 60)):?>
        setTimeout(function() {
            sotbit_modal_time();
            document.cookie = "MAILING_EMAIL_POPUP_TIME=Y; expires=<?=(time() + $arParams['MODAL_TIME_DAY_NOW'] * 24 * 60 * 60)?>; path=/; domain=<?=$_SERVER['SERVER_NAME']?>;";
            document.cookie = "MAILING_EMAIL_POPUP_TIME_FIRST=<?=mktime()?>; expires=<?=(time() + $arParams['MODAL_TIME_DAY_NOW'] * 24 * 60 * 60)?>; path=/; domain=<?=$_SERVER['SERVER_NAME']?>;";
        }, <?=(int)$arParams["MODAL_TIME_SECOND_OPEN"] * 1000?>);
    <?endif?>

    function form_time_open()
    {
        var email = $(".sotbit_reg_form #sotbit_reg_panel_form_time_open input[name=EMAIL_TO]").val();
        var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
        if(email == "<?=GetMessage('AUTH_REGISTER_PANNEL_EMAIL')?>")
        {
            var email = "";
        }

        if(pattern.test(email))
        {
            var params = "<?=$arResult['STR_PARAMS']?>";

            <?
            if($arParams["EMAIL_SEND_END"])
            {
                $EMAIL_SEND_END = $arParams["~EMAIL_SEND_END"];
            }
            else
            {
                $EMAIL_SEND_END = GetMessage('EMAIL_SEND_END');
            }
            ?>

            BX.ajax({
                url: "<?=$this->GetFolder() ?>/ajax.php",
                method: 'POST',
                dataType: 'html',
                data: 'getemail=Y&EMAIL_TO=' + email +'&'+ params,
                onsuccess: function(data) {
                    data = JSON.parse(data);
                    if(data.error == 'subscribed')
                    {
                        $('.bottom_pannel_in form').remove();
                        $('.reg_bottom_pannel_in_wrap_text').html('<?=GetMessage('SUBSCRIBE')?>');
                    }
                    else
                    {
                        $('.bottom_pannel_in form').remove();
                        $('.reg_bottom_pannel_in_wrap_text').html('<?=$EMAIL_SEND_END?>');
                    }
                },
            });
        }
        else
        {
            var email = "";
        }

        return false;
    }
</script>



<style>
.sotbit_reg_form .modal-block {
   background-color: #<?=$arParams["COLOR_MODAL_BORDER"]?>;
   padding: <?=$arParams["MODAL_BORDER_PADDING"]?>px;
   max-width: <?=$arParams["MODAL_BG_WIDTH"]?>px
}
.sotbit_reg_form .modal-block-inner {
   background-color: #<?=$arParams["COLOR_MODAL_BG"]?>;
   padding: <?=$arParams["MODAL_BG_PADDING"]?>px
}
</style>



<?
$frame->end();
?>