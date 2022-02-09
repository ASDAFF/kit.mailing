<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $DB;  


// обязательный модуль инфоблока 
if(!CModule::IncludeModule("iblock"))
{
    return;
}



//Рассылка по данным из инфоблока
//START
    //получим данные из инфоблока 
    
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
    
    //фильтрация по свойству дата
    if($arParams['IBLOCK_INFO_PROPERTY_FILLTER_DATE'] && $arParams["IBLOCK_INFO_PROPERTY_FILLTER_DATE_VALUE_from"] && $arParams["IBLOCK_INFO_PROPERTY_FILLTER_DATE_VALUE_to"]){
        $arFilterIblock['>=PROPERTY_'.$arParams['IBLOCK_INFO_PROPERTY_FILLTER_DATE']] = ConvertDateTime($arParams["IBLOCK_INFO_PROPERTY_FILLTER_DATE_VALUE_from"], "YYYY-MM-DD HH:MI:SS");
        $arFilterIblock['<=PROPERTY_'.$arParams['IBLOCK_INFO_PROPERTY_FILLTER_DATE']] = ConvertDateTime($arParams["IBLOCK_INFO_PROPERTY_FILLTER_DATE_VALUE_to"], "YYYY-MM-DD HH:MI:SS");;         
    }     
    
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_IBLOCK_PARAM_BEFORE.php");             
    $res = CIBlockElement::GetList($arSortIblock, $arFilterIblock, false, false, $arSelectIblock);
    while($ob = $res->GetNextElement())
    {
        $EmailSend = array();


        $arFields = $ob->GetFields();
                      
        //списки товаров
        if(strpos($arFields['LIST_PAGE_URL'],'?')) {
            $arFields['LIST_PAGE_URL'] = $arParams['MAILING_SITE_URL'].$arFields['LIST_PAGE_URL'].'&MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_'.$arParams['MAILING_EVENT_ID'];        
        } else {
            $arFields['LIST_PAGE_URL'] = $arParams['MAILING_SITE_URL'].$arFields['LIST_PAGE_URL'].'?MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_'.$arParams['MAILING_EVENT_ID'];          
        }  
                
        // детальная товара
        if(strpos($arFields['DETAIL_PAGE_URL'],'?')) {
            $arFields['DETAIL_PAGE_URL'] = $arParams['MAILING_SITE_URL'].$arFields['DETAIL_PAGE_URL'].'&MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_'.$arParams['MAILING_EVENT_ID'];        
        } else {
            $arFields['DETAIL_PAGE_URL'] = $arParams['MAILING_SITE_URL'].$arFields['DETAIL_PAGE_URL'].'?MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_'.$arParams['MAILING_EVENT_ID'];          
        }         
        //получим картинку
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
         
        //получим свойства
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
        include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_IBLOCK_PARAM_AFTER.php");  
                    
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


//Рекомендуемые товары
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/recommend/template_logic.php");    
// END


// исключения из рассылки
// START
    //дублирование писем
    if($arParams['EMAIL_DUBLICATE'] == 'Y'){ 
        $emailSendCount = array();
        foreach($arrEmailSend as $k => $ItemEmailSend) { 
            $EmailSend = array();
            $EmailSend['EMAIL_TO'] = CSotbitMailingHelp::ReplaceVariables($arParams["EMAIL_TO"] , $ItemEmailSend);  
            if(empty($emailSendCount[$EmailSend['EMAIL_TO']])) {
                $emailSendCount[$EmailSend['EMAIL_TO']] = $k;           
            } else {
                unset($arrEmailSend[$k]);
            }           
        }            
    }
// END



// Обработка и отправка письма
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/mail_template/template_logic.php");    
// END

       

?>
