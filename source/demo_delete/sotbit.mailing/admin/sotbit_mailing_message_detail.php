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


CSotbitMailingHelp::CacheConstantCheck();
$helper = new CSotbitMailingHelp();

if($_GET['ID']) {
    $ID = $_GET['ID'];
    
    $arResult = CSotbitMailingMessage::GetByID($ID);
    
    if(empty($arResult)){
        LocalRedirect("sotbit_mailing_message.php?lang=".LANG, true);
    } 
    
    
    $ListMailing = CSotbitMailingHelp::GetMailingInfo();
    $arResult['EVENT_INFO'] = $ListMailing[$arResult['ID_EVENT']];
    //���������� ���������������� ������� ������ ���������
    $event = $ListMailing[$arResult['ID_EVENT']]['EVENT_TYPE'];
    $message_id = $helper->EventTemplateCheck($event, $ListMailing[$arResult['ID_EVENT']]['NAME']);
    $arFields = array(
        'MESSEGE' => $arResult['MESSEGE'],
        'EMAIL_FROM' => $arResult['EMAIL_FROM'],
        'EMAIL_TO' => $arResult['EMAIL_TO'],
        'SUBJECT' => $arResult['SUBJECT'],
    );
    $messageText = $helper->CompileMessageText($event, $arFields, $message_id); //����� ��������� ��� ������ ���������� � ���� ����������
    
    //������� URL ����� ��� ������� ��������
    $siteUrl = $arResult["EVENT_INFO"]["SITE_URL"];
    
    //������� ������ � ������ ��������� �������, ������� ������ � ���� ����������
    $messageText = $helper->ReplaceTemplateLinks($messageText, $siteUrl);
    
    //���������� �������� ���������� ��� ������
    $InfoSend = CSotbitMailingMessage::GetByIDInfoSend($arResult['ID']);
    $InfoSend['PARAM_MESSEGE'] = unserialize($InfoSend['PARAM_MESSEGE']);
        
    $ReplaceMessageVars = array();
    $ReplaceMessageVars["MAILING_MESSAGE"] = $arResult['ID'];
    $ReplaceMessageVars["MAILING_EVENT_ID"] = $arResult['ID_EVENT'];
    
    // ���������� �������� �����������, ������� ����
    if($arResult['EVENT_INFO']['USER_AUTH']=='Y' && $InfoSend['PARAM_MESSEGE']['USER_AUTH']) {                   
        $ReplaceMessageVars["MAILING_MESSAGE"] = $ReplaceMessageVars["MAILING_MESSAGE"].'&USER_AUTH='.$InfoSend['PARAM_MESSEGE']['USER_AUTH'];          
    }
    
    //��� �������
    $ReplaceMessageVars['MAILING_UNSUBSCRIBE'] = $arResult['ID'].'||'.$arResult['ID_EVENT'];
    
    //������� URL ����� ����� (��� ���������)
    $ReplaceMessageVars['SITE_URL'] = parse_url($siteUrl, PHP_URL_HOST);
    
    //������� ���������� � ��������� ������� ����
    $messageText = $helper->ReplaceVariables($messageText, $ReplaceMessageVars);
     
} 


if($_GET['action'] == 'send') {
    CSotbitMailingTools::SendMessage(array('ID' =>$ID));   
    LocalRedirect("sotbit_mailing_message_detail.php?ID=".$ID."&lang=".LANG, true);
}




// �������� ������ � ����
// START

$arr_table_event = array(
    'EMAIL_FROM',
    'EMAIL_TO',  
    //'SUBJECT',
    //'MESSEGE',
    'BCC'
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

     if($ID) { 
        CSotbitMailingMessage::Update($ID , $arr_save);    
     } 

     LocalRedirect("sotbit_mailing_message_detail.php?ID=".$ID."&lang=".LANG, true);
     
                
    
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
        "LINK" => "/bitrix/admin/sotbit_mailing_message.php?lang=".LANG
    )
);


if($ID) {
    

    
    if($arResult['SEND'] == 'N') {
        //���������
        $aMenu[] = array(
            "TEXT" => GetMessage($module_id."_PANEL_TOP_SEND_TITLE"), 
            "LINK" => "javascript:if(confirm('".GetMessage($module_id."_PANEL_TOP_SEND_CONFORM",array("#ID#"=>$ID))."')) window.location='/bitrix/admin/sotbit_mailing_message_detail.php?action=send&ID=".$ID."&lang=".LANG."&".bitrix_sessid_get()."#tb';",
            "ICON"=>"btn_start_send"
        );         
    }
 
        
    //������� ��������
    $aMenu[] = array(
        "TEXT" => GetMessage($module_id."_PANEL_TOP_DELETE_TITLE"), 
        "LINK" => "javascript:if(confirm('".GetMessage($module_id."_PANEL_TOP_DELETE_CONFORM",array("#ID#"=>$ID))."')) window.location='/bitrix/admin/sotbit_mailing_message.php?action=delete&ID[]=".$ID."&lang=".LANG."&".bitrix_sessid_get()."';",
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
   'OPTION_20' => array('TITLE' => GetMessage($module_id.'_OPTION_20'), 'TAB' => 1),      
   'OPTION_30' => array('TITLE' => GetMessage($module_id.'_OPTION_30'), 'TAB' => 1),
            
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
$arOptions['COUNT_RUN'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_COUNT_RUN_TITLE'),
    'TYPE' => 'HIDDEN',
    'SORT' => '15',
    'REFRESH' => 'N',
    'DEFAULT' => $arResult['COUNT_RUN']
); 


$arOptions['ID'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_ID_TITLE'),
    'TYPE' => 'HIDDEN',
    'SORT' => '20',
    'REFRESH' => 'N',
    'DEFAULT' => $arResult['ID']
); 
$arOptions['DATE_CREATE'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_DATE_CREATE_TITLE'),
    'TYPE' => 'HIDDEN',
    'SORT' => '30',
    'REFRESH' => 'N',
    'DEFAULT' => $arResult['DATE_CREATE']
);
/*
$arOptions['SEND_SYSTEM'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_SEND_SYSTEM_TITLE'),
    'TYPE' => 'HIDDEN',
    'SORT' => '30',
    'REFRESH' => 'N',
    'DEFAULT' => GetMessage($module_id.'_SEND_SYSTEM_VALUE_'.$arResult['SEND_SYSTEM']), 
);
 */
$arOptions['SEND'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_SEND_TITLE'),
    'TYPE' => 'HIDDEN',
    'SORT' => '40',
    'REFRESH' => 'N',
    'DEFAULT' => GetMessage($module_id.'_VALUE_'.$arResult['SEND'])
);              
 
if($arResult['SEND'] == 'Y') {
    
    $arOptions['DATE_SEND'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_DATE_SEND_TITLE'),
        'TYPE' => 'HIDDEN',
        'SORT' => '50',
        'REFRESH' => 'N',
        'DEFAULT' =>  $arResult['DATE_SEND'] 
    );              
    
}

if($arResult['SEND_SYSTEM_MESSEGE_CODE']) {
    
    $arOptions['SEND_SYSTEM_MESSEGE_CODE'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_SEND_SYSTEM_MESSEGE_CODE_TITLE'),
        'TYPE' => 'HIDDEN',
        'SORT' => '52',
        'REFRESH' => 'N',
        'DEFAULT' =>  $arResult['SEND_SYSTEM_MESSEGE_CODE'] 
    );              
    
}


if($arResult['SEND_ERROR']) {
    
    $arOptions['SEND_ERROR'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_SEND_ERROR_TITLE'),
        'TYPE' => 'HIDDEN',
        'SORT' => '52',
        'REFRESH' => 'N',
        'DEFAULT' =>  $arResult['SEND_ERROR'] 
    );              
    
}


$arOptions['EMAIL_FROM'] = array(
    'GROUP' => 'OPTION_20',
    'TITLE' => GetMessage($module_id.'_EMAIL_FROM_TITLE'),
    'TYPE' => 'STRING',
    'SORT' => '60',
    'REFRESH' => 'N',
    'SIZE' => '60',
    'DEFAULT' => $arResult['EMAIL_FROM'] 
);              
$arOptions['EMAIL_TO'] = array(
    'GROUP' => 'OPTION_20',
    'TITLE' => GetMessage($module_id.'_EMAIL_TO_TITLE'),
    'TYPE' => 'STRING',
    'SORT' => '70',
    'REFRESH' => 'N',
    'SIZE' => '60',
    'DEFAULT' => $arResult['EMAIL_TO'] 
);  
$arOptions['BCC'] = array(
    'GROUP' => 'OPTION_20',
    'TITLE' => GetMessage($module_id.'_BCC_TITLE'),
    'TYPE' => 'STRING',
    'SORT' => '70',
    'REFRESH' => 'N',
    'SIZE' => '60',
    'DEFAULT' => $arResult['BCC'] 
); 
 
$arOptions['SUBJECT'] = array(
    'GROUP' => 'OPTION_20',
    'TITLE' => GetMessage($module_id.'_SUBJECT_TITLE'),
    'TYPE' => 'HIDDEN',
    'SORT' => '80',
    'REFRESH' => 'N',
    'SIZE' => '60',
    'DEFAULT' => $arResult['SUBJECT'] 
);  



$arOptions['MESSEGE'] = array(
    'GROUP' => 'OPTION_30',
    'TYPE' => 'HTML_SHOW',
    'SORT' => '80',
    'REFRESH' => 'N',
    'DEFAULT' => htmlspecialcharsBack($messageText) 
);  

?> 





<?

$opt = new CMailingDetailOptions($module_id, $arTabs, $arGroups, $arOptions, array());
$opt->ShowHTML();

?>    




<?$tabControl = new CAdminTabControl("tabControl",$arTabsControl);?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>