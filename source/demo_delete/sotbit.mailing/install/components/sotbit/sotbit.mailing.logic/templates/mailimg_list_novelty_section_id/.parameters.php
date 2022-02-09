<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Находим список инфоблоков
if(!CModule::IncludeModule("iblock")) return;




// новинки товаров 
// START
    $arIBlockType = CIBlockParameters::GetIBlockTypes();
    if(empty($arCurrentValues["IBLOCK_TYPE_NOVELTY_GOODS"])){
        foreach($arIBlockType as $key => $value) {
           $arCurrentValues["IBLOCK_TYPE_NOVELTY_GOODS"] = $key;
           break;
        } 
    }    
    // получим инфоблок
    $arIBlockNoveltyGoods = array('' => GetMessage('SELECT_CHANGE'));
    if($arCurrentValues["IBLOCK_TYPE_NOVELTY_GOODS"]) {
        

        $rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE_NOVELTY_GOODS"], "ACTIVE"=>"Y"));
        while($arr=$rsIBlock->Fetch())
        {
            $arIBlockNoveltyGoods[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
        }    
        
    }
    //свойство фильтрации 
    $arPropertyListNoveltyGoods = array('' => GetMessage('SELECT_CHANGE'));
    $textPeremenNoveltyGoods = '';  
    if($arCurrentValues["IBLOCK_ID_NOVELTY_GOODS"]) { 

        $rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID_NOVELTY_GOODS"]));
        while($arr = $rsProp->Fetch())
        {
            $textPeremenNoveltyGoods .=  '#PROP_'.$arr["CODE"].'# - '.$arr["NAME"].'<br />'; 
            if($arr["PROPERTY_TYPE"] == "L") {   
                $arPropertyListNoveltyGoods[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
            }
        }
        
    } 
    //значения свойства фильтрации
    $arNoveltyGoods_fillter_1_list = array('' => GetMessage('SELECT_CHANGE'));

    if($arCurrentValues["IBLOCK_ID_NOVELTY_GOODS"] && $arCurrentValues["PROPERTY_FILLTER_1_NOVELTY_GOODS"]) { 

        
        $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID_NOVELTY_GOODS"], "CODE"=>$arCurrentValues["PROPERTY_FILLTER_1_NOVELTY_GOODS"]));
        while($enum_fields = $property_enums->GetNext())
        {      
            $arNoveltyGoods_fillter_1_list[$enum_fields["ID"]] = "[".$enum_fields["XML_ID"]."] ".$enum_fields["VALUE"];   
        }    
            
    }    

    //получим типы цен
    $arPriceNoveltyGoods = array();
    if(CModule::IncludeModule("catalog")) {
        $dbPrice = CCatalogGroup::GetList();
        while($arPrice = $dbPrice->Fetch()) {
            $arPriceNoveltyGoods[$arPrice["NAME"]] = "[".$arPrice["ID"]."] ".$arPrice["NAME"];
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
// END





// рекомендованные товары
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/recommend/parameters_get_info.php"); 
// END


// просмотренные товары
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/viewed/parameters_get_info.php");                
// END


// получим скидки
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/discounts/parameters_get_info.php"); 
// END


// получим категории подписок
// START    
    $categoriesLi = CSotbitMailingHelp::GetCategoriesInfo();
    $section_id = array();
    foreach($categoriesLi as $v){
        if($v['PARAM_1']=='SECTION_ID') { 
            $section_id[] = $v['PARAM_2'];            
        }    
    }
    // получим SECTION_ID
    $section_id_info = array();
    $rsSect = CIBlockSection::GetList(array('left_margin' => 'asc'),array('ID'=>$section_id),false,array('ID','NAME'));
    while($arSect = $rsSect->GetNext())
    {
        $section_id_info[$arSect['ID']] = $arSect;
    }
    // соберем список категорий
    $arrCategoriesList = array();  
    foreach($categoriesLi as $v) { 
        if($v['PARAM_1']=='SECTION_ID') {
            $arrCategoriesList[$v['ID']] = '['.$v['ID'].'] '.$v['NAME'].' ('.$section_id_info[$v['PARAM_2']]['NAME'].')';          
        }
              
    }
// END   
  
  
//подготовим текст по умолчанию
//START    
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/mail_template/parameters_get_info.php"); 
//END

  
//значения  параметров шаблона
// TABS - код таба в меню
// TABS_NAME - имя таба
// PARENT - код группы в табе 
// PARENT_NAME - имя группы в табе
// NAME - имя свойства
// TYPE -  тип  свойства   
// ------STRING - строка
// ------INT - числовое поле 
// ------CHECKBOX - переключатель да/нет
// ------TEXT - вывод html редактора
// ------TEXTAREA - вывод текстового поля
// ------TABS_INFO - таб с тектом, для инструкции например
// ------PHP - вставка php кода  в логику рассылки
// ------DATE_PERIOD - календарь с датой
// ------USER_ID - выбор id пользователя
  
$arTemplateParameters = array(); 	
 
    
$arTemplateParameters["CATEGORIES_ID"] = array(
    "PARENT" => "PARAM_USER_FILLTER",                                        
    "PARENT_NAME" => GetMessage("GROUP_PARAM_USER_FILLTER"),
    "NAME" => GetMessage('CATEGORIES_ID_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arrCategoriesList,
    "REFRESH" => "Y",
    "SORT" => "40",
    "MULTIPLE" => "Y",
    "NOTES" => GetMessage('CATEGORIES_ID_NOTES'),    
); 
      
      
  //Вненший вид списка новинок
  //START
$arTemplateParameters["NOVELTY_GOODS_DATE_CREATE"] =  array(    
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),      
    "PARENT" => "GROUP_NOVELTY_GOODS_FILLTER_TIME",
    "PARENT_NAME" => GetMessage("GROUP_NOVELTY_GOODS_FILLTER_TIME_NAME"),
    "NAME" => GetMessage("NOVELTY_GOODS_DATE_CREATE_TITLE"),
    "TYPE" => "DATE_PERIOD_AGO",
    "SORT" => "82",
);    
   
  
  
$arTemplateParameters["IBLOCK_TYPE_NOVELTY_GOODS"] =  array(   
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),     
    "PARENT" => "PARAM_IBLOCK_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_NOVELTY_GOODS_FILLTER_NAME"),
    "NAME" => GetMessage('IBLOCK_TYPE_NOVELTY_GOODS_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arIBlockType,
    "REFRESH" => "Y",
    "SORT" => "10",
);
$arTemplateParameters["IBLOCK_ID_NOVELTY_GOODS"] =  array(
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),  
    "PARENT" => "PARAM_IBLOCK_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_NOVELTY_GOODS_FILLTER_NAME"),
    "NAME" => GetMessage('IBLOCK_ID_NOVELTY_GOODS_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arIBlockNoveltyGoods,
    "REFRESH" => "Y",
    "SORT" => "20",
); 
$arTemplateParameters["PROPERTY_FILLTER_1_NOVELTY_GOODS"] =  array(
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),    
    "PARENT" => "PARAM_IBLOCK_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_NOVELTY_GOODS_FILLTER_NAME"),
    "NAME" => GetMessage('PROPERTY_FILLTER_1_NOVELTY_GOODS_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arPropertyListNoveltyGoods,
    "REFRESH" => "Y",
    "SORT" => "40",
); 
$arTemplateParameters["PROPERTY_FILLTER_1_VALUE_NOVELTY_GOODS"] =  array(
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),   
    "PARENT" => "PARAM_IBLOCK_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_NOVELTY_GOODS_NAME"),
    "NAME" => GetMessage('PROPERTY_FILLTER_1_VALUE_NOVELTY_GOODS_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arNoveltyGoods_fillter_1_list,
    "REFRESH" => "N",
    "SORT" => "50",
    "NOTES" => GetMessage('PROPERTY_FILLTER_1_VALUE_NOVELTY_GOODS_NOTES'),
);   
$arTemplateParameters["TOP_COUNT_FILLTER_NOVELTY_GOODS_FROM"] =  array(
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),   
    "PARENT" => "PARAM_IBLOCK_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_NOVELTY_GOODS_NAME"),
    "NAME" => GetMessage('TOP_COUNT_FILLTER_NOVELTY_GOODS_FROM_TITLE'),
    "TYPE" => "INT",
    "SORT" => "50",
    "DEFAULT" => "3"
); 
$arTemplateParameters["TOP_COUNT_FILLTER_NOVELTY_GOODS_TO"] =  array(
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),   
    "PARENT" => "PARAM_IBLOCK_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_NOVELTY_GOODS_NAME"),
    "NAME" => GetMessage('TOP_COUNT_FILLTER_NOVELTY_GOODS_TO_TITLE'),
    "TYPE" => "INT",
    "SORT" => "60",
    "DEFAULT" => "10",
    "NOTES" => GetMessage('TOP_COUNT_FILLTER_NOVELTY_GOODS_TO_NOTES'),    
);    
      
     
         
$arTemplateParameters["SORT_BY_NOVELTY_GOODS"] =  array(
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),  
    "PARENT" => "PARAM_SORT_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_NOVELTY_GOODS_SORT_NAME"),
    "NAME" => GetMessage('SORT_BY_NOVELTY_GOODS_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $sort_by_iblock_list,
    "REFRESH" => "N",
    "SORT" => "20",
);      
$arTemplateParameters["SORT_ORDER_NOVELTY_GOODS"] =  array(
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),   
    "PARENT" => "PARAM_SORT_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_NOVELTY_GOODS_SORT_NAME"),
    "NAME" => GetMessage('SORT_ORDER_NOVELTY_GOODS_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $sort_order_iblock_list,
    "REFRESH" => "N",
    "SORT" => "40",
);  
  //тип цены 
$arTemplateParameters["PRICE_TYPE_NOVELTY_GOODS"] =  array(
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),   
    "PARENT" => "PARAM_PRICE_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_NOVELTY_GOODS_PRICE_NAME"),
    "NAME" => GetMessage('PRICE_TYPE_NOVELTY_GOODS_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arPriceNoveltyGoods,
    "REFRESH" => "N",
    "SORT" => "40",
);     
  
  
  
$arTemplateParameters["NOVELTY_GOODS_IMG_WIDTH"] =  array(
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),    
    "PARENT" => "TEMP_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_TEMP_NOVELTY_GOODS"),
    "NAME" => GetMessage("NOVELTY_GOODS_IMG_WIDTH_TITLE"),
    "TYPE" => "INT",
    "DEFAULT" => "100" ,
    "SORT" => "10",
);    
$arTemplateParameters["NOVELTY_GOODS_IMG_HEIGHT"] =  array(
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),     
    "PARENT" => "TEMP_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_TEMP_NOVELTY_GOODS"),
    "NAME" => GetMessage("NOVELTY_GOODS_IMG_HEIGHT_TITLE"),
    "TYPE" => "INT",
    "DEFAULT" => "200" ,
    "SORT" => "20",
);        
$arTemplateParameters["TEMP_NOVELTY_GOODS_TOP"] =  array(
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),  
    "PARENT" => "TEMP_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_TEMP_NOVELTY_GOODS"),
    "NAME" => GetMessage("TEMP_NOVELTY_GOODS_TOP_TITLE"),
    "COLS" => 40,
    "HEIGHT" => 160,
    "TYPE" => "TEXT",
    "DEFAULT" => GetMessage("TEMP_NOVELTY_GOODS_TOP_DEFAULT"), 
    "NOTES"  =>  GetMessage("TEMP_NOVELTY_GOODS_TOP_NOTES"),
    "SORT" => "40",
);  
$arTemplateParameters["TEMP_NOVELTY_GOODS_LIST"] =  array(
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),    
    "PARENT" => "TEMP_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_TEMP_NOVELTY_GOODS"),
    "NAME" => GetMessage("TEMP_NOVELTY_GOODS_LIST_TITLE"),
    "COLS" => 40,
    "HEIGHT" => 240,
    "TYPE" => "TEXT",
    "DEFAULT" => GetMessage("TEMP_NOVELTY_GOODS_LIST_DEFAULT"), 
    "NOTES"  =>  GetMessage("TEMP_NOVELTY_GOODS_LIST_NOTES", array('#IBLOCK_PROP#'=> $textPeremenNoveltyGoods)),
    "SORT" => "50",
);    
$arTemplateParameters["TEMP_NOVELTY_GOODS_BOTTOM"] =  array(
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),    
    "PARENT" => "TEMP_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_TEMP_NOVELTY_GOODS"),
    "NAME" => GetMessage("TEMP_NOVELTY_GOODS_BOTTOM_TITLE"),
    "COLS" => 40,
    "HEIGHT" => 160,
    "TYPE" => "TEXT",
    "DEFAULT" => GetMessage("TEMP_NOVELTY_GOODS_BOTTOM_DEFAULT"), 
    "NOTES"  =>  GetMessage("TEMP_NOVELTY_GOODS_BOTTOM_NOTES"),
    "SORT" => "60",
);  
  
  // PHP модификация
$arTemplateParameters["PHP_NOVELTY_GOODS_FILLTER_BEFORE"] =  array(
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),      
    "PARENT" => "PHP_MODIF_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_NOVELTY_GOODS_NAME"),
    "NAME" => GetMessage("PHP_NOVELTY_GOODS_FILLTER_BEFORE_TITLE"),
    "HEIGHT" => 250,
    "WIDTH" => 1000,
    "TYPE" => "PHP",
    "SORT" => "70",
    "NOTES" => GetMessage("PHP_NOVELTY_GOODS_FILLTER_BEFORE_NOTES"),
);       
$arTemplateParameters["PHP_NOVELTY_GOODS_FILLTER_WHILE_AFTER"] =  array(
    "TABS" => 'TABS_NOVELTY_GOODS',
    "TABS_NAME" => GetMessage("TABS_NOVELTY_GOODS_NAME"),      
    "PARENT" => "PHP_MODIF_NOVELTY_GOODS",
    "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_NOVELTY_GOODS_NAME"),
    "NAME" => GetMessage("PHP_NOVELTY_GOODS_FILLTER_WHILE_AFTER_TITLE"),
    "HEIGHT" => 250,
    "WIDTH" => 1000,
    "TYPE" => "PHP",
    "SORT" => "90",
    "NOTES" => GetMessage("PHP_NOVELTY_GOODS_FILLTER_WHILE_AFTER_NOTES"),
);  
  //END      
      
      
   
     
// Рекоммендуемые товары
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/recommend/parameters_array.php"); 
//END
     

//Просмотренные товары
//START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/viewed/parameters_array.php"); 
//END
     

//купоны на скидку
//START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/discounts/parameters_array.php"); 
//END
         
 
        
//Шаблон письма
//START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/mail_template/parameters_array.php");  
//END


  
// PHP модификация 
// START 
$arTemplateParameters["INCLUDE_PHP_MODIF_MAILING_TITLE"] =  array(   
    "PARENT" => "PHP_MODIF_MAILING",                                      
    "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_MAILING_NAME"),
    "NAME" => GetMessage("INCLUDE_PHP_MODIF_MAILING_TITLE"),
    "TYPE" => "CHECKBOX",
    "DEFAULT" => "N",  
    "SORT" => "10",
    "REFRESH" => "Y",
);  

if($arCurrentValues["INCLUDE_PHP_MODIF_MAILING_TITLE"]=='Y'){
    $arTemplateParameters["PHP_FILLTER_USER_PARAM_BEFORE"] =  array(
        "PARENT" => "PHP_MODIF_MAILING",                                      
        "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_MAILING_NAME"),
        "NAME" => GetMessage("PHP_FILLTER_USER_PARAM_BEFORE_TITLE"),
        "HEIGHT" => 200,
        "WIDTH" => 1000,
        "TYPE" => "PHP",
        "SORT" => "40",
        "NOTES" => GetMessage("PHP_FILLTER_USER_PARAM_BEFORE_NOTES"),
    );   
    $arTemplateParameters["PHP_FILLTER_USER_PARAM_AFTER"] =  array(
        "PARENT" => "PHP_MODIF_MAILING",                                      
        "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_MAILING_NAME"),
        "NAME" => GetMessage("PHP_FILLTER_USER_PARAM_AFTER_TITLE"),
        "HEIGHT" => 200,
        "WIDTH" => 1000,
        "TYPE" => "PHP",
        "SORT" => "50",
        "NOTES" => GetMessage("PHP_FILLTER_USER_PARAM_AFTER_NOTES"),
    );         
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/mail_template/parameters_php_array.php"); 
         
}   
  // END    
  
  
$arTemplateParameters["TABS_SOTBIT_MAILING_INSTRUCTION"] =  array(
    "TABS" => 'TABS_SOTBIT_MAILING_INSTRUCTION',
    "TABS_NAME" => GetMessage("TABS_SOTBIT_MAILING_INSTRUCTION_TITLE"),   
    "PARENT" => "PARAM_TABS_SOTBIT_MAILING_INSTRUCTION",
    "PARENT_NAME" => GetMessage("GROUP_TABS_SOTBIT_MAILING_INSTRUCTION_NAME"),    
    "TYPE" => "TABS_INFO",
    "DEFAULT" => GetMessage("TABS_SOTBIT_MAILING_INSTRUCTION_DEFAULT"),    
);     
  
  



?>
