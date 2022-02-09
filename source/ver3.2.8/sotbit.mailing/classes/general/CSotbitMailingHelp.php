<?
use Sotbit\Mailing\Helper\Menu;
use Bitrix\Main;
use Bitrix\Main\Mail;
use Bitrix\Main\SystemException;
use Bitrix\Main\Mail\Internal as MailInternal;

IncludeModuleLangFile(__FILE__);

/**
* Вспомогательный класс модуля sotbit.mailing
*
* Константы класса:
* <ul>
* <li><b>EVENT_ADD_MAILING</b> - Название почтового события для модуля</li>
* <li><b>MODULE_ID</b> - Идентификатор модуля</li>
* <li><b>CACHE_TIME_TOOLS</b> - Время кэширования</li>
* </ul> 
*/
class CSotbitMailingHelp
{
    CONST EVENT_ADD_MAILING = "SOTBIT_MAILING_EVENT_SEND";
    CONST MODULE_ID = "sotbit.mailing";
    CONST CACHE_TIME_TOOLS = 1800;

    /**
    * Метод включает управляемый кэш инфоблоков, если в настройках модуля sotbit.mailing установлен соответсвующий параметр.
    * 
    * @return void
    *
    */
    public function CacheConstantCheck()
    {
        if(COption::GetOptionString("sotbit.mailing", "MANAGED_CACHE_ON", "Y") == "Y")
        {
            define("BX_COMP_MANAGED_CACHE", true);
        } 
        elseif(COption::GetOptionString("sotbit.mailing", "MANAGED_CACHE_ON") == "N") 
        {
            define("BX_COMP_MANAGED_CACHE", false);    
        }        
    }

    //получим данные по рассылке
    //START
    /**
    * Метод возвращает информацию по настроенным рассылкам.
    * 
    * Метод использует кэш, если же кэш не валиден, то извлекает данные из базы и помещает их в него.
    * 
    * @return array $vars массив данных по рассылкам.
    *
    */
    public function GetMailingInfo()
    {
        CSotbitMailingHelp::CacheConstantCheck();
        
        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetMailingInfo';
        //$cache_dir = '/'.self::MODULE_ID;  
        $cache_id = self::MODULE_ID.'|GetMailingInfo|';  
        if($obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
           $vars = $obCache->GetVars();// Извлечение переменных из кэша   
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {     
           global $CACHE_MANAGER;
           $CACHE_MANAGER->StartTagCache($cache_dir);             
           $vars = array();

           $select = array(
                'ID',
                'ACTIVE',
                'NAME',
                'MODE',
                'EVENT_TYPE',
                'COUNT_RUN',
                'SITE_URL',
                'USER_AUTH',
                'EVENT_SEND_SYSTEM',
                'EVENT_SEND_SYSTEM_CODE',
                'EXCLUDE_HOUR_AGO',
                'EXCLUDE_UNSUBSCRIBED_USER',
                'EXCLUDE_UNSUBSCRIBED_USER_MORE',
                'EVENT_PARAMS'
           );
           
           $resData = CSotbitMailingEvent::GetList(array('ID'=>'ASC'),array(),false,$select);
           while($arrData = $resData->Fetch()) {
                if($arrData['EVENT_PARAMS']){
                    $EVENT_PARAMS = unserialize($arrData['EVENT_PARAMS']); 
                    foreach($EVENT_PARAMS as $k=>$v){
                        $arrData['EVENT_PARAMS_'.$k] = $v;    
                    }    
                }                
                $vars[$arrData['ID']] = $arrData;                              
           }           
           
                     
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetMailingInfo');  
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID);  
           $CACHE_MANAGER->EndTagCache();           
               
           $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.   
        }      
        return $vars;      
    }
    //END

    //получим данные по категориям подписок
    //START
    public function GetCategoriesInfo()
    {
        CSotbitMailingHelp::CacheConstantCheck();       
        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetCategoriesInfo';
        //$cache_dir = '/'.self::MODULE_ID;  
        $cache_id = self::MODULE_ID.'|GetCategoriesInfo|';  
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
           $vars = $obCache->GetVars();// Извлечение переменных из кэша   
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {     
           global $CACHE_MANAGER;
           $CACHE_MANAGER->StartTagCache($cache_dir);             
           $vars = array();

           $resData = CSotbitMailingCategories::GetList(array('ID'=>'ASC'),array(),false, array());
           while($arrData = $resData->Fetch()) {
                $vars[$arrData['ID']] = $arrData;                
           }           
           
                     
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetCategoriesInfo');  
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID);  
           $CACHE_MANAGER->EndTagCache();           
               
           $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.   
        }      
        return $vars;      
    }
    //END

    //получим шаблоны рассылки
    //START
    public function GetEventTemplate($ID_EVENT)
    {
        CSotbitMailingHelp::CacheConstantCheck();       
        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetEventTemplate_'.$ID_EVENT;
        //$cache_dir = '/'.self::MODULE_ID;  
        $cache_id = self::MODULE_ID.'|GetEventTemplate|'.$ID_EVENT;  
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
           $vars = $obCache->GetVars();// Извлечение переменных из кэша   
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {     
           global $CACHE_MANAGER;
           $CACHE_MANAGER->StartTagCache($cache_dir);             
           $vars = array();

           $resData = CSotbitMailingMessageTemplate::GetList(array('COUNT_START'=>'DESC'),array('ID_EVENT' => $ID_EVENT) ,false,array()); 
           while($arrData = $resData->Fetch()) {
                $vars[$arrData['ID']] = $arrData;                
           }          
                  
                      
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetEventTemplate_'.$ID_EVENT);  
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID);  
           $CACHE_MANAGER->EndTagCache();           
               
           $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.   
        }      
        return $vars;      
    }
    //END       

    //получим список email сообщения на которые были отправлены в определенный промежуток
    /**
    * Метод возвращает массив email адресов, на которые осуществлялась отправка сообщений в определенный промежуток времени.
    * 
    * Для своей работы модуль не использует кэш.
    * 
    * @param array $SETTING Массив настроек (передается параметр ID_EVENT)  
    * 
    * @return array|boolean Массив адресов, на которые осуществлялась отправка сообщений в определенный промежуток времени, false - в случае если не передан ID_EVENT или исключение установлено в 0 часов 
    *
    */
    public function GetEmailSendMessageTimeNoCache($SETTING=array())
    {
        if(!isset($SETTING['ID_EVENT'])){
            return false;    
        }    
        $GetMailingInfo = CSotbitMailingHelp::GetMailingInfo();    
        $resEvent = $GetMailingInfo[$SETTING['ID_EVENT']];
        
        
        if($resEvent['EXCLUDE_HOUR_AGO']==0){
            return false;                  
        }
        
        $vars = array();
        //получим даты
        $mkNow = mktime(date('H'),date('i')+20,date('s'),date('m'),date('j'),date('Y'));  
        $mkAgo = mktime(date('H')-$resEvent['EXCLUDE_HOUR_AGO'],date('i')-20,date('s'),date('m'),date('j'),date('Y'));  
        $FillterMessage = array(
            "ID_EVENT" => $resEvent['ID'],
            ">=DATE_CREATE" => date('d.m.Y H:i:s', $mkAgo),     
            "<=DATE_CREATE" => date('d.m.Y H:i:s', $mkNow)
        );


        ###############################
        if(isset($SETTING['COUNT_RUN'])){
            $FillterMessage['<=COUNT_RUN'] = $SETTING['COUNT_RUN'];
        }
        ###############################
        //режим работы по списку и текущему
        if($resEvent['EVENT_PARAMS_EXCLUDE_DAYS_HOUR_MODE']=='LIST'){
            $FillterMessage['ID_EVENT'] = array($resEvent['ID']);    
            foreach($resEvent['EVENT_PARAMS_EXCLUDE_HOUR_AGO_EVENT'] as $k=>$v){
                $FillterMessage['ID_EVENT'][] = $v;    
            }      
        }//режим работы по всем
        elseif($resEvent['EVENT_PARAMS_EXCLUDE_DAYS_HOUR_MODE']=='ALL'){ 
            unset($FillterMessage['ID_EVENT']);     
        }
            
            
        $resData = CSotbitMailingMessage::GetList(array(),$FillterMessage,false,array('ID','EMAIL_TO','DATE_SEND'));
        while($arrData = $resData->Fetch()) {
            $arrData['EMAIL_TO'] = trim($arrData['EMAIL_TO']);
            $vars[$arrData['EMAIL_TO']] = $arrData['ID'];                
        }      
          
        return $vars;                  
    }
    
    //получим список email сообщения на которые были отправлены в определенный промежуток с кешем
    //START
    public function GetEmailSendMessageTime($SETTING=array())
    {
        $ID_EVENT = $SETTING['ID_EVENT'];
        $COUNT_RUN = $SETTING['COUNT_RUN'];
        $EXCLUDE_HOUR_AGO = $SETTING['EXCLUDE_HOUR_AGO'];
        
        CSotbitMailingHelp::CacheConstantCheck();
        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetEmailSendMessageTime_'.$ID_EVENT.'_'.$COUNT_RUN;
        //$cache_dir = '/'.self::MODULE_ID;  
        $cache_id = self::MODULE_ID.'|GetEmailSendMessageTime|'.$ID_EVENT.'|'.$COUNT_RUN;  
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
           $vars = $obCache->GetVars();// Извлечение переменных из кэша   
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {     
           global $CACHE_MANAGER;
           $CACHE_MANAGER->StartTagCache($cache_dir);  
                
           $vars = array();
           $vars = CSotbitMailingHelp::GetEmailSendMessageTimeNoCache($SETTING);
                   
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetEmailSendMessageTime_'.$ID_EVENT.'_'.$COUNT_RUN);  
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID);  
           $CACHE_MANAGER->EndTagCache();           
               
           $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.   
        }    
   
          
        return $vars;      
        
    }
    //END 
         
   
    //получим инфоблок по ID
    //START
    public function GetSiteId()
    {
        CSotbitMailingHelp::CacheConstantCheck();       
        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetSiteId';
        //$cache_dir = '/'.self::MODULE_ID;  
        $cache_id = self::MODULE_ID.'|GetSiteId|';  
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
           $vars = $obCache->GetVars();// Извлечение переменных из кэша   
        }
        elseif(CModule::IncludeModule("iblock") && $obCache->StartDataCache())// Если кэш невалиден
        {     
           global $CACHE_MANAGER;
           $CACHE_MANAGER->StartTagCache($cache_dir);             
           $vars = array();

           $rsSites = CSite::GetList($by="sort", $order="desc", Array());
           while ($arS = $rsSites->Fetch())
           {
                $vars[] = $arS['LID'];  
           }              
           
           
                     
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetSiteId');  
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID);  
           $CACHE_MANAGER->EndTagCache();           
               
           $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.   
        }      
        return $vars;      
    }
    //END
   
   
    // получим адреса отписавшихся по рассылке - без кэша
    //START
    /**
    * Метод возвращает массив email адресов, которые были отписаны по рассылке, идентификатор которой был передан в метод.
    * 
    * Для своей работы модуль не использует кэш.
    * 
    * @param string $ID_EVENT Идентификатор расслки, для которой требуется получить отписавгиеся email
    * 
    * @return array Массив адресов, которые были отписаны по рассылке 
    *
    */
    public function GetUnsubscribedByMailing_NoCache($ID_EVENT=false)
    {
        $vars = array();
        $resSearch = CSotbitMailingUnsubscribed::GetList(array(), array('ID_EVENT' => $ID_EVENT, "ACTIVE" => "Y"), false, array('ID','EMAIL_TO'));
        while ($arrSearch = $resSearch->Fetch())
        {
            $arrSearch['EMAIL_TO'] = trim($arrSearch['EMAIL_TO']);
            $vars[$arrSearch['EMAIL_TO']] = $arrSearch['ID'];  
        }   
        
        return $vars;

    }
    //END

    // получим адреса отписавшихся по рассылке
    // START
    public function GetUnsubscribedByMailing($ID_EVENT=false)
    {
        CSotbitMailingHelp::CacheConstantCheck();                       
        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetUnsubscribedByMailing_'.$ID_EVENT;
        //$cache_dir = '/'.self::MODULE_ID;  
        $cache_id = self::MODULE_ID.'|GetUnsubscribedByMailing|'.$ID_EVENT;  
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
           $vars = $obCache->GetVars();// Извлечение переменных из кэша   
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {     
           global $CACHE_MANAGER;
           $CACHE_MANAGER->StartTagCache($cache_dir);             
 
           $vars = array();
           $vars = CSotbitMailingHelp::GetUnsubscribedByMailing_NoCache($ID_EVENT);            
                   
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetUnsubscribedByMailing_'.$ID_EVENT);  
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID);  
           $CACHE_MANAGER->EndTagCache();           
               
           $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.   
        }      
        return $vars;      
    }
    // END
        
           
    //получим адреса отписавшихся по всем рассылкам - без кэша
    //START
    /**
    * Метод возвращает массив email адресов, которые были отписаны по всем рассылкам.
    * 
    * Для своей работы модуль не использует кэш.
    * 
    * @return array Массив адресов, которые были отписаны по всем рассылкам 
    *
    */
    public function GetUnsubscribedAllMailing_NoCache()
    {
        $vars = array();
        $resSearch = CSotbitMailingUnsubscribed::GetList(array(), array("ACTIVE" => "Y"), false, array('ID','EMAIL_TO'));
        while ($arrSearch = $resSearch->Fetch())
        {
            $arrSearch['EMAIL_TO'] = trim($arrSearch['EMAIL_TO']);               
            $vars[$arrSearch['EMAIL_TO']] = $arrSearch['ID'];  
        }   
        
        return $vars;

    }
    //END

    // получим адреса отписавшихся всем рассылкам
    // START
    public function GetUnsubscribedAllMailing()
    {
        CSotbitMailingHelp::CacheConstantCheck();       
        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetUnsubscribedAllMailing';
        //$cache_dir = '/'.self::MODULE_ID;  
        $cache_id = self::MODULE_ID.'|GetUnsubscribedAllMailing|';  
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
           $vars = $obCache->GetVars();// Извлечение переменных из кэша   
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {     
           global $CACHE_MANAGER;
           $CACHE_MANAGER->StartTagCache($cache_dir);             

           
           $vars = array();
           $vars = CSotbitMailingHelp::GetUnsubscribedAllMailing_NoCache();           
                      
                   
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetUnsubscribedAllMailing');  
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID);  
           $CACHE_MANAGER->EndTagCache();           
               
           $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.   
        }      
        return $vars;      
    }
    // END
    
    //получим адреса письма по которым не были доставлены - без кэша
    //START
    /**
    * Метод возвращает массив email адресов, сообщения по которым не были доставлены.
    * 
    * Для своей работы модуль не использует кэш.
    * 
    * @return array Массив адресов, сообщения по которым не были доставлены 
    *
    */
    public function GetUndeliveredMailing_NoCache()
    {
        $vars = array();
        $resSearch = CSotbitMailingUndelivered::GetList(array(), array("ACTIVE" => "Y"), false, array('ID','EMAIL_TO'));
        while ($arrSearch = $resSearch->Fetch())
        {
            $arrSearch['EMAIL_TO'] = trim($arrSearch['EMAIL_TO']);               
            $vars[$arrSearch['EMAIL_TO']] = $arrSearch['ID'];  
        }   
        
        return $vars;

    }
    //END

    // получим адреса отписавшихся всем рассылкам
    // START
    public function GetUndeliveredMailing()
    {
        CSotbitMailingHelp::CacheConstantCheck();       
        $obCache = new CPHPCache();
        $cache_dir = '/'.self::MODULE_ID.'_GetUndeliveredMailing';
        //$cache_dir = '/'.self::MODULE_ID;  
        $cache_id = self::MODULE_ID.'|GetUndeliveredMailing|';  
        if( $obCache->InitCache(self::CACHE_TIME_TOOLS,$cache_id,$cache_dir))// Если кэш валиден
        {
           $vars = $obCache->GetVars();// Извлечение переменных из кэша   
        }
        elseif($obCache->StartDataCache())// Если кэш невалиден
        {     
           global $CACHE_MANAGER;
           $CACHE_MANAGER->StartTagCache($cache_dir);             

           
           $vars = array();
           $vars = CSotbitMailingHelp::GetUndeliveredMailing_NoCache();           
                      
                   
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID.'_GetUndeliveredMailing');  
           $CACHE_MANAGER->RegisterTag(self::MODULE_ID);  
           $CACHE_MANAGER->EndTagCache();           
               
           $obCache->EndDataCache($vars);// Сохраняем переменные в кэш.   
        }      
        return $vars;      
    }
    // END

    //функция для получения даты от нынешнего времени
    /**
    * Метод преобразует временной интервал в минутах, днях, часах или месяцах в две даты в полном вормате в виде "от" и "до" относительно текущей даты.
    * 
    * @param array $arFields Массив содержащий элементы "from", "to" и "type", где "type" - единица измерения интервала, а "from" и "to" - соответсвенно сам интервал
    * 
    * @return array Массив содержащий даты "от" и "до" относительно текущей.
    *
    */
    public function GetDateAgoNow($arFields)
    {
        if(!isset($arFields['from']) || !isset($arFields['to']) || !isset($arFields['type'])){
            return false;    
        }
        global $DB; 
        $result = array(); 
        //назад
        if($arFields['type']=='MIN'){
            $result['to'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i")-$arFields['from'], 0,  date("n"), date("d"), date("Y")));    
            $result['from'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i")-$arFields['to'], 0,  date("n"), date("d"), date("Y")));    
        }        
        elseif($arFields['type']=='HOURS'){
            $result['to'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H")-$arFields['from'], date("i"), 0,  date("n"), date("d"), date("Y")));    
            $result['from'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H")-$arFields['to'], date("i"), 0,  date("n"), date("d"), date("Y")));             
        }   
        elseif($arFields['type']=='DAYS'){
            $result['to'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i"), 0,  date("n"), date("d")-$arFields['from'], date("Y")));    
            $result['from'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i"), 0,  date("n"), date("d")-$arFields['to'], date("Y")));              
        }  
        elseif($arFields['type']=='MONTH'){
            $result['to'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i"), 0,  date("n")-$arFields['from'], date("d"), date("Y")));    
            $result['from'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i"), 0,  date("n")-$arFields['to'], date("d"), date("Y")));               
        }  
        //вперед
        elseif($arFields['type']=='MIN_FORWARD'){
            $result['to'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i")+$arFields['to'], 0,  date("n"), date("d"), date("Y")));    
            $result['from'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i")+$arFields['from'], 0,  date("n"), date("d"), date("Y")));    
        }        
        elseif($arFields['type']=='HOURS_FORWARD'){
            $result['to'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H")+$arFields['to'], date("i"), 0,  date("n"), date("d"), date("Y")));    
            $result['from'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H")+$arFields['from'], date("i"), 0,  date("n"), date("d"), date("Y")));             
        }   
        elseif($arFields['type']=='DAYS_FORWARD'){
            $result['to'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i"), 0,  date("n"), date("d")+$arFields['to'], date("Y")));    
            $result['from'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i"), 0,  date("n"), date("d")+$arFields['from'], date("Y")));              
        }  
        elseif($arFields['type']=='MONTH_FORWARD'){
            $result['to'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i"), 0,  date("n")+$arFields['to'], date("d"), date("Y")));    
            $result['from'] = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),  mktime(date("H"), date("i"), 0,  date("n")+$arFields['from'], date("d"), date("Y")));               
        }        
        
        
          
        
        return $result;              
                        
    }
    
   
    // проверим и создадим тип почтового события
    // START
    /**
    * Метод проверяет наличие в системе требуемого почтового события, создает его, если оно не найдено.
    * 
    * Имя почтового события берется из константы EVENT_ADD_MAILING, если событие отстутсвует,
    * то оно создается и также добавляется к нему почтовый шаблон с необходимыми переменными в качестве значений полей.
    * 
    * @param string $EVENT_TYPE необязательный параметр - имя события, по умолчанию - false 
    * 
    * @return string Название почтового события
    *
    */
    public function EventMessageCheck($EVENT_TYPE=false)
    {
        if(empty($EVENT_TYPE)){
            $EVENT_TYPE = self::EVENT_ADD_MAILING;    
        }
        
        $arFilter = array(
            "TYPE_ID" => $EVENT_TYPE,
            "LID"     => "ru"
        );
        $arET = CEventType::GetList($arFilter)->Fetch();

        if(!$arET)
        {
            
            $arrSite = array();
            $rsSites = CSite::GetList($by="sort", $order="desc", Array());
            while ($arS = $rsSites->Fetch())
            {
                $arrSite[] = $arS['LID'];  
            }            
            
            $et = new CEventType;
            $et->Add(array(
                "LID"           => "ru",
                "EVENT_NAME"    => $EVENT_TYPE,
                "NAME"          => GetMessage(self::MODULE_ID.'_EVENT_NAME'),
                "DESCRIPTION"   => ''
            ));
            $arFields = array(
                "ACTIVE" => "Y",
                "EVENT_NAME" => $EVENT_TYPE,
                "LID" => $arrSite,
                "EMAIL_FROM" => "#EMAIL_FROM#",
                "EMAIL_TO" => "#EMAIL_TO#",
                "BCC" => "",
                "SUBJECT" => "#SUBJECT#",
                "BODY_TYPE" => "html",
                "MESSAGE" => "#MESSEGE#"
            );//return;
            $mes = new CEventMessage;
            $mesID = $mes->Add($arFields);
            
            return $EVENT_TYPE;
        }  
        return $arET['EVENT_NAME'];      
    }
    // END
    
    /**
    * Метод возвращает массив идентификаторов всех сайтов, созданных в системе.
    * 
    * @return array массив идентификаторов всех сайтов.
    *
    */
    protected function getSitesArr()
    {
        $arrSite = array();
        $rsSites = CSite::GetList($by="sort", $order="desc", Array());
        while ($arS = $rsSites->Fetch())
        {
            $arrSite[] = $arS['LID'];  
        }
        return  $arrSite;
    }
    
    /**
    * Метод создает новый почтовый шаблон, с привязкой к событию, переданному в параметре $EVENT_TYPE.
    * 
    * @param string $EVENT_TYPE Тип почтового события, для которого необходимо создать шаблон
    * @param array $templateParams необязательный массив параметров почтового шаблона
    * 
    * @return string|boolean Идентификатор созданного почтового шаблона или false, в случае если передан пустой параметр
    *
    */
    public function CreateEventMessage($EVENT_TYPE, $templateParams = array())
    {
        if (empty($EVENT_TYPE)) {
            return false;
        }
        $arrSite = $this->getSitesArr();
        
        $arFields = array(
            "ACTIVE" => "Y",
            "EVENT_NAME" => $EVENT_TYPE,
            "LID" => $arrSite,
            "EMAIL_FROM" => "#EMAIL_FROM#",
            "EMAIL_TO" => "#EMAIL_TO#",
            "BCC" => '',
            "SUBJECT" => "#SUBJECT#",
            "BODY_TYPE" => "html",
            "MESSAGE" => "#MESSEGE#",
            "SITE_TEMPLATE_ID" => "",
        );
        
        if (!empty($templateParams)) {
            foreach ($templateParams as $key => $value) {
                $arFields[$key] =  $value;
            }
        }
        
        $mes = new CEventMessage;
        $mesID = $mes->Add($arFields);
        return $mesID;
    }
    
    /**
    * Метод обновляет существующий почтовый шаблон
    * 
    * @param string $eventMessageId Идентификатор почтового шаблона
    * @param array $templateParams массив параметров почтового шаблона
    * 
    * @return boolean true если изменения прошли успешно (или изменения не требовались), false если в процессе обновления произошли ошибки
    *
    */
    public function UpdateEventMessage($eventMessageId, $templateParams = array())
    {
        if (empty($eventMessageId)) {
            return false;
        }
        
        if (!empty($templateParams)) {
           $eventMessage = new CEventMessage;
           $res = $eventMessage->Update($eventMessageId, $templateParams);
           return $res;
        }
        
        return true;
    }
    
    /**
    * Метод создает в системе новый тип почтового события с указанным именем.
    * 
    * Если в системе уже имеется событие с таким именем, то новое событие генерироваться не будет
    * 
    * @param string $mailingId  - идентификатор сценария почтовой рассылки 
    * @param string $name  - название почтового события 
    * 
    * @return string Тип созданного почтового события
    *
    */
    public function CreateEventType($mailingId, $name = '')
    {
        $eventTypeName = 'SOTBIT_MAILING_EVENT_' . $mailingId;
        //существует ли такой тип события в системе?
            $arEventTypeFilter = array(
            "TYPE_ID" => $eventTypeName,
            );
            $rsET = CEventType::GetList($arEventTypeFilter);
            $arET = $rsET->Fetch();
            if (!empty($arET))
            {
                //Если такое событие уже существует в системе, то вернем его имя
                return  $eventTypeName;
            }
            
          //Создаем тип почтового события с новым именем
            $et = new CEventType;
            $et->Add(array(
                 "LID"           => "ru",
                 "EVENT_NAME"    => $eventTypeName,
                 "NAME"          => $name,
                 "DESCRIPTION"   => '',
            ));
            return  $eventTypeName;            
    }
    
    /**
    * Метод компилирует сообщение в сисетме bitrix и возвращает его содержимое.
    * 
    * @param string $event  - тип почтового события 
    * @param array $arFields  - массив полей для подстановки в шаблон 
    * @param string $message_id  - идентификатор почтового шаблона 
    * 
    * @return string Текст скомпилированного сообщения
    *
    */
    public function CompileMessageText($event, $arFields, $message_id)
    {
        $lid = $this->getSitesArr();
        $Duplicate = "N";
        $files = array();
        $languageId = '';
        
        if(!is_array($arFields))
        {
            $arFields = array();
        }

        $arEvent = array(
            "EVENT_NAME" => $event,
            "C_FIELDS" => $arFields,
            "LID" => (is_array($lid)? implode(",", $lid) : $lid),
            "DUPLICATE" => ($Duplicate != "N"? "Y" : "N"),
            "MESSAGE_ID" => (intval($message_id) > 0? intval($message_id): ""),
            "DATE_INSERT" => GetTime(time(), "FULL"),
            "FILE" => $files,
            "LANGUAGE_ID" => ($languageId == ''? LANGUAGE_ID : $languageId),
            "ID" => "0",
        );

        //Подготовка данных для компиляции письма
        if(!isset($arEvent['FIELDS']) && isset($arEvent['C_FIELDS']))
            $arEvent['FIELDS'] = $arEvent['C_FIELDS'];

        $arSites = explode(",", $arEvent["LID"]);

        $charset = false;
        $serverName = null;
        $siteDb = Main\SiteTable::getList(array(
            'select'=>array('SERVER_NAME', 'CULTURE_CHARSET'=>'CULTURE.CHARSET'),
            'filter' => array('LID' => $arSites)
        ));
        if($arSiteDb = $siteDb->fetch())
        {
            $charset = $arSiteDb['CULTURE_CHARSET'];
            $serverName = $arSiteDb['SERVER_NAME'];
        }

        // get filter for list of message templates
        $arEventMessageFilter = array();
        $MESSAGE_ID = intval($arEvent["MESSAGE_ID"]);
        if($MESSAGE_ID > 0)
        {
            $eventMessageDb = MailInternal\EventMessageTable::getById($MESSAGE_ID);
            if($eventMessageDb->Fetch())
            {
                $arEventMessageFilter['ID'] = $MESSAGE_ID;
                $arEventMessageFilter['ACTIVE'] = 'Y';
            }
        }
        if(count($arEventMessageFilter)==0)
        {
            $arEventMessageFilter = array(
                'ACTIVE' => 'Y',
                'EVENT_NAME' => $arEvent["EVENT_NAME"],
                'EVENT_MESSAGE_SITE.SITE_ID' => $arSites,
            );
        }

        // get list of message templates of event
        $messageDb = MailInternal\EventMessageTable::getList(array(
            'select' => array('ID'),
            'filter' => $arEventMessageFilter,
            'group' => array('ID')
        ));

        while($arMessage = $messageDb->fetch())
        {
            $eventMessage = MailInternal\EventMessageTable::getRowById($arMessage['ID']);
            $eventMessage['FILES'] = array();
            $attachmentDb = MailInternal\EventMessageAttachmentTable::getList(array(
                'select' => array('FILE_ID'),
                'filter' => array('EVENT_MESSAGE_ID' => $arMessage['ID']),
            ));
            while($arAttachmentDb = $attachmentDb->fetch())
            {
                $eventMessage['FILE'][] = $arAttachmentDb['FILE_ID'];
            }


            $arFields = $arEvent['FIELDS'];
            
            $arMessageParams = array(
                'EVENT' => $arEvent,
                'FIELDS' => $arFields,
                'MESSAGE' => $eventMessage,
                'SITE' => $arSites,
                'CHARSET' => $charset,
            );
            //Компиляция сообщения
            $message = Mail\EventMessageCompiler::createInstance($arMessageParams);
            $message->compile();
            //Возвращаем текст сообщения
            return $message->getMailBody();
        }
    }
    
    /**
    * Метод проверяет наличие в системе требуемого почтового события и наличие почтового шаблона для него, создает их, если не обнаружены.
    * 
    * Ткакже метод вызывает $this->EventMessageCheck() чтобы проверить наличие базового типа почтового события.
    * 
    * @param string $EVENT_TYPE  - имя события 
    * @param string $MAILING_NAME  - название сценария почтовой рассылки 
    * 
    * @return string|boolean Идентификатор почтового шаблона, привязанного к событию или false, если не передан параметр $EVENT_TYPE
    *
    */
    public function EventTemplateCheck($EVENT_TYPE, $MAILING_NAME = '')
    {
        //Проверим и создадим базовый тип почтового события и почтовых шаблон для отправки всех сообщений модуля
        $this->EventMessageCheck();
        
        if (empty($EVENT_TYPE)) {
            return false;
        }
        //Существует ли данный тип почтового события в системе?
        $arEventTypeFilter = array(
            "TYPE_ID" => $EVENT_TYPE,
            "LID"     => "ru",
        );
        
        $arET = CEventType::GetList($arEventTypeFilter)->Fetch();
        
        //Если тип существует, проверяем привязан ли к нему почтовый шаблон
        if ($arET) {
            $arEventMessageFilter = Array(
                "TYPE_ID" => $EVENT_TYPE,
            );
            
        $rsEventMessage = CEventMessage::GetList($by="id", $order="asc", $arEventMessageFilter);
        $arEventMessage = $rsEventMessage->Fetch();
        //Если шаблон существует, возвращаем его id
            if ($arEventMessage) {
                return $arEventMessage['ID'];
            }
            else {
                //Иначе создаем почтовый шаблон для данного типа события
                $mesId = $this->CreateEventMessage($EVENT_TYPE);
                return $mesId; 
            }
        }
        else
        {
            //Если тип не существует, создадим его и привяжем к нему почтовый шаблон
            $et = new CEventType;
            
            $id = $et->Add(array(
                "LID"           => "ru",
                "EVENT_NAME"    => $EVENT_TYPE,
                "NAME"          => $MAILING_NAME,
                "DESCRIPTION"   => ''
            ));
            //Создание шаблона
            $mesId = $this->CreateEventMessage($EVENT_TYPE);
            return $mesId;
        }
    }
    
    /**
    * Метод производит замену ссылок в тексте почтового шаблона.
    * 
    * @param string $messageText  - текст сообщения в котором требуется произвести замену ссылок 
    * @param string $siteUrl  - URL адрес, который будет использован в ссылках 
    * 
    * @return string|boolean Текст сообщения с замененными ссылками или false в случае невенрных входных параметров
    *
    */
    public function ReplaceTemplateLinks($messageText, $siteUrl = '')
    {
        if(empty($messageText)) {
            return false;
        }
        
        //соберем ссылки с текста
        $str = $messageText;
        $arr_link = array();
        $reg = '#href=([\'"]?)((?(?<![\'"])[^>\s]+|.+?(?=\1)))\1#si';
        if(preg_match_all($reg, $str, $find)) {
            //удалим лишнее из массива ссылок
            foreach($find['2'] as $k=>$v){

                if(strpos($v,'MAILING_MESSAGE')){
                    unset($find['2'][$k]);
                    unset($find['1'][$k]);    
                    unset($find['0'][$k]);        
                }    
                if(strpos($v,'@')){
                    unset($find['2'][$k]);  
                    unset($find['1'][$k]);    
                    unset($find['0'][$k]);      
                }
                if($v=="#"){
                    unset($find['2'][$k]); 
                    unset($find['1'][$k]);    
                    unset($find['0'][$k]);       
                }                                    
            }
            $arr_link = $find['2']; 
        }      
        
        
        //получим название домена
        $parse_url_mailing =  parse_url($siteUrl);
        //составим ссылку на замену  
        $arr_link_need = array();
        foreach($arr_link as $k=>$v){    

            $parse_url =  parse_url($v);
            if($parse_url['host']!=$parse_url_mailing['host']){
                $arr_link_need[$k] = 'href='.$find[1][$k].$siteUrl.'/?MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_#MAILING_EVENT_ID#&MAILING_FILE_REDURECT='.$v.$find[1][$k];        
            } 
            else {
                if(strpos($v,'?')){
                    $arr_link_need[$k] = 'href='.$find[1][$k].$v.'&MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_#MAILING_EVENT_ID#'.$find[1][$k];    
                } else {
                    $arr_link_need[$k] = 'href='.$find[1][$k].$v.'?MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=sotbit_mailing_#MAILING_EVENT_ID#'.$find[1][$k];                   
                }             
            } 
            
            
        }
        //заменим ссылки
        foreach($find[0] as $k=>$v){
            $messageText = str_replace($v, $arr_link_need[$k], $messageText);           
        }
        
        return $messageText;
    }
    
    
    //функция снимает лишние сообщения из массива
    //START
    /**
    * Метод исключает дублирование писем, исключает из рассылки отписавшиеся email, осуществляет пакетную выгрузку.
    * 
    * - метод содержит блок по исключению писам по времени дублирования в настройках сценария
    * - блок проверки на отписанные адреса
    * - проверка на адреса в списке недоставленых
    * - блок пакетной выгрузки
    *    - удаляет данные по которым отписались
    *    - удаляет данные по которым уже была отправка
    *    - возваращает модифицированный массив $arrEmailSend
    * 
    * @param array $SETTING Массив содержащий элементы arParams - параметры рассылки и $arrEmailSend - массив сообщений для отправки 
    * 
    * @return array Модифицированный массив сообщений для отправкиы
    *
    */    
    public function MessageCheck($SETTING)
    {
        $arParams = $SETTING['arParams'];
        $arrEmailSend = $SETTING['arrEmailSend'];     
        $COUNT_ALL = count($arrEmailSend); 
                           
           
        if(empty($arParams["MAILING_EVENT_ID"]) && empty($arParams['MAILING_COUNT_RUN'])){
             $arrEmailSend = array();  
             return $arrEmailSend;                
        }
        
        $arrProgress = CSotbitMailingHelp::ProgressFileGetArray($arParams["MAILING_EVENT_ID"], $arParams['MAILING_COUNT_RUN']);   
        $arrProgress['COUNT_ALL'] = $COUNT_ALL;
       
        // блок по исключению писем из рассылки
        // START 
            //по времени дублирования в настройках сценария
            if($arParams['MAILING_EXCLUDE_HOUR_AGO'] != 0) { 
                $setting_HourAgoEmail = array(
                    'ID_EVENT'=> $arParams['MAILING_EVENT_ID'],
                    'COUNT_RUN'=> $arParams['MAILING_COUNT_RUN']   ###############################
                );
                $DubleThisMailing = array();
                $HourAgoEmail = CSotbitMailingHelp::GetEmailSendMessageTimeNoCache($setting_HourAgoEmail);   
                $arrProgress_EMAIL_TO_EXCLUDE_HOUR_AGO = 0;
                $EMAIL_TO_EXCLUDE_HOUR_AGO_INFO = '';
                foreach($arrEmailSend as $k => $ItemEmailSend) { 
                    $EmailSend = array();
                    $EmailSend['EMAIL_TO'] = CSotbitMailingHelp::ReplaceVariables($arParams["EMAIL_TO"] , $ItemEmailSend);  
                    $EmailSend['EMAIL_TO'] = trim($EmailSend['EMAIL_TO']);
                    if($HourAgoEmail[$EmailSend['EMAIL_TO']]){
                        $EMAIL_TO_EXCLUDE_HOUR_AGO_INFO .=  $EmailSend['EMAIL_TO']."\n";
                        $arrProgress_EMAIL_TO_EXCLUDE_HOUR_AGO++;
                        //unset($arrEmailSend[$k]); 
                        $arrEmailSend[$k]['isContinue'] = 'Y';  
                    } elseif($DubleThisMailing[$EmailSend['EMAIL_TO']]) {
                        $EMAIL_TO_EXCLUDE_HOUR_AGO_INFO .=  $EmailSend['EMAIL_TO']."\n";
                        $arrProgress_EMAIL_TO_EXCLUDE_HOUR_AGO++;
                        //unset($arrEmailSend[$k]); 
                        $arrEmailSend[$k]['isContinue'] = 'Y';                          
                    }
                    $DubleThisMailing[$EmailSend['EMAIL_TO']] = $EmailSend['EMAIL_TO'];

                }  
                
                if($EMAIL_TO_EXCLUDE_HOUR_AGO_INFO){
                    $arrProgress_more['EMAIL_TO_EXCLUDE_HOUR_AGO_INFO'] = $EMAIL_TO_EXCLUDE_HOUR_AGO_INFO;                    
                }
                $arrProgress['EMAIL_TO_EXCLUDE_HOUR_AGO'] =  $arrProgress_EMAIL_TO_EXCLUDE_HOUR_AGO;        
            }
        //END   
 
 
        //проведем проверку на отписанные адреса вернем если адрес есть в списке
        //START   
        if($arParams['MAILING_EXCLUDE_UNSUBSCRIBED_USER'] == 'ALL') {
            $UnsubscribeEmail = CSotbitMailingHelp::GetUnsubscribedAllMailing_NoCache();  
        } 
        elseif($arParams['MAILING_EXCLUDE_UNSUBSCRIBED_USER'] == 'THIS') {
            $UnsubscribeEmail = CSotbitMailingHelp::GetUnsubscribedByMailing_NoCache($arParams["MAILING_EVENT_ID"]);  
            //добавим адреса на отписку 
            foreach($arParams['MAILING_EXCLUDE_UNSUBSCRIBED_USER_MORE'] as $event_id) {
                $UnsubscribeEmailMore = CSotbitMailingHelp::GetUnsubscribedByMailing_NoCache($event_id);        
                $UnsubscribeEmail = array_merge($UnsubscribeEmail,$UnsubscribeEmailMore); 
            }
                 
        }
        //почистим почтовые адреса
        $arrProgress_EMAIL_TO_EXCLUDE_UNSUBSCRIBED = 0;
        $EMAIL_TO_EXCLUDE_UNSUBSCRIBED_INFO = '';
        foreach($arrEmailSend as $k => $ItemEmailSend) { 
            $EmailSend = array();
            $EmailSend['EMAIL_TO'] = CSotbitMailingHelp::ReplaceVariables($arParams["EMAIL_TO"] , $ItemEmailSend);  
            $EmailSend['EMAIL_TO'] = trim($EmailSend['EMAIL_TO']);
            if($UnsubscribeEmail[$EmailSend['EMAIL_TO']]){
                $EMAIL_TO_EXCLUDE_UNSUBSCRIBED_INFO .=  $EmailSend['EMAIL_TO']."\n";
                $arrProgress_EMAIL_TO_EXCLUDE_UNSUBSCRIBED++;
                //unset($arrEmailSend[$k]);   
                $arrEmailSend[$k]['isContinue'] = 'Y'; 
            }
        }  
        
        if($EMAIL_TO_EXCLUDE_UNSUBSCRIBED_INFO){
            $arrProgress_more['EMAIL_TO_EXCLUDE_UNSUBSCRIBED_INFO'] = $EMAIL_TO_EXCLUDE_UNSUBSCRIBED_INFO;            
        }
        $arrProgress['EMAIL_TO_EXCLUDE_UNSUBSCRIBED'] =  $arrProgress_EMAIL_TO_EXCLUDE_UNSUBSCRIBED;                   
        //END         
        
        
        //проведем проверку на отписанные адреса вернем если адрес есть в списке
        //START   
        if($arParams['MAILING_EXCLUDE_UNSUBSCRIBED_USER'] == 'ALL') {
            $UnsubscribeEmail = CSotbitMailingHelp::GetUnsubscribedAllMailing_NoCache();  
        } 
        elseif($arParams['MAILING_EXCLUDE_UNSUBSCRIBED_USER'] == 'THIS') {
            $UnsubscribeEmail = CSotbitMailingHelp::GetUnsubscribedByMailing_NoCache($arParams["MAILING_EVENT_ID"]);  
            //добавим адреса на отписку 
            foreach($arParams['MAILING_EXCLUDE_UNSUBSCRIBED_USER_MORE'] as $event_id) {
                $UnsubscribeEmailMore = CSotbitMailingHelp::GetUnsubscribedByMailing_NoCache($event_id);        
                $UnsubscribeEmail = array_merge($UnsubscribeEmail,$UnsubscribeEmailMore); 
            }
                 
        }
        //почистим почтовые адреса
        $arrProgress_EMAIL_TO_EXCLUDE_UNSUBSCRIBED = 0;
        $EMAIL_TO_EXCLUDE_UNSUBSCRIBED_INFO = '';
        foreach($arrEmailSend as $k => $ItemEmailSend) { 
            $EmailSend = array();
            $EmailSend['EMAIL_TO'] = CSotbitMailingHelp::ReplaceVariables($arParams["EMAIL_TO"] , $ItemEmailSend);  
            $EmailSend['EMAIL_TO'] = trim($EmailSend['EMAIL_TO']);
            if($UnsubscribeEmail[$EmailSend['EMAIL_TO']]){
                $EMAIL_TO_EXCLUDE_UNSUBSCRIBED_INFO .=  $EmailSend['EMAIL_TO']."\n";
                $arrProgress_EMAIL_TO_EXCLUDE_UNSUBSCRIBED++;
                //unset($arrEmailSend[$k]);   
                $arrEmailSend[$k]['isContinue'] = 'Y'; 
            }
        }  
        
        if($EMAIL_TO_EXCLUDE_UNSUBSCRIBED_INFO){
            $arrProgress_more['EMAIL_TO_EXCLUDE_UNSUBSCRIBED_INFO'] = $EMAIL_TO_EXCLUDE_UNSUBSCRIBED_INFO;            
        }
        $arrProgress['EMAIL_TO_EXCLUDE_UNSUBSCRIBED'] =  $arrProgress_EMAIL_TO_EXCLUDE_UNSUBSCRIBED;                   
        //END
        
        

        //проведем проверку на адреса в списке недоставленых
        //START   
        $UndeliveredEmail = CSotbitMailingHelp::GetUndeliveredMailing_NoCache();  
       
        //почистим почтовые адреса
        $arrProgress_EMAIL_TO_EXCLUDE_UNDELIVERED = 0;
        $EMAIL_TO_EXCLUDE_UNDELIVERED_INFO = '';
        foreach($arrEmailSend as $k => $ItemEmailSend) { 
            $EmailSend = array();
            $EmailSend['EMAIL_TO'] = CSotbitMailingHelp::ReplaceVariables($arParams["EMAIL_TO"] , $ItemEmailSend);  
            $EmailSend['EMAIL_TO'] = trim($EmailSend['EMAIL_TO']);
            if($UndeliveredEmail[$EmailSend['EMAIL_TO']]){
                $EMAIL_TO_EXCLUDE_UNDELIVERED_INFO .=  $EmailSend['EMAIL_TO']."\n";
                $arrProgress_EMAIL_TO_EXCLUDE_UNDELIVERED++;
                //unset($arrEmailSend[$k]);   
                $arrEmailSend[$k]['isContinue'] = 'Y'; 
            }
        }  
        
        if($EMAIL_TO_EXCLUDE_UNDELIVERED_INFO){
            $arrProgress_more['EMAIL_TO_EXCLUDE_UNDELIVERED_INFO'] = $EMAIL_TO_EXCLUDE_UNDELIVERED_INFO;            
        }
        $arrProgress['EMAIL_TO_EXCLUDE_UNDELIVERED'] =  $arrProgress_EMAIL_TO_EXCLUDE_UNDELIVERED;                   
        //END
        
        
        //пакетная выгрузка
        //START   
            
        //удалим те данные по которым отписались
        foreach($arrEmailSend as $k => $ItemEmailSend){ 
            if($arrEmailSend[$k]['isContinue']=='Y') {
                unset($arrEmailSend[$k]); 
                $arrProgress['COUNT_ALL'] = $arrProgress['COUNT_ALL']-1;   
            }    
        }
                    
            
        //уберем данные по которым уже отправили     
        $c=0;
        foreach($arrEmailSend as $k => $ItemEmailSend){
            $c++; 
            if($c>=$arParams['MAILING_MAILING_WORK_COUNT']){
                break;    
            } else{
                unset($arrEmailSend[$k]);
            }           
        } 
        
         
        //END          
        
        if($arrProgress['COUNT_ALL']==0){
            $arrProgress['COUNT_ALL'] = 0;
            $arrProgress['COUNT_NOW'] = 0;
            $arrProgress['COUNT_SEND'] = 0;   
        }        
        
        
        CSotbitMailingHelp::ProgressFile($arParams["MAILING_EVENT_ID"], $arParams['MAILING_COUNT_RUN'], $arrProgress, $arrProgress_more);     
                
        
        return $arrEmailSend;  
        
    }
    //END
    
    //Функция для запроса в UniSender
    //START
    public function QueryUniSender($Metod=false, $Fields=array())
    {
        
       // выставим ключ
       $data = array(
          'api_key' => COption::GetOptionString('sotbit.mailing', 'UNSENDER_API_KEY'),
          'format' => 'json',
          'double_optin' => '1'
       );
       
       // объединим массив
       if(is_array($Fields)) {
           $data = array_merge($data,$Fields);           
       }  
       

       // сделаем запрос 
       $postdata = http_build_query($data);   
       $Response = QueryGetData(
            'api.unisender.com',
            80,
            '/ru/api/'.$Metod.'?',
            $postdata,
            $error_number,
            $error_text ,
            'POST'
        );       
        $arrResponse = json_decode($Response,true);    
              
        return $arrResponse;    
    }      
    //END
    
    
    //экспорт контактов из Uniseder
    //START
    public function UniSenderExportContact($CATEGORIES_ID, $LIST_UNISENDER)
    {
        
        $arrayUser = array();
        $iteration = 500;
        $d = 0;
        //получим массив
        while($d <= 1000000)
        {
            
             $arrUniseder = array(
                'list_id' => $LIST_UNISENDER,
                'limit' => $iteration,
                'offset' => $d
            );
            $ExportList = CSotbitMailingHelp::QueryUniSender('exportContacts',$arrUniseder);    
            foreach($ExportList['result']['data'] as $item) {
                
                $arrayUser[] = array(
                    'EMAIL_TO' => $item[0],
                    'CATEGORIES_ID' => array($CATEGORIES_ID)
                );   
                $d++;         
            } 
            if(count($ExportList['result']['data']) < $iteration) {
                break;    
            }
                   
        }
        
        $count=0;
        foreach($arrayUser as $exportInfo) {
            
            $exportInfo['SOURCE'] = 'UNISENDER_EXPORT';
                     
            $ANSWER = CsotbitMailingSubTools::AddSubscribers($exportInfo, array('UNISENDER_EXPORT'=>'Y'));
            if(is_numeric($ANSWER['ID_SUBSCRIBERS'])) {
                $count++;    
            }    
        }
         
        $result = array(
            'COUNT' => $count,
            'STATUS' => 'OK'
        ); 
        return $result;     
           
    }      
    //END    
    
    /**
    * Метод создает json файл в директории /bitrix/tmp/ для сохранения прогресса запущенной рассылки.
    * 
    * Также метод добавляет в созданный файл информацию, которую полоучает из входных параметров
    * 
    * @param string $EVENT_ID Идентификатор рассылки, для которой запрашивается текущее состояние
    *  
    * @param string $EVENT_COUNT_RUN Порядковый номер запуска рассылки
    *   
    * @param array $SETTING Массив параметров, которые требуется сохранить в файле
    * 
    * @param array $MORE_INFO Массив дополнительной информации, которую требуется сохранить в файле
    * 
    * @return void|false false возвращается в случае, если не переданы идентификатор рассылки и параметр $EVENT_COUNT_RUN
    *
    */
    public function ProgressFile($EVENT_ID=false, $EVENT_COUNT_RUN=0,$SETTING=array(), $MORE_INFO=array())
    {
        $file_path =  $_SERVER["DOCUMENT_ROOT"]."/bitrix/tmp/sotbit_mailing_progress_".$EVENT_ID."_".$EVENT_COUNT_RUN.".json";
        // создадим папку
        $file_path_dir = $_SERVER["DOCUMENT_ROOT"]."/bitrix/tmp/";
        if(!file_exists($file_path_dir)) {
            mkdir($file_path_dir, 0777, true);
        }
        
        if(!$EVENT_ID && !$EVENT_COUNT_RUN) {
            return false;    
        }
        $json = json_encode($SETTING);
        
        $f = fopen($file_path, "w");
        fwrite($f, $json); 
        // Закрыть текстовый файл
        fclose($f);     
        
        
        //добавим в файл информацию
        //START
        if($MORE_INFO['EMAIL_TO_SEND_INFO']){
       
            $file = $_SERVER["DOCUMENT_ROOT"]."/bitrix/tmp/sotbit_mailing_progress_".$EVENT_ID."_".$EVENT_COUNT_RUN."_EMAIL_TO_SEND.txt";
            $current = file_get_contents($file);
            $current .= $MORE_INFO['EMAIL_TO_SEND_INFO']."\n";
            // Пишем содержимое обратно в файл
            file_put_contents($file, $current);          
        }
        
        if($MORE_INFO['EMAIL_TO_EXCLUDE_HOUR_AGO_INFO']){
       
            $file = $_SERVER["DOCUMENT_ROOT"]."/bitrix/tmp/sotbit_mailing_progress_".$EVENT_ID."_".$EVENT_COUNT_RUN."_EMAIL_TO_EXCLUDE_HOUR_AGO.txt";
            $current = file_get_contents($file);
            $current .= $MORE_INFO['EMAIL_TO_EXCLUDE_HOUR_AGO_INFO']."\n";
            // Пишем содержимое обратно в файл
            file_put_contents($file, $current);          
        }  
        
        if($MORE_INFO['EMAIL_TO_EXCLUDE_UNSUBSCRIBED_INFO']){
       
            $file = $_SERVER["DOCUMENT_ROOT"]."/bitrix/tmp/sotbit_mailing_progress_".$EVENT_ID."_".$EVENT_COUNT_RUN."_EMAIL_TO_EXCLUDE_UNSUBSCRIBED.txt";
            $current = file_get_contents($file);
            $current .= $MORE_INFO['EMAIL_TO_EXCLUDE_UNSUBSCRIBED_INFO']."\n";
            // Пишем содержимое обратно в файл
            file_put_contents($file, $current);          
        }  
          
        if($MORE_INFO['EMAIL_TO_EXCLUDE_UNDELIVERED_INFO']){
       
            $file = $_SERVER["DOCUMENT_ROOT"]."/bitrix/tmp/sotbit_mailing_progress_EMAIL_TO_EXCLUDE_UNDELIVERED.txt";
            $current = file_get_contents($file);
            $current .= $MORE_INFO['EMAIL_TO_EXCLUDE_UNDELIVERED_INFO']."\n";
            // Пишем содержимое обратно в файл
            file_put_contents($file, $current);          
        }          
                     
        //END           
                              
    }    
       
    /**
    * Метод возвращает массив данных из файла текущего состояния рассылки.
    * 
    * Метод возвращает данные текущей статистики, которые используются для отрисовки прогресс-бара
    * при запущенной рассылке
    * 
    * @param string $EVENT_ID Идентификатор рассылки, для которой запрашивается текущее состояние
    *  
    * @param string $EVENT_COUNT_RUN Порядковый номер запуска рассылки  
    * 
    * @return array|boolean Массив с данными статистики о запущенной рассылке или false в случае если переданы не все параметры или файл со статистикой не доступен
    *
    */
    public function ProgressFileGetArray($EVENT_ID=0, $EVENT_COUNT_RUN=0)
    {
        $file_path =  $_SERVER["DOCUMENT_ROOT"]."/bitrix/tmp/sotbit_mailing_progress_".$EVENT_ID."_".$EVENT_COUNT_RUN.".json";
            
        if(!$EVENT_ID && !$EVENT_COUNT_RUN) {
            return false;    
        }
        if (!is_readable($file_path)) {
             return false;
        }    
        $f = fopen($file_path, "r+");
        $arr = fgets($f); 
        $info = json_decode($arr);
        // Закрыть текстовый файл
        fclose($f);   
        $info = (array)$info;
        return $info ;                                        
    }    
   
    /**
    * Метод удаляет файлы прогресса для рассылки.
    * 
    * Метод возвращает данные текущей статистики, которые используются для отрисовки прогресс-бара
    * при запущенной рассылке
    * 
    * @param string $EVENT_ID Идентификатор рассылки, для которой запрашивается текущее состояние
    *  
    * @param string $EVENT_COUNT_RUN Порядковый номер запуска рассылки  
    * 
    * @return void
    *
    */
    public function ProgressFileDelete($EVENT_ID=0, $EVENT_COUNT_RUN=0)
    {
        $file_path = $_SERVER["DOCUMENT_ROOT"]."/bitrix/tmp/sotbit_mailing_progress_".$EVENT_ID."_".$EVENT_COUNT_RUN.".json";                    
        if(file_exists($file_path))
            unlink($file_path);
        
        //удалим дополнительную информацию
        $file_path = $_SERVER["DOCUMENT_ROOT"]."/bitrix/tmp/sotbit_mailing_progress_".$EVENT_ID."_".$EVENT_COUNT_RUN."_EMAIL_TO_SEND.txt";
        if(file_exists($file_path))
            unlink($file_path);
        
        $file_path = $_SERVER["DOCUMENT_ROOT"]."/bitrix/tmp/sotbit_mailing_progress_".$EVENT_ID."_".$EVENT_COUNT_RUN."_EMAIL_TO_EXCLUDE_HOUR_AGO.txt";    
        if(file_exists($file_path))
            unlink($file_path);
        
        $file_path = $_SERVER["DOCUMENT_ROOT"]."/bitrix/tmp/sotbit_mailing_progress_".$EVENT_ID."_".$EVENT_COUNT_RUN."_EMAIL_TO_EXCLUDE_UNSUBSCRIBED.txt";    
        if(file_exists($file_path))
            unlink($file_path);
        
        $file_path = $_SERVER["DOCUMENT_ROOT"]."/bitrix/tmp/sotbit_mailing_progress_EMAIL_TO_EXCLUDE_UNDELIVERED.txt";    
        if(file_exists($file_path))
            unlink($file_path);
    }

    /**
    * Метод производит подстановку переменных в строку.
    * 
    * @param string $string Строка в которой требуется произвести замену
    *  
    * @param string $variables Массив переменных и их значений для замены  
    * 
    * @return string Строка с замещенными переменными.
    *
    */
    public function ReplaceVariables($string=false, $variables=array())
    {
        if(isset($string))
        {
            if($variables!==false && is_array($variables))  {
                foreach($variables as $search => $replace) {
                    $string = str_replace('#'.$search.'#', $replace, $string);                       
                }
             
            }
            return $string;
        }
    }

    public function multineedle_stripos($haystack, $needles, $offset=0)
    {
        
        foreach($needles as $needle) {
            if(stripos($haystack, $needle)!=false){
                return true;
            }
        }
        return false;
        
    }

    /**
    * Метод извлекает из текста сообщения имена переменных, для которых требуется подстановка значений
    * 
    * Также дополнительно производится проверка на отсутсвие недопустимых символов в именах переменных
    * 
    * @param string $TEMPLATE Текст сообщения, в котором осуществляется поиск переменных
    *  
    * @return array Массив переменных.
    *
    */
    public function GetNeedVariablesTemplate($TEMPLATE)
    {
        
        $simvolNone = array(
            '"',
            ';',
            '&',
            '<',
            '>',
            '='
        );

        $templateArr = array();
        $chars = preg_split('/[\##]/', $TEMPLATE, -1, PREG_SPLIT_DELIM_CAPTURE);

        foreach($chars as $kte => $vte) {
            $countVte = strlen($vte);
            if($countVte < 150 && CSotbitMailingHelp::multineedle_stripos($vte,$simvolNone)==false){
               $templateArr[$vte] = $vte;
            }
        }

        
        return $templateArr; 
        
        
    }

    public function slaap($seconds=0)
    {
        $seconds = abs($seconds);
        if ($seconds < 1):
           usleep($seconds*1000000);
        else:
           sleep($seconds);
        endif;   
    }

    /**
    * Метод - обработчик, вызывается для события onPageStart модуля main.
    * 
    * Метод подклюдчает файлы event.php из папок шаблонов компонента sotbit.mailing.logic, если они существуют
    * Фйл event.php содержит код добавления в систему обработчика на какое-либо событие и функцию, его реализующую.
    * (Например, при смене статуса заказа обработчик, объявленный в файле event.php соответсвующей рассылки сам производит её запуск)
    * Авторизирует пользователя из рассылки, если присутствует нужный параметр  
    *
    * @return void
    *
    */   
    public function OnPageStart()
    {
        // сделаем механизм событий сценариев
        // START
        //получим шаблоны и файл event.php   
        $arrEventLogicFile = array();          
        $templComponentMailing = CComponentUtil::GetTemplatesList('sotbit:sotbit.mailing.logic',true);
    
        if(is_array($templComponentMailing)) { 
            foreach($templComponentMailing as $valTemp){    
                if($valTemp['NAME'] != '.default') {   
                    if($valTemp['TEMPLATE']) {
                        $fileEventName = '/bitrix/templates/'.$valTemp['TEMPLATE'].'/components/sotbit/sotbit.mailing.logic/'.$valTemp['NAME'].'/event.php';
                        if(file_exists($_SERVER["DOCUMENT_ROOT"].$fileEventName)) {
                            $arrEventLogicFile[$valTemp['NAME']] = $fileEventName;    
                        }                                 
                    } 
                    else {
                        $fileEventName = '/bitrix/components/sotbit/sotbit.mailing.logic/templates/'.$valTemp['NAME'].'/event.php';
                        if(file_exists($_SERVER["DOCUMENT_ROOT"].$fileEventName)) {
                            $arrEventLogicFile[$valTemp['NAME']] = $fileEventName;    
                        } 
                    }                
                }
            }  
        }          
        //подключим все файлы с событиями
        //получим активные рассылки
        
        $ar_Temp_id = array();
        $ar_activeTemp = array();
        $db_activeTemp = CSotbitMailingEvent::GetList(array(),array('ACTIVE'=>'Y'),false,array('ID','TEMPLATE','TEMPLATE_PARAMS'));
        while($res_activeTemp = $db_activeTemp->Fetch()) {
            $ar_activeTemp[] = $res_activeTemp['TEMPLATE'];   
            $ar_Temp_id[$res_activeTemp['TEMPLATE']][$res_activeTemp['ID']] = array('ID'=>$res_activeTemp['ID'],'TEMPLATE_PARAMS'=>unserialize($res_activeTemp['TEMPLATE_PARAMS']));
            //$ar_Temp_id[$res_activeTemp['TEMPLATE']][] = $res_activeTemp['ID'];
        }        
        foreach($arrEventLogicFile as $k=>$EventLogicFile) {
            if(in_array($k, $ar_activeTemp)){
                $arResult['MAILING_INFO'] = $ar_Temp_id[$k];
                include($_SERVER["DOCUMENT_ROOT"].$EventLogicFile);
            }
        }
        // END 
                
        // авторизуем пользователя из рассылки если  есть параметры
        // START  
        if($_GET['USER_AUTH'] && $_GET['MAILING_MESSAGE']) { 
            //получим объект пользователя
            global $USER;
            if(!is_object($USER)){
                $USER = new CUser();
            }
            //проверим авторизован ли пользователь  
            if(!$USER->IsAuthorized()) {
                //достанем данные из базы для авторизации пользователя
                $paramMessage = CSotbitMailingMessage::GetByIDInfoParamMessage($_GET['MAILING_MESSAGE']); 
                if($paramMessage['USER_AUTH']==$_GET['USER_AUTH'] && !empty($paramMessage['USER_ID'])){

					$auth = false;
					$groups = unserialize(Main\Config\Option::get('sotbit.mailing','AUTH_GROUPS','a:1:{i:0;s:1:"6";}'));
                	if(!is_array($groups))
					{
						$groups = [];
					}

					$user_groups = \CUser::GetUserGroup($paramMessage['USER_ID']);

					if(count($groups) > 0 && !in_array(1,$user_groups) && count(array_intersect($groups, $user_groups)) > 0)
					{
						$auth = true;
					}

					if($auth)
					{
						$USER->Authorize($paramMessage['USER_ID']);
					}
                }               
            }
                 
                
        }  
        // END        
    }

    /**
    * Метод - обработчик, вызывается для события OnAfterEpilog модуля main.
    * 
    * Если польщователь вернулся обратно, то собираем статистику, после сбора - редирект по месту перехода пользователя
    * Если пользователь отписался от рассылки, то заносим его данныце в таблицу отписавшихся
    * Записываем в cookies откуда пришел пользователь
    *
    * @return void
    *
    */   
    public function OnEpilog()
    {
        // если пользователь вернулся по сообщению обратно
        // START
        global $APPLICATION;
        if($_GET['MAILING_MESSAGE'])
        {
            $mailing_message = CSotbitMailingMessage::GetByIDInfoStatic($_GET['MAILING_MESSAGE']);
            
            if($_SERVER['SERVER_NAME'])
            {
                $site_name = $_SERVER['SERVER_NAME'];
            }
            elseif($_SERVER['HTTP_HOST'])
            {
                $site_name = $_SERVER['HTTP_HOST'];
            }
            if($site_name)
            {
                setcookie('MAILING_CAME_MESSAGE_ID', $_GET['MAILING_MESSAGE'], time()+3600*3, "/", $site_name);
            }
            
            if($mailing_message)
            {
                global $DB;
                global $USER;
                if(!is_object($USER))
                {
                    $USER = new CUser();
                }
 
                // создадим данные для обновления статистики
                $arrFields = array();
                if($mailing_message['STATIC_USER_BACK'] == 'N')
                {
                    $arrFields['STATIC_USER_BACK'] = 'Y';
                }
                
                // получим массивы
                $arrFields['STATIC_USER_BACK_DATE'] = unserialize($mailing_message['STATIC_USER_BACK_DATE']);
                $arrFields['STATIC_USER_ID'] = unserialize($mailing_message['STATIC_USER_ID']);
                $arrFields['STATIC_SALE_UID'] = unserialize($mailing_message['STATIC_SALE_UID']);
                $arrFields['STATIC_GUEST_ID'] = unserialize($mailing_message['STATIC_GUEST_ID']);
                $arrFields['STATIC_PAGE_START'] = unserialize($mailing_message['STATIC_PAGE_START']);
                
                // добавим в массивы новую информацию
                //START
                
                //запишем дату                                                                                              
                $arrFields['STATIC_USER_BACK_DATE'][] = Date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL", SITE_ID)));
                
                //запишем пользователя
                $USER_ID = $USER->GetID();
                if($USER_ID) {
                    $arrFields['STATIC_USER_ID'][] = $USER_ID;                    
                } else {
                    $arrFields['STATIC_USER_ID'][] = 'N';                      
                }
                
                //запишем id пользователя корзины
               // $SALE_UID = $_COOKIE[COption::GetOptionString("main", "cookie_name", "0").'_SALE_UID'];
                if(CModule::IncludeModule('sale')){
                    $SALE_UID = CSaleBasket::GetBasketUserID();
                    if($SALE_UID) {
                        $arrFields['STATIC_SALE_UID'][] = $SALE_UID;                    
                    } else {
                        $arrFields['STATIC_SALE_UID'][] = 'N';                      
                    }                     
                }                 
                //запишем id гостя из веб-аналитики
                //$GUEST_ID = $_COOKIE[COption::GetOptionString("main", "cookie_name", "0").'_GUEST_ID'];
                $GUEST_ID = intval($_SESSION["SESS_GUEST_ID"]);
                if($GUEST_ID) {
                    $arrFields['STATIC_GUEST_ID'][] = $GUEST_ID;                    
                } else {
                    $arrFields['STATIC_GUEST_ID'][] = 'N';                      
                }   
                
                //запишем куда перешел пользователь
                $user_came_where = (empty($_SERVER['HTTPS']) ? 'http' : 'https').'://'.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
                $arrFields['STATIC_PAGE_START'][] = $user_came_where;
                //END

                //подготовим массив для базы
                $arrFields['STATIC_USER_BACK_DATE'] = serialize($arrFields['STATIC_USER_BACK_DATE']);
                $arrFields['STATIC_USER_ID'] = serialize($arrFields['STATIC_USER_ID']);
                $arrFields['STATIC_SALE_UID'] = serialize($arrFields['STATIC_SALE_UID']);
                $arrFields['STATIC_GUEST_ID'] = serialize($arrFields['STATIC_GUEST_ID']);
                $arrFields['STATIC_PAGE_START'] = serialize($arrFields['STATIC_PAGE_START']);
                
                CSotbitMailingMessage::Update($_GET['MAILING_MESSAGE'],$arrFields);
                
                
                //сделаем редирект убрав данные
                //START
                $user_came_where_redirect = $user_came_where;
//                $strReplaceLink = array(
//                    'MAILING_MESSAGE='.$_GET['MAILING_MESSAGE'].'&',
//                    'MAILING_MESSAGE='.$_GET['MAILING_MESSAGE'],
//                    'USER_AUTH='.$_GET['USER_AUTH'].'&',
//                    'USER_AUTH='.$_GET['USER_AUTH']
//                );
//                foreach($strReplaceLink as $replace){
//                    $user_came_where_redirect = str_replace($replace, "",  $user_came_where_redirect);
//                }
                $user_came_where_redirect = self::removeqsvar($user_came_where_redirect, 'MAILING_MESSAGE');
                $user_came_where_redirect = self::removeqsvar($user_came_where_redirect, 'USER_AUTH');

                // so that when you add to the basket by the link, the addition to the basket is not duplicated
                if(!empty($user_came_where_redirect)) {
                    $query = parse_url($user_came_where_redirect, PHP_URL_QUERY);
                    $user_came_where_redirect .= ($query ? '&' : '?').'SOTBIT_MAILING_STOP_ADD_BASKET=Y';
                }

                $APPLICATION->RestartBuffer();
                LocalRedirect($user_came_where_redirect, true);
                //END
            }
        }
        // END
        
        // если пользователь с файлом
        // START
        if($_GET['MAILING_FILE_REDURECT'])
        {
            $APPLICATION->RestartBuffer();
            LocalRedirect($_GET['MAILING_FILE_REDURECT'], true);
        }
        // END
        
        
        // если пользователь отписался от рассылки
        // START
        if($_GET['MAILING_UNSUBSCRIBE']) { 
         
            $arrUnscrible = explode('||', $_GET['MAILING_UNSUBSCRIBE']); 
            $ID_MESSEGE = $arrUnscrible[0];
            $ID_EVENT = $arrUnscrible[1];
            if($ID_MESSEGE && $ID_EVENT) {
              
                $MessageInfo = CSotbitMailingMessage::GetByID($ID_MESSEGE);
                if($MessageInfo['ID_EVENT'] == $ID_EVENT && !empty($MessageInfo['EMAIL_TO'])) {
                    global $DB;
                    $EMAIL_TO = $MessageInfo['EMAIL_TO'];
         
                    $resSearch = CSotbitMailingUnsubscribed::GetList(array(), array('ID_EVENT' => $ID_EVENT, 'EMAIL_TO' => $EMAIL_TO));
                    $arrSearch = $resSearch->Fetch();
                    if(empty($arrSearch)) {
                     
                        CSotbitMailingUnsubscribed::Add(array(
                            'DATE_CREATE' => Date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL", SITE_ID))),
                            'ID_MESSEGE' => $ID_MESSEGE,
                            'ID_EVENT' => $ID_EVENT,
                            'EMAIL_TO' => $EMAIL_TO
                        ));                        
                        
                        global $CACHE_MANAGER;       
                        $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetUnsubscribedByMailing_'.$ID_EVENT);  
                        $CACHE_MANAGER->ClearByTag(self::MODULE_ID.'_GetUnsubscribedAllMailing');                             
                    }                  
                }              
            }                   
        }
        // END
        
        //Данные для статистики
        //START
        //запишем в cookies откуда пришел пользователь
        $MAILING_USER_CAME = '';
        if($_COOKIE["MAILING_USER_CAME"])
        {
            $MAILING_USER_CAME = $_COOKIE["MAILING_USER_CAME"];
        }
        if(empty($MAILING_USER_CAME))
        {
            $MAILING_USER_CAME = getenv("HTTP_REFERER");
            if($MAILING_USER_CAME)
            {
                while (preg_match('%([0-9A-F]{2})',$MAILING_USER_CAME))
                {
                    $val=preg_replace('.*%([0-9A-F]{2}).*','\1',$MAILING_USER_CAME);
                    $newval=chr(hexdec($val));
                    $MAILING_USER_CAME=str_replace('%'.$val,$newval,$MAILING_USER_CAME);
                }
                SetCookie("MAILING_USER_CAME", $MAILING_USER_CAME, time()+7*24*60*60, '/', $_SERVER['SERVER_NAME']);
            }
        }
    }

    public function removeqsvar($url, $varname) {
        list($urlpart, $qspart) = array_pad(explode('?', $url), 2, '');
        parse_str($qspart, $qsvars);
        unset($qsvars[$varname]);
        $newqs = http_build_query($qsvars);
        return $urlpart . '?' . $newqs;
    }

    //запишем номер заказа в сообщение
    /**
    * Метод - обработчик, вызывается для события OnOrderAdd модуля sale.
    * 
    * Метод записывает номер заказа в сообщение (таблица b_sotbit_mailing_message)
    *
    * @return void
    *
    */
    public function OnOrderAdd($ID, $arFields)
    {
        if($_COOKIE["MAILING_CAME_MESSAGE_ID"] && $ID)
        {
            CSotbitMailingMessage::Update($_COOKIE["MAILING_CAME_MESSAGE_ID"], array('STATIC_ORDER_ID' => $ID));
        }
    }

    //логи мэйла
    function ParseLogMail($file = "/var/log/maillog")
    {
        if(!file_exists($file))
        {
            $error["ERROR"][] = GetMessage(""); //вывесть ошибку о несуществовании файла
            return $error;
        }
        $str = file_get_contents($file);
        if(($str !== false) && strlen($str) > 0)
        {
            $arrLog = explode("\n", $str);
            $noMail = array();
            if(count($arrLog) > 0)
            {
                foreach($arrLog as $lineLog)
                {
                    if(strlen($lineLog) > 0)
                    {    
                        if(preg_match("/said:\s{1}(\d+)/i", $lineLog, $arr))
                        {
                            $arr[1] = trim($arr[1]);
                            if(preg_match("/([a-z0-9_\.\-])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,4})+/i", $lineLog, $mail) && !in_array($mail[0], $disabled[$arr[1]]))
                            {
                                
                                $disabled[$arr[1]][] = $mail[0];
                                if(!in_array($mail[0], $email_all))
                                {
                                    $email_all[] = $mail[0];
                                }
                            }
                        }
                       
                    }
                }
                
                if(isset($disabled) && count($disabled)>0)
                {
                    $result["EMAIL_ERROR"] = $disabled;
                    $result["EMAIL_ERROR_ALL"] = $email_all;
                }
            }
            return $result;
        }
        else
        {
             $error["ERROR"][] = GetMessage("ParseLogMail_EMPTY"); //файл пустой
             return $error;
        }
    }

    public function OnBuildGlobalMenuHandler(&$arGlobalMenu, &$arModuleMenu)
    {
        Menu::getAdminMenu($arGlobalMenu, $arModuleMenu);
    }
}
