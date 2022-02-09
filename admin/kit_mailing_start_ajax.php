<?
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
$module_id = "kit.mailing";
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/include.php");
CKitMailingHelp::CacheConstantCheck();

$CONS_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($CONS_RIGHT <= "D")
{
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}


//�������� ��������
//START
if($_GET['ID'] && $_GET['MAILING_START'] == 'Y')
{
    $arrProgress = CKitMailingHelp::ProgressFileGetArray($_GET['ID'], $_GET['COUNT_RUN']);
    if($_GET['MAILING_WORK_CHECK'] == 'Y')
    {
        $arrProgress['MAILING_WORK'] = 'Y';
    }

    CKitMailingHelp::ProgressFile($_GET['ID'], $_GET['COUNT_RUN'], $arrProgress);

    CKitMailingTools::StartMailing($_GET['ID'], array('TYPE_StartMailing' => 'HAND'));
}
//END

//��������� ��������
//START
if($_GET['ID'] && $_GET['MAILING_STOP'] == 'Y')
{
    $arrProgress = CKitMailingHelp::ProgressFileGetArray($_GET['ID'], $_GET['COUNT_RUN']);

    CKitMailingHelp::ProgressFile($_GET['ID'], $_GET['COUNT_RUN'], $arrProgress);

    CKitMailingEvent::Update($_GET['ID'], array(
        "MAILING_WORK_COUNT" => $arrProgress['COUNT_NOW']
    ));
}
//END

?>