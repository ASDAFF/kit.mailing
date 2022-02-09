<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Находим список инфоблоков
if(!CModule::IncludeModule("iblock")) return;

// данные по инфоблоку
// START
    // выберем тип инфоблока
    $arIBlockTypeInfo = CIBlockParameters::GetIBlockTypes();
    if(empty($arCurrentValues["IBLOCK_TYPE_INFO"])){
        foreach($arIBlockTypeInfo as $key => $value) {
           $arCurrentValues["IBLOCK_TYPE_INFO"] = $key;
           break;
        } 
    }
    // выберем инфоблок
    $arIBlockInfo = array('' => GetMessage('SELECT_CHANGE'));
    if($arCurrentValues["IBLOCK_TYPE_INFO"]) {
        $rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE_INFO"], "ACTIVE"=>"Y"));
        while($arr=$rsIBlock->Fetch())
        {
            $arIBlockInfo[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
        }    
        
    }

    //выберем свойства фильтрафии
    $arIBlockInfoPropertyList = array('' => GetMessage('SELECT_CHANGE'));
    $arIBlockInfoPropertyString = array('' => GetMessage('SELECT_CHANGE'));
    $arIBlockInfoPropertyDate = array('' => GetMessage('SELECT_CHANGE'));    
    $Messsage_peremen['#IBLOCK_PROP#'] = '';
    if($arCurrentValues["IBLOCK_ID_INFO"]) { 
        $rsProp = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID_INFO"]));
        while($arr = $rsProp->Fetch())
        {
            $Messsage_peremen['#IBLOCK_PROP#'] .=  '#PROP_'.$arr["CODE"].'# - '.$arr["NAME"].'<br />'; 
            if($arr["PROPERTY_TYPE"] == "L") {   
                $arIBlockInfoPropertyList[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
            }
            if($arr["PROPERTY_TYPE"] == "S" && $arr["USER_TYPE"]!='DateTime') {   
                $arIBlockInfoPropertyString[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
            }
            if($arr["PROPERTY_TYPE"] == "S" && $arr["USER_TYPE"]=='DateTime') {   
                $arIBlockInfoPropertyDate[$arr["CODE"]] = "[".$arr["CODE"]."] ".$arr["NAME"];
            }             
        } 
    } 
    
    //значения свойства фильтрации
    $arIBlockInfoPropertyListValue = array('' => GetMessage('SELECT_CHANGE'));
    if($arCurrentValues["IBLOCK_ID_INFO"] && $arCurrentValues["IBLOCK_INFO_PROPERTY_FILLTER_LIST"]) { 
        $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID_INFO"], "CODE"=>$arCurrentValues["IBLOCK_INFO_PROPERTY_FILLTER_LIST"]));
        while($enum_fields = $property_enums->GetNext())
        {      
            $arIBlockInfoPropertyListValue[$enum_fields["ID"]] = "[".$enum_fields["XML_ID"]."] ".$enum_fields["VALUE"];   
        }            
    } 
    
    
    //значения свойства после отправки сообщения
    $arIBlockInfoPropertyListFinishValue = array('' => GetMessage('SELECT_CHANGE'));
    if($arCurrentValues["IBLOCK_ID_INFO"] && $arCurrentValues["IBLOCK_INFO_PROPERTY_FINISH_LIST"]) { 
        $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID_INFO"], "CODE"=>$arCurrentValues["IBLOCK_INFO_PROPERTY_FINISH_LIST"]));
        while($enum_fields = $property_enums->GetNext())
        {      
            $arIBlockInfoPropertyListFinishValue[$enum_fields["ID"]] = "[".$enum_fields["XML_ID"]."] ".$enum_fields["VALUE"];   
        }            
    }         
       
        
   
        
        
// END


// рекомендованные товары
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/recommend/parameters_get_info.php"); 
// END


// получим скидки
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/discounts/parameters_get_info.php"); 
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
	
  // Выберем инфоблока для рассылки       
$arTemplateParameters["IBLOCK_TYPE_INFO"] = array(    
    "PARENT" => "PARAM_IBLOCK",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_NAME"),
    "NAME" => GetMessage('IBLOCK_TYPE_INFO_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arIBlockTypeInfo,
    "REFRESH" => "Y",
    "SORT" => "10",
);
$arTemplateParameters["IBLOCK_ID_INFO"] = array(
    "PARENT" => "PARAM_IBLOCK",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_NAME"),
    "NAME" => GetMessage('IBLOCK_ID_INFO_TITLE'),
    "TYPE" => "LIST",
    "ADDITIONAL_VALUES" => "Y",
    "VALUES" => $arIBlockInfo,
    "REFRESH" => "Y",
    "SORT" => "30",
); 

    
//фильтр по свойству список
$arTemplateParameters["IBLOCK_INFO_PROPERTY_FILLTER_LIST"] = array(
    "PARENT" => "PARAM_IBLOCK_FILLTER_LIST",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_FILLTER_LIST"),
    "NAME" => GetMessage('IBLOCK_INFO_PROPERTY_FILLTER_LIST_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arIBlockInfoPropertyList,
    "REFRESH" => "Y",
    "SORT" => "40",
); 
if($arCurrentValues["IBLOCK_INFO_PROPERTY_FILLTER_LIST"]){ 
    $arTemplateParameters["IBLOCK_INFO_PROPERTY_FILLTER_LIST_VALUE"] = array(
        "PARENT" => "PARAM_IBLOCK_FILLTER_LIST",
        "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_FILLTER_LIST"),
        "NAME" => GetMessage('IBLOCK_INFO_PROPERTY_FILLTER_LIST_VALUE_TITLE'),
        "TYPE" => "LIST",
        "VALUES" => $arIBlockInfoPropertyListValue,
        "REFRESH" => "N",
        "SORT" => "50",
        "NOTES" => GetMessage('IBLOCK_INFO_PROPERTY_FILLTER_LIST_VALUE_NOTES')
    );     
} else {
    $arTemplateParameters["IBLOCK_INFO_PROPERTY_FILLTER_LIST"]["NOTES"] = GetMessage('IBLOCK_INFO_PROPERTY_FILLTER_LIST_VALUE_NOTES');   
}  
  
            
//фильтр по свойству строка   
$arTemplateParameters["IBLOCK_INFO_PROPERTY_FILLTER_STRING"] = array(
    "PARENT" => "PARAM_IBLOCK_FILLTER_STRING",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_FILLTER_STRING"),
    "NAME" => GetMessage('IBLOCK_INFO_PROPERTY_FILLTER_STRING_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arIBlockInfoPropertyString,
    "REFRESH" => "Y",
    "SORT" => "60",
); 
if($arCurrentValues["IBLOCK_INFO_PROPERTY_FILLTER_STRING"]){ 
    $arTemplateParameters["IBLOCK_INFO_PROPERTY_FILLTER_STRING_VALUE"] = array(
        "PARENT" => "PARAM_IBLOCK_FILLTER_STRING",
        "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_FILLTER_STRING"),
        "NAME" => GetMessage('IBLOCK_INFO_PROPERTY_FILLTER_STRING_VALUE_TITLE'),
        "TYPE" => "STRING",
        "SORT" => "70",
        "NOTES" => GetMessage('IBLOCK_INFO_PROPERTY_FILLTER_STRING_VALUE_NOTES')
    );    
} else {
    $arTemplateParameters["IBLOCK_INFO_PROPERTY_FILLTER_STRING"]["NOTES"] = GetMessage('IBLOCK_INFO_PROPERTY_FILLTER_STRING_VALUE_NOTES');      
}          
      
//фильтр по свойству дата      
$arTemplateParameters["IBLOCK_INFO_PROPERTY_FILLTER_DATE"] = array(
    "PARENT" => "PARAM_IBLOCK_FILLTER_DATE",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_FILLTER_DATE"),
    "NAME" => GetMessage('IBLOCK_INFO_PROPERTY_FILLTER_DATE_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arIBlockInfoPropertyDate,
    "REFRESH" => "Y",
    "SORT" => "60",
); 
if($arCurrentValues["IBLOCK_INFO_PROPERTY_FILLTER_DATE"]){ 
    $arTemplateParameters["IBLOCK_INFO_PROPERTY_FILLTER_DATE_VALUE"] = array(
        "PARENT" => "PARAM_IBLOCK_FILLTER_DATE",
        "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_FILLTER_DATE"),
        "NAME" => GetMessage('IBLOCK_INFO_PROPERTY_FILLTER_DATE_VALUE_TITLE'),
        "TYPE" => "DATE_PERIOD_AGO",
        "SORT" => "70",
        "NOTES" => GetMessage('IBLOCK_INFO_PROPERTY_FILLTER_DATE_VALUE_NOTES')
    );           
} else {
    $arTemplateParameters["IBLOCK_INFO_PROPERTY_FILLTER_DATE"]["NOTES"] = GetMessage('IBLOCK_INFO_PROPERTY_FILLTER_DATE_VALUE_NOTES');      
}        
      
// поставить метку после работы      
$arTemplateParameters["IBLOCK_INFO_PROPERTY_FINISH_LIST"] = array(
    "PARENT" => "PARAM_IBLOCK_FINISH_LIST",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_FINISH_LIST"),
    "NAME" => GetMessage('IBLOCK_INFO_PROPERTY_FINISH_LIST_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arIBlockInfoPropertyList,
    "REFRESH" => "Y",
    "SORT" => "40",
); 

if($arCurrentValues["IBLOCK_INFO_PROPERTY_FINISH_LIST"]){ 
    $arTemplateParameters["IBLOCK_INFO_PROPERTY_FINISH_LIST_VALUE"] = array(
        "PARENT" => "PARAM_IBLOCK_FINISH_LIST",
        "PARENT_NAME" => GetMessage("GROUP_PARAM_IBLOCK_FINISH_LIST"),
        "NAME" => GetMessage('IBLOCK_INFO_PROPERTY_FINISH_LIST_VALUE_TITLE'),
        "TYPE" => "LIST",
        "VALUES" => $arIBlockInfoPropertyListFinishValue,
        "REFRESH" => "N",
        "SORT" => "50",
        "NOTES" => GetMessage('IBLOCK_INFO_PROPERTY_FINISH_LIST_VALUE_NOTES')
    );      
} else {
    $arTemplateParameters["IBLOCK_INFO_PROPERTY_FINISH_LIST"]["NOTES"] =  GetMessage('IBLOCK_INFO_PROPERTY_FINISH_LIST_VALUE_NOTES');      
}   
 
   
//исключение из рассылки
$arTemplateParameters["EMAIL_DUBLICATE"] = array(
    "PARENT" => "PARAM_EXCEPTIONS",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_EXCEPTIONS"),
    "NAME" => GetMessage("EMAIL_DUBLICATE_TITLE"),
    "TYPE" => "CHECKBOX",
    "DEFAULT" => "" ,
    "SORT" => "10",
    "NOTES" => GetMessage("EMAIL_DUBLICATE_NOTES"),
); 


   
   
// Рекоммендуемые товары
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/recommend/parameters_array.php"); 
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
  
    $arTemplateParameters["PHP_FILLTER_IBLOCK_PARAM_BEFORE"] = array(
        "PARENT" => "PHP_MODIF_MAILING",                                      
        "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_MAILING_NAME"),
        "NAME" => GetMessage("PHP_FILLTER_IBLOCK_PARAM_BEFORE_TITLE"),
        "HEIGHT" => 200,
        "WIDTH" => 1000,
        "TYPE" => "PHP",
        "SORT" => "40",
        "NOTES" => GetMessage("PHP_FILLTER_IBLOCK_PARAM_BEFORE_NOTES"),
    );
    $arTemplateParameters["PHP_FILLTER_IBLOCK_PARAM_AFTER"] = array(
        "PARENT" => "PHP_MODIF_MAILING",                                      
        "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_MAILING_NAME"),
        "NAME" => GetMessage("PHP_FILLTER_IBLOCK_PARAM_AFTER_TITLE"),
        "HEIGHT" => 200,
        "WIDTH" => 1000,
        "TYPE" => "PHP",
        "SORT" => "50",
        "NOTES" => GetMessage("PHP_FILLTER_IBLOCK_PARAM_AFTER_NOTES"),
    );    
         
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/mail_template/parameters_php_array.php"); 
         
}    
  // END    
  
  
$arTemplateParameters["TABS_SOTBIT_MAILING_INSTRUCTION"] = array(
    "TABS" => 'TABS_SOTBIT_MAILING_INSTRUCTION',
    "TABS_NAME" => GetMessage("TABS_SOTBIT_MAILING_INSTRUCTION_TITLE"),   
    "PARENT" => "PARAM_TABS_SOTBIT_MAILING_INSTRUCTION",
    "PARENT_NAME" => GetMessage("GROUP_TABS_SOTBIT_MAILING_INSTRUCTION_NAME"),    
    "TYPE" => "TABS_INFO",
    "DEFAULT" => GetMessage("TABS_SOTBIT_MAILING_INSTRUCTION_DEFAULT"),    
);     
  
  



?>
