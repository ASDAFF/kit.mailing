<?define("NOT_CHECK_PERMISSIONS", true);   
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("sotbit.mailing"); 


if($_GET['MAILING_MESSAGE']) {
            
    $mailing_message = CSotbitMailingMessage::GetByIDInfoStatic($_GET['MAILING_MESSAGE']);   
    if($mailing_message) { 
        global $DB;
        //если не администратор то
        $arrFields = array();
        if($mailing_message['STATIC_USER_OPEN'] == 'N'){
            $arrFields['STATIC_USER_OPEN'] = 'Y';    
        } 
        //запишем дату                                                                                                     
        $arrFields['STATIC_USER_OPEN_DATE'] = unserialize($mailing_message['STATIC_USER_OPEN_DATE']); 
        $arrFields['STATIC_USER_OPEN_DATE'][] = Date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL", SITE_ID)));  
        $arrFields['STATIC_USER_OPEN_DATE'] = serialize($arrFields['STATIC_USER_OPEN_DATE']);
        
        CSotbitMailingMessage::Update($_GET['MAILING_MESSAGE'],$arrFields);               

    }
}

?>