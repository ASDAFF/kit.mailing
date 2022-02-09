<?
use \Bitrix\Catalog\CatalogViewedProductTable as CatalogViewedProductTable;
if($arParams["VIEWED_SHOW"] == 'Y' && CModule::IncludeModule("iblock") && CModule::IncludeModule("catalog")) {  
    //получим FUSER_ID пользователей магазина
    //START 
    $arr_user_id_views = $ARR_USER_ID_SEND;
    
    $arr_result_fuser_id = array();
    $arr_result_user_id = array();
    if(CModule::IncludeModule('sale')) {
        foreach($arr_user_id_views as $item_user_id_views) {
            $res_FUSER_ID = CSaleUser::GetList(array('USER_ID' => $item_user_id_views)); 
            if($res_FUSER_ID['USER_ID'] == $item_user_id_views){
                $arr_result_fuser_id[$item_user_id_views] = $res_FUSER_ID['ID']; 
                $arr_result_user_id[$res_FUSER_ID['ID']] = $item_user_id_views;           
            }       
        }        
    }

    //END   
    
    //получим id просмотренных товаров
    //START
    if($arr_result_fuser_id) {
        $arr_user_product_id = array();
        $arr_product_id = array();
        
        //fillter
        $viewedFillter = array(
            "FUSER_ID" => $arr_result_fuser_id    
        );
        //sort
        $viewedOrder = array(
            $arParams['SORT_BY_VIEWED'] => $arParams['SORT_ORDER_VIEWED']    
        );
        
        $viewedIterator = CatalogViewedProductTable::GetList(array(
            "filter" => $viewedFillter,
            'order' => $viewedOrder,
            'limit' => 1000000000
        ));
        while($viewedProduct = $viewedIterator->fetch())
        {
            if(count($arr_user_product_id[$arr_result_user_id[$viewedProduct['FUSER_ID']]]) < $arParams['TOP_COUNT_FILLTER_VIEWED']){
                $arr_user_product_id[$arr_result_user_id[$viewedProduct['FUSER_ID']]][] = $viewedProduct['PRODUCT_ID'];
                $arr_product_id[] = $viewedProduct['PRODUCT_ID'];            
            }
        }        
    }
    //END
    
    // получим товары 
    // START
    if($arr_product_id) {

        $arrViewedProduct = array();
        $arSelectViewed = Array(
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
        $arFilterViewed = Array(
            "ACTIVE" => 'Y' 
        );
        // инфоблок просмотренных товаров
        if($arParams["IBLOCK_ID_VIEWED"]) {
            $arFilterViewed['IBLOCK_ID'] = $arParams["IBLOCK_ID_VIEWED"];    
        }
        // разделы просмотренных товаров
        if($arParams["IBLOCK_SECTION_VIEWED"]) {
            $arFilterViewed['SECTION_ID'] = $arParams["IBLOCK_SECTION_VIEWED"];   
            $arFilterViewed['INCLUDE_SUBSECTIONS'] = "Y";  
        }          
        // id просмотренных товаров
        if($arr_product_id) {
            $arFilterViewed['ID'] = $arr_product_id;    
        }        
        if($arParams['PROPERTY_FILLTER_1_VIEWED'] &&  $arParams['PROPERTY_FILLTER_1_VALUE_VIEWED']) {
             $arFilterViewed["PROPERTY_".$arParams["PROPERTY_FILLTER_1_VIEWED"]]  = $arParams["PROPERTY_FILLTER_1_VALUE_VIEWED"];   
        }
        
        $arNavStartParamsViewed = false;              
        $arSortViewed = Array();   
        
        
        //добавим цену
        //START
        if(empty($arParams['PRICE_TYPE_VIEWED']) && CModule::IncludeModule("catalog")){
            $dbPrice = CCatalogGroup::GetList(array(),array('BASE'=>'Y'));
            while($arPrice = $dbPrice->Fetch()) {
                $arParams['PRICE_TYPE_VIEWED'] = array($arPrice['NAME']);        
            }      
        }
        elseif(!is_array($arParams['PRICE_TYPE_VIEWED'])){
            $arParams['PRICE_TYPE_VIEWED'] = array($arParams['PRICE_TYPE_VIEWED']);        
        }   
        $arParams['VIEWED_PRICES'] = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID_VIEWED"], $arParams['PRICE_TYPE_VIEWED']);
        
        foreach($arParams['VIEWED_PRICES'] as $value)
        {
            $arSelectViewed[] = $value["SELECT"];
        }    
        
        //END
        if($arParams['INCLUDE_PHP_MODIF_VIEWED']=='Y'){ 
            include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_VIEWED_FILLTER_BEFORE.php");             
        }        
          
       
                     
        $res = CIBlockElement::GetList($arSortViewed, $arFilterViewed, false, $arNavStartParamsViewed, $arSelectViewed);
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
                $fileImg = CFile::ResizeImageGet($arFields['PICTURE'], array('width'=> $arParams['IMG_WIDTH_VIEWED'], 'height'=> $arParams['IMG_HEIGHT_VIEWED'] ), BX_RESIZE_IMAGE_PROPORTIONAL, true);  
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
                    $arOffersViewedFillter = array(
                        'IBLOCK_ID' => $arInfo['IBLOCK_ID'], 
                        'PROPERTY_'.$arInfo['SKU_PROPERTY_ID'] => $ID_PRODUCT                
                    );  
                    //SELECT              
                    $arOffersViewedSelect = array(
                        'ID',
                        'IBLOCK_ID'  
                    );
                    foreach($arParams['VIEWED_PRICES'] as $value)
                    {
                        $arOffersViewedSelect[] = $value["SELECT"];
                    }      
                          
                
                    $rsOffers = CIBlockElement::GetList(array('SORT'=>'ASC'), $arOffersViewedFillter,false,false,$arOffersViewedSelect); 
                    while ($arOffer = $rsOffers->GetNext()) 
                    { 
                        $arrSiteId = CSotbitMailingHelp::GetSiteId();
                        $priceProduct = CIBlockPriceTools::GetItemPrices($ID_IBLOCK_PRODUCT, $arParams['VIEWED_PRICES'], $arOffer, true, array(), 0, $arrSiteId);
                        if($priceProduct) { 
                            break;
                        }
                    } 
                           
                } else {
                    $arrSiteId = CSotbitMailingHelp::GetSiteId();
                    $priceProduct = CIBlockPriceTools::GetItemPrices($ID_IBLOCK_PRODUCT, $arParams['VIEWED_PRICES'], $arFields, true, array(), 0, $arrSiteId); 
                }
                
            
                foreach($arParams['VIEWED_PRICES'] as $k=>$v) {
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
            if($arParams['INCLUDE_PHP_MODIF_VIEWED']=='Y'){  
                include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_VIEWED_FILLTER_WHILE_AFTER.php");                        
            }
  
            
            if($phpIncludeFunction['isContinue']){
                continue;    
            }  
            if($phpIncludeFunction['isBreak']){
                break;    
            }          
                        
            if($arFields){
                $arrViewedProduct[$arFields['ID']] = $arFields;           
            }             
            
            
            
        } 
        
    }     
    //END
    
    
    //Составим список для пользователей
    //START
    if(count($arrViewedProduct) > 0) {
        $VIEWED_PRODUCT_USER = array();
        $VIEWED_PRODUCT_USER_ID_ARRAY = array();
        foreach($arr_user_product_id as $kuser => $vproduct) {
            $VIEWED_ITEM_CONTENT = '';
            $i = 0;  
            foreach($vproduct as $viewed_product_id) {
                if($arrViewedProduct[$viewed_product_id]){
                    $arrViewedProduct[$viewed_product_id]['BORDER_TABLE_STYLE'] = '';
                    if($i > 0){
                        $arrViewedProduct[$viewed_product_id]['BORDER_TABLE_STYLE'] = ' border-top: 1px solid #E6EAEC; ';  
                    }                     
                    $VIEWED_ITEM_CONTENT .= CSotbitMailingHelp::ReplaceVariables($arParams["TEMP_LIST_VIEWED"] , $arrViewedProduct[$viewed_product_id]);   
                    $i++;                          
                }        
            } 
            if($i>0) {
                $VIEWED_PRODUCT_USER[$kuser] = $arParams['TEMP_TOP_VIEWED'].$VIEWED_ITEM_CONTENT.$arParams['TEMP_BOTTOM_VIEWED'];           
                $VIEWED_PRODUCT_USER_ID_ARRAY[$kuser] = $arr_user_product_id[$kuser];        
            }
                       
               
        }
                
    }     
    //END
}  
?>