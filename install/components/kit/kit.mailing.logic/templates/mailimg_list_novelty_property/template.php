<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $DB;  

// ������������ ������ ��������� 
if(!CModule::IncludeModule("iblock"))
{
    return;
}
      
      
if($arParams["NOVELTY_GOODS_HOURS_AGO_END"] && $arParams["NOVELTY_GOODS_HOURS_AGO_START"]){
    $arParams["NOVELTY_GOODS_DATE_CREATE_from"] =  date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H")-$arParams["NOVELTY_GOODS_HOURS_AGO_END"], date("i"), 0,  date("n"), date("d"), date("Y"))); 
    $arParams["NOVELTY_GOODS_DATE_CREATE_to"] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H")-$arParams["NOVELTY_GOODS_HOURS_AGO_START"], date("i"), 0,  date("n"), date("d"), date("Y")));        
}
if($arParams['NOVELTY_GOODS_DATE_CREATE_from'] && $arParams['NOVELTY_GOODS_DATE_CREATE_to']) {
    $arParams["NOVELTY_GOODS_DATE_CREATE_from"] = $arParams['NOVELTY_GOODS_DATE_CREATE_from'];
    $arParams["NOVELTY_GOODS_DATE_CREATE_to"] = $arParams['NOVELTY_GOODS_DATE_CREATE_to'];     
}      
   



//�������� �� ���� ������������� ��������
//START
    
    $ARR_USER_ID_SEND = array();
    $arrUser = array();
    
    
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
        include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_USER_PARAM_AFTER.php");
        $arrUser[$arItemsUser['ID']] = $arItemsUser;
    }
    

    // ������� ������� ������ � ���������
    // START
    $categoriesLi = CKitMailingHelp::GetCategoriesInfo();
    $Novelty_property = array();
    $MailingCatProperty = array();
    $mailing_category_id = array();
    foreach($categoriesLi as $v) {
        
  
        if(empty($arParams['CATEGORIES_ID'])) {      
            if($v['PARAM_1']=='PROPERTY' && $v['PARAM_2'] && $v['PARAM_3']) {
                $Novelty_property[$v['PARAM_2']] = $v['PARAM_3']; 
                $mailing_category_id[] = $v['ID'];  
                $MailingCatProperty[$v['ID']] = array('PARAM_2'=>$v['PARAM_2'],'PARAM_3'=>$v['PARAM_3']);   
            }
        }
        else {
            if(in_array($v['ID'], $arParams['CATEGORIES_ID']) && $v['PARAM_2'] && $v['PARAM_3']) {
                $Novelty_property[$v['PARAM_2']] = $v['PARAM_3'];
                $mailing_category_id[] = $v['ID'];
                $MailingCatProperty[$v['ID']] = array('PARAM_2'=>$v['PARAM_2'],'PARAM_3'=>$v['PARAM_3']);                                             
            }
        }
    }
    // END
    
           
    // ������� ���������� �� ��������
    // START
    $arSelectNovelty = Array(
        "ID", 
        "NAME",
        "IBLOCK_ID", 
        "PREVIEW_TEXT",
        'DETAIL_TEXT',
        'PREVIEW_PICTURE',
        'DETAIL_PICTURE',
        'LIST_PAGE_URL',
        'DETAIL_PAGE_URL',
        'SECTION_ID'
    );
    $arFilterNovelty = Array(
        "ACTIVE" => 'Y' 
    );
    if($arParams["IBLOCK_ID_NOVELTY_GOODS"]) {
        $arFilterNovelty['IBLOCK_ID'] = $arParams["IBLOCK_ID_NOVELTY_GOODS"];    
    }
    if($arParams['PROPERTY_FILLTER_1_NOVELTY_GOODS'] &&  $arParams['PROPERTY_FILLTER_1_VALUE_NOVELTY_GOODS']) {
         $arFilterNovelty["PROPERTY_".$arParams["PROPERTY_FILLTER_1_NOVELTY_GOODS"]]  = $arParams["PROPERTY_FILLTER_1_VALUE_NOVELTY_GOODS"];   
    }
    // ���� �������� ��
    if($arParams["NOVELTY_GOODS_DATE_CREATE_from"]){
        $arFilterNovelty['>=DATE_CREATE'] = $arParams["NOVELTY_GOODS_DATE_CREATE_from"];     
    }
    // ���� �������� ��
    if($arParams["NOVELTY_GOODS_DATE_CREATE_to"]){
        $arFilterNovelty['<=DATE_CREATE'] = $arParams["NOVELTY_GOODS_DATE_CREATE_to"];      
    }

    $arNavStartParams = false;              
    
    
   
    
    $arSortNovelty = Array(
        $arParams["SORT_BY_NOVELTY_GOODS"] => $arParams["SORT_ORDER_NOVELTY_GOODS"]
    );   
    
    if($arParams['TOP_COUNT_FILLTER_NOVELTY_GOODS_TO']){
        $arNavStartParams = array('nTopCount'=>$arParams['TOP_COUNT_FILLTER_NOVELTY_GOODS_TO']);    
    }  else {
        $arNavStartParams = false;              
    }        
    
    //������� ����
    //START
    if(empty($arParams['PRICE_TYPE_NOVELTY_GOODS']) && CModule::IncludeModule("catalog")){
        $dbPrice = CCatalogGroup::GetList(array(),array('BASE'=>'Y'));
        while($arPrice = $dbPrice->Fetch()) {
            $arParams['PRICE_TYPE_NOVELTY_GOODS'] = array($arPrice['NAME']);        
        }      
    }
    elseif(!is_array($arParams['PRICE_TYPE_NOVELTY_GOODS'])){
        $arParams['PRICE_TYPE_NOVELTY_GOODS'] = array($arParams['PRICE_TYPE_NOVELTY_GOODS']);        
    }   
    $arParams['NOVELTY_GOODS_PRICES'] = CIBlockPriceTools::GetCatalogPrices($arParams["IBLOCK_ID_NOVELTY_GOODS"], $arParams['PRICE_TYPE_NOVELTY_GOODS']);
    
    foreach($arParams['NOVELTY_GOODS_PRICES'] as $value)
    {
        $arSelectNovelty[] = $value["SELECT"];
    }    
    
    //END    
            
    
    foreach($Novelty_property as $key_Novelty_property=>$value_Novelty_property) {
    
        $arrNoveltyProduct = array();        
        
        // �������� ������ ��� ������
        if($key_Novelty_property &&  $value_Novelty_property) {
             $arFilterNovelty["PROPERTY_".$key_Novelty_property]  = $value_Novelty_property;   
        }
        
        
        include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_NOVELTY_GOODS_FILLTER_BEFORE.php");
                   
        $res = CIBlockElement::GetList($arSortNovelty, $arFilterNovelty, false, $arNavStartParams, $arSelectNovelty);
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
                $fileImg = CFile::ResizeImageGet($arFields['PICTURE'], array('width'=> $arParams['NOVELTY_GOODS_IMG_WIDTH'], 'height'=> $arParams['NOVELTY_GOODS_IMG_HEIGHT'] ), BX_RESIZE_IMAGE_PROPORTIONAL, true);  
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
            
            //������� ���� ������
            //START
            if(CModule::IncludeModule("catalog") && CModule::IncludeModule("sale")) {
                $priceProduct = array();
                $ID_IBLOCK_PRODUCT = $arFields['IBLOCK_ID'];
                $ID_PRODUCT = $arFields['ID'];

                //���� ���� ������ ������� ����
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
                    foreach($arParams['NOVELTY_GOODS_PRICES'] as $value)
                    {
                        $arOffersRecommendSelect[] = $value["SELECT"];
                    } 
                                  
                    $rsOffers = CIBlockElement::GetList(array('SORT'=>'ASC'), $arOffersRecommendFillter,false,false,$arOffersRecommendSelect); 
                    while ($arOffer = $rsOffers->GetNext()) 
                    { 
                        $arrSiteId = CKitMailingHelp::GetSiteId();
                        $priceProduct = CIBlockPriceTools::GetItemPrices($ID_IBLOCK_PRODUCT, $arParams['NOVELTY_GOODS_PRICES'], $arOffer, true, array(), 0, $arrSiteId);
                        if($priceProduct) { 
                            break;
                        }
                    } 
                           
                } else {
                    $arrSiteId = CKitMailingHelp::GetSiteId();
                    $priceProduct = CIBlockPriceTools::GetItemPrices($ID_IBLOCK_PRODUCT, $arParams['NOVELTY_GOODS_PRICES'], $arFields, true, array(), 0, $arrSiteId); 
                }
                

                foreach($arParams['NOVELTY_GOODS_PRICES'] as $k=>$v) {
                    //������������ ����
                    $arFields['PRICE_OPTIMAL'] = floor($priceProduct[$k]['DISCOUNT_VALUE']);
                    $arFields['PRICE_OPTIMAL_CURRENCY'] = $priceProduct[$k]['PRINT_DISCOUNT_VALUE'];  
                    //���� �� �������
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
                    // ���� ��� ������
                    $arFields['PRINT_PRICE'] = $priceProduct[$k]['PRINT_DISCOUNT_VALUE'];  
                    $arFields['PRICE'] = $priceProduct[$k]['DISCOUNT_VALUE'];           
                } 
        
            }  
            //END           
         

            $phpIncludeFunction = array();
            include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_NOVELTY_GOODS_FILLTER_WHILE_AFTER.php");

            if($phpIncludeFunction['isContinue']){
                continue;    
            }  
            if($phpIncludeFunction['isBreak']){
                break;    
            }                  
                
            if($arFields) {
                $arrNoveltyProduct[] = $arFields;                 
            }    
                


        }  

                      
         
        // �������� ��������������� ������ 
        if(count($arrNoveltyProduct) >= $arParams['TOP_COUNT_FILLTER_NOVELTY_GOODS_FROM']) {
            $i = 0;  
            $NOVELTY_PRODUCT_ID_ARRAY[$key_Novelty_property.$value_Novelty_property] = array();     
            $NOVELTY_GOODS_PRODUCT[$key_Novelty_property.$value_Novelty_property] = CKitMailingHelp::ReplaceVariables($arParams['TEMP_NOVELTY_GOODS_TOP'] , array('PROPERTY_VALUE'=>$value_Novelty_property));
            foreach($arrNoveltyProduct as $ItemNoveltyProduct) {
                $ItemNoveltyProduct['BORDER_TABLE_STYLE'] = '';
                $NOVELTY_PRODUCT_ID_ARRAY[$key_Novelty_property.$value_Novelty_property][] = $ItemNoveltyProduct['ID'];
                if($i > 0){
                    $ItemNoveltyProduct['BORDER_TABLE_STYLE'] = ' border-top: 1px solid #E6EAEC; ';  
                }             
                $NOVELTY_GOODS_PRODUCT[$key_Novelty_property.$value_Novelty_property] .= CKitMailingHelp::ReplaceVariables($arParams["TEMP_NOVELTY_GOODS_LIST"] , $ItemNoveltyProduct);
                $i++;           
            }
            $NOVELTY_GOODS_PRODUCT[$key_Novelty_property.$value_Novelty_property] .= CKitMailingHelp::ReplaceVariables($arParams['TEMP_NOVELTY_GOODS_BOTTOM'] , array('PROPERTY_VALUE'=>$value_Novelty_property));
        }    
    
        
    }  
           
    // END  
    

    

    // ��������� ��������  ������� ������ � ���������� ���������
    // START
    $arr_subscr_id_category = CKitMailingSubscribers::GetCategoryByAllSubscribers();
         
    $arrEmailSend = array(); 
    $dbSubscribers = CKitMailingSubscribers::GetList(array(),array('CATEGORIES_ID' => $mailing_category_id ,'ACTIVE'=>'Y'), false, array('ID','USER_ID','EMAIL_TO'));
    while($resSubscribers = $dbSubscribers->Fetch()){

        $EmailSend = array();
                              
        //���� ���� ������������ ������� ������
        if($arrUser[$resSubscribers['USER_ID']] ) {
            foreach($arrUser[$resSubscribers['USER_ID']] as $ku => $vu){
                $EmailSend['USER_'.$ku]  = $vu;       
            }    
            $EmailSend['PARAM_1'] = $resSubscribers['USER_ID'];                       
        }
        if($EmailSend['USER_ID']){
            $ARR_USER_ID_SEND[] = $EmailSend['USER_ID'];               
        } 
        
        // ������� ������� ��������� � ���������
        $EmailSend['NOVELTY_GOODS'] = '';
        $EmailSend['PARAM_MESSEGE']['NOVELTY_PRODUCT_PRODUCT_ID'] = array();
        foreach($MailingCatProperty as $categor=>$proper){
            if($arr_subscr_id_category[$resSubscribers['ID']][$categor]){
                $EmailSend['NOVELTY_GOODS'] .= $NOVELTY_GOODS_PRODUCT[$proper['PARAM_2'].$proper['PARAM_3']];    
           
                if($NOVELTY_PRODUCT_ID_ARRAY[$proper['PARAM_2'].$proper['PARAM_3']]){
                    $EmailSend['PARAM_MESSEGE']['NOVELTY_PRODUCT_PRODUCT_ID'] =  array_merge($EmailSend['PARAM_MESSEGE']['NOVELTY_PRODUCT_PRODUCT_ID'], $NOVELTY_PRODUCT_ID_ARRAY[$proper['PARAM_2'].$proper['PARAM_3']]);    
                }             
      
            }    
        }      
     
   
   
        // ������� ������� ��������� � ���������

        if(empty($EmailSend['NOVELTY_GOODS'])){
            continue;   
        }
   
        foreach($resSubscribers as $ku => $vu){
            $EmailSend['SUBSCRIBLE_'.$ku]  = $vu;       
        }   
         

        $arrEmailSend[] = $EmailSend;            
          
    }
  
    // END
    
                                                                                  
                       
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