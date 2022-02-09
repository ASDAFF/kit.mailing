<?
$arTemplateParameters["RECOMMEND_SHOW"] =  array(
    "TABS" => 'TABS_RECOMMEND',
    "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),   
    "PARENT" => "PARAM_RECOMMEND_SETTING",
    "PARENT_NAME" => GetMessage("GROUP_RECOMMEND_SETTING_NAME"),
    "NAME" => GetMessage("RECOMMEND_SHOW_TITLE"),
    "TYPE" => "CHECKBOX",
    "DEFAULT" => "4",  
    "SORT" => "7",
    "REFRESH" => "Y",
);  

if($arCurrentValues["RECOMMEND_SHOW"]=='Y'){

 
    $arTemplateParameters["IBLOCK_TYPE_RECOMMEND"] =  array(   
        "TABS" => 'TABS_RECOMMEND',
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),    
        "PARENT" => "PARAM_IBLOCK_RECOMMEND",
        "PARENT_NAME" => GetMessage("GROUP_RECOMMEND_FILLTER_NAME"),
        "NAME" => GetMessage('IBLOCK_TYPE_RECOMMEND_TITLE'),
        "TYPE" => "LIST",
        "VALUES" => $arIBlockType,
        "REFRESH" => "Y",
        "SORT" => "10",
    );
    $arTemplateParameters["IBLOCK_ID_RECOMMEND"] =  array(
        "TABS" => 'TABS_RECOMMEND',
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),   
        "PARENT" => "PARAM_IBLOCK_RECOMMEND",
        "PARENT_NAME" => GetMessage("GROUP_RECOMMEND_FILLTER_NAME"),
        "NAME" => GetMessage('IBLOCK_ID_RECOMMEND_TITLE'),
        "TYPE" => "LIST",
        "VALUES" => $arIBlockRecommend,
        "REFRESH" => "Y",
        "SORT" => "20",
    ); 
    if($arIBlockSectionRecommend){
        $arTemplateParameters["IBLOCK_SECTION_RECOMMEND"] = array(
            "TABS" => 'TABS_RECOMMEND',
            "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),   
            "PARENT" => "PARAM_IBLOCK_RECOMMEND",
            "PARENT_NAME" => GetMessage("GROUP_RECOMMEND_FILLTER_NAME"),
            "NAME" => GetMessage('IBLOCK_SECTION_RECOMMEND_TITLE'),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arIBlockSectionRecommend,
            "REFRESH" => "Y",
            "SORT" => "22",
            "SIZE" => '10',
        );        
    }
    $arTemplateParameters["PROPERTY_FILLTER_1_RECOMMEND"] =  array(
        "TABS" => 'TABS_RECOMMEND',
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),   
        "PARENT" => "PARAM_IBLOCK_RECOMMEND",
        "PARENT_NAME" => GetMessage("GROUP_RECOMMEND_FILLTER_NAME"),
        "NAME" => GetMessage('PROPERTY_FILLTER_1_RECOMMEND_TITLE'),
        "TYPE" => "LIST",
        "VALUES" => $arPropertyListRecommend,
        "REFRESH" => "Y",
        "SORT" => "40",
    ); 
    $arTemplateParameters["PROPERTY_FILLTER_1_VALUE_RECOMMEND"] =  array(
        "TABS" => 'TABS_RECOMMEND',
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),   
        "PARENT" => "PARAM_IBLOCK_RECOMMEND",
        "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_RECOMMEND_NAME"),
        "NAME" => GetMessage('PROPERTY_FILLTER_1_VALUE_RECOMMEND_TITLE'),
        "TYPE" => "LIST",
        "VALUES" => $arRecommend_fillter_1_list,
        "REFRESH" => "N",
        "SORT" => "50",
        "NOTES" => GetMessage('PROPERTY_FILLTER_1_VALUE_RECOMMEND_NOTES'),
    );  
    $arTemplateParameters["DATE_CREATE_AGO_RECOMMEND"] = array(    
        "TABS" => 'TABS_RECOMMEND',    
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),   
        "PARENT" => "PARAM_IBLOCK_RECOMMEND",
        "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_RECOMMEND_NAME"),
        "NAME" => GetMessage('DATE_CREATE_AGO_RECOMMEND_TITLE'),
        "TYPE" => "DATE_PERIOD_AGO",
        "SORT" => "60",
    );      
      
    $arTemplateParameters["TOP_COUNT_FILLTER_RECOMMEND"] =  array(
        "TABS" => 'TABS_RECOMMEND',
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),   
        "PARENT" => "PARAM_IBLOCK_RECOMMEND",
        "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_RECOMMEND_NAME"),
        "NAME" => GetMessage('TOP_COUNT_FILLTER_RECOMMEND_TITLE'),
        "TYPE" => "INT",
        "SORT" => "70",
        "DEFAULT" => "4"
    );     
         
           
    $arTemplateParameters["SORT_BY_RECOMMEND"] =  array(
        "TABS" => 'TABS_RECOMMEND',
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),   
        "PARENT" => "PARAM_SORT_RECOMMEND",
        "PARENT_NAME" => GetMessage("GROUP_RECOMMEND_SORT_NAME"),
        "NAME" => GetMessage('SORT_BY_RECOMMEND_TITLE'),
        "TYPE" => "LIST",
        "VALUES" => $sort_by_iblock_list,
        "REFRESH" => "N",
        "SORT" => "20",
    );      
    $arTemplateParameters["SORT_ORDER_RECOMMEND"] =  array(
        "TABS" => 'TABS_RECOMMEND',
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),   
        "PARENT" => "PARAM_SORT_RECOMMEND",
        "PARENT_NAME" => GetMessage("GROUP_RECOMMEND_SORT_NAME"),
        "NAME" => GetMessage('SORT_ORDER_RECOMMEND_TITLE'),
        "TYPE" => "LIST",
        "VALUES" => $sort_order_iblock_list,
        "REFRESH" => "N",
        "SORT" => "40",
    );  
      
      //тип цены 
    $arTemplateParameters["PRICE_TYPE_RECOMMEND"] =  array(
        "TABS" => 'TABS_RECOMMEND',
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),   
        "PARENT" => "PARAM_PRICE_RECOMMEND",
        "PARENT_NAME" => GetMessage("GROUP_RECOMMEND_PRICE_NAME"),
        "NAME" => GetMessage('PRICE_TYPE_RECOMMEND_TITLE'),
        "TYPE" => "LIST",
        "VALUES" => $arPriceRecommend,
        "REFRESH" => "N",
        "SORT" => "40",
    );    
      
      //Вненший вид списка товаров
    $arTemplateParameters["IMG_WIDTH_RECOMMEND"] =  array(
        "TABS" => 'TABS_RECOMMEND',
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),   
        "PARENT" => "PARAM_TEMP_RECOMMEND",
        "PARENT_NAME" => GetMessage("GROUP_TEMP_RECOMMEND_NAME"),
        "NAME" => GetMessage("IMG_WIDTH_RECOMMEND_TITLE"),
        "TYPE" => "INT",
        "DEFAULT" => "100" ,
        "SORT" => "10",
    );    

    $arTemplateParameters["IMG_HEIGHT_RECOMMEND"] =  array(
        "TABS" => 'TABS_RECOMMEND',
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),    
        "PARENT" => "PARAM_TEMP_RECOMMEND",
        "PARENT_NAME" => GetMessage("GROUP_TEMP_RECOMMEND_NAME"),
        "NAME" => GetMessage("IMG_HEIGHT_RECOMMEND_TITLE"),
        "TYPE" => "INT",
        "DEFAULT" => "200" ,
        "SORT" => "20",
    );        
      
      
    $arTemplateParameters["TEMP_TOP_RECOMMEND"] =  array(
        "TABS" => 'TABS_RECOMMEND',
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),  
        "PARENT" => "PARAM_TEMP_RECOMMEND",
        "PARENT_NAME" => GetMessage("GROUP_TEMP_RECOMMEND_NAME"),
        "NAME" => GetMessage("TEMP_TOP_RECOMMEND_TITLE"),
        "COLS" => 40,
        "HEIGHT" => 160,
        "TYPE" => "TEXT",
        "DEFAULT" => GetMessage("TEMP_TOP_RECOMMEND_DEFAULT"), 
        "NOTES"  =>  GetMessage("TEMP_TOP_RECOMMEND_NOTES"),
        "SORT" => "40",
    );  
    $arTemplateParameters["TEMP_LIST_RECOMMEND"] =  array(
        "TABS" => 'TABS_RECOMMEND',
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"), 
        "PARENT" => "PARAM_TEMP_RECOMMEND",
        "PARENT_NAME" => GetMessage("GROUP_TEMP_RECOMMEND_NAME"),
        "NAME" => GetMessage("TEMP_LIST_RECOMMEND_TITLE"),
        "COLS" => 40,
        "HEIGHT" => 240,
        "TYPE" => "TEXT",
        "DEFAULT" => GetMessage("TEMP_LIST_RECOMMEND_DEFAULT"), 
        "NOTES"  =>  GetMessage("TEMP_LIST_RECOMMEND_NOTES", array('#IBLOCK_PROP#'=>$textPeremenRecommend)),
        "SORT" => "50",
    );    
    $arTemplateParameters["TEMP_BOTTOM_RECOMMEND"] =  array(
        "TABS" => 'TABS_RECOMMEND',
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),   
        "PARENT" => "PARAM_TEMP_RECOMMEND",
        "PARENT_NAME" => GetMessage("GROUP_TEMP_RECOMMEND_NAME"),
        "NAME" => GetMessage("TEMP_BOTTOM_RECOMMEND_TITLE"),
        "COLS" => 40,
        "HEIGHT" => 160,
        "TYPE" => "TEXT",
        "DEFAULT" => GetMessage("TEMP_BOTTOM_RECOMMEND_DEFAULT"), 
        "NOTES"  =>  GetMessage("TEMP_BOTTOM_RECOMMEND_NOTES"),
        "SORT" => "60",
    );    
      
      
    $arTemplateParameters["CANCEL_EMPTY_RECOMMEND"] =  array(
        "TABS" => 'TABS_RECOMMEND',
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),   
        "PARENT" => "PARAM_RECOMMEND_ADDITIONAL_SETTING",
        "PARENT_NAME" => GetMessage("GROUP_RECOMMEND_ADDITIONAL_SETTING_NAME"),
        "NAME" => GetMessage("CANCEL_EMPTY_RECOMMEND_TITLE"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "N",  
        "SORT" => "7",
        "NOTES" => GetMessage("CANCEL_EMPTY_RECOMMEND_NOTES"),
    );     
    
    $arTemplateParameters["INCLUDE_PHP_MODIF_RECOMMEND"] =  array(
        "TABS" => 'TABS_RECOMMEND',
        "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),   
        "PARENT" => "PHP_MODIF_RECOMMEND",
        "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_RECOMMEND_NAME"),
        "NAME" => GetMessage("INCLUDE_PHP_MODIF_RECOMMEND_TITLE"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "N",  
        "SORT" => "10",
        "REFRESH" => "Y",
    );        
     
      
    // PHP модификация
    if($arCurrentValues["INCLUDE_PHP_MODIF_RECOMMEND"]=='Y'){ 
        $arTemplateParameters["PHP_RECOMMEND_FILLTER_BEFORE"] =  array(
            "TABS" => 'TABS_RECOMMEND',
            "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),     
            "PARENT" => "PHP_MODIF_RECOMMEND",
            "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_RECOMMEND_NAME"),
            "NAME" => GetMessage("PHP_RECOMMEND_FILLTER_BEFORE_TITLE"),
            "HEIGHT" => 250,
            "WIDTH" => 1000,
            "TYPE" => "PHP",
            "SORT" => "70",
            "NOTES" => GetMessage("PHP_RECOMMEND_FILLTER_BEFORE_NOTES"),
        );      
            
        $arTemplateParameters["PHP_RECOMMEND_FILLTER_WHILE_AFTER"] =  array(
            "TABS" => 'TABS_RECOMMEND',
            "TABS_NAME" => GetMessage("TABS_RECOMMEND_NAME"),     
            "PARENT" => "PHP_MODIF_RECOMMEND",
            "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_RECOMMEND_NAME"),
            "NAME" => GetMessage("PHP_RECOMMEND_FILLTER_WHILE_AFTER_TITLE"),
            "HEIGHT" => 250,
            "WIDTH" => 1000,
            "TYPE" => "PHP",
            "SORT" => "90",
            "NOTES" => GetMessage("PHP_RECOMMEND_FILLTER_WHILE_AFTER_NOTES"),
        );            
    }
 
    
}
?>