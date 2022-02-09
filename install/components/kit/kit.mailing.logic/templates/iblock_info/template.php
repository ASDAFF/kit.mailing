<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $DB;  


// ������������ ������ ��������� 
if(!CModule::IncludeModule("iblock"))
{
    return;
}



//�������� �� ������ �� ���������
//START
    //������� ������ �� ��������� 
    
    $arrRecommendProduct = array();
    
    $arSortIblock = array();
    $arSelectIblock = Array(
        "ID", 
        "NAME",
        "IBLOCK_ID", 
        "PREVIEW_TEXT",
        'DETAIL_TEXT',
        'PREVIEW_PICTURE',
        'DETAIL_PICTURE',
        'LIST_PAGE_URL',
        'DETAIL_PAGE_URL'
    );
    
    $arFilterIblock = Array(
        "IBLOCK_ID"=> $arParams["IBLOCK_ID_INFO"], 
        "ACTIVE" => 'Y'    
    );
    if($arParams['IBLOCK_INFO_PROPERTY_FILLTER_LIST'] &&  $arParams['IBLOCK_INFO_PROPERTY_FILLTER_LIST_VALUE']) {
         $arFilterIblock["PROPERTY_".$arParams["IBLOCK_INFO_PROPERTY_FILLTER_LIST"]]  = $arParams["IBLOCK_INFO_PROPERTY_FILLTER_LIST_VALUE"];   
    }
    
    if($arParams['IBLOCK_INFO_PROPERTY_FILLTER_STRING'] &&  $arParams['IBLOCK_INFO_PROPERTY_FILLTER_STRING_VALUE']) {
         $arFilterIblock["PROPERTY_".$arParams["IBLOCK_INFO_PROPERTY_FILLTER_STRING"]] = $arParams["IBLOCK_INFO_PROPERTY_FILLTER_STRING_VALUE"];   
    }
    
    //���������� �� �������� ����
    if($arParams['IBLOCK_INFO_PROPERTY_FILLTER_DATE'] && $arParams["IBLOCK_INFO_PROPERTY_FILLTER_DATE_VALUE_from"] && $arParams["IBLOCK_INFO_PROPERTY_FILLTER_DATE_VALUE_to"]){
        $arFilterIblock['>=PROPERTY_'.$arParams['IBLOCK_INFO_PROPERTY_FILLTER_DATE']] = ConvertDateTime($arParams["IBLOCK_INFO_PROPERTY_FILLTER_DATE_VALUE_from"], "YYYY-MM-DD HH:MI:SS");
        $arFilterIblock['<=PROPERTY_'.$arParams['IBLOCK_INFO_PROPERTY_FILLTER_DATE']] = ConvertDateTime($arParams["IBLOCK_INFO_PROPERTY_FILLTER_DATE_VALUE_to"], "YYYY-MM-DD HH:MI:SS");;         
    }     
    
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_IBLOCK_PARAM_BEFORE.php");
    $res = CIBlockElement::GetList($arSortIblock, $arFilterIblock, false, false, $arSelectIblock);
    while($ob = $res->GetNextElement())
    {
        $EmailSend = array();


        $arFields = $ob->GetFields();
                      
        //������ �������
        if(strpos($arFields['LIST_PAGE_URL'],'?')) {
            $arFields['LIST_PAGE_URL'] = $arParams['MAILING_SITE_URL'].$arFields['LIST_PAGE_URL'].'&MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=kit_mailing_'.$arParams['MAILING_EVENT_ID'];
        } else {
            $arFields['LIST_PAGE_URL'] = $arParams['MAILING_SITE_URL'].$arFields['LIST_PAGE_URL'].'?MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=kit_mailing_'.$arParams['MAILING_EVENT_ID'];
        }  
                
        // ��������� ������
        if(strpos($arFields['DETAIL_PAGE_URL'],'?')) {
            $arFields['DETAIL_PAGE_URL'] = $arParams['MAILING_SITE_URL'].$arFields['DETAIL_PAGE_URL'].'&MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=kit_mailing_'.$arParams['MAILING_EVENT_ID'];
        } else {
            $arFields['DETAIL_PAGE_URL'] = $arParams['MAILING_SITE_URL'].$arFields['DETAIL_PAGE_URL'].'?MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=kit_mailing_'.$arParams['MAILING_EVENT_ID'];
        }         
        //������� ��������
        //START
        if($arFields['PREVIEW_PICTURE']){
            $arFields['PICTURE'] = $arFields['PREVIEW_PICTURE'];    
        } 
        elseif($arFields['DETAIL_PICTURE']) {
            $arFields['PICTURE'] = $arFields['DETAIL_PICTURE'];              
        }      
        //END
        $arFields['PICTURE_SRC'] = '';
        $arFields['PICTURE_WIDTH'] = '';   
        $arFields['PICTURE_HEIGHT'] = '';         
        if($arFields['PICTURE']) {
            $fileImg = CFile::ResizeImageGet($arFields['PICTURE'], array('width'=> $arParams['IMG_WIDTH_RECOMMEND'], 'height'=> $arParams['IMG_HEIGHT_RECOMMEND'] ), BX_RESIZE_IMAGE_PROPORTIONAL, true);  
            $arFields['PICTURE_SRC'] = $arParams['MAILING_SITE_URL'].$fileImg["src"];   
            $arFields['PICTURE_WIDTH'] = $fileImg["width"];  
            $arFields['PICTURE_HEIGHT'] = $fileImg["height"];                              
        } 
         
        //������� ��������
        $arFields['PROPERTIES'] = $ob->GetProperties();
        foreach($arFields['PROPERTIES'] as $kprop => $vprop) { 
            $display_value = CIBlockFormatProperties::GetDisplayValue($arFields, $vprop, "news_out");   
            if(is_array($display_value['DISPLAY_VALUE'])){
                $display = '';
                foreach($display_value['DISPLAY_VALUE'] as $v) {
                    $display .= $v.' ';    
                }    
                $display_value['DISPLAY_VALUE'] = $display;    
            }  
            $arFields["PROP_".$kprop] = $display_value['DISPLAY_VALUE'];   
            
            if($vprop['PROPERTY_TYPE']=='F') {
                $file = CFile::GetFileArray($vprop['VALUE']);  
                $arFields["PROP_".$kprop."_PATH"] = $arParams['MAILING_SITE_URL'].$file['SRC'];                       
            }
               
        }        
        unset($arFields['PROPERTIES']);

         
        $EmailSend = $arFields;
        $EmailSend['PARAM_1'] =  $arFields['ID'];
        $EmailSend['PARAM_MESSEGE']['ELEMENT_ID'] = $arFields['ID'];
        
        
 
        $phpIncludeFunction = array();
        include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_IBLOCK_PARAM_AFTER.php");
                    
        if($phpIncludeFunction['isContinue']){
            continue;    
        }  
        if($phpIncludeFunction['isBreak']){
            break;    
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


// ���������� �� ��������
// START
    //������������ �����
    if($arParams['EMAIL_DUBLICATE'] == 'Y'){ 
        $emailSendCount = array();
        foreach($arrEmailSend as $k => $ItemEmailSend) { 
            $EmailSend = array();
            $EmailSend['EMAIL_TO'] = CKitMailingHelp::ReplaceVariables($arParams["EMAIL_TO"] , $ItemEmailSend);
            if(empty($emailSendCount[$EmailSend['EMAIL_TO']])) {
                $emailSendCount[$EmailSend['EMAIL_TO']] = $k;           
            } else {
                unset($arrEmailSend[$k]);
            }           
        }            
    }
// END



// ��������� � �������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/mail_template/template_logic.php");
// END

       

?>
