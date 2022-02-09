<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(!CModule::IncludeModule("sale"))
{
    return;
}
global $DB;  


if($arParams["DAY_AGO_END"] && $arParams["DAY_AGO_START"]){
    $arParams["DATE_REGISTER_1"] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i"), 0,  date("n"), date("d")-$arParams["DAY_AGO_END"], date("Y"))); 
    $arParams["DATE_REGISTER_2"] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i"), 0,  date("n"), date("d")-$arParams["DAY_AGO_START"], date("Y")));        
}

if($arParams['DATE_REGISTER_from'] && $arParams['DATE_REGISTER_to']) {
    $arParams["DATE_REGISTER_1"] = $arParams['DATE_REGISTER_from'];
    $arParams["DATE_REGISTER_2"] = $arParams['DATE_REGISTER_to'];     
}



//������� ������� �������
//START

    //������� ���� ������������� ������� ����������
    //START
    $ARR_USER_ID_SEND = array();
    $arr_order_user_id = array();
    $arFilter = Array();
    $rsSales = CSaleOrder::GetList(array(), $arFilter,false,false,array('ID','USER_ID'));
    while ($arSales = $rsSales->Fetch())
    {
        $arr_order_user_id[$arSales['USER_ID']][]  = $arSales['ID'];  
    }    
    //END
    
    //������� ������������� ������� ������ �������    
    $arrEmailSend = array(); 
    $fillterUser = array(
        'DATE_REGISTER_1' => $arParams["DATE_REGISTER_1"],    
        'DATE_REGISTER_2' => $arParams["DATE_REGISTER_2"],
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
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_USER_PARAM_BEFORE.php");
    
    $dbResUser = CUser::GetList($byUser, $orderUser,  $fillterUser, $arParametersUser);
    while($arItemsUser = $dbResUser->Fetch())
    {    
        //���� ������������ ���������
        if(empty($arr_order_user_id[$arItemsUser['ID']])) {
            
            $EmailSend = array();
 
            foreach($arItemsUser as $ku => $vu){
                $EmailSend['USER_'.$ku]  = $vu;       
            }            
             
            $EmailSend['PARAM_1'] = $arItemsUser['ID'];          


            $phpIncludeFunction = array();
            include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_USER_PARAM_AFTER.php");
                    
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
   
    }
    
 
                           
//END



//������������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/recommend/template_logic.php");
// END


// ������������� ������ ��� �������������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/viewed/template_logic.php");
// END



// ��������� � �������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/mail_template/template_logic.php");
// END


?>