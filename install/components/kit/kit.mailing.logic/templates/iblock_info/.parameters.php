<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// ������� ������ ����������
if(!CModule::IncludeModule("iblock")) return;

// ������ �� ���������
// START
    // ������� ��� ���������
    $arIBlockTypeInfo = CIBlockParameters::GetIBlockTypes();
    if(empty($arCurrentValues["IBLOCK_TYPE_INFO"])){
        foreach($arIBlockTypeInfo as $key => $value) {
           $arCurrentValues["IBLOCK_TYPE_INFO"] = $key;
           break;
        } 
    }
    // ������� ��������
    $arIBlockInfo = array('' => GetMessage('SELECT_CHANGE'));
    if($arCurrentValues["IBLOCK_TYPE_INFO"]) {
        $rsIBlock = CIBlock::GetList(Array("sort" => "asc"), Array("TYPE" => $arCurrentValues["IBLOCK_TYPE_INFO"], "ACTIVE"=>"Y"));
        while($arr=$rsIBlock->Fetch())
        {
            $arIBlockInfo[$arr["ID"]] = "[".$arr["ID"]."] ".$arr["NAME"];
        }    
        
    }

    //������� �������� ����������
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
    
    //�������� �������� ����������
    $arIBlockInfoPropertyListValue = array('' => GetMessage('SELECT_CHANGE'));
    if($arCurrentValues["IBLOCK_ID_INFO"] && $arCurrentValues["IBLOCK_INFO_PROPERTY_FILLTER_LIST"]) { 
        $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID_INFO"], "CODE"=>$arCurrentValues["IBLOCK_INFO_PROPERTY_FILLTER_LIST"]));
        while($enum_fields = $property_enums->GetNext())
        {      
            $arIBlockInfoPropertyListValue[$enum_fields["ID"]] = "[".$enum_fields["XML_ID"]."] ".$enum_fields["VALUE"];   
        }            
    } 
    
    
    //�������� �������� ����� �������� ���������
    $arIBlockInfoPropertyListFinishValue = array('' => GetMessage('SELECT_CHANGE'));
    if($arCurrentValues["IBLOCK_ID_INFO"] && $arCurrentValues["IBLOCK_INFO_PROPERTY_FINISH_LIST"]) { 
        $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$arCurrentValues["IBLOCK_ID_INFO"], "CODE"=>$arCurrentValues["IBLOCK_INFO_PROPERTY_FINISH_LIST"]));
        while($enum_fields = $property_enums->GetNext())
        {      
            $arIBlockInfoPropertyListFinishValue[$enum_fields["ID"]] = "[".$enum_fields["XML_ID"]."] ".$enum_fields["VALUE"];   
        }            
    }         
       
        
   
        
        
// END


// ��������������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/recommend/parameters_get_info.php");
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
// ------CHECKBOX - ������������� ��/���
// ------TEXT - ����� html ���������
// ------TEXTAREA - ����� ���������� ����
// ------TABS_INFO - ��� � ������, ��� ���������� ��������
// ------PHP - ������� php ����  � ������ ��������
// ------DATE_PERIOD - ��������� � �����
// ------USER_ID - ����� id ������������

  
$arTemplateParameters = array(); 
	
  // ������� ��������� ��� ��������       
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

    
//������ �� �������� ������
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
  
            
//������ �� �������� ������   
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
      
//������ �� �������� ����      
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
      
// ��������� ����� ����� ������      
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
 
   
//���������� �� ��������
$arTemplateParameters["EMAIL_DUBLICATE"] = array(
    "PARENT" => "PARAM_EXCEPTIONS",
    "PARENT_NAME" => GetMessage("GROUP_PARAM_EXCEPTIONS"),
    "NAME" => GetMessage("EMAIL_DUBLICATE_TITLE"),
    "TYPE" => "CHECKBOX",
    "DEFAULT" => "" ,
    "SORT" => "10",
    "NOTES" => GetMessage("EMAIL_DUBLICATE_NOTES"),
); 


   
   
// �������������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/recommend/parameters_array.php");
//END
     
     
//������ �� ������
//START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/discounts/parameters_array.php");
//END
 
        
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
