<?
    // получим типы инфоблоков
    $arIBlockType = CIBlockParameters::GetIBlockTypes();
    if(empty($arCurrentValues["IBLOCK_TYPE_RECOMMEND"])){
        foreach($arIBlockType as $key => $value) {
           $arCurrentValues["IBLOCK_TYPE_RECOMMEND"] = $key;
           break;
        } 
    }    
    // получим инфоблок
    $arIBlockRecommend = array('' => GetMessage('SELECT_CHANGE'));
    if($arCurrentValues["IBLOCK_TYPE_RECOMMEND"]) {
        

        $rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE_RECOMMEND"], "ACTIVE"=>"Y"));
        while($arr=$rsIBlock->Fetch())
        {
            $arIBlockRecommend[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
        }    
        
    }

    
    //свойство фильтрации 
    $arPropertyListRecommend = array('' => GetMessage('SELECT_CHANGE'));
    $textPeremenRecommend = '';  
    if($arCurrentValues["IBLOCK_ID_RECOMMEND"]) { 

        $rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID_RECOMMEND"]));
        while($arr = $rsProp->Fetch())
        {
            $textPeremenRecommend .=  '#PROP_'.$arr["CODE"].'# - '.$arr["NAME"].'<br />'; 
            if($arr["PROPERTY_TYPE"] == "L") {   
                $arPropertyListRecommend[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
            }
        }
        
    } 
    //значения свойства фильтрации
    $arRecommend_fillter_1_list = array('' => GetMessage('SELECT_CHANGE'));

    if($arCurrentValues["IBLOCK_ID_RECOMMEND"] && $arCurrentValues["PROPERTY_FILLTER_1_RECOMMEND"]) { 

        
        $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID_RECOMMEND"], "CODE"=>$arCurrentValues["PROPERTY_FILLTER_1_RECOMMEND"]));
        while($enum_fields = $property_enums->GetNext())
        {      
            $arRecommend_fillter_1_list[$enum_fields["ID"]] = "[".$enum_fields["XML_ID"]."] ".$enum_fields["VALUE"];   
        }    
            
    }    
    
    //получим список разделов
    if($arCurrentValues["IBLOCK_ID_RECOMMEND"]){
        $arIBlockSectionRecommend = array();
        $dbSection = CIBlockSection::GetTreeList(Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID_RECOMMEND"]), array("ID", "NAME", "DEPTH_LEVEL"));  
        while($resSection = $dbSection->GetNext()){
            $arIBlockSectionRecommend[$resSection['ID']] = str_repeat(" . ", $resSection["DEPTH_LEVEL"]).$resSection["NAME"];  
            
        }     
    }

    //получим типы цен
    $arPriceRecommend = array();
    if(CModule::IncludeModule("catalog")) {
        $dbPrice = CCatalogGroup::GetList();
        while($arPrice = $dbPrice->Fetch()) {
            $arPriceRecommend[$arPrice["NAME"]] = "[".$arPrice["ID"]."] ".$arPrice["NAME"];
        } 
    }                                                                                 

    
 
    $sort_by_iblock_list = array(
        'SORT' => GetMessage('SORT_BY_IBLOCK_LIST_VALUE_SORT'),
        'ID' => GetMessage('SORT_BY_IBLOCK_LIST_VALUE_ID'),   
        'NAME' => GetMessage('SORT_BY_IBLOCK_LIST_VALUE_NAME'),
        'CREATED' => GetMessage('SORT_BY_IBLOCK_LIST_VALUE_CREATED'),    
        'SHOWS' => GetMessage('SORT_BY_IBLOCK_LIST_VALUE_SHOWS'),  
        'RAND' => GetMessage('SORT_BY_IBLOCK_LIST_VALUE_RAND'),                                    
    );  
    $sort_order_iblock_list = array(
        'ASC' => GetMessage('SORT_ORDER_IBLOCK_LIST_VALUE_ASC'),
        'DESC' => GetMessage('SORT_ORDER_IBLOCK_LIST_VALUE_DESC'),        
    ); 
?>