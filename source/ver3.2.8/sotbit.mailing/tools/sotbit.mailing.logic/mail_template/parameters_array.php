<?
$arTemplateParameters["SUBJECT"] = array(
    "TABS" => 'TABS_MESSAGE',
    "TABS_NAME" => GetMessage("TABS_MESSAGE_NAME"),    
    "PARENT" => "MESSAGE_INFO",
    "PARENT_NAME" => GetMessage("GROUP_MESSAGE_INFO_NAME"),
    "NAME" => GetMessage("SUBJECT_TITLE"),
    "TYPE" => "STRING",
    "DEFAULT" => GetMessage("SUBJECT_DEFAULT"),
    "SORT" => "10",
    "SIZE" => '50'
); 
$arTemplateParameters["EMAIL_FROM"] = array(
    "TABS" => 'TABS_MESSAGE',
    "TABS_NAME" => GetMessage("TABS_MESSAGE_NAME"),    
    "PARENT" => "MESSAGE_INFO",
    "PARENT_NAME" => GetMessage("GROUP_MESSAGE_INFO_NAME"),
    "NAME" => GetMessage("EMAIL_FROM_TITLE"),
    "TYPE" => "STRING",
    "DEFAULT" => COption::GetOptionString('main', 'email_from'),
    "SORT" => "20",
    "SIZE" => '50'
);    
$arTemplateParameters["EMAIL_TO"] = array(
    "TABS" => 'TABS_MESSAGE',
    "TABS_NAME" => GetMessage("TABS_MESSAGE_NAME"),    
    "PARENT" => "MESSAGE_INFO",
    "PARENT_NAME" => GetMessage("GROUP_MESSAGE_INFO_NAME"),
    "NAME" => GetMessage("EMAIL_TO_TITLE"),
    "TYPE" => "STRING",
    "DEFAULT" => "#USER_EMAIL#" ,
    "SORT" => "30",
    "SIZE" => '50'
); 

$arTemplateParameters["BCC"] = array(
    "TABS" => 'TABS_MESSAGE',
    "TABS_NAME" => GetMessage("TABS_MESSAGE_NAME"),    
    "PARENT" => "MESSAGE_INFO",
    "PARENT_NAME" => GetMessage("GROUP_MESSAGE_INFO_NAME"),
    "NAME" => GetMessage("BCC_TITLE"),
    "TYPE" => "STRING",
    "DEFAULT" => "" ,
    "SORT" => "40",
    "SIZE" => '50'
);

$arTemplateParameters["SITE_TEMPLATE_ID"] = array(
    "TABS" => 'TABS_MESSAGE',
    "TABS_NAME" => GetMessage("TABS_MESSAGE_NAME"),    
    "PARENT" => "MESSAGE_INFO",
    "PARENT_NAME" => GetMessage("GROUP_MESSAGE_INFO_NAME"),
    "NAME" => GetMessage("SITE_TEMPLATE_ID_TITLE"),
    "TYPE" => "LIST",
    "VALUES" => $arMailSiteTemplateList,
    "DEFAULT" =>  COption::GetOptionString("sotbit.mailing", "TEMPLATE_MAILING_THEME_DEF"),
    "REFRESH" => "N",
    "SORT" => "50",
    "SIZE" => '50'
);
    
$arTemplateParameters["MESSAGE"] = array(
    "TABS" => 'TABS_MESSAGE',
    "TABS_NAME" => GetMessage("TABS_MESSAGE_NAME"),    
    "PARENT" => "MESSAGE_INFO",
    "PARENT_NAME" => GetMessage("GROUP_MESSAGE_INFO_NAME"),
    "TYPE" => "TEXT",
    "TYPE_MAIL" => "Y",    
    "DEFAULT" => $MESSAGE_DEFAULT, 
    "NOTES"  =>  GetMessage("GROUP_MESSAGE_NOTES", $Messsage_peremen),
    "SORT" => "60",
);
?>