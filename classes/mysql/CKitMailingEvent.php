<?
/**
* ����� ��� ������ � �������� b_kit_mailing_event, ������� ������ ��������� ����������� ��������.
*
* ���� �������:
* <ul>
* <li><b>ID</b> - ������������� ��������</li>
* <li><b>ACTIVE</b> - ���� ���������� ��������</li>
* <li><b>NAME</b> - �������� ��������</li>
* <li><b>DESCRIPTION</b> - �������� ��������</li>
* <li><b>SORT</b> - ������� ����������</li>
* <li><b>MODE</b> - ����� ������ �������� (������� ��� ��������)</li>
* <li><b>SITE_URL</b> - ����� �����</li>
* <li><b>USER_AUTH</b> - �������������� �� ������������</li>
* <li><b>TEMPLATE</b> - ������ ��������</li>
* <li><b>TEMPLATE_PARAMS</b> - ��������������� ��������� ��������</li>
* <li><b>EVENT_PARAMS</b> - ��������������� ��������� ��������� �������</li>
* <li><b>COUNT_RUN</b> - ���������� �������� ������ ��������</li>
* <li><b>DATE_LAST_RUN</b> - ���� ���������� �������</li>
* <li><b>AGENT_ID</b> - ������������� ������, �� �������� �������������� ������</li>
* <li><b>AGENT_TIME_START</b> - ����� ��� ����������� ������ ��������</li>
* <li><b>AGENT_TIME_END</b> - ����� ��� ����������� ��������� ��������</li>
* <li><b>EVENT_TYPE</b> - �������� ��������� �������. � �������� ��������� ��������</li>
* <li><b>EVENT_SEND_SYSTEM</b> - �������� �������� �������, ������� ������������ �������� ��������� (BITRIX ��� UNISENDER)</li>
* <li><b>EXCLUDE_HOUR_AGO</b> - ���������� ������ ����� (�����)</li>
* <li><b>EXCLUDE_UNSUBSCRIBED_USER</b> - ��������� ������������ email (ALL - ������������ � ������ ������, THIS - �� ���� ��������, NO - �� ���������)</li>
* <li><b>EXCLUDE_UNSUBSCRIBED_USER_MORE</b> - �������������� ����������</li>
* <li><b>CATEGORY_ID</b> - ������������� ������������ ��������� ��� �������� (��� �������� �����������)</li>
* </ul> 
*/  
class CKitMailingEvent
{  
    public function Add($arFields)
    {
        global $DB;   
        $arInsert = $DB->PrepareInsert("b_kit_mailing_event", $arFields);
        $strSql =
            "INSERT INTO b_kit_mailing_event(".$arInsert[0].") ".
            "VALUES(".$arInsert[1].")";
        $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);       
        $ID = IntVal($DB->LastID());

        return $ID;       
    } 


    
    public function Update($ID ,$arFields)
    {
        global $DB;
        $ID = IntVal($ID);   
        $strUpdate = $DB->PrepareUpdate("b_kit_mailing_event", $arFields);
        $strSql = "UPDATE b_kit_mailing_event SET ".$strUpdate." WHERE ID=".$ID;
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
        $strSql = "SELECT P.* FROM b_kit_mailing_event P WHERE P.ID='".$DB->ForSql($ID)."'";
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
        $strSql = "DELETE FROM b_kit_mailing_event WHERE ID='".$DB->ForSql($ID)."'";
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
                //����    
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
       
        

        //���������� �������  
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
        

        // ��� ������� ������� ���� � ����� ���� � ������� 
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
        
       

        
        
        
        //��������� ������ �� �������  
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
                // ���� ���� � �����
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
            FROM b_kit_mailing_event P
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

            $rsSection = CKitMailingSectionTable::getList(array(
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
            $cData = new CKitMailingEvent;
            $rsData = $cData->GetList($aSort, $arFilter);
            while($arData = $rsData->Fetch())
            {
                $arData["T"]="R";
                //������� �������� ���� ���������� ��� ��������� ������� ������� ��������
                $helper = new CKitMailingHelp();
                $eventMessageId =  $helper->EventTemplateCheck($arData['EVENT_TYPE'], $arData['NAME']);
                //������� ��������� ��������� �������
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