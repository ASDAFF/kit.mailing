<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $DB;  


if($arParams["DAY_AGO_END"] && $arParams["DAY_AGO_START"]){
    $arParams["LAST_LOGIN_1"] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i"), 0,  date("n"), date("d")-$arParams["DAY_AGO_END"], date("Y"))); 
    $arParams["LAST_LOGIN_2"] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i"), 0,  date("n"), date("d")-$arParams["DAY_AGO_START"], date("Y")));        
}

if($arParams['LAST_LOGIN_from'] && $arParams['LAST_LOGIN_to']) {
    $arParams["LAST_LOGIN_1"] = $arParams['LAST_LOGIN_from'];
    $arParams["LAST_LOGIN_2"] = $arParams['LAST_LOGIN_to'];     
}




//Получим забытые корзины
//START
    //получим пользователей которые забыли корзины    
    $ARR_USER_ID_SEND = array();
    $arrEmailSend = array();
    $fillterUser = array(
        'LAST_LOGIN_1' => $arParams["LAST_LOGIN_1"],
        'LAST_LOGIN_2' => $arParams["LAST_LOGIN_2"],
    );
    
    if(!empty($arParams["SITE_ID"])){
        $fillterUser['LID'] = $arParams["SITE_ID"];    
    }
    
    
    $arParametersUser['FIELDS'] = array(
        'ID',
        'LOGIN',
        'EMAIL',
        'NAME',
        'LAST_NAME',
        'SECOND_NAME',
    );      
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_USER_PARAM_BEFORE.php");
        
    $dbResUser = CUser::GetList($byUser, $orderUser,  $fillterUser, $arParametersUser);
    while($arItemsUser = $dbResUser->Fetch())
    {       
        $EmailSend = array();
 
        foreach($arItemsUser as $ku => $vu){
            $EmailSend['USER_'.$ku]  = $vu;       
        }             
        $EmailSend['PARAM_1'] = $arItemsUser['ID'];   
        

        $phpIncludeFunction = array();
        include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_USER_PARAM_AFTER.php");   
                
        if($phpIncludeFunction['isContinue']){
            continue;    
        }  
        if($phpIncludeFunction['isBreak']){
            break;    
        }         
        

        if($EmailSend['USER_ID']){
            $ARR_USER_ID_SEND[] = $EmailSend['USER_ID'];               
        } 
          
        if($EmailSend) {
            $arrEmailSend[] = $EmailSend;             
        } 
                  
   
    }
    

                           
//END




//Рекомендуемые товары
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/recommend/template_logic.php");    
// END


// Просмотренные товары для пользователей
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/viewed/template_logic.php");    
// END


// Обработка и отправка письма
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/mail_template/template_logic.php");    
// END

 

?>
