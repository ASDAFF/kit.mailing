<?
if($arParams["RECOMMEND_SHOW"] == 'Y' && CModule::IncludeModule("iblock")) {
    
    
    //получим информацию по товарам
    //START
    
    $arrRecommendProduct = array();
    $arSelectRecommend = Array(
        "ID", 
        "NAME",
        "IBLOCK_ID", 
        "PREVIEW_TEXT",
        'DETAIL_TEXT',
        'PREVIEW_PICTURE',
        'DETAIL_PICTURE',
        'LIST_PAGE_URL',
        'DETAIL_PAGE_URL',
    );
    $arFilterRecommend = Array(
        "ACTIVE" => 'Y' 
    );
    // инфоблок рекомендованных товаров
    if($arParams["IBLOCK_ID_RECOMMEND"]) {
        $arFilterRecommend['IBLOCK_ID'] = $arParams["IBLOCK_ID_RECOMMEND"];    
    }
    // разделы рекомендованных товаров
    if($arParams["IBLOCK_SECTION_RECOMMEND"]) {
        $arFilterRecommend['SECTION_ID'] = $arParams["IBLOCK_SECTION_RECOMMEND"];   
        $arFilterRecommend['INCLUDE_SUBSECTIONS'] = "Y";  
    }  
    
    // дата создания от
    if($arParams["DATE_CREATE_AGO_RECOMMEND_from"]){
        $arFilterRecommend['>=DATE_CREATE'] = $arParams["DATE_CREATE_AGO_RECOMMEND_from"];     
    }
    // дата создания до
    if($arParams["DATE_CREATE_AGO_RECOMMEND_to"]){
        $arFilterRecommend['<=DATE_CREATE'] = $arParams["DATE_CREATE_AGO_RECOMMEND_to"];      
    }   
    
    if($arParams['PROPERTY_FILLTER_1_RECOMMEND'] &&  $arParams['PROPERTY_FILLTER_1_VALUE_RECOMMEND']) {
         $arFilterRecommend["PROPERTY_".$arParams["PROPERTY_FILLTER_1_RECOMMEND"]]  = $arParams["PROPERTY_FILLTER_1_VALUE_RECOMMEND"];   
    } 
    

    
    if($arParams['TOP_COUNT_FILLTER_RECOMMEND']){
        $arNavStartParams = array('nTopCount'=>$arParams['TOP_COUNT_FILLTER_RECOMMEND']);    
    }  else {
        $arNavStartParams = false;              
    }
    
    $arSortRecpmmend = Array(
        $arParams["SORT_BY_RECOMMEND"] => $arParams["SORT_ORDER_RECOMMEND"]
    );   
    
    
    
    //добавим цену
    //START
    if(empty($arParams['PRICE_TYPE_RECOMMEND']) && CModule::IncludeModule("catalog")){
        $dbPrice = CCatalogGroup::GetList(array(),array('BASE'=>'Y'));
        while($arPrice = $dbPrice->Fetch()) {
            $arParams['PRICE_TYPE_RECOMMEND'] = array($arPrice['NAME']);        
        }      
    }
    elseif(!is_array($arParams['PRICE_TYPE_RECOMMEND'])){
        $arParams['PRICE_TYPE_RECOMMEND'] = array($arParams['PRICE_TYPE_RECOMMEND']);        
    }   
    $arParams['RECOMMEND_PRICES'] = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID_RECOMMEND"], $arParams['PRICE_TYPE_RECOMMEND']);
        
    foreach($arParams['RECOMMEND_PRICES'] as $key => $value)
    {
        $arSelectRecommend[] = $value["SELECT"];
        $arParams['RECOMMEND_PRICES'][$key]['CAN_VIEW'] = '1';
        $arParams['RECOMMEND_PRICES'][$key]['CAN_BUY'] = '1';                 
    }    
    //END
     
    if($arParams['INCLUDE_PHP_MODIF_RECOMMEND']=='Y'){
        include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_RECOMMEND_FILLTER_BEFORE.php");         
    }    
       
           
    $res = CIBlockElement::GetList($arSortRecpmmend, $arFilterRecommend, false, $arNavStartParams, $arSelectRecommend);
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
        }        
        unset($arFields['PROPERTIES']);  
        
        
        
        //получим цену товара
        //START
        if(CModule::IncludeModule("catalog") && CModule::IncludeModule("sale")) {
            $priceProduct = array();
            $ID_IBLOCK_PRODUCT = $arFields['IBLOCK_ID'];
            $ID_PRODUCT = $arFields['ID'];

            //если есть офферы получим цену
            if(CCatalogSKU::IsExistOffers($ID_PRODUCT)){ 
                if(empty($arInfo)) {
                    $arInfo = CCatalogSKU::GetInfoByProductIBlock($ID_IBLOCK_PRODUCT);
                }
                //FILLTER
                $arOffersRecommendFillter = array(
                    'IBLOCK_ID' => $arInfo['IBLOCK_ID'], 
                    'PROPERTY_'.$arInfo['SKU_PROPERTY_ID'] => $ID_PRODUCT                
                );  
                //SELECT              
                $arOffersRecommendSelect = array(
                    'ID',
                    'IBLOCK_ID'  
                );
                foreach($arParams['RECOMMEND_PRICES'] as $value)
                {
                    $arOffersRecommendSelect[] = $value["SELECT"];
                } 
                              
                $rsOffers = CIBlockElement::GetList(array('SORT'=>'ASC'), $arOffersRecommendFillter,false,false,$arOffersRecommendSelect); 
                while ($arOffer = $rsOffers->GetNext()) 
                {            
                    $arrSiteId = CSotbitMailingHelp::GetSiteId();    
                    $priceProduct = CIBlockPriceTools::GetItemPrices($ID_IBLOCK_PRODUCT, $arParams['RECOMMEND_PRICES'], $arOffer, true, array(), 0, $arrSiteId); 
                    if($priceProduct) { 
                        break;
                    }
                } 
                       
            } else {
                $arrSiteId = CSotbitMailingHelp::GetSiteId();
                $priceProduct = CIBlockPriceTools::GetItemPrices($ID_IBLOCK_PRODUCT, $arParams['RECOMMEND_PRICES'], $arFields, true, array(), 0, $arrSiteId); 
            }
            
             
            //
            foreach($arParams['RECOMMEND_PRICES'] as $k=>$v) {
                //исторические цены
                $arFields['PRICE_OPTIMAL'] = floor($priceProduct[$k]['DISCOUNT_VALUE']);
                $arFields['PRICE_OPTIMAL_CURRENCY'] = $priceProduct[$k]['PRINT_DISCOUNT_VALUE'];  
                //цена со скидкой
                $arFields['PRINT_NO_DISCOUNT_PRICE'] = ''; 
                $arFields['NO_DISCOUNT_PRICE'] = '';  
                $arFields['PRINT_DISCOUNT_DIFF'] = ''; 
                $arFields['DISCOUNT_DIFF'] = ''; 
                                   
                if($priceProduct[$k]['VALUE'] > $priceProduct[$k]['DISCOUNT_VALUE']) {
                    $arFields['PRINT_NO_DISCOUNT_PRICE'] = $priceProduct[$k]['PRINT_VALUE']; 
                    $arFields['NO_DISCOUNT_PRICE'] = $priceProduct[$k]['VALUE'];  
                  
                    $arFields['PRINT_DISCOUNT_DIFF'] = $priceProduct[$k]['PRINT_VALUE']; 
                    $arFields['DISCOUNT_DIFF'] = $priceProduct[$k]['VALUE'];                                            
                }
                // цена без скидки
                $arFields['PRINT_PRICE'] = $priceProduct[$k]['PRINT_DISCOUNT_VALUE'];  
                $arFields['PRICE'] = $priceProduct[$k]['DISCOUNT_VALUE'];           
            } 
    
        }  
        //END          
        
        $phpIncludeFunction = array();
        if($arParams['INCLUDE_PHP_MODIF_RECOMMEND']=='Y'){ 
            include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_RECOMMEND_FILLTER_WHILE_AFTER.php");                 
        }
 
        
        if($phpIncludeFunction['isContinue']){
            continue;    
        }  
        if($phpIncludeFunction['isBreak']){
            break;    
        }          
                    
        if($arFields){
            $arrRecommendProduct[] = $arFields;             
        }           
        
    }   
    //END 
    
    //если стоит отмена отправки
    if($arParams['CANCEL_EMPTY_RECOMMEND']=='Y' && count($arrRecommendProduct) == 0){
        $arrEmailSend = array();             
    }
     
               
    // составим рекомендованные товары 
    if(count($arrRecommendProduct) > 0) {
        $i = 0;  
        $RECOMMEND_PRODUCT_ID_ARRAY = array();
        $RECOMMEND_PRODUCT = $arParams['TEMP_TOP_RECOMMEND'];         
        foreach($arrRecommendProduct as $ItemRecommendProduct) {
            $ItemRecommendProduct['BORDER_TABLE_STYLE'] = '';
            $RECOMMEND_PRODUCT_ID_ARRAY[] = $ItemRecommendProduct['ID'];
            if($i > 0){
                $ItemRecommendProduct['BORDER_TABLE_STYLE'] = ' border-top: 1px solid #E6EAEC; ';  
            }             
            $RECOMMEND_PRODUCT .= CSotbitMailingHelp::ReplaceVariables($arParams["TEMP_LIST_RECOMMEND"] , $ItemRecommendProduct);   
            $i++;           
        }
        $RECOMMEND_PRODUCT .= $arParams['TEMP_BOTTOM_RECOMMEND'];        
    }  
    

    
    
}     
?>