<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
         
// Ќаходим список инфоблоков
if(!CModule::IncludeModule("iblock")) return;

// список сайтов 
// START
$arrSiteId = array(''=>GetMessage('SITE_ID_ALL'));
$rsSites = CSite::GetList($by="sort", $order="desc", Array());
while ($arS = $rsSites->Fetch())
{
    $arrSiteId[$arS['LID']] = '['.$arS['LID'].'] '.$arS['NAME'];  
}  
// END

  


// данные по пользовател€м
// START
$arUserGroup = array();
$rsGroups = CGroup::GetList ($by = "id", $order = "asc", Array ("ACTIVE" => 'Y'));
while($arrsGroups = $rsGroups->Fetch()) {
    $arUserGroup[$arrsGroups['ID']] =  '['.$arrsGroups['ID'].'] '.$arrsGroups['NAME'];      
}

$arUserPersonalGender = array(
    '' => GetMessage('USER_FILLTER_PERSONAL_GENDER_VALUE_ALL'),
    'M' => GetMessage('USER_FILLTER_PERSONAL_GENDER_VALUE_M'),
    'F' => GetMessage('USER_FILLTER_PERSONAL_GENDER_VALUE_F')    
);

// END
$arSelectYesNo = array(
    ''=> GetMessage('SELECT_PARAM_ALL'),
    'Y'=> GetMessage('SELECT_PARAM_Y'),
    'N'=> GetMessage('SELECT_PARAM_N')                
);

// данные по заказам
// START
    
if(CModule::IncludeModule("sale")) {
    // получим статусы
    //$arOrderStatus = array('' => GetMessage('SELECT_PARAM_ALL'));
    $arOrderStatus = array();
    $dbStatusList = CSaleStatus::GetList(array("SORT" => "ASC"),array(),false,false,array("ID", "NAME"));
    while($arStatusList = $dbStatusList->Fetch())
    {  
        $arOrderStatus[$arStatusList['ID']] = '['.$arStatusList['ID'].'] '.$arStatusList['NAME'];    
    }    


                
    // им€ свойств дл€ описани€ переменных
    // START 
    $Messsage_peremen['#PEREMEN_ORDER_PROP#'] = '';  
    $fillterProperPeremen  = array();
    if($arCurrentValues["ORDER_FILLTER_PERSON_TYPE_ID"]) {
        $fillterProperPeremen['PERSON_TYPE_ID'] = $arCurrentValues["ORDER_FILLTER_PERSON_TYPE_ID"];     
    }       
    $db_props = CSaleOrderProps::GetList(array("SORT" => "ASC"),$fillterProperPeremen,false,false,array());
    while($arr_props = $db_props->Fetch())
    {  
        
        if($arr_props['IS_LOCATION'] == 'Y') {
            $Messsage_peremen['#PEREMEN_ORDER_PROP#'] .=  '#ORDER_PROP_'.$arr_props["CODE"].'# - '.$arr_props["NAME"].'<br />';              
            $Messsage_peremen['#PEREMEN_ORDER_PROP#'] .=  '#ORDER_PROP_'.$arr_props["CODE"].'_COUNTRY# - '.$arr_props["NAME"].' - '.GetMessage('ORDER_PROPER_COUNTRY').'<br />'; 
            $Messsage_peremen['#PEREMEN_ORDER_PROP#'] .=  '#ORDER_PROP_'.$arr_props["CODE"].'_CITY# - '.$arr_props["NAME"].' - '.GetMessage('ORDER_PROPER_CITY').'<br />';           
        } else {
            $Messsage_peremen['#PEREMEN_ORDER_PROP#'] .=  '#ORDER_PROP_'.$arr_props["CODE"].'# - '.$arr_props["NAME"].'<br />';              
        }
        
    }   
    // END 
    
    
    
}
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


//подготовим текст по умолчанию
//START    
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/mail_template/parameters_get_info.php"); 
//END
  
//значени€  параметров шаблона
// TABS - код таба в меню
// TABS_NAME - им€ таба
// PARENT - код группы в табе 
// PARENT_NAME - им€ группы в табе
// NAME - им€ свойства
// TYPE -  тип  свойства   
// ------STRING - строка
// ------INT - числовое поле 
// ------LIST - список . ≈сли есть значение "MULTIPLE" => 'Y'  список будет множественный
// ------CHECKBOX - переключатель да/нет
// ------TEXT - вывод html редактора
// ------TEXTAREA - вывод текстового пол€
// ------TABS_INFO - таб с тектом, дл€ инструкции например
// ------PHP - вставка php кода  в логику рассылки
// ------DATE_PERIOD - календарь с датой
// ------USER_ID - выбор id пользовател€

  
$arTemplateParameters = array(); 
    
  // ‘ильтраци€ заказов
  // START
  // сайт
$arTemplateParameters["SITE_ID"] = array(    
    "PARENT" => "PARAM_ORDER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_ORDER_FILLTER"),
    "NAME" => GetMessage('SITE_ID_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arrSiteId,
    "SORT" => "10",
);  
    
  //статус   
$arTemplateParameters["ORDER_FILLTER_STATUS"] = array(    
    "PARENT" => "PARAM_ORDER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_ORDER_FILLTER"),
    "NAME" => GetMessage('ORDER_FILLTER_STATUS_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arOrderStatus,
    "SORT" => "40",
    "SIZE" => '4',
    "NOTES"  =>  GetMessage("ORDER_FILLTER_STATUS_NOTES"),
);
   
  //¬нешний вид списка товаров
  //START
$arTemplateParameters["FORGET_BASKET_IMG_WIDTH"] = array(
    "TABS" => 'TABS_FORGET_BASKE',
    "TABS_NAME" => GetMessage("TABS_FORGET_BASKET_NAME"),    
    "PARENT" => "TEMP_FORGET_BASKET",
    "PARENT_NAME" => GetMessage("GROUP_TEMP_FORGET_BASKET"),
    "NAME" => GetMessage("FORGET_BASKET_IMG_WIDTH_TITLE"),
    "TYPE" => "INT",
    "DEFAULT" => "100" ,
    "SORT" => "10", 
);    

$arTemplateParameters["FORGET_BASKET_IMG_HEIGHT"] = array(
    "TABS" => 'TABS_FORGET_BASKE',
    "TABS_NAME" => GetMessage("TABS_FORGET_BASKET_NAME"),     
    "PARENT" => "TEMP_FORGET_BASKET",
    "PARENT_NAME" => GetMessage("GROUP_TEMP_FORGET_BASKET"),
    "NAME" => GetMessage("FORGET_BASKET_IMG_HEIGHT_TITLE"),
    "TYPE" => "INT",
    "DEFAULT" => "200" ,
    "SORT" => "20",
);        
  
  
$arTemplateParameters["TEMP_FORGET_BASKET_TOP"] = array(
    "TABS" => 'TABS_FORGET_BASKE',
    "TABS_NAME" => GetMessage("TABS_FORGET_BASKET_NAME"),  
    "PARENT" => "TEMP_FORGET_BASKET",
    "PARENT_NAME" => GetMessage("GROUP_TEMP_FORGET_BASKET"),
    "NAME" => GetMessage("TEMP_FORGET_BASKET_TOP_TITLE"),
    "COLS" => 40,
    "HEIGHT" => 160,
    "TYPE" => "TEXT",
    "DEFAULT" => GetMessage("TEMP_FORGET_BASKET_TOP_DEFAULT"), 
    "NOTES"  =>  GetMessage("TEMP_FORGET_BASKET_TOP_NOTES"),
    "SORT" => "40",
);  
  
$arTemplateParameters["TEMP_FORGET_BASKET_LIST"] = array(
    "TABS" => 'TABS_FORGET_BASKE',
    "TABS_NAME" => GetMessage("TABS_FORGET_BASKET_NAME"),    
    "PARENT" => "TEMP_FORGET_BASKET",
    "PARENT_NAME" => GetMessage("GROUP_TEMP_FORGET_BASKET"),
    "NAME" => GetMessage("TEMP_FORGET_BASKET_LIST_TITLE"),
    "COLS" => 40,
    "HEIGHT" => 240,
    "TYPE" => "TEXT",
    "DEFAULT" => GetMessage("TEMP_FORGET_BASKET_LIST_DEFAULT"), 
    "NOTES"  =>  GetMessage("TEMP_FORGET_BASKET_LIST_NOTES"),
    "SORT" => "50",
);    
  
$arTemplateParameters["TEMP_FORGET_BASKET_BOTTOM"] = array(
    "TABS" => 'TABS_FORGET_BASKE',
    "TABS_NAME" => GetMessage("TABS_FORGET_BASKET_NAME"),    
    "PARENT" => "TEMP_FORGET_BASKET",
    "PARENT_NAME" => GetMessage("GROUP_TEMP_FORGET_BASKET"),
    "NAME" => GetMessage("TEMP_FORGET_BASKET_BOTTOM_TITLE"),
    "COLS" => 40,
    "HEIGHT" => 160,
    "TYPE" => "TEXT",
    "DEFAULT" => GetMessage("TEMP_FORGET_BASKET_BOTTOM_DEFAULT"), 
    "NOTES"  =>  GetMessage("TEMP_FORGET_BASKET_BOTTOM_NOTES"),
    "SORT" => "60",
);  
    
  // PHP модификаци€
$arTemplateParameters["PHP_BASKET_PRODUCT_FILLTER_BEFORE"] = array(
    "TABS" => 'TABS_FORGET_BASKE',
    "TABS_NAME" => GetMessage("TABS_FORGET_BASKET_NAME"),      
    "PARENT" => "PHP_MODIF_BASKET_PRODUCT",
    "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_BASKET_PRODUCT_NAME"),
    "NAME" => GetMessage("PHP_BASKET_PRODUCT_FILLTER_BEFORE_TITLE"),
    "HEIGHT" => 250,
    "WIDTH" => 1000,
    "TYPE" => "PHP",
    "SORT" => "70",
    "NOTES" => GetMessage("PHP_BASKET_PRODUCT_FILLTER_BEFORE_NOTES"),
);      
    
$arTemplateParameters["PHP_BASKET_PRODUCT_FILLTER_WHILE_AFTER"] = array(
    "TABS" => 'TABS_FORGET_BASKE',
    "TABS_NAME" => GetMessage("TABS_FORGET_BASKET_NAME"),      
    "PARENT" => "PHP_MODIF_BASKET_PRODUCT",
    "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_BASKET_PRODUCT_NAME"),
    "NAME" => GetMessage("PHP_BASKET_PRODUCT_FILLTER_WHILE_AFTER_TITLE"),
    "HEIGHT" => 250,
    "WIDTH" => 1000,
    "TYPE" => "PHP",
    "SORT" => "90",
    "NOTES" => GetMessage("PHP_BASKET_PRODUCT_FILLTER_WHILE_AFTER_NOTES"),
);  
  //END   
   
   
   
// –екоммендуемые товары
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/recommend/parameters_array.php"); 
//END
     

//ѕросмотренные товары
//START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/viewed/parameters_array.php"); 
//END
     

//купоны на скидку
//START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/discounts/parameters_array.php"); 
//END
  
 
//Ўаблон письма
//START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/mail_template/parameters_array.php");  
//END


  
// PHP модификаци€ 
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
    $arTemplateParameters["PHP_FILLTER_ORDER_PARAM_BEFORE"] = array(
        "PARENT" => "PHP_MODIF_MAILING",                                      
        "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_MAILING_NAME"),
        "NAME" => GetMessage("PHP_FILLTER_ORDER_PARAM_BEFORE_TITLE"),
        "HEIGHT" => 200,
        "WIDTH" => 1000,
        "TYPE" => "PHP",
        "SORT" => "40",
        "NOTES" => GetMessage("PHP_FILLTER_ORDER_PARAM_BEFORE_NOTES"),
    );
    $arTemplateParameters["PHP_FILLTER_ORDER_PARAM_AFTER"] = array(
        "PARENT" => "PHP_MODIF_MAILING",                                      
        "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_MAILING_NAME"),
        "NAME" => GetMessage("PHP_FILLTER_ORDER_PARAM_AFTER_TITLE"),
        "HEIGHT" => 200,
        "WIDTH" => 1000,
        "TYPE" => "PHP",
        "SORT" => "50",
        "NOTES" => GetMessage("PHP_FILLTER_ORDER_PARAM_AFTER_NOTES"),
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
