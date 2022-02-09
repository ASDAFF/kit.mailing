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
    "PARENT" => "PARAM_USER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_USER_FILLTER"),
    "NAME" => GetMessage('SITE_ID_TITLE'),
    "TYPE" => "LIST",
    "VALUES" => $arrSiteId,
    "SORT" => "1",
);      
    
  // ��������� ������  
$arTemplateParameters["DATE_REGISTER"] = array(    
    "PARENT" => "PARAM_USER_FILLTER",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_USER_FILLTER"),
    "NAME" => GetMessage("DATE_REGISTER_TITLE"),
    "TYPE" => "DATE_PERIOD_AGO",
    "SORT" => "22",
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
