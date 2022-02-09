<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
$module_id = "sotbit.mailing";

$arTabs = array();
IncludeModuleLangFile(__FILE__);
$APPLICATION->SetTitle(GetMessage($module_id.'_PAGE_TITLE', array("#ID#" => $_GET['ID'])));

// �������� ����
$CONS_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($CONS_RIGHT <= "D")
{
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin.php');
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/include.php");



$CSotbitMailingTools = new CSotbitMailingTools();


CSotbitMailingHelp::CacheConstantCheck();
$helper = new CSotbitMailingHelp();

$parentID = 0;

if(isset($_REQUEST["parent"]) && $_REQUEST["parent"])
{
    $parentID = $_REQUEST["parent"];
}

//START
if($_REQUEST['update'] && $_REQUEST['save'])
{
    //die();
    $_REQUEST['TEMPLATE_PARAMS_MESSAGE'] = $helper->ReplaceTemplateLinks($_REQUEST['TEMPLATE_PARAMS_MESSAGE'], $_REQUEST['SITE_URL']);
}
//END

// START
if(is_array($_REQUEST))
{
    foreach($_REQUEST as $k => $v)
    {
        if(stripos($k, 'TEMPLATE_PARAMS_') !== false)
        {
            $code = str_replace("TEMPLATE_PARAMS_", "", $k);
            $_REQUEST['TEMPLATE_PARAMS'][$code] = $v;
            unset($_REQUEST[$k]);
        }

        if(stripos($k, 'EVENT_PARAMS_') !== false)
        {
            $code = str_replace("EVENT_PARAMS_", "", $k);
            $_REQUEST['EVENT_PARAMS'][$code] = $v;
        }
    }
}
// END

//START
$arrTempl = array();
$templComponent = CComponentUtil::GetTemplatesList('sotbit:sotbit.mailing.logic', true);

if(is_array($templComponent))
{
    foreach($templComponent as $valTemp)
    {
        if($valTemp['NAME'] != '.default')
        {
            if($valTemp['TEMPLATE'])
            {
                $valTemp["TITLE_CUSTOM"] = $valTemp["TITLE"]. ' ['.GetMessage($module_id.'_TEMPLATE_TYPE_MY').' - '.$valTemp['NAME'].']';
            }
            else
            {
                $valTemp["TITLE_CUSTOM"] = $valTemp["TITLE"]. ' ['.GetMessage($module_id.'_TEMPLATE_TYPE_SYSTEM').' - '.$valTemp['NAME'].']';
            }
            
            $arrTempl[$valTemp['NAME']] = $valTemp;
        }
    }
}
//printr($arrTempl);
//END



if($_GET['TEMPLATE'] && empty($arrTempl[$_GET['TEMPLATE']]))
{
    LocalRedirect("sotbit_mailing_list.php?lang=".LANG, true);
}


if($_GET['ID'])
{
    $ID = $_GET['ID'];
    
    $ListMailing = CSotbitMailingHelp::GetMailingInfo();
    if(empty($ListMailing[$ID]))
    {
        LocalRedirect("sotbit_mailing_list.php?lang=".LANG, true);
    }
}



if($_GET['TEMPLATE'])
{
    $arResult['TEMPLATE'] = $_REQUEST['TEMPLATE'];
    
    $arResult['NAME'] = $arrTempl[$_REQUEST['TEMPLATE']]['TITLE'];
    $arResult['DESCRIPTION'] = $arrTempl[$_REQUEST['TEMPLATE']]['DESCRIPTION'];
    $arResult['SITE_URL'] = 'http://'.$_SERVER['SERVER_NAME'];
}

$arResult['ID'] = IntVal($_REQUEST['ID']);

// START
$arr_table_event = array(
    'ID',
    'ACTIVE',
    'NAME',
    'DESCRIPTION',
    'SORT',
    'MODE',
    'SITE_URL',
    'USER_AUTH',
    'TEMPLATE',
    'TEMPLATE_PARAMS',
    'EVENT_PARAMS',
    'DATE_LAST_RUN',
    'AGENT_ID',
    'AGENT_TIME_START',
    'AGENT_TIME_END',
    'EVENT_TYPE',
    'EVENT_SEND_SYSTEM',
    'EXCLUDE_HOUR_AGO',
    'EXCLUDE_UNSUBSCRIBED_USER',
    'EXCLUDE_UNSUBSCRIBED_USER_MORE',
    'CATEGORY_ID',
    //'EVENT_PARAMS_AGENT_AROUND'
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

if($_REQUEST['update'] && $_REQUEST['save'])
{
    //printr($_REQUEST);
    //die();

    unset($_REQUEST['DATE_LAST_RUN']);
    
    if(empty($_REQUEST['EXCLUDE_HOUR_AGO']))
    {
        $_REQUEST['EXCLUDE_HOUR_AGO'] = 0;
    }
    else
    {
        $_REQUEST['EXCLUDE_HOUR_AGO'] = IntVal($_REQUEST['EXCLUDE_HOUR_AGO']);
    }

    $arr_save = array();
    foreach($_REQUEST as $k_save =>  $v_save)
    {
        if(in_array($k_save ,$arr_table_event))
        {
            if($k_save == 'ID')
            {
                $arResult['ID'] = $v_save;
                continue;
            }
            if($k_save == 'TEMPLATE_PARAMS')
            {
                $v_save = serialize($v_save);
            }
            if($k_save == 'EVENT_PARAMS')
            {
                $v_save = serialize($v_save);
            }
            if($k_save == 'EXCLUDE_UNSUBSCRIBED_USER_MORE')
            {
                $v_save = serialize($v_save);    
            }
            
            $arr_save[$k_save] = $v_save;           
        }
    }

    //���� ����� ������� ��������� �����������
    if($arr_save['AGENT_TIME_START'] > $arr_save['AGENT_TIME_END'])
    {
        $AGENT_TIME_START = $arr_save['AGENT_TIME_START'];
        $arr_save['AGENT_TIME_START'] = $arr_save['AGENT_TIME_END'];
        $arr_save['AGENT_TIME_END'] = $AGENT_TIME_START;
    }

    //���������� ������������ ��������
    if($arResult['ID'])
    {
        CSotbitMailingEvent::Update($arResult['ID'] , $arr_save);

        // �������� ������ � ��� ���������
        // START
        $arrEventInfo = CSotbitMailingEvent::GetByID($arResult['ID']);
        $arrEventInfo['TEMPLATE_PARAMS'] = unserialize($arrEventInfo['TEMPLATE_PARAMS']);
        $resEventTemplate = CSotbitMailingMessageTemplate::GetList(array('COUNT_START' => 'DESC'), array('ID_EVENT' => $arResult['ID'], 'ARCHIVE' => 'N'), false, array());
        while($arrEventTemplate = $resEventTemplate->Fetch())
        {
            //���� ������ �� ���� ��������
            $arrEventTemplate_last = $arrEventTemplate;
            if($arrEventTemplate['COUNT_START'] == $arrEventInfo['COUNT_RUN'])
            {
                CSotbitMailingMessageTemplate::Update($arrEventTemplate['ID'], array('TEMPLATE' => $arrEventInfo['TEMPLATE_PARAMS']['MESSAGE']));   
                break;         
            } 
            elseif($arrEventTemplate['COUNT_START'] != $arrEventInfo['COUNT_RUN'] && $arrEventInfo['TEMPLATE_PARAMS']['MESSAGE'] != $arrEventTemplate['TEMPLATE'])
            {
                CSotbitMailingMessageTemplate::Update($arrEventTemplate['ID'],array('ARCHIVE'=>'Y','COUNT_END'=>$arrEventInfo['COUNT_RUN']));
                $arrAddTemplate = array(
                    'ID_EVENT' => $arrEventInfo['ID'],
                    'COUNT_START' => $arrEventInfo['COUNT_RUN'],
                    'COUNT_END' => $arrEventInfo['COUNT_RUN'], 
                    'TEMPLATE' => $arrEventInfo['TEMPLATE_PARAMS']['MESSAGE'],
                    'ARCHIVE' => 'N'
                );
                CSotbitMailingMessageTemplate::Add($arrAddTemplate);
                break;
            }
        }

        if(empty($arrEventTemplate_last))
        {
            // ������� ������ ��������
            // START
            $arr_save['ID'] = $arResult['ID'];
            $arrEvent = $arr_save;
            $arrEvent['TEMPLATE_PARAMS'] = unserialize($arrEvent['TEMPLATE_PARAMS']);
            $arrAddTemplate = array(
                'ID_EVENT' => $arrEvent['ID'],
                'COUNT_START' => '0',
                'COUNT_END' => $arrEventInfo['COUNT_RUN'],
                'TEMPLATE' => $arrEvent['TEMPLATE_PARAMS']['MESSAGE'],
                'ARCHIVE' => 'N'
            );
            CSotbitMailingMessageTemplate::Add($arrAddTemplate); 
            // END                 
        }
        // END
        
        //���������� ������� � ������� Bitrix
        //�������� ��������� ��������� ������� ��� ����������
        
        //�������� ������������� ��������� �������, ���������� � ������ ���������
        $eventMessageId =  $helper->EventTemplateCheck($arrEventInfo['EVENT_TYPE'], $arrEventInfo['NAME']);
        //���������� ������ ��� ���������� ��������� �������
        $templateParams = unserialize($arr_save['TEMPLATE_PARAMS']);
        $arEventMessageFields = Array(
            "SITE_TEMPLATE_ID" => $templateParams['SITE_TEMPLATE_ID'],
        );
        //��������� �������� ������
        if(!empty($eventMessageId))
        {
            $helper->UpdateEventMessage($eventMessageId, $arEventMessageFields);
        }
    }
    //���������� ����� ��������
    else
    {
        $arr_save['EVENT_SEND_SYSTEM'] = 'BITRIX';
        
        //��� ��������� ������ ��������� �������, ������ �������� ������ �� ���������
        //$arr_save['EVENT_TYPE'] = CSotbitMailingHelp::EventMessageCheck();
        $arr_save['EVENT_TYPE'] = '';

        $arResult['ID'] = CSotbitMailingEvent::Add($arr_save);
        
        //����� ������� ����� ��� ��������� �������, ����������� ��� ���������� �������
        $eventType =  $helper->CreateEventType($arResult['ID'], $arr_save['NAME']);
        
        //������� ������ ��������� � ����������� � ���� ��������� �������
        
        $templateParams = unserialize($arr_save['TEMPLATE_PARAMS']);
        $arTemplateFields = array("SITE_TEMPLATE_ID" => $templateParams['SITE_TEMPLATE_ID']);
        $mesID = $helper->CreateEventMessage($eventType, $arTemplateFields);
        
        //�������� ��� ��������� ������� � ��������
        CSotbitMailingEvent::Update($arResult['ID'], array('EVENT_TYPE' => $eventType));
        
        // ������� ������ ��������
        // START
        $arr_save['ID'] = $arResult['ID'];
        $arrEvent = $arr_save;  
        $arrEvent['TEMPLATE_PARAMS'] = unserialize($arrEvent['TEMPLATE_PARAMS']);
        $arrAddTemplate = array(
            'ID_EVENT' => $arrEvent['ID'],
            'COUNT_START' => '0',
            'COUNT_END' => '0',
            'TEMPLATE' => $arrEvent['TEMPLATE_PARAMS']['MESSAGE'],
            'ARCHIVE' => 'N'
        );
        CSotbitMailingMessageTemplate::Add($arrAddTemplate); 
        // END
    }

    //��������� ������
    //START
    if(empty($_REQUEST['AGENT_ID']) && $arResult['ID'])
    {
        $arResult['AGENT_ID'] = CAgent::AddAgent('CSotbitMailingTools::AgentStartTemplate('.$arResult['ID'].');', "sotbit.mailing", "N", '3600', "", "N");
        $_REQUEST['AGENT_ID'] = $arResult['AGENT_ID'];
        CSotbitMailingEvent::Update($arResult['ID'], array('AGENT_ID' => $arResult['AGENT_ID']));
    }

    if($_REQUEST['AGENT_ID'] && $arResult['ID'])
    {
        $arrAgentUpdate = array();
        
        $arrAgentUpdate['NAME'] = 'CSotbitMailingTools::AgentStartTemplate('.$arResult['ID'].');';
        $arrAgentUpdate['MODULE_ID'] = 'sotbit.mailing';

        if($_REQUEST['AGENT_ACTIVE'])
        {
            $arrAgentUpdate['ACTIVE'] = $_REQUEST['AGENT_ACTIVE'];
        }

        if($_REQUEST['AGENT_NEXT_EXEC'])
        {
            $arrAgentUpdate['NEXT_EXEC']  = $_REQUEST['AGENT_NEXT_EXEC'];
        }

        if($_REQUEST['AGENT_INTERVAL'])
        {
            $arrAgentUpdate['AGENT_INTERVAL'] = $AGENT_INTERVAL = IntVal($_REQUEST['AGENT_INTERVAL']);
        }
        else
        {
            $arrAgentUpdate['AGENT_INTERVAL'] = 3600;
        }
        
        CAgent::Update($_REQUEST['AGENT_ID'], $arrAgentUpdate);
    }
    //END



    // ������� ��� ��� �������
    // START
    global $CACHE_MANAGER;
    $CACHE_MANAGER->ClearByTag($module_id.'_GetMailingInfo');
    $CACHE_MANAGER->ClearByTag($module_id.'_GetEventTemplate_'.$arResult['ID']);
    // END
    
    $parentID = $arr_save['CATEGORY_ID'];
    
    LocalRedirect("sotbit_mailing_detail.php?parent=".$parentID."&ID=".$arResult['ID']."&SOTBIT_MAILING_DETAIL=Y&lang=".LANG."&tabControl_active_tab=".$_REQUEST['tabControl_active_tab'].'#form', true);
}
// END



//������� ������ � ��������
//START
if($arResult['ID'] > 0)
{
    if($res = CSotbitMailingEvent::GetByID($arResult['ID']))
    {
        $res['TEMPLATE_PARAMS'] = unserialize($res['TEMPLATE_PARAMS']);
        $res['EXCLUDE_UNSUBSCRIBED_USER_MORE'] = unserialize($res['EXCLUDE_UNSUBSCRIBED_USER_MORE']);
        
        //������ � �������� ��������
        //START

        //�������� ��������� ������� ������ �� ���������
        /*$EVENT_TYPE = CSotbitMailingHelp::EventMessageCheck();   
        if($res['EVENT_TYPE'] != $EVENT_TYPE) {
            CSotbitMailingEvent::Update($res['ID'] , array('EVENT_TYPE' => $EVENT_TYPE));                   
        } */
        
        //����� ��������� ������������� ��������� ������� � ��������, ��������� � ���, ���� �� ���, �� ��� ���������.
        $eventMessageId = $helper->EventTemplateCheck($res['EVENT_TYPE'], $res['NAME']);
        //�������� ������ ��������� �������
        $rsEventMessage = CEventMessage::GetByID($eventMessageId);
        $arEventMessage = $rsEventMessage->Fetch();
        //END


        //������ � �������� UniSender
        //START
        if($res['EVENT_SEND_SYSTEM'] == 'UNISENDER')
        {
            //���������� ��������
            //START
            $getListUniSender = CSotbitMailingHelp::QueryUniSender('getLists'); 
            $arrListUniSender = array();
            foreach($getListUniSender['result'] as $k=>$v) {
                $arrListUniSender[$v['id']] =  $v['title'];    
            }
            // ���� ��� ��������� ��������, �������� �����
            if(empty($arrListUniSender[$res['EVENT_SEND_SYSTEM_CODE']])){
                
                
                $createListUniSender = CSotbitMailingHelp::QueryUniSender('createList', array('title'=>$res['NAME'])); 
                if($createListUniSender['result']['id']){
                     CSotbitMailingEvent::Update($res['ID'] , array('EVENT_SEND_SYSTEM_CODE' => $createListUniSender['result']['id']));                           
                }             
            }
            //���� ���������� ��������, �������
            if($arrListUniSender[$res['EVENT_SEND_SYSTEM_CODE']] && $arrListUniSender[$res['EVENT_SEND_SYSTEM_CODE']] != $res['NAME']) {
                $updateListUniSender = CSotbitMailingHelp::QueryUniSender('updateList', array('list_id'=>$res['EVENT_SEND_SYSTEM_CODE'],'title'=>$res['NAME']));             
            }    
            //END
        }
        //END


        // ������ � �������
        // START
        if($res['AGENT_ID'])
        {
            $resAgent = CAgent::GetList(array(), array('ID' => $res['AGENT_ID']));
            $arrAgent = $resAgent->Fetch();
            
            if($arrAgent)
            {
                $res['AGENT_ACTIVE'] = $arrAgent['ACTIVE'];
                $res['AGENT_LAST_EXEC'] = $arrAgent['LAST_EXEC'];
                $res['AGENT_NEXT_EXEC'] = $arrAgent['NEXT_EXEC'];
                $res['AGENT_INTERVAL'] = $arrAgent['AGENT_INTERVAL'];
            }
            else
            {
                $res['AGENT_ID'] = CAgent::AddAgent('CSotbitMailingTools::AgentStartTemplate('.$arResult['ID'].');', "sotbit.mailing", "N", '3600', "", "N");
                CSotbitMailingEvent::Update($res['ID'], array('AGENT_ID' => $res['AGENT_ID']));
            }
        }
        // END

        $arResult = $res;
    }
}
elseif($_GET['ID_COPY']) // ���� �������� ���������� �����������
{
    if($res = CSotbitMailingEvent::GetByID($_GET['ID_COPY']))
    {
        $res['TEMPLATE_PARAMS'] = unserialize($res['TEMPLATE_PARAMS']);
        $res['EXCLUDE_UNSUBSCRIBED_USER_MORE'] = unserialize($res['EXCLUDE_UNSUBSCRIBED_USER_MORE']);
        
        // ������ � �������
        // START
        if($res['AGENT_ID'])
        {
            $resAgent = CAgent::GetList(array(), array('ID' => $res['AGENT_ID']));
            $arrAgent = $resAgent->Fetch();
            
            if($arrAgent)
            {
                $res['AGENT_LAST_EXEC'] = $arrAgent['LAST_EXEC'];
                $res['AGENT_NEXT_EXEC'] = $arrAgent['NEXT_EXEC'];
                $res['AGENT_INTERVAL'] = $arrAgent['AGENT_INTERVAL'];
            }
        }
        // END

        unset($res['ID']);
        unset($res['AGENT_ID']);
        unset($res['MODE']);

        $arResult = $res;
    }
}



if(is_array($_REQUEST))
{
    foreach($_REQUEST as $key => $val)
    {
        if(!in_array($key,$arr_form_option))
        {
            $arResult[$key] = $val;
        }
    }
}
//END


$aMenu = array(
    array(
        "TEXT" => GetMessage($module_id.'_PANEL_TOP_BACK_TITLE'),
        "ICON" => "btn_list",
        //"LINK" => "/bitrix/admin/sotbit_mailing_list.php?lang=".LANG.GetFilterParams("filter_")
        "LINK" => "/bitrix/admin/sotbit_mailing_list.php?parent=".$parentID."&lang=".LANG.GetFilterParams("filter_")
    )
);


if($ID)
{
    // �������� ����� ��������
    $arDDMenu = array();
    if(is_array($arrTempl))
    {
        foreach($arrTempl as $kres => $arRes)
        {
             $arDDMenu[] = array(
                "TEXT" => $arRes['TITLE_CUSTOM'],
                "TITLE" => '['.$arRes["NAME"].'] '.$arRes['DESCRIPTION'],
                "ACTION" => "window.location = 'sotbit_mailing_detail.php?parent=".$parentID."&lang=".LANG."&TEMPLATE=".$arRes["NAME"]."';"
            );
        }
    }

    $aMenu[] = array(
        "TEXT" => GetMessage($module_id."_PANEL_TOP_ADD_NEW_TITLE"),
        "ICON" => "btn_new",
        "TITLE" => GetMessage($module_id."_PANEL_TOP_ADD_NEW_ALT"),
        "MENU" => $arDDMenu
    );

    $excludeTemplate = [
        'user_register_mailing',
        'user_subscribe'
    ];

    if($arResult['ACTIVE'] == 'Y')
    {
        $arrProgress = CSotbitMailingHelp::ProgressFileGetArray($arResult['ID'], $arResult['COUNT_RUN']);

        //��������� ��������
        if(
                $arResult['MAILING_WORK'] !== 'Y' &&
                $arResult['AGENT_ACTIVE'] !== 'Y' &&
                !in_array($arResult['TEMPLATE'], $excludeTemplate))
        {
            $aMenu[] = array(
                "TEXT" => GetMessage($module_id."_PANEL_TOP_START_TITLE"),
                "ICON"=>"btn_start_send"
            );

            /*$aMenu[] = array(
                "TEXT" => GetMessage($module_id."_PANEL_TOP_STOP_TITLE"),
                "ICON"=>"btn_stop_send"
            );*/
        }
    }
    
    //������� ��������
    $aMenu[] = array(
        "TEXT" => GetMessage($module_id."_PANEL_TOP_DELETE_TITLE"),
        "LINK" => "javascript:if(confirm('".GetMessage($module_id."_PANEL_TOP_DELETE_CONFORM",array("#ID#"=>$ID))."')) window.location='/bitrix/admin/sotbit_mailing_list.php?parent=".$parentID."&action=delete&ID[]=R".$ID."&lang=".LANG."&".bitrix_sessid_get()."#tb';",
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
        'TAB' => GetMessage($module_id.'_edit20'),
        'ICON' => '',
        'TITLE' => GetMessage($module_id.'_edit20'),
        'SORT' => '30'
    ),
    array(
        'DIV' => 'edit30',
        'TAB' => GetMessage($module_id.'_edit30'),
        'ICON' => '',
        'TITLE' => GetMessage($module_id.'_edit30'),
        'SORT' => '20'
    ),
);

$arGroups = array(
   'OPTION_10' => array('TITLE' => GetMessage($module_id.'_OPTION_10'), 'TAB' => 1), 
   'OPTION_40' => array('TITLE' => GetMessage($module_id.'_OPTION_40'), 'TAB' => 2),  
   'OPTION_50' => array('TITLE' => GetMessage($module_id.'_OPTION_50'), 'TAB' => 2),  
   'OPTION_60' => array('TITLE' => GetMessage($module_id.'_OPTION_60'), 'TAB' => 2),    
   'OPTION_70' => array('TITLE' => GetMessage($module_id.'_OPTION_70'), 'TAB' => 2),        
   'OPTION_20' => array('TITLE' => GetMessage($module_id.'_OPTION_10'), 'TAB' => 3),                   
);

$values_arr_MODE = array(
    'REFERENCE_ID' => array(
        'TEST',
        'WORK'
    ),
    'REFERENCE' => array(
        GetMessage($module_id.'_MODE_VALUE_TEST'),
        GetMessage($module_id.'_MODE_VALUE_WORK')        
    )
);

$values_arr_agent_interval = array(
    'REFERENCE_ID' => array(
        '300',
        '900',
        '1800',
        '3600',
        '7200',
        '14400',
        '28800',
        '43200',
        '86400',
        '172800',
        '259200',
        '345600',
        '604800',
        '1209600',
        '2678400'
    ),
    'REFERENCE' => array(
        GetMessage($module_id."_AGENT_INTERVAL_SELECT_300"),
        GetMessage($module_id.'_AGENT_INTERVAL_SELECT_900'),
        GetMessage($module_id.'_AGENT_INTERVAL_SELECT_1800'),  
        GetMessage($module_id."_AGENT_INTERVAL_SELECT_3600"),
        GetMessage($module_id.'_AGENT_INTERVAL_SELECT_7200'),
        GetMessage($module_id.'_AGENT_INTERVAL_SELECT_14400'),    
        GetMessage($module_id."_AGENT_INTERVAL_SELECT_28800"),
        GetMessage($module_id.'_AGENT_INTERVAL_SELECT_43200'),
        GetMessage($module_id.'_AGENT_INTERVAL_SELECT_86400'),  
        GetMessage($module_id."_AGENT_INTERVAL_SELECT_172800"),
        GetMessage($module_id.'_AGENT_INTERVAL_SELECT_259200'),
        GetMessage($module_id.'_AGENT_INTERVAL_SELECT_345600'),    
        GetMessage($module_id."_AGENT_INTERVAL_SELECT_604800"),
        GetMessage($module_id.'_AGENT_INTERVAL_SELECT_1209600'),
        GetMessage($module_id.'_AGENT_INTERVAL_SELECT_2678400')                    
    )
);

$values_arr_agent_time = array(
    'REFERENCE_ID' => array(
   //     '0',
        '1',
        '2',
        '3',
        '4',
        '5',
        '6',
        '7',
        '8',
        '9',
        '10',
        '11',
        '12',
        '13',
        '14',
        '15',
        '16',
        '17',
        '18',
        '19',
        '20',
        '21',
        '22',
        '23'
    ),
    'REFERENCE' => array(
    //    '00.00',
        '01.00',
        '02.00',
        '03.00',
        '04.00',
        '05.00',
        '06.00',
        '07.00',
        '08.00',
        '09.00',
        '10.00',
        '11.00',
        '12.00',
        '13.00',
        '14.00',
        '15.00',
        '16.00',
        '17.00',
        '18.00',
        '19.00',
        '20.00',
        '21.00',
        '22.00',
        '23.00'      
    )
);

$values_arr_unsubscribed_user = array(
    'REFERENCE_ID' => array(
        'NO',
        'ALL',
        'THIS',
    ),
    'REFERENCE' => array(
        GetMessage($module_id.'_EXCLUDE_UNSUBSCRIBED_USER_VALUE_NO'),
        GetMessage($module_id.'_EXCLUDE_UNSUBSCRIBED_USER_VALUE_ALL'),
        GetMessage($module_id.'_EXCLUDE_UNSUBSCRIBED_USER_VALUE_THIS'),
    )
);

$values_arr_event_send_system = array(
    'REFERENCE_ID' => array(
        'BITRIX',
       // 'UNISENDER',
    ),
    'REFERENCE' => array(
        GetMessage($module_id.'_EVENT_SEND_SYSTEM_VALUE_BITRIX'),
      //  GetMessage($module_id.'_EVENT_SEND_SYSTEM_VALUE_UNISENDER'),
    )
);





$_arCategory = CSotbitMailingSectionTable::getCategoryList();
foreach($_arCategory as $item)
{
    $arCategory['REFERENCE'][$item[0]['ID']] = $item[0]['NAME'];
    $arCategory['REFERENCE_ID'][$item[0]['ID']] = $item[0]['ID'];
}

//������ ��������� ��� �������
//START
$values_arr_unsubscribed_user_more = array();
if(is_array($ListMailing)) { 
    foreach($ListMailing as $kmailing=>$vmailing) {
        if($kmailing != $arResult['ID']){
            $values_arr_unsubscribed_user_more['REFERENCE_ID'][] = $kmailing; 
            $values_arr_unsubscribed_user_more['REFERENCE'][] = '['.$kmailing.'] '.$vmailing['NAME'];               
        }    
    }     
}
//END


//������ ��������� ��� ������������
//START
$values_arr_exclude_hore_ago_mode = array(
    'REFERENCE_ID' => array(
        'THIS',
        'ALL',
        'LIST',
    ),
    'REFERENCE' => array(
        GetMessage($module_id.'_EVENT_PARAMS_EXCLUDE_DAYS_HOUR_MODE_VALUE_THIS'),
        GetMessage($module_id.'_EVENT_PARAMS_EXCLUDE_DAYS_HOUR_MODE_VALUE_ALL'),
        GetMessage($module_id.'_EVENT_PARAMS_EXCLUDE_DAYS_HOUR_MODE_VALUE_LIST'),
    )
);

$values_arr_exclude_hore_ago_event = array();
if(is_array($ListMailing)) { 
    foreach($ListMailing as $kmailing=>$vmailing) {
        if($kmailing != $arResult['ID']){
            $values_arr_exclude_hore_ago_event['REFERENCE_ID'][] = $kmailing; 
            $values_arr_exclude_hore_ago_event['REFERENCE'][] = '['.$kmailing.'] '.$vmailing['NAME'];               
        }    
    }     
}
//END


$arOptions = array();


$arOptions['ID'] = array(
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage($module_id.'_ID_TITLE'),
      'TYPE' => 'HIDDEN',
      'SORT' => '10',
      'REFRESH' => 'N',
);

$arOptions['CATEGORY_ID'] = array( 
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage($module_id.'_CATEGORY_TITLE'),
      'SORT' => '10',
      'REFRESH' => 'N',
      'VALUES' => $arCategory,
      'TYPE' => 'SELECT',
);

$arOptions['DATE_LAST_RUN'] = array(
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage($module_id.'_DATE_LAST_RUN_TITLE'),
      'TYPE' => 'HIDDEN',
      'SORT' => '20',
      'REFRESH' => 'N',
);
    
$arOptions['COUNT_RUN'] = array(
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage($module_id.'_COUNT_RUN_TITLE'),
      'TYPE' => 'HIDDEN',
      'SORT' => '20',
      'REFRESH' => 'N',
);
   
$arOptions['TEMPLATE'] = array(
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage($module_id.'_TEMPLATE_TITLE'),
      'TYPE' => 'HIDDEN',
      'SORT' => '30',
      'REFRESH' => 'N',
);
$arOptions['ACTIVE'] = array(
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage($module_id.'_ACTIVE_TITLE'),
      'TYPE' => 'CHECKBOX',
      'SORT' => '40',
      'REFRESH' => 'N',
);
$arOptions['NAME'] = array(
      'GROUP' => 'OPTION_10',
      'TITLE' =>  GetMessage($module_id.'_NAME_TITLE'),
      'TYPE' => 'STRING', 
      'DEFAULT' => '',
      'SORT' => '50',
      'REFRESH' => 'N',
      'SIZE' => '50'
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
$arOptions['MODE'] = array(
      'GROUP' => 'OPTION_10',
      'TITLE' =>  GetMessage($module_id.'_MODE_TITLE'),
      'TYPE' => 'SELECT', 
      'SORT' => '70',
      'VALUES' => $values_arr_MODE,
      'NOTES' => GetMessage($module_id.'_MODE_NOTES'),
);



$arOptions['EVENT_SEND_SYSTEM'] = array(
      'GROUP' => 'OPTION_10',
      'TITLE' =>  GetMessage($module_id.'_EVENT_SEND_SYSTEM_TITLE'),
      'TYPE' => 'SELECT', 
      'SORT' => '74',
      'VALUES' => $values_arr_event_send_system ,
      'SIZE' => 5,
      'DEFAULT' => 'BITRIX',  
);

$arOptions['SITE_URL'] = array(
      'GROUP' => 'OPTION_10',
      'TITLE' =>  GetMessage($module_id.'_SITE_URL_TITLE'),
      'TYPE' => 'STRING', 
      'DEFAULT' => '',
      'SORT' => '80',
      'REFRESH' => 'N',
      'SIZE' => '50',
      'NOTES' => GetMessage($module_id.'_SITE_URL_NOTES'),
); 
   
$arOptions['USER_AUTH'] = array(
      'GROUP' => 'OPTION_10',
      'TITLE' => GetMessage($module_id.'_USER_AUTH_TITLE'),
      'TYPE' => 'CHECKBOX',
      'SORT' => '90',
      'REFRESH' => 'N',
      'NOTES' => GetMessage($module_id.'_USER_AUTH_NOTES'),
);


//��������� ��� ������
//START     
$arOptions['AGENT_ACTIVE'] = array(
    'GROUP' => 'OPTION_40',
    'TITLE' => GetMessage($module_id.'_AGENT_ACTIVE_TITLE'),
    'TYPE' => 'CHECKBOX',
    'SORT' => '7',
    'REFRESH' => 'N',
);
$arOptions['AGENT_ID'] = array(
    'GROUP' => 'OPTION_40',
    'TITLE' => GetMessage($module_id.'_AGENT_ID_TITLE'),
    'TYPE' => 'HIDDEN',
    'SORT' => '10',
    'REFRESH' => 'N',
);
$arOptions['AGENT_LAST_EXEC'] = array(
    'GROUP' => 'OPTION_40',
    'TITLE' => GetMessage($module_id.'_AGENT_LAST_EXEC_TITLE'),
    'TYPE' => 'HIDDEN',
    'SORT' => '20',
    'REFRESH' => 'N',
);    
$arOptions['AGENT_NEXT_EXEC'] = array(
    'GROUP' => 'OPTION_40',
    'TITLE' => GetMessage($module_id.'_AGENT_NEXT_EXEC_TITLE'),
    'TYPE' => 'DATE',
    'SORT' => '40',
    'REFRESH' => 'N',
);       
$arOptions['AGENT_INTERVAL'] = array(
    'GROUP' => 'OPTION_40',
    'TITLE' => GetMessage($module_id.'_AGENT_INTERVAL_TITLE'),
    'TYPE' => 'SELECT',
    'SORT' => '50',
    'VALUES' => $values_arr_agent_interval,
);


//���������� ��� �������
$arOptions['EVENT_PARAMS_AGENT_AROUND'] = array(
    'GROUP' => 'OPTION_50',
    'TITLE' => GetMessage($module_id.'_EVENT_PARAMS_AGENT_AROUND_TITLE'),
    'TYPE' => 'CHECKBOX',
    'SORT' => '10',
    'DEFAULT' => 'Y',
    'REFRESH' => 'Y',
); 

if($arResult['EVENT_PARAMS_AGENT_AROUND']=='N' || $_REQUEST['EVENT_PARAMS_AGENT_AROUND']=='N')
{
    $arOptions['AGENT_TIME_START'] = array(
          'GROUP' => 'OPTION_50',
          'TITLE' =>  GetMessage($module_id.'_AGENT_TIME_START_TITLE'),
          'TYPE' => 'SELECT', 
          'SORT' => '20',
          'VALUES' => $values_arr_agent_time,
          'DEFAULT' => '8'
    ); 
    $arOptions['AGENT_TIME_END'] = array(
          'GROUP' => 'OPTION_50',
          'TITLE' =>  GetMessage($module_id.'_AGENT_TIME_END_TITLE'),
          'TYPE' => 'SELECT', 
          'SORT' => '30',
          'VALUES' => $values_arr_agent_time,
          'DEFAULT' => '22'      
    );     
}
//END

  
// ������������ �����
$arOptions['EXCLUDE_HOUR_AGO'] = array(
    'GROUP' => 'OPTION_60',
    'TITLE' => GetMessage($module_id.'_EXCLUDE_DAYS_HOUR_TITLE'),
    'TYPE' => 'INT',
    'SORT' => '40',
    'REFRESH' => 'N',
    'DEFAULT' => '336',
    'NOTES' =>  GetMessage($module_id.'_EXCLUDE_DAYS_HOUR_NOTES'),  
); 
$arOptions['EVENT_PARAMS_EXCLUDE_DAYS_HOUR_MODE'] = array(
    'GROUP' => 'OPTION_60',
    'TITLE' =>  GetMessage($module_id.'_EVENT_PARAMS_EXCLUDE_DAYS_HOUR_MODE_TITLE'),
    'TYPE' => 'SELECT', 
    'SORT' => '60',
    'VALUES' => $values_arr_exclude_hore_ago_mode,
    'SIZE' => 10,
    'DEFAULT' => 'THIS',
    'REFRESH' => 'Y',
    'NOTES' =>  GetMessage($module_id.'_EVENT_PARAMS_EXCLUDE_DAYS_HOUR_MODE_NOTES'),      
); 
 
if($arResult['EVENT_PARAMS_EXCLUDE_DAYS_HOUR_MODE']=='LIST' || $_REQUEST['EVENT_PARAMS_EXCLUDE_DAYS_HOUR_MODE']=='LIST'){ 
    $arOptions['EVENT_PARAMS_EXCLUDE_HOUR_AGO_EVENT'] = array(
        'GROUP' => 'OPTION_60',
        'TITLE' =>  GetMessage($module_id.'_EVENT_PARAMS_EXCLUDE_HOUR_AGO_EVENT_TITLE'),
        'TYPE' => 'MSELECT', 
        'SORT' => '70',
        'VALUES' => $values_arr_exclude_hore_ago_event,
        'SIZE' => 10,
        'DEFAULT' => '',
        'NOTES' =>  GetMessage($module_id.'_EVENT_PARAMS_EXCLUDE_HOUR_AGO_EVENT_NOTES'),      
    );      
} 
 
//������������ ������������ 
$arOptions['EXCLUDE_UNSUBSCRIBED_USER'] = array(
      'GROUP' => 'OPTION_70',
      'TITLE' =>  GetMessage($module_id.'_EXCLUDE_UNSUBSCRIBED_USER_TITLE'),
      'TYPE' => 'SELECT', 
      'SORT' => '50',
      'VALUES' => $values_arr_unsubscribed_user,
      'SIZE' => 10,
      'DEFAULT' => 'ALL',
      'NOTES' =>  GetMessage($module_id.'_EXCLUDE_UNSUBSCRIBED_USER_NOTES'), 
      'REFRESH' => 'Y'     
);  
   
if($arResult['EXCLUDE_UNSUBSCRIBED_USER']=='THIS' || $_REQUEST['EXCLUDE_UNSUBSCRIBED_USER']=='THIS'){
    $arOptions['EXCLUDE_UNSUBSCRIBED_USER_MORE'] = array(
          'GROUP' => 'OPTION_70',
          'TITLE' =>  GetMessage($module_id.'_EXCLUDE_UNSUBSCRIBED_USER_MORE_TITLE'),
          'TYPE' => 'MSELECT', 
          'SORT' => '60',
          'VALUES' => $values_arr_unsubscribed_user_more,
          'SIZE' => 10,
          'DEFAULT' => '',
          'NOTES' =>  GetMessage($module_id.'_EXCLUDE_UNSUBSCRIBED_USER_MORE_NOTES'),      
    );     
}
$types = array(
    'TEMP_TOP_RECOMMEND_TYPE' => $arResult['TEMPLATE_PARAMS']['TEMP_TOP_RECOMMEND_TYPE'],
    'TEMP_LIST_RECOMMEND_TYPE' => $arResult['TEMPLATE_PARAMS']['TEMP_LIST_RECOMMEND_TYPE'],
    'TEMP_BOTTOM_RECOMMEND_TYPE' => $arResult['TEMPLATE_PARAMS']['TEMP_BOTTOM_RECOMMEND_TYPE'],

    'TEMP_TOP_VIEWED_TYPE' => $arResult['TEMPLATE_PARAMS']['TEMP_TOP_VIEWED_TYPE'],
    'TEMP_LIST_VIEWED_TYPE' => $arResult['TEMPLATE_PARAMS']['TEMP_LIST_VIEWED_TYPE'],
    'TEMP_BOTTOM_VIEWED_TYPE' => $arResult['TEMPLATE_PARAMS']['TEMP_BOTTOM_VIEWED_TYPE'],

    'TEMP_NOVELTY_GOODS_TOP_TYPE' => $arResult['TEMPLATE_PARAMS']['TEMP_NOVELTY_GOODS_TOP_TYPE'],
    'TEMP_NOVELTY_GOODS_BOTTOM_TYPE' => $arResult['TEMPLATE_PARAMS']['TEMP_NOVELTY_GOODS_BOTTOM_TYPE'],
    'TEMP_NOVELTY_GOODS_LIST_TYPE' => $arResult['TEMPLATE_PARAMS']['TEMP_NOVELTY_GOODS_LIST_TYPE'],

    'TEMP_FORGET_BASKET_TOP_TYPE' => $arResult['TEMPLATE_PARAMS']['TEMP_FORGET_BASKET_TOP_TYPE'],
    'TEMP_FORGET_BASKET_LIST_TYPE' => $arResult['TEMPLATE_PARAMS']['TEMP_FORGET_BASKET_LIST_TYPE'],
    'TEMP_FORGET_BASKET_BOTTOM_TYPE' => $arResult['TEMPLATE_PARAMS']['TEMP_FORGET_BASKET_BOTTOM_TYPE'],
);


//������� ��������� ������� ����������
//file_put_contents(dirname(__FILE__) . '/templateParams.log', print_r($arResult['TEMPLATE_PARAMS'], true));
$paramTemplate = CComponentUtil::GetTemplateProps('sotbit:sotbit.mailing.logic', $arResult['TEMPLATE'], "", $arResult['TEMPLATE_PARAMS']);
//file_put_contents(dirname(__FILE__) . '/log_paramTemplate.log', print_r($paramTemplate, true));



// ������� ������� �� �������
// START
// ������� ����
$i = count($arTabs)+1;
$templateTabs = array();
$tabs_add = array();
$tabs_code_add = array();
if(is_array($paramTemplate))
{
    foreach($paramTemplate as $k => $v)
    {
        if($v['TABS'])
        {
            if($tabs_code_add[$v['TABS']])
            {
                $paramTemplate[$k]['NUMBER_TAB'] = $tabs_code_add[$v['TABS']];
            }
            else
            {
                $paramTemplate[$k]['NUMBER_TAB'] = $i;
            }

            if(!in_array($v['TABS'], $tabs_add))
            {
                $tabs_add[$i] = $v['TABS'];
                $tabs_code_add[$v['TABS']] = $i;
                $tab = array(
                  'DIV' => 'edit'.$i*10,
                  'TAB' => $v['TABS_NAME'],     
                  'ICON' => '',
                  'TITLE' => $v['TABS_NAME'],
                  'SORT' => $i
                );
                $arTabs[] = $tab;
                $i++;
            }
        }
    }
}
//END


if($arResult['EXCLUDE_HOUR_AGO'] == 0 && isset($arResult['EXCLUDE_HOUR_AGO'])) {
    $arOptions['EXCLUDE_HOUR_AGO']['DEFAULT'] = 0;    
}


// �������� �������� ������� ����� ����������
if(is_array($arOptions))
{
    foreach($arOptions as $kopt => $vopt)
    {
        if($arResult[$kopt])
        {
            $arOptions[$kopt]['DEFAULT'] = $arResult[$kopt];

            if($kopt == 'TEMPLATE') {
                $arOptions[$kopt]['VALUE_SHOW'] = $arrTempl[$arResult[$kopt]]['TITLE_CUSTOM'];                  
            }

            if($kopt == 'AGENT_ID') {
                $arOptions[$kopt]['VALUE_SHOW'] = '<a target="_blank" href="/bitrix/admin/agent_edit.php?ID='.$arResult[$kopt].'">'.$arResult[$kopt].'</a>';
            }
        }
    }
}



// ������� ������ �������� ������� ����
// START
if(is_array($paramTemplate))
{
    foreach($paramTemplate as $templGroup)
    {
        if($templGroup['PARENT'])
        {
            $DEF_NAME = GetMessage($module_id.'_OPTION_DEF_NANE');
            
            if(empty($arGroups[$templGroup['PARENT']]['TITLE']) && $templGroup['PARENT_NAME']) {
                $NAME = $templGroup['PARENT_NAME'];
            }
            elseif($arGroups[$templGroup['PARENT']]['TITLE'] == $DEF_NAME && $templGroup['PARENT_NAME']) {
                $NAME = $templGroup['PARENT_NAME'];
            }
            elseif(empty($arGroups[$templGroup['PARENT']]['TITLE'])) {
                $NAME = $DEF_NAME;
            }

            if($templGroup['NUMBER_TAB'])
            {
                $arGroups[$templGroup['PARENT']] = array(
                    'TITLE' => $NAME,
                    'TAB' => $templGroup['NUMBER_TAB']
                );  
                                   
            }
            else
            {
                $arGroups[$templGroup['PARENT']] = array(
                    'TITLE' => $NAME,
                    'TAB' => 3    
                );
            }
        }
    }
}     
// END
  
// ���������� ��������� �������
// START

/*
� ������  �������������� ������� ������ ��������� �������,
���������� � ������� $arEventMessage
*/
if (is_array($arEventMessage)) {
    $arResult['TEMPLATE_PARAMS']['SITE_TEMPLATE_ID'] = $arEventMessage['SITE_TEMPLATE_ID'];
}

if(is_array($paramTemplate))
{
    foreach($paramTemplate as  $KeyOpt => $templOption)
    {
        $KeyOption = 'TEMPLATE_PARAMS_'.$KeyOpt.'';
        
        //�������� ������ ���������
        $arOptions[$KeyOption]['GROUP'] = $templOption['PARENT'];  
        $arOptions[$KeyOption]['TITLE'] = $templOption['NAME'];      
        $arOptions[$KeyOption]['REFRESH'] = $templOption['REFRESH'];        
        $arOptions[$KeyOption]['SORT'] = $templOption['SORT']; 
        $arOptions[$KeyOption]['NOTES'] = $templOption['NOTES'];           
        $arOptions[$KeyOption]['SIZE'] = $templOption['SIZE'];     
        
        $arOptions[$KeyOption]['COLS'] = $templOption['COLS'];
        //$arOptions[$KeyOption]['HEIGHT'] = $templOption['HEIGHT'];
        
        $arOptions[$KeyOption]['HEIGHT'] = $templOption['HEIGHT'];
        
        $arOptions[$KeyOption]['DEFAULT_TYPE'] = $templOption['DEFAULT_TYPE'];
        $arOptions[$KeyOption]['TYPE_MAIL'] = $templOption['TYPE_MAIL'];
        

        if($templOption['TYPE'] == 'DATE_PERIOD' || $templOption['TYPE'] == 'INT_FROM_TO' || $templOption['TYPE'] == 'DATE_PERIOD_AGO')
        {
            $arOptions[$KeyOption]['DEFAULT'] = array(
                'from' => $arResult['TEMPLATE_PARAMS'][$KeyOpt.'_from'],
                'to' => $arResult['TEMPLATE_PARAMS'][$KeyOpt.'_to'],
                'type' => $arResult['TEMPLATE_PARAMS'][$KeyOpt.'_type']
            );
        }
        else
        {
            if($arResult['TEMPLATE_PARAMS'][$KeyOpt] !== null)
            {
                $arOptions[$KeyOption]['DEFAULT'] = $arResult['TEMPLATE_PARAMS'][$KeyOpt];
            } 
            else
            {
                $arOptions[$KeyOption]['DEFAULT'] = $templOption['DEFAULT'];          
            }   
            
        }

        //���� ������
        if($templOption['TYPE'] == 'STRING'){
            $arOptions[$KeyOption]['TYPE'] = $templOption['TYPE'];          
        }
        //���� �������
        if($templOption['TYPE'] == 'CHECKBOX'){ 
             $arOptions[$KeyOption]['TYPE'] = $templOption['TYPE'];        
        }
        
        
        // ���������� ������ �������� ��� ������
        if($templOption['TYPE'] == 'LIST')
        {
            foreach($templOption['VALUES'] as $k => $v)
            {
                $arOptions[$KeyOption]['VALUES']['REFERENCE_ID'][] = $k;
                $arOptions[$KeyOption]['VALUES']['REFERENCE'][] = $v;
            }
        }        
        
        //���� ����� �� ������ �������� ���������������
        if($templOption['TYPE'] == 'LIST' && $templOption['MULTIPLE'] == 'Y') {  
            $arOptions[$KeyOption]['TYPE'] = 'MSELECT';                  
        } 
        elseif($templOption['TYPE'] == 'LIST'){
            $arOptions[$KeyOption]['TYPE'] = 'SELECT';         
        } 
        // ���� �����
        elseif($templOption['TYPE'] == 'TEXT') {
            $arOptions[$KeyOption]['TYPE'] = 'HTML';              
        }  
        elseif($templOption['TYPE'] == 'TEXTAREA') {
            $arOptions[$KeyOption]['TYPE'] = 'TEXT';              
        } elseif($templOption['TYPE'] == 'TABS_INFO') {
            $arOptions[$KeyOption]['TYPE'] = 'TABS_INFO';              
        }  
        elseif($templOption['TYPE'] == 'PHP') {
            $arOptions[$KeyOption]['TYPE'] = 'PHP';              
        }        
        elseif($templOption['TYPE'] == 'DATE_PERIOD') {
            $arOptions[$KeyOption]['TYPE'] = 'DATE_PERIOD';              
        }          
        elseif($templOption['TYPE'] == 'DELIVERY_ID') {
            $arOptions[$KeyOption]['TYPE'] = 'DELIVERY_ID';              
        } else {
            $arOptions[$KeyOption]['TYPE'] = $templOption['TYPE'];              
        }           
    }
}
// END
?>



<div id="status_bar" style="overflow:hidden;display:none; height: 40px;">
    <div id="progress_bar" style="width: 500px;float:left;" class="adm-progress-bar-outer">
        <div id="progress_bar_inner" style="width: 0px;" class="adm-progress-bar-inner"></div>
        <div id="progress_text" style="width: 500px;" class="adm-progress-bar-inner-text">0%</div>
    </div>
    <div id="progress_info_bar" style="float:left;width:400px;height:35px;line-height:35px;font-weight:bold;margin-left:30px;"></div>
    <div id="current_test"></div>
    <?/*
    <div class="adm-workarea" style="padding: 0px;">
        <a class="adm-btn" id="btn_start_stop" href="">���������� ��������</a>
    </div>
    */?>
</div>
<div id="progress_info_mail_send">
</div>
<div id="progress_info_mail_exclude_undelivered">
</div>
<div id="progress_info_mail_exclude_unsubscribed">
</div>
<div id="progress_info_mail_exclude_hour_ago">
</div>


<style>
    #progress_info_mail_send span,
    #progress_info_mail_exclude_unsubscribed span,
    #progress_info_mail_exclude_hour_ago span {
        display: inline-block;
        padding: 0px 2px 0px 7px
    }
</style>

<br/>



<script>

$("#btn_start_send").on('click', function(e) {
    //alert("start button");

    $(this).remove();
    //$(this).attr('id','btn_stop_send');
    $(this).text('<?=GetMessage($module_id."_PANEL_TOP_STOP_TITLE")?>');
    
    BX.ajax.get("/bitrix/admin/sotbit_mailing_start_ajax.php?ID=<?=$ID?>&SOTBIT_MAILING_DETAIL=Y&MAILING_START=Y&COUNT_RUN=<?=$arResult['COUNT_RUN']?>", "", function(data){});

    SotbitGetJsonProgress();
    
    return false;
});

$("#btn_stop_send").on('click', function(e) {
    //alert("stop button");

    $(this).attr('id','btn_start_send');
    $(this).text('<?=GetMessage($module_id."_PANEL_TOP_START_TITLE")?>');
    
    BX.ajax.get("/bitrix/admin/sotbit_mailing_start_ajax.php?ID=<?=$ID?>&SOTBIT_MAILING_DETAIL=Y&MAILING_STOP=Y&COUNT_RUN=<?=$arResult['COUNT_RUN']?>", "", function(data){});
    
    SotbitGetJsonProgress();
    
    return false;
});

function SotbitGetJsonProgress()
{
    $('#status_bar').show();
    
    $.ajaxSetup({cache: false});
    
    $.getJSON('/bitrix/tmp/sotbit_mailing_progress_<?=$ID?>_<?=$arResult['COUNT_RUN']?>.json', function(data) {
        if(data.COUNT_NOW>0) {
            prog = Math.ceil((data.COUNT_NOW/data.COUNT_ALL)*100);       
        } else if(data.COUNT_NOW==0 && data.COUNT_ALL==0) {
             prog = 100;              
        } else {
            prog = 0;    
        }
        BX('progress_text').innerHTML = prog + '%';  
        BX('progress_bar_inner').style.width = 500 * prog / 100 + 'px'; 
        if(data.COUNT_ALL == 999999) {
            BX('progress_info_bar').innerHTML = "<?=GetMessage($module_id.'_JS_OJIDANIE')?> ";      
        } else {
            BX('progress_info_bar').innerHTML = "<?=GetMessage($module_id.'_JS_OTPRAV')?> "+data.COUNT_SEND+" <?=GetMessage($module_id.'_JS_IZ')?> "+data.COUNT_ALL+" <?=GetMessage($module_id.'_JS_PISEM')?> ";              
        }
        
        //BX('progress_info_bar').innerHTML = "<?=GetMessage($module_id.'_JS_OTPRAV')?> "+data.COUNT_SEND+" <?=GetMessage($module_id.'_JS_IZ')?> "+data.COUNT_ALL+" <?=GetMessage($module_id.'_JS_PISEM')?> ";  
                                                                
        if(data.EMAIL_TO_SEND) {                                                                                          
            BX('progress_info_mail_send').innerHTML = "<?=GetMessage($module_id.'_JS_SEND_MAILING')?> "+data.EMAIL_TO_SEND+" <a target='_blank' href='/bitrix/tmp/sotbit_mailing_progress_<?=$ID?>_<?=$arResult['COUNT_RUN']?>_EMAIL_TO_SEND.txt'><?=GetMessage($module_id.'_JS_MORE_INFO')?></a><br />";            
        }
        if(data.EMAIL_TO_EXCLUDE_UNSUBSCRIBED) {
            BX('progress_info_mail_exclude_unsubscribed').innerHTML = "<?=GetMessage($module_id.'_JS_NO_SEND_EXCLUDE_UNSUBSCRIBED_MAILING')?> "+data.EMAIL_TO_EXCLUDE_UNSUBSCRIBED+" <a target='_blank' href='/bitrix/tmp/sotbit_mailing_progress_<?=$ID?>_<?=$arResult['COUNT_RUN']?>_EMAIL_TO_EXCLUDE_UNSUBSCRIBED.txt'><?=GetMessage($module_id.'_JS_MORE_INFO')?></a> <br />";            
        } 
        if(data.EMAIL_TO_EXCLUDE_UNDELIVERED) {
            BX('progress_info_mail_exclude_undelivered').innerHTML = "<?=GetMessage($module_id.'_JS_NO_SEND_EXCLUDE_UNDELIVERED_MAILING')?> "+data.EMAIL_TO_EXCLUDE_UNDELIVERED+" <a target='_blank' href='/bitrix/tmp/sotbit_mailing_progress_EMAIL_TO_EXCLUDE_UNDELIVERED.txt'><?=GetMessage($module_id.'_JS_MORE_INFO')?></a> <br />";            
        }               
        if(data.EMAIL_TO_EXCLUDE_HOUR_AGO) {
            BX('progress_info_mail_exclude_hour_ago').innerHTML = "<?=GetMessage($module_id.'_JS_NO_SEND_EXCLUDE_HOUR_AGO_MAILING')?> "+data.EMAIL_TO_EXCLUDE_HOUR_AGO+" <a target='_blank' href='/bitrix/tmp/sotbit_mailing_progress_<?=$ID?>_<?=$arResult['COUNT_RUN']?>_EMAIL_TO_EXCLUDE_HOUR_AGO.txt'><?=GetMessage($module_id.'_JS_MORE_INFO')?></a> <br />";            
        }  
        
        if(prog >= 100) {
             BX('progress_text').innerHTML = '<?=GetMessage($module_id.'_JS_SEND_END')?>';          
        }
        
        //�������� �������� ���� ������������
        if(data.MAILING_WORK == 'N') {
            BX.ajax.get("/bitrix/admin/sotbit_mailing_start_ajax.php?ID=<?=$ID?>&SOTBIT_MAILING_DETAIL=Y&MAILING_START=Y&COUNT_RUN=<?=$arResult['COUNT_RUN']?>&MAILING_WORK_CHECK=Y", "", function(data){});      
        }
    });

    var proggres_width = $("#progress_bar_inner").width();
    if(proggres_width >= 500)
    {
        
    }
    else
    {
        setTimeout(function(){
            SotbitGetJsonProgress(); 
        }, 1000);
    }
}

</script>



<div id="results"></div>
<?if($arResult['MAILING_WORK']=='Y'):?>
<script>
    SotbitGetJsonProgress();
    <?
    $arrProgress = CSotbitMailingHelp::ProgressFileGetArray($ID, $arResult['COUNT_RUN']);
    if($arrProgress['MAILING_WORK'] == 'Y'):?>
        BX.ajax.get("/bitrix/admin/sotbit_mailing_start_ajax.php?ID=<?=$ID?>&SOTBIT_MAILING_DETAIL=Y&MAILING_START=Y&COUNT_RUN=<?=$arResult['COUNT_RUN']?>&MAILING_WORK_CHECK=Y", "", function(data){});
    <?endif;
    ?>
</script>
<?endif;?>

<?
//file_put_contents(dirname(__FILE__) . '/arTabs.log', print_r($arTabs, true));
//file_put_contents(dirname(__FILE__) . '/arGroups.log', print_r($arGroups, true));
//file_put_contents(dirname(__FILE__) . '/arOptions.log', print_r($arOptions, true));

$settings = array();

$settings['TEMPLATE_PARAMS_TEMP_TOP_RECOMMEND']['TYPE_FIELD'] = $types['TEMP_TOP_RECOMMEND_TYPE'];
$settings['TEMPLATE_PARAMS_TEMP_LIST_RECOMMEND']['TYPE_FIELD'] = $types['TEMP_LIST_RECOMMEND_TYPE'];
$settings['TEMPLATE_PARAMS_TEMP_BOTTOM_RECOMMEND']['TYPE_FIELD'] = $types['TEMP_BOTTOM_RECOMMEND_TYPE'];
$settings['TEMPLATE_PARAMS_TEMP_TOP_VIEWED']['TYPE_FIELD'] = $types['TEMP_TOP_VIEWED_TYPE'];
$settings['TEMPLATE_PARAMS_TEMP_LIST_VIEWED']['TYPE_FIELD'] = $types['TEMP_LIST_VIEWED_TYPE'];
$settings['TEMPLATE_PARAMS_TEMP_BOTTOM_VIEWED']['TYPE_FIELD'] = $types['TEMP_BOTTOM_VIEWED_TYPE'];

$settings['TEMPLATE_PARAMS_TEMP_NOVELTY_GOODS_TOP']['TYPE_FIELD'] = $types['TEMP_NOVELTY_GOODS_TOP_TYPE'];
$settings['TEMPLATE_PARAMS_TEMP_NOVELTY_GOODS_BOTTOM']['TYPE_FIELD'] = $types['TEMP_NOVELTY_GOODS_BOTTOM_TYPE'];
$settings['TEMPLATE_PARAMS_TEMP_NOVELTY_GOODS_LIST']['TYPE_FIELD'] = $types['TEMP_NOVELTY_GOODS_LIST_TYPE'];

$settings['TEMPLATE_PARAMS_TEMP_FORGET_BASKET_TOP']['TYPE_FIELD'] = $types['TEMP_FORGET_BASKET_TOP_TYPE'];
$settings['TEMPLATE_PARAMS_TEMP_FORGET_BASKET_LIST']['TYPE_FIELD'] = $types['TEMP_FORGET_BASKET_LIST_TYPE'];
$settings['TEMPLATE_PARAMS_TEMP_FORGET_BASKET_BOTTOM']['TYPE_FIELD'] = $types['TEMP_FORGET_BASKET_BOTTOM_TYPE'];



$opt = new CMailingDetailOptions($module_id, $arTabs, $arGroups, $arOptions, $settings);
$opt->ShowHTML();
?>


<?
$tabControl = new CAdminTabControl("tabControl", $arTabs);
CJSCore::Init(array("jquery"));
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");?>
