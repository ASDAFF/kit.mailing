<?
//������� ������� �����, ������������ email, ������ �������� ��������  - ������� ����� ������  �������
//START
$arrEmailSend = CKitMailingHelp::MessageCheck(array('arParams'=>$arParams,'arrEmailSend'=>$arrEmailSend));
//END


// ������� ����������, �������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_MESSAGE_FOREACH_BEFORE.php");
$i=0;
foreach($arrEmailSend as $k => $ItemEmailSend) {    
    $EmailSend = array();
    
    $ItemEmailSend['RECOMMEND_PRODUCT'] = $RECOMMEND_PRODUCT;
    $ItemEmailSend['RECOMMEND_PRODUCT_ID'] = $RECOMMEND_PRODUCT_ID_ARRAY;
    //���������� ������
    //START
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/discounts/template_logic.php");
    //END 
    
    //������������� ������
    $ItemEmailSend['VIEWED_PRODUCT'] = ''; 
    if($ItemEmailSend['USER_ID']){
        $ItemEmailSend['VIEWED_PRODUCT'] = $VIEWED_PRODUCT_USER[$ItemEmailSend['USER_ID']]; 
        $ItemEmailSend['VIEWED_PRODUCT_ID'] = $VIEWED_PRODUCT_USER_ID_ARRAY[$ItemEmailSend['USER_ID']];        
    } 
            
    //������� ��������� php ���
    //START
    $phpIncludeFunction = array();
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_MESSAGE_FOREACH_ITEM_BEFORE.php");
            
    if($phpIncludeFunction['isContinue']){
        continue;    
    }  
    if($phpIncludeFunction['isBreak']){
        break;    
    } 
    //END       
    
    
    //��������� ������
    $EmailSend['SUBJECT'] = CKitMailingHelp::ReplaceVariables($arParams["SUBJECT"] , $ItemEmailSend);
    // ����
    $EmailSend['EMAIL_TO'] = CKitMailingHelp::ReplaceVariables($arParams["EMAIL_TO"] , $ItemEmailSend);
    //�� ����
    $EmailSend['EMAIL_FROM'] = CKitMailingHelp::ReplaceVariables($arParams["EMAIL_FROM"] , $ItemEmailSend);
    if(empty($EmailSend['EMAIL_FROM'])) {
        $EmailSend['EMAIL_FROM'] = COption::GetOptionString('kit.mailing', 'EMAIL_FROM');
    }    
    elseif(empty($EmailSend['EMAIL_FROM'])) {
        $EmailSend['EMAIL_FROM'] = COption::GetOptionString('main', 'email_from') ;
    }
    $EmailSend['BCC'] = CKitMailingHelp::ReplaceVariables($arParams["BCC"] , $EmailSendItem);
    // ����� ���������
    //$EmailSend['MESSEGE'] = CKitMailingHelp::ReplaceVariables($arParams["MESSAGE"] , $ItemEmailSend);
    $EmailSend['MESSEGE_PARAMETR'] = array();
    $needVariables = CKitMailingHelp::GetNeedVariablesTemplate($arParams["MESSAGE"]);
    foreach($ItemEmailSend as $k=>$v){
        if($needVariables[$k]){
            $EmailSend['MESSEGE_PARAMETR'][$k] = $v;        
        }        
    }     
    
     
    
    // �������� ���������
    $addMessageFields = array(
        'MAILING_ID' => $arParams['MAILING_EVENT_ID'],
        'MAILING_COUNT_RUN' => $arParams['MAILING_COUNT_RUN'],
    );
    $EmailSend['PARAM_1'] =  $ItemEmailSend['PARAM_1'];
    $EmailSend['PARAM_2'] =  $ItemEmailSend['PARAM_2'];
    $EmailSend['PARAM_3'] =  $ItemEmailSend['PARAM_3'];  
    //����� ������ ��� ����������
    //START
    $EmailSend['PARAM_MESSEGE'] =  $ItemEmailSend['PARAM_MESSEGE'];
    if($ItemEmailSend['USER_ID']){
        $EmailSend['PARAM_MESSEGE']['USER_ID'] = $ItemEmailSend['USER_ID'];    
    }
    if($RECOMMEND_PRODUCT_ID_ARRAY){
        $EmailSend['PARAM_MESSEGE']['RECOMMEND_PRODUCT_ID'] = $RECOMMEND_PRODUCT_ID_ARRAY;    
    }   
    if($arr_user_product_id[$ItemEmailSend['USER_ID']]){
        $EmailSend['PARAM_MESSEGE']['VIEWED_PRODUCT_ID'] = $arr_user_product_id[$ItemEmailSend['USER_ID']];    
    }     
    //END     
    $AddAnswerMessage = CKitMailingTools::AddMailingMessage($addMessageFields, $EmailSend);
    //��� ��������  iblock_info , ����� � ���������
    //START
    if(is_numeric($AddAnswerMessage['ID']) && $arParams["IBLOCK_INFO_PROPERTY_FINISH_LIST"] && $arParams["IBLOCK_INFO_PROPERTY_FINISH_LIST_VALUE"]) {
        CIBlockElement::SetPropertyValuesEx($ItemEmailSend['ID'], $ItemEmailSend['IBLOCK_ID'], array($arParams["IBLOCK_INFO_PROPERTY_FINISH_LIST"] => $arParams["IBLOCK_INFO_PROPERTY_FINISH_LIST_VALUE"]));       
    }     
    //END        

    $i++; 
    //�������� �������� ���������        
    //START
    $arParams['MAILING_MAILING_WORK_COUNT'] = $arParams['MAILING_MAILING_WORK_COUNT']+1;
    if($i>=$arParams['MAILING_PACKAGE_COUNT']){
        CKitMailingEvent::Update($arParams['MAILING_EVENT_ID'], array(
            "MAILING_WORK_COUNT" => $arParams['MAILING_MAILING_WORK_COUNT']
        ));
        $arrProgress = CKitMailingHelp::ProgressFileGetArray($arParams['MAILING_EVENT_ID'], $arParams['MAILING_COUNT_RUN']);
        $arrProgress['MAILING_WORK'] = 'N';
        CKitMailingHelp::ProgressFile($arParams['MAILING_EVENT_ID'], $arParams['MAILING_COUNT_RUN'], $arrProgress);
        ###############################
        if($arParams['MORE_OPTIONS_TEMPLATE']['TYPE_StartMailing'] == 'AGENT'){
            CKitMailingTools::send();
        }
        ###############################
        die;    
    }    
    //END 
           
                      
}  

// END 
?>