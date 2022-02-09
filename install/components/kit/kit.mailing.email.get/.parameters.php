<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule('kit.mailing')){
    return false;    
}

// ������� ��������� ��������
// START
$categoriesList = array();
$categoriesLi = CKitMailingHelp::GetCategoriesInfo();
foreach($categoriesLi as $v) { 
    $categoriesList[$v['ID']] = $v['NAME'];            
}  
// END  


//������� ������ �������������
//START
$arUserGroup = array();
$rsGroups = CGroup::GetList ($by = "id", $order = "asc", Array ("ACTIVE" => 'Y'));
while($arrsGroups = $rsGroups->Fetch()) {

    if($arrsGroups['ID'] != 1) {
        $arUserGroup[$arrsGroups['ID']] = '['.$arrsGroups['ID'].'] '.$arrsGroups['NAME'];        
    }
       
}
//END

// ������� ��� �����������
// START
$arrMailingEvent = array();
$resMailingList = CKitMailingEvent::GetList(array($by=>$order), array('ACTIVE'=>'Y','TEMPLATE'=>'user_register_mailing'), false,array('ID','NAME','TEMPLATE'));
while($arrMailingList = $resMailingList->Fetch()) {
    $arrMailingEvent[$arrMailingList['ID']] = '['.$arrMailingList['ID'].'] '.$arrMailingList['NAME'];   
}
// END 

//������� ������ ��������
//START
if(CModule::IncludeModule('subscribe')) { 
    $arRubric = array();
    $rub = CRubric::GetList(array("ID"=>"ASC"), array("ACTIVE"=>"Y"));
    while($arrRub = $rub->Fetch()) {
         $arRubric[$arrRub['ID']] = '['.$arrRub['ID'].'] '.$arrRub['NAME'];     
    }     
}
//END   

//������� ������ � mailchimp.com
//START
$api_key_mailchimp = COption::GetOptionString('kit.mailing', 'MAILCHIMP_API_KEY');
if($api_key_mailchimp) {
    $arMailchimpList = array();
    $ApiMailchimp = new MCAPI($api_key_mailchimp);
    $retval = $ApiMailchimp->lists(array(),0,100); 
    if(is_array($retval['data'])) {
        foreach($retval['data'] as $listitem) {
            $arMailchimpList[$listitem['id']] = '['.$listitem['id'].'] '.$listitem['name']. ' - '.$listitem['web_id'];               
        }           
    }  
}
//END

//������� ������ � unisender
//START
$getListUniSender = CKitMailingHelp::QueryUniSender('getLists');
$arListUniSender = array();
if($getListUniSender['result']) {
    foreach($getListUniSender['result'] as $k=>$v) {
        $arListUniSender[$v['id']] = '['.$v['id'].'] '.$v['title'];       
    }    
}
//END

  
$arComponentParameters['GROUPS']['GROUPS_DOPOLN'] = array(
    "NAME" => GetMessage("GROUPS_DOPOLN"),
);                     
$arComponentParameters['PARAMETERS']['CATEGORIES_ID']  = array(
    "PARENT" => "BASE",
    "NAME" => GetMessage("CATEGORIES_ID"),
    "TYPE" => "LIST",
    "MULTIPLE" => "Y",
    "VALUES" => $categoriesList,
);  

if($arCurrentValues['TYPE']=='FIELD'){
    $arComponentParameters['PARAMETERS']['CATEGORIES_SHOW']  = array(
        "PARENT" => "BASE",
        "NAME" => GetMessage("CATEGORIES_SHOW"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "N",        
    );          
}
 
$arComponentParameters['PARAMETERS']['JQUERY'] =  array(
    "PARENT" => "GROUPS_DOPOLN",
    "NAME" => GetMessage("JQUERY_ADD"),
    "TYPE" => "CHECKBOX",
    "DEFAULT" => "N",
); 
  

if(in_array($arCurrentValues['TYPE'],array('SECTION_ID','PROPERTY'))) { 
 
    $arComponentParameters['GROUPS']['GROUPS_AUTO_CREATE_CATEGORY'] = array(
        "NAME" => GetMessage("GROUPS_AUTO_CREATE_CATEGORY")
    );   
    
    // ������������ � �������
    $arComponentParameters['PARAMETERS']['CATEGORY_SUNC_USER'] = array(
        "PARENT" => "GROUPS_AUTO_CREATE_CATEGORY",
        "NAME" => GetMessage("CATEGORY_SUNC_USER"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "N",
    ); 
    $arComponentParameters['PARAMETERS']['CATEGORY_SUNC_USER_GROUP'] = array(
        "PARENT" => "GROUPS_AUTO_CREATE_CATEGORY",
        "NAME" => GetMessage("CATEGORY_SUNC_USER_GROUP"),
        "TYPE" => "LIST",
        "MULTIPLE" => "Y",
        "VALUES" => $arUserGroup,
    );       
    $arComponentParameters['PARAMETERS']['CATEGORY_SUNC_USER_MESSAGE'] = array(
        "PARENT" => "GROUPS_AUTO_CREATE_CATEGORY",
        "NAME" => GetMessage("CATEGORY_SUNC_USER_MESSAGE"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "N",
    );     
    $arComponentParameters['PARAMETERS']['CATEGORY_SUNC_USER_EVENT'] = array(
        "PARENT" => "GROUPS_AUTO_CREATE_CATEGORY",
        "NAME" => GetMessage("CATEGORY_SUNC_USER_EVENT"),
        "TYPE" => "LIST",
        "MULTIPLE" => "N",
        "VALUES" => $arrMailingEvent,
    ); 
     
    // ���������� �������
    if(CModule::IncludeModule('subscribe')) {    
        $arComponentParameters['PARAMETERS']['CATEGORY_SUNC_SUBSCRIPTION'] = array(
            "PARENT" => "GROUPS_AUTO_CREATE_CATEGORY",
            "NAME" => GetMessage("CATEGORY_SUNC_SUBSCRIPTION"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "N",
        );     
        $arComponentParameters['PARAMETERS']['CATEGORY_SUNC_SUBSCRIPTION_LIST'] = array(
            "PARENT" => "GROUPS_AUTO_CREATE_CATEGORY",
            "NAME" => GetMessage("CATEGORY_SUNC_SUBSCRIPTION_LIST"),
            "TYPE" => "LIST",
            "MULTIPLE" => "Y",
            "VALUES" => $arRubric,
        );
    }
    
    // ���������� mailchimp
    $arComponentParameters['PARAMETERS']['CATEGORY_SUNC_MAILCHIMP'] = array(
        "PARENT" => "GROUPS_AUTO_CREATE_CATEGORY",
        "NAME" => GetMessage("CATEGORY_SUNC_MAILCHIMP"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "N",
    );     
    $arComponentParameters['PARAMETERS']['CATEGORY_SUNC_MAILCHIMP_LIST'] = array(
        "PARENT" => "GROUPS_AUTO_CREATE_CATEGORY",
        "NAME" => GetMessage("CATEGORY_SUNC_MAILCHIMP_LIST"),
        "TYPE" => "LIST",
        "MULTIPLE" => "N",
        "VALUES" => $arMailchimpList,
    ); 
         
    // ���������� unisender
    $arComponentParameters['PARAMETERS']['CATEGORY_SUNC_UNISENDER'] = array(
        "PARENT" => "GROUPS_AUTO_CREATE_CATEGORY",
        "NAME" => GetMessage("CATEGORY_SUNC_UNISENDER"),
        "TYPE" => "CHECKBOX",
        "DEFAULT" => "N",
    );     
    $arComponentParameters['PARAMETERS']['CATEGORY_SUNC_UNISENDER_LIST'] = array(
        "PARENT" => "GROUPS_AUTO_CREATE_CATEGORY",
        "NAME" => GetMessage("CATEGORY_SUNC_UNISENDER_LIST"),
        "TYPE" => "LIST",
        "MULTIPLE" => "N",
        "VALUES" => $arListUniSender,
    );

      
}       
    
$arComponentParameters['PARAMETERS']["CACHE_TIME"] = array("DEFAULT"=>36000000);
$arComponentParameters['PARAMETERS']["CACHE_GROUPS"] = array(
		"PARENT" => "CACHE_SETTINGS",
		"NAME" => GetMessage("CACHE_GROUPS"),
		"TYPE" => "CHECKBOX",
		"DEFAULT" => "Y",
);




?>
