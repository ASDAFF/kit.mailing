<?
use Bitrix\Main\GroupTable;
$module_id = "sotbit.mailing";

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$module_id.'/include.php');
IncludeModuleLangFile(__FILE__);

//Получим список тем оформления шаблонов сообщений
$arMailSiteTemplate = array();
$mailSiteTemplateDb = CSiteTemplate::GetList(null, array('TYPE' => 'mail'));
while($mailSiteTemplate = $mailSiteTemplateDb->GetNext()) {
    $arMailSiteTemplate[] = $mailSiteTemplate;    
}

$arThemeId = array();
$arThemeVal = array();    
//Сформируем массивы идентификаторов и значений
    $arThemeId = array('');
    $arThemeVal = array(GetMessage('SELECT_CHANGE'));
    foreach($arMailSiteTemplate as $mailSiteTemplate) {
        $arThemeId[] = $mailSiteTemplate['ID'];
        $arThemeVal[] = '['.$mailSiteTemplate['ID'].'] ' . $mailSiteTemplate['NAME'];           
    }
    
    $arSelMailingTheme = array('REFERENCE_ID' => $arThemeId, 'REFERENCE' => $arThemeVal);   

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
   'OPTION_10' => array('TITLE' => GetMessage($module_id.'_OPTION_10'), 'TAB' => 0),    
   'OPTION_20' => array('TITLE' => GetMessage($module_id.'_OPTION_20'), 'TAB' => 0),   
   'OPTION_30' => array('TITLE' => GetMessage($module_id.'_OPTION_30'), 'TAB' => 0), 
   'OPTION_40' => array('TITLE' => GetMessage($module_id.'_OPTION_40'), 'TAB' => 0),   
   'OPTION_50' => array('TITLE' => GetMessage($module_id.'_OPTION_50'), 'TAB' => 0),   
   'OPTION_60' => array('TITLE' => GetMessage($module_id.'_OPTION_60'), 'TAB' => 0),   
   'OPTION_70' => array('TITLE' => GetMessage($module_id.'_OPTION_70'), 'TAB' => 0), 
);

$values_arr_unsender_wrap_type = array(
    'REFERENCE_ID' => array(
        'center',
        'right',
        'left',
    ),
    'REFERENCE' => array(
        GetMessage($module_id.'_UNSENDER_WRAP_TYPE_VALUE_center'),
        GetMessage($module_id.'_UNSENDER_WRAP_TYPE_VALUE_right'),
        GetMessage($module_id.'_UNSENDER_WRAP_TYPE_VALUE_left'),
    )
);


$rs = GroupTable::getList( array(
	'filter' => array(
		'ACTIVE' => 'Y',
		'!ID' => 1
	),
	'select' => array(
		'ID',
		'NAME'
	)
) );
$groups_auth = array();
while ( $group = $rs->fetch() )
{
	$groups['REFERENCE_ID'][] = $group['ID'];
	$groups['REFERENCE'][] = '[' . $group['ID'] . '] ' . $group['NAME'];
}


$arOptions = array( 

   'EMAIL_FROM' => array(
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage($module_id."_EMAIL_FROM_TITLE"),
      'TYPE' => 'STRING',  
      'SORT' => '10',
      'SIZE' => '50',
      'DEFAULT' => COption::GetOptionString('main', 'email_from')
   ),

	'AUTH_GROUPS' => array(
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage($module_id."_AUTH_GROUPS_TITLE"),
      'TYPE' => 'MSELECT',
      'SORT' => '20',
      'VALUES' => $groups,
      'DEFAULT' => serialize([6])
   ),
   
   'UNSENDER_API_KEY' => array(
      'GROUP' => 'OPTION_20',
      'TITLE' => GetMessage($module_id."_UNSENDER_API_KEY_TITLE"),
      'TYPE' => 'STRING',  
      'SORT' => '10',
      'SIZE' => '50',
      'DEFAULT' => ''
   ),    
 
 
 
   'MAILCHIMP_API_KEY' => array(
      'GROUP' => 'OPTION_30',
      'TITLE' => GetMessage($module_id."_MAILCHIMP_API_KEY_TITLE"),
      'TYPE' => 'STRING',  
      'SORT' => '10',
      'SIZE' => '50',
      'DEFAULT' => ''
   ),         
     
    
   'MANAGED_CACHE_ON' => array(
      'GROUP' => 'OPTION_40',
      'TITLE' => GetMessage($module_id."_MANAGED_CACHE_ON_TITLE"),
      'TYPE' => 'CHECKBOX',  
      'SORT' => '10',
      'DEFAULT' => 'Y'
   ),      
       
       
   'TEMPLATE_MAILING_THEME_DEF' => array(
      'GROUP' => 'OPTION_50',
      'TITLE' => GetMessage($module_id."_TEMPLATE_MAILING_THEME_DEF_TITLE"),
      "TYPE" => "SELECT",
      "VALUES" => $arSelMailingTheme,  
      'SORT' => '10',
      'SIZE' => '70',
      'DEFAULT' => 'sotbit_mailing_default_mail'
   ),          
      
      
   'MAILING_PACKAGE_COUNT' => array(
      'GROUP' => 'OPTION_60',
      'TITLE' => GetMessage($module_id."_MAILING_PACKAGE_COUNT_TITLE"),
      'TYPE' => 'INT',  
      'SORT' => '10',
      'SIZE' => '50',
      'DEFAULT' => '3000',
      'NOTES' => GetMessage($module_id."_MAILING_PACKAGE_COUNT_NOTES"),      
   ),        
  
   'MAILING_MESSAGE_SLAAP' => array(
      'GROUP' => 'OPTION_60',
      'TITLE' => GetMessage($module_id."_MAILING_MESSAGE_SLAAP_TITLE"),
      'TYPE' => 'STRING',  
      'SORT' => '20',
      'SIZE' => '50',
      'DEFAULT' => '0.001',
      'NOTES' => GetMessage($module_id."_MAILING_MESSAGE_SLAAP_NOTES"),      
   ),      
     
   'FILE_LOG' => array(
      'GROUP' => 'OPTION_70',
      'TITLE' => GetMessage($module_id."_FILE_LOG_TITLE"),
      'TYPE' => 'STRING',  
      'SORT' => '20',
      'SIZE' => '50',
      'DEFAULT' => '/var/log/maillog',
      'NOTES' => GetMessage($module_id."_FILE_LOG_NOTES"),      
   ),    
       
); 
?>
<?  
$showRightsTab = true;
$opt = new CModuleOptions($module_id, $arTabs, $arGroups, $arOptions, $showRightsTab);
$opt->ShowHTML();      
?>