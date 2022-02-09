<?
/**
* Класс для работы с таблицей b_sotbit_mailing_event, которая хранит параметры настроенных рассылок. 
*
* Поля таблицы:
* <ul>
* <li><b>ID</b> - Идентификатор рассылки</li>
* <li><b>ACTIVE</b> - Флаг активности рассылки</li>
* <li><b>NAME</b> - Название рассылки</li>
* <li><b>DESCRIPTION</b> - Описание рассылки</li>
* <li><b>SORT</b> - Порядок сортировки</li>
* <li><b>MODE</b> - Режим работы рассылки (рабочий или тестовый)</li>
* <li><b>SITE_URL</b> - Адрес сайта</li>
* <li><b>USER_AUTH</b> - Авторизовывать ли пользователя</li>
* <li><b>TEMPLATE</b> - Шаблон рассылки</li>
* <li><b>TEMPLATE_PARAMS</b> - Сериализованные параметры рассылки</li>
* <li><b>EVENT_PARAMS</b> - Сериализованные параметры почтового события</li>
* <li><b>COUNT_RUN</b> - Количество запусков данной рассылки</li>
* <li><b>DATE_LAST_RUN</b> - Дата последнего запуска</li>
* <li><b>AGENT_ID</b> - Идентификатор агента, по которому осуществляется запуск</li>
* <li><b>AGENT_TIME_START</b> - Время для ограничения начала рассылки</li>
* <li><b>AGENT_TIME_END</b> - Время для ограничения окончания рассылки</li>
* <li><b>EVENT_TYPE</b> - Название почтового события. к которому привязана рассылка</li>
* <li><b>EVENT_SEND_SYSTEM</b> - Название почтовой системы, которая осуществляет отправку сообщения (BITRIX или UNISENDER)</li>
* <li><b>EXCLUDE_HOUR_AGO</b> - Исключение дублей писем (часов)</li>
* <li><b>EXCLUDE_UNSUBSCRIBED_USER</b> - Исключить отписавшиеся email (ALL - отписавшиеся с общего списка, THIS - по этой рассылке, NO - не исключать)</li>
* <li><b>EXCLUDE_UNSUBSCRIBED_USER_MORE</b> - Дополнительные исключения</li>
* <li><b>CATEGORY_ID</b> - Идентификатор родительской категории для рассылки (для создания вложенности)</li>
* </ul> 
*/  
class CSotbitMailingEvent
{  
    public function Add($arFields)
    {
        global $DB;   
        $arInsert = $DB->PrepareInsert("b_sotbit_mailing_event", $arFields);
        $strSql =
            "INSERT INTO b_sotbit_mailing_event(".$arInsert[0].") ".
            "VALUES(".$arInsert[1].")";
        $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);       
        $ID = IntVal($DB->LastID());

        return $ID;       
    } 


    
    public function Update($ID ,$arFields)
    {
        global $DB;
        $ID = IntVal($ID);   
        $strUpdate = $DB->PrepareUpdate("b_sotbit_mailing_event", $arFields);  
        $strSql = "UPDATE b_sotbit_mailing_event SET ".$strUpdate." WHERE ID=".$ID;
        $res = $DB->Query($strSql, true, $err_mess.__LINE__);
        if($res == false) {
            return false;    
        }
        else {
            return $ID;     
        }        
    }
     
        
    public function GetByID($ID)
    {
        global $DB;    
        $ORDER_ID = IntVal($ID);
        $strSql = "SELECT P.* FROM b_sotbit_mailing_event P WHERE P.ID='".$DB->ForSql($ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        if($arRes['EVENT_PARAMS']){
            $EVENT_PARAMS = unserialize($arRes['EVENT_PARAMS']); 
            foreach($EVENT_PARAMS as $k=>$v){
                $arRes['EVENT_PARAMS_'.$k] = $v;    
            }    
        } 
        return $arRes;        
    }      
          
    public function Delete($ID)
    {
        global $DB;
        $ID = IntVal($ID);
        $strSql = "DELETE FROM b_sotbit_mailing_event WHERE ID='".$DB->ForSql($ID)."'";
        $DB->Query($strSql, true);
        return true; 
    }
           
    public function GetList($aSort=Array(), $arFilter=Array(), $arNavStartParams=false, $arSelect=Array())
    {
        global $DB;
        $arSqlSearch = Array();
        $arSqlSearch_h = Array();
        $strSqlSearch = ""; 
 

        if (is_array($arFilter))
        {
            foreach($arFilter as $key=>$val)
            {
                
                //if (!is_array($val) && (strlen($val)<=0 || $val=="NOT_REF"))
                if($key!="CATEGORY_ID")if (!is_array($val) && (strlen($val)<=0 || $val=="NOT_REF"))
                    continue;

                
                    
                switch(strtoupper($key))
                {
                case "ID":
                    if(is_array($val)) {
                        $val = implode(" | ",$val);                       
                    }
                    $arSqlSearch[] = GetFilterQuery("P.ID",$val,'N');  
                    break;
                case "ACTIVE":
                    $arSqlSearch[] = GetFilterQuery("P.ACTIVE",$val);
                    break;  
                case "NAME":
                    $arSqlSearch[] = GetFilterQuery("P.NAME",$val);
                    break;   
                case "SORT":
                    $arSqlSearch[] = GetFilterQuery("P.SORT",$val);
                    break;   
                case "MODE":
                    $arSqlSearch[] = GetFilterQuery("P.MODE",$val);
                    break;    
                case "SITE_URL":
                    $arSqlSearch[] = GetFilterQuery("P.SITE_URL",$val);
                    break;  
                case "USER_AUTH":
                    $arSqlSearch[] = GetFilterQuery("P.USER_AUTH",$val);
                    break;                                                                                                                                    
                case "TEMPLATE":
                    $arSqlSearch[] = GetFilterQuery("P.TEMPLATE",$val);
                    break;  
                
                case "COUNT_RUN":
                    $arSqlSearch[] = GetFilterQuery("P.COUNT_RUN",$val);
                    break;                 
                //дата    
                case ">=DATE_LAST_RUN":
                    $arSqlSearch[] = "P.DATE_LAST_RUN >= FROM_UNIXTIME('".MkDateTime(FmtDate($val,"DD.MM.YYYY HH:MI:SS"),"d.m.Y H:i:s")."')";
                    break;   
                case "<=DATE_LAST_RUN":
                    $arSqlSearch[] = "P.DATE_LAST_RUN <= FROM_UNIXTIME('".MkDateTime(FmtDate($val,"DD.MM.YYYY HH:MI:SS"),"d.m.Y H:i:s")."')";
                    break;                     
                case ">DATE_LAST_RUN":
                    $arSqlSearch[] = "P.DATE_LAST_RUN > FROM_UNIXTIME('".MkDateTime(FmtDate($val,"DD.MM.YYYY HH:MI:SS"),"d.m.Y H:i:s")."')";
                    break;   
                case "<DATE_LAST_RUN":
                    $arSqlSearch[] = "P.DATE_LAST_RUN < FROM_UNIXTIME('".MkDateTime(FmtDate($val,"DD.MM.YYYY HH:MI:SS"),"d.m.Y H:i:s")."')";
                    break;   
                                      
                                                                           
                case "AGENT_ID":
                    $arSqlSearch[] = GetFilterQuery("P.AGENT_ID",$val);
                    break;                     
                case "EVENT_TYPE":
                    $arSqlSearch[] = GetFilterQuery("P.EVENT_TYPE_ID",$val);
                    break;    
                case "EVENT_SEND_SYSTEM":
                    $arSqlSearch[] = GetFilterQuery("P.EVENT_SEND_SYSTEM",$val);
                    break;     
  
                case "EXCLUDE_HOUR_AGO":
                    $arSqlSearch[] = GetFilterQuery("P.EXCLUDE_HOUR_AGO",$val);
                    break;                                        
                case "EXCLUDE_UNSUBSCRIBED_USER":
                    $arSqlSearch[] = GetFilterQuery("P.EXCLUDE_UNSUBSCRIBED_USER",$val);
                    break; 
                 case "CATEGORY_ID":
                    $arSqlSearch[] = "P.CATEGORY_ID='".$val."'";
                    break;
                } 
                
            }
        }
       
        

        //сортировка запроса  
        $arOrder = array();
        foreach($aSort as $key => $ord)
        {    
            $key = strtoupper($key);
            $ord = (strtoupper($ord) <> "ASC"? "DESC": "ASC");
            switch($key)
            {
                case "ID":        $arOrder[$key] = "P.ID ".$ord; break;
                case "ACTIVE":    $arOrder[$key] = "P.ACTIVE ".$ord; break;
                case "NAME":    $arOrder[$key] = "P.NAME ".$ord; break;  
                case "SORT":    $arOrder[$key] = "P.SORT ".$ord; break;
                case "MODE":    $arOrder[$key] = "P.MODE ".$ord; break;
                case "TEMPLATE":    $arOrder[$key] = "P.TEMPLATE ".$ord; break; 
                case "COUNT_RUN":    $arOrder[$key] = "P.COUNT_RUN ".$ord; break;  
                case "DATE_LAST_RUN":    $arOrder[$key] = "P.DATE_LAST_RUN ".$ord; break;  
                case "AGENT_ID":    $arOrder[$key] = "P.AGENT_ID ".$ord; break;                                                                                                       
                case "EVENT_TYPE_ID":    $arOrder[$key] = "P.EVENT_TYPE_ID ".$ord; break;    
                case "EXCLUDE_HOUR_AGO":    $arOrder[$key] = "P.EXCLUDE_HOUR_AGO ".$ord; break;                 
                case "EXCLUDE_UNSUBSCRIBED_USER":    $arOrder[$key] = "P.EXCLUDE_UNSUBSCRIBED_USER ".$ord; break; 
                case "CATEGORY_ID":        $arOrder[$key] = "P.CATEGORY_ID ".$ord; break;   
                
            }        
        }
        if(count($arOrder) <= 0)
        {
            $arOrder["ID"] = "P.ID DESC";
        }
        

        // все таблицы которые есть и могут быть в запросе 
        $b_class_sel = array(
            "ID",
            "ACTIVE",
            "NAME",
            "DESCRIPTION",
            "SORT",
            "MODE",
            "SITE_URL",
            "USER_AUTH",
            "TEMPLATE",
            "TEMPLATE_PARAMS",
            "COUNT_RUN",
            "DATE_LAST_RUN",
            "EVENT_TYPE",
            "AGENT_ID",
            "AGENT_TIME_START",
            "AGENT_TIME_END",
            "EVENT_SEND_SYSTEM",
            "EVENT_SEND_SYSTEM_CODE",
            "EXCLUDE_HOUR_AGO",
            "EXCLUDE_UNSUBSCRIBED_USER",
            "EXCLUDE_UNSUBSCRIBED_USER_MORE",
            "CATEGORY_ID",
            "EVENT_PARAMS",
        );
        
       

        
        
        
        //подчистим лишнее из запроса  
        foreach($arSelect as $k => $v) {
            if(!in_array($v, $b_class_sel)) {
                unset($arSelect[$k]);        
            }        
        } 
        if(!empty($arSelect)) {

            $dateFields = array(
                'DATE_LAST_RUN',
                'DATE_CHECKOUT'
            ); 
            $iOrder = 0;
            foreach($arSelect as $selectVal) {
                if($iOrder != 0){    
                    $strSqlSelect .= ",";              
                }
                // если поле с датой
                if(in_array($selectVal, $dateFields)) {
                    $strSqlSelect .= "
                        ".$DB->DateToCharFunction("P.".$selectVal)." ". $selectVal ." 
                    ";                         
                }
                else {
                    $strSqlSelect .= "
                        P.".$selectVal."     
                    ";                     
                }
                $iOrder++;
            }
        }
        else {
            $strSqlSelect = "P.*";
        } 
            
        $strSqlOrder = " ORDER BY ".implode(", ", $arOrder);
        $strSqlSearch = GetFilterSqlSearch($arSqlSearch);  
        $strSql = "
            SELECT 
                 ".$strSqlSelect."            
            FROM b_sotbit_mailing_event P
            WHERE
            ".$strSqlSearch."
        ";   
        if(count($arSqlSearch_h)>0)
        {
            $strSqlSearch_h = GetFilterSqlSearch($arSqlSearch_h);
            $strSql = $strSql." HAVING ".$strSqlSearch_h;
        }
        $strSql.=$strSqlOrder;   
        

       
        $res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);       
        $res->is_filtered = (IsFiltered($strSqlSearch));
        return $res;   

          
    }  
    
    function GetMixedList($aSort=Array(), $arFilter=Array(), $show="all")
    {
        $filter = array();
        $arResult = array();
        if($show=="all" || $show=="section")
        {
            if(isset($arFilter["CATEGORY_ID"]))
            {
                //$filter["PARENT_CATEGORY_ID"] = $arFilter["CATEGORY_ID"];
                $filter["CATEGORY_ID"] = $arFilter["CATEGORY_ID"];
            }

            $rsSection = CSotbitMailingSectionTable::getList(array(
                'limit' =>null,
                'offset' => null,
                'select' => array("*"),
                //'order' => $aSort,
                "filter" => $filter
            ));

            while($arSection = $rsSection->Fetch())
            {
                $arSection["T"]="S";
                $arResult[]=$arSection;
            }    
        }/*else{
            $arResult[] = array();    
        }*/
        
        if($show=="all" || $show=="delivery")
        {
            $cData = new CSotbitMailingEvent;
            $rsData = $cData->GetList($aSort, $arFilter);
            while($arData = $rsData->Fetch())
            {
                $arData["T"]="R";
                //Получим значение темы оформления для почтового шаблона текущей рассылки
                $helper = new CSotbitMailingHelp();
                $eventMessageId =  $helper->EventTemplateCheck($arData['EVENT_TYPE'], $arData['NAME']);
                //Получим параметры почтового шаблона
                $arEM = array();
                if (!empty($eventMessageId)) {
                    $rsEM = CEventMessage::GetByID($eventMessageId);
                    $arEM = $rsEM->Fetch();
                }
                if (isset($arEM['SITE_TEMPLATE_ID'])) {
                    $arData['MESSAGE_THEME'] = $arEM['SITE_TEMPLATE_ID'];                                
                }
                $arResult[] = $arData;
            }
            unset($cData);    
        }/*else{
            $arResult[] = array();    
        }*/

        $rsResult = new CDBResult;
        $rsResult->InitFromArray($arResult);

        //printr($rsResult);
        
        return $rsResult;
    }      
}
?>