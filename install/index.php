<?php
use Bitrix\Main\ModuleManager;

IncludeModuleLangFile(__FILE__);
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/update_client.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/classes/general/update_client_partner.php');

class kit_mailing extends CModule
{
    const MODULE_ID = 'kit.mailing';
    var $MODULE_ID = 'kit.mailing';
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $MODULE_CSS;
    var $_26174285 = '';

    function __construct()
    {
        $arModuleVersion = array();
        include(dirname(__FILE__) . '/version.php');
        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        $this->MODULE_NAME = GetMessage('kit.mailing_MODULE_NAME');
        $this->MODULE_DESCRIPTION = GetMessage('kit.mailing_MODULE_DESC');
        $this->PARTNER_NAME = GetMessage('kit.mailing_PARTNER_NAME');
        $this->PARTNER_URI = GetMessage('kit.mailing_PARTNER_URI');
    }

    function InstallEvents()
    {
        return true;
    }

    function UnInstallEvents()
    {
        return true;
    }

    function DoInstall()
    {
        global $APPLICATION;
        $this->InstallDB();
        $this->InstallFiles();
        ModuleManager::registerModule(self::MODULE_ID);
    }

    function InstallDB($_1362428416 = array())
    {
        global $DB, $DBType, $APPLICATION;
        $this->_1716207575 = false;
        if (!$DB->Query("SELECT 'x' FROM b_kit_mailing_event", true)) {
            $this->_1716207575 = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/db/' . $DBType . '/install.sql');
        }
        if ($this->_1716207575 !== false) {
            $APPLICATION->ThrowException(implode('', $this->_1716207575));
            return false;
        }
        $_1214104047 = COption::GetOptionString('sale', 'expiration_processing_events');
        if (!empty($_1214104047) && $_1214104047 != 'Y') {
            COption::SetOptionString('sale', 'expiration_processing_events', 'Y');
        }
        RegisterModuleDependences('main', 'OnPageStart', self::MODULE_ID, 'CKitMailingHelp', 'OnPageStart');
        RegisterModuleDependences('main', 'OnBuildGlobalMenu', self::MODULE_ID, 'CKitMailingHelp', 'OnBuildGlobalMenuHandler');
        RegisterModuleDependences('main', 'OnEpilog', self::MODULE_ID, 'CKitMailingHelp', 'OnEpilog');
        RegisterModuleDependences('sale', 'OnOrderAdd', self::MODULE_ID, 'CKitMailingHelp', 'OnOrderAdd');
        RegisterModuleDependences(self::MODULE_ID, 'OnSubConnectorList', self::MODULE_ID, 'kit\mailing\subconnectormanager', 'onSubConnectorListMainUser');
        RegisterModuleDependences(self::MODULE_ID, 'OnSubConnectorList', self::MODULE_ID, 'kit\mailing\subconnectormanager', 'onSubConnectorListSaleBuyer');
        RegisterModuleDependences(self::MODULE_ID, 'OnSubConnectorList', self::MODULE_ID, 'kit\mailing\subconnectormanager', 'onSubConnectorListFormResult');
        RegisterModuleDependences(self::MODULE_ID, 'OnSubConnectorList', self::MODULE_ID, 'kit\mailing\subconnectormanager', 'onSubConnectorListSubscriber');
        CAgent::AddAgent('CKitMailingTools::AgentMailingNeedAction();', self::MODULE_ID, 'N', 14400, '', 'Y');
        return true;
    }

    function InstallFiles($_1362428416 = array())
    {
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin', true);
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/themes/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/themes/', true, true);
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/local/', $_SERVER['DOCUMENT_ROOT'] . '/local/', true, true);
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/other/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/templates/.default/components/bitrix/', true, true);
        COption::SetOptionString('kit.mailing', 'TEMPLATE_MAILING_THEME_DEF', 'kit_mailing_default_mail');
        if (is_dir($_1381371367 = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/components')) {
            if ($_1668798468 = opendir($_1381371367)) {
                while (false !== $_1912439387 = readdir($_1668798468)) {
                    if ($_1912439387 == '..' || $_1912439387 == '.') continue;
                    CopyDirFiles($_1381371367 . '/' . $_1912439387, $_SERVER['DOCUMENT_ROOT'] . '/bitrix/components/' . $_1912439387, $_518218131 = True, $_1068945681 = True);
                }
                closedir($_1668798468);
            }
        }
        return true;
    }

    function DoUninstall()
    {
        global $APPLICATION, $step;
        $step = IntVal($step);
        if ($step < 2) {
            $APPLICATION->IncludeAdminFile(GetMessage('kit.mailing_MODULE_INSTALL_TITLE'), $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/unstep1.php');
        } elseif ($step == 2) {
            $this->UnInstallDB(array('savedata' => $_REQUEST['savedata'],));
            $this->UnInstallFiles();
            $GLOBALS['errors'] = $this->_1716207575;
            $APPLICATION->IncludeAdminFile(GetMessage('kit.mailing_MODULE_INSTALL_TITLE'), $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/unstep2.php');
        }
    }

    function UnInstallDB($_1362428416 = array())
    {
        global $DB, $DBType, $APPLICATION;
        $this->_1716207575 = false;
        if (array_key_exists('savedata', $_1362428416) && $_1362428416['savedata'] != 'Y') {
            $this->_1716207575 = $DB->RunSQLBatch($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/db/' . $DBType . '/uninstall.sql');
            if ($this->_1716207575 !== false) {
                $APPLICATION->ThrowException(implode('', $this->_1716207575));
                return false;
            }
        }
        UnRegisterModuleDependences('main', 'OnPageStart', self::MODULE_ID, 'CKitMailingHelp', 'OnPageStart');
        UnRegisterModuleDependences('main', 'OnEpilog', self::MODULE_ID, 'CKitMailingHelp', 'OnEpilog');
        UnRegisterModuleDependences('main', 'OnBuildGlobalMenu', self::MODULE_ID, 'CKitMailingHelp', 'OnBuildGlobalMenuHandler');
        UnRegisterModuleDependences('sale', 'OnOrderAdd', self::MODULE_ID, 'CKitMailingHelp', 'OnOrderAdd');
        UnRegisterModuleDependences(self::MODULE_ID, 'OnSubConnectorList', self::MODULE_ID, 'kit\mailing\subconnectormanager', 'onSubConnectorListMainUser');
        UnRegisterModuleDependences(self::MODULE_ID, 'OnSubConnectorList', self::MODULE_ID, 'kit\mailing\subconnectormanager', 'onSubConnectorListSaleBuyer');
        UnRegisterModuleDependences(self::MODULE_ID, 'OnSubConnectorList', self::MODULE_ID, 'kit\mailing\subconnectormanager', 'onSubConnectorListFormResult');
        UnRegisterModuleDependences(self::MODULE_ID, 'OnSubConnectorList', self::MODULE_ID, 'kit\mailing\subconnectormanager', 'onSubConnectorListSubscriber');
        CAgent::RemoveModuleAgents(self::MODULE_ID);
        UnRegisterModule(self::MODULE_ID);
        return true;
    }

    function UnInstallFiles()
    {
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/admin', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin');
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/themes/.default/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/themes/.default');
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/local', $_SERVER['DOCUMENT_ROOT'] . '/local');
        DeleteDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/other/', $_SERVER['DOCUMENT_ROOT'] . '/bitrix/templates/.default/components/bitrix/');
        if (is_dir($_1381371367 = $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/' . self::MODULE_ID . '/install/components')) {
            if ($_1668798468 = opendir($_1381371367)) {
                while (false !== $_1912439387 = readdir($_1668798468)) {
                    if ($_1912439387 == '..' || $_1912439387 == '.' || !is_dir($_943363076 = $_1381371367 . '/' . $_1912439387)) continue;
                    $_1081389549 = opendir($_943363076);
                    while (false !== $_286881267 = readdir($_1081389549)) {
                        if ($_286881267 == '..' || $_286881267 == '.') continue;
                        DeleteDirFilesEx('/bitrix/components/' . $_1912439387 . '/' . $_286881267);
                    }
                    closedir($_1081389549);
                }
                closedir($_1668798468);
            }
        }
        return true;
    }
}