<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();



if(!CModule::IncludeModule("iblock"))
{
    return;
}
if(!CModule::IncludeModule("sale"))
{
    return;
} 
if(!CModule::IncludeModule("catalog"))
{
    return;
}



global $DB;  


if($arParams["HOURS_AGO_END"] && $arParams["HOURS_AGO_START"]){
    $arParams["BASKET_DATE_UPDATE_from"] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H")-$arParams["HOURS_AGO_END"], date("i"), 0,  date("n"), date("d"), date("Y"))); 
    $arParams["BASKET_DATE_UPDATE_to"] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H")-$arParams["HOURS_AGO_START"], date("i"), 0,  date("n"), date("d"), date("Y")));        
}

if($arParams['BASKET_DATE_UPDATE_from'] && $arParams['BASKET_DATE_UPDATE_to']) {
    $arParams["BASKET_DATE_UPDATE_from"] = $arParams['BASKET_DATE_UPDATE_from'];
    $arParams["BASKET_DATE_UPDATE_to"] = $arParams['BASKET_DATE_UPDATE_to'];     
}

if(empty($arParams["BASKET_DATE_UPDATE_to"]) || empty($arParams["BASKET_DATE_UPDATE_from"])){
    return;    
}




//������� ������� �������
//START
    //������� ������������� ������� ������ ������� 
    $arrProductId = array();
    $arrUserId = array();
    $basketFillter = array(
        "!USER_ID" => false,
        "ORDER_ID" => "NULL",
        "CAN_BUY" => 'Y',
        ">=DATE_UPDATE"=> $arParams["BASKET_DATE_UPDATE_from"],
        "<=DATE_UPDATE"=> $arParams["BASKET_DATE_UPDATE_to"],   
    ); 
    if(!empty($arParams["SITE_ID"])){
        $basketFillter['LID'] = $arParams["SITE_ID"];    
    }
    $basketSelect = array(
        'ID',
        'NAME',
        'DETAIL_PAGE_URL',
        'PRICE',
        'CURRENCY',
        'PRODUCT_ID',
        'PRODUCT_PRICE_ID',
        'USER_ID',
        'QUANTITY'    
    );
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_BASKET_PARAM_AFTER.php");
        

 
        
    $resBasketItems = CSaleBasket::GetList(
       $basketSort,
       $basketFillter, 
       false,
       false,
       $basketSelect
    );
    while($arItems = $resBasketItems->Fetch())
    {
        //������� ID ������ ��� �������� �������� 
        //START 
        $mxResult = CCatalogSku::GetProductInfo($arItems['PRODUCT_ID']);
        if(is_array($mxResult)) {
            $arrProductId[$mxResult['ID']] = $mxResult['ID'];     
            $arItems['ITEM_PRODUCT_ID'] = $mxResult['ID']; 
            
            //��� �������
            $arrProductIdOffer[$arItems['PRODUCT_ID']] = $mxResult['ID']; 
            $arrProductId[$arItems['PRODUCT_ID']] = $arItems['PRODUCT_ID'];    
                      
        } else {
            $arrProductId[$arItems['PRODUCT_ID']] = $arItems['PRODUCT_ID'];     
            $arItems['ITEM_PRODUCT_ID'] = $arItems['PRODUCT_ID'];
        }
        //END
        
        $arBasketItemsItem[$arItems['USER_ID']][] = $arItems;
    }

    //������� ���������� �� �������
    //START
    if(count($arrProductId)>0){
     
        $arrProductIdInfo = array();
        $arSelectProduct = Array(
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
        $arFilterProduct = Array(
            "ID"=> $arrProductId
        );
        
        $arSortProduct = array();
        
        include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_BASKET_PRODUCT_FILLTER_BEFORE.php");
        $res = CIBlockElement::GetList($arSortProduct, $arFilterProduct, false, false, $arSelectProduct);
        while($ob = $res->GetNextElement())
        {      
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
                $fileImg = CFile::ResizeImageGet($arFields['PICTURE'], array('width'=> $arParams['FORGET_BASKET_IMG_WIDTH'], 'height'=> $arParams['FORGET_BASKET_IMG_HEIGHT'] ), BX_RESIZE_IMAGE_PROPORTIONAL, true);  
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
            }        
            unset($arFields['PROPERTIES']);
                    
            //��� �������
            //START 
            if($arrProductIdOffer[$arFields['ID']]){
                foreach($arFields as $k => $v){
                    $arFields['OFFER_'.$k] = $v; 
                    unset($arFields[$k]);   
                }   
                $arFields['ID'] = $arrProductIdOffer[$arFields['OFFER_ID']]; 

            }             
            if($arrProductIdInfo[$arFields['ID']]){
                $arFields = array_merge($arrProductIdInfo[$arFields['ID']], $arFields);        
            }
            //END
            
            $phpIncludeFunction = array();
            include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_BASKET_PRODUCT_FILLTER_WHILE_AFTER.php");

            if($phpIncludeFunction['isContinue']){
                continue;    
            }  
            if($phpIncludeFunction['isBreak']){
                break;    
            }                
                 
            if($arFields){
                $arrProductIdInfo[$arFields['ID']] = $arFields;                 
            }           


        }      
        
    }


    //END


    $ARR_USER_ID_SEND = array();
    $arrEmailSend = array();
    foreach($arBasketItemsItem as $kuser=>$vbasitems){
        $EmailSend = array();
        
        $EmailSend['PARAM_1'] = $kuser;
        // ������� ������������
        //START
        $rsUser = CUser::GetByID($kuser);
        $arUser = $rsUser->Fetch();
        foreach($arUser as $ku => $vu){
            $EmailSend['USER_'.$ku]  = $vu;       
        }
        //END
        
        //�������� ������� ��� �������
        //START
        $FORGET_BASKET = $arParams['TEMP_FORGET_BASKET_TOP'];
        $i = 0;     
        
        $EmailSend['BASKET_PRICE_ALL'] = 0;
        $EmailSend['BASKET_COUNT'] = count($vbasitems);
        foreach($vbasitems as $item) {
            $EmailSend['PARAM_3'][] = $item['PRODUCT_ID'];
            $EmailSend['PARAM_MESSEGE']['BASKET_PRODUCT_ID'][] = $item['PRODUCT_ID'];
            $EmailSend['FORGET_BASKET_ID'][] = $item['PRODUCT_ID'];
            $EmailSend['BASKET_PRICE_ALL'] = $EmailSend['BASKET_PRICE_ALL'] + $item['PRICE']*$item['QUANTITY'];
            $CURRENCY = $item['CURRENCY'];
            $BasketItem = array();
            //���������� ������ � �������
            if(strpos($item["DETAIL_PAGE_URL"],'?')) {
                $item['DETAIL_PAGE_URL'] = $arParams['MAILING_SITE_URL'].$item["DETAIL_PAGE_URL"].'&MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=kit_mailing_'.$arParams['MAILING_EVENT_ID'];
            } else {
                $item['DETAIL_PAGE_URL'] = $arParams['MAILING_SITE_URL'].$item["DETAIL_PAGE_URL"].'?MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=kit_mailing_'.$arParams['MAILING_EVENT_ID'];
            }             
            $item['PRICE_FORMAT'] = SaleFormatCurrency($item['PRICE'], $item['CURRENCY']);
            //���������� ������
            $item['BORDER_TABLE_STYLE'] = '';
            if($i > 0){
               $item['BORDER_TABLE_STYLE'] = ' border-top: 1px solid #E6EAEC; ';  
            }   
            $item['QUANTITY'] = floor($item['QUANTITY']); 
            
                                    
            foreach($item as $k=>$v) {
                $BasketItem['BASKET_'.$k] = $v;      
            }

            foreach($arrProductIdInfo[$item['ITEM_PRODUCT_ID']] as $k=>$v) {
                $BasketItem['PRODUCT_'.$k] = $v;                     
            }
            


            
            // ������� ���������� � �������
            $FORGET_BASKET .= CKitMailingHelp::ReplaceVariables($arParams["TEMP_FORGET_BASKET_LIST"] , $BasketItem);
             
                   
            $i++;         
        }
        $EmailSend['BASKET_PRICE_ALL_FORMAT'] = SaleFormatCurrency($EmailSend['BASKET_PRICE_ALL'], $CURRENCY);
        
        $EmailSend['PARAM_3'] = serialize($EmailSend['PARAM_3']);
        
        $FORGET_BASKET .= $arParams['TEMP_FORGET_BASKET_BOTTOM'];
          
        $EmailSend['FORGET_BASKET'] = $FORGET_BASKET;
        //END
        
        if($EmailSend['USER_ID']){
            $ARR_USER_ID_SEND[] = $EmailSend['USER_ID'];               
        }         
           
          
             
        $arrEmailSend[] = $EmailSend;    
                
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