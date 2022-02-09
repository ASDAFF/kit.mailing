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
    
    
    //создадим категории по параметрам
    //START
    if(in_array($_REQUEST['PARAM_1'],array('SECTION_ID','PROPERTY'))){
        //составим фильтры
        $arfillterCat = array();    
        if($_REQUEST['PARAM_1']=='SECTION_ID' && $_REQUEST["PARAM_2"]){     
              
            $arfillterCat = array(
                'PARAM_1' => $_REQUEST["PARAM_1"],
                'PARAM_2' => $_REQUEST["PARAM_2"]
            );    
                            
        }
        if($_REQUEST['PARAM_1']=='PROPERTY' && $_REQUEST["PARAM_2"] && $_REQUEST["PARAM_3"]){
            
            $arfillterCat = array(
                'PARAM_1' => $_REQUEST["PARAM_1"],
                'PARAM_2' => $_REQUEST["PARAM_2"],
                'PARAM_3' => $_REQUEST["PARAM_3"]
            );   
                      
        }      

       
        if($arfillterCat) {
            // получим категорию подписки
            $new_category_id = '';
            $sel = CSotbitMailingCategories::GetList(array(),$arfillterCat ,false,array('ID','PARAM_1','PARAM_2','PARAM_3'));
            while($res = $sel->Fetch()){
                $new_category_id = $res['ID']; 
            }  
            // создадим новую если нет существующей
            if(empty($new_category_id)) {
            
                $arrNewCategory = array(
                    'ACTIVE'=> 'Y',
                    'NAME' => $_REQUEST['CATEGORY_NAME'],
                    'DESCRIPTION' => $_REQUEST['CATEGORY_DESCRIPTION'],
                    'SUNC_USER' => $_REQUEST['CATEGORY_SUNC_USER'],
                    'SUNC_USER_GROUP' => serialize($_REQUEST['CATEGORY_SUNC_USER_GROUP']),
                    'SUNC_USER_MESSAGE' => $_REQUEST['CATEGORY_SUNC_USER_MESSAGE'], 
                    'SUNC_USER_EVENT' => $_REQUEST['CATEGORY_SUNC_USER_EVENT'], 
                    'SUNC_SUBSCRIPTION' => $_REQUEST['CATEGORY_SUNC_SUBSCRIPTION'],
                    'SUNC_SUBSCRIPTION_LIST' => serialize($_REQUEST['CATEGORY_SUNC_SUBSCRIPTION_LIST']), 
                    'SUNC_MAILCHIMP' => $_REQUEST['CATEGORY_SUNC_MAILCHIMP'],
                    'SUNC_MAILCHIMP_LIST' => $_REQUEST['CATEGORY_SUNC_MAILCHIMP_LIST'],
                    'SUNC_UNISENDER' => $_REQUEST['CATEGORY_SUNC_UNISENDER'],
                    'SUNC_UNISENDER_LIST' => $_REQUEST['CATEGORY_SUNC_UNISENDER_LIST'],
                    'PARAM_1' =>  $_REQUEST['PARAM_1'], 
                    'PARAM_2' =>  $_REQUEST['PARAM_2'], 
                    'PARAM_3' =>  $_REQUEST['PARAM_3']                                                                                                                                          
                );
                $new_category_id = CSotbitMailingCategories::Add($arrNewCategory);   
                global $CACHE_MANAGER; 
                $CACHE_MANAGER->ClearByTag('sotbit.mailing_GetCategoriesInfo');                
                                                  
            }
            if($new_category_id){
                $EMAIL_SAVE['CATEGORIES_ID'][] = $new_category_id;                 
            }
 
                        
                      
        }
         
        
        
              
    }

    

    
    
    $EMAIL_SAVE['SOURCE'] = 'COMP_FIELD';

    $ANSWER = CsotbitMailingSubTools::AddSubscribers($EMAIL_SAVE, array('USER_ADD_AUTH'=>'Y'));

    if($ANSWER == 'ERROR_SAVE'){
        echo json_encode(array('error'=>'subscribed'));
    }else{
        echo json_encode(array('success'=>'yes'));
    }

    SetCookie("MAILING_USER_MAIL_GET", 'Y', time()+300*24*60*60, '/', $_SERVER['SERVER_NAME']);    
}


?>
