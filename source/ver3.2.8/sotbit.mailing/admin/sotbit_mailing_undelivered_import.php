<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
$module_id = "sotbit.mailing";
$arTabsControl = array();
IncludeModuleLangFile(__FILE__);
$APPLICATION->SetTitle(GetMessage($module_id.'_PAGE_TITLE', array("#ID#" => $_GET['ID']))); 


$CONS_RIGHT = $APPLICATION->GetGroupRight($module_id);   
if ($CONS_RIGHT <= "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}



require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin.php');
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/include.php"); 
CSotbitMailingHelp::CacheConstantCheck();




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
    

     $arr_answer = array(
        'NEW'=>0,
        'OLD'=>0,
        'ERROR' => GetMessage($module_id."_RESULT_IMPORT_MESSAGE_ERROR")        
     );     
    
     $arr_email = array();

     if($_REQUEST['TYPE']=='FILE_LOG' && $_REQUEST['FILE_LOG']){
        $parseLogInfo = CSotbitMailingHelp::ParseLogMail($_REQUEST['FILE_LOG']);   
                              
        $email_error_code = array();
        foreach($parseLogInfo['EMAIL_ERROR'] as $code => $arr_EMAIL_ERROR){
            $arr_answer['ERROR'] .= GetMessage($module_id."_RESULT_IMPORT_MESSAGE_CODE").' '.$code.': '.count($arr_EMAIL_ERROR);
            $arr_answer['ERROR'] .= '
            ';           
            foreach($arr_EMAIL_ERROR as $email){
                if(empty($email_error_code[$email])){
                    $arr_email[] = $email;
                    $email_error_code[$email] = $code;                                            
                }
            }  
        }
     }//���� �� �������
      else {
        $arr_email = preg_split('/\\r\\n?|\\n|\\,/', $_REQUEST['EMAIL_TO']);
        //��������� email
        foreach($arr_email as $key=>$email){
            if($email){
                $arr_email[$key] = trim($email);                
            } else{
                unset($arr_email[$key]);
            }    
        }         
     }
    



     

     //START
     $arrMailingUndelivered = array();
     $DBMailingUndelivered = CSotbitMailingUndelivered::GetList(array(),array(),false,array('ID','EMAIL_TO'));
     while($resMailingUndelivered = $DBMailingUndelivered->fetch()){
        $arrMailingUndelivered[$resMailingUndelivered['EMAIL_TO']] = $resMailingUndelivered['ID'];       
     }
     //END


     //START
     $arr_save = array();
     $arr_save['ACTIVE'] = 'Y';     

     $arr_save['ID_EVENT'] = $_REQUEST['ID_EVENT'];

     if($_REQUEST['SPAM']){
        $arr_save['SPAM'] = $_REQUEST['SPAM'];    
     }

     if($_REQUEST['ERROR_CODE']){
        $arr_save['ERROR_CODE'] = $_REQUEST['ERROR_CODE'];    
     }            

     global $DB;   
     $arr_save['DATE_CREATE'] = Date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL", SITE_ID)));      
     //END
     

     
     

     foreach($arr_email as $key => $email){

        if($_REQUEST['TYPE']=='FILE_LOG' && $email_error_code[$email]){
            $arr_save['ERROR_CODE'] = $email_error_code[$email];       
        }           

        $arr_save['EMAIL_TO'] = $email;  

        if(empty($arrMailingUndelivered[$email])){
            CSotbitMailingUndelivered::Add($arr_save);   
            $arr_answer['NEW']++;               
        } else {
            $arr_answer['OLD']++;    
            CSotbitMailingUndelivered::Update($arrMailingUndelivered[$email] , $arr_save);                 
        } 
     }
 
    global $CACHE_MANAGER;    
    $CACHE_MANAGER->ClearByTag($module_id.'_GetUndeliveredMailing');          
 
    CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage(
        $module_id."_RESULT_IMPORT_MESSAGE",
        array(
            '#NEW#'=>$arr_answer['NEW'],
            '#OLD#'=>$arr_answer['OLD'],
            '#ERROR#'=>$arr_answer['ERROR']
        )), 
        "TYPE"=>"OK"
    ));  
     
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
        "LINK" => "/bitrix/admin/sotbit_mailing_undelivered.php?lang=".LANG
    )
);


if($ID) {
    

        

    $aMenu[] = array(
        "TEXT" => GetMessage($module_id."_PANEL_TOP_DELETE_TITLE"), 
        "LINK" => "javascript:if(confirm('".GetMessage($module_id."_PANEL_TOP_DELETE_CONFORM",array("#ID#"=>$ID))."')) window.location='/bitrix/admin/sotbit_mailing_undelivered.php?action=delete&ID[]=".$ID."&lang=".LANG."&".bitrix_sessid_get()."';",
        "TITLE" => GetMessage($module_id."_PANEL_TOP_DELETE_ALT"),
        "ICON" => "btn_delete",
    );    
        
    
    
}





?>
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
   'OPTION_TYPE' => array('TITLE' => GetMessage($module_id.'_OPTION_TYPE'), 'TAB' => 1), 
   'OPTION_10' => array('TITLE' => GetMessage($module_id.'_OPTION_10'), 'TAB' => 1),              
);

 
 
 

//������� ������
//START
//��������
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

$values_type_mode = array(
    'REFERENCE_ID' => array(
        'EMPTY',
        'MANUAL',
        'FILE_LOG',
    ),
    'REFERENCE' => array(
        GetMessage($module_id.'_TYPE_VALUE_EMPTY'),
        GetMessage($module_id.'_TYPE_VALUE_MANUAL'),
        GetMessage($module_id.'_TYPE_VALUE_FILE_LOG'),
    )
);

//END 
 
 
 

$arOptions = array(); 

$arOptions['TYPE'] = array( 
      'GROUP' => 'OPTION_TYPE',
      'TITLE' => GetMessage($module_id.'_TYPE_TITLE'),
      'SORT' => '5',
      'TYPE' => 'SELECT',
      'VALUES' => $values_type_mode,
      'DEFAULT' => 'EMPTY',
      'REFRESH' => 'Y',

);


if($arResult['TYPE']=='MANUAL' || $_REQUEST['TYPE']=='MANUAL'){  

    $arOptions['ID_EVENT'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_ID_EVENT_TITLE'),
        'TYPE' => 'SELECT',
        'SORT' => '10',
        'VALUES' => $values_arr_event,
    );     
    
    
    $arOptions['ERROR_CODE'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_ERROR_CODE_TITLE'),
        'TYPE' => 'STRING',
        'SORT' => '15',
    );      
    $arOptions['SPAM'] = array(
          'GROUP' => 'OPTION_10',
          'TITLE' => GetMessage($module_id.'_SPAM_TITLE'),
          'TYPE' => 'CHECKBOX',
          'SORT' => '20',
          'REFRESH' => 'N',
    );               
    $arOptions['EMAIL_TO'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_EMAIL_TO_TITLE'),
        'TYPE' => 'TEXT',
        'SORT' => '70',
        'COLS' => '52',
        'ROWS' => '20',
        'NOTES' => GetMessage($module_id.'_EMAIL_TO_NOTES'), 
    ); 
        
}
 
if($arResult['TYPE']=='FILE_LOG' || $_REQUEST['TYPE']=='FILE_LOG'){  

    $arOptions['ID_EVENT'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_ID_EVENT_TITLE'),
        'TYPE' => 'SELECT',
        'SORT' => '10',
        'VALUES' => $values_arr_event,
    );     
    $arOptions['SPAM'] = array(
          'GROUP' => 'OPTION_10',
          'TITLE' => GetMessage($module_id.'_SPAM_TITLE'),
          'TYPE' => 'CHECKBOX',
          'SORT' => '20',
          'REFRESH' => 'N',
    );     
    $arOptions['FILE_LOG'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_FILE_LOG_TITLE'),
        'TYPE' => 'STRING',
        'SORT' => '15',
        'DEFAULT' => COption::GetOptionString('sotbit.mailing','FILE_LOG','/var/log/maillog')
    );    
           
}  


if(is_array($arOptions)) { 
    foreach($arOptions  as $kopt => $vopt) {
        if($arResult[$kopt]) {
            $arOptions[$kopt]['DEFAULT'] = $arResult[$kopt];
        }   
    }  
}



/*
$se = CSotbitMailingHelp::ParseLogMail();
printr($se);  
*/
?> 





<?
$opt = new CMailingDetailOptions($module_id, $arTabs, $arGroups, $arOptions,[]);
$opt->ShowHTML();


CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage($module_id."_ERROR_CODE_ANSWER",array()), "TYPE"=>"OK"));
CJSCore::Init(array("jquery")); 
?>    




<?$tabControl = new CAdminTabControl("tabControl",$arTabsControl);?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>