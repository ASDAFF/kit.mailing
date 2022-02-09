<?
define("NOT_CHECK_PERMISSIONS", true);
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
$module_id = "sotbit.mailing";
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/include.php");
CSotbitMailingHelp::CacheConstantCheck();

$CONS_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($CONS_RIGHT <= "D")
{
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}


//запустим рассылку
//START
if($_GET['ID'] && $_GET['MAILING_START'] == 'Y')
{
    $arrProgress = CSotbitMailingHelp::ProgressFileGetArray($_GET['ID'], $_GET['COUNT_RUN']);
    if($_GET['MAILING_WORK_CHECK'] == 'Y')
    {
        $arrProgress['MAILING_WORK'] = 'Y';
    }

    CSotbitMailingHelp::ProgressFile($_GET['ID'], $_GET['COUNT_RUN'], $arrProgress);

    CSotbitMailingTools::StartMailing($_GET['ID'], array('TYPE_StartMailing' => 'HAND'));
}
//END

//остановим рассылку
//START
if($_GET['ID'] && $_GET['MAILING_STOP'] == 'Y')
{
    $arrProgress = CSotbitMailingHelp::ProgressFileGetArray($_GET['ID'], $_GET['COUNT_RUN']);

    CSotbitMailingHelp::ProgressFile($_GET['ID'], $_GET['COUNT_RUN'], $arrProgress);

    CSotbitMailingEvent::Update($_GET['ID'], array(
        "MAILING_WORK_COUNT" => $arrProgress['COUNT_NOW']
    ));
}
//END

?>