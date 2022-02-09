<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

global $DB;


if(empty($arParams['CATEGORIES_ID'])) {
    return false;
}

//�������� �� ���� ������������� ��������
//START

$ARR_USER_ID_SEND = array();
$arrUser = array();
$arParametersUser['FIELDS'] = array(
    'ID',
    'LOGIN',
    'EMAIL',
    'NAME',
    'LAST_NAME',
    'SECOND_NAME',
);
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/php_files_mailing/".$arParams['MAILING_EVENT_ID']."/PHP_FILLTER_USER_PARAM_AFTER.php");
$dbResUser = CUser::GetList($byUser, $orderUser,  $fillterUser, $arParametersUser);
while($arItemsUser = $dbResUser->Fetch())
{
    $arrUser[$arItemsUser['ID']] = $arItemsUser;
}





$arrEmailSend = array();
$dbSubscribers = CKitMailingSubscribers::GetList(array(),array('CATEGORIES_ID'=>$arParams['CATEGORIES_ID']));
while($resSubscribers =$dbSubscribers->Fetch()){


    $EmailSend = array();

    if($arrUser[$resSubscribers['USER_ID']] ) {
        foreach($arrUser[$resSubscribers['USER_ID']] as $ku => $vu){
            $EmailSend['USER_'.$ku]  = $vu;
        }
        $EmailSend['PARAM_1'] = $resSubscribers['USER_ID'];
    }

    foreach($resSubscribers as $ku => $vu){
        $EmailSend['SUBSCRIBLE_'.$ku]  = $vu;
    }

    if($EmailSend['USER_ID']){
        $ARR_USER_ID_SEND[] = $EmailSend['USER_ID'];
    }

     if(!isset($EmailSend['USER_EMAIL']) && empty($EmailSend['USER_EMAIL']))
         $EmailSend['USER_EMAIL'] = $EmailSend['SUBSCRIBLE_EMAIL_TO'];

    $arrEmailSend[] = $EmailSend;

}
//END


//������������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/recommend/template_logic.php");
// END


// ������������� ������ ��� �������������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/viewed/template_logic.php");
// END


// ��������� � �������� ������
// START
include($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/kit.mailing/tools/kit.mailing.logic/mail_template/template_logic.php");
// END


?>