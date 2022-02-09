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



CSotbitMailingHelp::CacheConstantCheck();
//�������� ����
//�������� ����
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);   
if ($POST_RIGHT <= "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));         
} 
//����� �������� ����  




// ������� ������ � ���������
// START
$MailingList = CSotbitMailingHelp::GetMailingInfo();

// END

$sTableID = "tbl_sotbit_mailing_message"; // ID �������
$oSort = new CAdminSorting($sTableID, "ID", "desc"); // ������ ����������
$lAdmin = new CAdminList($sTableID, $oSort); // �������� ������ ������


// ******************************************************************** //
//                           ������                                     //
// ******************************************************************** //

// *********************** CheckFilter ******************************** //
// �������� �������� ������� ��� �������� ������� � ��������� �������
function CheckFilter()
{
  global $FilterArr, $lAdmin;
  foreach ($FilterArr as $f) global $$f;
   /*
  $find_DATE_CREATE_from = intval($find_DATE_CREATE_from);
  $find_DATE_CREATE_to = intval($find_DATE_CREATE_to);
  */
  if($find_DATE_CREATE_from) {
    $find_DATE_CREATE_from = $find_DATE_CREATE_from.' 00:00:00';       
  } 
  if($find_DATE_CREATE_to) {
    $find_DATE_CREATE_to = $find_DATE_CREATE_to.' 23:59:59';       
  }    
  
  if($find_DATE_SEND_from) {
    $find_DATE_SEND_from = $find_DATE_SEND_from.' 00:00:00';       
  } 
  if($find_DATE_SEND_to) {
    $find_DATE_SEND_to = $find_DATE_SEND_to.' 23:59:59';       
  }    
  // � ������ ������ ��������� ������. 
  // � ����� ������ ����� ��������� �������� ���������� $find_���
  // � � ������ �������������� ������ ���������� �� ����������� 
  // ����������� $lAdmin->AddFilterError('�����_������').
  
  return count($lAdmin->arFilterErrors)==0; // ���� ������ ����, ������ false;
}
// *********************** /CheckFilter ******************************* //


// ������ �������� �������
$FilterArr = Array(
  "find_ID", 
  "find_ID_EVENT", 
  "find_COUNT_RUN_from", 
  "find_COUNT_RUN_to",  
  
  "find_DATE_CREATE_from", 
  "find_DATE_CREATE_to",       
  "find_DATE_SEND_from",  
  "find_DATE_SEND_to", 
  "find_SEND_SYSTEM",   
  "find_SEND",    
//  "find_SUBJECT",      
  "find_EMAIL_TO",  
  "find_STATIC_USER_OPEN",    
  "find_STATIC_USER_BACK",  
  "find_STATIC_SALE_UID", 
  "find_STATIC_GUEST_ID", 
  "find_STATIC_PAGE_START",      
);

// �������������� ������
$lAdmin->InitFilter($FilterArr);



// ���� ��� �������� ������� ���������, ���������� ���
if (CheckFilter())
{
     
  // �������� ������ ���������� ��� ������� CRubric::GetList() �� ������ �������� �������
  $arFilter = Array(
    "ID"  => $find_ID,    
    "ID_EVENT" => $find_ID_EVENT,  
    ">=COUNT_RUN" => $find_COUNT_RUN_from,  
    "<=COUNT_RUN" => $find_COUNT_RUN_to,    
    ">=DATE_CREATE" => $find_DATE_CREATE_from,  
    "<=DATE_CREATE" => $find_DATE_CREATE_to,         
    ">=DATE_SEND" => $find_DATE_SEND_from,  
    "<=DATE_SEND" => $find_DATE_SEND_to,  
    "SEND_SYSTEM" => $find_SEND_SYSTEM,  
    "SEND" => $find_SEND,    
//  "SUBJECT" => $find_SUBJECT, 
    "EMAIL_TO" => $find_EMAIL_TO, 
    "STATIC_USER_OPEN" => $find_STATIC_USER_OPEN,     
    "STATIC_USER_BACK" => $find_STATIC_USER_BACK, 
    "STATIC_SALE_UID" => $find_STATIC_SALE_UID, 
    "STATIC_GUEST_ID" => $find_STATIC_GUEST_ID, 
    "STATIC_GUEST_ID" => $find_STATIC_GUEST_ID, 
    "STATIC_PAGE_START" => $find_STATIC_PAGE_START,                           
  );
}

$cData = new CSotbitMailingMessage; 
// ******************************************************************** //
//                ��������� �������� ��� ���������� ������              //
// ******************************************************************** //



// ���������� ����������������� ���������  
if($POST_RIGHT=="W")
{
  $lAdmin->EditAction();  
  // ������� �� ������ ���������� ���������     
  
  if(is_array($FIELDS)){
 
      foreach($FIELDS as $ID=>$arFields)
      {
        
        if(!$lAdmin->IsUpdated($ID))
          continue;
        
        // �������� ��������� ������� ��������
        $DB->StartTransaction();
        $ID = IntVal($ID);  
     
        if(!$cData->Update($ID, $arFields))
        {
            $lAdmin->AddGroupError(GetMessage($module_id.'_ERROR_DB').' '.$cData->LAST_ERROR, $ID);
            $DB->Rollback();
        }

        global $CACHE_MANAGER; 
        $CACHE_MANAGER->ClearByTag($module_id);       
        $DB->Commit();
      } 
      
  }    

    
  
}
 
 


    

 
 
// ��������� ��������� � ��������� ��������
if($POST_RIGHT=="W")
{
    
        // ������� ����������� ������
      $arID = $lAdmin->GroupAction(); 
      // ���� ������� "��� ���� ���������"
      if($_REQUEST['action_target']=='selected')
      {

        $rsData = $cData->GetList(array($by=>$order), $arFilter, false ,array('ID'));
        while($arRes = $rsData->Fetch()) {
            $arID[] = $arRes['ID'];            
        }

          
      }
      
      if(is_array($arID)){ 
    
          // ������� �� ������ ���������
          foreach($arID as $ID)
          {            
                if(strlen($ID)<=0){
                  continue;            
                }
                
                $ID = IntVal($ID);
                

                // ��� ������� �������� �������� ��������� ��������
                switch($_REQUEST['action'])
                {
                   // �������� �������� 
                   case "send": 
                        CSotbitMailingTools::SendMessage(array('ID' => $ID));         
                   
                   break;               
                    // ��������
                   case "delete":
                        $cData->Delete($ID); 

                   break;
             
                } 
          } 
            
      }

  
  
}
 

// ******************************************************************** //
//                ������� ��������� ������                              //
// ******************************************************************** //


$cData = new CSotbitMailingMessage;
$rsData = $cData->GetList(array($by=>$order), $arFilter);


// ����������� ������ � ��������� ������ CAdminResult
$rsData = new CAdminResult($rsData, $sTableID);

// ���������� CDBResult �������������� ������������ ���������.
$rsData->NavStart();

// �������� ����� ������������� ������� � �������� ������ $lAdmin
$lAdmin->NavText($rsData->GetNavPrint(GetMessage($module_id.'_NAV_TITLE')));

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
  array(  "id"    =>"ID_EVENT",
    "content"  => GetMessage($module_id.'_list_title_ID_EVENT'),  
    "sort"    =>"ID_EVENT",
    "default"  =>true,    
  ),   
  array("id" =>"DATE_CREATE",
    "content"  =>  GetMessage($module_id.'_list_title_DATE_CREATE'),   
    "sort"    =>"DATE_CREATE",
    "default"  =>true,    
  ), 
  array("id" =>"COUNT_RUN",
    "content"  =>  GetMessage($module_id.'_list_title_COUNT_RUN'),   
    "sort"    =>"COUNT_RUN",
    "default"  =>true,    
  ),   
  array(  "id"    =>"SEND",
    "content"  => GetMessage($module_id.'_list_title_SEND'),  
    "sort"    =>"SEND",
    "default"  =>true,    
  ), /*
  array(  "id"    =>"SEND_SYSTEM",
    "content"  => GetMessage($module_id.'_list_title_SEND_SYSTEM'),  
    "sort"    =>"SEND_SYSTEM",
    "default"  =>true,    
  ), */       
  array(  "id"    =>"DATE_SEND",
    "content"  => GetMessage($module_id.'_list_title_DATE_SEND'),  
    "sort"    =>"DATE_SEND",
    "default"  =>true,    
  ), 
  /*
  array(  "id"    =>"SEND_SYSTEM",
    "content"  => GetMessage($module_id.'_list_title_SEND_SYSTEM'),  
    "sort"    =>"SEND_SYSTEM",
    "default"  =>true,    
  ),   */
  array(  "id"    =>"EMAIL_FROM",
    "content"  => GetMessage($module_id.'_list_title_EMAIL_FROM'),  
    "sort"    =>"EMAIL_FROM",
    "default"  =>true,    
  ),  
  array(  "id"    =>"EMAIL_TO",
    "content"  => GetMessage($module_id.'_list_title_EMAIL_TO'),  
    "sort"    =>"EMAIL_TO",
    "default"  =>true,    
  ),  
  array(  "id"    =>"BCC",
    "content"  => GetMessage($module_id.'_list_title_BCC'),  
    "sort"    =>"BCC",
    "default"  =>true,    
  ),    
  array(  "id"    =>"SUBJECT",
    "content"  => GetMessage($module_id.'_list_title_SUBJECT'),  
    "sort"    =>"SUBJECT",
    "default"  =>true,    
  ),
  array(  "id"    =>"STATIC_USER_OPEN",
    "content"  => GetMessage($module_id.'_list_title_STATIC_USER_OPEN'),  
    "sort"    =>"STATIC_USER_OPEN",
    "default"  =>true,    
  ),    
  array(  "id"    =>"STATIC_USER_OPEN_DATE",
    "content"  => GetMessage($module_id.'_list_title_STATIC_USER_OPEN_DATE'),  
    "sort"    =>"STATIC_USER_OPEN_DATE",
    "default"  =>true,    
  ), 
      
  array(  "id"    =>"STATIC_USER_BACK",
    "content"  => GetMessage($module_id.'_list_title_STATIC_USER_BACK'),  
    "sort"    =>"STATIC_USER_BACK",
    "default"  =>true,    
  ),    
  array(  "id"    =>"STATIC_USER_BACK_DATE",
    "content"  => GetMessage($module_id.'_list_title_STATIC_USER_BACK_DATE'),  
    "sort"    =>"STATIC_USER_BACK_DATE",
    "default"  =>true,    
  ),       

  array(  "id"    =>"STATIC_USER_ID",
    "content"  => GetMessage($module_id.'_list_title_STATIC_USER_ID'),  
    "sort"    =>"STATIC_USER_ID",
    "default"  =>true,    
  ),      
  array(  "id"    =>"STATIC_SALE_UID",
    "content"  => GetMessage($module_id.'_list_title_STATIC_SALE_UID'),  
    "sort"    =>"STATIC_SALE_UID",
    "default"  =>true,    
  ),      
  array(  "id"    =>"STATIC_GUEST_ID",
    "content"  => GetMessage($module_id.'_list_title_STATIC_GUEST_ID'),  
    "sort"    =>"STATIC_GUEST_ID",
    "default"  =>true,    
  ),      
  array(  "id"    =>"STATIC_PAGE_START",
    "content"  => GetMessage($module_id.'_list_title_STATIC_PAGE_START'),  
    "sort"    =>"STATIC_PAGE_START",
    "default"  =>true,    
  ),            
  array(  "id"    =>"STATIC_ORDER_ID",
    "content"  => GetMessage($module_id.'_list_title_STATIC_ORDER_ID'),  
    "sort"    =>"STATIC_ORDER_ID",
    "default"  =>true,    
  ),   
  
 
  
  
  
));


$arResMessage = array();
while($arRes = $rsData->Fetch()) { 
   
    $arrMes = CSotbitMailingMessageText::GetByMessegeID($arRes['ID']);       
    $arRes['SUBJECT'] = $arrMes['SUBJECT'];
    
    $arResMessage[$arRes['ID']] = $arRes;        
    $ID_messege_arr[] = $arRes['ID'];
} 
      




foreach($arResMessage as $arRes) {
    $editUrl = "sotbit_mailing_message_detail.php?ID=".$arRes['ID']."&lang=".LANG;  
      
    $row =& $lAdmin->AddRow($arRes['ID'], $arRes);    
    // ����� �������� ����������� �������� ��� ��������� � �������������� ������         
    if($POST_RIGHT>="W") {           
          //�������� NAME ����� ��������������� ��� �����, � ������������ �������
         // $row->AddInputField("NAME_1C", array("size"=>20));    
         $row->AddInputField("EMAIL_FROM", array("size"=>40));
         $row->AddInputField("EMAIL_TO", array("size"=>40));
         $row->AddInputField("BCC", array("size"=>40));
         //$row->AddInputField("SUBJECT", array("size"=>40));
                      
         $row_SEND_SYSTEM =  GetMessage($module_id."_list_title_SEND_SYSTEM_VALUE_".$arRes['SEND_SYSTEM']);                    
         $row->AddViewField("SEND_SYSTEM", $row_SEND_SYSTEM);         
                  
         $row->AddCheckField("SEND");
            
         // ������ �� ������������ ������
         if($arRes['STATIC_USER_OPEN'] == 'Y') {
            $row_STATIC_USER_OPEN = GetMessage($module_id.'_YES');           
         } 
         else {
            $row_STATIC_USER_OPEN = GetMessage($module_id.'_NO');                     
         }
         $row->AddViewField("STATIC_USER_OPEN", $row_STATIC_USER_OPEN);
                           
         //���� �������� ������
         $row_STATIC_USER_OPEN_DATE = '';   
         $arr_row_STATIC_USER_OPEN_DATE = unserialize($arRes['STATIC_USER_OPEN_DATE']);  
         if(is_array($arr_row_STATIC_USER_OPEN_DATE)){   
             foreach($arr_row_STATIC_USER_OPEN_DATE as $k => $v) {
                 $count = $k+1;
                 $row_STATIC_USER_OPEN_DATE .= '('.$count.') '.$v.'<br />';       
             }                        
         } 
 
         $row->AddViewField("STATIC_USER_OPEN_DATE", $row_STATIC_USER_OPEN_DATE);
         
         // ������ �� ������������ ������
         if($arRes['STATIC_USER_BACK'] == 'Y') {
            $row_STATIC_USER_BACK = GetMessage($module_id.'_YES');           
         } 
         else {
            $row_STATIC_USER_BACK = GetMessage($module_id.'_NO');                     
         }
         $row->AddViewField("STATIC_USER_BACK", $row_STATIC_USER_BACK);
         

                  
         //���� �����������
         $row_STATIC_USER_BACK_DATE = '';   
         $arr_row_STATIC_USER_BACK_DATE = unserialize($arRes['STATIC_USER_BACK_DATE']); 
         if(is_array($arr_row_STATIC_USER_BACK_DATE)){  
        
             foreach($arr_row_STATIC_USER_BACK_DATE as $k => $v) {
                 $count = $k+1;
                 $row_STATIC_USER_BACK_DATE .= '('.$count.') '.$v.'<br />';       
             }              
             
         }           
  
         $row->AddViewField("STATIC_USER_BACK_DATE", $row_STATIC_USER_BACK_DATE);
                     
         //id ������������
         //START
         $row_STATIC_USER_ID = '';   
         $arr_row_STATIC_USER_ID = unserialize($arRes['STATIC_USER_ID']);  
         if(is_array($arr_row_STATIC_USER_ID)){ 
            
             foreach($arr_row_STATIC_USER_ID as $k => $v) {             
                 $count = $k+1;
                 if($v == 'N') {
                    $v = GetMessage($module_id."_list_title_NO_INFO");       
                 } else {
                    $v = '<a target="_blank" href="user_edit.php?ID='.$v.'&lang='.LANG.'">'.$v.'</a>';                    
                 }
                 
                 $row_STATIC_USER_ID .= '('.$count.') '.$v.'<br />';       
             }              
                      
         } 
 
         $row->AddViewField("STATIC_USER_ID", $row_STATIC_USER_ID);
         //END
         
         //id ��������� �������
         //START
         $row_STATIC_SALE_UID = '';   
         $arr_row_STATIC_SALE_UID = unserialize($arRes['STATIC_SALE_UID']);  
         if(is_array($arr_row_STATIC_SALE_UID)){
     
             foreach($arr_row_STATIC_SALE_UID as $k => $v) {
                 $count = $k+1;
                 if($v == 'N') {
                    $v = GetMessage($module_id."_list_title_NO_INFO");       
                 }
                 
                 $row_STATIC_SALE_UID .= '('.$count.') '.$v.'<br />';       
             }               
         } 
 
         $row->AddViewField("STATIC_SALE_UID", $row_STATIC_SALE_UID);         
         //END
         
         
         // id ����� �� ���-���������
         //START
         $row_STATIC_GUEST_ID = '';   
         $arr_row_STATIC_GUEST_ID = unserialize($arRes['STATIC_GUEST_ID']); 
         if(is_array($arr_row_STATIC_GUEST_ID)){ 
           
             foreach($arr_row_STATIC_GUEST_ID as $k => $v) {
                 $count = $k+1;
                 if($v == 'N') {
                    $v = GetMessage($module_id."_list_title_NO_INFO");       
                 } else {
                    $v = '<a target="_blank" href="guest_list.php?set_filter=Y&find_id='.$v.'&lang='.LANG.'">'.$v.'</a>';   
                 }
                 
                 $row_STATIC_GUEST_ID .= '('.$count.') '.$v.'<br />';       
             }             
                      
         }  
 
         $row->AddViewField("STATIC_GUEST_ID", $row_STATIC_GUEST_ID);           
         //END     
       
         // ���������� � �������� ���� �������
         //START
         $row_STATIC_PAGE_START = '';   
         $arr_row_STATIC_PAGE_START = unserialize($arRes['STATIC_PAGE_START']); 
         if(is_array($arr_row_STATIC_PAGE_START)){ 
    
             foreach($arr_row_STATIC_PAGE_START as $k => $v) {
                 $count = $k+1;
                 if($v == 'N') {
                    $v = GetMessage($module_id."_list_title_NO_INFO");       
                 }
                 
                 
                if(strpos($v,'MAILING_MESSAGE='.$arRes['ID'])) {
                    $v = str_replace("MAILING_MESSAGE=".$arRes['ID'], "", $v);      
                } 
                   
                 $row_STATIC_PAGE_START .= '('.$count.') <a href="'.$v.'">'.$v.'</a><br />';       
             }      
             
         }           
 
         $row->AddViewField("STATIC_PAGE_START", $row_STATIC_PAGE_START);           
         //END                        
                         
         $row_ID_EVENT = '['.$arRes['ID_EVENT'].'] '.$MailingList[$arRes['ID_EVENT']]['NAME']  ;                    
         $row->AddViewField("ID_EVENT", $row_ID_EVENT);
       

          // �������� ����������� ���� � ������
                                       
    } 
      
      
    if($POST_RIGHT>="W") { 
   
           $arActions = array();
           
           $ACTION_VIEW =  array(
                "ICON"=>"view",
                "TEXT"=> GetMessage($module_id."_ACTION_VIEW"),
                "ACTION"=>$lAdmin->ActionRedirect($editUrl),
           ); 

           $ACTION_SEND =  array(          
                "TEXT"=> GetMessage($module_id."_ACTION_SEND"),
                "ACTION"=> $lAdmin->ActionDoGroup($arRes['ID'], "send"), 
           );  
                  
           $ACTION_DELETE =  array(
                "ICON"=>"delete",
                "TEXT"=> GetMessage($module_id."_ACTION_DELETE"),
                "ACTION"=>"if(confirm('".GetMessage($module_id."_ACTION_DELETE_CONFORM", array('#ID#' =>$arRes['ID']))."?')) ".$lAdmin->ActionDoGroup($arRes['ID'], "delete"),
           );
           
           $arActions[] = $ACTION_VIEW; 
           if($arRes['SEND'] != 'Y') {
                $arActions[] = $ACTION_SEND;                 
           }
                
           $arActions[] = $ACTION_DELETE;          
            
    }   
  
    $row->AddActions($arActions); 

  
}

// ������ �������
$lAdmin->AddFooter(
  array(
    array(
        "title"=> GetMessage("MAIN_ADMIN_LIST_SELECTED"), 
        "value"=>$rsData->SelectedRowsCount()), // ���-�� ���������
        array(
            "counter"=>true, 
            "title"=>GetMessage("MAIN_ADMIN_LIST_CHECKED"), 
            "value"=>"0"
        ), // ������� ��������� ���������
  )
);

// ��������� ��������
if($POST_RIGHT>="W") {

    $GroupActionTable = array(
        "send"=>GetMessage($module_id."_FOOTER_ACTION_SEND"),     
        "delete"=>GetMessage($module_id."_FOOTER_ACTION_DEL"),     
    ); 
       
}


$lAdmin->AddGroupActionTable($GroupActionTable);

// ******************************************************************** //
//                ���������������� ����                                 //
// ******************************************************************** //
if($POST_RIGHT>="W") {
  
    // ���������� ���� �������� ��������

    if(is_array($aContext)) {
        $lAdmin->AddAdminContextMenu($aContext);          
    } else {
        $lAdmin->AddAdminContextMenu(array());          
    }
    
    
    
}    

 


// ******************************************************************** //
//                �����                                                 //
// ******************************************************************** //

// �������������� �����
$lAdmin->CheckListMode();

// ��������� ��������� ��������
$APPLICATION->SetTitle(GetMessage($module_id.'_MESSAGE_LIST_TITLE'));  

// �� ������� ��������� ���������� ������ � �����
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

// ******************************************************************** //
//                ����� �������                                         //
// ******************************************************************** //
 
 
// �������� ������ �������
$oFilter = new CAdminFilter(
  $sTableID."_filter",
  array(
    GetMessage($module_id.'_list_title_ID'),
    GetMessage($module_id.'_list_title_ID_EVENT'), 
    GetMessage($module_id.'_list_title_COUNT_RUN'),     
    GetMessage($module_id.'_list_title_DATE_CREATE'),
    GetMessage($module_id.'_list_title_SEND'),
    GetMessage($module_id.'_list_title_DATE_SEND'), 
    GetMessage($module_id.'_list_title_SEND_SYSTEM'),
    GetMessage($module_id.'_list_title_EMAIL_TO'), 
    //GetMessage($module_id.'_list_title_SUBJECT'),     
    GetMessage($module_id.'_list_title_STATIC_USER_OPEN'),      
    GetMessage($module_id.'_list_title_STATIC_USER_BACK'),     
    GetMessage($module_id.'_list_title_STATIC_SALE_UID'),     
    GetMessage($module_id.'_list_title_STATIC_GUEST_ID'), 
    GetMessage($module_id.'_list_title_STATIC_PAGE_START'),                 
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
        <td><?=GetMessage($module_id.'_list_title_ID_EVENT')?>:</td>
        <td>
            <select name="find_ID_EVENT">
                <option value=""><?=htmlspecialcharsex(GetMessage($module_id.'_ANY'))?></option>
                <?foreach($MailingList as $MailingItem):?>
                <option value="<?=$MailingItem['ID']?>"<?if($find_ID_EVENT==$MailingItem['ID'])echo " selected"?>>[<?=$MailingItem['ID']?>] <?=$MailingItem['NAME']?></option>                
                <?endforeach;?>
            </select>        
        
        </td>
    </tr>
    
    <tr>
        <td><?=GetMessage($module_id.'_list_title_COUNT_RUN')?>:</td>
        <td>
            <script type="text/javascript">
                function find_COUNT_RUN_from_Change()
                {
                    if (document.find_form.find_COUNT_RUN_to.value.length<=0)
                    {
                        document.find_form.find_COUNT_RUN_to.value = document.find_form.find_COUNT_RUN_from.value;
                    }
                }
            </script>
            <?=GetMessage($module_id.'_FROM')?> 
            <input type="text" name="find_COUNT_RUN_from" OnChange="find_COUNT_RUN_from_Change()" value="<?echo (IntVal($find_COUNT_RUN_from)>0)?IntVal($find_COUNT_RUN_from):""?>" size="10">
            <?=GetMessage($module_id.'_TO')?> 
            <input type="text" name="find_COUNT_RUN_to" value="<?echo (IntVal($find_COUNT_RUN_to)>0)?IntVal($find_COUNT_RUN_to):""?>" size="10">
        </td>
    </tr>    
    
    
    <tr>
        <td><?=GetMessage($module_id.'_list_title_DATE_CREATE')?>:</td>
        <td>   
            <?echo CalendarPeriod("find_DATE_CREATE_from", $filter_DATE_CREATE_from, "find_DATE_CREATE_to", $filter_DATE_CREATE_to, "find_form", "Y")?>
        </td>
    </tr>    
    

    <tr>
        <td><?echo GetMessage($module_id."_list_title_SEND")?>:</td>
        <td>
            <select name="find_SEND">
                <option value=""><?=htmlspecialcharsex(GetMessage($module_id.'_ANY'))?></option>
                <option value="Y"<?if($find_SEND=="Y")echo " selected"?>><?=htmlspecialcharsex(GetMessage($module_id."_YES"))?></option>
                <option value="N"<?if($find_SEND=="N")echo " selected"?>><?=htmlspecialcharsex(GetMessage($module_id."_NO"))?></option>
            </select>
        </td>
    </tr>
   
    <tr>
        <td><?=GetMessage($module_id.'_list_title_DATE_SEND')?>:</td>
        <td>
            <?echo CalendarPeriod("find_DATE_SEND_from", $filter_DATE_SEND_from, "find_DATE_SEND_to", $filter_DATE_SEND_to, "find_form", "Y")?>
        </td>
    </tr> 
    
    <tr>
        <td><?echo GetMessage($module_id."_list_title_SEND_SYSTEM")?>:</td>
        <td>
            <select name="find_SEND_SYSTEM">
                <option value=""><?=htmlspecialcharsex(GetMessage($module_id.'_ANY'))?></option>
                <option value="BITRIX"<?if($find_SEND_SYSTEM=="BITRIX")echo " selected"?>><?=htmlspecialcharsex(GetMessage($module_id."_list_title_SEND_SYSTEM_VALUE_BITRIX"))?></option>
                <option value="UNISENDER"<?if($find_SEND_SYSTEM=="UNISENDER")echo " selected"?>><?=htmlspecialcharsex(GetMessage($module_id."_list_title_SEND_SYSTEM_VALUE_UNISENDER"))?></option>
            </select>
        </td>
    </tr>    
    
    <tr>
        <td><?=GetMessage($module_id.'_list_title_EMAIL_TO')?>:</td>
        <td>
            <input type="text" name="find_EMAIL_TO" size="20" value="<?echo htmlspecialchars($find_EMAIL_TO)?>">
        </td>
    </tr>
    
   <?  /*
    <tr>
        <td><?=GetMessage($module_id.'_list_title_SUBJECT')?>:</td>
        <td>
            <input type="text" name="find_SUBJECT" size="20" value="<?echo htmlspecialchars($find_SUBJECT)?>">
        </td>
    </tr>    
    */ 
    ?>
  
    <tr>
        <td><?echo GetMessage($module_id."_list_title_STATIC_USER_OPEN")?>:</td>
        <td>
            <select name="find_STATIC_USER_OPEN">
                <option value=""><?=htmlspecialcharsex(GetMessage($module_id.'_ANY'))?></option>
                <option value="Y"<?if($find_STATIC_USER_OPEN=="Y")echo " selected"?>><?=htmlspecialcharsex(GetMessage($module_id."_YES"))?></option>
                <option value="N"<?if($find_STATIC_USER_OPEN=="N")echo " selected"?>><?=htmlspecialcharsex(GetMessage($module_id."_NO"))?></option>
            </select>
        </td>
    </tr>      
  
    <tr>
        <td><?echo GetMessage($module_id."_list_title_STATIC_USER_BACK")?>:</td>
        <td>
            <select name="find_STATIC_USER_BACK">
                <option value=""><?=htmlspecialcharsex(GetMessage($module_id.'_ANY'))?></option>
                <option value="Y"<?if($find_STATIC_USER_BACK=="Y")echo " selected"?>><?=htmlspecialcharsex(GetMessage($module_id."_YES"))?></option>
                <option value="N"<?if($find_STATIC_USER_BACK=="N")echo " selected"?>><?=htmlspecialcharsex(GetMessage($module_id."_NO"))?></option>
            </select>
        </td>
    </tr>  
    
    <tr>
        <td><?=GetMessage($module_id.'_list_title_STATIC_SALE_UID')?>:</td>
        <td>
            <input type="text" name="find_STATIC_SALE_UID" size="20" value="<?echo htmlspecialchars($find_STATIC_SALE_UID)?>">
        </td>
    </tr>   
    
    <tr>
        <td><?=GetMessage($module_id.'_list_title_STATIC_GUEST_ID')?>:</td>
        <td>
            <input type="text" name="find_STATIC_GUEST_ID" size="20" value="<?echo htmlspecialchars($find_STATIC_GUEST_ID)?>">
        </td>
    </tr>         
    
    <tr>
        <td><?=GetMessage($module_id.'_list_title_STATIC_PAGE_START')?>:</td>
        <td>
            <input type="text" name="find_STATIC_PAGE_START" size="20" value="<?echo htmlspecialchars($find_STATIC_PAGE_START)?>">
        </td>
    </tr> 
  
    
    
    
<?
$oFilter->Buttons(array("table_id"=>$sTableID, "url"=>$APPLICATION->GetCurPage(),"form"=>"find_form"));
$oFilter->End();

?>
</form>




<?
if($CSotbitMailingTools->ReturnDemo() == 2) CAdminMessage::ShowMessage(array("MESSAGE" => GetMessage($module_id."_DEMO"), "DETAILS" => GetMessage($module_id."_DEMO_DETAILS"), "HTML" => true));
$lAdmin->DisplayList();
?>


<?
//���������� ��������
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>