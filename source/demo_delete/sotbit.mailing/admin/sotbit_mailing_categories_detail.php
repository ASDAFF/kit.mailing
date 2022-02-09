<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
$module_id = "sotbit.mailing";
$arTabsControl = array();
IncludeModuleLangFile(__FILE__);
$APPLICATION->SetTitle(GetMessage($module_id.'_PAGE_TITLE', array("#ID#" => $_GET['ID']))); 

//�������� ����
$CONS_RIGHT = $APPLICATION->GetGroupRight($module_id);   
if ($CONS_RIGHT <= "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin.php');
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/include.php");

$CSotbitMailingTools = new CSotbitMailingTools();


$MailingLi = CSotbitMailingHelp::GetMailingInfo();
$MailingList = array();
foreach($MailingLi as $v) {
    $MailingList[$v['ID']] = $v;    
}

if($_GET['ID']) {
    $ID = $_GET['ID'];
    $arResult = CSotbitMailingCategories::GetByID($ID);

    $arResult['SUNC_USER_GROUP'] = unserialize($arResult['SUNC_USER_GROUP']);  
    $arResult['SUNC_SUBSCRIPTION_LIST'] = unserialize($arResult['SUNC_SUBSCRIPTION_LIST']);        
    
    
    if(empty($arResult)){
        LocalRedirect("sotbit_mailing_categories.php?lang=".LANG, true);
    } 
    

}  

/*
if($_GET['ID'] && $arResult['SUNC_UNISENDER'] == 'Y' && $arResult['SUNC_UNISENDER_LIST'] && $_GET['action']=='EXPORT_UNISENDER') {
    $result = CSotbitMailingHelp::UniSenderExportContact($_GET['ID'],$arResult['SUNC_UNISENDER_LIST']);  
    
    if($result['STATUS']=='OK') {
        LocalRedirect("sotbit_mailing_categories_detail.php?ID=".$ID."&UNISENDER_EXPORT_OK=".$result['COUNT']."&lang=".LANG);        
    }
  
}

if($_GET['UNISENDER_EXPORT_OK']){
    CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage($module_id."_EXPORT_UNISENDER",array('#COUNT#'=>$_GET['UNISENDER_EXPORT_OK'])), "TYPE"=>"OK"));        
}
*/
 
    
$arResult['EVENT_INFO'] = $MailingList[$arResult['ID_EVENT']]; 

// START

$arr_table_event = array(
    'ACTIVE',
    'NAME',
    'DESCRIPTION',
    'SUNC_USER',
    'SUNC_USER_MESSAGE',
    'SUNC_USER_GROUP',
    'SUNC_USER_EVENT',
    'SUNC_SUBSCRIPTION',
    'SUNC_SUBSCRIPTION_LIST',
    'SUNC_MAILCHIMP',
    'SUNC_MAILCHIMP_BACK',
    'SUNC_MAILCHIMP_LIST',
    'SUNC_UNISENDER',
    'SUNC_UNISENDER_BACK',
    'SUNC_UNISENDER_LIST'    
);
     
$arr_form_option = array(
    'mid',
    'lang',
    'sessid',
    'autosave_id',
    'refresh',
    'update',
    'save',
);

if($_REQUEST['update'] && $_REQUEST['save']){
     

     $arr_save = array();
     foreach($_REQUEST as $k_save =>  $v_save) {
         
        if(in_array($k_save ,$arr_table_event)){
            

            if($k_save == 'SUNC_USER_GROUP') {
                $v_save = serialize($v_save);        
            }
            if($k_save == 'SUNC_SUBSCRIPTION_LIST') {
                $v_save = serialize($v_save);        
            }    
                        
            
            $arr_save[$k_save] = $v_save;  
                     
        } 
            
     }
     

     
     if($ID) { 
            CSotbitMailingCategories::Update($ID , $arr_save);               
     } else {
         global $DB ;
         $arr_save['DATE_CREATE'] = Date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL", SITE_ID))); 
         
         
         if(empty($arr_save['NAME'])) {
            CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage($module_id."_ERROR_NAME_SAVE"), "TYPE"=>"ERROR"));             
         } else {
            $ID = CSotbitMailingCategories::Add($arr_save);               
         }

         
         
     }
     
    // ������� ��� ��� �������
    // START
    global $CACHE_MANAGER; 
    $CACHE_MANAGER->ClearByTag($module_id.'_GetCategoriesInfo');
    // END     
    if($ID) {
        LocalRedirect("sotbit_mailing_categories_detail.php?ID=".$ID."&lang=".LANG, true);
    } 

     
                
    
}

foreach($_REQUEST as $key => $val) {
    
    if(!in_array($key,$arr_form_option)) {
        $arResult[$key] = $val;            
    }
    
}

// END





$aMenu = array(
    array(
        "TEXT" => GetMessage($module_id.'_PANEL_TOP_BACK_TITLE'),
        "ICON" => "btn_list",
        "LINK" => "/bitrix/admin/sotbit_mailing_categories.php?lang=".LANG
    )
);


if($ID) {
    
    /*
    // �������������� �� unisender    
    $aMenu[] = array(
        "TEXT" => GetMessage($module_id."_PANEL_TOP_EXPORT_UNISENDER_TITLE"), 
        "LINK" => "javascript:if(confirm('".GetMessage($module_id."_PANEL_TOP_EXPORT_UNISENDER_CONFORM",array("#ID#"=>$ID))."')) window.location='/bitrix/admin/sotbit_mailing_categories_detail.php?ID=".$ID."&action=EXPORT_UNISENDER&lang=".LANG."&".bitrix_sessid_get()."';",
        "TITLE" => GetMessage($module_id."_PANEL_TOP_EXPORT_UNISENDER_ALT"),
        "ICON" => "btn_new",
    );   
    */
    //������� ��������
    $aMenu[] = array(
        "TEXT" => GetMessage($module_id."_PANEL_TOP_DELETE_TITLE"), 
        "LINK" => "javascript:if(confirm('".GetMessage($module_id."_PANEL_TOP_DELETE_CONFORM",array("#ID#"=>$ID))."')) window.location='/bitrix/admin/sotbit_mailing_categories.php?action=delete&ID[]=".$ID."&lang=".LANG."&".bitrix_sessid_get()."';",
        "TITLE" => GetMessage($module_id."_PANEL_TOP_DELETE_ALT"),
        "ICON" => "btn_delete",
    );    
        
 

    
    
}





?>
<a name="form"></a>
<?
$context = new CAdminContextMenu($aMenu);
$context->Show(); 
?>


<?
$arTabs = array(
   array(
      'DIV' => 'edit10',
      'TAB' => GetMessage($module_id.'_edit10'),
      'ICON' => '',
      'TITLE' => GetMessage($module_id.'_edit10'),
      'SORT' => '10'
   ),   
   array(
      'DIV' => 'edit20',
      'TAB' => GetMessage($module_id.'_edit_VIDEO'),
      'ICON' => '',
      'TITLE' => GetMessage($module_id.'_edit_VIDEO'),
      'SORT' => '20'
   ),              
);
                               
$arGroups = array(
   'OPTION_10' => array('TITLE' => GetMessage($module_id.'_OPTION_10'), 'TAB' => 1),   
   'OPTION_20' => array('TITLE' => GetMessage($module_id.'_OPTION_20'), 'TAB' => 1),      
   'OPTION_30' => array('TITLE' => GetMessage($module_id.'_OPTION_30'), 'TAB' => 1),
   'OPTION_40' => array('TITLE' => GetMessage($module_id.'_OPTION_40'), 'TAB' => 1),  
   'OPTION_50' => array('TITLE' => GetMessage($module_id.'_OPTION_50'), 'TAB' => 1),  
   'OPTION_VIDEO' => array('TITLE' => GetMessage($module_id.'_OPTION_VIDEO'), 'TAB' => 2),                 
);

 

//������� ������ �������������
//START
$arUserGroup = array();
$rsGroups = CGroup::GetList ($by = "id", $order = "asc", Array ("ACTIVE" => 'Y'));
while($arrsGroups = $rsGroups->Fetch()) {

    if($arrsGroups['ID'] != 1) {
        $arUserGroup['REFERENCE_ID'][] = $arrsGroups['ID'];
        $arUserGroup['REFERENCE'][] = '['.$arrsGroups['ID'].'] '.$arrsGroups['NAME'];        
    }
       
}
//END
 
//������� ������ ��������
//START
if(CModule::IncludeModule('subscribe')) { 
    $arRubric = array();
    $rub = CRubric::GetList(array("ID"=>"ASC"), array("ACTIVE"=>"Y"));
    while($rub->ExtractFields("r_")) {
         $arRubric['REFERENCE_ID'][] = $r_ID;
         $arRubric['REFERENCE'][] = '['.$r_ID.'] '.$r_NAME;        
    }  
}
//END   

// ������� ��� �����������
// START
$arrMailingEvent = array();
$resMailingList = CSotbitMailingEvent::GetList(array($by=>$order), array('ACTIVE'=>'Y','TEMPLATE'=>'user_register_mailing'), false,array('ID','NAME','TEMPLATE'));
while($arrMailingList = $resMailingList->Fetch()) {
    $arrMailingEvent['REFERENCE_ID'][] = $arrMailingList['ID'];
    $arrMailingEvent['REFERENCE'][] = '['.$arrMailingList['ID'].'] '.$arrMailingList['NAME'];   
}
// END 

//������� ������ � mailchimp.com
//START
$api_key_mailchimp = COption::GetOptionString('sotbit.mailing', 'MAILCHIMP_API_KEY');
if($api_key_mailchimp) {
    $arMailchimpList = array();
    $ApiMailchimp = new MCAPI($api_key_mailchimp);
    $retval = $ApiMailchimp->lists(array(),0,100); 
    if(is_array($retval['data'])) {
        foreach($retval['data'] as $listitem) {
            $arMailchimpList['REFERENCE_ID'][] = $listitem['id'];
            $arMailchimpList['REFERENCE'][] = '['.$listitem['id'].'] '.$listitem['name']. ' - '.$listitem['web_id'];               
        }           
    }  
}
     
//END

//������� ������ � unisender
//START
$getListUniSender = CSotbitMailingHelp::QueryUniSender('getLists'); 
$arListUniSender = array();
if($getListUniSender['result']) {
    foreach($getListUniSender['result'] as $k=>$v) {
        $arListUniSender['REFERENCE_ID'][] = $v['id'];
        $arListUniSender['REFERENCE'][] = '['.$v['id'].'] '.$v['title'];       
    }    
}
//END



$arOptions = array(); 

if($arResult['ID']) {
    $arOptions['ID'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_ID_TITLE'),
        'TYPE' => 'HIDDEN',
        'SORT' => '10',
        'REFRESH' => 'N',
        'DEFAULT' => $arResult['ID']
    ); 
}


if($arResult['DATE_CREATE']) {
    $arOptions['DATE_CREATE'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_DATE_CREATE_TITLE'),
        'TYPE' => 'HIDDEN',
        'SORT' => '30',
        'REFRESH' => 'N',
        'DEFAULT' => $arResult['DATE_CREATE']
    );    
}

if($arResult['PARAM_1']) {
    $arOptions['PARAM_1'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_PARAM_1_TITLE'),
        'TYPE' => 'HIDDEN',
        'SORT' => '32',
        'REFRESH' => 'N',
        'DEFAULT' => $arResult['PARAM_1']
    );    
}

if($arResult['PARAM_2']) {
    $arOptions['PARAM_2'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_PARAM_2_TITLE'),
        'TYPE' => 'HIDDEN',
        'SORT' => '34',
        'REFRESH' => 'N',
        'DEFAULT' => $arResult['PARAM_2']
    );    
}

if($arResult['PARAM_3']) {
    $arOptions['PARAM_3'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_PARAM_3_TITLE'),
        'TYPE' => 'HIDDEN',
        'SORT' => '36',
        'REFRESH' => 'N',
        'DEFAULT' => $arResult['PARAM_3']
    );    
}

$arOptions['ACTIVE'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_ACTIVE_TITLE'),
    'TYPE' => 'CHECKBOX',
    'SORT' => '40',
    'REFRESH' => 'N',
    'DEFAULT' => 'Y'
); 
$arOptions['NAME'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_NAME_TITLE'),
    'TYPE' => 'STRING',
    'SORT' => '50',
    'REFRESH' => 'N',
    'SIZE' => '50',
);  
$arOptions['DESCRIPTION'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' =>  GetMessage($module_id.'_DESCRIPTION_TITLE'),
    'TYPE' => 'TEXT', 
    'DEFAULT' => '',
    'COLS' => '52',
    'SORT' => '60',
    'REFRESH' => 'N',
);  
  
   
$arOptions['SUNC_USER'] = array(
    'GROUP' => 'OPTION_20',
    'TITLE' => GetMessage($module_id.'_SUNC_USER_TITLE'),
    'TYPE' => 'CHECKBOX',
    'SORT' => '40',
    'REFRESH' => 'N',
);        
$arOptions['SUNC_USER_GROUP'] = array(
    'GROUP' => 'OPTION_20',
    'TITLE' => GetMessage($module_id.'_SUNC_USER_GROUP_TITLE'),
    'TYPE' => 'MSELECT', 
    'SORT' => '50',
    'REFRESH' => 'N',
    'WIDTH' => '350',
    'SIZE' => 7,
    'VALUES' => $arUserGroup,
); 


$arOptions['SUNC_USER_MESSAGE'] = array(
    'GROUP' => 'OPTION_20',
    'TITLE' => GetMessage($module_id.'_SUNC_USER_MESSAGE_TITLE'),
    'TYPE' => 'CHECKBOX',
    'SORT' => '70',
    'REFRESH' => 'N',
); 
$arOptions['SUNC_USER_EVENT'] = array(
    'GROUP' => 'OPTION_20',
    'TITLE' => GetMessage($module_id.'_SUNC_USER_EVENT_TITLE'),
    'TYPE' => 'SELECT', 
    'SORT' => '80',
    'REFRESH' => 'N',
    'WIDTH' => '350',
    'SIZE' => 7,
    'VALUES' => $arrMailingEvent,
    'NOTES' => GetMessage($module_id.'_SUNC_USER_INFO_NOTES'),  
);

      
if(CModule::IncludeModule('subscribe')) {
    $arOptions['SUNC_SUBSCRIPTION'] = array(
        'GROUP' => 'OPTION_30',
        'TITLE' => GetMessage($module_id.'_SUNC_SUBSCRIPTION_TITLE'),
        'TYPE' => 'CHECKBOX',
        'SORT' => '40',
        'REFRESH' => 'N',

    );    
    $arOptions['SUNC_SUBSCRIPTION_LIST'] = array(
        'GROUP' => 'OPTION_30',
        'TITLE' => GetMessage($module_id.'_SUNC_SUBSCRIPTION_LIST_TITLE'),
        'TYPE' => 'MSELECT', 
        'SORT' => '70',
        'REFRESH' => 'N',
        'WIDTH' => '350',
        'SIZE' => 4,
        'VALUES' => $arRubric,
        'NOTES' => GetMessage($module_id.'_SUNC_SUBSCRIPTION_INFO_NOTES'),        
    );     
}
    
 
$arOptions['SUNC_MAILCHIMP'] = array(
    'GROUP' => 'OPTION_40',
    'TITLE' => GetMessage($module_id.'_SUNC_MAILCHIMP_TITLE'),
    'TYPE' => 'CHECKBOX',
    'SORT' => '40',
    'REFRESH' => 'N',

);    
$arOptions['SUNC_MAILCHIMP_LIST'] = array(
    'GROUP' => 'OPTION_40',
    'TITLE' => GetMessage($module_id.'_SUNC_MAILCHIMP_LIST_TITLE'),
    'TYPE' => 'SELECT', 
    'SORT' => '70',
    'REFRESH' => 'N',
    'WIDTH' => '350',
    'VALUES' => $arMailchimpList,
    'NOTES' => GetMessage($module_id.'_SUNC_MAILCHIMP_INFO_NOTES'),        
);    
 
 
$arOptions['SUNC_UNISENDER'] = array(
    'GROUP' => 'OPTION_50',
    'TITLE' => GetMessage($module_id.'_SUNC_UNISENDER_TITLE'),
    'TYPE' => 'CHECKBOX',
    'SORT' => '40',
    'REFRESH' => 'N',

);    
$arOptions['SUNC_UNISENDER_LIST'] = array(
    'GROUP' => 'OPTION_50',
    'TITLE' => GetMessage($module_id.'_SUNC_UNISENDER_LIST_TITLE'),
    'TYPE' => 'SELECT', 
    'SORT' => '70',
    'REFRESH' => 'N',
    'WIDTH' => '350',
    'VALUES' => $arListUniSender,
    'NOTES' => GetMessage($module_id.'_SUNC_UNISENDER_INFO_NOTES'),        
);  
 
 
$arOptions['TABS_VIDEO_INSTRUCT'] = array(
    'GROUP' => 'OPTION_VIDEO',
    'TITLE' => GetMessage($module_id.'_OPTION_VIDEO'),
    'TYPE' => 'TABS_INFO', 
    'SORT' => '70',
    'DEFAULT' => GetMessage($module_id.'_TABS_SOTBIT_MAILING_CATEGORY_INSTRUCTION'),        
);  
 
 
if(is_array($arOptions)) { 
    foreach($arOptions  as $kopt => $vopt) {
        if($arResult[$kopt]) {
            $arOptions[$kopt]['DEFAULT'] = $arResult[$kopt];
        }   
    }  
} 
         

?> 





<?
$opt = new CMailingDetailOptions($module_id, $arTabs, $arGroups, $arOptions);
$opt->ShowHTML();



?>    




<?$tabControl = new CAdminTabControl("tabControl",$arTabsControl);?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>