<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $DB;  


// обязательный модуль инфоблока 
if(!CModule::IncludeModule("iblock"))
{
    return;
}

if(!CModule::IncludeModule("sale"))
{
    return;
}




//определим параметры
if($arParams['ORDER_FILLTER_DATE_INSERT_AGO_from'] && $arParams['ORDER_FILLTER_DATE_INSERT_AGO_to']){
    $arParams['ORDER_FILLTER_DATE_INSERT_from'] = $arParams['ORDER_FILLTER_DATE_INSERT_AGO_from']; 
    $arParams['ORDER_FILLTER_DATE_INSERT_to'] = $arParams['ORDER_FILLTER_DATE_INSERT_AGO_to'];   
    unset($arParams['ORDER_FILLTER_DATE_INSERT_AGO_from']);
    unset($arParams['ORDER_FILLTER_DATE_INSERT_AGO_to']);      
}
if($arParams['ORDER_FILLTER_DATE_STATUS_AGO_from'] && $arParams['ORDER_FILLTER_DATE_STATUS_AGO_to']){
    $arParams['ORDER_FILLTER_DATE_STATUS_from'] = $arParams['ORDER_FILLTER_DATE_STATUS_AGO_from']; 
    $arParams['ORDER_FILLTER_DATE_STATUS_to'] = $arParams['ORDER_FILLTER_DATE_STATUS_AGO_to'];   
    unset($arParams['ORDER_FILLTER_DATE_STATUS_AGO_from']);
    unset($arParams['ORDER_FILLTER_DATE_STATUS_AGO_to']);      
}







//получим заказы
//START

    // получим дополнительные данные
    // START
    
        // пользователи
        $arParametersUser['FIELDS'] = array(
            'ID',
            'LOGIN',
            'EMAIL',
            'NAME',
            'LAST_NAME',
            'SECOND_NAME',
        );          
        $dbResUser = CUser::GetList($byUser, $orderUser,  $fillterUser, $arParametersUser);
        while($arItemsUser = $dbResUser->Fetch())
        {       
            $arrUser[$arItemsUser['ID']] = $arItemsUser;
        }
        // получим статусы
        $arOrderStatus = array();
        $dbStatusList = CSaleStatus::GetList(array("SORT" => "ASC"),array(),false,false,array("ID", "NAME"));
        while($arStatusList = $dbStatusList->Fetch())
        {  
            $arOrderStatus[$arStatusList['ID']] = $arStatusList['NAME'];    
        }
        //получим системы оплаты  
        $arOrderPaySystemId = array();        
        $dbpaySystem = CSalePaySystem::GetList(Array("SORT"=>"ASC", "NAME"=>"ASC"), Array());
        while($paySystem = $dbpaySystem->Fetch()){
            $arOrderPaySystemId[$paySystem['ID']] = $paySystem['NAME'];    
        } 
        
        //получим автоматизированные службы доставки  
        $arOrderDeliveryId = array();     
        $rsDeliveryServicesList = CSaleDeliveryHandler::GetList(array(), array()); 
        while ($arDeliveryService = $rsDeliveryServicesList->GetNext())
        {    
            if (!is_array($arDeliveryService) || !is_array($arDeliveryService["PROFILES"])) continue;
            foreach ($arDeliveryService["PROFILES"] as $profile_id => $arDeliveryProfile)
            {
                $delivery_id = $arDeliveryService["SID"].":".$profile_id;
                $arOrderDeliveryId[$delivery_id] = $arDeliveryService["NAME"]." - ".$arDeliveryProfile["TITLE"];     
            }  
        } 
        //получим обычные службы доставки   
        $dbDelivery = CSaleDelivery::GetList(array("SORT"=>"ASC", "NAME"=>"ASC"),array());
        while ($arDelivery = $dbDelivery->GetNext())
        {
            $arOrderDeliveryId[$arDelivery["ID"]] = $arDelivery["NAME"];
        }        
           
        // получить местоположения
        $arLocationValue = array();
        $db_location = CSaleLocation::GetList();
        while($arr_location = $db_location->Fetch()){
            $arLocationValue[$arr_location['ID']] = $arr_location;
        }
      
        //получим типы свойств
        $propertyOrderInfo = array();
        $db_props = CSaleOrderProps::GetList(array("SORT" => "ASC"),array(),false,false,array());
        while($arr_props = $db_props->Fetch())
        {  
            $propertyOrderInfo[$arr_props["CODE"]] = $arr_props;    
        }         
        
               
            
        
    // END
    
    
    //получим заказы пользователя
    $arrEmailSend = array();
     
    //настроим фильтры
    //START
        $arFilterOrder = array();
        $ARR_USER_ID_SEND = array();
        
        // исключения из рассылки
        // START

            //заказы получившие ранее письма
            //START
            if($arParams['EXCEPTIONS_SALE_SEND'] == 'Y' || $arParams['EXCEPTIONS_USER_SEND'] == 'Y'){ 
                //получим параметры 
                $dbResParamMessage = CSotbitMailingMessage::GetList(array(),array('ID_EVENT'=>$arParams['MAILING_EVENT_ID']),false,array('PARAM_1','PARAM_3'));
                while($arParamMessage = $dbResParamMessage->Fetch())
                { 
                    if($arParams['EXCEPTIONS_SALE_SEND'] == 'Y'){ 
                        $arFilterOrder['!ID'][] = $arParamMessage['PARAM_1'];
                    } 
                    if($arParams['EXCEPTIONS_USER_SEND'] == 'Y'){ 
                        $arFilterOrder['!USER_ID'][] = $arParamMessage['PARAM_3'];
                    }                     
                }               
            }
            //END
            
        // END        
        
        
        
        
        if(!empty($arParams["SITE_ID"])){
            $arFilterOrder['LID'] = $arParams["SITE_ID"];    
        }      
 
        //цена заказа от и до
        if($arParams["ORDER_FILLTER_PRICE_from"]){
            $arFilterOrder['>=PRICE'] = $arParams["ORDER_FILLTER_PRICE_from"];    
        }
        if($arParams["ORDER_FILLTER_PRICE_to"]){
            $arFilterOrder['<=PRICE'] = $arParams["ORDER_FILLTER_PRICE_to"];    
        }        

               
        //дата создания
        if($arParams["ORDER_FILLTER_DATE_INSERT_from"]){
            $arFilterOrder['>=DATE_INSERT'] = $arParams["ORDER_FILLTER_DATE_INSERT_from"];    
        }
        if($arParams["ORDER_FILLTER_DATE_INSERT_to"]){
            $arFilterOrder['<=DATE_INSERT'] = $arParams["ORDER_FILLTER_DATE_INSERT_to"];    
        }           
        //статусы
        if($arParams["ORDER_FILLTER_STATUS"]){
            $arFilterOrder['STATUS_ID'] = $arParams["ORDER_FILLTER_STATUS"];    
        }
        //дата изменения статуса
        if($arParams["ORDER_FILLTER_DATE_STATUS_from"]){
            $arFilterOrder['>=DATE_STATUS'] = $arParams["ORDER_FILLTER_DATE_STATUS_from"];    
        }
        if($arParams["ORDER_FILLTER_DATE_STATUS_to"]){
            $arFilterOrder['<=DATE_STATUS'] = $arParams["ORDER_FILLTER_DATE_STATUS_to"];    
        }
               
        //системы оплаты
        if($arParams["ORDER_FILLTER_PAY_SYSTEM_ID"]){
            $arFilterOrder['PAY_SYSTEM_ID'] = $arParams["ORDER_FILLTER_PAY_SYSTEM_ID"];    
        }        
        //оплачен заказ
        if($arParams["ORDER_FILLTER_PAYED"]){
            $arFilterOrder['PAYED'] = $arParams["ORDER_FILLTER_PAYED"];    
        }            
        //системы доставки
        if($arParams["ORDER_FILLTER_DELIVERY_ID"]){
            $arFilterOrder['PAY_DELIVERY_ID'] = $arParams["ORDER_FILLTER_DELIVERY_ID"];    
        }                

                     
     
    //END      
    $orderIdMailing = array();
     
     
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_ORDER_PARAM_BEFORE.php");
    $dbResOrder = CSaleOrder::GetList($arSortOrder,  $arFilterOrder, false, false, $arSelectOrder);
    while($arItemsOrder = $dbResOrder->Fetch())
    {   

        $EmailSend = array();
        //стоимость доставки
        $arItemsOrder['PRICE_PRINT'] = SaleFormatCurrency($arItemsOrder['PRICE'], $arItemsOrder['CURRENCY']);      
        $arItemsOrder['PRICE_DELIVERY_PRINT'] = SaleFormatCurrency($arItemsOrder['PRICE_DELIVERY'], $arItemsOrder['CURRENCY']); 
          
        
        
         
        //определим данные для рассылки  
        foreach($arItemsOrder as $ko=>$vo) { 
            $arItemsOrder['ORDER_'.$ko] = $vo;
            //оплата,доставка,отмена 
            if(in_array($ko,array('PAYED','CANCELED','ALLOW_DELIVERY'))) {
                $arItemsOrder['ORDER_'.$ko.'_PRINT'] = GetMessage('SELECT_PARAM_'.$vo);        
            }
            //статус заказа 
            elseif($ko=='STATUS_ID'){
                $arItemsOrder['ORDER_'.$ko.'_PRINT'] = $arOrderStatus[$vo] ; 
            }
            //система оплаты
            elseif($ko=='PAY_SYSTEM_ID'){
                $arItemsOrder['ORDER_'.$ko.'_PRINT'] = $arOrderPaySystemId[$vo] ; 
            }            
            //служба доставки
            elseif($ko=='DELIVERY_ID'){
                $arItemsOrder['ORDER_'.$ko.'_PRINT'] = $arOrderDeliveryId[$vo] ; 
            }  
                                
            unset($arItemsOrder[$ko]);                
        }
        //получим данные из заказа
        //START  
        foreach($propertyOrderInfo as $k=>$v){
            $arItemsOrder['ORDER_PROP_'.$k] = '';        
        } 
        $arItemsOrder['ORDER_EMAIL'] = '';  
        $order_props = CSaleOrderPropsValue::GetOrderProps($arItemsOrder['ORDER_ID']);
        while($arProps = $order_props->Fetch())
        {    
            if($arProps['IS_EMAIL'] == 'Y'){
                $arItemsOrder['ORDER_EMAIL'] = $arProps["VALUE"];
                $arItemsOrder['ORDER_PROP_'.$arProps["CODE"]] = $arProps["VALUE"];                
            } elseif($arProps['IS_LOCATION'] == 'Y') {
                $arItemsOrder['ORDER_PROP_'.$arProps["CODE"]] = $arProps["VALUE"];
                 
                $arItemsOrder['ORDER_PROP_'.$arProps["CODE"].'_COUNTRY'] = $arLocationValue[$arProps["VALUE"]]['COUNTRY_NAME']; 
                $arItemsOrder['ORDER_PROP_'.$arProps["CODE"].'_CITY'] = $arLocationValue[$arProps["VALUE"]]['CITY_NAME'] ;                       
            }
            else{
                $arItemsOrder['ORDER_PROP_'.$arProps["CODE"]] = $arProps["VALUE"];                   
            }   
            
             
        }    
        //END  
        

        
        //получим данные из пользователя
        //START
        if($arrUser[$arItemsOrder['ORDER_USER_ID']]) {
            foreach($arrUser[$arItemsOrder['ORDER_USER_ID']] as $ku => $vu){
                $arItemsOrder['USER_'.$ku]  = $vu;       
            }                         
        }             
        //END
        
        
        $EmailSend = $arItemsOrder;      
        $EmailSend['PARAM_1'] = $arItemsOrder['ORDER_ID'];  
        $EmailSend['PARAM_3'] = $arItemsOrder['ORDER_USER_ID'];
        $EmailSend['PARAM_MESSEGE']['ORDER_ID'] = $arItemsOrder['ORDER_ID'];     
            
   
        $phpIncludeFunction = array();
        include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_ORDER_PARAM_AFTER.php");

        if($phpIncludeFunction['isContinue']){
            continue;    
        }  
        if($phpIncludeFunction['isBreak']){
            break;    
        }                  
                
        
        $orderIdMailing[] = $arItemsOrder['ORDER_ID']; 
        
        if($EmailSend['USER_ID']){
            $ARR_USER_ID_SEND[] = $EmailSend['USER_ID'];               
        }
        
                 
        if($EmailSend) { 
            $arrEmailSend[$arItemsOrder['ORDER_ID']] = $EmailSend;                     
        }                       

    }
                                  
//END



//Получим товары заказа
//START
    //получим товары заказа
if($orderIdMailing){
    
    
    $arrProductId = array();
    $arrUserId = array();
    $basketFillter = array(
        "ORDER_ID" => $orderIdMailing, 
    ); 
    $basketSelect = array(
        'ID',
        'NAME',
        'DETAIL_PAGE_URL',
        'PRICE',
        'CURRENCY',
        'PRODUCT_ID',
        'PRODUCT_PRICE_ID',
        'USER_ID',
        'QUANTITY',
        'ORDER_ID'    
    );
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_BASKET_PARAM_AFTER.php"); 
        
    $resBasketItems = CSaleBasket::GetList(
       $basketSort,
       $basketFillter, 
       false,
       false,
       $basketSelect
    );
    while($arItems = $resBasketItems->Fetch())
    {
        //получим ID товара где хранятся свойства 
        //START 
        $mxResult = CCatalogSku::GetProductInfo($arItems['PRODUCT_ID']);
        if(is_array($mxResult)) {
            $arrProductId[$mxResult['ID']] = $mxResult['ID']; 
            $arItems['ITEM_PRODUCT_ID'] = $mxResult['ID'];  
            
            //для офферов
            $arrProductIdOffer[$arItems['PRODUCT_ID']] = $mxResult['ID']; 
            $arrProductId[$arItems['PRODUCT_ID']] = $arItems['PRODUCT_ID'];                        
        } else {
            $arrProductId[$arItems['PRODUCT_ID']] = $arItems['PRODUCT_ID'];     
            $arItems['ITEM_PRODUCT_ID'] = $arItems['PRODUCT_ID'];
        }
        //END
        
        $arBasketItemsItem[$arItems['ORDER_ID']][] = $arItems;
    }
    

    
    //получим информацию по товарам
    //START
    
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
    
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_BASKET_PRODUCT_FILLTER_BEFORE.php");
    $res = CIBlockElement::GetList($arSortProduct, $arFilterProduct, false, false, $arSelectProduct);
    while($ob = $res->GetNextElement())
    {      
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
            $fileImg = CFile::ResizeImageGet($arFields['PICTURE'], array('width'=> $arParams['FORGET_BASKET_IMG_WIDTH'], 'height'=> $arParams['FORGET_BASKET_IMG_HEIGHT'] ), BX_RESIZE_IMAGE_PROPORTIONAL, true);  
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
        }        
        unset($arFields['PROPERTIES']);   
        
            //для офферов
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
            include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_BASKET_PRODUCT_FILLTER_WHILE_AFTER.php"); 
            
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
    //END


    foreach($arBasketItemsItem as $korder=>$vbasitems){
        $EmailSend = array();
        
        //составим внешний вид товаров
        //START
        $FORGET_BASKET = $arParams['TEMP_FORGET_BASKET_TOP'];
        $i = 0;     
        
        $arrEmailSend[$korder]['BASKET_PRICE_ALL'] = 0;
        $arrEmailSend[$korder]['BASKET_COUNT'] = count($vbasitems);
        foreach($vbasitems as $item) {
            $arrEmailSend[$korder]['FORGET_BASKET_ID'][] = $item['PRODUCT_ID'];
            $arrEmailSend[$korder]['BASKET_PRICE_ALL'] = $arrEmailSend[$korder]['BASKET_PRICE_ALL'] + $item['PRICE']*$item['QUANTITY'];
            $CURRENCY = $item['CURRENCY'];
            $BasketItem = array();
            //сформируем ссылки с корзины
            if(strpos($item["DETAIL_PAGE_URL"],'?')) {
                $item['DETAIL_PAGE_URL'] = $arParams['MAILING_SITE_URL'].$item["DETAIL_PAGE_URL"].'&MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_'.$arParams['MAILING_EVENT_ID'];        
            } else {
                $item['DETAIL_PAGE_URL'] = $arParams['MAILING_SITE_URL'].$item["DETAIL_PAGE_URL"].'?MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_'.$arParams['MAILING_EVENT_ID'];          
            }             
            $item['PRICE_FORMAT'] = SaleFormatCurrency($item['PRICE'], $item['CURRENCY']);
            //сформируем отступ
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
            // заменим переменные в шаблоне
            $FORGET_BASKET .= CSotbitMailingHelp::ReplaceVariables($arParams["TEMP_FORGET_BASKET_LIST"] , $BasketItem);                 
            $i++;         
        }
        $arrEmailSend[$korder]['BASKET_PRICE_ALL_FORMAT'] = SaleFormatCurrency($arrEmailSend[$korder]['BASKET_PRICE_ALL'], $CURRENCY);
        
        $FORGET_BASKET .= $arParams['TEMP_FORGET_BASKET_BOTTOM'];
        $arrEmailSend[$korder]['FORGET_BASKET'] = $FORGET_BASKET;
        //END            
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