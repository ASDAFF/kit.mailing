<?
namespace Kit\Mailing\Helper;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Menu
{
    public static function getAdminMenu (&$arGlobalMenu, &$arModuleMenu)
    {
        $iModuleID = "kit.mailing";
        if (!isset($arGlobalMenu['global_menu_kit'])) {
            $arGlobalMenu['global_menu_kit'] = [
                'menu_id' => 'kit',
                'text' => Loc::getMessage(
                    $iModuleID . '_GLOBAL_MENU'
                ),
                'title' => Loc::getMessage(
                    $iModuleID . '_GLOBAL_MENU'
                ),
                'sort' => 1000,
                'items_id' => 'global_menu_kit_items',
                "icon" => "",
                "page_icon" => "",
            ];
        }

        global $APPLICATION;
        if ($APPLICATION->GetGroupRight($iModuleID) != "D") {
            $aMenu = array(
                "parent_menu" => 'global_menu_kit',
                "section" => 'kit.mailing',
                "sort" => 450,
                "text" => GetMessage("MENU_MAILING_TEXT"),
                "title" => GetMessage("MENU_MAILING_TITLE"),
                "icon" => "mailing_menu_icon",
                "page_icon" => "mailing_page_icon",
                "items_id" => "menu_kit.mailing",
                "items" => array(
                    array(
                        "text" => GetMessage("MENU_MAILING_LIST_TEXT"),
                        "url" => "kit_mailing_list.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "kit_mailing_list.php",
                            "kit_mailing_detail.php",
                            "kit_mailing_section_edit.php",
                        ),
                        "title" => GetMessage("MENU_MAILING_LIST_TITLE")
                    ),
                    array(
                        "text" => GetMessage("MENU_MESSAGE_LIST_TEXT"),
                        "url" => "kit_mailing_message.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "kit_mailing_message.php",
                            "kit_mailing_message_detail.php"
                        ),
                        "title" => GetMessage("MENU_MESSAGE_LIST_TITLE")
                    ),
                    array(
                        "text" => GetMessage("MENU_CATEGORIES_LIST_TEXT"),
                        "url" => "kit_mailing_categories.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "kit_mailing_categories.php",
                            "kit_mailing_categories_detail.php"
                        ),
                        "title" => GetMessage("MENU_CATEGORIES_LIST_TITLE")
                    ),
                    array(
                        "text" => GetMessage("MENU_SUBSCRIBERS_IMPORT_TEXT"),
                        "url" => "kit_mailing_import.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "kit_mailing_import",
                        ),
                        "title" => GetMessage("MENU_SUBSCRIBERS_IMPORT_TITLE")
                    ),
                    array(
                        "text" => GetMessage("MENU_SUBSCRIBERS_LIST_TEXT"),
                        "url" => "kit_mailing_subscribers.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "kit_mailing_subscribers.php",
                            "kit_mailing_subscribers_detail.php"
                        ),
                        "title" => GetMessage("MENU_SUBSCRIBERS_LIST_TITLE")
                    ),
                    array(
                        "text" => GetMessage("MENU_UNSUBSCRIBED_LIST_TEXT"),
                        "url" => "kit_mailing_unsubscribed.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "kit_mailing_unsubscribed.php",
                            "kit_mailing_unsubscribed_detail.php",
                            "kit_mailing_unsubscribed_import.php",
                        ),
                        "title" => GetMessage("MENU_UNSUBSCRIBED_LIST_TITLE")
                    ),
                    array(
                        "text" => GetMessage("MENU_UNDELIVERED_LIST_TEXT"),
                        "url" => "kit_mailing_undelivered.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "kit_mailing_undelivered.php",
                            "kit_mailing_undelivered_import.php",
                        ),
                        "title" => GetMessage("MENU_UNDELIVERED_LIST_TITLE")
                    ),
                    array(
                        "text" => GetMessage("MENU_STATISTICS_LIST_TEXT"),
                        "url" => "kit_mailing_stats_list.php?lang=" . LANGUAGE_ID,
                        "more_url" => array(
                            "kit_mailing_stats_list.php",
                            "kit_mailing_stats_detail.php",
                        ),
                        "title" => GetMessage("MENU_STATISTICS_LIST_TEXT")
                    ),

                )
            );
            $arGlobalMenu['global_menu_kit']['items'][$iModuleID] = $aMenu;
        }
    }
}
?>