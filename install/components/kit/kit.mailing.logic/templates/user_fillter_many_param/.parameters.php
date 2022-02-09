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




// ������ �� �������������
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



// ��������������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/recommend/parameters_get_info.php");
// END


// ������������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/viewed/parameters_get_info.php");
// END


// ������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/discounts/parameters_get_info.php");
// END


//���������� ����� �� ���������
//START    
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/mail_template/parameters_get_info.php");
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
// ------LIST - ������ . ���� ���� �������� "MULTIPLE" => 'Y'  ������ ����� �������������
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
   
// �������������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/recommend/parameters_array.php");
//END
     

//������������� ������
//START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/viewed/parameters_array.php");
//END
 
    
//������ �� ������
//START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/discounts/parameters_array.php");
//END     
 
        
    
  //���������� �� ��������
$arTemplateParameters["EXCEPTIONS_USER_SEND"] = array(
    "PARENT" => "PARAM_EXCEPTIONS",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_EXCEPTIONS"),
    "NAME" => GetMessage("EXCEPTIONS_USER_SEND_TITLE"),
    "TYPE" => "CHECKBOX",
    "DEFAULT" => "" ,
    "SORT" => "10",
    "NOTES" => GetMessage("EXCEPTIONS_USER_SEND_NOTES"),
); 
          
 
    
//������ ������
//START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/mail_template/parameters_array.php");
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
         
    include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/mail_template/parameters_php_array.php");
         
}
   
// END  
  
  
$arTemplateParameters["TABS_KIT_MAILING_INSTRUCTION"] = array(
    "TABS" => 'TABS_KIT_MAILING_INSTRUCTION',
    "TABS_NAME" => GetMessage("TABS_KIT_MAILING_INSTRUCTION_TITLE"),   
    "PARENT" => "PARAM_TABS_KIT_MAILING_INSTRUCTION",
    "PARENT_NAME" => GetMessage("GROUP_TABS_KIT_MAILING_INSTRUCTION_NAME"),    
    "TYPE" => "TABS_INFO",
    "DEFAULT" => GetMessage("TABS_KIT_MAILING_INSTRUCTION_DEFAULT"),    
);     
  
  
 


?>