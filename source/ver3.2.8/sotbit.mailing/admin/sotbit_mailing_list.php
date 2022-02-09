<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');

$module_id = "sotbit.mailing";

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/include.php");

IncludeModuleLangFile(__FILE__);



$CSotbitMailingTools = new CSotbitMailingTools();
if(!$CSotbitMailingTools->getDemo())
{
    CAdminMessage::ShowMessage(array("MESSAGE" => GetMessage($module_id."_DEMO_END"), "DETAILS" => GetMessage($module_id."_DEMO_END_DETAILS"), "HTML" => true));
    return false;
}



//������� ����
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($POST_RIGHT <= "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

//������� ������ � ��������
$arrTempl = array();
$templComponent = CComponentUtil::GetTemplatesList('sotbit:sotbit.mailing.logic', true);

if(is_array($templComponent)) {
    foreach($templComponent as $valTemp) {
        if($valTemp['NAME'] != '.default') {
            if($valTemp['TEMPLATE']) {
                $valTemp["TITLE_CUSTOM"] = $valTemp["TITLE"]. ' ['.GetMessage($module_id.'_TEMPLATE_TYPE_MY').' - '.$valTemp['NAME'].']';   
            } else {
                $valTemp["TITLE_CUSTOM"] = $valTemp["TITLE"]. ' ['.GetMessage($module_id.'_TEMPLATE_TYPE_SYSTEM').' - '.$valTemp['NAME'].']';                 
            }
            $arrTempl[$valTemp['NAME']] = $valTemp;
        }
    }
}

//������� ������ � ��������� ����� ���������� �������� ��������
//���� ���������� ��������� �������
$arMailSiteTemplate = array();
$mailSiteTemplateDb = CSiteTemplate::GetList(null, array('TYPE' => 'mail'));
while($mailSiteTemplate = $mailSiteTemplateDb->GetNext())
    $arMailSiteTemplate[] = $mailSiteTemplate;

//���������� ������ ��������
$ar_MESSAGE_THEME = array("" => GetMessage($module_id.'_NO_MESSAGE_THEME'));
    foreach($arMailSiteTemplate as $mailSiteTemplate) {
        $ar_MESSAGE_THEME[$mailSiteTemplate['ID']] = '['.$mailSiteTemplate['ID'].'] ' . $mailSiteTemplate['NAME'];          
    }


//printr($arrTempl);

//��������� ��������� � ���������� �������
$sTableID = "tbl_sotbit_mailing_event"; // ID �������
$oSort = new CAdminSorting($sTableID, "ID", "asc"); // ������ ����������
$lAdmin = new CAdminList($sTableID, $oSort); // �������� ������ ������

$cData = new CSotbitMailingEvent;
$sectData = new CSotbitMailingSectionTable;
$helper = new CSotbitMailingHelp();

// ******************************************************************** //
//                           ������                                     //
// ******************************************************************** //

// *********************** CheckFilter ******************************** //
// �������� �������� ������� ��� �������� ������� � ��������� �������
function CheckFilter()
{
    global $FilterArr, $lAdmin;
    foreach ($FilterArr as $f) global $$f;
    
    return count($lAdmin->arFilterErrors)==0;  //���� ���� ������ ������ false
}

$FilterArr = array(
    "find",
    "find_TYPE",
    "find_ID", 
    "find_NAME", 
    "find_ACTIVE",  
    "find_TEMPLATE",  
    "find_MODE",  
    "find_SHOW_SR", // ��� ����������� ������ (��������/�����/���)
);

$parentID = 0;
if(isset($_REQUEST["parent"]) && $_REQUEST["parent"])
{
    $parentID = $_REQUEST["parent"];
}

//������� �����
//$sThisSectionUrl = "sotbit_mailing_list.php?parent=".$parentID."&lang=".LANG;;

//��� �������� �������� �� ������
$sLastFolder = '';

if($parentID>0) {
    $rsData = CSotbitMailingSectionTable::GetList(array("filter" => array("ID"=>$parentID)));
    while($arRes = $rsData->Fetch()){    
        $sLastFolder = "sotbit_mailing_list.php?parent=".$arRes["CATEGORY_ID"]."&lang=".LANG;
    }
}


/*�������� ��� id �������� � ���������*/
$cIDData = $cData->GetMixedList(array($by=>$order), array());
while($field = $cIDData->Fetch())
{
   $arCID['T'][] = array(
        "parent_id" => $field['CATEGORY_ID'],
        "element_id" => $field['ID'],
        "type" => $field['T']
   );
   $arCID[] = $field['CATEGORY_ID'];
}


//������������� �������
$lAdmin->InitFilter($FilterArr);

if(CheckFilter()) {
   $arFilter = array(
        "ID" => ($find!="" && $find_TYPE == "id" ? $find : $find_ID),
        "NAME" => $find_NAME,
        "ACTIVE" => $find_ACTIVE,
        "TEMPLATE" => $find_TEMPLATE,
        "MODE" => $find_MODE,
        //"CATEGORY_ID" => $find_SHOW_SR
        "CATEGORY_ID" => $parentID
    );
}
   

//$cDataMessage = new CSotbitMailingMessage; 
// ******************************************************************** //
//                ��������� �������� ��� ���������� ������              //
// ******************************************************************** //



//���������� ����������������� ���������
if($lAdmin->EditAction() && $POST_RIGHT=="W") {
  
    foreach($FIELDS as $ID=>$arFields)
    {
        if(!$lAdmin->IsUpdated($ID))
            continue;

        $DB->StartTransaction();

        //�������� ���� ��������
        $type = substr($ID,0,1);
        
        $ID = intval(substr($ID,1));
        
            
            if($type == "R"){
                if(!$cData->Update($ID, $arFields))
                {
                    $lAdmin->AddGroupError(GetMessage($module_id.'_ERROR_DB').' '.$cData->LAST_ERROR, $ID);
                    $DB->Rollback();
                }  else {
                    //������� ��������� ��������� ������� bitrix
                    $res = CSotbitMailingEvent::GetByID($ID);
                    $eventMessageId = $helper->EventTemplateCheck($res['EVENT_TYPE'], $arFields['NAME']);
                    
                    $arEventMessageFields = Array(
                        "SITE_TEMPLATE_ID"     => $arFields['MESSAGE_THEME'],
                    );
                    if (!empty($eventMessageId)) {
                        $helper->UpdateEventMessage($eventMessageId, $arEventMessageFields);
                    }
                    $DB->Commit();
                    }
                
            } else {
                
                    if(!$sectData->Update($ID, $arFields))
                    {
                        $lAdmin->AddGroupError(GetMessage($module_id.'_ERROR_DB').' '.$sectData->LAST_ERROR, $ID);
                        $DB->Rollback();
                    }  else {
                        $DB->Commit();
                    } 
                }
            
        
        
        

        //���� ������� �� �������� �� ���������� ��������
        if($type=="S") continue;
        
        $DB->StartTransaction();   
        
        if($res = CSotbitMailingEvent::GetByID($ID)) { 
        
            //������ � �������� UniSender
            //START
            if($res['EVENT_SEND_SYSTEM'] == 'UNISENDER') {
                
                //���������� ��������
                //START
                $getListUniSender = CSotbitMailingHelp::QueryUniSender('getLists'); 
                $arrListUniSender = array();
                if(is_array($getListUniSender)) {
                
                    foreach($getListUniSender['result'] as $k=>$v) {
                        $arrListUniSender[$v['id']] =  $v['title'];    
                    }               
                    
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
        } 
        
        global $CACHE_MANAGER; 
        $CACHE_MANAGER->ClearByTag($module_id);  //��������� ���     
        $CACHE_MANAGER->ClearByTag($module_id.'_GetMailingInfo');        
        $DB->Commit();
    }
}




//��������� ��������� � ��������� ��������
if(($arID = $lAdmin->GroupAction()) 
    && $POST_RIGHT=="W"
    || isset($_REQUEST['delete'])
)
{

      // ������� ����������� ������ ��� R
      $arrInfo = array();
      $rsDataInfo = $cData->GetList(array($by=>$order), $arFilter, false, array('ID', 'AGENT_ID') );
      while($ResInfo = $rsDataInfo->Fetch()) {
        $arrInfo[$ResInfo['ID']] = $ResInfo;    
      } 
    
     // ���� ������� "��� ���� ���������"
      if($_REQUEST['action_target']=='selected')
      {
            $cData = new CSotbitMailingEvent;
            
            $rsData = $cData->GetMixedList(array($by=>$order), $arFilter);
            while($arRes = $rsData->Fetch()){
                $arID[] = $arRes['T'].$arRes['ID'];
            }
      }   
       
         // ������� �� ������ ���������
          foreach($arID as $ID)
          {            
                if(strlen($ID)<=0){
                  continue;            
                }
                
                $TYPE = substr($ID, 0, 1);
                $ID = intval(substr($ID,1));
                
                // ��� ������� �������� �������� ��������� ��������
                switch($_REQUEST['action'])
                {           
                   case "delete":

                        if($TYPE=="S") {
                            
                            foreach($arCID["T"] as $k => $arID){
                                
                                if($ID == $arID['parent_id']) {
                                    
                                   $DB->StartTransaction();
                                   
                                     if( $arID['type'] == "S" ){
                                         
                                         if(!$sectData->Update($arID['element_id'], array('CATEGORY_ID'=>0))){
                                             
                                            $lAdmin->AddGroupError(GetMessage($module_id.'_ERROR_DB').' '.$sectData->LAST_ERROR, $ID);
                                            $DB->Rollback();
                                            
                                         }  else {
                                             
                                            $DB->Commit();
                                            
                                         } 
                                     } else {
                                         
                                          if(!$cData->Update($arID['element_id'], array('CATEGORY_ID'=>0))){
                                             
                                            $lAdmin->AddGroupError(GetMessage($module_id.'_ERROR_DB').' '.$cData->LAST_ERROR, $ID);
                                            $DB->Rollback();
                                            
                                         }  else {
                                             
                                            $DB->Commit();
                                            
                                         }                                         
                                     }
                                }
                            }
                            
                            CSotbitMailingSectionTable::Delete($ID);    
                        }
                        
                        if($TYPE=="R"){
                            // ������ ��� ��������� ��������      
                            $resEventMessage = CSotbitMailingMessage::GetList(array(),array('ID_EVENT'=>$ID),false,array('ID'));
                            while($arrEventMessage = $resEventMessage->Fetch()) {
                                CSotbitMailingMessage::Delete($arrEventMessage['ID']);
                            }
                            
                                                                      
                            //������ ��� ������� ��������    
                            $resEventTemplate = CSotbitMailingMessageTemplate::GetList(array(),array('ID_EVENT'=>$ID),false,array('ID','ID_EVENT'));
                            while($arrEventTemplate = $resEventTemplate->Fetch()) {
                                CSotbitMailingMessageTemplate::Delete($arrEventTemplate['ID']);
                            }                     
                       
                            //������� ��� ��������� �������, ���������� � ���������
                            $data = $cData->GetByID($ID);
                            if (!empty($data)) {
                                $eventType = $data['EVENT_TYPE'];
                                
                                //������ ��� �������� ������� ��� ������� ����
                                $arEventMessageFilter = array(
                                    "TYPE_ID" => $eventType,
                                );
                                $rsEM = CEventMessage::GetList($by="site_id", $order="desc", $arEventMessageFilter);
                                while ($arEM = $rsEM->Fetch()) {
                                    CEventMessage::Delete($arEM['ID']);    
                                }
                                
                                //������ ��� �������� ������� ��� ������ ��������
                                $et = new CEventType;
                                $et->Delete($eventType);
                            }
                       
                            $cData->Delete($ID); 
                            if($arrInfo[$ID]['AGENT_ID']) {
                                CAgent::Delete($arrInfo[$ID]['AGENT_ID']);                         
                            }
                            
                            //������ ��� ��������� ������ �������� 
                            //������ ����� ��� ��������
                            DeleteDirFilesEx("/bitrix/modules/".$module_id ."/php_files_mailing/".$ID.'/');
}
                   break;                    
                   /*case "copy":
                        $CopyElement = $cData->GetByID($ID);
                        unset($CopyElement['ID']);
                        $cData->Add($CopyElement);
                   break;
                   */
                    // ���������/�����������
                   case "activate":
                        $cData->Update($ID, array("ACTIVE" => "Y"));
                   break;       
                   case "deactivate":
                        $cData->Update($ID, array("ACTIVE" => "N"));
                   break;    
                } 
          } 
          
        global $CACHE_MANAGER; 
        $CACHE_MANAGER->ClearByTag($module_id);  //��������� ���     
        $CACHE_MANAGER->ClearByTag($module_id.'_GetMailingInfo');            
}



// ******************************************************************** //
//                ������� ��������� ������                              //
// ******************************************************************** //




// ******************************************************************** //
//                ���������� ������ � ������                            //
// ******************************************************************** //


$lAdmin->AddHeaders(array(
 
  array("id"    =>"ID",
    "content"  =>'ID',
    "sort"    =>"ID",
    "align"    =>"right",
    "default"  =>true,
  ),
  array(  "id"    =>"ACTIVE",
    "content"  => GetMessage($module_id.'_list_title_ACTIVE'),  
    "sort"    =>"ACTIVE",
    "default"  =>true,    
  ),   
  array("id" =>"NAME",
    "content"  =>  GetMessage($module_id.'_list_title_NAME'),   
    "sort"    =>"NAME",
    "default"  =>true,    
  ), 
  array(  "id"    =>"DESCRIPTION",
    "content"  => GetMessage($module_id.'_list_title_DESCRIPTION'),  
    "sort"    =>"DESCRIPTION",
    "default"  =>true,    
  ),      
  array(  "id"    =>"TEMPLATE",
    "content"  => GetMessage($module_id.'_list_title_TEMPLATE'),  
    "sort"    =>"TEMPLATE",
    "default"  =>true,    
  ), 
  array(  "id"    =>"MODE",
    "content"  => GetMessage($module_id.'_list_title_MODE'),  
    "sort"    =>"MODE",
    "default"  =>true,    
  ),    
  /*array(  "id"    =>"EVENT_SEND_SYSTEM",
    "content"  => GetMessage($module_id.'_list_title_EVENT_SEND_SYSTEM'),  
    "sort"    =>"EVENT_SEND_SYSTEM",
    "default"  =>true,    
  ),*/  
  array(  "id"    =>"DATE_LAST_RUN",
    "content"  => GetMessage($module_id.'_list_title_DATE_LAST_RUN'),  
    "sort"    =>"DATE_LAST_RUN",
    "default"  =>true,    
  ), 
  array(  "id"    =>"COUNT_RUN",
    "content"  => GetMessage($module_id.'_list_title_COUNT_RUN'),  
    "sort"    =>"COUNT_RUN",
    "default"  =>true,    
  ),   
  array(  "id"    =>"EXCLUDE_UNSUBSCRIBED_USER",
    "content"  => GetMessage($module_id.'_list_title_EXCLUDE_UNSUBSCRIBED_USER'),  
    "sort"    =>"EXCLUDE_UNSUBSCRIBED_USER",
    "default"  =>true,    
  ),
  array(  "id"    =>"MESSAGE_THEME",
    "content"  => GetMessage($module_id.'_list_title_MESSAGE_THEME'),  
    "sort"    =>"",
    "default"  =>true,    
  ),
  array(  "id"    =>"CATEGORY_ID",
    "content"  => GetMessage($module_id.'_list_title_CATEGORY_ID'),  
    "sort"    =>"CATEGORY_ID",
    "default"  =>true,    
  ), 
      
));


//���� ��� ����������� ������ (������)
$cData = new CSotbitMailingEvent;

$show = "all";
if(isset($_REQUEST["show_sr"]) && $_REQUEST["show_sr"]=="all")
{
    unset($arFilter["CATEGORY_ID"]);
    $show = "all";
}elseif(isset($_REQUEST["show_sr"]) && $_REQUEST["show_sr"]=="section")
{
    $show = "section";
    unset($arFilter["CATEGORY_ID"]);    
}elseif(isset($_REQUEST["show_sr"]) && $_REQUEST["show_sr"]=="delivery")
{
    $show = "delivery";
    unset($arFilter["CATEGORY_ID"]);    
}

//��������� ������ ��������� �� �����
$rsData = $cData->GetMixedList(array($by=>$order), $arFilter, $show);
$rsData = new CAdminResult($rsData, $sTableID);
$rsData->NavStart();
$lAdmin->NavText($rsData->GetNavPrint(GetMessage($module_id."_NAV_TITLE")));



$_arCategory = CSotbitMailingSectionTable::getCategoryList();

foreach($_arCategory as $item){
    $arCategory[$item[0]['ID']] = $item[0]['NAME'];
}



$arShowSR['REFERENCE'] = array(GetMessage($module_id."_box_inner_SR_TREE"), GetMessage($module_id."_box_inner_SR_ALL"), GetMessage($module_id."_box_inner_SR_SECTION"), GetMessage($module_id."_box_inner_SR_RASS"));
$arShowSR['REFERENCE_ID'] = array("tree", "all", "section", "delivery");


while($arRes = $rsData->NavNext(true, "f_")):
        
        $editUrl = "sotbit_mailing_detail.php?T=".$f_T."&ID=".$f_ID."&SOTBIT_MAILING_DETAIL=Y&parent=".$parentID."&lang=".LANG;
        $copyUrl = "sotbit_mailing_detail.php?T=".$f_T."&TEMPLATE=".$f_TEMPLATE."&ID_COPY=".$f_ID."&parent=".$parentID."&lang=".LANG;
        
        //��������� ��� �����
        if(!in_array($f_ID,$arCID)){
            $title = GetMessage($module_id."_FOLDER_IS_EMPTY");
            //$style = "color:red";
        } else {
            $style = "";
        }

        
       $row =& $lAdmin->AddRow($f_T.$f_ID, $arRes);
    
       if($f_T=="S")
        {
            $row->AddViewField("NAME", '<a href="sotbit_mailing_list.php?parent='.$f_ID.'&lang='.LANG.'" class="adm-list-table-icon-link" style='.$style.' title='.$title.'><span class="adm-submenu-item-link-icon adm-list-table-icon iblock-section-icon"></span><span class="adm-list-table-link">'.$f_NAME.'</span></a>');
        }
        else{
            
            $row->AddViewField("NAME", '<a href="sotbit_mailing_detail.php?T='.$f_T.'&ID='.$f_ID.'&SOTBIT_MAILING_DETAIL=Y&parent='.$parentID.'&lang='.LANG.'" title="'.GetMessage($module_id."_ACTION_EDIT").'">'.$f_NAME.'</a>');
            
             //����� ������
             $ar_MODE = array(
                'TEST' => GetMessage($module_id."_list_title_MODE_VALUE_TEST"),
                'WORK' => GetMessage($module_id."_list_title_MODE_VALUE_WORK")
             );       
             $row->AddSelectField("MODE",$ar_MODE);
             
             //��� �������� � ��������� �� ��� �������
             if($arrTempl[$f_TEMPLATE]) {
                $row_TEMPLATE = $arrTempl[$f_TEMPLATE]['TITLE_CUSTOM'];             
             } 
             else {
                $row_TEMPLATE = "<span style='color:red'>".GetMessage($module_id.'_list_title_TEMPLATE_DELETE')." ".$f_TEMPLATE."</span>";                    
             }

             $row->AddViewField("TEMPLATE", $row_TEMPLATE);
             
             //���������� e-mail
             $ar_EXCLUDE_UNSUBSCRIBED_USER = array(
                'NO' => GetMessage($module_id."_list_title_EXCLUDE_UNSUBSCRIBED_USER_VALUE_NO"),
                'ALL' => GetMessage($module_id."_list_title_EXCLUDE_UNSUBSCRIBED_USER_VALUE_ALL"),
                'THIS' => GetMessage($module_id."_list_title_EXCLUDE_UNSUBSCRIBED_USER_VALUE_THIS"),                        
             );
             $row->AddSelectField(
                "EXCLUDE_UNSUBSCRIBED_USER", 
                 $ar_EXCLUDE_UNSUBSCRIBED_USER 
             );
             
             /*
            //������� ��������
            $ar_EVENT_SEND_SYSTEM = array(
                'BITRIX' => GetMessage($module_id."_list_title_EVENT_SEND_SYSTEM_VALUE_BITRIX"),
                'UNISENDER' => GetMessage($module_id."_list_title_EVENT_SEND_SYSTEM_VALUE_UNISENDER"),                    
             );
             $row->AddSelectField(
                "EVENT_SEND_SYSTEM", 
                 $ar_EVENT_SEND_SYSTEM 
             ); */
             
             $row->AddSelectField(
                "MESSAGE_THEME", 
                 $ar_MESSAGE_THEME 
             );        
        }
         
            $row->AddCheckField("ACTIVE");

            $row->AddInputField("NAME", array("size"=>40));

            //��� ���� �����   
            $sHTML = '<textarea rows="5" cols="30" name="FIELDS['.$f_T.$f_ID.'][DESCRIPTION]">'.$f_DESCRIPTION.'</textarea>';
            $row->AddEditField("DESCRIPTION",$sHTML);

            //id ��������
            $row->AddSelectField("CATEGORY_ID",$arCategory);
            //$row->AddViewField("CATEGORY_ID",$arCategoryView);
   
   
   
        //��������� ����������� ����
         $arActions = array();
   
   
           if($f_T=="S"){
                $arActions[] = array(
                    "ICON"=>"edit",
                    "TEXT"=> GetMessage($module_id."_ACTION_EDIT"),
                    "ACTION"=>$lAdmin->ActionRedirect("sotbit_mailing_section_edit.php?parent=".$parentID."&ID=".$f_ID)
                );
                $arActions[] = array(
                    "ICON"=>"delete",
                    "TEXT"=> GetMessage($module_id."_ACTION_DELETE"),
                    //"ACTION"=>"if(confirm('".GetMessage($module_id."_DELIVERY_ACT_DEL_CONF")."')) ".$lAdmin->ActionDoGroup($f_T.$f_ID, "delete",$sThisSectionUrl)
                    "ACTION"=>"if(confirm('".GetMessage($module_id."_DELIVERY_ACT_DEL_CONF")."')) ".$lAdmin->ActionDoGroup($f_T.$f_ID, "delete","parent=".$parentID)
                );
           }
           
            if($f_T=="R"){
                if($arrTempl[$f_TEMPLATE]) {
                    $arActions[] = array(
                        "ICON"=>"edit",
                        "TEXT"=> GetMessage($module_id."_ACTION_EDIT"),
                        "ACTION"=>$lAdmin->ActionRedirect($editUrl) 
                    );
                }
               
                if($arrTempl[$f_TEMPLATE]) {
                    $arActions[] = array(
                        "ICON"=>"copy",
                        "TEXT"=> GetMessage($module_id."_ACTION_COPY"),
                        "ACTION"=>$lAdmin->ActionRedirect($copyUrl) 
                    );
                }               

               $arActions[] = array(
                    "ICON"=>"delete",
                    "TEXT"=> GetMessage($module_id."_ACTION_DELETE"),
                    //"ACTION"=>"if(confirm('".GetMessage($module_id."_ACTION_DELETE_CONFORM", array('#ID#' =>$f_ID))."?')) ".$lAdmin->ActionDoGroup($f_T.$f_ID, "delete",array('redirectUrl'=>$sThisSectionUrl))
                    "ACTION"=>"if(confirm('".GetMessage($module_id."_ACTION_DELETE_CONFORM", array('#ID#' =>$f_ID))."?')) ".$lAdmin->ActionDoGroup($f_T.$f_ID, "delete","parent=".$parentID)
               ); 
            }
            
            // �������� ����������� ���� � ������
            $row->AddActions($arActions);
    
endwhile;
   

// ������ �������
$lAdmin->AddFooter(
  array(
    array("title"=> GetMessage("MAIN_ADMIN_LIST_SELECTED"),"value"=>$rsData->SelectedRowsCount()), // ���-�� ���������
    array("counter"=>true,"title"=>GetMessage("MAIN_ADMIN_LIST_CHECKED"), "value"=>"0"), // ������� ��������� ���������
  )
); 

$lAdmin->AddGroupActionTable(
    array(
        "delete"=>GetMessage($module_id."_FOOTER_ACTION_DEL"),   
        "activate"=>GetMessage($module_id."_FOOTER_ACTION_ACTIVATE"),
        "deactivate"=>GetMessage($module_id."_FOOTER_ACTION_DEACTIVATE")      
    )
);


// ******************************************************************** //
//                ���������������� ����                                 //
// ******************************************************************** //

// ���������� ���� �������� ��������
    $arDDMenu = array();
    if(is_array($arrTempl)) {
   
        foreach($arrTempl as $kres => $arRes) {

            $arDDMenu[] = array(
                "TEXT" => $arRes['TITLE_CUSTOM'],
                "TITLE" => '['.$arRes["NAME"].'] '.$arRes['DESCRIPTION'],
                //"ACTION" => "window.location = 'sotbit_mailing_detail.php?lang=".LANG."&TEMPLATE=".$arRes["NAME"]."';"
                "ACTION" => "window.location = 'sotbit_mailing_detail.php?parent=".$parentID."&lang=".LANG."&TEMPLATE=".$arRes["NAME"]."';"
            );             
        }   
    }
    
$aContext = array(
        array(
            "TEXT" => GetMessage($module_id."_MENU_TOP_MAILING_ADD"),
            "ICON" => "btn_new",
            "TITLE" => GetMessage($module_id."_MENU_TOP_MAILING_ADD_ALT"),
            "MENU" => $arDDMenu
        ),
        array(
            "TEXT"=>GetMessage($module_id."_MENU_TOP_MAILING_ADD_SECTION"),
            "LINK"=>"sotbit_mailing_section_edit.php?parent=".$parentID."&lang=".LANG,
            "TITLE"=>GetMessage($module_id."_MENU_TOP_MAILING_ADD_SECTION_ALT"),
            "ICON"=>"btn_sect_new",
        ),
    );

    
if(strlen($sLastFolder)>0)
{
    $aContext[] = Array(
        "TEXT" => GetMessage($module_id."_SOTBIT_MAILING_UP"),
        "LINK" => $sLastFolder,
        "TITLE" => GetMessage($module_id."_SOTBIT_MAILING_UP_TITLE"),
    );
}
    
    //printr($aContext);
    
    $lAdmin->AddAdminContextMenu($aContext); 
    
    

// ******************************************************************** //
//                �����                                                 //
// ******************************************************************** //

// �������������� �����
$lAdmin->CheckListMode();

// ��������� ��������� ��������
$APPLICATION->SetTitle(GetMessage($module_id.'_MAILING_LIST_TITLE'));  







require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');  //������ ����� ������

/*����� ��������*/
// ******************************************************************** //
//                ����� �������                                         //
// ******************************************************************** //
 
// �������� ������ �������
$oFilter = new CAdminFilter(
    $sTableID."_filter",
    array(
        GetMessage($module_id.'_list_title_ID'),
        GetMessage($module_id.'_list_title_NAME'),  
        GetMessage($module_id.'_list_title_ACTIVE'),
        GetMessage($module_id.'_list_title_TEMPLATE'),
        GetMessage($module_id.'_list_title_MODE'), 
        GetMessage($module_id.'_list_title_SHOW_SR'),
    )
);?>

<form name="find_form" method="get" action="<?echo $APPLICATION->GetCurPage();?>">
    <?$oFilter->Begin();?>

        <tr>
            <td><?=GetMessage($module_id.'_list_title_ID')?>:</td>
            <td>
                <input type="text" name="find_ID" size="20" value="<?echo htmlspecialchars($find_ID)?>">
            </td>
        </tr>


        <tr>
            <td><?=GetMessage($module_id.'_list_title_NAME')?>:</td>
            <td>
                <input type="text" name="find_NAME" size="47" value="<?echo htmlspecialchars($find_NAME)?>">
            </td>
        </tr>

        <tr>
            <td><?echo GetMessage($module_id."_list_title_ACTIVE")?>:</td>
            <td>
                <select name="find_ACTIVE">
                    <option value=""><?=htmlspecialcharsex(GetMessage($module_id.'_ANY'))?></option>
                    <option value="Y"<?if($find_ACTIVE=="Y")echo " selected"?>><?=htmlspecialcharsex(GetMessage($module_id."_YES"))?></option>
                    <option value="N"<?if($find_ACTIVE=="N")echo " selected"?>><?=htmlspecialcharsex(GetMessage($module_id."_NO"))?></option>
                </select>
            </td>
        </tr>

        <tr>
            <td><?echo GetMessage($module_id."_list_title_TEMPLATE")?>:</td>
            <td>
                <select name="find_TEMPLATE">
                    <option value=""><?=htmlspecialcharsex(GetMessage($module_id.'_ANY'))?></option>
                <?foreach($arrTempl as $kres => $arRes):?>    
                    <option value="<?=$kres?>"<?if($find_MODE==$kres)echo " selected"?>><?=htmlspecialcharsex($arRes['TITLE_CUSTOM'])?></option>
                <?endforeach;?>

                </select>
            </td>
        </tr>    

        
        <tr>
            <td><?echo GetMessage($module_id."_list_title_MODE")?>:</td>
            <td>
                <select name="find_MODE">
                    <option value=""><?=htmlspecialcharsex(GetMessage($module_id.'_ANY'))?></option>
                    <option value="TEST" <?if($find_MODE=="TEST")echo " selected"?>><?=htmlspecialcharsex(GetMessage($module_id."_list_title_MODE_VALUE_TEST"))?></option>
                    <option value="WORK" <?if($find_MODE=="WORK")echo " selected"?>><?=htmlspecialcharsex(GetMessage($module_id."_list_title_MODE_VALUE_WORK"))?></option>
                </select>
            </td>
        </tr>
        
        <tr>
            <td><?=GetMessage($module_id."_select_box_SHOW_SR")?>:</td>
            <td>
                <?
                echo SelectBoxFromArray("show_sr", $arShowSR, $find_SHOW_SR, "", "");
                ?>
            </td>
        </tr>
    
    <?
    $oFilter->Buttons(array("table_id"=>$sTableID, "url"=>$APPLICATION->GetCurPage(),"form"=>"find_form"));
    $oFilter->End();
    ?>
    <input type="hidden" name="parent" value="<?=$_REQUEST["parent"]?>" />
</form>

<?
if($CSotbitMailingTools->ReturnDemo() == 2) CAdminMessage::ShowMessage(array("MESSAGE" => GetMessage($module_id."_DEMO"), "DETAILS" => GetMessage($module_id."_DEMO_DETAILS"), "HTML" => true));
$lAdmin->DisplayList();

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');
?>