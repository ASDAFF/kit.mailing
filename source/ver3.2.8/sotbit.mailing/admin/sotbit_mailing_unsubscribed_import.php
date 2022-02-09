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
if(!$CSotbitMailingTools->getDemo())
{
    CAdminMessage::ShowMessage(array("MESSAGE" => GetMessage($module_id."_DEMO_END"), "DETAILS" => GetMessage($module_id."_DEMO_END_DETAILS"), "HTML" => true));
    return false;
}


    
CSotbitMailingHelp::CacheConstantCheck();    


// �������� ������ � ����
// START

$arr_table_event = array(
    'ID_EVENT', 
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
     

     $arr_email = preg_split('/\\r\\n?|\\n|\\,/', $_REQUEST['EMAIL_TO']);


     //������� ���� ������������
     //START
     $arrMailingUnsubscribed = array();
     $DBMailingUnsubscribed = CSotbitMailingUnsubscribed::GetList(array(),array(),false,array('ID','EMAIL_TO'));
     while($resMailingUnsubscribed = $DBMailingUnsubscribed->fetch()){
        $arrMailingUnsubscribed[$resMailingUnsubscribed['EMAIL_TO']] = $resMailingUnsubscribed['ID'];       
     }
     //END
     
     
     $arr_answer = array(
        'NEW'=>0,
        'OLD'=>0,        
     ); 
     foreach($arr_email as $email){
        if($email){
            $email = trim($email);
            global $DB;   
            $arr_save = array();
            $arr_save['ID_EVENT'] = $_REQUEST['ID_EVENT'];
            $arr_save['DATE_CREATE'] = Date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL", SITE_ID)));   
            $arr_save['EMAIL_TO'] = $email;     
            if(empty($arrMailingUnsubscribed[$email])){
                CSotbitMailingUnsubscribed::Add($arr_save);   
                $arr_answer['NEW']++;               
            } else {
                $arr_answer['OLD']++; 
                $arr_save['ACTIVE'] = 'Y';        
                CSotbitMailingUnsubscribed::Update($arrMailingUnsubscribed[$email] , $arr_save);                 
            } 
        }    
     }

     global $CACHE_MANAGER;    
     $CACHE_MANAGER->ClearByTag($module_id.'_GetUnsubscribedByMailing_'.$_REQUEST['ID_EVENT']);      
     $CACHE_MANAGER->ClearByTag($module_id.'_GetUnsubscribedAllMailing');
 
 
     CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage($module_id."_RESULT_IMPORT_MESSAGE",array('#NEW#'=>$arr_answer['NEW'],'#OLD#'=>$arr_answer['OLD'])), "TYPE"=>"OK"));  
     
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
        "LINK" => "/bitrix/admin/sotbit_mailing_unsubscribed.php?lang=".LANG
    )
);


if($ID) {
    

        
    //������� ��������
    $aMenu[] = array(
        "TEXT" => GetMessage($module_id."_PANEL_TOP_DELETE_TITLE"), 
        "LINK" => "javascript:if(confirm('".GetMessage($module_id."_PANEL_TOP_DELETE_CONFORM",array("#ID#"=>$ID))."')) window.location='/bitrix/admin/sotbit_mailing_unsubscribed.php?action=delete&ID[]=".$ID."&lang=".LANG."&".bitrix_sessid_get()."';",
        "TITLE" => GetMessage($module_id."_PANEL_TOP_DELETE_ALT"),
        "ICON" => "btn_delete",
    );    
        
    
    
}





?>
<?
if($CSotbitMailingTools->ReturnDemo() == 2) CAdminMessage::ShowMessage(array("MESSAGE" => GetMessage($module_id."_DEMO"), "DETAILS" => GetMessage($module_id."_DEMO_DETAILS"), "HTML" => true));

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

 
 
 

//������� ������
//START
$values_arr_event = array();
$ListMailing = CSotbitMailingHelp::GetMailingInfo(); 
if(is_array($ListMailing)) { 
    foreach($ListMailing as $kmailing=>$vmailing) {
        if($kmailing != $arResult['ID']){
            $values_arr_event['REFERENCE_ID'][] = $kmailing; 
            $values_arr_event['REFERENCE'][] = '['.$kmailing.'] '.$vmailing['NAME'];               
        }    
    }     
}
//END 
 
 
 

$arOptions = array(); 
$arOptions['ID_EVENT'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_ID_EVENT_TITLE'),
    'TYPE' => 'SELECT',
    'SORT' => '60',
    'VALUES' => $values_arr_event,
    'DEFAULT' => '['.$arResult['ID_EVENT'].'] '.$arResult['EVENT_INFO']['NAME']
);   
      
      
         
$arOptions['EMAIL_TO'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_EMAIL_TO_TITLE'),
    'TYPE' => 'TEXT',
    'SORT' => '70',
    'COLS' => '52',
    'ROWS' => '20',
    'DEFAULT' => $arResult['EMAIL_TO'],
    'NOTES' => GetMessage($module_id.'_EMAIL_TO_NOTES'), 
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