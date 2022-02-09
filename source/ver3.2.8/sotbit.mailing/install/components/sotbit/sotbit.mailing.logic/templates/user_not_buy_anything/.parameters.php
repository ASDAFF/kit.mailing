<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Находим список инфоблоков
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
	
  // Фильтрация пользователей  
$arTemplateParameters["SITE_ID"] = array(    
    "PARENT" => "PARAM_USER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_USER_FILLTER"),
    "NAME" => GetMessage('SITE_ID_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arrSiteId,
    "SORT" => "1",
);      
    
  // Параметры выбора  
$arTemplateParameters["DATE_REGISTER"] = array(    
    "PARENT" => "PARAM_USER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_USER_FILLTER"),
    "NAME" => GetMessage("DATE_REGISTER_TITLE"),
    "TYPE" => "DATE_PERIOD_AGO",
    "SORT" => "22",
);    

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
    $arTemplateParameters["PHP_FILLTER_USER_PARAM_BEFORE"] = array(
        "PARENT" => "PHP_MODIF_MAILING",                                      
        "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_MAILING_NAME"),
        "NAME" => GetMessage("PHP_FILLTER_USER_PARAM_BEFORE_TITLE"),
        "HEIGHT" => 200,
        "WIDTH" => 1000,
        "TYPE" => "PHP",
        "SORT" => "40",
        "NOTES" => GetMessage("PHP_FILLTER_USER_PARAM_BEFORE_NOTES"),
    );
    $arTemplateParameters["PHP_FILLTER_USER_PARAM_AFTER"] = array(
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
  
  
  
  
$arTemplateParameters["TABS_SOTBIT_MAILING_INSTRUCTION"] = array(
    "TABS" => 'TABS_SOTBIT_MAILING_INSTRUCTION',
    "TABS_NAME" => GetMessage("TABS_SOTBIT_MAILING_INSTRUCTION_TITLE"),   
    "PARENT" => "PARAM_TABS_SOTBIT_MAILING_INSTRUCTION",
    "PARENT_NAME" => GetMessage("GROUP_TABS_SOTBIT_MAILING_INSTRUCTION_NAME"),    
    "TYPE" => "TABS_INFO",
    "DEFAULT" => GetMessage("TABS_SOTBIT_MAILING_INSTRUCTION_DEFAULT"),    
);    
  
 
  
  
 


?>
