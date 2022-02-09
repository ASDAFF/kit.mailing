<?
use Bitrix\Main\Loader;
use Bitrix\Main\Entity;
use Bitrix\Main\Entity\ExpressionField;
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin.php');

$module_id = "sotbit.mailing";
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/include.php");
IncludeModuleLangFile(__FILE__);
IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/interface/admin_lib.php");



$CSotbitMailingTools = new CSotbitMailingTools();
if(!$CSotbitMailingTools->getDemo())
{
    CAdminMessage::ShowMessage(array("MESSAGE" => GetMessage($module_id."_DEMO_END"), "DETAILS" => GetMessage($module_id."_DEMO_END_DETAILS"), "HTML" => true));
    return false;
}



CModule::IncludeModule('sotbit.mailing');

$parentID = 0;
if(isset($_REQUEST["parent"]) && $_REQUEST["parent"])
{
    $parentID = $_REQUEST["parent"];
}


$ID = intval($ID);

$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if ($POST_RIGHT <= "D") {
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}
$bCopy = ($action == "copy");

if($REQUEST_METHOD == "POST" && ($save!="" || $apply!="") && $POST_RIGHT=="W" && check_bitrix_sessid())
{       
    $arFields = Array(
        "NAME"    => $NAME,
        "SORT"    => $SORT,
        "ACTIVE"    => ($ACTIVE <> "Y"? "N":"Y"),
        "DESCRIPTION"    => $DESCRIPTION,
        //"PARENT_CATEGORY_ID"    => $PARENT_CATEGORY_ID,
        "CATEGORY_ID"    => $CATEGORY_ID,
    );
    
   
    if($ID>0)
    {
        //$result = ShsParserSectionTable::Update($ID, $arFields);
        $result = CSotbitMailingSectionTable::Update($ID, $arFields);
        if (!$result->isSuccess())
        {
            $errors = $result->getErrorMessages();
            $res = false;
        }else
            $res = true;
    }
    else
    {
        $arFields["DATE_CREATE"] = new Bitrix\Main\Type\DateTime(date('Y-m-d H:i:s',time()),'Y-m-d H:i:s');

        $result = CSotbitMailingSectionTable::add($arFields);

        if ($result->isSuccess())
        {
            $ID = $result->getId();
            $res = ($ID>0);
        }else{
            $errors = $result->getErrorMessages();
            $res = false;
        }

    }


    if($res)
    {
        if($apply!="")
            LocalRedirect("/bitrix/admin/sotbit_mailing_section_edit.php?ID=".$ID."&parent=".$parentID."&lang=".LANG, true);
        else
            LocalRedirect("/bitrix/admin/sotbit_mailing_list.php?parent=".$parentID."&lang=".LANG, true);
    }
    else
    {
        if(isset($errors) && !empty($errors))
        {
            foreach($errors as $error)
            {
                CAdminMessage::ShowMessage($error);
            }

        }
        $bVarsFromForm = true;
    }
}

if(isset($_REQUEST["ID"]) || $copy)
{
    
    $ID = (int)$_REQUEST["ID"];
    if($copy){
            $arDataTable = CSotbitMailingSectionTable::GetByID($copy)->Fetch();
    }   
    else {
        $arDataTable = CSotbitMailingSectionTable::GetByID($ID)->Fetch();
    }

}



$aTabs = array(
        array(
            "DIV" => "edit1",
            "TAB" => GetMessage($module_id."_category_name"),
            "ICON" => "shs_parser_category_icon",
            "TITLE" => GetMessage($module_id."_category_name")
        ),
);


$aMenu = array(
    array(
        "TEXT"=>GetMessage($module_id."_list"),
        "TITLE"=>GetMessage($module_id."_list_title"),
        "LINK"=>"sotbit_mailing_list.php?parent=".$parentID."&lang=".LANG,
        "ICON"=>"btn_list",
    )
);
if($ID>0)
{
    $aMenu[] = array("SEPARATOR"=>"Y");
    $aMenu[] = array(
        "TEXT"=>GetMessage($module_id."_section_add"),
        "TITLE"=>GetMessage($module_id."_section_add_title"),
        "LINK"=>"sotbit_mailing_section_edit.php?parent=".$parentID."&lang=".LANG,
        "ICON"=>"btn_new",
    );
    $aMenu[] = array(
        "TEXT"=>GetMessage($module_id."_section_copy"),
        "TITLE"=>GetMessage($module_id."_section_copy_title"),
        "LINK"=>"sotbit_mailing_section_edit.php?parent=".$parentID."&copy=".$ID."&lang=".LANG,
        "ICON"=>"btn_copy",
    );
    $aMenu[] = array(
        "TEXT"=>GetMessage($module_id."_section_delete"),
        "TITLE"=>GetMessage($module_id."_section_delete_title"),
        "LINK"=>"javascript:if(confirm('".GetMessage($module_id."_section_delete_title")."'))window.location='sotbit_mailing_list.php?ID=S".$ID."&action=delete&parent=".$parentID."&lang=".LANG."&".bitrix_sessid_get()."';",
        "ICON"=>"btn_delete",
    );
}

if($CSotbitMailingTools->ReturnDemo() == 2) CAdminMessage::ShowMessage(array("MESSAGE" => GetMessage($module_id."_DEMO"), "DETAILS" => GetMessage($module_id."_DEMO_DETAILS"), "HTML" => true));

$context = new CAdminContextMenu($aMenu);
$context->Show();



$_arCategory = CSotbitMailingSectionTable::getCategoryList();

foreach($_arCategory as $item){
    $arCategory['REFERENCE'][$item[0]['ID']] = $item[0]['NAME'];
    $arCategory['REFERENCE_ID'][$item[0]['ID']] = $item[0]['ID'];
}


$tabControl = new CAdminTabControl("tabControl", $aTabs);


?>
<form method="POST" id="" Action="<?echo $APPLICATION->GetCurPage()?>" ENCTYPE="multipart/form-data" name="post_form">
<?
$tabControl->Begin();

$tabControl->BeginNextTab();
?>
    <tr>
        <td width="40%"><?echo GetMessage($module_id."_section_act")?>:</td>
        <td width="60%"><input type="checkbox" name="ACTIVE" value="Y"<?if($arDataTable["ACTIVE"] == "Y" || !$ID) echo " checked"?>>
        </td>
    </tr>
    <tr>
        <td><?echo GetMessage($module_id."_section_sort")?></td>
        <td><input type="text" name="SORT" value="<?echo !$ID?"500":$arDataTable["SORT"];?>" size="4"></td>
    </tr>
    <tr>
        <td><span class="required">*</span><?echo GetMessage($module_id."_section_name")?></td>
        <td><input type="text" name="NAME" value="<?echo $arDataTable["NAME"];?>" size="40" maxlength="250"></td>
    </tr>
    <tr>
        <td><?echo GetMessage($module_id."_category_title")?></td>
        <?/*<td><?=SelectBoxFromArray('PARENT_CATEGORY_ID', $arCategory, isset($arDataTable["PARENT_CATEGORY_ID"])?$arDataTable["PARENT_CATEGORY_ID"]:$parentID, GetMessage($module_id."_category_select"), "id='category' style='width:262px'");?></td>*/?>
        <?/*<td><?=SelectBoxFromArray('CATEGORY_ID', $arCategory, isset($arDataTable["CATEGORY_ID"])?$arDataTable["CATEGORY_ID"]:$parentID, GetMessage($module_id."_category_select"), "id='category' style='width:262px'");?></td>*/?>
        <td><?=SelectBoxFromArray('CATEGORY_ID', $arCategory, isset($arDataTable["CATEGORY_ID"])?$arDataTable["CATEGORY_ID"]:$parentID, "", "id='category' style='width:262px'");?></td>
    </tr>
    <tr>
        <td colspan="2" align="center"><?echo GetMessage($module_id."_category_description")?>:</td>
    </tr>
    <tr>
        <td colspan="2" align="center">
            <textarea cols="60" rows="15"  name="DESCRIPTION" style="width:100%"><?echo $arDataTable["DESCRIPTION"]?></textarea>
            <?=BeginNote();?>
            <?echo GetMessage($module_id."_section_descr")?>
            <?=EndNote();?>
        </td>
    </tr>
    <?echo bitrix_sessid_post();?>
    <input type="hidden" name="lang" value="<?=LANG?>">
    <?if($ID>0 && !$bCopy):?>
    <input type="hidden" name="ID" value="<?=$ID?>">
    <?endif;?>
    <input type="hidden" name="parent" value="<?=$parentID?>">
<?
$tabControl->End();

$tabControl->Buttons(
    array(
        "disabled"=>($POST_RIGHT<"W"),
        "back_url"=>"sotbit_mailing_list.php?parent=".$parentID."&lang=".LANG,

    )
);



$APPLICATION->SetTitle(($ID>0? GetMessage($module_id."_section_title_edit") : GetMessage($module_id."_section_title_add")));



require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>