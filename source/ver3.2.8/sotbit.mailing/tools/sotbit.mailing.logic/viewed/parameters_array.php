<?
if(CModule::IncludeModule("catalog")) { 
    
    $arTemplateParameters["VIEWED_SHOW"] =  array(
        "TABS" => 'TABS_VIEWED',
        "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),   
        "PARENT" => "PARAM_VIEWED_SETTING",
        "PARENT_NAME" => GetMessage("GROUP_VIEWED_SETTING_NAME"),
        "NAME" => GetMessage("VIEWED_SHOW_TITLE"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "4" ,
        "SORT" => "7",
        "REFRESH" => "Y",
    );  

    if($arCurrentValues["VIEWED_SHOW"]=='Y'){ 
        
        $arTemplateParameters["IBLOCK_TYPE_VIEWED"] =  array(   
            "TABS" => 'TABS_VIEWED',
            "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),    
            "PARENT" => "PARAM_IBLOCK_VIEWED",
            "PARENT_NAME" => GetMessage("GROUP_VIEWED_FILLTER_NAME"),
            "NAME" => GetMessage('IBLOCK_TYPE_VIEWED_TITLE'),
            "TYPE" => "LIST",
            "VALUES" => $arIBlockType,
            "REFRESH" => "Y",
            "SORT" => "10",
        );
        $arTemplateParameters["IBLOCK_ID_VIEWED"] =  array(
            "TABS" => 'TABS_VIEWED',
            "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),     
            "PARENT" => "PARAM_IBLOCK_VIEWED",
            "PARENT_NAME" => GetMessage("GROUP_VIEWED_FILLTER_NAME"),
            "NAME" => GetMessage('IBLOCK_ID_VIEWED_TITLE'),
            "TYPE" => "LIST",
            "VALUES" => $arIBlockViewed,
            "REFRESH" => "Y",
            "SORT" => "20",
        ); 
        if($arIBlockSectionViewed){
            $arTemplateParameters["IBLOCK_SECTION_VIEWED"] = array(
                "TABS" => 'TABS_VIEWED',
                "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),     
                "PARENT" => "PARAM_IBLOCK_VIEWED",
                "PARENT_NAME" => GetMessage("GROUP_VIEWED_FILLTER_NAME"),
                "NAME" => GetMessage('IBLOCK_SECTION_VIEWED_TITLE'),
                "TYPE" => "LIST",
                "MULTIPLE" => "Y",
                "VALUES" => $arIBlockSectionViewed,
                "REFRESH" => "Y",
                "SORT" => "22",
                "SIZE" => '10',
            );        
        }    
        $arTemplateParameters["PROPERTY_FILLTER_1_VIEWED"] =  array(
            "TABS" => 'TABS_VIEWED',
            "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),       
            "PARENT" => "PARAM_IBLOCK_VIEWED",
            "PARENT_NAME" => GetMessage("GROUP_VIEWED_FILLTER_NAME"),
            "NAME" => GetMessage('PROPERTY_FILLTER_1_VIEWED_TITLE'),
            "TYPE" => "LIST",
            "VALUES" => $arPropertyListViewed,
            "REFRESH" => "Y",
            "SORT" => "40",
        ); 
        $arTemplateParameters["PROPERTY_FILLTER_1_VALUE_VIEWED"] =  array(
            "TABS" => 'TABS_VIEWED',
            "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),     
            "PARENT" => "PARAM_IBLOCK_VIEWED",
            "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_VIEWED_NAME"),
            "NAME" => GetMessage('PROPERTY_FILLTER_1_VALUE_VIEWED_TITLE'),
            "TYPE" => "LIST",
            "VALUES" => $arViewed_fillter_1_list,
            "REFRESH" => "N",
            "SORT" => "50",
            "NOTES" => GetMessage('PROPERTY_FILLTER_1_VALUE_VIEWED_NOTES'),
        );    
        $arTemplateParameters["TOP_COUNT_FILLTER_VIEWED"] =  array(
            "TABS" => 'TABS_VIEWED',
            "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),     
            "PARENT" => "PARAM_IBLOCK_VIEWED",
            "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_VIEWED_NAME"),
            "NAME" => GetMessage('TOP_COUNT_FILLTER_VIEWED_TITLE'),
            "TYPE" => "INT",
            "SORT" => "50",
            "DEFAULT" => "4"
        );            
        $arTemplateParameters["SORT_BY_VIEWED"] =  array(
            "TABS" => 'TABS_VIEWED',
            "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),    
            "PARENT" => "PARAM_SORT_VIEWED",
            "PARENT_NAME" => GetMessage("GROUP_VIEWED_SORT_NAME"),
            "NAME" => GetMessage('SORT_BY_VIEWED_TITLE'),
            "TYPE" => "LIST",
            "VALUES" => $sort_by_viewed_list,
            "REFRESH" => "N",
            "SORT" => "20",
        );      
        $arTemplateParameters["SORT_ORDER_VIEWED"] =  array(
            "TABS" => 'TABS_VIEWED',
            "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),     
            "PARENT" => "PARAM_SORT_VIEWED",
            "PARENT_NAME" => GetMessage("GROUP_VIEWED_SORT_NAME"),
            "NAME" => GetMessage('SORT_ORDER_VIEWED_TITLE'),
            "TYPE" => "LIST",
            "VALUES" => $sort_order_viewed_list,
            "REFRESH" => "N",
            "SORT" => "40",
        );  
          
          //тип цены 
        $arTemplateParameters["PRICE_TYPE_VIEWED"] =  array(
            "TABS" => 'TABS_VIEWED',
            "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),       
            "PARENT" => "PARAM_PRICE_VIEWED",
            "PARENT_NAME" => GetMessage("GROUP_VIEWED_PRICE_NAME"),
            "NAME" => GetMessage('PRICE_TYPE_VIEWED_TITLE'),
            "TYPE" => "LIST",
            "VALUES" => $arPriceViewed,
            "REFRESH" => "N",
            "SORT" => "40",
        );   
          
          
          
          //Вненший вид списка товаров
        $arTemplateParameters["IMG_WIDTH_VIEWED"] =  array(
            "TABS" => 'TABS_VIEWED',
            "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),    
            "PARENT" => "PARAM_TEMP_VIEWED",
            "PARENT_NAME" => GetMessage("GROUP_TEMP_VIEWED_NAME"),
            "NAME" => GetMessage("IMG_WIDTH_VIEWED_TITLE"),
            "TYPE" => "INT",
            "DEFAULT" => "100" ,
            "SORT" => "10",
        );    

        $arTemplateParameters["IMG_HEIGHT_VIEWED"] =  array(
            "TABS" => 'TABS_VIEWED',
            "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),       
            "PARENT" => "PARAM_TEMP_VIEWED",
            "PARENT_NAME" => GetMessage("GROUP_TEMP_VIEWED_NAME"),
            "NAME" => GetMessage("IMG_HEIGHT_VIEWED_TITLE"),
            "TYPE" => "INT",
            "DEFAULT" => "200" ,
            "SORT" => "20",
        );        
        $arTemplateParameters["TEMP_TOP_VIEWED"] =  array(
            "TABS" => 'TABS_VIEWED',
            "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),     
            "PARENT" => "PARAM_TEMP_VIEWED",
            "PARENT_NAME" => GetMessage("GROUP_TEMP_VIEWED_NAME"),
            "NAME" => GetMessage("TEMP_TOP_VIEWED_TITLE"),
            "COLS" => 40,
            "HEIGHT" => 160,
            "TYPE" => "TEXT",
            "DEFAULT" => GetMessage("TEMP_TOP_VIEWED_DEFAULT"), 
            "NOTES"  =>  GetMessage("TEMP_TOP_VIEWED_NOTES"),
            "SORT" => "40",
        );  
        $arTemplateParameters["TEMP_LIST_VIEWED"] =  array(
            "TABS" => 'TABS_VIEWED',
            "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),    
            "PARENT" => "PARAM_TEMP_VIEWED",
            "PARENT_NAME" => GetMessage("GROUP_TEMP_VIEWED_NAME"),
            "NAME" => GetMessage("TEMP_LIST_VIEWED_TITLE"),
            "COLS" => 40,
            "HEIGHT" => 240,
            "TYPE" => "TEXT",
            "DEFAULT" => GetMessage("TEMP_LIST_VIEWED_DEFAULT"), 
            "NOTES"  =>  GetMessage("TEMP_LIST_VIEWED_NOTES", array('#IBLOCK_PROP#'=>$textPeremenViewed)),
            "SORT" => "50",
        );    
        $arTemplateParameters["TEMP_BOTTOM_VIEWED"] =  array(
            "TABS" => 'TABS_VIEWED',
            "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),     
            "PARENT" => "PARAM_TEMP_VIEWED",
            "PARENT_NAME" => GetMessage("GROUP_TEMP_VIEWED_NAME"),
            "NAME" => GetMessage("TEMP_BOTTOM_VIEWED_TITLE"),
            "COLS" => 40,
            "HEIGHT" => 160,
            "TYPE" => "TEXT",
            "DEFAULT" => GetMessage("TEMP_BOTTOM_VIEWED_DEFAULT"), 
            "NOTES"  =>  GetMessage("TEMP_BOTTOM_VIEWED_NOTES"),
            "SORT" => "60",
        );    
         
        $arTemplateParameters["INCLUDE_PHP_MODIF_VIEWED"] =  array(
            "TABS" => 'TABS_VIEWED',
            "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),   
            "PARENT" => "PHP_MODIF_VIEWED",
            "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_VIEWED_NAME"),
            "NAME" => GetMessage("INCLUDE_PHP_MODIF_VIEWED_TITLE"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",  
            "SORT" => "10",
            "REFRESH" => "Y",
        );         
        // PHP модификация 
        if($arCurrentValues["INCLUDE_PHP_MODIF_VIEWED"]=='Y'){  
            $arTemplateParameters["PHP_VIEWED_FILLTER_BEFORE"] =  array(
                "TABS" => 'TABS_VIEWED',
                "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),        
                "PARENT" => "PHP_MODIF_VIEWED",
                "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_VIEWED_NAME"),
                "NAME" => GetMessage("PHP_VIEWED_FILLTER_BEFORE_TITLE"),
                "HEIGHT" => 250,
                "WIDTH" => 1000,
                "TYPE" => "PHP",
                "SORT" => "70",
                "NOTES" => GetMessage("PHP_VIEWED_FILLTER_BEFORE_NOTES"),
            );        
            $arTemplateParameters["PHP_VIEWED_FILLTER_WHILE_AFTER"] =  array(
                "TABS" => 'TABS_VIEWED',
                "TABS_NAME" => GetMessage("TABS_VIEWED_NAME"),        
                "PARENT" => "PHP_MODIF_VIEWED",
                "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_VIEWED_NAME"),
                "NAME" => GetMessage("PHP_VIEWED_FILLTER_WHILE_AFTER_TITLE"),
                "HEIGHT" => 250,
                "WIDTH" => 1000,
                "TYPE" => "PHP",
                "SORT" => "90",
                "NOTES" => GetMessage("PHP_VIEWED_FILLTER_WHILE_AFTER_NOTES"),
            );            
        }   
      
           
    }    
    
}

?>