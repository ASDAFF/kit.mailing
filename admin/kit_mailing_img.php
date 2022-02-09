<?define("NOT_CHECK_PERMISSIONS", true);   
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("kit.mailing");


if($_GET['MAILING_MESSAGE']) {
            
    $mailing_message = CKitMailingMessage::GetByIDInfoStatic($_GET['MAILING_MESSAGE']);
    if($mailing_message) { 
        global $DB;
        //���� �� ������������� ��
        $arrFields = array();
        if($mailing_message['STATIC_USER_OPEN'] == 'N'){
            $arrFields['STATIC_USER_OPEN'] = 'Y';    
        } 
        //������� ����                                                                                                     
        $arrFields['STATIC_USER_OPEN_DATE'] = unserialize($mailing_message['STATIC_USER_OPEN_DATE']); 
        $arrFields['STATIC_USER_OPEN_DATE'][] = Date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL", SITE_ID)));  
        $arrFields['STATIC_USER_OPEN_DATE'] = serialize($arrFields['STATIC_USER_OPEN_DATE']);
        
        CKitMailingMessage::Update($_GET['MAILING_MESSAGE'],$arrFields);

    }
}

?>