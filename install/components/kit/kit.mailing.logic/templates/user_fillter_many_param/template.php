<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $DB;  

  
// ������������ ������ ��������� 
if(!CModule::IncludeModule("iblock"))
{
    return;
}

    
if($arParams['USER_FILLTER_DATE_REGISTER_AGO_from'] && $arParams['USER_FILLTER_DATE_REGISTER_AGO_to']){
    $arParams['USER_FILLTER_DATE_REGISTER_from'] = $arParams['USER_FILLTER_DATE_REGISTER_AGO_from']; 
    $arParams['USER_FILLTER_DATE_REGISTER_to'] = $arParams['USER_FILLTER_DATE_REGISTER_AGO_to'];   
    unset($arParams['USER_FILLTER_DATE_REGISTER_AGO_from']);
    unset($arParams['USER_FILLTER_DATE_REGISTER_AGO_to']);      
}
if($arParams['USER_FILLTER_LAST_LOGIN_AGO_from'] && $arParams['USER_FILLTER_LAST_LOGIN_AGO_to']){
    $arParams['USER_FILLTER_LAST_LOGIN_from'] = $arParams['USER_FILLTER_LAST_LOGIN_AGO_from']; 
    $arParams['USER_FILLTER_LAST_LOGIN_to'] = $arParams['USER_FILLTER_LAST_LOGIN_AGO_to'];   
    unset($arParams['USER_FILLTER_LAST_LOGIN_AGO_from']);
    unset($arParams['USER_FILLTER_LAST_LOGIN_AGO_to']);      
}
if($arParams['USER_FILLTER_PERSONAL_BIRTHDAY_AGO_from'] && $arParams['USER_FILLTER_PERSONAL_BIRTHDAY_AGO_to']){
    $arParams['USER_FILLTER_PERSONAL_BIRTHDAY_from'] = $arParams['USER_FILLTER_PERSONAL_BIRTHDAY_AGO_from']; 
    $arParams['USER_FILLTER_PERSONAL_BIRTHDAY_to'] = $arParams['USER_FILLTER_PERSONAL_BIRTHDAY_AGO_to'];   
    unset($arParams['USER_FILLTER_PERSONAL_BIRTHDAY_AGO_from']);
    unset($arParams['USER_FILLTER_PERSONAL_BIRTHDAY_AGO_to']);      
}    
    
      

//������� �������������
//START
    //����������� �������������
    $ARR_USER_ID_SEND = array();
    $arrEmailSend = array();
     
    $fillterUser = array();
    if(!empty($arParams["SITE_ID"])){
        $fillterUser['LID'] = $arParams["SITE_ID"];    
    }    
    
    //���� �����������
    if($arParams["USER_FILLTER_DATE_REGISTER_from"]){
        $fillterUser['DATE_REGISTER_1'] = $arParams["USER_FILLTER_DATE_REGISTER_from"];    
    }
    if($arParams["USER_FILLTER_DATE_REGISTER_to"]){
        $fillterUser['DATE_REGISTER_2'] = $arParams["USER_FILLTER_DATE_REGISTER_to"];    
    }
    
    
    //���� �����������
    if($arParams["USER_FILLTER_LAST_LOGIN_from"]){
        $fillterUser['LAST_LOGIN_1'] = $arParams["USER_FILLTER_LAST_LOGIN_from"];    
    }
    if($arParams["USER_FILLTER_LAST_LOGIN_to"]){
        $fillterUser['LAST_LOGIN_2'] = $arParams["USER_FILLTER_LAST_LOGIN_to"];    
    }
    //������ ������������
    if($arParams["USER_FILLTER_GROUPS_ID"]){
        $fillterUser['GROUPS_ID'] = $arParams["USER_FILLTER_GROUPS_ID"];    
    }
       
    //���� �������� 
    if($arParams["USER_FILLTER_PERSONAL_BIRTHDAY_from"]){
        $fillterUser['PERSONAL_BIRTHDAY_1'] = $arParams["USER_FILLTER_PERSONAL_BIRTHDAY_from"];    
    }
    if($arParams["USER_FILLTER_PERSONAL_BIRTHDAY_to"]){
        $fillterUser['PERSONAL_BIRTHDAY_2'] = $arParams["USER_FILLTER_PERSONAL_BIRTHDAY_to"];    
    }
         
    //��� ������������
    if($arParams["USER_FILLTER_PERSONAL_GENDER"]){
        $fillterUser['PERSONAL_GENDER'] = $arParams["USER_FILLTER_PERSONAL_GENDER"];    
    }
      
    if($arParams["USER_FILLTER_NAME"]){
        $fillterUser['NAME'] = $arParams["USER_FILLTER_NAME"];    
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
            
                              
//END


//������������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/recommend/template_logic.php");
// END


// ������������� ������ ��� �������������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/viewed/template_logic.php");
// END


// ���������� �� ��������
// START

    //������������ ���������� ����� ������
    //START
    if($arParams['EXCEPTIONS_USER_SEND'] == 'Y'){ 
        $emailSendCount = array();
        
        //������� ��������� 
        $idParamMessageUser = array();
        $dbResParamMessage = CKitMailingMessage::GetList(array(),array('ID_EVENT'=>$arParams['MAILING_EVENT_ID']),false,array('PARAM_1'));
        while($arParamMessage = $dbResParamMessage->Fetch())
        { 
            $idParamMessageUser[$arParamMessage['PARAM_1']] = 'YES';   
        } 
        
        foreach($arrEmailSend as $k => $ItemEmailSend) { 
            if($idParamMessageUser[$ItemEmailSend['USER_ID']]){
                unset($arrEmailSend[$k]);    
            }           
        }             
    }
    //END
    
// END




// ��������� � �������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/mail_template/template_logic.php");
// END

   
       

?>