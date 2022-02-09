<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();




if(!CModule::IncludeModule("iblock"))
{
    return;
}
global $DB;  




//Получим пользователей
//START  
    $ARR_USER_ID_SEND = array();
    $arrEmailSend = array();
     
    $fillterUser = array();
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
        'PERSONAL_BIRTHDAY'
    );      
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_USER_PARAM_BEFORE.php");
    $dbResUser = CUser::GetList($byUser, $orderUser,  $fillterUser, $arParametersUser);
    while($arItemsUser = $dbResUser->Fetch())
    {    
        
        // если родился в этот день
        $dateBirth = date("d.m.",mktime(0, 0, 0,  date("n"), date("d")+$arParams["DAY_AGO_START"], date("Y")));
        if(strstr($arItemsUser['PERSONAL_BIRTHDAY'], $dateBirth)) {
            $EmailSend = array();
 
            foreach($arItemsUser as $ku => $vu){
                $EmailSend['USER_'.$ku]  = $vu;       
            }            
             
            $EmailSend['PARAM_1'] = $arItemsUser['ID'];          
            include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_USER_PARAM_AFTER.php"); 
            
            if($EmailSend['USER_ID']){
                $ARR_USER_ID_SEND[] = $EmailSend['USER_ID'];               
            }             
                
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