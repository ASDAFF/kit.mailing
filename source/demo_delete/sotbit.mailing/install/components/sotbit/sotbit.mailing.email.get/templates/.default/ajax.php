<?define("NOT_CHECK_PERMISSIONS", true);
define("NO_KEEP_STATISTIC", true);
define("STOP_STATISTICS", true);
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");


if(!CModule::IncludeModule('sotbit.mailing')){
    return false;    
}


if($_REQUEST['getemail']=='Y' && !empty($_REQUEST['EMAIL_TO'])){
    
    $EMAIL_SAVE = array(
        "EMAIL_TO" => $_REQUEST['EMAIL_TO'], 
    );
    if($_REQUEST['CATEGORIES_ID']) {
        $EMAIL_SAVE['CATEGORIES_ID'] = $_REQUEST['CATEGORIES_ID'];    
    }
    if($_REQUEST['STATIC_PAGE_SIGNED']) {
        $EMAIL_SAVE['STATIC_PAGE_SIGNED'] = $_REQUEST['STATIC_PAGE_SIGNED'];        
    }

    if($_REQUEST['STATIC_PAGE_CAME']) {
        $EMAIL_SAVE['STATIC_PAGE_CAME'] = $_REQUEST['STATIC_PAGE_CAME'];        
    }
    
    $EMAIL_SAVE['SOURCE'] = 'COMP_PANEL';
    
    
    $ANSWER = CsotbitMailingSubTools::AddSubscribers($EMAIL_SAVE, array('USER_ADD_AUTH'=>'Y'));
    if($ANSWER == 'ERROR_SAVE'){
        echo json_encode(array('error'=>'subscribed'));
    }else{
        echo json_encode(array('success'=>'yes'));
    }
    SetCookie("MAILING_USER_MAIL_GET", 'Y', time()+300*24*60*60, '/', $_SERVER['SERVER_NAME']);    
}


?>

