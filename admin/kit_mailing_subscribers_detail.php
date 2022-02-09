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


if($_GET['STATUS']){
    CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage($module_id."_".$_GET['STATUS']."_SAVE"), "TYPE"=>"OK"));      
}







if($_GET['ID']) {
    $ID = $_GET['ID'];
    $arResult = CKitMailingSubscribers::GetByID($ID);
    $arResult['CATEGORIES_ID'] = CKitMailingSubscribers::GetCategoriesBySubscribers($_GET['ID']);
  
    $arResult['SOURCE'] = Getmessage($module_id."_list_title_SOURCE_VALUE_".$arResult['SOURCE']);          
 
   
       
    if(empty($arResult)){
        LocalRedirect("kit_mailing_subscribers.php?lang=".LANG, true);
    } 
    

}  




// START

$arr_table_event = array(
    'ACTIVE',
    'NAME',    
    'EMAIL_TO',
    'USER_ID',
    'CATEGORIES_ID',
    'STATIC_PAGE_SIGNED',
    'STATIC_PAGE_CAME'    
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
     
     if(empty($arr_save['CATEGORIES_ID'])){
        $arr_save['CATEGORIES_ID'] = array('0');    
     }
     
     $arr_save['ID_EVENT'] = $arResult['ID_EVENT'];
     
     if($ID) { 
        $arr_save['DATE_UPDATE'] = Date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL", SITE_ID)));         
        CKitMailingSubscribers::Update($ID, $arr_save, $SETTING);
        $STATUS = 'UPDATE';  
     } else {
         global $DB;

         $arr_save['SOURCE'] = 'ADMIN_ONE';                               
         $ANSWER = CkitMailingSubTools::AddSubscribers($arr_save,[]);
         if($ANSWER == 'ERROR_EMAIL'){
            CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage($module_id."_ERROR_EMAIL_SAVE"), "TYPE"=>"ERROR"));      
         }
         elseif($ANSWER == 'ERROR_SAVE') {
            CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage($module_id."_ERROR_SAVE"), "TYPE"=>"ERROR"));    
         } 
         elseif(is_numeric($ANSWER['ID_SUBSCRIBERS'])) {
            $ID = $ANSWER['ID_SUBSCRIBERS'];  
            $STATUS = 'ADD';  
         }  
            
   
     }
     if($ID) {
        LocalRedirect("kit_mailing_subscribers_detail.php?ID=".$ID."&STATUS=".$STATUS."&lang=".LANG, true);
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
        "LINK" => "/bitrix/admin/kit_mailing_subscribers.php?lang=".LANG
    )
);


if($ID) {
    

        

    $aMenu[] = array(
        "TEXT" => GetMessage($module_id."_PANEL_TOP_DELETE_TITLE"), 
        "LINK" => "javascript:if(confirm('".GetMessage($module_id."_PANEL_TOP_DELETE_CONFORM",array("#ID#"=>$ID))."')) window.location='/bitrix/admin/kit_mailing_subscribers.php?action=delete&ID[]=".$ID."&lang=".LANG."&".bitrix_sessid_get()."';",
        "TITLE" => GetMessage($module_id."_PANEL_TOP_DELETE_ALT"),
        "ICON" => "btn_delete",
    );    
        
    
    
}



// ������� ���������
// START
$categoriesLi = CKitMailingHelp::GetCategoriesInfo();
$categoriesList = array();
foreach($categoriesLi as $v) { 
    $categoriesList['REFERENCE_ID'][] = $v['ID'];
    $categoriesList['REFERENCE'][] = '['.$v['ID'].'] '.$v['NAME'];            
}
// END 


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
if($arResult['ID']) {
    $arOptions['ID'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_ID_TITLE'),
        'TYPE' => 'HIDDEN',
        'SORT' => '10',
        'REFRESH' => 'N',
    );    
}


if($arResult['DATE_CREATE']) {
 
    $arOptions['DATE_CREATE'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_DATE_CREATE_TITLE'),
        'TYPE' => 'HIDDEN',
        'SORT' => '30',
        'REFRESH' => 'N',
    ); 
    
}

if($arResult['DATE_UPDATE']) {
 
    $arOptions['DATE_UPDATE'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_DATE_UPDATE_TITLE'),
        'TYPE' => 'HIDDEN',
        'SORT' => '30',
        'REFRESH' => 'N',
    ); 
    
}


if($arResult['SOURCE']) {

    $arOptions['SOURCE'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_SOURCE_TITLE'),
        'TYPE' => 'HIDDEN',
        'SORT' => '10',
        'REFRESH' => 'N',
    );
    
}

if($arResult['STATIC_PAGE_SIGNED']) {

    $arOptions['STATIC_PAGE_SIGNED'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_STATIC_PAGE_SIGNED_TITLE'),
        'TYPE' => 'HIDDEN',
        'SORT' => '10',
        'REFRESH' => 'N',
    );
    
}

if($arResult['STATIC_PAGE_CAME']) { 
    
    $arOptions['STATIC_PAGE_CAME'] = array(
        'GROUP' => 'OPTION_10',
        'TITLE' => GetMessage($module_id.'_STATIC_PAGE_CAME_TITLE'),
        'TYPE' => 'HIDDEN',
        'SORT' => '20',
        'REFRESH' => 'N',
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
    'SIZE' => '60',
);             
$arOptions['EMAIL_TO'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_EMAIL_TO_TITLE'),
    'TYPE' => 'STRING',
    'SORT' => '60',
    'REFRESH' => 'N',
    'SIZE' => '60',
);  
$arOptions['USER_ID'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_USER_ID_TITLE'),
    'TYPE' => 'USER_ID',
    'SORT' => '70',
    'REFRESH' => 'N',
    'SIZE' => '60',
);  

$arOptions['CATEGORIES_ID'] = array(
    'GROUP' => 'OPTION_10',
    'TITLE' => GetMessage($module_id.'_CATEGORIES_ID_TITLE'),
    'TYPE' => 'MSELECT', 
    'SORT' => '80',
    'REFRESH' => 'N',
    'WIDTH' => '350',
    'SIZE' => 4,
    'VALUES' => $categoriesList,       
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
$opt = new CMailingDetailOptions($module_id, $arTabs, $arGroups, $arOptions, $arSettings);
$opt->ShowHTML();

?>



<?$tabControl = new CAdminTabControl("tabControl",$arTabsControl);?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>