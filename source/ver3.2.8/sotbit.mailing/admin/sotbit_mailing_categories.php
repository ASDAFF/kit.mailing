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



//�������� ����
//�������� ����
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);   
if ($POST_RIGHT <= "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));         
} 
//����� �������� ����  




$sTableID = "tbl_sotbit_mailing_categories"; // ID �������
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
  "find_NAME", 
  "find_ACTIVE",   
);

// �������������� ������
$lAdmin->InitFilter($FilterArr);

// ���� ��� �������� ������� ���������, ���������� ���
if (CheckFilter())
{
  // �������� ������ ���������� ��� ������� CRubric::GetList() �� ������ �������� �������
  $arFilter = Array(
    "ID"  => $find_ID,    
    "NAME" => $find_NAME,  
    "ACTIVE" => $find_ACTIVE,     
  );
}

$cData = new CSotbitMailingCategories; 

$cDataMessage = new CSotbitMailingSubscribers; 
// ******************************************************************** //
//                ��������� �������� ��� ���������� ������              //
// ******************************************************************** //



// ���������� ����������������� ���������  
if($POST_RIGHT=="W")
{
  $lAdmin->EditAction();  
  // ������� �� ������ ���������� ���������  
  if(is_array($FIELDS)) { 
  
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

        
        if($res = CSotbitMailingCategories::GetByID($ID)) { 

            
                    
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
    
      $arID = $lAdmin->GroupAction(); 
      // ���� ������� "��� ���� ���������"
      if($_REQUEST['action_target']=='selected')
      {

        $rsData = $cData->GetList(array($by=>$order), $arFilter, false ,array('ID'));
        while($arRes = $rsData->Fetch()) {
            $arID[] = $arRes['ID'];            
        }

          
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
                                 
                    // ��������
                   case "delete":
                        $cData->Delete($ID); 
                   break;                    
                   case "copy":
                        $CopyElement = $cData->GetByID($ID);
                        unset($CopyElement['ID']);
                        $cData->Add($CopyElement);
                   break;
                    // ���������/�����������
                   case "activate":
                        $cData->Update($ID, array("ACTIVE" => "Y"));
                   break;       
                   case "deactivate":
                        $cData->Update($ID, array("ACTIVE" => "N"));
                   break;    
                } 
                 
          }       
          
      }
      

  
  
}
 

// ******************************************************************** //
//                ������� ��������� ������                              //
// ******************************************************************** //


$cData = new CSotbitMailingCategories;
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
  array("id" =>"NAME",
    "content"  =>  GetMessage($module_id.'_list_title_NAME'),   
    "sort"    =>"NAME",
    "default"  =>true,    
  ), 
  array(  "id"    =>"DESCRIPTION",
    "content"  => GetMessage($module_id.'_list_title_DESCRIPTION'),  
    "sort"    =>"ACTIVE",
    "default"  =>true,    
  ),      
  array(  "id"    =>"SUNC_USER",
    "content"  => GetMessage($module_id.'_list_title_SUNC_USER'),  
    "sort"    =>"SUNC_USER",
    "default"  =>true,    
  ),    
  array(  "id"    =>"SUNC_SUBSCRIPTION",
    "content"  => GetMessage($module_id.'_list_title_SUNC_SUBSCRIPTION'),  
    "sort"    =>"SUNC_SUBSCRIPTION",
    "default"  =>true,    
  ),  
  array(  
    "id"    =>"PARAM_1",
    "content"  => GetMessage($module_id.'_list_title_PARAM_1'),  
    "sort"    =>"PARAM_1",
    "default"  =>true,    
  ),   
  array(  
    "id"    =>"PARAM_2",
    "content"  => GetMessage($module_id.'_list_title_PARAM_2'),  
    "sort"    =>"PARAM_2",
    "default"  =>true,    
  ), 
  array(  
    "id"    =>"PARAM_3",
    "content"  => GetMessage($module_id.'_list_title_PARAM_3'),  
    "sort"    =>"PARAM_3",
    "default"  =>true,    
  ),   
  
));





while($arRes = $rsData->NavNext(true, "f_")) {
    $editUrl = "sotbit_mailing_categories_detail.php?ID=".$f_ID."&lang=".LANG;  
    
    
    $row =& $lAdmin->AddRow($f_ID, $arRes);    
    // ����� �������� ����������� �������� ��� ��������� � �������������� ������         
    if($POST_RIGHT>="W") {           
          //�������� NAME ����� ��������������� ��� �����, � ������������ �������
         // $row->AddInputField("NAME_1C", array("size"=>20));    
         $row->AddInputField("NAME", array("size"=>40));
         $row->AddCheckField("ACTIVE");
         
       
         $sHTML = '<textarea rows="5" cols="30" name="FIELDS['.$f_ID.'][DESCRIPTION]">'.$f_DESCRIPTION.'</textarea>';
         $row->AddEditField("DESCRIPTION",$sHTML);         
         
         $row->AddCheckField("SUNC_USER");       
         $row->AddCheckField("SUNC_USER_MESSAGE");
         $row->AddCheckField("SUNC_SUBSCRIPTION");
                         
          // �������� ����������� ���� � ������                                      
    } 
      
      
    if($POST_RIGHT>="W") { 
   
           $arActions = array();
   
                     
           $ACTION_EDIT =  array(
                "ICON"=>"edit",
                "TEXT"=> GetMessage($module_id."_ACTION_EDIT"),
                "ACTION"=>$lAdmin->ActionRedirect($editUrl),
           );  
           /* 
           $ACTION_COPY =  array(
                "ICON"=>"copy",
                "TEXT"=> GetMessage($module_id."_ACTION_COPY"),
                "ACTION"=> $lAdmin->ActionDoGroup($f_ID, "copy"),
           );  */                  
           $ACTION_DELETE =  array(
                "ICON"=>"delete",
                "TEXT"=> GetMessage($module_id."_ACTION_DELETE"),
                "ACTION"=>"if(confirm('".GetMessage($module_id."_ACTION_DELETE_CONFORM", array('#ID#' =>$f_ID))."?')) ".$lAdmin->ActionDoGroup($f_ID, "delete"),
           );
       
            
           $arActions[] = $ACTION_EDIT; 
   
                   
         
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
        "deactivate"=>GetMessage($module_id."_FOOTER_ACTION_DEACTIVATE")      
    ); 
       
}


$lAdmin->AddGroupActionTable($GroupActionTable);

// ******************************************************************** //
//                ���������������� ����                                 //
// ******************************************************************** //
if($POST_RIGHT>="W") {
  

    $aContext = array(
        array(
            "TEXT" => GetMessage($module_id."_MENU_TOP_CATEGORIES_ADD"),
            "LINK" => "sotbit_mailing_categories_detail.php?lang=".LANG,
            "TITLE" => GetMessage($module_id."_MENU_TOP_CATEGORIES_ADD_ALT"),
            "ICON" => "btn_new",
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
$APPLICATION->SetTitle(GetMessage($module_id.'_CATEGORIES_LIST_TITLE'));  

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
    GetMessage($module_id.'_list_title_NAME'),  
    GetMessage($module_id.'_list_title_ACTIVE'),
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
