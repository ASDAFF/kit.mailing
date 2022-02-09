<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// ������� ������ ����������
if(!CModule::IncludeModule("iblock")) return;
     
// ������ ������ 
// START
$arrSiteId = array(''=>GetMessage('SITE_ID_ALL'));
$rsSites = CSite::GetList($by="sort", $order="desc", Array());
while ($arS = $rsSites->Fetch())
{
    $arrSiteId[$arS['LID']] = '['.$arS['LID'].'] '.$arS['NAME'];  
}  
// END
     
     
// ��������������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/recommend/parameters_get_info.php"); 
// END


// ������������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/viewed/parameters_get_info.php");                
// END


// ������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/discounts/parameters_get_info.php"); 
// END
 
 
  
//���������� ����� �� ���������
//START    
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/mail_template/parameters_get_info.php"); 
//END



//��������  ���������� �������
// TABS - ��� ���� � ����
// TABS_NAME - ��� ����
// PARENT - ��� ������ � ���� 
// PARENT_NAME - ��� ������ � ����
// NAME - ��� ��������
// TYPE -  ���  ��������   
// ------STRING - ������
// ------INT - �������� ���� 
// ------CHECKBOX - ������������� ��/���
// ------TEXT - ����� html ���������
// ------TEXTAREA - ����� ���������� ����
// ------TABS_INFO - ��� � ������, ��� ���������� ��������
// ------PHP - ������� php ����  � ������ ��������
// ------DATE_PERIOD - ��������� � �����
// ------USER_ID - ����� id ������������

$arTemplateParameters = array(); 
    
  // ���������� �������������  
$arTemplateParameters["SITE_ID"] = array(    
    "PARENT" => "PARAM_BASKET_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_BASKET_FILLTER"),
    "NAME" => GetMessage('SITE_ID_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arrSiteId,
    "SORT" => "10",
);      
    
    
  // ��������� ������ ������� ������
$arTemplateParameters["BASKET_DATE_UPDATE"] = array(    
    "PARENT" => "PARAM_BASKET_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_BASKET_FILLTER"),
    "NAME" => GetMessage("BASKET_DATE_UPDATE_TITLE"),
    "TYPE" => "DATE_PERIOD_AGO",
    "SORT" => "12",
    "NOTES" => GetMessage("BASKET_DATE_UPDATE_NOTES"),
);     
  
  //������� ��� ������ �������
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
    
  // PHP �����������
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
    
    
        
// �������������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/recommend/parameters_array.php"); 
//END
     

//������������� ������
//START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/viewed/parameters_array.php"); 
//END
 

//������ �� ������
//START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/discounts/parameters_array.php"); 
//END
 
 
//������ ������
//START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/sotbit.mailing/tools/sotbit.mailing.logic/mail_template/parameters_array.php");  
//END

  // PHP ����������� 
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
    $arTemplateParameters["PHP_FILLTER_BASKET_PARAM_AFTER"] = array(
        "PARENT" => "PHP_MODIF_MAILING",                                      
        "PARENT_NAME" => GetMessage("GROUP_PHP_MODIF_MAILING_NAME"),
        "NAME" => GetMessage("PHP_FILLTER_BASKET_PARAM_AFTER_TITLE"),
        "HEIGHT" => 200,
        "WIDTH" => 1000,
        "TYPE" => "PHP",
        "SORT" => "40",
        "NOTES" => GetMessage("PHP_FILLTER_BASKET_PARAM_AFTER_NOTES"),
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