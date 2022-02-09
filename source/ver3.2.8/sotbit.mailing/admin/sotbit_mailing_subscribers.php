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


// ������� ������ � ���������� ��������
// START
$categoriesList = CSotbitMailingHelp::GetCategoriesInfo();
// END



$sTableID = "tbl_sotbit_mailing_subscribers"; // ID �������
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
  "find_USER_ID", 
  "find_NAME",     
  "find_EMAIL_TO",         
  "find_CATEGORIES_ID",         
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
    "USER_ID" => $find_USER_ID,  
    "NAME" => $find_NAME,     
    "EMAIL_TO" => $find_EMAIL_TO,          
    "CATEGORIES_ID" => $find_CATEGORIES_ID,                            
  );
}

$cData = new CSotbitMailingSubscribers; 
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
                    // �������� � ������
                   case "add_categories":
                        if($_REQUEST['categories_to_move']){
                            CSotbitMailingSubscribers::SubscribersAdd(array('SUBSCRIBERS_ID'=>$ID,'CATEGORIES_ID'=>$_REQUEST['categories_to_move']),array('ACTION_DELETE'=>'NO_DELETE'));                             
                        }     
                   break;                   
                    // ������� �� ������
                   case "delete_categories":
                        if($_REQUEST['categories_to_move']){
                            CSotbitMailingSubscribers::SubscribersCategoriesDelete($ID, $_REQUEST['categories_to_move']);                             
                        }     
                   break; 


                }    
                              
          }    
            
      }

      // ������� ��� ��� �������
      // START
      global $CACHE_MANAGER; 
      $CACHE_MANAGER->ClearByTag($module_id.'_GetCategoriesInfo');
      // END       
      
  
  
}
 

// ******************************************************************** //
//                ������� ��������� ������                              //
// ******************************************************************** //




$cData = new CSotbitMailingSubscribers;
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
  array(  
    "id"    =>"ACTIVE",
    "content"  => GetMessage($module_id.'_list_title_ACTIVE'),  
    "sort"    =>"ACTIVE",
    "default"  =>true,    
  ),    
  array("id" =>"DATE_CREATE",
    "content"  =>  GetMessage($module_id.'_list_title_DATE_CREATE'),   
    "sort"    =>"DATE_CREATE",
    "default"  =>true,    
  ), 
  array(  "id"    =>"NAME",
    "content"  => GetMessage($module_id.'_list_title_NAME'),  
    "sort"    =>"NAME",
    "default"  =>true,    
  ),     
  array(  "id"    =>"EMAIL_TO",
    "content"  => GetMessage($module_id.'_list_title_EMAIL_TO'),  
    "sort"    =>"EMAIL_TO",
    "default"  =>true,    
  ),    
  array(  "id"    =>"USER_ID",
    "content"  => GetMessage($module_id.'_list_title_USER_ID'),  
    "sort"    =>"USER_ID",
    "default"  =>true,    
  ),  
  array(  "id"    =>"CATEGORIES_ID",
    "content"  => GetMessage($module_id.'_list_title_CATEGORIES_ID'),  
    "sort"    =>"CATEGORIES_ID",
    "default"  =>true,    
  ),  

  array(  "id"    =>"STATIC_PAGE_SIGNED",
    "content"  => GetMessage($module_id.'_list_title_STATIC_PAGE_SIGNED'),  
    "sort"    =>"STATIC_PAGE_SIGNED",
    "default"  =>true,    
  ),   
  array(  "id"    =>"STATIC_PAGE_CAME",
    "content"  => GetMessage($module_id.'_list_title_STATIC_PAGE_CAME'),  
    "sort"    =>"STATIC_PAGE_CAME",
    "default"  =>true,    
  ),    
  array(  "id"    =>"SOURCE",
    "content"  => GetMessage($module_id.'_list_title_SOURCE'),  
    "sort"    =>"SOURCE",
    "default"  =>true,    
  ), 
  
));


//������� ���� �������������
//START
$arUsers = array();
$resUsers = CUser::GetList(($byUser="LAST_NAME"), ($orderUser="asc"), array(), array( 'FIELDS' => array('ID','NAME','LAST_NAME','LOGIN')  )); // �������� ������������� 
while($itemUsers  = $resUsers->Fetch())  {
    $arUsers[$itemUsers['ID']] = $itemUsers;  
} 
//END

//������� ��� ��������� ��������
//START
$categoriesLi = CSotbitMailingHelp::GetCategoriesInfo();
//END


while($arRes = $rsData->NavNext(true, "f_", false)) {
    $editUrl = "sotbit_mailing_subscribers_detail.php?ID=".$f_ID."&lang=".LANG;  
    
    
    $row =& $lAdmin->AddRow($f_ID, $arRes);    
    // ����� �������� ����������� �������� ��� ��������� � �������������� ������         
    if($POST_RIGHT>="W") {           
          //�������� NAME ����� ��������������� ��� �����, � ������������ �������
         // $row->AddInputField("NAME_1C", array("size"=>20));    
         $row->AddViewField("EMAIL_TO", '<a href="sotbit_mailing_subscribers_detail.php?ID='.$f_ID.'&amp;lang='.LANG.'" title="'.GetMessage("subscr_upd").'">'.$f_EMAIL_TO.'</a>');
         $row->AddCheckField("ACTIVE");         
    
    
        if($f_USER_ID > 0 && $arUsers[$f_USER_ID]) {
            $strUser = "[<a class='tablebodylink' href=\"/bitrix/admin/user_edit.php?ID=".$f_USER_ID."&amp;lang=".LANG."\"  >".$f_USER_ID."</a>] (".$arUsers[$f_USER_ID]['LOGIN'].") ".$arUsers[$f_USER_ID]['NAME']." ".$arUsers[$f_USER_ID]['LAST_NAME'];               
        }
        else  {
            $strUser = GetMessage($module_id."_MAILING_ANONIM");            
        }
         $row->AddViewField("USER_ID", $strUser);    

        
        
         
         
         //������� ��������� ��������
         $catInfo = CSotbitMailingSubscribers::GetCategoriesBySubscribers($f_ID);
         $row_CATEGORIES_ID = '';
         if($catInfo) {
            foreach($catInfo as $c) {
                if($categoriesLi[$c]) {
                    $row_CATEGORIES_ID .= '<p style="margin: 0px 0px 4px 0px;">[<a target="_blank" href="sotbit_mailing_categories_detail.php?ID='.$categoriesLi[$c]['ID'].'&SOTBIT_MAILING_DETAIL=Y&lang='.LANG.'">'.$categoriesLi[$c]['ID'].'</a>] '.$categoriesLi[$c]['NAME'].'</p>'  ;                        
                } 
            }    
         }  
         
         if(empty($row_CATEGORIES_ID)) {
            $row_CATEGORIES_ID = GetMessage($module_id.'_CATEGORIES_ID_NO');                
         }
                  
         $row->AddViewField("CATEGORIES_ID", $row_CATEGORIES_ID);
          // �������� ����������� ���� � ������
         
         
         
         

         
         // ����������� �������������
         if($f_SOURCE) {
            $row->AddViewField("SOURCE", GetMessage($module_id."_list_title_SOURCE_VALUE_".$f_SOURCE));                 
         }       
         
                                   
    } 
      
      
    if($POST_RIGHT>="W") { 
   
           $arActions = array();
           
           $ACTION_EDIT =  array(
                "ICON"=>"view",
                "TEXT"=> GetMessage($module_id."_ACTION_EDIT"),
                "ACTION"=>$lAdmin->ActionRedirect($editUrl),
           );         
        
                       
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



$arParams = array();
// ��������� ��������
if($POST_RIGHT>="W") {


    
    $GroupActionTable = array(
        "delete"=>GetMessage($module_id."_FOOTER_ACTION_DEL"),    
        "activate"=>GetMessage($module_id."_FOOTER_ACTION_ACTIVATE"),
        "deactivate"=>GetMessage($module_id."_FOOTER_ACTION_DEACTIVATE"),    
    );
    
    //���������� � ������ � �������� �� ������
    //START
    $arrGroup = array();
    $dbGroup = CSotbitMailingCategories::GetList(array('ID'=>'asc'),array(),false,array('ID','ACTIVE','NAME'));
    while($resGroup = $dbGroup->Fetch()){
        $arrGroup[] = $resGroup;    
    }
    
    $categories = '<div id="categories_to_move" style="display:none"><select name="categories_to_move">';
    foreach($arrGroup as $ar)
    {
        $categories .= '<option value="'.$ar["ID"].'">['.$ar["ID"].'] '.$ar["NAME"].'</option>';
    }
    $categories .= '</select></div>'; 
 
 
    $GroupActionTable['add_categories'] = GetMessage($module_id."_FOOTER_ACTION_ADD_CATEGORIES");
    $GroupActionTable['delete_categories'] = GetMessage($module_id."_FOOTER_ACTION_DELETE_CATEGORIES");    
    $GroupActionTable['categories_chooser'] = array("type" => "html", "value" => $categories);
    
    
    $arParams["select_onchange"] = "BX('categories_to_move').style.display = (this.value == 'add_categories' || this.value == 'delete_categories'? 'block':'none');";
    //END
       
}


$lAdmin->AddGroupActionTable($GroupActionTable,$arParams);

// ******************************************************************** //
//                ���������������� ����                                 //
// ******************************************************************** //
if($POST_RIGHT>="W") {
  
    // ���������� ���� �������� ��������

    
    $aContext = array(
        array(
            "TEXT" => GetMessage($module_id."_MENU_TOP_MAILING_ADD"),
            "ICON" => "btn_new",
            "LINK" => "sotbit_mailing_subscribers_detail.php?lang=".LANG,
            "TITLE" => GetMessage($module_id."_MENU_TOP_MAILING_ADD_ALT"),
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
    GetMessage($module_id.'_list_title_USER_ID'), 
    GetMessage($module_id.'_list_title_NAME'),             
    GetMessage($module_id.'_list_title_EMAIL_TO'), 
    GetMessage($module_id.'_list_title_CATEGORIES_ID'),                 
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
        <td><?=GetMessage($module_id.'_list_title_USER_ID')?>:</td>
        <td>
            <input type="text" name="find_USER_ID" size="20" value="<?echo htmlspecialchars($find_ID_MESSEGE)?>">
        </td>
    </tr>         
    
    <tr>
        <td><?=GetMessage($module_id.'_list_title_NAME')?>:</td>
        <td>
            <input type="text" name="find_NAME" size="20" value="<?echo htmlspecialchars($find_NAME)?>">
        </td>
    </tr>    
       
    <tr>
        <td><?=GetMessage($module_id.'_list_title_EMAIL_TO')?>:</td>
        <td>
            <input type="text" name="find_EMAIL_TO" size="20" value="<?echo htmlspecialchars($find_EMAIL_TO)?>">
        </td>
    </tr>
            
       
    
    <tr>
        <td><?=GetMessage($module_id.'_list_title_CATEGORIES_ID')?>:</td>
        <td>
            <select name="find_CATEGORIES_ID">
                <option value=""><?=htmlspecialcharsex(GetMessage($module_id.'_ANY'))?></option>
                <?foreach($categoriesList as $categoriesItem):?>
                <option value="<?=$categoriesItem['ID']?>"<?if($find_ID_EVENT==$categoriesItem['ID'])echo " selected"?>>[<?=$categoriesItem['ID']?>] <?=$categoriesItem['NAME']?></option>                
                <?endforeach;?>
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
