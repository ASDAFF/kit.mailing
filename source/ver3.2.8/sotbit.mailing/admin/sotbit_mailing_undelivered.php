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





//������� ��� ��� ��������
//START 
global $CACHE_MANAGER;    
$CACHE_MANAGER->ClearByTag($module_id.'_GetUndeliveredMailing'); 

//END 

// ������� ������ � ���������
// START
$MailingList = CSotbitMailingHelp::GetMailingInfo();

// END

$sTableID = "tbl_sotbit_mailing_undelivered"; // ID �������
$oSort = new CAdminSorting($sTableID, "ID", "asc"); // ������ ����������
$lAdmin = new CAdminList($sTableID, $oSort); // �������� ������ ������


// ******************************************************************** //
//                           ������                                     //
// ******************************************************************** //

// *********************** CheckFilter ******************************** //
// �������� �������� ������� ��� �������� ������� � ��������� �������
function CheckFilter()
{
  global $FilterArr, $lAdmin;
  if(is_array($FilterArr)) { 
    foreach ($FilterArr as $f) global $$f;          
  }


  
  if($find_DATE_CREATE_from) {
    $find_DATE_CREATE_from = $find_DATE_CREATE_from.' 00:00:00';       
  } 
  if($find_DATE_CREATE_to) {
    $find_DATE_CREATE_to = $find_DATE_CREATE_to.' 23:59:59';       
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
  "find_ACTIVE",  
  "find_DATE_CREATE_from", 
  "find_DATE_CREATE_to",    
  "find_ID_EVENT",       
  "find_EMAIL_TO",
  "find_SPAM",  
  "find_ERROR_CODE",   
);

// �������������� ������
$lAdmin->InitFilter($FilterArr);

// ���� ��� �������� ������� ���������, ���������� ���
if (CheckFilter())
{
  // �������� ������ ���������� ��� ������� CRubric::GetList() �� ������ �������� �������
  $arFilter = Array(
    "ID"  => $find_ID,  
    "ACTIVE" => $find_ACTIVE,
    ">=DATE_CREATE" => $find_DATE_CREATE_from,  
    "<=DATE_CREATE" => $find_DATE_CREATE_to,            
    "ID_EVENT" => $find_ID_EVENT,  
    "EMAIL_TO" => $find_EMAIL_TO,  
    "SPAM" => $find_SPAM,   
    "ERROR_CODE" => $find_ERROR_CODE,                         
  );
}

$cData = new CSotbitMailingUndelivered; 
// ******************************************************************** //
//                ��������� �������� ��� ���������� ������              //
// ******************************************************************** //



// ���������� ����������������� ���������  
if($POST_RIGHT=="W")
{
  $lAdmin->EditAction(); 
  
  if(is_array($FIELDS)) {  
 
      // ������� �� ������ ���������� ���������         
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

        $rsData = $cData->GetList(array($by=>$order), $arFilter, false , array('ID','ID_EVENT'));
        while($arRes = $rsData->Fetch()) {
            $arID[] = $arRes['ID'];     
        }

          
      }
      
      //������� EVENT  
      $rsData = $cData->GetList(array($by=>$order), array('ID' => $arID), false , array('ID','ID_EVENT'));
      while($arRes = $rsData->Fetch()) {   
        $arIDEvent[$arRes['ID']] = $arRes['ID_EVENT'];
      }
      
      if(is_array($arID)) {  
            
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
                   // ���������
                   case "activate":
                        $cData->Update($ID, array("ACTIVE" => "Y")); 
                   break;   
                   // �����������    
                   case "deactivate":
                        $cData->Update($ID, array("ACTIVE" => "N")); 
                   break;              
                    // ��������
                   case "delete":
                        $cData->Delete($ID); 
                   break;
                   
             
                }    
                
                global $CACHE_MANAGER;    
                $CACHE_MANAGER->ClearByTag($module_id.'_GetUndeliveredMailing');             
                 
          }    
            
      }

      
      
  
  
}
 

// ******************************************************************** //
//                ������� ��������� ������                              //
// ******************************************************************** //


$cData = new CSotbitMailingUndelivered;
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
  array(  "id"    =>"ACTIVE",
    "content"  => GetMessage($module_id.'_list_title_ACTIVE'),  
    "sort"    =>"ACTIVE",
    "default"  =>true,    
  ),    
  array("id" =>"DATE_CREATE",
    "content"  =>  GetMessage($module_id.'_list_title_DATE_CREATE'),   
    "sort"    =>"DATE_CREATE",
    "default"  =>true,    
  ),   
  array(  "id"    =>"ID_EVENT",
    "content"  => GetMessage($module_id.'_list_title_ID_EVENT'),  
    "sort"    =>"ID_EVENT",
    "default"  =>true,    
  ),     
  array(  "id"    =>"EMAIL_TO",
    "content"  => GetMessage($module_id.'_list_title_EMAIL_TO'),  
    "sort"    =>"EMAIL_TO",
    "default"  =>true,    
  ),  
  array(  "id"    =>"SPAM",
    "content"  => GetMessage($module_id.'_list_title_SPAM'),  
    "sort"    => "SPAM",
    "default"  => true,    
  ),   
  array(  "id"    =>"ERROR_CODE",
    "content"  => GetMessage($module_id.'_list_title_ERROR_CODE'),  
    "sort"    => "ERROR_CODE",
    "default"  => true,    
  ),  
));





while($arRes = $rsData->NavNext(true, "f_", false)) {
    $editUrl = "sotbit_mailing_undelivered_detail.php?ID=".$f_ID."&lang=".LANG;  
    
    
    $row =& $lAdmin->AddRow($f_ID, $arRes);    
    // ����� �������� ����������� �������� ��� ��������� � �������������� ������         
    if($POST_RIGHT>="W") {           
          //�������� NAME ����� ��������������� ��� �����, � ������������ �������
         // $row->AddInputField("NAME_1C", array("size"=>20));    
         //$row->AddViewField("EMAIL_TO", '<a href="sotbit_mailing_undelivered_detail.php?ID='.$f_ID.'&amp;lang='.LANG.'" title="'.GetMessage("subscr_upd").'">'.$f_EMAIL_TO.'</a>');
                  
         $row->AddCheckField("ACTIVE");
    
         $row_ID_EVENT = '[<a target="_blank" href="sotbit_mailing_detail.php?ID='.$f_ID_EVENT.'&SOTBIT_MAILING_DETAIL=Y&lang='.LANG.'">'.$f_ID_EVENT.'</a>] '.$MailingList[$f_ID_EVENT]['NAME']  ;                    
         $row->AddViewField("ID_EVENT", $row_ID_EVENT);
          // �������� ����������� ���� � ������
 
         $row->AddCheckField("SPAM"); 
                                       
    } 
      
      
    if($POST_RIGHT>="W") { 
   
           $arActions = array();
                   
                  
           $ACTION_DELETE =  array(
                "ICON"=>"delete",
                "TEXT"=> GetMessage($module_id."_ACTION_DELETE"),
                "ACTION"=>"if(confirm('".GetMessage($module_id."_ACTION_DELETE_CONFORM", array('#ID#' =>$f_ID))."?')) ".$lAdmin->ActionDoGroup($f_ID, "delete"),
           );
                   
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
        "delete"=>GetMessage($module_id."_FOOTER_ACTION_DEL"),    
        "activate"=>GetMessage($module_id."_FOOTER_ACTION_ACTIVATE"),
        "deactivate"=>GetMessage($module_id."_FOOTER_ACTION_DEACTIVATE"),    
 
    ); 
       
}


$lAdmin->AddGroupActionTable($GroupActionTable);

// ******************************************************************** //
//                ���������������� ����                                 //
// ******************************************************************** //
if($POST_RIGHT>="W") {
  
    // ���������� ���� �������� ��������

    

    
    $aContext = array(
        array(
            "TEXT" => GetMessage($module_id."_MENU_TOP_MAILING_IMPORT"),
            "ICON" => "btn_new",
            "LINK" => "sotbit_mailing_undelivered_import.php?lang=".LANG,
            "TITLE" => GetMessage($module_id."_MENU_TOP_MAILING_IMPORT_ALT"),
        ),        
    );
    $lAdmin->AddAdminContextMenu($aContext);     
    
    
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
    GetMessage($module_id.'_list_title_ACTIVE'),
    GetMessage($module_id.'_list_title_DATE_CREATE'),        
    GetMessage($module_id.'_list_title_ID_EVENT'),  
    GetMessage($module_id.'_list_title_EMAIL_TO'), 
    GetMessage($module_id.'_list_title_SPAM'),   
    GetMessage($module_id.'_list_title_ERROR_CODE'),                  
  )   
);

CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage($module_id."_INFO_PAGE",array()), "TYPE"=>"OK"));  
 
?>

<form name="find_form" method="get" action="<?echo $APPLICATION->GetCurPage();?>">
<?$oFilter->Begin();?>

    <tr>
        <td><?=GetMessage($module_id.'_list_title_ID')?>:</td>
        <td>
            <input type="text" name="find_ID" size="20" value="<?echo htmlspecialchars($find_ID)?>">
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
        <td><?=GetMessage($module_id.'_list_title_DATE_CREATE')?>:</td>
        <td>
            <?echo CalendarPeriod("filter_DATE_CREATE_from", $filter_DATE_CREATE_from, "filter_DATE_CREATE_to", $filter_DATE_CREATE_to, "find_form", "Y")?>
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
        <td><?=GetMessage($module_id.'_list_title_EMAIL_TO')?>:</td>
        <td>
            <input type="text" name="find_EMAIL_TO" size="20" value="<?echo htmlspecialchars($find_EMAIL_TO)?>">
        </td>
    </tr>
    
    <tr>
        <td><?echo GetMessage($module_id."_list_title_SPAM")?>:</td>
        <td>
            <select name="find_SPAM">
                <option value=""><?=htmlspecialcharsex(GetMessage($module_id.'_ANY'))?></option>
                <option value="Y"<?if($find_SPAM=="Y")echo " selected"?>><?=htmlspecialcharsex(GetMessage($module_id."_YES"))?></option>
                <option value="N"<?if($find_SPAM=="N")echo " selected"?>><?=htmlspecialcharsex(GetMessage($module_id."_NO"))?></option>
            </select>
        </td>
    </tr>
    
    <tr>
        <td><?=GetMessage($module_id.'_list_title_ERROR_CODE')?>:</td>
        <td>
            <input type="text" name="find_ERROR_CODE" size="20" value="<?echo htmlspecialchars($find_ERROR_CODE)?>">
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
 
CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage($module_id."_ERROR_CODE_ANSWER",array()), "TYPE"=>"OK")); 

//���������� ��������
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>