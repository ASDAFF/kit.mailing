<?
namespace Sotbit\Mailing\Helper;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Menu
{
    public static function getAdminMenu (&$arGlobalMenu, &$arModuleMenu)
    {
        $iModuleID = "sotbit.mailing";
        if (!isset($arGlobalMenu['global_menu_sotbit'])) {
            $arGlobalMenu['global_menu_sotbit'] = [
                'menu_id' => 'sotbit',
                'text' => Loc::getMessage(
                    $iModuleID . '_GLOBAL_MENU'
                ),
                'title' => Loc::getMessage(
                    $iModuleID . '_GLOBAL_MENU'
                ),
                'sort' => 1000,
                'items_id' => 'global_menu_sotbit_items',
                "icon" => "",
                "page_icon" => "",
            ];
        }

        global $APPLICATION;
        if ($APPLICATION->GetGroupRight($iModuleID) != "D") {
            $aMenu = array(
                "parent_menu" => 'global_menu_sotbit',
                "section" => 'sotbit.mailing',
                "sort" => 450,
                "text" => GetMessage("MENU_MAILING_TEXT"),
                "title" => GetMessage("MENU_MAILING_TITLE"),
                "icon" => "mailing_menu_icon",
                "page_icon" => "mailing_page_icon",
                "items_id" => "menu_sotbit.mailing",
                "items" => array(
                    array(
                        "text" => GetMessage("MENU_MAILING_LIST_TEXT"),
                        "url" => "sotbit_mailing_list.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "sotbit_mailing_list.php",
                            "sotbit_mailing_detail.php",
                            "sotbit_mailing_section_edit.php",
                        ),
                        "title" => GetMessage("MENU_MAILING_LIST_TITLE")
                    ),
                    array(
                        "text" => GetMessage("MENU_MESSAGE_LIST_TEXT"),
                        "url" => "sotbit_mailing_message.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "sotbit_mailing_message.php",
                            "sotbit_mailing_message_detail.php"
                        ),
                        "title" => GetMessage("MENU_MESSAGE_LIST_TITLE")
                    ),
                    array(
                        "text" => GetMessage("MENU_CATEGORIES_LIST_TEXT"),
                        "url" => "sotbit_mailing_categories.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "sotbit_mailing_categories.php",
                            "sotbit_mailing_categories_detail.php"
                        ),
                        "title" => GetMessage("MENU_CATEGORIES_LIST_TITLE")
                    ),
                    array(
                        "text" => GetMessage("MENU_SUBSCRIBERS_IMPORT_TEXT"),
                        "url" => "sotbit_mailing_import.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "sotbit_mailing_import",
                        ),
                        "title" => GetMessage("MENU_SUBSCRIBERS_IMPORT_TITLE")
                    ),
                    array(
                        "text" => GetMessage("MENU_SUBSCRIBERS_LIST_TEXT"),
                        "url" => "sotbit_mailing_subscribers.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "sotbit_mailing_subscribers.php",
                            "sotbit_mailing_subscribers_detail.php"
                        ),
                        "title" => GetMessage("MENU_SUBSCRIBERS_LIST_TITLE")
                    ),
                    array(
                        "text" => GetMessage("MENU_UNSUBSCRIBED_LIST_TEXT"),
                        "url" => "sotbit_mailing_unsubscribed.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "sotbit_mailing_unsubscribed.php",
                            "sotbit_mailing_unsubscribed_detail.php",
                            "sotbit_mailing_unsubscribed_import.php",
                        ),
                        "title" => GetMessage("MENU_UNSUBSCRIBED_LIST_TITLE")
                    ),
                    array(
                        "text" => GetMessage("MENU_UNDELIVERED_LIST_TEXT"),
                        "url" => "sotbit_mailing_undelivered.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "sotbit_mailing_undelivered.php",
                            "sotbit_mailing_undelivered_import.php",
                        ),
                        "title" => GetMessage("MENU_UNDELIVERED_LIST_TITLE")
                    ),
                    array(
                        "text" => GetMessage("MENU_STATISTICS_LIST_TEXT"),
                        "url" => "sotbit_mailing_stats_list.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "sotbit_mailing_stats_list.php",
                            "sotbit_mailing_stats_detail.php",
                        ),
                        "title" => GetMessage("MENU_STATISTICS_LIST_TEXT")
                    ),

                )
            );
            $arGlobalMenu['global_menu_sotbit']['items'][$iModuleID] = $aMenu;
        }
    }
}
?>