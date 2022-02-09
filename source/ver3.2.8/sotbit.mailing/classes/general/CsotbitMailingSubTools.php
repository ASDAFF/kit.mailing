<?
class CsotbitMailingSubTools {  
    
    public function AddSubscribers($arFields,$SETTING) {
        global $DB; 
        $user = new CUser();
        $result = array();
        if(empty($arFields['EMAIL_TO'])){
            return 'ERROR_EMAIL';    
        }

        //������� ������ ������ ������������
        if($_COOKIE['MAILING_USER_CAME'] && empty($arFields["STATIC_PAGE_CAME"])){
            $arFields["STATIC_PAGE_CAME"] = $_COOKIE['MAILING_USER_CAME'];           
        }

                   
        
        $answer = CSotbitMailingSubscribers::checkSubscribers($arFields);           
        if(is_array($answer)){ 
            if($arFields['CATEGORIES_ID']){ 
                if(empty($SETTING['ACTION_DELETE']))
                {
                   $SETTING['ACTION_DELETE'] = 'NO_DELETE'; 
                }  
                CSotbitMailingSubscribers::SubscribersAdd(array('SUBSCRIBERS_ID'=>$answer['ID'],'CATEGORIES_ID'=>$arFields['CATEGORIES_ID']), array('ACTION_DELETE'=>$SETTING['ACTION_DELETE']));                 
            }
            return 'ERROR_SAVE';        
        }
        //���� ���� ������������ � ����, ������ � ���

        $rsUsers = $user->GetList(($by="sort"), ($order="asc"), array('EMAIL' => $arFields['EMAIL_TO']), array('FIELDS'=>array('ID','EMAIL','NAME','LAST_NAME','PERSONAL_PHONE')));
        if($arrUsers = $rsUsers->Fetch()){   
            $arFields['USER_ID'] = $arrUsers['ID'];
        }
         
        $arFields['DATE_CREATE'] = Date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL", SITE_ID)));

         
        $ID_SUBSCRIBERS = CSotbitMailingSubscribers::add($arFields,[]);
        if($ID_SUBSCRIBERS) {
        
        
            //��������� �� ���� ������� � ������� ���������
            //START
            $categoriesLi = CSotbitMailingHelp::GetCategoriesInfo();  
            if($arFields['CATEGORIES_ID']) {
                $PARAMETR_EVENT = array();
                foreach($arFields['CATEGORIES_ID'] as $CAT_ID){
                    //�������� � �������� ���
                    $categoriesItem = $categoriesLi[$CAT_ID]; 
                    $categoriesItem['SUNC_USER_GROUP'] = unserialize($categoriesItem['SUNC_USER_GROUP']); 
                    $categoriesItem['SUNC_SUBSCRIPTION_LIST'] = unserialize($categoriesItem['SUNC_SUBSCRIPTION_LIST']);
                    //���� � ��������� ���� �������� ������������
                    if($categoriesItem['SUNC_USER'] == 'Y' && empty($arFields['USER_ID'])){
                        $PARAMETR_EVENT['USER']['ADD'] = 'Y';  
                        //������� � ������
                        if(is_array($categoriesItem['SUNC_USER_GROUP'])) {
                            foreach($categoriesItem['SUNC_USER_GROUP'] as $group) {
                                if(!in_array($group, $PARAMETR_EVENT['USER']['GROUP'])){
                                    $PARAMETR_EVENT['USER']['GROUP'][] = $group;    
                                }    
                            }                         
                        }

                   
                    }    
                    //���� � �������� ���� �������� �������
                    if($categoriesItem['SUNC_USER'] == 'Y' && $categoriesItem['SUNC_USER_MESSAGE'] == 'Y' && $categoriesItem['SUNC_USER_EVENT'] && empty($arFields['USER_ID'])){
                        $PARAMETR_EVENT['USER']['EVENT_SEND'] = $categoriesItem['SUNC_USER_EVENT'];    
                    }
                    if(!empty($arFields['USER_ID'])){
                        $PARAMETR_EVENT['USER']['EVENT_SEND'] = $categoriesItem['SUNC_USER_EVENT'];
                    }
                    //��������� ���������� � ��������
                    if($categoriesItem['SUNC_SUBSCRIPTION'] == 'Y') {
                        $PARAMETR_EVENT['SUBSCRIPTION']['ADD'] = 'Y'; 
                        $PARAMETR_EVENT['SUBSCRIPTION']['RUBIC'] = array();  
                        //������� � ������
                        if(is_array($categoriesItem['SUNC_SUBSCRIPTION_LIST'])){
                            foreach($categoriesItem['SUNC_SUBSCRIPTION_LIST'] as $group) {
                                if(!in_array($group, $PARAMETR_EVENT['SUBSCRIPTION']['RUBIC'])){
                                    $PARAMETR_EVENT['SUBSCRIPTION']['RUBIC'][] = $group;    
                                }    
                            }                           
                        }
                         
                    }  
                    
                    //�������� � mailchimp.com
                    if($categoriesItem['SUNC_MAILCHIMP'] == 'Y' && $categoriesItem['SUNC_MAILCHIMP_LIST']) {
                        $PARAMETR_EVENT['MAILCHIMP']['ADD'] = 'Y';  
                        $PARAMETR_EVENT['MAILCHIMP']['LIST'][] = $categoriesItem['SUNC_MAILCHIMP_LIST']; 
                    }      
                    
                    //�������� � unisender.com
                    if($categoriesItem['SUNC_UNISENDER'] == 'Y' && $categoriesItem['SUNC_UNISENDER_LIST']) {
                        $PARAMETR_EVENT['UNISENDER']['ADD'] = 'Y'; 
                        if(empty($PARAMETR_EVENT['UNISENDER']['LIST'])) {
                            $PARAMETR_EVENT['UNISENDER']['LIST'] = $categoriesItem['SUNC_UNISENDER_LIST'];   
                        }  else {
                            $PARAMETR_EVENT['UNISENDER']['LIST'] = ",".$categoriesItem['SUNC_UNISENDER_LIST'];    
                        }
                    }                                         
                                      
                }                
            }
            //END

            //���� ������������ ��� �����������������
            if(!empty($arFields['USER_ID']) && isset($PARAMETR_EVENT['USER']['EVENT_SEND']) && !is_null($PARAMETR_EVENT['USER']['EVENT_SEND'])){
                CSotbitMailingTools::StartMailing(
                    $PARAMETR_EVENT['USER']['EVENT_SEND'],
                    array(
                        'USER_ID' => $arFields['USER_ID'],
                        'USER_PASSWORD' => ''
                    )
                );
            }
            // �������� ��������� � ������
            //START
            //��������� � �������� �������������
            if($PARAMETR_EVENT['USER']){
                if($PARAMETR_EVENT['USER']['ADD']=='Y'){

                    $USER_PASSWORD = randString(10, array("abcdefghijklnmopqrstuvwxyz","ABCDEFGHIJKLNMOPQRSTUVWXYZ","0123456789"));//���������� ������          
                 
                    $userAddFields = array(
                        'LOGIN' => $arFields['EMAIL_TO'],
                        'EMAIL' => $arFields['EMAIL_TO'] ,
                        'PASSWORD' => $USER_PASSWORD,
                        'CONFIRM_PASSWORD' => $USER_PASSWORD,
                        'LID'=> SITE_ID     
                    );
                    if(is_array($PARAMETR_EVENT['USER']['GROUP'])) {
                        $userAddFields['GROUP'] = $PARAMETR_EVENT['USER']['GROUP'];    
                    }
                    $USER_ID = $user->Add($userAddFields);
                    if($USER_ID) {
                        
                        CSotbitMailingSubscribers::Update($ID_SUBSCRIBERS, array('USER_ID'=>$USER_ID));     
                        $arFields['USER_ID'] = $USER_ID;   
                        $result['USER_ADD'] = $USER_ID;
                        

                        if(!$user->IsAuthorized() && in_array($arFields['SOURCE'],array('COMP_PANEL','COMP_POPUP_TIME','COMP_POPUP_CLICK','COMP_FIELD')) ){
                            $user->Authorize($USER_ID);    
                        } elseif(!$user->IsAuthorized() && $SETTING['USER_ADD_AUTH']=='Y'){
                            $user->Authorize($USER_ID);    
                        }                        
                        
                        if($PARAMETR_EVENT['USER']['EVENT_SEND']) {                    
                            CSotbitMailingTools::StartMailing(
                                $PARAMETR_EVENT['USER']['EVENT_SEND'],
                                array(
                                    'USER_ID' => $USER_ID,
                                    'USER_PASSWORD' => $USER_PASSWORD
                                )
                            );        
                        }   
            
                        
                    }        
                }    
            } 
            
            //��������� � ����������
            if($PARAMETR_EVENT['SUBSCRIPTION'] && CModule::IncludeModule('subscribe')){
                if($PARAMETR_EVENT['SUBSCRIPTION']['ADD']=='Y'){

                    $subscr = new CSubscription;
                    $arrSubscrFields = Array(
                        "USER_ID" => $arFields['USER_ID'],
                        "FORMAT" => "html/text",
                        "EMAIL" => $arFields['EMAIL_TO'],
                        "ACTIVE" => "Y",
                        "SEND_CONFIRM" => "N",
                        "CONFIRMED" => "Y",
                        "ALL_SITES" => "Y"
                    ); 
                    if($PARAMETR_EVENT['SUBSCRIPTION']['RUBIC']) {
                        $arrSubscrFields['RUB_ID'] = $PARAMETR_EVENT['SUBSCRIPTION']['RUBIC'];    
                    }

  
                    $idsubrscr = $subscr->Add($arrSubscrFields);                        
      

                }    
            }  
            
            
            //������� � mailchimp.com
            if($PARAMETR_EVENT['MAILCHIMP']['ADD'] && $PARAMETR_EVENT['MAILCHIMP']['LIST'] && $SETTING['UNISENDER_EXPORT']!='Y'){
                $api_key_mailchimp = COption::GetOptionString('sotbit.mailing', 'MAILCHIMP_API_KEY');
                $ApiMailchimp = new MCAPI($api_key_mailchimp);

                $listMailChimp = array();
                $listMailChimp = $ApiMailchimp->listsForEmail($arFields['EMAIL_TO']);

                
                
                
                foreach($PARAMETR_EVENT['MAILCHIMP']['LIST'] as $itemchimp) {
                    // �������� �� ������������� � ������
                                                           

                    if(!in_array($itemchimp, $listMailChimp)) {

                        
                        if($arFields['NAME']){
                            $merge_vars['FNAME'] = $arFields['NAME'];  
                        } elseif($arrUsers['NAME']){
                            $merge_vars['FNAME'] = $arrUsers['NAME'];           
                        }
                        if($arrUsers['LAST_NAME']){
                            $merge_vars['LNAME'] = $arrUsers['LAST_NAME'];           
                        }
                        if($arrUsers['PERSONAL_PHONE']){
                            $merge_vars['PHONE'] = $arrUsers['PERSONAL_PHONE'];           
                        }                                      
 
                        $ApiMailchimp = new MCAPI($api_key_mailchimp);
                        $yes = $ApiMailchimp->listSubscribe($itemchimp, $arFields['EMAIL_TO'], $merge_vars, 'html', false);
  
                        
                        unset($merge_vars);  
                    }       
                }       
            }
             
             
            //������� � unisender.com
            if($PARAMETR_EVENT['UNISENDER']['ADD'] && $PARAMETR_EVENT['UNISENDER']['LIST'] && $SETTING['UNISENDER_EXPORT']!='Y'){
                
                if($arFields['NAME']){
                    $UNI_NAME =  $arFields['NAME'];  
                } else {
                   $UNI_NAME = $arrUsers['LAST_NAME'];
                }
                
                $arrUnisender = array(
                    'list_ids' => $PARAMETR_EVENT['UNISENDER']['LIST'],
                    'fields' => array(
                        'email' =>  $arFields['EMAIL_TO'],
                        'Name' =>  $UNI_NAME,
                        'phone' =>  $arrUsers['PERSONAL_PHONE'],
                    ),
                    'tags' => 'sotbit.mailing'                    
                ); 
                $getListUniSender = CSotbitMailingHelp::QueryUniSender('subscribe',$arrUnisender);   
                           
            }                
          
            $result['ID_SUBSCRIBERS'] = $ID_SUBSCRIBERS;
            return $result;
        }  
        return false;

        

        

          
    } 
           
}
?>