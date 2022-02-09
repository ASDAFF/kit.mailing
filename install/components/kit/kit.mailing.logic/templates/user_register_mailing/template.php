<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $DB;  

if(empty($arParams['MORE_OPTIONS_TEMPLATE']['USER_ID'])) {
    return false;    
} 

//��������� ��� ����������� ������������
//START
    $ARR_USER_ID_SEND = array();
    $arrEmailSend = array();
    $fillterUser = array(
        'ID' => $arParams['MORE_OPTIONS_TEMPLATE']['USER_ID'],
    );
    
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_USER_PARAM_AFTER.php");
        
    $dbResUser = CUser::GetList($byUser, $orderUser,  $fillterUser, $arParametersUser);
    while($arItemsUser = $dbResUser->Fetch())
    {       
        $EmailSend = array();
 
        foreach($arItemsUser as $ku => $vu){
            $EmailSend['USER_'.$ku]  = $vu;       
        } 
           
        $EmailSend['USER_PASSWORD'] = $arParams['MORE_OPTIONS_TEMPLATE']['USER_PASSWORD'];        
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
     
     
// ��������� � �������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/mail_template/template_logic.php");
// END


?>