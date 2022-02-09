<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if(!CModule::IncludeModule("kit.mailing"))
{
    ShowError(GetMessage("SOP_MODULE_NOT_INSTALL"));
    return;
}

//������� ��� ��������� ����������
$param_date_age_code = array(); 
$paramTemplate = CComponentUtil::GetTemplateProps('kit:kit.mailing.logic', $arParams['MAILING_TEMPLATE'], "", $arParams);
foreach($paramTemplate as $kParam => $vParam)
{
    if($vParam['TYPE']=='DATE_PERIOD_AGO')
    {
        $param_date_age_code[] = $kParam;
    }
}


//������� ������ �� ���� DATE_PERIOD_AGO
foreach($param_date_age_code as $code)
{
    if(isset($arParams[$code.'_from']) && isset($arParams[$code.'_to']) && isset($arParams[$code.'_type']))
    {
        $GetDateAgoNowParam = array(
            'from' => $arParams[$code.'_from'],
            'to' => $arParams[$code.'_to'],
            'type' => $arParams[$code.'_type']
        );
        $arrDateAgoNowParam = CKitMailingHelp::GetDateAgoNow($GetDateAgoNowParam);
        $arParams[$code.'_from'] = $arrDateAgoNowParam['from'];
        $arParams[$code.'_to'] = $arrDateAgoNowParam['to'];
    }
}


//���� �������� �� ���� �������� �������� �����
$mailingInfo = CKitMailingEvent::GetByID($arParams['MAILING_EVENT_ID']);
if($mailingInfo['MAILING_WORK'] == 'N')
{
    CKitMailingEvent::Update($mailingInfo['ID'], array(
        "MAILING_WORK" => 'Y',
        "MAILING_WORK_PARAM" => serialize($arParams)
    ));
}
//���� �������� ��� ��������
elseif($mailingInfo['MAILING_RUN'] == 'Y' && !empty($mailingInfo['MAILING_WORK_PARAM']))
{
    $arParams = unserialize($mailingInfo['MAILING_WORK_PARAM']);
}

$this->IncludeComponentTemplate();
?>