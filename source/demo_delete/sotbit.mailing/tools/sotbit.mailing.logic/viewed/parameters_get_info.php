<?
    $arIBlockType = CIBlockParameters::GetIBlockTypes();
    if(empty($arCurrentValues["IBLOCK_TYPE_VIEWED"])){
        foreach($arIBlockType as $key => $value) {
           $arCurrentValues["IBLOCK_TYPE_VIEWED"] = $key;
           break;
        } 
    }    
    
    // получим инфоблок
    $arIBlockViewed = array('' => GetMessage('SELECT_CHANGE'));
    if($arCurrentValues["IBLOCK_TYPE_VIEWED"]) {
        

        $rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE_VIEWED"], "ACTIVE"=>"Y"));
        while($arr=$rsIBlock->Fetch())
        {
            $arIBlockViewed[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
        }    
        
    }
    //свойство фильтрации 
    $arPropertyListViewed = array('' => GetMessage('SELECT_CHANGE'));
    $textPeremenViewed = '';  
    if($arCurrentValues["IBLOCK_ID_VIEWED"]) { 

        $rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID_VIEWED"]));
        while($arr = $rsProp->Fetch())
        {
            $textPeremenViewed .=  '#PROP_'.$arr["CODE"].'# - '.$arr["NAME"].'<br />'; 
            if($arr["PROPERTY_TYPE"] == "L") {   
                $arPropertyListViewed[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
            }
        }
        
    } 
    //значения свойства фильтрации
    $arViewed_fillter_1_list = array('' => GetMessage('SELECT_CHANGE'));

    if($arCurrentValues["IBLOCK_ID_VIEWED"] && $arCurrentValues["PROPERTY_FILLTER_1_VIEWED"]) { 
        $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID_VIEWED"], "CODE"=>$arCurrentValues["PROPERTY_FILLTER_1_VIEWED"]));
        while($enum_fields = $property_enums->GetNext())
        {      
            $arViewed_fillter_1_list[$enum_fields["ID"]] = "[".$enum_fields["XML_ID"]."] ".$enum_fields["VALUE"];   
        }         
    }    

    //получим список разделов
    if($arCurrentValues["IBLOCK_ID_VIEWED"]){
        $arIBlockSectionViewed = array();
        $dbSection = CIBlockSection::GetTreeList(Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID_VIEWED"]), array("ID", "NAME", "DEPTH_LEVEL"));  
        while($resSection = $dbSection->GetNext()){
            $arIBlockSectionViewed[$resSection['ID']] = str_repeat(" . ", $resSection["DEPTH_LEVEL"]).$resSection["NAME"];  
            
        }     
    }    
    
    //получим типы цен
    $arPriceViewed = array();
    if(CModule::IncludeModule("catalog")) {
        $dbPrice = CCatalogGroup::GetList();
        while($arPrice = $dbPrice->Fetch()) {
            $arPriceViewed[$arPrice["NAME"]] = "[".$arPrice["ID"]."] ".$arPrice["NAME"];
        } 
    }  
 
    $sort_by_viewed_list = array(
        'DATE_VISIT' => GetMessage('SORT_BY_VIEWED_VALUE_DATE_VISIT'),
        'VIEW_COUNT' => GetMessage('SORT_BY_VIEWED_VALUE_VIEW_COUNT'),                                    
    );  
    $sort_order_viewed_list = array(
        'DESC' => GetMessage('SORT_ORDER_IBLOCK_LIST_VALUE_DESC'),        
        'ASC' => GetMessage('SORT_ORDER_IBLOCK_LIST_VALUE_ASC'),    
    );
?>