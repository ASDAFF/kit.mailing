<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
$module_id = "kit.mailing";
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/include.php");
$arTabsControl = array();
IncludeModuleLangFile(__FILE__);
$APPLICATION->SetTitle(GetMessage($module_id.'_PAGE_TITLE', array("#ID#" => $_GET['ID'])));



$CKitMailingTools = new CKitMailingTools();

$CONS_RIGHT = $APPLICATION->GetGroupRight($module_id);   
if ($CONS_RIGHT <= "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}



require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin.php');
CModule::IncludeModule($module_id);



$arr_form_option = array(
    'mid',
    'lang',
    'sessid',
    'autosave_id',
    'refresh',
    'update',
    'save',
);




if($_REQUEST['update'] && $_REQUEST['import']){
       
    $arr_connect = explode('||', $_REQUEST['IMPORT_TYPE']); 
    $endpointList = array(
        'MODULE_ID' => $arr_connect[0],
        'CODE' => $arr_connect[1]
    );  
    $connector = \Kit\Mailing\SubConnectorManager::getConnector($endpointList);
    if($connector) {
        $connector_count = 0;                
        
     
        $connector->setFieldValues($_REQUEST['CONNECTOR_SETTING'][$connector->getModuleId()][$connector->getCode()][$connector_count]);        
        $resultImport = $connector->getDataResult();
        
        
        $arr_answer = array(
            'NEW' => 0,
            'OLD' => 0
        );
        foreach($resultImport as $itemImport){
            $itemImport['CATEGORIES_ID'] = $_REQUEST['CATEGORIES_ID'];    
            $itemImport['SOURCE'] = 'IMPORT_PAGE';    
            $answer = CkitMailingSubTools::AddSubscribers($itemImport, []);
            
            //������� �����
            if($answer=='ERROR_SAVE'){
                $arr_answer['OLD'] = $arr_answer['OLD']+1;                
            }  
            elseif($answer['ID_SUBSCRIBERS']){
                $arr_answer['NEW'] = $arr_answer['NEW']+1;
            }            
                       
        }
        
        CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage($module_id."_RESULT_IMPORT_MESSAGE",array('#NEW#'=>$arr_answer['NEW'],'#OLD#'=>$arr_answer['OLD'])), "TYPE"=>"OK")); 
        
        
    }    
           
}

    


foreach($_REQUEST as $key => $val) {
    
    if(!in_array($key,$arr_form_option)) {
        $arResult[$key] = $val;           
    }
    
}



$aMenu = array(
    array(
        "TEXT" => GetMessage($module_id.'_PANEL_TOP_BACK_TITLE'),
        "ICON" => "btn_list",
        "LINK" => "/bitrix/admin/kit_mailing_subscribers.php?lang=".LANG
    )
);

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
   'OPTION_CATEGORIES' => array('TITLE' => GetMessage($module_id.'_OPTION_CATEGORIES'), 'TAB' => 1), 
   'OPTION_TYPE' => array('TITLE' => GetMessage($module_id.'_OPTION_TYPE'), 'TAB' => 1),       
   'OPTION_SETTINGS' => array('TITLE' => GetMessage($module_id.'_OPTION_SETTINGS'), 'TAB' => 1),               
);

 


// ����������� ���� �����������
// START        
$values_arr_TYPE = array();                                                         
$connectorList = \Kit\Mailing\SubConnectorManager::getConnectorClassList();
$default_connector = '';        
foreach($connectorList as $connectorItem){      
    if(empty($default_connector)){
        $default_connector = $connectorItem['MODULE_ID'].'||'.$connectorItem['CODE'];       
    }    
    $values_arr_TYPE['REFERENCE_ID'][] = $connectorItem['MODULE_ID'].'||'.$connectorItem['CODE'];       
    $values_arr_TYPE['REFERENCE'][] = $connectorItem['NAME'];    
}
// END


// ������� ���������
// START
$categoriesLi = CKitMailingHelp::GetCategoriesInfo();
$categoriesList = array();
foreach($categoriesLi as $v) { 
    $categoriesList['REFERENCE_ID'][] = $v['ID'];
    $categoriesList['REFERENCE'][] = '['.$v['ID'].'] '.$v['NAME'];            
}
// END


$arOptions = array(); 


$arOptions['CATEGORIES_ID'] = array(
    'GROUP' => 'OPTION_CATEGORIES',
    'TITLE' => GetMessage($module_id.'_CATEGORIES_ID_TITLE'),
    'TYPE' => 'SELECT', 
    'SORT' => '80',
    'REFRESH' => 'N',
    'WIDTH' => '350',
    'SIZE' => 4,
    'VALUES' => $categoriesList,       
);  


$arOptions['IMPORT_TYPE'] = array(
    'GROUP' => 'OPTION_TYPE',
    'TITLE' => GetMessage($module_id.'_IMPORT_TYPE_TITLE'),
    'TYPE' => 'SELECT',  
    'VALUES' => $values_arr_TYPE,    
    'SORT' => '10',   
    'REFRESH' => 'Y'            
); 

 
 
if($_REQUEST['IMPORT_TYPE']){
    $default_connector = $_REQUEST['IMPORT_TYPE'];       
} 
$arOptions['CONNECTOR'] = array(
    'GROUP' => 'OPTION_SETTINGS',
    'TITLE' => GetMessage($module_id.'_TYPE_TITLE'),
    'TYPE' => 'CONNECTOR_SUB',  
    'SORT' => '10',   
    'DEFAULT' => $default_connector, 
    'REFRESH' => 'Y'                
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
$opt = new CMailingDetailOptions($module_id, $arTabs, $arGroups, $arOptions,array('page'=>'import_user'));
$opt->ShowHTML();
    
$tabControl = new CAdminTabControl("tabControl",$arTabsControl);
CJSCore::Init(array("jquery"));
?>



<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>