<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
$module_id = "kit.mailing";
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $module_id . "/include.php");
IncludeModuleLangFile(__FILE__);


$CKitMailingTools = new CKitMailingTools();

CKitMailingHelp::CacheConstantCheck();

$POST_RIGHT = $APPLICATION->GetGroupRight($module_id);
if($POST_RIGHT <= "D")
{
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

// START
$MailingList = CKitMailingHelp::GetMailingInfo();
// END

$sTableID = "tbl_kit_mailing_static"; // ID �������
$oSort = new CAdminSorting($sTableID, "DATE_SEND", "asc"); // ������ ����������
$lAdmin = new CAdminList($sTableID, $oSort); // �������� ������ ������


function CheckFilter()
{
    global $FilterArr, $lAdmin;
    foreach($FilterArr as $f) global $$f;

    return count($lAdmin->arFilterErrors) == 0; // ���� ������ ����, ������ false;
}

// *********************** /CheckFilter ******************************* //

// ������ �������� �������
$FilterArr = Array(
    "find_ID_EVENT",
    "find_DATE_SEND_from",
    "find_DATE_SEND_to",
    "find_COUNT_RUN_from",
    "find_COUNT_RUN_to",
);

// �������������� ������
$lAdmin->InitFilter($FilterArr);


// ��������� ���������� �� 3 ������
if(empty($find_DATE_SEND_from) && empty($_REQUEST['set_filter']))
{
    $find_DATE_SEND_from = date('d.m.Y', mktime(0, 0, 0, date("m") - 3, date("d"), date("Y")));
}

// ���� ��� �������� ������� ���������, ���������� ���
if(CheckFilter())
{
    // �������� ������ ���������� ��� ������� CRubric::GetList() �� ������ �������� �������
    $arFilter = Array(
        "ID_EVENT" => $find_ID_EVENT,
        ">=COUNT_RUN" => $find_COUNT_RUN_from,
        "<=COUNT_RUN" => $find_COUNT_RUN_to,
    );
    if($find_DATE_SEND_from)
    {
        $arFilter['>=DATE_SEND'] = $find_DATE_SEND_from . ' 00:00:00';
    }
    if($find_DATE_SEND_to)
    {
        $arFilter['<=DATE_SEND'] = $find_DATE_SEND_to . ' 23:59:59';
    }
}

// ******************************************************************** //
//                ������� ��������� ������                              //
// ******************************************************************** //

// ���������� ��� ����������
// START 
$arrStaticInfo = array();
 
$arrStaticInfo['MAILING_USER_ID'] = array();
$arrStaticInfo['MAILING_USER_ID_MESSAGE'] = array();

$arrStaticInfo['USER_OPEN']['YES'] = 0; 
$arrStaticInfo['USER_OPEN']['NO'] = 0;  
$arrStaticInfo['USER_OPEN']['ALL'] = 0;  
$arrStaticInfo['USER_BACK']['YES'] = 0; 
$arrStaticInfo['USER_BACK']['NO'] = 0;  
$arrStaticInfo['USER_BACK']['ALL'] = 0;  
$arrStaticInfo['USER_BACK_OPEN_MAIL']['YES'] = 0; 
$arrStaticInfo['USER_BACK_OPEN_MAIL']['NO'] = 0;  
$arrStaticInfo['USER_BACK_OPEN_MAIL']['ALL'] = 0;   
$arrStaticInfo['STATICK_HOUR_USER'] = array(
    '00' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '01' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '02' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '03' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '04' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '05' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '06' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '07' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '08' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ),  
    '09' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '10' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '11' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '12' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '13' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '14' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '15' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '16' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '17' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '18' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ),    
    '19' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '20' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '21' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '22' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
    '23' => array(
        'USER_OPEN'=> 0,
        'USER_BACK'=> 0,
        'DATE_SEND' => 0
    ), 
);  
$arrStaticInfo['STATICK_HOUR_USER_ALL'] =  array(
    'USER_OPEN'=> 0,
    'USER_BACK'=> 0,
    'DATE_SEND' => 0
);


//����������� ��� �������
$arrStaticInfo['STATICK_COUPON'] = array();
$arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL'] = array();
$arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL_DATE_COUNT'] = array();
$arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_NOAPPLY'] = array();
$arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY'] = array();
$arrStaticInfo['STATICK_COUPON']['COUPON_MESSAGE_ID'] = array();
$arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY_DATE_COUNT'] = array();
$arrStaticInfo['STATICK_COUPON']['COUPON_NOAPPLY_COUNT'] = 0;
$arrStaticInfo['STATICK_COUPON']['COUPON_APPLY_COUNT'] = 0;
$arrStaticInfo['STATICK_COUPON']['COUPON_ALL_COUNT'] = 0;

$arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_HOUR_INFO'] = array(
    '00' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ), 
    '01' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ), 
    '02' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ), 
    '03' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ), 
    '04' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ), 
    '05' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ),
    '06' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ),
    '07' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ), 
    '08' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ),  
    '09' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ), 
    '10' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ), 
    '11' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ), 
    '12' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ),
    '13' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ), 
    '14' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ), 
    '15' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ), 
    '16' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ),
    '17' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ), 
    '18' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ),    
    '19' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ),
    '20' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ),
    '21' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ),
    '22' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ), 
    '23' => array(
        'COUPON_CREATE'=> 0,
        'COUPON_APPLY'=> 0,
    ), 
); 
$arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_HOUR_INFO_ALL'] = 0;


//���������� �� �������
$arrStaticInfo['STATICK_ORDER']['ORDER_ELEMENT'] = array();
$arrStaticInfo['STATICK_ORDER']['ORDER_DATE_CREATE_COUNT'] = array();
$arrStaticInfo['STATICK_ORDER']['ORDER_PRICE_ALL_COUNT'] = 0;
$arrStaticInfo['STATICK_ORDER']['ORDER_HOUR_INFO'] = array(
    '00' => array(
        'ORDER_CREATE'=> 0,
    ), 
    '01' => array(
        'ORDER_CREATE'=> 0,
    ),  
    '02' => array(
        'ORDER_CREATE'=> 0,
    ),  
    '03' => array(
        'ORDER_CREATE'=> 0,
    ),  
    '04' => array(
        'ORDER_CREATE'=> 0,
    ), 
    '05' => array(
        'ORDER_CREATE'=> 0,
    ), 
    '06' => array(
        'ORDER_CREATE'=> 0,
    ), 
    '07' => array(
        'ORDER_CREATE'=> 0,
    ),  
    '08' => array(
        'ORDER_CREATE'=> 0,
    ),  
    '09' => array(
        'ORDER_CREATE'=> 0,
    ),  
    '10' => array(
        'ORDER_CREATE'=> 0,
    ),  
    '11' => array(
        'ORDER_CREATE'=> 0,
    ),  
    '12' => array(
        'ORDER_CREATE'=> 0,
    ), 
    '13' => array(
        'ORDER_CREATE'=> 0,
    ),  
    '14' => array(
        'ORDER_CREATE'=> 0,
    ),  
    '15' => array(
        'ORDER_CREATE'=> 0,
    ),  
    '16' => array(
        'ORDER_CREATE'=> 0,
    ), 
    '17' => array(
        'ORDER_CREATE'=> 0,
    ),  
    '18' => array(
        'ORDER_CREATE'=> 0,
    ),     
    '19' => array(
        'ORDER_CREATE'=> 0,
    ), 
    '20' => array(
        'ORDER_CREATE'=> 0,
    ), 
    '21' => array(
        'ORDER_CREATE'=> 0,
    ), 
    '22' => array(
        'ORDER_CREATE'=> 0,
    ),  
    '23' => array(
        'ORDER_CREATE'=> 0,
    ),  
); 
$arrStaticInfo['STATICK_ORDER']['ORDER_MESSEGE_ID'] = array();


//������� ����� ���� �������������
//START
$arrAllUser = array();
$dbResUser = CUser::GetList($byUser, $orderUser, array(), array('FIELDS' => array('ID', 'EMAIL')));
while($arItemsUser = $dbResUser->Fetch())
{
    $arrAllUser[$arItemsUser['EMAIL']] = $arItemsUser['ID'];
}
//END


//END   
$arrMessageStatic = array();
$cData = new CKitMailingMessage;
$selectMessage = array(
    'ID',
    'ID_EVENT',
    'COUNT_RUN',
    'SEND',
    'DATE_SEND',
    'EMAIL_TO',
    'STATIC_USER_OPEN',
    'STATIC_USER_OPEN_DATE',
    'STATIC_USER_BACK',
    'STATIC_USER_BACK_DATE',
    'STATIC_USER_ID',
    'STATIC_SALE_UID',
    'STATIC_GUEST_ID',
    'STATIC_PAGE_START',
    'STATIC_ORDER_ID',
    'PARAM_1',
    'PARAM_2',
    'PARAM_3',
    'PARAM_MESSEGE'
);
if(is_numeric($_GET['ID']))
{
    $arFilter['ID_EVENT'] = $_GET['ID'];
}

$arFilter['SEND'] = 'Y';

$date_send_first = false;
$rsData = $cData->GetList(array($by => $order), $arFilter, false, $selectMessage);
while($arRes = $rsData->Fetch())
{
    printr($arRes);
    $arRes['STATIC_PAGE_START'] = unserialize($arRes['STATIC_PAGE_START']);
    $arRes['STATIC_USER_OPEN_DATE'] = unserialize($arRes['STATIC_USER_OPEN_DATE']);
    $arRes['STATIC_USER_BACK_DATE'] = unserialize($arRes['STATIC_USER_BACK_DATE']);
    $arRes['PARAM_MESSEGE'] = unserialize($arRes['PARAM_MESSEGE']);
    if(empty($date_send_first))
    {
        $date_send_first = $arRes['DATE_SEND'];
    }

    //���������� �������� �����
    //START
    if($arRes['STATIC_USER_OPEN'] == 'Y')
    {
        $arrStaticInfo['USER_OPEN']['YES'] = $arrStaticInfo['USER_OPEN']['YES'] + 1;
        $arrStaticInfo['USER_OPEN']['ALL'] = $arrStaticInfo['USER_OPEN']['ALL'] + 1;
    }
    else
    {
        $arrStaticInfo['USER_OPEN']['NO'] = $arrStaticInfo['USER_OPEN']['NO'] + 1;
        $arrStaticInfo['USER_OPEN']['ALL'] = $arrStaticInfo['USER_OPEN']['ALL'] + 1;
    }
    //END

    //����� ������� ������������� � ��� �� ����
    //START
    if($arRes['STATIC_USER_BACK'] == 'Y')
    {
        $arrStaticInfo['USER_BACK']['YES'] = $arrStaticInfo['USER_BACK']['YES'] + 1;
        $arrStaticInfo['USER_BACK']['ALL'] = $arrStaticInfo['USER_BACK']['ALL'] + 1;
    }
    else
    {
        $arrStaticInfo['USER_BACK']['NO'] = $arrStaticInfo['USER_BACK']['NO'] + 1;
        $arrStaticInfo['USER_BACK']['ALL'] = $arrStaticInfo['USER_BACK']['ALL'] + 1;
    }
    //END

    //������� ������������� � ��� �� ���� �� �������� �����
    //START
    if($arRes['STATIC_USER_BACK'] == 'Y' && $arRes['STATIC_USER_OPEN'] == 'Y')
    {
        $arrStaticInfo['USER_BACK_OPEN_MAIL']['YES'] = $arrStaticInfo['USER_BACK_OPEN_MAIL']['YES'] + 1;
        $arrStaticInfo['USER_BACK_OPEN_MAIL']['ALL'] = $arrStaticInfo['USER_BACK_OPEN_MAIL']['ALL'] + 1;
    }
    elseif($arRes['STATIC_USER_OPEN'] == 'Y')
    {
        $arrStaticInfo['USER_BACK_OPEN_MAIL']['NO'] = $arrStaticInfo['USER_BACK_OPEN_MAIL']['NO'] + 1;
        $arrStaticInfo['USER_BACK_OPEN_MAIL']['ALL'] = $arrStaticInfo['USER_BACK_OPEN_MAIL']['ALL'] + 1;
    }
    //END

    //����� ��� �������� ����� � ���� �����
    //START
    if($arRes['DATE_SEND'])
    {
        $dateParse = ParseDateTime($arRes['DATE_SEND']);

        if(empty($arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]))
        {
            $arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] = 1;
            $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]['DATE_SEND'] = 1;
        }
        else
        {
            $arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] = $arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] + 1;
            $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]['DATE_SEND'] = $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]['DATE_SEND'] + 1;
        }
        $arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT_ALL'] = $arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT_ALL'] + 1;
        ksort($arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT']);

        if($dateParse['HH'])
        {
            $arrStaticInfo['STATICK_HOUR_USER'][$dateParse['HH']]['DATE_SEND'] = $arrStaticInfo['STATICK_HOUR_USER'][$dateParse['HH']]['DATE_SEND'] + 1;
            $arrStaticInfo['STATICK_HOUR_USER_ALL']['DATE_SEND'] = $arrStaticInfo['STATICK_HOUR_USER_ALL']['DATE_SEND'] + 1;
        }
    }
    //END

    //����� ��� �������� �����
    //START
    if($arRes['STATIC_USER_OPEN_DATE'])
    {
        foreach($arRes['STATIC_USER_OPEN_DATE'] as $date)
        {
            $dateParse = ParseDateTime($date);

            //������� �������� �� �����
            if(empty($arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]))
            {
                $arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] = 1;
                $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]['DATE_OPEN'] = 1;
            }
            else
            {
                $arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] = $arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] + 1;
                $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]['DATE_OPEN'] = $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]['DATE_OPEN'] + 1;
            }
            $arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT_ALL'] = $arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT_ALL'] + 1;
            ksort($arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT']);

            //������� �������� �� �����
            if($dateParse['HH'])
            {
                $arrStaticInfo['STATICK_HOUR_USER'][$dateParse['HH']]['USER_OPEN'] = $arrStaticInfo['STATICK_HOUR_USER'][$dateParse['HH']]['USER_OPEN'] + 1;
                $arrStaticInfo['STATICK_HOUR_USER_ALL']['USER_OPEN'] = $arrStaticInfo['STATICK_HOUR_USER_ALL']['USER_OPEN'] + 1;
            }
        }
    }
    //END

    //����� ��� �������� �� �������
    //START
    if($arRes['STATIC_USER_BACK_DATE'])
    {
        foreach($arRes['STATIC_USER_BACK_DATE'] as $date)
        {
            $dateParse = ParseDateTime($date);

            //������� �������� �� �����
            if(empty($arrStaticInfo['STATICK_USER_BACK_ELEMENT_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]))
            {
                $arrStaticInfo['STATICK_USER_BACK_ELEMENT_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] = 1;
            }
            else
            {
                $arrStaticInfo['STATICK_USER_BACK_ELEMENT_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] = $arrStaticInfo['STATICK_USER_BACK_ELEMENT_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] + 1;
            }
            $arrStaticInfo['STATICK_USER_BACK_ELEMENT_COUNT_ALL'] = $arrStaticInfo['STATICK_USER_BACK_ELEMENT_COUNT_ALL'] + 1;
            ksort($arrStaticInfo['STATICK_USER_BACK_ELEMENT_COUNT']);

            //������� �������� �� �����
            if($dateParse['HH'])
            {
                $arrStaticInfo['STATICK_HOUR_USER'][$dateParse['HH']]['USER_BACK'] = $arrStaticInfo['STATICK_HOUR_USER'][$dateParse['HH']]['USER_BACK'] + 1;
                $arrStaticInfo['STATICK_HOUR_USER_ALL']['USER_BACK'] = $arrStaticInfo['STATICK_HOUR_USER_ALL']['USER_BACK'] + 1;
            }
        }
    }
    //END

    //������� �� �������
    //START
    if($arRes['STATIC_PAGE_START'])
    {
        //������ ������� ���� ��������
        $strReplaceLink = array(
            'utm_source=email&',
            'utm_source=email',
            'utm_medium=kit_mailing&',
            'utm_medium=kit_mailing',
            'utm_campaign=mailing_' . $arRes['ID_EVENT'] . '&',
            'utm_campaign=mailing_' . $arRes['ID_EVENT'],
            'utm_source=newsletter&',
            'utm_source=newsletter',
            'utm_medium=email&',
            'utm_medium=email',
            'utm_campaign=kit_mailing_' . $arRes['ID_EVENT'] . '&',
            'utm_campaign=kit_mailing_' . $arRes['ID_EVENT'],
            'MAILING_MESSAGE=' . $arRes['ID'] . '&',
            'MAILING_MESSAGE=' . $arRes['ID']
        );
        //���� ���� ����������� ������� ������
        if($arRes['PARAM_MESSEGE']['USER_AUTH'])
        {
            $strReplaceLink[] = 'USER_AUTH=' . $arRes['PARAM_MESSEGE']['USER_AUTH'] . '&';
            $strReplaceLink[] = 'USER_AUTH=' . $arRes['PARAM_MESSEGE']['USER_AUTH'];
        }


        foreach($arRes['STATIC_PAGE_START'] as $klink => $vlink)
        {
            //������� �������
            foreach($strReplaceLink as $replace)
            {
                $vlink = str_replace($replace, "", $vlink);
            }
            //���� ��������� ���� ?  ������ ���
            if(substr($vlink, -1) == '?')
            {
                $vlink = str_replace('?', "", $vlink);
            }
            //���� ��������� �����
            if(substr($vlink, -1) == '&')
            {
                $vlink = mb_substr($vlink, 0, -1);
            }

            //$arRes['STATIC_PAGE_START'][$klink] = $vlink; 
            //�������� ����������
            if($arrStaticInfo['PAGE_START'][$vlink])
            {
                $arrStaticInfo['PAGE_START'][$vlink] = $arrStaticInfo['PAGE_START'][$vlink] + 1;
            }
            else
            {
                $arrStaticInfo['PAGE_START'][$vlink] = 1;
            }
        }
        arsort($arrStaticInfo['PAGE_START']);
    }
    //END

    // ������� ������ ����������� �����
    // START
    if($arRes['EMAIL_TO'])
    {
        $ARR_PARSE_EMAIL_TO = explode('@', $arRes['EMAIL_TO']);
        $ARR_PARSE_EMAIL_TO[1] = trim($ARR_PARSE_EMAIL_TO[1]);
        if($arrStaticInfo['EMAIL_TO_DOMAIN'][$ARR_PARSE_EMAIL_TO[1]])
        {
            $arrStaticInfo['EMAIL_TO_DOMAIN'][$ARR_PARSE_EMAIL_TO[1]] = $arrStaticInfo['EMAIL_TO_DOMAIN'][$ARR_PARSE_EMAIL_TO[1]] + 1;
        }
        else
        {
            $arrStaticInfo['EMAIL_TO_DOMAIN'][$ARR_PARSE_EMAIL_TO[1]] = 1;
        }
        //������������� ����� �� �������
        if($arRes['STATIC_USER_OPEN'] == 'Y')
        {
            if($arrStaticInfo['EMAIL_TO_DOMAIN_OPEN'][$ARR_PARSE_EMAIL_TO[1]])
            {
                $arrStaticInfo['EMAIL_TO_DOMAIN_OPEN'][$ARR_PARSE_EMAIL_TO[1]] = $arrStaticInfo['EMAIL_TO_DOMAIN_OPEN'][$ARR_PARSE_EMAIL_TO[1]] + 1;
            }
            else
            {
                $arrStaticInfo['EMAIL_TO_DOMAIN_OPEN'][$ARR_PARSE_EMAIL_TO[1]] = 1;
            }
        }
    }
    arsort($arrStaticInfo['EMAIL_TO_DOMAIN']);
    arsort($arrStaticInfo['EMAIL_TO_DOMAIN_OPEN']);
    // END


    // ������� ������
    // START
    if($arRes['PARAM_MESSEGE']['COUPON'])
    {
        $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL'][$arRes['PARAM_MESSEGE']['COUPON']] = $arRes['PARAM_MESSEGE']['COUPON'];

        $arrStaticInfo['STATICK_COUPON']['COUPON_MESSAGE_ID'][$arRes['PARAM_MESSEGE']['COUPON']] = $arRes['ID'];
        if($arRes['DATE_SEND'])
        {
            //������� ���������� ������ ������� �� ����
            $dateParse = ParseDateTime($arRes['DATE_SEND']);
            if(empty($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL_DATE_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]))
            {
                $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL_DATE_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] = 1;
            }
            else
            {
                $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL_DATE_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] = $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL_DATE_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] + 1;
            }
            ksort($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL_DATE_COUNT']);

            //������� ������ ������� ����� 
            if($dateParse['HH'])
            {
                $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_HOUR_INFO'][$dateParse['HH']]['COUPON_CREATE'] = $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_HOUR_INFO'][$dateParse['HH']]['COUPON_CREATE'] + 1;
            }
        }
    }
    // END

    // ������� ���� ������������� �� ���������
    // START
    if($arRes['PARAM_MESSEGE']['USER_ID'])
    {
        $arrStaticInfo['MAILING_USER_ID'][$arRes['PARAM_MESSEGE']['USER_ID']] = $arRes['PARAM_MESSEGE']['USER_ID'];

        $arrStaticInfo['MAILING_USER_ID_MESSAGE'][$arRes['PARAM_MESSEGE']['USER_ID']][] = $arRes['ID'];

    }
    elseif($arrAllUser[$arRes['EMAIL_TO']])
    {
        $arrStaticInfo['MAILING_USER_ID'][$arrAllUser[$arRes['EMAIL_TO']]] = $arrAllUser[$arRes['EMAIL_TO']];
        $arrStaticInfo['MAILING_USER_ID_MESSAGE'][$arrAllUser[$arRes['EMAIL_TO']]][] = $arRes['ID'];
    }
    // END

    //������� ��� ������ �� ����������
    //START
    if($arRes['STATIC_ORDER_ID'])
    {
        $arrStaticInfo['STATICK_ORDER']['ORDER_MESSEGE_ID'][$arRes['ID']] = $arRes['STATIC_ORDER_ID'];
        $arrStaticInfo['STATICK_ORDER']['ORDER_MESSEGE_ID_ORDER'][$arRes['STATIC_ORDER_ID']] = $arRes['ID'];
    }
    //END
}


//������� ������  �� ������� � ������� ����������
//START
if(CModule::IncludeModule("catalog") && $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL'])
{
    $arrStaticInfo['STATICK_COUPON']['COUPON_ALL_COUNT'] = count($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL']);
    $rsDiscountCoupon = CCatalogDiscountCoupon::GetList(
        array(),
        array(
            //'COUPON' => $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL'],
        ),
        false,
        false,
        array('DISCOUNT_ID', 'COUPON', 'DATE_APPLY')
    );
    while($arrDiscountCoupon = $rsDiscountCoupon->Fetch())
    {
        if($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL'][$arrDiscountCoupon['COUPON']])
        {
            if($arrDiscountCoupon['DATE_APPLY'])
            {
                $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY'][$arrDiscountCoupon['COUPON']] = $arrDiscountCoupon['COUPON'];
                $arrStaticInfo['STATICK_COUPON']['COUPON_APPLY_COUNT'] = $arrStaticInfo['STATICK_COUPON']['COUPON_APPLY_COUNT'] + 1;

                //������� ���������� ������ ������� �� ����
                $dateParse = ParseDateTime($arrDiscountCoupon['DATE_APPLY']);
                if(empty($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY_DATE_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]))
                {
                    $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY_DATE_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] = 1;
                }
                else
                {
                    $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY_DATE_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] = $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY_DATE_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] + 1;
                }
                ksort($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY_DATE_COUNT']);

                //������� ������ ������� �����
                if($dateParse['HH'])
                {
                    $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_HOUR_INFO'][$dateParse['HH']]['COUPON_APPLY'] = $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_HOUR_INFO'][$dateParse['HH']]['COUPON_APPLY'] + 1;
                    $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_HOUR_INFO_ALL'] = $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_HOUR_INFO_ALL'] + 1;
                }
            }
            else
            {
                $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_NOAPPLY'][] = $arrDiscountCoupon['COUPON'];
                $arrStaticInfo['STATICK_COUPON']['COUPON_NOAPPLY_COUNT'] = $arrStaticInfo['STATICK_COUPON']['COUPON_NOAPPLY_COUNT'] + 1;
            }
        }
    }
}
//END


//������� ������ � ���������� �� ���
//START
$arrOrder = array();
$arrOrderId = array();
if(CModule::IncludeModule("sale") && $arrStaticInfo['MAILING_USER_ID'])
{
    //������� ������
    //START 
    $arFilterOrder['>=DATE_INSERT'] = $date_send_first;
    //������� ������ �� ID
    $arSelectOrder = array(
        'ID',
        'PRICE',
        'DATE_INSERT',
        'USER_ID'
    );

    $dbResOrder = CSaleOrder::GetList(array('DATE_INSERT' => 'ASC'), $arFilterOrder, false, false, $arSelectOrder);
    while($arItemsOrder = $dbResOrder->Fetch())
    {
        if($arrStaticInfo['STATICK_ORDER']['ORDER_MESSEGE_ID_ORDER'][$arItemsOrder['ID']])
        {
            $arItemsOrder['MESSAGE_ID'] = $arrStaticInfo['STATICK_ORDER']['ORDER_MESSEGE_ID_ORDER'][$arItemsOrder['ID']];
        }

        //������� ������ ��� �������������
        $arrOrder[$arItemsOrder['ID']] = $arItemsOrder;
    }
    //END

    //������� ������ ��� �������
    //START
    $basketFillter = array(
        // 'ORDER_ID' => $arrOrderId
    );
    $basketFillter = array(
        '!ORDER_ID' => false
    );

    $basketSelect = array(
        'ORDER_ID',
        'PRODUCT_ID',
        'PRICE',
        'DISCOUNT_COUPON'
    );
    $resBasketItems = CSaleBasket::GetList(
        array('DATE_INSERT' => 'ASC'),
        $basketFillter,
        false,
        false,
        $basketSelect
    );
    while($arItemsBasket = $resBasketItems->Fetch())
    {
        if($arrOrder[$arItemsBasket['ORDER_ID']])
        {
            if($arItemsBasket['DISCOUNT_COUPON'])
            {
                $arrOrder[$arItemsBasket['ORDER_ID']]['COUPON'] = $arItemsBasket['DISCOUNT_COUPON'];
                if($arrStaticInfo['STATICK_COUPON']['COUPON_MESSAGE_ID'][$arItemsBasket['DISCOUNT_COUPON']])
                {
                    $arrOrder[$arItemsBasket['ORDER_ID']]['MESSAGE_ID'] = $arrStaticInfo['STATICK_COUPON']['COUPON_MESSAGE_ID'][$arItemsBasket['DISCOUNT_COUPON']];
                    $arrStaticInfo['STATICK_ORDER']['ORDER_MESSEGE_ID_ORDER'][$arItemsBasket['ORDER_ID']] = $arrStaticInfo['STATICK_COUPON']['COUPON_MESSAGE_ID'][$arItemsBasket['DISCOUNT_COUPON']];
                }
            }
            $arrOrder[$arItemsBasket['ORDER_ID']]['ITEM'][$arItemsBasket['PRODUCT_ID']] = $arItemsBasket;
        }
    }
    //END

    foreach($arrOrder as $order_id => $order_info)
    {
        if(empty($order_info['MESSAGE_ID']))
        {
            unset($arrOrder[$order_id]);
        }
    }

    //printr($arrOrder);
}
//END


//���������� ������ ��� ���������� �������
//START
foreach($arrOrder as $arItemsOrder)
{
    if(empty($arrStaticInfo['MAILING_USER_ID'][$arItemsOrder['USER_ID']]) && empty($arItemsOrder['COUPON']))
    {
        continue;
    }

    $arrStaticInfo['STATICK_ORDER']['ORDER_COUNT'] = $arrStaticInfo['STATICK_ORDER']['ORDER_COUNT'] + 1;
    //������� ���������� ������� �� ����
    $dateParse = ParseDateTime($arItemsOrder['DATE_INSERT']);
    if(empty($arrStaticInfo['STATICK_ORDER']['ORDER_DATE_CREATE_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]))
    {
        $arrStaticInfo['STATICK_ORDER']['ORDER_DATE_CREATE_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] = 1;
        $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]['ORDER_CREATE'] = 1;
        $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]['ORDER_CREATE_PRICE'] = $arItemsOrder['PRICE'];

        if($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY'][$arItemsOrder['COUPON']])
        {
            $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]['ORDER_CREATE_COUPON_PRICE'] = $arItemsOrder['PRICE'];
        }
    }
    else
    {
        $arrStaticInfo['STATICK_ORDER']['ORDER_DATE_CREATE_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] = $arrStaticInfo['STATICK_ORDER']['ORDER_DATE_CREATE_COUNT'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']] + 1;
        $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]['ORDER_CREATE'] = $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]['ORDER_CREATE'] + 1;
        $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]['ORDER_CREATE_PRICE'] = $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]['ORDER_CREATE_PRICE'] + $arItemsOrder['PRICE'];

        if($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY'][$arItemsOrder['COUPON']])
        {
            $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]['ORDER_CREATE_COUPON_PRICE'] = $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$dateParse['YYYY'] . '.' . $dateParse['MM'] . '.' . $dateParse['DD']]['ORDER_CREATE_COUPON_PRICE'] + $arItemsOrder['PRICE'];
        }
    }
    ksort($arrStaticInfo['STATICK_ORDER']['ORDER_DATE_CREATE_COUNT']);
    //������� ������ �� �����
    if($dateParse['HH'])
    {
        $arrStaticInfo['STATICK_ORDER']['ORDER_HOUR_INFO'][$dateParse['HH']]['ORDER_CREATE'] = $arrStaticInfo['STATICK_ORDER']['ORDER_HOUR_INFO'][$dateParse['HH']]['ORDER_CREATE'] + 1;
    }
    //������� ��������� ���� �������
    $arrStaticInfo['STATICK_ORDER']['ORDER_PRICE_CURRENCY'] = $arItemsOrder['CURRENCY'];
    $arrStaticInfo['STATICK_ORDER']['ORDER_PRICE_ALL_COUNT'] = $arrStaticInfo['STATICK_ORDER']['ORDER_PRICE_ALL_COUNT'] + $arItemsOrder['PRICE'];

    if($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY'][$arItemsOrder['COUPON']])
    {
        $arrStaticInfo['STATICK_ORDER']['ORDER_PRICE_COUPON_ALL_COUNT'] = $arrStaticInfo['STATICK_ORDER']['ORDER_PRICE_COUPON_ALL_COUNT'] + $arItemsOrder['PRICE'];
    }

    $arrStaticInfo['STATICK_ORDER']['ORDER_ELEMENT'][$arItemsOrder['ID']] = $arItemsOrder;
}

if(is_array($arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'])) {
    ksort($arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING']);
}
//END


//��������� �� ����� � ���� ��� �� ����� ���� �������
// START

if(is_array($arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT']) && count($arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT']) == 1)
{
    foreach($arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT'] as $date => $value)
    {
        $mkTime = MakeTimeStamp($date, "YYYY.MM.DD");
        $date_prev = date('Y.m.d', $mkTime - 86400);
        $arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT'] = array($date_prev => 0);
        $arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT'][$date] = $value;
    }
}

if(is_array($arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT']) && count($arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT']) == 1)
{
    foreach($arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT'] as $date => $value)
    {
        $mkTime = MakeTimeStamp($date, "YYYY.MM.DD");
        $date_prev = date('Y.m.d', $mkTime - 86400);
        $arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT'] = array($date_prev => 0);
        $arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT'][$date] = $value;
    }
}

if(is_array($arrStaticInfo['STATICK_USER_BACK_ELEMENT_COUNT']) && count($arrStaticInfo['STATICK_USER_BACK_ELEMENT_COUNT']) == 1)
{
    foreach($arrStaticInfo['STATICK_USER_BACK_ELEMENT_COUNT'] as $date => $value)
    {
        $mkTime = MakeTimeStamp($date, "YYYY.MM.DD");
        $date_prev = date('Y.m.d', $mkTime - 86400);
        $arrStaticInfo['STATICK_USER_BACK_ELEMENT_COUNT'] = array($date_prev => 0);
        $arrStaticInfo['STATICK_USER_BACK_ELEMENT_COUNT'][$date] = $value;
    }
}

//������
if(count($arrStaticInfo['STATICK_ORDER']['ORDER_DATE_CREATE_COUNT']) == 1)
{
    foreach($arrStaticInfo['STATICK_ORDER']['ORDER_DATE_CREATE_COUNT'] as $date => $value)
    {
        $mkTime = MakeTimeStamp($date, "YYYY.MM.DD");
        $date_prev = date('Y.m.d', $mkTime - 86400);
        $arrStaticInfo['STATICK_ORDER']['ORDER_DATE_CREATE_COUNT'] = array($date_prev => 0);
        $arrStaticInfo['STATICK_ORDER']['ORDER_DATE_CREATE_COUNT'][$date] = $value;
    }
}
if(is_array($arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING']) && count($arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING']) == 1)
{
    foreach($arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'] as $date => $value)
    {
        $mkTime = MakeTimeStamp($date, "YYYY.MM.DD");
        $date_prev = date('Y.m.d', $mkTime - 86400);
        $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'] = array($date_prev => array(
            'DATE_SEND' => 0,
            'DATE_OPEN' => 0,
            'ORDER_CREATE' => 0,
            'ORDER_CREATE_PRICE' => 0
        ));
        $arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'][$date] = $value;
    }
}

//������
if(is_array($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL_DATE_COUNT']) && count($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL_DATE_COUNT']) == 1)
{
    foreach($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL_DATE_COUNT'] as $date => $value)
    {
        $mkTime = MakeTimeStamp($date, "YYYY.MM.DD");
        $date_prev = date('Y.m.d', $mkTime - 86400);
        $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL_DATE_COUNT'] = array($date_prev => 0);
        $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL_DATE_COUNT'][$date] = $value;
    }
}
if(is_array($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY_DATE_COUNT']) && count($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY_DATE_COUNT']) == 1)
{
    foreach($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY_DATE_COUNT'] as $date => $value)
    {
        $mkTime = MakeTimeStamp($date, "YYYY.MM.DD");
        $date_prev = date('Y.m.d', $mkTime - 86400);
        $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY_DATE_COUNT'] = array($date_prev => 0);
        $arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY_DATE_COUNT'][$date] = $value;
    }
}

// END


/***************************************************************************
            HTML �����
****************************************************************************/
$lAdmin->BeginPrologContent();?>
  
<?if($arFilter['>=DATE_SEND'] || $arFilter['<=DATE_SEND'] || $arFilter['<=DATE_SEND']):?>
    <h1>
        <?=GetMessage($module_id.'_FILLTER_STATIC')?>
        <?if($arFilter['>=DATE_SEND'] || $arFilter['<=DATE_SEND']):?>
            <br />
            <?if($arFilter['>=DATE_SEND']):?>
                <?=GetMessage($module_id.'_FROM')?> <?=str_replace(' 00:00:00','',$arFilter['>=DATE_SEND'])?>
            <?endif;?>
            <?if($arFilter['<=DATE_SEND']):?>
                <?=GetMessage($module_id.'_TO')?> <?=str_replace(' 23:59:59','',$arFilter['<=DATE_SEND'])?>
            <?endif;?>
        <?endif;?>
        
        <?if($arFilter['>=COUNT_RUN'] || $arFilter['<=COUNT_RUN']):?>
            <br />
            <?=GetMessage($module_id.'_list_title_COUNT_RUN')?>
            
            <?if($arFilter['>=COUNT_RUN']):?>
                <?=GetMessage($module_id.'_FROM')?> <?=$arFilter['>=COUNT_RUN']?>
            <?endif;?>
            <?if($arFilter['<=COUNT_RUN']):?>
                <?=GetMessage($module_id.'_TO')?> <?=$arFilter['<=COUNT_RUN']?>
            <?endif;?>
        <?endif;?>
    </h1>
<?else:?>
    <h1><?=GetMessage($module_id.'_ALL_STATIC')?></h1>
<?endif;?>

<style>
    .graph h3,
    .graph h4 {
        text-align:center;
    }
</style>

<div class="graph">
    <h3><?=GetMessage($module_id.'_STATIC_ITEMS_OPENING_LETTERS_AND_TRANTSITIONS')?></h3>
    <br><br>

    <h4><?=GetMessage($module_id.'_ACTIVE_ADMINISTRATION_OF_LETTERS')?></h4>
    <script type="text/javascript">
      google.setOnLoadCallback(drawChart_DATE_SEND_TIMELINE);
      function drawChart_DATE_SEND_TIMELINE() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', '<?=GetMessage($module_id.'_DATE')?>');
        data.addColumn('number', '<?=GetMessage($module_id.'_MESSAGES_SENT')?>');    
        data.addRows([
          <?
            $i = 1;
            foreach($arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT'] as $date=>$count):
            $dateParse =  explode('.',$date);
            ?>
            [new Date(<?=$dateParse[0]?>,<?=$dateParse[1]-1?>,<?=$dateParse[2]?>), <?=$count?>]<?if(count($arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT']) > $i):?>,<?endif;?>
            
            <?
          $i++;
          endforeach;?>            
        ]);               
        
        var options = {
          hAxis: {title: '<?=GetMessage($module_id.'_DURING_THE_PERIOD_SENT')?>: <?=$arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT_ALL']?>',  titleTextStyle: {color: '#FF0000'}}, 
          tooltip: {isHtml: true},
          legend: 'none'
        };
        
        var chart =  new google.visualization.AreaChart(document.getElementById('DATE_SEND_TIMELINE'));
        chart.draw(data, options);
      } 
      drawChart_DATE_SEND_TIMELINE();         
    </script>
    <div id="DATE_SEND_TIMELINE" style="width: 95%; height: 400px; margin:0px auto"></div>

    <h4><?=GetMessage($module_id.'_INDICATORS_OF_OPENING_LETTERS')?></h4>
    <script type="text/javascript">
        google.setOnLoadCallback(drawChart_USER_OPEN);

        function drawChart_USER_OPEN() {
            var data = google.visualization.arrayToDataTable([
                ['<?=GetMessage($module_id.'_NAME')?>', '<?=GetMessage($module_id.'_NUMBER_OF')?>'],
                ['<?=GetMessage($module_id.'_OPEN')?> (<?=$arrStaticInfo['USER_OPEN']['YES']?>)', <?=$arrStaticInfo['USER_OPEN']['YES']?>],
                ['<?=GetMessage($module_id.'_NO_OPEN')?> (<?=$arrStaticInfo['USER_OPEN']['NO']?>)',     <?=$arrStaticInfo['USER_OPEN']['NO']?>]
            ]);

            var options = {
                is3D: true
            };
            
            var chart = new google.visualization.PieChart(document.getElementById('USER_OPEN'));
            
            chart.draw(data, options);
        }
        drawChart_USER_OPEN();
    </script>
    <div id="USER_OPEN" style="width: 800px; height: 400px; margin:0px auto"></div>

    <h4><?=GetMessage($module_id.'_ACTIVITY_OPENING_LETTERS')?></h4>
    <script type="text/javascript">
      google.setOnLoadCallback(drawChart_USER_OPEN_TIMELINE);
      function drawChart_USER_OPEN_TIMELINE() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', '<?=GetMessage($module_id.'_DATE')?>');
        data.addColumn('number', '<?=GetMessage($module_id.'_OPEN')?>');
        data.addRows([
          <?
            $i = 1;
            foreach($arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT'] as $date=>$count):
            $dateParse =  explode('.',$date);
            ?>
            [new Date(<?=$dateParse[0]?>,<?=$dateParse[1]-1?>,<?=$dateParse[2]?>), <?=$count?>]<?if(count($arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT']) > $i):?>,<?endif;?>
            
            <?
          $i++;
          endforeach;?>           
          
        ]);
        
        var options = {
          hAxis: {title: '<?=GetMessage($module_id.'_DURING_THE_PERIOD_OF_OPEN_TIME')?>: <?=$arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT_ALL']?> ',  titleTextStyle: {color: '#FF0000'}}, 
          tooltip: {isHtml: true},
          legend: 'none'
        };
        
        var chart =  new google.visualization.AreaChart(document.getElementById('USER_OPEN_TIMELINE'));
        chart.draw(data, options);
      } 
      drawChart_USER_OPEN_TIMELINE();         
    </script>
    <div id="USER_OPEN_TIMELINE" style="width: 95%; height: 400px; margin:0px auto"></div>

    <h4><?=GetMessage($module_id.'_INDICATORS_OF_OPENING_LETTERS_DEPENDING_ON_THE_TIME_OF_DAY')?></h4>
    <script type="text/javascript">
      google.setOnLoadCallback(drawChart_USER_OPEN_HOUR);
      function drawChart_USER_OPEN_HOUR() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', '<?=GetMessage($module_id.'_TIME_OF_DAY')?>');
        data.addColumn('number', '<?=GetMessage($module_id.'_OPENED_THE_LETTER')?>');
        data.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
        data.addRows([
          <?
            $i = 1;
            foreach($arrStaticInfo['STATICK_HOUR_USER'] as $hour=>$count_open):?>
            ['<?=$hour?>',  <?=$count_open['USER_OPEN']?>, '<p style="margin:0px;padding:5px;font-size:14px;"><?=GetMessage($module_id.'_TIME')?> - <?=$hour?>:00 <br /> <?=GetMessage($module_id.'_OPENED_THE_LETTER')?>: <?=$count_open['USER_OPEN']?></p>']<?if(count($arrStaticInfo['STATICK_HOUR_USER']) > $i):?>,<?endif;?>
            <?
          $i++;
          endforeach;?> 
        ]);
        
        
        var options = {
          hAxis: {title: '<?=GetMessage($module_id.'_TIME_OF_DAY')?>',  titleTextStyle: {color: '#FF0000'}}, 
          tooltip: {isHtml: true},
          legend: 'none'
        };

               
        var chart = new google.visualization.AreaChart(document.getElementById('USER_OPEN_HOUR'));
        chart.draw(data, options);
      }
      drawChart_USER_OPEN_HOUR();
    </script>
    <div id="USER_OPEN_HOUR"  style="width: 95%;height:300px ; margin:0px auto"></div>

    <h4><?=GetMessage($module_id.'_OVERALL_REFERRALS_FROM_LETTERS')?></h4>
    <script type="text/javascript">
        google.setOnLoadCallback(drawChart_USER_BACK);
                  
        function drawChart_USER_BACK() {
            var data = google.visualization.arrayToDataTable([
                ['<?=GetMessage($module_id.'_NAME')?>', '<?=GetMessage($module_id.'_NUMBER_OF')?>'],
                ['<?=GetMessage($module_id.'_SEND_A_LINK')?> (<?=$arrStaticInfo['USER_BACK']['YES']?>)',     <?=$arrStaticInfo['USER_BACK']['YES']?>],
                ['<?=GetMessage($module_id.'_NOT_MOVED')?> (<?=$arrStaticInfo['USER_BACK']['NO']?>)',     <?=$arrStaticInfo['USER_BACK']['NO']?>]
            ]);
                          
            var options = {
                is3D: true
            };

            var chart = new google.visualization.PieChart(document.getElementById('USER_BACK'));

            chart.draw(data, options);
        }
        drawChart_USER_BACK(); 
    </script>
    <div id="USER_BACK" style="width: 800px; height: 400px; margin:0px auto"></div>

    <h4><?=GetMessage($module_id.'_CONVERSION_RATE_ON_THE_LINKS_OF_OPEN_LETTERS')?></h4>
    <script type="text/javascript">
        google.setOnLoadCallback(drawChart_USER_BACK_OPEN_MAIL);
                  
        function drawChart_USER_BACK_OPEN_MAIL() {
            var data = google.visualization.arrayToDataTable([
                ['<?=GetMessage($module_id.'_NAME')?>', '<?=GetMessage($module_id.'_NUMBER_OF')?>'],
                ['<?=GetMessage($module_id.'_SEND_A_LINK')?> (<?=$arrStaticInfo['USER_BACK_OPEN_MAIL']['YES']?>)',     <?=$arrStaticInfo['USER_BACK_OPEN_MAIL']['YES']?>],
                ['<?=GetMessage($module_id.'_NOT_MOVED')?> (<?=$arrStaticInfo['USER_BACK_OPEN_MAIL']['NO']?>)',     <?=$arrStaticInfo['USER_BACK_OPEN_MAIL']['NO']?>]
            ]);

            var options = {
                is3D: true
            };

            var chart = new google.visualization.PieChart(document.getElementById('USER_BACK_OPEN_MAIL'));

            chart.draw(data, options);
        }
        drawChart_USER_BACK_OPEN_MAIL(); 
    </script>
    <div id="USER_BACK_OPEN_MAIL" style="width: 800px; height: 400px; margin:0px auto"></div>

    <h4><?=GetMessage($module_id.'_ACTIVE_REFERRALS')?></h4>
    <script type="text/javascript">
      google.setOnLoadCallback(drawChart_USER_BACK_TIMELINE);
      function drawChart_USER_BACK_TIMELINE() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', '<?=GetMessage($module_id.'_DATE')?>');
        data.addColumn('number', '<?=GetMessage($module_id.'_HAVE_FOLLOWED_A_LINK')?>');
        data.addRows([
          <?
            $i = 1;
            foreach($arrStaticInfo['STATICK_USER_BACK_ELEMENT_COUNT'] as $date=>$count):
            $dateParse =  explode('.',$date);
            ?>
            [new Date(<?=$dateParse[0]?>,<?=$dateParse[1]-1?>,<?=$dateParse[2]?>), <?=$count?>]<?if(count($arrStaticInfo['STATICK_USER_BACK_ELEMENT_COUNT']) > $i):?>,<?endif;?>
            
            <?
          $i++;
          endforeach;?>           
          
        ]);
        
        var options = {
          hAxis: {title: '<?=GetMessage($module_id.'_DURING_THE_PERIOD_OF_TIME_PASSED')?>: <?=$arrStaticInfo['STATICK_USER_BACK_ELEMENT_COUNT_ALL']?> ',  titleTextStyle: {color: '#FF0000'}}, 
          tooltip: {isHtml: true},
          legend: 'none'
        };
        
        var chart =  new google.visualization.AreaChart(document.getElementById('USER_BACK_TIMELINE'));
        chart.draw(data, options);
      } 
      drawChart_USER_BACK_TIMELINE();         
    </script>
    <div id="USER_BACK_TIMELINE" style="width: 95%; height: 400px; margin:0px auto"></div>

    <h4><?=GetMessage($module_id.'_ACTIVE_REFERRALS_DEPENDING_ON_THE_TIME_OF_DAY')?></h4>
    <script type="text/javascript">
      google.setOnLoadCallback(drawChart_USER_BACK_HOUR);
      function drawChart_USER_BACK_HOUR() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', '<?=GetMessage($module_id.'_TIME_OF_DAY')?>');
        data.addColumn('number', '<?=GetMessage($module_id.'_OPENED_THE_LETTER')?>');
        data.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
        data.addRows([
          <?
            $i = 1;
            foreach($arrStaticInfo['STATICK_HOUR_USER'] as $hour=>$count_open):?>
            ['<?=$hour?>',  <?=$count_open['USER_BACK']?>, '<p style="margin:0px;padding:5px;font-size:14px;"><?=GetMessage($module_id.'_TIME')?> - <?=$hour?>:00 <br /> <?=GetMessage($module_id.'_HAVE_FOLLOWED_A_LINK')?>: <?=$count_open['USER_BACK']?></p>']<?if(count($arrStaticInfo['STATICK_HOUR_USER']) > $i):?>,<?endif;?>
            <?
          $i++;
          endforeach;?> 
        ]);
        
        var options = {
          hAxis: {title: '<?=GetMessage($module_id.'_TIME_OF_DAY')?>',  titleTextStyle: {color: '#FF0000'}}, 
          tooltip: {isHtml: true},
          legend: 'none'
        };
       
        var chart = new google.visualization.AreaChart(document.getElementById('USER_BACK_HOUR'));
        chart.draw(data, options);
      }
      drawChart_USER_BACK_HOUR();
    </script>
    <div id="USER_BACK_HOUR"  style="width: 90%; margin:0px auto"></div>

    <h4><?=GetMessage($module_id.'_TOP_10_HITS_ON_THE_SITE')?></h4>
    <script type="text/javascript">
        google.setOnLoadCallback(drawChart_USER_BACK_PAGE_START);
                  
        function drawChart_USER_BACK_PAGE_START() {
            
            var data = new google.visualization.DataTable();
            data.addColumn('string', '<?=GetMessage($module_id.'_NAME')?>');
            data.addColumn('number', '<?=GetMessage($module_id.'_NUMBER_OF')?>');
            data.addRows([
                   <?
                    //������� ��������� ������
                    $c = 1;
                    foreach($arrStaticInfo['PAGE_START'] as $k=>$v) {
                        if($c<10){
                            $arrStaticInfo['PAGE_START_TOP_10'][$k] = $v;
                        }elseif($c>=10){
                            $arrStaticInfo['PAGE_START_TOP_10'][GetMessage($module_id.'_TOP_10_MORE')] = $v+$arrStaticInfo['PAGE_START_TOP_10'][GetMessage($module_id.'_TOP_10_MORE')];
                        }
                        $c++;
                    }

                    $i = 1;
                    foreach($arrStaticInfo['PAGE_START_TOP_10'] as $link=>$count_go):?>
                    ['<?=$link?>',  <?=$count_go?>]<?if(count($arrStaticInfo['PAGE_START_TOP_10']) > $i):?>,<?else: break; endif;?>

                    <?
                    if($i==9){
                        $i = count($arrStaticInfo['PAGE_START_TOP_10']);
                    }elseif($i==10){

                    }
                  $i++;
                  endforeach;?>
            ]);
            
            
            var options = {
                is3D: true,
                tooltip: {isHtml: true,trigger: 'selection' },
            };            

            var chart = new google.visualization.PieChart(document.getElementById('USER_BACK_PAGE_START'));  
            chart.draw(data, options);
        }
        drawChart_USER_BACK_PAGE_START(); 
    </script>
    <div id="USER_BACK_PAGE_START" style="width: 90%; height: 500px; margin:0px auto"></div>

    <h4><?=GetMessage($module_id.'_NAVIGATION_LINKS_AND_THE_NUMBER_OF_TRANSITIONS')?></h4>
    <script type="text/javascript">
      google.setOnLoadCallback(drawTable_PAGE_START);

      function drawTable_PAGE_START() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', '<?=GetMessage($module_id.'_NAVIGATION_LINKS')?>');
        data.addColumn('number', '<?=GetMessage($module_id.'_NUMBER_OF_PASSAGES')?>');
        data.addRows([
        
        <?
        $i = 1;
        foreach($arrStaticInfo['PAGE_START'] as $link=>$go_count):?>
        ['<?=$link?>',  <?=$go_count?>]<?if(count($arrStaticInfo['PAGE_START']) > $i):?>,<?endif;?>
        <?
        $i++;
        endforeach;?>  
        ]);

        var table = new google.visualization.Table(document.getElementById('table_PAGE_START'));
        var formatter = new google.visualization.PatternFormat('<a target="_blank" href="{0}">{0}</a>');
        formatter.format(data, [0, 1]); // Apply formatter and set the formatted value of the first column.
        
        var options = {
            showRowNumber: true,
            page: 'enable',
            pageSize: 10,
            allowHtml: true,
            sortAscending: false,
            sortColumn: 1,
        };        
        
        table.draw(data, options);
      }
      drawTable_PAGE_START();
    </script>
    <div id="table_PAGE_START" style="width: 800px;margin:0px auto"></div>

    <h4><?=GetMessage($module_id.'_TOP_10_EMAIL_TO_DOMAIN')?></h4>
    <script type="text/javascript">
        google.setOnLoadCallback(drawChart_EMAIL_TO_DOMAIN);
                  
        function drawChart_EMAIL_TO_DOMAIN() {
            
            var data = new google.visualization.DataTable();
            data.addColumn('string', '<?=GetMessage($module_id.'_NAME')?>');
            data.addColumn('number', '<?=GetMessage($module_id.'_NUMBER_OF')?>');
            data.addRows([
                   <?
                    //������� ��������� ������
                    $c = 1;
                    foreach($arrStaticInfo['EMAIL_TO_DOMAIN'] as $k=>$v) {
                        if($c<10){
                            $arrStaticInfo['EMAIL_TO_DOMAIN_TOP_10'][$k] = $v;     
                        }elseif($c>=10){
                            $arrStaticInfo['EMAIL_TO_DOMAIN_TOP_10'][GetMessage($module_id.'_TOP_10_MORE')] = $v+$arrStaticInfo['EMAIL_TO_DOMAIN_TOP_10'][GetMessage($module_id.'_TOP_10_MORE')];          
                        } 
                        $c++;                         
                    }
                   
                    $i = 1;
                    foreach($arrStaticInfo['EMAIL_TO_DOMAIN_TOP_10'] as $link=>$count_go):?>
                    ['<?=$link?>',  <?=$count_go?>]<?if(count($arrStaticInfo['EMAIL_TO_DOMAIN_TOP_10']) > $i):?>,<?else: break; endif;?>
                    
                    <?
                    if($i==9){
                        $i = count($arrStaticInfo['EMAIL_TO_DOMAIN_TOP_10']);        
                    }elseif($i==10){
                        
                    }
                  $i++;
                  endforeach;?>  
            ]);
            
            
            var options = {
                is3D: true,
                tooltip: {isHtml: true,trigger: 'selection' },
            };            

            var chart = new google.visualization.PieChart(document.getElementById('EMAIL_TO_DOMAIN'));  
            chart.draw(data, options);
        }
        drawChart_EMAIL_TO_DOMAIN(); 
    </script>
    <div id="EMAIL_TO_DOMAIN" style="width: 90%; height: 500px; margin:0px auto"></div>

    <h4><?=GetMessage($module_id.'_NAVIGATION_DOMAIN_LINK_INFO')?></h4>
    <script type="text/javascript">
      google.setOnLoadCallback(drawTable_EMAIL_TO_DOMAIN_TABLE);

      function drawTable_EMAIL_TO_DOMAIN_TABLE() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', '<?=GetMessage($module_id.'_NAVIGATION_DOMAIN')?>');
        data.addColumn('number', '<?=GetMessage($module_id.'_NAVIGATION_DOMAIN_COUNT')?>');
        data.addRows([
        
        <?
        $i = 1;
        foreach($arrStaticInfo['EMAIL_TO_DOMAIN'] as $link=>$go_count):?>
        ['<?=$link?>',  <?=$go_count?>]<?if(count($arrStaticInfo['EMAIL_TO_DOMAIN']) > $i):?>,<?endif;?>
        <?
        $i++;
        endforeach;?>  
        ]);

        var table = new google.visualization.Table(document.getElementById('table_EMAIL_TO_DOMAIN_TABLE'));
        /*var formatter = new google.visualization.PatternFormat('<a target="_blank" href="{0}">{0}</a>');
        formatter.format(data, [0, 1]); // Apply formatter and set the formatted value of the first column.
        */
        var options = {
            showRowNumber: true,
            page: 'enable',
            pageSize: 10,
            allowHtml: true,
            sortAscending: false,
            sortColumn: 1,
        };        
        
        table.draw(data, options);
      }
      drawTable_EMAIL_TO_DOMAIN_TABLE();
    </script>
    <div id="table_EMAIL_TO_DOMAIN_TABLE" style="width: 800px;margin:0px auto"></div>

    <h4><?=GetMessage($module_id.'_TOP_10_EMAIL_TO_DOMAIN_OPEN')?></h4>
    <script type="text/javascript">
        google.setOnLoadCallback(drawChart_EMAIL_TO_DOMAIN_OPEN);
                  
        function drawChart_EMAIL_TO_DOMAIN_OPEN() {
            
            var data = new google.visualization.DataTable();
            data.addColumn('string', '<?=GetMessage($module_id.'_NAME')?>');
            data.addColumn('number', '<?=GetMessage($module_id.'_NUMBER_OF')?>');
            data.addRows([
                   <?
                    //������� ��������� ������
                    $c = 1;
                    foreach($arrStaticInfo['EMAIL_TO_DOMAIN_OPEN'] as $k=>$v) {
                        if($c<10){
                            $arrStaticInfo['EMAIL_TO_DOMAIN_OPEN_TOP_10'][$k] = $v;     
                        }elseif($c>=10){
                            $arrStaticInfo['EMAIL_TO_DOMAIN_OPEN_TOP_10'][GetMessage($module_id.'_TOP_10_MORE')] = $v+$arrStaticInfo['EMAIL_TO_DOMAIN_OPEN_TOP_10'][GetMessage($module_id.'_TOP_10_MORE')];          
                        } 
                        $c++;                         
                    }
                   
                    $i = 1;
                    foreach($arrStaticInfo['EMAIL_TO_DOMAIN_OPEN_TOP_10'] as $link=>$count_go):?>
                    ['<?=$link?>',  <?=$count_go?>]<?if(count($arrStaticInfo['EMAIL_TO_DOMAIN_OPEN_TOP_10']) > $i):?>,<?else: break; endif;?>
                    
                    <?
                    if($i==9){
                        $i = count($arrStaticInfo['EMAIL_TO_DOMAIN_OPEN_TOP_10']);        
                    }elseif($i==10){
                        
                    }
                  $i++;
                  endforeach;?>  
            ]);
            
            
            var options = {
                is3D: true,
                tooltip: {isHtml: true,trigger: 'selection' },
            };            

            var chart = new google.visualization.PieChart(document.getElementById('EMAIL_TO_DOMAIN_OPEN'));  
            chart.draw(data, options);
        }
        drawChart_EMAIL_TO_DOMAIN_OPEN(); 
    </script>
    <div id="EMAIL_TO_DOMAIN_OPEN" style="width: 90%; height: 500px; margin:0px auto"></div>
</div>

<div class="graph">
    <h3><?=GetMessage($module_id.'_STATISTICS_ON_COUPONS')?></h3>
    
    <?if(count($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL_DATE_COUNT'])>0):?>
    <br>
    <h4><?=GetMessage($module_id.'_ACTIVITY_DEPARTURE_COUPONS')?></h4>
    <script type="text/javascript">
      google.setOnLoadCallback(drawChart_COUPON_ALL_DATE_TIMELINE);
      function drawChart_COUPON_ALL_DATE_TIMELINE() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', '<?=GetMessage($module_id.'_DATE')?>');
        data.addColumn('number', '<?=GetMessage($module_id.'_COUPONS_CREATED')?>');
        data.addRows([
          <?
            $i = 1;
            foreach($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL_DATE_COUNT'] as $date=>$count):
            $dateParse =  explode('.',$date);
            ?>
            [new Date(<?=$dateParse[0]?>,<?=$dateParse[1]-1?>,<?=$dateParse[2]?>), <?=$count?>]<?if(count($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_ALL_DATE_COUNT']) > $i):?>,<?endif;?>
            
            <?
          $i++;
          endforeach;?>            
        ]);            
        
        var options = {
          hAxis: {title: '<?=GetMessage($module_id.'_TOTAL_CREATED_COUPONS_FOR_THE_PERIOD')?>: <?=$arrStaticInfo['STATICK_COUPON']['COUPON_ALL_COUNT']?>',  titleTextStyle: {color: '#FF0000'}}, 
          tooltip: {isHtml: true},
          legend: 'none'
        };
        
        var chart =  new google.visualization.AreaChart(document.getElementById('COUPON_ALL_DATE_TIMELINE'));
        chart.draw(data, options);
      } 
      drawChart_COUPON_ALL_DATE_TIMELINE();         
    </script>
    <div id="COUPON_ALL_DATE_TIMELINE" style="width: 95%; height: 400px; margin:0px auto"></div>
    <?endif;?>

    <?if(count($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_HOUR_INFO'])>0):?>
    <h4><?=GetMessage($module_id.'_INDICATORS_OF_CREATING_AND_SENDING_COUPONS_DEPENDING_ON_THE_TIME_OF_DAY')?></h4>
    <script type="text/javascript">
      google.setOnLoadCallback(drawChart_COUPON_CREATE_HOUR);
      function drawChart_COUPON_CREATE_HOUR() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', '<?=GetMessage($module_id.'_TIME_OF_DAY')?>');
        data.addColumn('number', '<?=GetMessage($module_id.'_SENT_COUPONS')?>');
        data.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
        data.addRows([
          <?
            $i = 1;
            foreach($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_HOUR_INFO'] as $hour=>$count_open):?>
            ['<?=$hour?>',  <?=$count_open['COUPON_CREATE']?>, '<p style="margin:0px;padding:5px;font-size:14px;"><?=GetMessage($module_id.'_TIME')?> - <?=$hour?>:00 <br /> <?=GetMessage($module_id.'_POSTED_BY')?>: <?=$count_open['COUPON_CREATE']?></p>']<?if(count($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_HOUR_INFO']) > $i):?>,<?endif;?>
            <?
          $i++;
          endforeach;?> 
        ]);
        
        var options = {
          hAxis: {title: '<?=GetMessage($module_id.'_TIME_OF_DAY')?>',  titleTextStyle: {color: '#FF0000'}}, 
          tooltip: {isHtml: true},
          legend: 'none'
        };
       
        var chart = new google.visualization.AreaChart(document.getElementById('COUPON_CREATE_HOUR'));
        chart.draw(data, options);
      }
      drawChart_COUPON_CREATE_HOUR();
    </script>
    <div id="COUPON_CREATE_HOUR" style="width: 95%; height:300px; margin:0px auto"></div>
    <?endif;?>

    <br>
    <h4><?=GetMessage($module_id.'_THE_UTILIZATION_OF_COUPONS')?></h4>
    <script type="text/javascript">
        google.setOnLoadCallback(drawChart_COUPON_CREATE_AND_APPLY);
                  
        function drawChart_COUPON_CREATE_AND_APPLY() {
            var data = google.visualization.arrayToDataTable([
                ['<?=GetMessage($module_id.'_NAME')?>', '<?=GetMessage($module_id.'_NUMBER_OF')?>'],
                ['<?=GetMessage($module_id.'_NOT_USED')?> (<?=$arrStaticInfo['STATICK_COUPON']['COUPON_ALL_COUNT']-$arrStaticInfo['STATICK_COUPON']['COUPON_APPLY_COUNT']?>)', <?=$arrStaticInfo['STATICK_COUPON']['COUPON_ALL_COUNT']-$arrStaticInfo['STATICK_COUPON']['COUPON_APPLY_COUNT']?>],
                ['<?=GetMessage($module_id.'_USED')?> (<?=$arrStaticInfo['STATICK_COUPON']['COUPON_APPLY_COUNT']?>)', <?=$arrStaticInfo['STATICK_COUPON']['COUPON_APPLY_COUNT']?>]
            ]);

            var options = {
                is3D: true
            };

            var chart = new google.visualization.PieChart(document.getElementById('COUPON_CREATE_AND_APPLY'));

            chart.draw(data, options);
        }
        drawChart_COUPON_CREATE_AND_APPLY(); 
    </script>
    <div id="COUPON_CREATE_AND_APPLY" style="width: 800px; height: 400px; margin:0px auto"></div>

    <h4><?=GetMessage($module_id.'_ACTIVE_USE_OF_COUPONS')?></h4>
    <script type="text/javascript">
      google.setOnLoadCallback(drawChart_COUPON_APPLY_DATE_TIMELINE);
      function drawChart_COUPON_APPLY_DATE_TIMELINE() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', '<?=GetMessage($module_id.'_DATE')?>');
        data.addColumn('number', '<?=GetMessage($module_id.'_COUPONS_USED')?>');
        data.addRows([
          <?
            $i = 1;
            foreach($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY_DATE_COUNT'] as $date=>$count):
            $dateParse =  explode('.',$date);
            ?>
            [new Date(<?=$dateParse[0]?>,<?=$dateParse[1]-1?>,<?=$dateParse[2]?>), <?=$count?>]<?if(count($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY_DATE_COUNT']) > $i):?>,<?endif;?>
            
            <?
          $i++;
          endforeach;?>            
        ]);            
        
        var options = {
          hAxis: {title: '<?=GetMessage($module_id.'_JUST_USE_THE_COUPON_FOR_THE_PERIOD')?>: <?=$arrStaticInfo['STATICK_COUPON']['COUPON_APPLY_COUNT']?>',  titleTextStyle: {color: '#FF0000'}}, 
          tooltip: {isHtml: true},
          legend: 'none'
        };
        
        var chart =  new google.visualization.AreaChart(document.getElementById('COUPON_APPLY_DATE_TIMELINE'));
        chart.draw(data, options);
      } 
      drawChart_COUPON_APPLY_DATE_TIMELINE();         
    </script>
    <div id="COUPON_APPLY_DATE_TIMELINE" style="width: 95%; height: 400px; margin:0px auto"></div>

    <?if($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_HOUR_INFO_ALL']>0):?>
    <h4><?=GetMessage($module_id.'_INDICATORS_OF_THE_USE_OF_COUPONS_DEPENDING_ON_THE_TIME_OF_DAY')?></h4>
    <script type="text/javascript">
      google.setOnLoadCallback(drawChart_COUPON_APPLY_HOUR);
      function drawChart_COUPON_APPLY_HOUR() {

        var data = new google.visualization.DataTable();
        data.addColumn('string', '<?=GetMessage($module_id.'_TIME_OF_DAY')?>');
        data.addColumn('number', '<?=GetMessage($module_id.'_SENT_COUPONS')?>');
        data.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
        data.addRows([
          <?
            $i = 1;
            foreach($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_HOUR_INFO'] as $hour=>$count_open):?>
            ['<?=$hour?>',  <?=$count_open['COUPON_APPLY']?>, '<p style="margin:0px;padding:5px;font-size:14px;"><?=GetMessage($module_id.'_TIME')?> - <?=$hour?>:00 <br /> <?=GetMessage($module_id.'_USED')?>: <?=$count_open['COUPON_APPLY']?></p>']<?if(count($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_HOUR_INFO']) > $i):?>,<?endif;?>
            <?
          $i++;
          endforeach;?> 
        ]);
        
        
        var options = {
          hAxis: {title: '<?=GetMessage($module_id.'_TIME_OF_DAY')?>',  titleTextStyle: {color: '#FF0000'}}, 
          tooltip: {isHtml: true},
          legend: 'none'
        };

               
        var chart = new google.visualization.AreaChart(document.getElementById('COUPON_APPLY_HOUR'));
        chart.draw(data, options);
      }
      drawChart_COUPON_APPLY_HOUR();
    </script>
    <div id="COUPON_APPLY_HOUR" style="width: 95%; height:300px; margin:0px auto"></div>
    <?endif;?>

    <h4><?=GetMessage($module_id.'_VALUE_OF_ORDERS_FOR_COUPONS')?></h4>
    <script type="text/javascript">
      google.setOnLoadCallback(drawChart_ORDER_CREATE_PRICE_COUPON_TIMELINE);
      function drawChart_ORDER_CREATE_PRICE_COUPON_TIMELINE() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', '<?=GetMessage($module_id.'_DATE')?>');
        data.addColumn('number', '<?=GetMessage($module_id.'_COST')?>');              
        data.addRows([
          <?$i = 1;
            foreach($arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'] as $date=>$value):
            $dateParse =  explode('.',$date);
            
            if($value['ORDER_CREATE_COUPON_PRICE']){
                $ORDER_CREATE_COUPON_PRICE = $value['ORDER_CREATE_COUPON_PRICE'];
            } else {
                $ORDER_CREATE_COUPON_PRICE = 0;    
            }             
            ?>
            [new Date(<?=$dateParse[0]?>,<?=$dateParse[1]-1?>,<?=$dateParse[2]?>),<?=$ORDER_CREATE_COUPON_PRICE?>]<?if(count($arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING']) > $i):?>,<?endif;?>
              
            <?$i++;
          endforeach;?>            
        ]);            
        
        var options = {
          hAxis: {title: '<?=GetMessage($module_id.'_TOTAL_ORDERS_IN_THE_AMOUNT_OF')?>: <?=$arrStaticInfo['STATICK_ORDER']['ORDER_PRICE_COUPON_ALL_COUNT']?>',  titleTextStyle: {color: '#FF0000'}}, 
          tooltip: {isHtml: true},
          legend: 'none'
        };
        
        var chart =  new google.visualization.AreaChart(document.getElementById('ORDER_CREATE_PRICE_COUPON_TIMELINE'));
        chart.draw(data, options);
      } 
      drawChart_ORDER_CREATE_PRICE_COUPON_TIMELINE();         
    </script>
    <div id="ORDER_CREATE_PRICE_COUPON_TIMELINE" style="width: 95%; height: 400px; margin:0px auto"></div>

    <?if(count($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY'])):?>
    <h4><?=GetMessage($module_id.'_ORDERS_THAT_HAVE_BEEN_MADE_BY_USERS_ON_THE_COUPONS')?></h4>
    <script type="text/javascript">
      google.setOnLoadCallback(drawTable_USER_ORDER_COUPON_HAVE);

      function drawTable_USER_ORDER_COUPON_HAVE() {
        var data = new google.visualization.DataTable();
        data.addColumn('number', '<?=GetMessage($module_id.'_ORDER_NUMBER')?>');
        data.addColumn('number', '<?=GetMessage($module_id.'_ORDER_PRICE')?>');
        data.addColumn('string', '<?=GetMessage($module_id.'_DATE_INSERT')?>');                
        data.addColumn('number', '<?=GetMessage($module_id.'_USER_ID')?>');
        data.addColumn('string', '<?=GetMessage($module_id.'_COUPONS')?>');       
        data.addColumn('string', '<?=GetMessage($module_id.'_MESSAGE_ID')?>');        
        data.addRows([
        
        <?
        $i = 1;
        foreach($arrStaticInfo['STATICK_ORDER']['ORDER_ELEMENT'] as $order_info):?>
        <? 
        if($arrStaticInfo['STATICK_COUPON']['COUPON_ELEMENT_APPLY'][$order_info['COUPON']]){
  
        $messageId = $arrStaticInfo['STATICK_COUPON']['COUPON_MESSAGE_ID'][$order_info['COUPON']];    
            
        ?>
        [<?=$order_info['ID']?>, <?=$order_info['PRICE']?>, '<?=$order_info['DATE_INSERT']?>', <?=$order_info['USER_ID']?>,'<?=$order_info['COUPON']?>','<?=$messageId?>']<?if(count($arrStaticInfo['STATICK_ORDER']['ORDER_ELEMENT']) > $i):?>,<?endif;?>  
        
        <?    
        }
        $i++;
        endforeach;?>  
        ]);

        var table = new google.visualization.Table(document.getElementById('table_USER_ORDER_COUPON_HAVE'));

       
        var options = {
            showRowNumber: true,
            page: 'enable',
            pageSize: 10,
            allowHtml: true,
            sortAscending: false,
            sortColumn: 1,
        };        
        
        table.draw(data, options);
      }
      drawTable_USER_ORDER_COUPON_HAVE();
    </script>
    <div id="table_USER_ORDER_COUPON_HAVE" style="width: 90%;margin:0px auto"></div>
    <?endif;?>
</div>

<div class="graph">
    <h3><?=GetMessage($module_id.'_STATISTICS_DEMANDS_OF_USERS_INVOLVED_IN_THE_DELIVERY')?></h3>
    <br>

    <h4><?=GetMessage($module_id.'_PERFORMANCE_INDICATOR_MAILING_SENT_LETTERS')?></h4>
    <script type="text/javascript">
        google.setOnLoadCallback(drawChart_ORDER_CREATE_SEND_MAILING);
                  
        function drawChart_ORDER_CREATE_SEND_MAILING() {   
            var data = google.visualization.arrayToDataTable([
                ['<?=GetMessage($module_id.'_NAME')?>', '<?=GetMessage($module_id.'_NUMBER_OF')?>'],
                ['<?=GetMessage($module_id.'_MADE_AN_ORDER')?> (<?=$arrStaticInfo['STATICK_ORDER']['ORDER_COUNT']?>)',     <?=$arrStaticInfo['STATICK_ORDER']['ORDER_COUNT']?>],
                ['<?=GetMessage($module_id.'_DID_NOT_ORDER')?> (<?=$arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT_ALL']-$arrStaticInfo['STATICK_ORDER']['ORDER_COUNT']?>)',     <?=$arrStaticInfo['STATICK_DATE_SEND_ELEMENT_COUNT_ALL']-$arrStaticInfo['STATICK_ORDER']['ORDER_COUNT']?>]
            ]);
            
            var options = {
                is3D: true
            };
            
            var chart = new google.visualization.PieChart(document.getElementById('ORDER_CREATE_SEND_MAILING'));
            
            chart.draw(data, options);
        }
        drawChart_ORDER_CREATE_SEND_MAILING();
    </script>
    <div id="ORDER_CREATE_SEND_MAILING" style="width: 800px; height: 400px; margin:0px auto"></div>
    <br>

    <br>
    <h4><?=GetMessage($module_id.'_PERFORMANCE_INDICATOR_DISTRIBUTION_OF_OPEN_LETTERS')?></h4>
    <script type="text/javascript">
        google.setOnLoadCallback(drawChart_ORDER_CREATE_OPEN_MAILING);
                  
        function drawChart_ORDER_CREATE_OPEN_MAILING() {   
            var data = google.visualization.arrayToDataTable([
                ['<?=GetMessage($module_id.'_NAME')?>', '<?=GetMessage($module_id.'_NUMBER_OF')?>'],
                ['<?=GetMessage($module_id.'_MADE_AN_ORDER')?> (<?=$arrStaticInfo['STATICK_ORDER']['ORDER_COUNT']?>)',     <?=$arrStaticInfo['STATICK_ORDER']['ORDER_COUNT']?>],
                ['<?=GetMessage($module_id.'_DID_NOT_ORDER')?> (<?=$arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT_ALL']-$arrStaticInfo['STATICK_ORDER']['ORDER_COUNT']?>)', <?=$arrStaticInfo['STATICK_USER_OPEN_ELEMENT_COUNT_ALL']-$arrStaticInfo['STATICK_ORDER']['ORDER_COUNT']?>]
            ]);
            
            var options = {
                is3D: true
            };
            
            var chart = new google.visualization.PieChart(document.getElementById('ORDER_CREATE_OPEN_MAILING'));
            
            chart.draw(data, options);
        }
        drawChart_ORDER_CREATE_OPEN_MAILING();
    </script>
    <div id="ORDER_CREATE_OPEN_MAILING" style="width: 800px; height: 400px; margin:0px auto"></div>

    <br>
    <h4><?=GetMessage($module_id.'_INDICATORS_NUMBER_OF_ITEMS_OPEN_MESSAGES_AND_CREATED_ORDERS')?></h4>
    <script type="text/javascript">
      google.setOnLoadCallback(drawChart_ORDER_DATE_CREATE_TIMELINE);
      function drawChart_ORDER_DATE_CREATE_TIMELINE() {
        var data = new google.visualization.DataTable();
        data.addColumn('date', '<?=GetMessage($module_id.'_DATE')?>');
        data.addColumn('number', '<?=GetMessage($module_id.'_MESSAGES_SENT')?>'); 
        data.addColumn('number', '<?=GetMessage($module_id.'_OPEN_POSTS')?>');   
        data.addColumn('number', '<?=GetMessage($module_id.'_THE_NUMBER_OF_ORDERS')?>');             
        data.addRows([
          <?$i = 1;
            foreach($arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'] as $date=>$value):
            $dateParse =  explode('.',$date);
            
            if($value['DATE_SEND']){
                $DATE_SEND = $value['DATE_SEND'];
            } else {
                $DATE_SEND = 0;    
            }
            
            if($value['ORDER_CREATE']){
                $ORDER_CREATE = $value['ORDER_CREATE'];
            } else {
                $ORDER_CREATE = 0;    
            }   
            
            if($value['DATE_OPEN']){
                $DATE_OPEN = $value['DATE_OPEN'];
            } else {
                $DATE_OPEN = 0;    
            }            
                     
            ?>
            [new Date(<?=$dateParse[0]?>,<?=$dateParse[1]-1?>,<?=$dateParse[2]?>),<?=$DATE_SEND?>,<?=$DATE_OPEN?>,<?=$ORDER_CREATE?>]<?if(count($arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING']) > $i):?>,<?endif;?>
              
            <?$i++;
          endforeach;?>            
        ]);            
        
        var options = {
          hAxis: {title: '<?=GetMessage($module_id.'_TOTAL_ORDERS_MADE_BY_USERS')?>: <?=$arrStaticInfo['STATICK_ORDER']['ORDER_COUNT']?>',  titleTextStyle: {color: '#FF0000'}}, 
          tooltip: {isHtml: true}
        };
        
        var chart =  new google.visualization.AreaChart(document.getElementById('ORDER_DATE_CREATE_TIMELINE'));
        chart.draw(data, options);
      } 
      drawChart_ORDER_DATE_CREATE_TIMELINE();         
    </script>
    <div id="ORDER_DATE_CREATE_TIMELINE" style="width: 95%; height: 400px; margin:0px auto"></div>

    <br>
    <h4><?=GetMessage($module_id.'_COST_ORDERS_USERS_BY_DATE')?></h4>
    <script type="text/javascript">
        google.setOnLoadCallback(drawChart_ORDER_CREATE_PRICE_TIMELINE);
        function drawChart_ORDER_CREATE_PRICE_TIMELINE() {
            var data = new google.visualization.DataTable();
            data.addColumn('date', '<?=GetMessage($module_id.'_DATE')?>');
            data.addColumn('number', '<?=GetMessage($module_id.'_COST')?>');
            data.addRows([
                <?
                $i = 1;
                foreach($arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING'] as $date=>$value):
                    $dateParse =  explode('.',$date);
                
                    if($value['ORDER_CREATE_PRICE'])
                    {
                        $ORDER_CREATE_PRICE = $value['ORDER_CREATE_PRICE'];
                    }
                    else
                    {
                        $ORDER_CREATE_PRICE = 0;
                    }
                ?>
                [new Date(<?=$dateParse[0]?>,<?=$dateParse[1]-1?>,<?=$dateParse[2]?>),<?=$ORDER_CREATE_PRICE?>]<?if(count($arrStaticInfo['STATICK_ORDER']['STATICK_ORDER_MAILING']) > $i):?>,<?endif;?>
                <?
                $i++;
                endforeach;
                ?>
            ]);

            var options = {
                hAxis: {title: '<?=GetMessage($module_id.'_TOTAL_ORDERS_IN_THE_AMOUNT_OF')?>: <?=$arrStaticInfo['STATICK_ORDER']['ORDER_PRICE_ALL_COUNT']?>',  titleTextStyle: {color: '#FF0000'}}, 
                tooltip: {isHtml: true},
                legend: 'none'
            };

            var chart =  new google.visualization.AreaChart(document.getElementById('ORDER_CREATE_PRICE_TIMELINE'));
            chart.draw(data, options);
        }
        drawChart_ORDER_CREATE_PRICE_TIMELINE();
    </script>
    <div id="ORDER_CREATE_PRICE_TIMELINE" style="width: 95%; height: 400px; margin:0px auto"></div>

    <?if($arrStaticInfo['STATICK_ORDER']['ORDER_HOUR_INFO']):?>
    <h4><?=GetMessage($module_id.'_INDICATORS_CREATE_ORDERS_FOR_THE_DAY')?></h4>
    <script type="text/javascript">
        google.setOnLoadCallback(drawChart_ORDER_CREATE_HOUR);
        function drawChart_ORDER_CREATE_HOUR() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', '<?=GetMessage($module_id.'_TIME_OF_DAY')?>');
            data.addColumn('number', '<?=GetMessage($module_id.'_CREATE_A_PURCHASE_ORDER')?>');
            data.addColumn({type: 'string', role: 'tooltip', 'p': {'html': true}});
            data.addRows([
                <?
                $i = 1;
                foreach($arrStaticInfo['STATICK_ORDER']['ORDER_HOUR_INFO'] as $hour=>$count_open):?>
                ['<?=$hour?>',  <?=$count_open['ORDER_CREATE']?>, '<p style="margin:0px;padding:5px;font-size:14px;"><?=GetMessage($module_id.'_TIME')?> - <?=$hour?>:00 <br /> <?=GetMessage($module_id.'_POWERED_ORDERS')?>: <?=$count_open['ORDER_CREATE']?></p>']<?if(count($arrStaticInfo['STATICK_ORDER']['ORDER_HOUR_INFO']) > $i):?>,<?endif;?>
                <?
                $i++;
                endforeach;
                ?>
            ]);
            
            var options = {
                hAxis: {title: '<?=GetMessage($module_id.'_TIME_OF_DAY')?>',  titleTextStyle: {color: '#FF0000'}},
                tooltip: {isHtml: true},
                legend: 'none'
            };
            
            var chart = new google.visualization.AreaChart(document.getElementById('ORDER_CREATE_HOUR'));
            chart.draw(data, options);
        }
        drawChart_ORDER_CREATE_HOUR();
    </script>
    <div id="ORDER_CREATE_HOUR" style="width: 95%; height:300px; margin:0px auto"></div>
    <?endif;?>

    <?if($arrStaticInfo['STATICK_ORDER']['ORDER_ELEMENT']):?>
    <h4><?=GetMessage($module_id.'_ORDERS_THAT_HAVE_BEEN_MADE_BY_USERS')?></h4>
    <script type="text/javascript">
      google.setOnLoadCallback(drawTable_USER_ORDER_HAVE);

      function drawTable_USER_ORDER_HAVE() {
        var data = new google.visualization.DataTable();
        data.addColumn('number', '<?=GetMessage($module_id.'_ORDER_NUMBER')?>');
        data.addColumn('number', '<?=GetMessage($module_id.'_ORDER_PRICE')?>'); 
        data.addColumn('string', '<?=GetMessage($module_id.'_DATE_INSERT')?>');         
        data.addColumn('number', '<?=GetMessage($module_id.'_USER_ID')?>');
        data.addColumn('string', '<?=GetMessage($module_id.'_MESSAGE_ID')?>');        
        data.addRows([
        
        <?
        $i = 1;
        foreach($arrStaticInfo['STATICK_ORDER']['ORDER_ELEMENT'] as $order_info):?>
        
        <?
        $messageId =  $arrStaticInfo['STATICK_ORDER']['ORDER_MESSEGE_ID_ORDER'][$order_info['ID']]; 
        ?>
        [<?=$order_info['ID']?>, <?=$order_info['PRICE']?>,  '<?=$order_info['DATE_INSERT']?>', <?=$order_info['USER_ID']?>,'<?=$messageId?>']<?if(count($arrStaticInfo['STATICK_ORDER']['ORDER_ELEMENT']) > $i):?>,<?endif;?>
        <?
        $i++;
        endforeach;?>  
        ]);

        var table = new google.visualization.Table(document.getElementById('table_USER_ORDER_HAVE'));

       
        var options = {
            showRowNumber: true,
            page: 'enable',
            pageSize: 10,
            allowHtml: true,
            sortAscending: false,
            sortColumn: 1,
        };        
        
        table.draw(data, options);
      }
      drawTable_USER_ORDER_HAVE();
    </script>
    <div id="table_USER_ORDER_HAVE" style="width: 90%;margin:0px auto"></div>
    <?endif;?>
</div>

<?
$lAdmin->EndPrologContent();


// ******************************************************************** //
//                �����                                                 //
// ******************************************************************** //

// �������������� �����
$lAdmin->CheckListMode();

// ��������� ��������� ��������
if(is_numeric($_GET['ID']))
{
    $APPLICATION->SetTitle(GetMessage($module_id . '_MESSAGE_LIST_TITLE', array("#ID#" => $arFilter['ID_EVENT'])));
}
else
{
    $APPLICATION->SetTitle(GetMessage($module_id . '_MESSAGE_LIST_TITLE_ALL'));
}

//$APPLICATION->SetTitle(GetMessage($module_id.'_PAGE_TITLE', array("#ID#" => $_GET['ID'])));

// �� ������� ��������� ���������� ������ � �����
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");


// ******************************************************************** //
//                ����� �������                                         //
// ******************************************************************** //

// �������� ������ �������
$oFilter = new CAdminFilter(
    $sTableID . "_filter",
    array(
        GetMessage($module_id . '_list_title_DATE_SEND'),
        GetMessage($module_id . '_list_title_COUNT_RUN'),
    )
);
?>

<form name="find_form" method="get" action="<?echo $APPLICATION->GetCurPage();?>">
<?$oFilter->Begin();?>
    <?if(is_numeric($_GET['ID'])):?>
    <input type="hidden" value="<?=$_GET['ID']?>" name="find_ID_EVENT"/>
    <?endif;?>
    <tr>
        <td><?=GetMessage($module_id.'_list_title_DATE_SEND')?>:</td>
        <td>
            <?echo CalendarPeriod("find_DATE_SEND_from", $find_DATE_SEND_from, "find_DATE_SEND_to", $find_DATE_SEND_to, "find_form", "Y")?>
        </td>
    </tr>
    <tr>
        <td><?=GetMessage($module_id.'_list_title_COUNT_RUN')?>:</td>
        <td>
            <script type="text/javascript">
                function find_COUNT_RUN_from_Change()
                {
                    if (document.find_form.find_COUNT_RUN_to.value.length<=0)
                    {
                        document.find_form.find_COUNT_RUN_to.value = document.find_form.find_COUNT_RUN_from.value;
                    }
                }
            </script>
            <?=GetMessage($module_id.'_FROM')?> 
            <input type="text" name="find_COUNT_RUN_from" OnChange="find_COUNT_RUN_from_Change()" value="<?echo (IntVal($find_COUNT_RUN_from)>0)?IntVal($find_COUNT_RUN_from):""?>" size="10">
            <?=GetMessage($module_id.'_TO')?> 
            <input type="text" name="find_COUNT_RUN_to" value="<?echo (IntVal($find_COUNT_RUN_to)>0)?IntVal($find_COUNT_RUN_to):""?>" size="10">
        </td>
    </tr>
<?
$oFilter->Buttons(array("table_id"=>$sTableID, "url"=>$APPLICATION->GetCurPage(),"form"=>"find_form"));
$oFilter->End();
?>
</form>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
    google.load("visualization", "1", {packages:["table"]});
    google.load("visualization", "1", {packages:["corechart"]});
    google.load('visualization', '1', {'packages':['annotatedtimeline']});
</script>

<?
$lAdmin->DisplayList();

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
?>
