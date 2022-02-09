<?php
$_1544658314 = 'sotbit.mailing';
IncludeModuleLangFile(__FILE__);
CModule::IncludeModule('iblock');
$_42492588 = CModule::AddAutoloadClasses('sotbit.mailing',
    array(
        'CSotbitMailingHelp' => 'classes/general/CSotbitMailingHelp.php',
        'CModuleOptions' => 'classes/general/CModuleOptions.php',
        'CMailingDetailOptions' => 'classes/general/CMailingDetailOptions.php',
        'CSotbitMailingEvent' => 'classes/mysql/CSotbitMailingEvent.php',
        'CSotbitMailingMessage' => 'classes/mysql/CSotbitMailingMessage.php',
        'CSotbitMailingMessageText' => 'classes/mysql/CSotbitMailingMessageText.php',
        'CSotbitMailingMessageTemplate' => 'classes/mysql/CSotbitMailingMessageTemplate.php',
        'CSotbitMailingUnsubscribed' => 'classes/mysql/CSotbitMailingUnsubscribed.php',
        'CSotbitMailingUndelivered' => 'classes/mysql/CSotbitMailingUndelivered.php',
        'CSotbitMailingSubscribers' => 'classes/mysql/CSotbitMailingSubscribers.php',
        'CSotbitMailingCategories' => 'classes/mysql/CSotbitMailingCategories.php',
        'CsotbitMailingSubTools' => 'classes/general/CsotbitMailingSubTools.php',
        'CSotbitMailingSectionTable' => 'classes/mysql/CSotbitMailingSection.php',
        'MCAPI' => 'classes/general/MCAPI.class.php',
    )
);

class CSotbitMailingTools
{
    const EVENT_ADD_MAILING = "SOTBIT_MAILING_EVENT_SEND";
    const MODULE_ID = "sotbit.mailing";
    private static $_759810266 = array();
    private static $_794221308 = false;
    private static $_1352605665 = 0;

    public function __construct()
    {
        $this->__973220548();
    }

    private static function __973220548()
    {
        static::$_1352605665 = CModule::IncludeModuleEx("sotbit.mailing");
    }

    public function getDemo()
    {
        if (static::$_1352605665 == 0 || static::$_1352605665 == 3) return false; else return true;
    }

    public function ReturnDemo()
    {
        return static::$_1352605665;
    }

    public function AgentStartTemplate($_50629441 = 0)
    {
        global $USER;
        if (!is_object($USER)) {
            $USER = new CUser();
        }
        $_1381752207 = CSotbitMailingEvent::GetByID($_50629441);
        $_1584904758 = date('H');
        self::$_794221308 = true;
        if ($_1381752207['EVENT_PARAMS_AGENT_AROUND'] == 'Y') {
            CSotbitMailingTools::StartMailing($_50629441, array('TYPE_StartMailing' => 'AGENT'));
        } elseif ($_1584904758 >= $_1381752207['AGENT_TIME_START'] && $_1584904758 < $_1381752207['AGENT_TIME_END']) {
            CSotbitMailingTools::StartMailing($_50629441, array('TYPE_StartMailing' => 'AGENT'));
        }
        self::send();
        return "CSotbitMailingTools::AgentStartTemplate($_50629441);";
    }

    public function StartMailing($_50629441 = 0, $_2043825558 = array())
    {
        global $APPLICATION;
        global $DB;
        $_1381752207 = CSotbitMailingEvent::GetByID($_50629441);
        if ($_1381752207['ACTIVE'] == 'Y') {
            set_time_limit(0);
            if ($_1381752207['COUNT_RUN'] != 0) {
                CSotbitMailingHelp::ProgressFileDelete($_50629441, $_1381752207['COUNT_RUN'] - 1);
            }
            if ($_1381752207['MAILING_WORK'] == 'N') {
                $_529922068['COUNT_ALL'] = 999999;
                $_529922068['COUNT_NOW'] = 0;
                $_529922068['COUNT_SEND'] = 0;
                $_529922068['EMAIL_TO_SEND'] = 0;
                $_529922068['EMAIL_TO_EXCLUDE_UNSUBSCRIBED'] = 0;
                $_529922068['EMAIL_TO_EXCLUDE_HOUR_AGO'] = 0;
                $_529922068['MAILING_WORK'] = 'Y';
                CSotbitMailingHelp::ProgressFile($_50629441, $_1381752207['COUNT_RUN'], $_529922068);
            }
            $_500120612 = unserialize($_1381752207['TEMPLATE_PARAMS']);
            $_500120612['MAILING_EVENT_ID'] = $_1381752207['ID'];
            $_500120612['MAILING_SITE_URL'] = $_1381752207['SITE_URL'];
            $_500120612['MAILING_COUNT_RUN'] = $_1381752207['COUNT_RUN'];
            $_500120612['MAILING_TEMPLATE'] = $_1381752207['TEMPLATE'];
            $_500120612['MAILING_EXCLUDE_HOUR_AGO'] = $_1381752207['EXCLUDE_HOUR_AGO'];
            $_500120612['MAILING_EXCLUDE_UNSUBSCRIBED_USER'] = $_1381752207['EXCLUDE_UNSUBSCRIBED_USER'];
            $_500120612['MAILING_EXCLUDE_UNSUBSCRIBED_USER_MORE'] = unserialize($_1381752207['EXCLUDE_UNSUBSCRIBED_USER_MORE']);
            $_500120612['MAILING_MAILING_WORK_COUNT'] = $_1381752207['MAILING_WORK_COUNT'];
            $_500120612['MAILING_PACKAGE_COUNT'] = COption::GetOptionString('sotbit.mailing', 'MAILING_PACKAGE_COUNT', '3000');
            unset($_500120612['MAILING_INFO']['TEMPLATE_PARAMS']);
            if ($_2043825558) {
                $_500120612['MORE_OPTIONS_TEMPLATE'] = $_2043825558;
            }
            $APPLICATION->IncludeComponent('sotbit:sotbit.mailing.logic', $_1381752207['TEMPLATE'], $_500120612);
            global $CACHE_MANAGER;
            $CACHE_MANAGER->ClearByTag(self::MODULE_ID . '_GetMailingInfo');
            $CACHE_MANAGER->ClearByTag(self::MODULE_ID . '_GetEventTemplate_' . $_1381752207['ID']);
            CSotbitMailingEvent::Update($_50629441, array('COUNT_RUN' => $_1381752207['COUNT_RUN'] + 1, 'DATE_LAST_RUN' => Date($DB->DateFormatToPHP(CLang::GetDateFormat('FULL', SITE_ID))), 'MAILING_WORK' => 'N', 'MAILING_WORK_COUNT' => 0, 'MAILING_WORK_PARAM' => ''));
            $_1489970456 = CSotbitMailingMessageTemplate::GetList(array('COUNT_START' => 'DESC'), array('ID_EVENT' => $_50629441, 'ARCHIVE' => 'N'), false, array('ID', 'COUNT_END'));
            while ($_2048944103 = $_1489970456->Fetch()) {
                CSotbitMailingMessageTemplate::Update($_2048944103['ID'], array('COUNT_END' => $_1381752207['COUNT_RUN'] + 1));
                break;
            }
        }
    }

    public static function send()
    {
        foreach (self::$_759810266 as $_1881468694) {
            CSotbitMailingTools::SendMessage(array('ID_EVENT' => $_1881468694['ID_EVENT'], 'ID' => $_1881468694['ID']));
            CSotbitMailingHelp::slaap(COption::GetOptionString('sotbit.mailing', 'MAILING_MESSAGE_SLAAP', '0.001'));
        }
    }

    public function SendMessage($_1822731620 = array(), $_754080943 = array())
    {
        global $DB;
        $_319086815 = CModule::IncludeModuleEx('sotbit.mailing');
        if ($_319086815 == '0') {
            CSotbitMailingMessage::Update($_1822731620['ID'], array('SEND_ERROR' => GetMessage('NOT_FOUND')));
            return false;
        } elseif ($_319086815 == '3') {
            CSotbitMailingMessage::Update($_1822731620['ID'], array('SEND_ERROR' => GetMessage('DEMO_END')));
            return false;
        }
        if (empty($_1822731620['ID'])) {
            return false;
        }
        $_726319206 = CSotbitMailingMessage::GetByIDInfoSend($_1822731620['ID']);
        $_726319206['PARAM_MESSEGE'] = unserialize($_726319206['PARAM_MESSEGE']);
        $_1548927215 = '';
        $_644628512 = new CSotbitMailingHelp();
        if ($_726319206) {
            $_1822731620['ID_EVENT'] = $_726319206['ID_EVENT'];
            $_754080943['ID_EVENT'] = $_726319206['ID_EVENT'];
            $_754080943['EMAIL_FROM'] = $_726319206['EMAIL_FROM'];
            $_754080943['EMAIL_TO'] = $_726319206['EMAIL_TO'];
            $_754080943['SUBJECT'] = $_726319206['SUBJECT'];
            $_754080943['MESSEGE'] = $_726319206['MESSEGE'];
            $_754080943['BCC'] = $_726319206['BCC'];
        } else {
            return false;
        }
        if ($_726319206['SEND'] == 'Y') {
            return false;
        }
        $_260962268 = CSotbitMailingHelp::GetMailingInfo();
        $_1171335915 = $_260962268[$_1822731620['ID_EVENT']];
        $_239741154 = $_644628512->EventTemplateCheck($_1171335915['EVENT_TYPE'], $_1171335915['NAME']);
        $_813919981 = array('MESSEGE' => $_754080943['MESSEGE'], 'EMAIL_FROM' => $_754080943['EMAIL_FROM'], 'EMAIL_TO' => $_754080943['EMAIL_TO'], 'SUBJECT' => $_754080943['SUBJECT'],);
        $_2007401319 = $_644628512->CompileMessageText($_1171335915['EVENT_TYPE'], $_813919981, $_239741154);
        $_2007401319 = $_644628512->ReplaceTemplateLinks($_2007401319, $_1171335915['SITE_URL']);
        $_817130455['MAILING_MESSAGE'] = $_1822731620['ID'];
        $_817130455['MAILING_EVENT_ID'] = $_1822731620['ID_EVENT'];
        if ($_1171335915['USER_AUTH'] == 'Y' && $_726319206['PARAM_MESSEGE']['USER_AUTH']) {
            $_817130455['MAILING_MESSAGE'] = $_817130455['MAILING_MESSAGE'] . '&USER_AUTH=' . $_726319206['PARAM_MESSEGE']['USER_AUTH'];
        }
        $_817130455['MAILING_UNSUBSCRIBE'] = $_1822731620['ID'] . '||' . $_1822731620['ID_EVENT'];
        $_817130455['SITE_URL'] = parse_url($_1171335915['SITE_URL'], PHP_URL_HOST);
        $_754080943['MESSEGE'] = CSotbitMailingHelp::ReplaceVariables($_2007401319, $_817130455);
        $_754080943['MESSEGE'] = htmlspecialcharsBack($_754080943['MESSEGE']);
        $_754080943['EMAIL_FROM'] = htmlspecialcharsBack($_754080943['EMAIL_FROM']);
        $_754080943['SUBJECT'] = htmlspecialcharsBack($_754080943['SUBJECT']);
        if ($_1171335915['EVENT_SEND_SYSTEM'] == 'BITRIX') {
            $_502923035 = CSotbitMailingHelp::GetSiteId();
            if ($_754080943['BCC']) {
                $_754080943['BCC'] = htmlspecialcharsBack($_754080943['BCC']);
                $_1311136515 = $_754080943;
                $_1311136515['EMAIL_TO'] = $_754080943['BCC'];
                $_1311136515['SUBJECT'] = $_1311136515['SUBJECT'] . ' - ' . $_754080943['EMAIL_TO'];
                unset($_1311136515['BCC']);
                $_516700919 = CEvent::Send(self::EVENT_ADD_MAILING, $_502923035, $_1311136515);
                unset($_754080943['BCC']);
            }
            $_754080943['MESSEGE'] .= '<img src="' . $_1171335915['SITE_URL'] . '/bitrix/admin/sotbit_mailing_img.php?MAILING_MESSAGE=' . $_1822731620['ID'] . '" width="1px" height="1px"  />';
            $_516700919 = CEvent::Send(self::EVENT_ADD_MAILING, $_502923035, $_754080943, 'Y');
            if ($_516700919) {
                CSotbitMailingMessage::Update($_1822731620['ID'], array('SEND' => 'Y', 'DATE_SEND' => Date($DB->DateFormatToPHP(CLang::GetDateFormat('FULL', SITE_ID))), 'SEND_ERROR' => '', 'SEND_SYSTEM' => $_1171335915['EVENT_SEND_SYSTEM'],));
                return $_516700919;
            }
        }
        if ($_1171335915['EVENT_SEND_SYSTEM'] == 'UNISENDER') {
            $_1170728781 = $_754080943['EMAIL_FROM'];
            if (stripos($_1170728781, '<') !== false) {
                $_1200800317 = explode('<', $_1170728781);
                $_1200800317[1] = str_replace('>', '', $_1200800317[1]);
                $_296990361 = $_1200800317[0];
                $_353889608 = $_1200800317[1];
            } elseif ($_1170728781) {
                $_296990361 = $_1200800317[0];
                $_353889608 = $_1170728781;
                $_296990361 = COption::GetOptionString('sotbit.mailing', 'UNSENDER_SENDER_NAME');
            }
            if (empty($_296990361)) {
                $_296990361 = COption::GetOptionString('sotbit.mailing', 'UNSENDER_SENDER_NAME');
            }
            if (empty($_353889608)) {
                $_353889608 = COption::GetOptionString('sotbit.mailing', 'UNSENDER_SENDER_EMAIL');
            }
            $_1794397518 = $_754080943['MESSEGE'] . ' <img src="' . $_1171335915['SITE_URL'] . '/bitrix/admin/sotbit_mailing_img.php?MAILING_MESSAGE=' . $_1822731620['ID'] . '" width="1px" height="1px"  />';
            $_2147447352 = array('sender_name' => $_296990361, 'sender_email' => $_353889608, 'email' => $_754080943['EMAIL_TO'], 'subject' => $_754080943['SUBJECT'], 'body' => $_1794397518, 'list_id' => $_1171335915['EVENT_SEND_SYSTEM_CODE'], 'user_campaign_id' => $_1822731620['ID_EVENT'], 'track_read' => 1, 'wrap_type' => COption::GetOptionString('sotbit.mailing', 'UNSENDER_WRAP_TYPE'),);
            $_1477231671 = CSotbitMailingHelp::QueryUniSender('sendEmail', $_2147447352);
            if ($_1477231671['error']) {
                CSotbitMailingMessage::Update($_1822731620['ID'], array('SEND_ERROR' => $_1477231671['error'], 'SEND_SYSTEM' => $_1171335915['EVENT_SEND_SYSTEM'],));
            }
            if ($_1477231671['result']['email_id']) {
                CSotbitMailingMessage::Update($_1822731620['ID'], array('SEND' => 'Y', 'SEND_SYSTEM_MESSEGE_CODE' => $_1477231671['result']['email_id'], 'DATE_SEND' => Date($DB->DateFormatToPHP(CLang::GetDateFormat('FULL', SITE_ID))), 'SEND_ERROR' => '', 'SEND_SYSTEM' => $_1171335915['EVENT_SEND_SYSTEM']));
                if ($_754080943['BCC']) {
                    $_502923035 = CSotbitMailingHelp::GetSiteId();
                    $_754080943['BCC'] = htmlspecialcharsBack($_754080943['BCC']);
                    $_1311136515 = $_754080943;
                    $_1311136515['EMAIL_TO'] = $_754080943['BCC'];
                    $_1311136515['SUBJECT'] = $_1311136515['SUBJECT'] . ' - ' . $_754080943['EMAIL_TO'];
                    unset($_1311136515['BCC']);
                    $_516700919 = CEvent::Send(self::EVENT_ADD_MAILING, $_502923035, $_1311136515, 'Y');
                    unset($_754080943['BCC']);
                }
                return $_1477231671['result']['email_id'];
            }
        }
    }

    public function AddMailingMessage($_1822731620 = array(), $_754080943 = array(), $_761901698 = array())
    {
        global $DB;
        $_74737845 = array();
        if (empty($_1822731620['MAILING_ID'])) {
            return false;
        }
        $_260962268 = CSotbitMailingHelp::GetMailingInfo();
        $_1171335915 = $_260962268[$_1822731620['MAILING_ID']];
        $_1171335915['EXCLUDE_UNSUBSCRIBED_USER_MORE'] = unserialize($_1171335915['EXCLUDE_UNSUBSCRIBED_USER_MORE']);
        $_754080943['EMAIL_TO'] = trim($_754080943['EMAIL_TO']);
        if (empty($_754080943['EMAIL_FROM']) || empty($_754080943['EMAIL_TO']) || empty($_754080943['SUBJECT'])) {
            $_529922068 = CSotbitMailingHelp::ProgressFileGetArray($_1822731620['MAILING_ID'], $_1171335915['COUNT_RUN']);
            $_529922068['COUNT_NOW'] = $_529922068['COUNT_NOW'] + 1;
            CSotbitMailingHelp::ProgressFile($_1822731620['MAILING_ID'], $_1171335915['COUNT_RUN'], $_529922068);
            return false;
        }
        $_817130455 = array();
        $_817130455['MAILING_EVENT_ID'] = $_1171335915['ID'];
        if ($_1171335915['USER_AUTH'] == 'Y' && $_754080943['PARAM_MESSEGE']['USER_ID']) {
            $_22999859 = randString(15, array('abcdefghijklnmopqrstuvwxyz', 'ABCDEFGHIJKLNMOPQRSTUVWXYZ', '0123456789'));
            $_754080943['PARAM_MESSEGE']['USER_AUTH'] = $_22999859;
            $_754080943['MESSEGE_PARAMETR']['USER_AUTH'] = $_22999859;
            $_754080943['MESSEGE_PARAMETR']['USER_ID'] = $_754080943['PARAM_MESSEGE']['USER_ID'];
        }
        $_754080943['MESSEGE'] = CSotbitMailingHelp::ReplaceVariables($_754080943['MESSEGE'], $_817130455);
        unset($_754080943['PARAM_MESSEGE']['RECOMMEND_PRODUCT_ID']);
        unset($_754080943['PARAM_MESSEGE']['VIEWED_PRODUCT_ID']);
        unset($_754080943['PARAM_MESSEGE']['BASKET_PRODUCT_ID']);
        $_754080943['PARAM_MESSEGE'] = serialize($_754080943['PARAM_MESSEGE']);
        if ($_754080943['MESSEGE_PARAMETR'] && $_817130455) {
            $_754080943['MESSEGE_PARAMETR'] = array_merge($_754080943['MESSEGE_PARAMETR'], $_817130455);
        }
        $_754080943['MESSEGE_PARAMETR'] = serialize($_754080943['MESSEGE_PARAMETR']);
        $_191420419 = CSotbitMailingMessage::Add(array('ID_EVENT' => $_1171335915['ID'], 'DATE_CREATE' => Date($DB->DateFormatToPHP(CLang::GetDateFormat('FULL', SITE_ID))), 'SEND_SYSTEM' => $_1171335915['EVENT_SEND_SYSTEM'], 'EMAIL_FROM' => $_754080943['EMAIL_FROM'], 'EMAIL_TO' => $_754080943['EMAIL_TO'], 'COUNT_RUN' => $_1171335915['COUNT_RUN'] + 1, 'BCC' => $_754080943['BCC'], 'SUBJECT' => $_754080943['SUBJECT'], 'MESSEGE' => $_754080943['MESSEGE'], 'MESSEGE_PARAMETR' => $_754080943['MESSEGE_PARAMETR'], 'PARAM_1' => $_754080943['PARAM_1'], 'PARAM_2' => $_754080943['PARAM_2'], 'PARAM_3' => $_754080943['PARAM_3'], 'PARAM_MESSEGE' => $_754080943['PARAM_MESSEGE'],));
        if ($_191420419) {
            $_74737845['ID'] = $_191420419;
        }
        if ($_1171335915['MODE'] == 'WORK' && !self::$_794221308) {
            CSotbitMailingTools::SendMessage(array('ID_EVENT' => $_1171335915['ID'], 'ID' => $_191420419));
            $_74737845['SEND'] = 'Y';
        }
        if ($_1171335915['MODE'] == 'WORK' && self::$_794221308) {
            self::$_759810266[] = ['ID_EVENT' => $_1171335915['ID'], 'ID' => $_191420419];
        }
        if ($_GET['SOTBIT_MAILING_DETAIL']) {
            CSotbitMailingHelp::slaap(COption::GetOptionString('sotbit.mailing', 'MAILING_MESSAGE_SLAAP', '0.001'));
        }
        $_529922068 = CSotbitMailingHelp::ProgressFileGetArray($_1822731620['MAILING_ID'], $_1171335915['COUNT_RUN']);
        if (empty($_529922068['COUNT_ALL'])) {
            $_529922068['COUNT_ALL'] = $_1822731620['PROGRESS_COUNT_ALL'];
        }
        $_529922068['COUNT_NOW'] = $_529922068['COUNT_NOW'] + 1;
        $_529922068['COUNT_SEND'] = $_529922068['COUNT_SEND'] + 1;
        $_529922068['EMAIL_TO_SEND'] = $_529922068['EMAIL_TO_SEND'] + 1;
        $_74737845['PROGRESS'] = $_529922068;
        CSotbitMailingHelp::ProgressFile($_1822731620['MAILING_ID'], $_1171335915['COUNT_RUN'], $_529922068, array('EMAIL_TO_SEND_INFO' => $_754080943['EMAIL_TO']));
        return $_74737845;
    }

    public function AgentMailingNeedAction()
    {
        global $USER;
        if (!is_object($USER)) {
            $USER = new CUser();
        }
        CSotbitMailingTools::MailingNeedAction();
        return 'CSotbitMailingTools::AgentMailingNeedAction();';
    }

    public function MailingNeedAction()
    {
        global $DB;
        $_32705249 = array();
        $_1211309364 = CUser::GetList($_1662594227, $_208699573, array(), array('FIELDS' => array('ID', 'EMAIL')));
        while ($_2000575999 = $_1211309364->Fetch()) {
            $_32705249[$_2000575999['EMAIL']] = $_2000575999;
        }
        $_1302794545 = CSotbitMailingSubscribers::GetList(array(), array('USER_ID' => '0'), false, array('ID', 'EMAIL_TO', 'USER_ID'));
        while ($_1321731202 = $_1302794545->Fetch()) {
            if ($_32705249[$_1321731202['EMAIL_TO']]) {
                CSotbitMailingSubscribers::Update($_1321731202['ID'], array('USER_ID' => $_32705249[$_1321731202['EMAIL_TO']]['ID']));
            }
        }
        $_1796246067 = CSotbitMailingEvent::GetList(array(), array('ACTIVE' => 'Y'), false, array('ID', 'TEMPLATE_PARAMS'));
        while ($_95748629 = $_1796246067->Fetch()) {
            $_761901698 = array();
            $_761901698 = unserialize($_95748629['TEMPLATE_PARAMS']);
            $_761901698['MAILING_EVENT_ID'] = $_95748629['ID'];
            if ($_761901698['NEW_COUPON_ADD'] == 'Y' && \Bitrix\Main\Loader::includeModule('sale')) {
                $_1849684381 = array();
                $_1917078736 = array(
                    'ID_EVENT' => $_761901698['MAILING_EVENT_ID'],
                    '>=DATE_CREATE' => date($DB->DateFormatToPHP(CLang::GetDateFormat('FULL')), mktime(date('H') - $_761901698['NEW_COUPON_LIFETIME'] - 24, date('i'), 0, date('n'), date('d'), date('Y'))),
                    '<=DATE_CREATE' => date($DB->DateFormatToPHP(CLang::GetDateFormat('FULL')), mktime(date('H') - $_761901698['NEW_COUPON_LIFETIME'] + 24, date('i'), 0, date('n'), date('d'), date('Y'))),
                );
                $_1376434809 = CSotbitMailingMessage::GetList(array(), $_1917078736, false, array('ID', 'PARAM_2', 'PARAM_MESSEGE'));
                while ($_728517539 = $_1376434809->Fetch()) {
                    $_728517539['PARAM_MESSEGE'] = unserialize($_728517539['PARAM_MESSEGE']);
                    if ($_728517539['PARAM_2']) {
                        $_1849684381[$_728517539['PARAM_2']] = $_728517539['PARAM_2'];
                    }
                    if ($_728517539['PARAM_MESSEGE']['COUPON']) {
                        $_1849684381[$_728517539['PARAM_MESSEGE']['COUPON']] = $_728517539['PARAM_MESSEGE']['COUPON'];
                    }
                }
                if (count($_1849684381) > 0) {
                    $_1818199669 = (new \Bitrix\Main\Type\DateTime())->add(-$_761901698['NEW_COUPON_LIFETIME'] . ' hours');
                    $_255656248 = \Bitrix\Sale\Internals\DiscountCouponTable::getList(array('filter' => array('COUPON' => $_1849684381, 'DISCOUNT_ID' => $_761901698['NEW_COUPON_DISCOUNT_ID'], 'DATE_APPLY' => false, '<=DATE_CREATE' => $_1818199669)));
                    while ($_1018537045 = $_255656248->Fetch()) {
                        if ($_761901698['NEW_COUPON_LIFETIME_ACTION'] == 'DELETE') {
                            \Bitrix\Sale\Internals\DiscountCouponTable::delete($_1018537045['ID']);
                        } elseif ($_761901698['NEW_COUPON_LIFETIME_ACTION'] == 'DEACTION') {
                            \Bitrix\Sale\Internals\DiscountCouponTable::update($_1018537045['ID'], array('ACTIVE' => 'N'));
                        }
                    }
                }
            }
            if ($_761901698['COUPON_ADD'] == 'Y' && CModule::IncludeModule('catalog')) {
                $_1808949156 = array();
                $_1594570806 = array(
                    'ID_EVENT' => $_761901698['MAILING_EVENT_ID'],
                    '>=DATE_CREATE' => date($DB->DateFormatToPHP(CLang::GetDateFormat('FULL')), mktime(date('H') - $_761901698['COUPON_TIME_LIFE'] - 24, date('i'), 0, date('n'), date('d'), date('Y'))),
                    '<=DATE_CREATE' => date($DB->DateFormatToPHP(CLang::GetDateFormat('FULL')), mktime(date('H') - $_761901698['COUPON_TIME_LIFE'] + 24, date('i'), 0, date('n'), date('d'), date('Y'))),
                );
                $_558545227 = CSotbitMailingMessage::GetList(array(), $_1594570806, false, array('ID', 'PARAM_2', 'PARAM_MESSEGE'));
                while ($_1778335098 = $_558545227->Fetch()) {
                    $_1778335098['PARAM_MESSEGE'] = unserialize($_1778335098['PARAM_MESSEGE']);
                    if ($_1778335098['PARAM_2']) {
                        $_1808949156[$_1778335098['PARAM_2']] = $_1778335098['PARAM_2'];
                    }
                    if ($_1778335098['PARAM_MESSEGE']['COUPON']) {
                        $_1808949156[$_1778335098['PARAM_MESSEGE']['COUPON']] = $_1778335098['PARAM_MESSEGE']['COUPON'];
                    }
                }
                if (count($_1808949156) > 0) {
                    $_211103869 = CCatalogDiscountCoupon::GetList(array(), array('COUPON' => $_1808949156, 'DISCOUNT_ID' => $_761901698['COUPON_DISCOUNT_ID'], 'DATE_APPLY' => false, '<=DATE_CREATE' => date($DB->DateFormatToPHP(CLang::GetDateFormat('FULL')), mktime(date('H') - $_761901698['COUPON_TIME_LIFE'], date('i'), 0, date('n'), date('d'), date('Y')))), false, false, array());
                    while ($_50710865 = $_211103869->Fetch()) {
                        if ($_761901698['COUPON_TIME_LIFE_ACTION'] == 'DELETE') {
                            CCatalogDiscountCoupon::Delete($_50710865['ID']);
                        } elseif ($_761901698['COUPON_TIME_LIFE_ACTION'] == 'DEACTION') {
                            CCatalogDiscountCoupon::Update($_50710865['ID'], array('ACTIVE' => 'N'));
                        }
                    }
                }
            }
        }
    }
}