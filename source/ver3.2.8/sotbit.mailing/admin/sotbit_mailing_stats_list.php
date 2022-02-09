<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
$module_id = "sotbit.mailing";
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $module_id . "/include.php");
IncludeModuleLangFile(__FILE__);


$CSotbitMailingTools = new CSotbitMailingTools();
if(!$CSotbitMailingTools->getDemo())
{
    CAdminMessage::ShowMessage(array("MESSAGE" => GetMessage($module_id . "_DEMO_END"), "DETAILS" => GetMessage($module_id . "_DEMO_END_DETAILS"), "HTML" => true));
    return false;
}


CSotbitMailingHelp::CacheConstantCheck();
//Проверка прав
$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($POST_RIGHT <= "D")
{
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}


//получим данные о шаблонах
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
                $valTemp["TITLE_CUSTOM"] = $valTemp["TITLE"] . ' [' . GetMessage($module_id . '_TEMPLATE_TYPE_MY') . ' - ' . $valTemp['NAME'] . ']';
            }
            else
            {
                $valTemp["TITLE_CUSTOM"] = $valTemp["TITLE"] . ' [' . GetMessage($module_id . '_TEMPLATE_TYPE_SYSTEM') . ' - ' . $valTemp['NAME'] . ']';
            }

            $arrTempl[$valTemp['NAME']] = $valTemp;
        }
    }
}
//END


$sTableID = "tbl_sotbit_mailing_statics"; // ID таблицы
$oSort = new CAdminSorting($sTableID, "ID", "asc"); // объект сортировки
$lAdmin = new CAdminList($sTableID, $oSort); // основной объект списка


$cData = new CSotbitMailingEvent;

$cDataMessage = new CSotbitMailingMessage;
// ******************************************************************** //
//                ОБРАБОТКА ДЕЙСТВИЙ НАД ЭЛЕМЕНТАМИ СПИСКА              //
// ******************************************************************** //


// ******************************************************************** //
//                ВЫБОРКА ЭЛЕМЕНТОВ СПИСКА                              //
// ******************************************************************** //

$cData = new CSotbitMailingEvent;
$rsData = $cData->GetList(array($by => $order), $arFilter);

// преобразуем список в экземпляр класса CAdminResult
$rsData = new CAdminResult($rsData, $sTableID);

// аналогично CDBResult инициализируем постраничную навигацию
$rsData->NavStart();

// отправим вывод переключателя страниц в основной объект $lAdmin
$lAdmin->NavText($rsData->GetNavPrint(GetMessage($module_id . '_NAV_TITLE')));

// ******************************************************************** //
//                ПОДГОТОВКА СПИСКА К ВЫВОДУ                            //
// ******************************************************************** //

$lAdmin->AddHeaders(array(
    array(
        "id" => "ID",
        "content" => 'ID',
        "sort" => "ID",
        "align" => "right",
        "default" => true,
    ),
    array(
        "id" => "ACTIVE",
        "content" => GetMessage($module_id . '_list_title_ACTIVE'),
        "sort" => "ACTIVE",
        "default" => true,
    ),
    array(
        "id" => "NAME",
        "content" => GetMessage($module_id . '_list_title_NAME'),
        "sort" => "NAME",
        "default" => true,
    ),
    array(
        "id" => "DESCRIPTION",
        "content" => GetMessage($module_id . '_list_title_DESCRIPTION'),
        "sort" => "ACTIVE",
        "default" => true,
    ),
    array(
        "id" => "TEMPLATE",
        "content" => GetMessage($module_id . '_list_title_TEMPLATE'),
        "sort" => "TEMPLATE",
        "default" => true,
    ),
    array(
        "id" => "DATE_LAST_RUN",
        "content" => GetMessage($module_id . '_list_title_DATE_LAST_RUN'),
        "sort" => "DATE_LAST_RUN",
        "default" => true,
    ),
    array(
        "id" => "COUNT_RUN",
        "content" => GetMessage($module_id . '_list_title_COUNT_RUN'),
        "sort" => "COUNT_RUN",
        "default" => true,
    ),
));


$rowItemArray = array();
$rowItemArray[] = array(
    'ID' => 'ALL',
    'ACTIVE' => 'Y',
    'NAME' => GetMessage($module_id . "_list_title_ALL_STATIC")
);
while($arRes = $rsData->Fetch())
{
    $rowItemArray[] = $arRes;
}


foreach($rowItemArray as $kRes => $arRes)
{
    $editUrl = "sotbit_mailing_stats_detail.php?ID=" . $arRes['ID'] . "&lang=" . LANG;

    if($arRes['ACTIVE'] == 'Y')
    {
        $arRes['ACTIVE'] = GetMessage($module_id . '_YES');
    }
    else
    {
        $arRes['ACTIVE'] = GetMessage($module_id . '_NO');
    }

    if($arrTempl[$arRes['TEMPLATE']])
    {
        $arRes['TEMPLATE'] = $arrTempl[$arRes['TEMPLATE']]['TITLE_CUSTOM'];
    }

    $row =& $lAdmin->AddRow($arRes['ID'], $arRes);

    if($POST_RIGHT >= "W")
    {
        $arActions = array();
        $ACTION_EDIT = array(
            "ICON" => "view",
            "TEXT" => GetMessage($module_id . "_ACTION_VIEW"),
            "ACTION" => $lAdmin->ActionRedirect($editUrl),
        );
        $arActions[] = $ACTION_EDIT;
    }
    $row->AddActions($arActions);
}

// резюме таблицы
$lAdmin->AddFooter(
    array(
        array(
            "title" => GetMessage("MAIN_ADMIN_LIST_SELECTED"),
            "value" => $rsData->SelectedRowsCount()
        ), // кол-во элементов
        array(
            "counter" => true,
            "title" => GetMessage("MAIN_ADMIN_LIST_CHECKED"),
            "value" => "0"
        ), // счетчик выбранных элементов
    )
);

// групповые действия
if($POST_RIGHT >= "W")
{
    $GroupActionTable = array();
}

$lAdmin->AddGroupActionTable($GroupActionTable);

// ******************************************************************** //
//                АДМИНИСТРАТИВНОЕ МЕНЮ                                 //
// ******************************************************************** //
if($POST_RIGHT >= "W")
{
    $aContext = array();
    $lAdmin->AddAdminContextMenu($aContext);
}

// ******************************************************************** //
//                ВЫВОД                                                 //
// ******************************************************************** //

// альтернативный вывод
$lAdmin->CheckListMode();

// установим заголовок страницы
$APPLICATION->SetTitle(GetMessage($module_id . '_MAILING_LIST_TITLE'));

// не забудем разделить подготовку данных и вывод
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
?>


<?
if($CSotbitMailingTools->ReturnDemo() == 2) CAdminMessage::ShowMessage(array("MESSAGE" => GetMessage($module_id . "_DEMO"), "DETAILS" => GetMessage($module_id . "_DEMO_DETAILS"), "HTML" => true));
$lAdmin->DisplayList();
?>


<?
//завершение страницы
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
?>
