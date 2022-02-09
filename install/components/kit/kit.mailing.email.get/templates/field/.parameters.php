<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();


$arType = array(
    'FIELD' => GetMessage("TYPE_VALUE_FIELD"),
    'SECTION_ID' => GetMessage("TYPE_VALUE_SECTION_ID"), 
    'PROPERTY' => GetMessage("TYPE_VALUE_PROPERTY")       
);    




/**********  параметры компонента  ***************/
/**********            START       ***************/
$arTemplateParameters = array();


$arTemplateParameters['TYPE'] = array(
    "NAME" => GetMessage("TYPE"),
    "TYPE" => "LIST",
    "MULTIPLE" => "N",
    "PARENT" => "GROUPS_VISUAL",  
    "VALUES" => $arType,         
    "REFRESH" => "Y",
    "DEFAULT" => "FIELD",
);



if($arCurrentValues['TYPE']=='SECTION_ID') {
    
    $arTemplateParameters["PARAM_2:SECTION_ID"] = array(
        "NAME" => GetMessage("PARAM_2_SECTION_ID"),
        "TYPE" => "HIDDEN",
        "PARENT" => "GROUPS_VISUAL",           
        "DEFAULT" => '',
    ); 
       
}        
     
     
if($arCurrentValues['TYPE']=='PROPERTY') {
    
    $arTemplateParameters["PARAM_2:PROPERTY"] = array(
        "NAME" => GetMessage("PARAM_2_PROPERTY"),
        "TYPE" => "HIDDEN",
        "PARENT" => "GROUPS_VISUAL",           
        "DEFAULT" => 'CML2_MANUFACTURER',
    ); 
    
    $arTemplateParameters["PARAM_3:PROPERTY"] = array(
        "NAME" => GetMessage("PARAM_3_PROPERTY"),
        "TYPE" => "HIDDEN",
        "PARENT" => "GROUPS_VISUAL",           
        "DEFAULT" => '',
    );  
      
}  


     
     
        
$arTemplateParameters["INFO_TEXT"] = array(
    "NAME" => GetMessage("INFO_TEXT"),
    "TYPE" => "STRING",
    "PARENT" => "GROUPS_VISUAL",           
    "DEFAULT" => GetMessage('INFO_TEXT_DEFAULT'),
);
$arTemplateParameters["EMAIL_SEND_END"] = array(
    "NAME" => GetMessage("EMAIL_SEND_END"),
    "TYPE" => "STRING",
    "DEFAULT" => GetMessage("EMAIL_SEND_END_DEFAULT"),
    "PARENT" => "GROUPS_VISUAL",
);            
$arTemplateParameters["COLOR_BUTTON"] = array(
    "NAME" => GetMessage("COLOR_BUTTON"),
    "TYPE" => "STRING",
    "DEFAULT" => "6e7278",
    "PARENT" => "GROUPS_VISUAL",
);
$arTemplateParameters["DISPLAY_IF_ADMIN"] = array(
    "NAME" => GetMessage("REGISTER_DISPLAY_IF_ADMIN"),
    "TYPE" => "CHECKBOX",
    "DEFAULT" => "N",
    "PARENT" => "GROUPS_DOPOLN",
);  
$arTemplateParameters["DISPLAY_NO_AUTH"] = array(
    "NAME" => GetMessage("DISPLAY_NO_AUTH"),
    "TYPE" => "CHECKBOX",
    "DEFAULT" => "N",
    "PARENT" => "GROUPS_DOPOLN",
);  
                       
/**********            END       ***************/
                       
                       
                       




?>