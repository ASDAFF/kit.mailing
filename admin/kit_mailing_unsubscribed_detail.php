<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
$module_id = "kit.mailing";
$arTabsControl = array();
IncludeModuleLangFile(__FILE__);
$APPLICATION->SetTitle(GetMessage($module_id.'_PAGE_TITLE', array("#ID#" => $_GET['ID']))); 


$CONS_RIGHT = $APPLICATION->GetGroupRight($module_id);   
if ($CONS_RIGHT <= "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}



require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin.php');
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/include.php"); 
CKitMailingHelp::CacheConstantCheck();

$MailingLi = CKitMailingHelp::GetMailingInfo();
$MailingList = array();
foreach($MailingLi as $v) {
    $MailingList[$v['ID']] = $v;    
}





if($_GET['MAILING']) {
    
    if(empty($MailingList[$_GET['MAILING']])) {
        LocalRedirect("kit_mailing_unsubscribed.php?lang=".LANG, true);
    }  
    
    $arResult['ID_EVENT'] = $_GET['MAILING'] ;
  
} 
elseif($_GET['ID']) {
    $ID = $_GET['ID'];
    
    
    
    
    $arResult = CKitMailingUnsubscribed::GetByID($ID);

    
    if(empty($arResult)){
        LocalRedirect("kit_mailing_unsubscribed.php?lang=".LANG, true);
    } 
    
    
    
}  else {
    LocalRedirect("kit_mailing_unsubscribed.php?lang=".LANG, true);
}


global $CACHE_MANAGER;    
$CACHE_MANAGER->ClearByTag($module_id.'_GetUnsubscribedByMailing_'.$arResult['ID_EVENT']);     
    
    
$arResult['EVENT_INFO'] = $MailingList[$arResult['ID_EVENT']]; 


// �������� ������ � ����
// START

$arr_table_event = array(
    'ACTIVE',
    'ID_EVENT',
    'EMAIL_TO',    
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
            

            $arr_save[$k_save] = $v_save;  
                     
        } 
            
     }
     
     $arr_save['ID_EVENT'] = $arResult['ID_EVENT'];
      

     //START
     $arrMailingUnsubscribed = array();
     $DBMailingUnsubscribed = CKitMailingUnsubscribed::GetList(array(),array(),false,array('ID','EMAIL_TO'));
     while($resMailingUnsubscribed = $DBMailingUnsubscribed->fetch()){
        $arrMailingUnsubscribed[$resMailingUnsubscribed['EMAIL_TO']] = $resMailingUnsubscribed['ID'];       
     }
     //END      
      
      
     if($ID) { 
        CKitMailingUnsubscribed::Update($ID , $arr_save);
     } else {
         global $DB ;
         $email = $arr_save['EMAIL_TO'];
         $arr_save['DATE_CREATE'] = Date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL", SITE_ID))); 
         $ID = CKitMailingUnsubscribed::Add($arr_save);
         if(empty($arrMailingUnsubscribed[$email])){
            $ID = CKitMailingUnsubscribed::Add($arr_save);
         } else {
            $arr_save['ACTIVE'] = 'Y';        
            CKitMailingUnsubscribed::Update($arrMailingUnsubscribed[$email] , $arr_save);
            $ID = $arrMailingUnsubscribed[$email];                
         }                   
     }

     LocalRedirect("kit_mailing_unsubscribed_detail.php?ID=".$ID."&lang=".LANG, true);
     
}

foreach($_REQUEST as $key => $val) {
    
    if(!in_array($key,$arr_form_option)) {
        $arResult[$key] = $val;            
    }
    
}
//END


$aMenu = array(
    array(
        "TEXT" => GetMessage($module_id.'_PANEL_TOP_BACK_TITLE'),
        "ICON" => "btn_list",
        "LINK" => "/bitrix/admin/kit_mailing_unsubscribed.php?lang=".LANG
    )
);


if($ID) {
    

    $aMenu[] = array(
        "TEXT" => GetMessage($module_id."_PANEL_TOP_DELETE_TITLE"), 
        "LINK" => "javascript:if(confirm('".GetMessage($module_id."_PANEL_TOP_DELETE_CONFORM",array("#ID#"=>$ID))."')) window.location='/bitrix/admin/kit_mailing_unsubscribed.php?action=delete&ID[]=".$ID."&lang=".LANG."&".bitrix_sessid_get()."';",
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
);
                               
$arGroups = array(
   'OPTION_10' => array('TITLE' => GetMessage($module_id.'_OPTION_10'), 'TAB' => 1),   
            
);

 
 

$arOptions = array(); 
$arOptions['ID_EVENT'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_ID_EVENT_TITLE'),
    'TYPE' => 'HIDDEN',
    'SORT' => '10',
    'REFRESH' => 'N',
    'DEFAULT' => '['.$arResult['ID_EVENT'].'] '.$arResult['EVENT_INFO']['NAME']
);
$arOptions['ID_MESSEGE'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_ID_MESSEGE_TITLE'),
    'TYPE' => 'HIDDEN',
    'SORT' => '10',
    'REFRESH' => 'N',
    'DEFAULT' => $arResult['ID_MESSEGE']
);
$arOptions['DATE_CREATE'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_DATE_CREATE_TITLE'),
    'TYPE' => 'HIDDEN',
    'SORT' => '30',
    'REFRESH' => 'N',
    'DEFAULT' => $arResult['DATE_CREATE']
);
$arOptions['ACTIVE'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_ACTIVE_TITLE'),
    'TYPE' => 'CHECKBOX',
    'SORT' => '40',
    'REFRESH' => 'N',
    'DEFAULT' => 'Y'
);           
$arOptions['EMAIL_TO'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_EMAIL_TO_TITLE'),
    'TYPE' => 'STRING',
    'SORT' => '70',
    'REFRESH' => 'N',
    'SIZE' => '60',
    'DEFAULT' => $arResult['EMAIL_TO'] 
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
$opt = new CMailingDetailOptions($module_id, $arTabs, $arGroups, $arOptions,[]);
$opt->ShowHTML();



?>    




<?$tabControl = new CAdminTabControl("tabControl",$arTabsControl);?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>