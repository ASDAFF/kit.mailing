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
    
  // ‘ильтраци€ пользователей  
$arTemplateParameters["SITE_ID"] = array(    
    "PARENT" => "PARAM_USER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_USER_FILLTER"),
    "NAME" => GetMessage('SITE_ID_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arrSiteId,
    "SORT" => "10",
);  

$arTemplateParameters["USER_FILLTER_DATE_REGISTER"] = array(    
    "PARENT" => "PARAM_USER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_USER_FILLTER"),
    "NAME" => GetMessage('USER_FILLTER_DATE_REGISTER_TITLE'),
    "TYPE" => "DATE_PERIOD",
    "SORT" => "20",
); 
$arTemplateParameters["USER_FILLTER_DATE_REGISTER_AGO"] = array(    
    "PARENT" => "PARAM_USER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_USER_FILLTER"),
    "NAME" => GetMessage('USER_FILLTER_DATE_REGISTER_TITLE'),
    "TYPE" => "DATE_PERIOD_AGO",
    "SORT" => "22",
);   
$arTemplateParameters["USER_FILLTER_LAST_LOGIN"] = array(    
    "PARENT" => "PARAM_USER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_USER_FILLTER"),
    "NAME" => GetMessage('USER_FILLTER_LAST_LOGIN_TITLE'),
    "TYPE" => "DATE_PERIOD",
    "SORT" => "30",
); 
$arTemplateParameters["USER_FILLTER_LAST_LOGIN_AGO"] = array(    
    "PARENT" => "PARAM_USER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_USER_FILLTER"),
    "NAME" => GetMessage('USER_FILLTER_LAST_LOGIN_TITLE'),
    "TYPE" => "DATE_PERIOD_AGO",
    "SORT" => "32",
);        
$arTemplateParameters["USER_FILLTER_GROUPS_ID"] = array(    
    "PARENT" => "PARAM_USER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_USER_FILLTER"),
    "NAME" => GetMessage('USER_FILLTER_GROUPS_ID_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arUserGroup,
    "SORT" => "40",
    "SIZE" => '8',
    "MULTIPLE" => 'Y'
);   
          
$arTemplateParameters["USER_FILLTER_PERSONAL_GENDER"] = array(    
    "PARENT" => "PARAM_USER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_USER_FILLTER"),
    "NAME" => GetMessage('USER_FILLTER_PERSONAL_GENDER_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arUserPersonalGender,
    "SORT" => "50",
); 
$arTemplateParameters["USER_FILLTER_PERSONAL_BIRTHDAY"] = array(    
    "PARENT" => "PARAM_USER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_USER_FILLTER"),
    "NAME" => GetMessage('USER_FILLTER_PERSONAL_BIRTHDAY_TITLE'),
    "TYPE" => "DATE_PERIOD",
    "SORT" => "55",
);  
$arTemplateParameters["USER_FILLTER_PERSONAL_BIRTHDAY_AGO"] = array(    
    "PARENT" => "PARAM_USER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_USER_FILLTER"),
    "NAME" => GetMessage('USER_FILLTER_PERSONAL_BIRTHDAY_TITLE'),
    "TYPE" => "DATE_PERIOD_AGO",
    "SORT" => "56",
); 
    
$arTemplateParameters["USER_FILLTER_NAME"] = array(
    "PARENT" => "PARAM_USER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_USER_FILLTER"),
    "NAME" => GetMessage('USER_FILLTER_NAME_TITLE'),
    "TYPE" => "STRING",
    "SORT" => "60",
    "NOTES" => GetMessage("USER_FILLTER_NAME_NOTES"), 
);           
   
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
 
        
    
  //исключение из рассылки
$arTemplateParameters["EXCEPTIONS_USER_SEND"] = array(
    "PARENT" => "PARAM_EXCEPTIONS",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_EXCEPTIONS"),
    "NAME" => GetMessage("EXCEPTIONS_USER_SEND_TITLE"),
    "TYPE" => "CHECKBOX",
    "DEFAULT" => "" ,
    "SORT" => "10",
    "NOTES" => GetMessage("EXCEPTIONS_USER_SEND_NOTES"),
); 
          
 
    
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