<?
class CSotbitMailingUndelivered
{  
    public function Add($arFields)
    {
        global $DB;
        $arInsert = $DB->PrepareInsert("b_sotbit_mailing_undelivered", $arFields);
        $strSql =
            "INSERT INTO b_sotbit_mailing_undelivered(".$arInsert[0].") ".
            "VALUES(".$arInsert[1].")";
        $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);       
        $ID = IntVal($DB->LastID());
        
        global $CACHE_MANAGER;    
        $CACHE_MANAGER->ClearByTag($module_id.'_GetUndeliveredMailing'); 
        
        return $ID;
    } 
    
    public function Update($ID ,$arFields)
    {
        global $DB;
        $ID = IntVal($ID);  
        $strUpdate = $DB->PrepareUpdate("b_sotbit_mailing_undelivered", $arFields);  
        $strSql = "UPDATE b_sotbit_mailing_undelivered SET ".$strUpdate." WHERE ID=".$ID;
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
        $strSql = "SELECT P.* FROM b_sotbit_mailing_undelivered P WHERE P.ID='".$DB->ForSql($ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        return $arRes;        
    }      
          
    public function Delete($ID)
    {
        global $DB;
        $ID = IntVal($ID);
        $strSql = "DELETE FROM b_sotbit_mailing_undelivered WHERE ID='".$DB->ForSql($ID)."'";
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
                if (!is_array($val) && (strlen($val)<=0 || $val=="NOT_REF"))
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
           
                case ">=DATE_CREATE":
                    $arSqlSearch[] = "P.DATE_CREATE >= FROM_UNIXTIME('".MkDateTime(FmtDate($val,"DD.MM.YYYY HH:MI:SS"),"d.m.Y H:i:s")."')";
                    break;   
                case "<=DATE_CREATE":
                    $arSqlSearch[] = "P.DATE_CREATE <= FROM_UNIXTIME('".MkDateTime(FmtDate($val,"DD.MM.YYYY HH:MI:SS"),"d.m.Y H:i:s")."')";
                    break;                     
                case ">DATE_CREATE":
                    $arSqlSearch[] = "P.DATE_CREATE > FROM_UNIXTIME('".MkDateTime(FmtDate($val,"DD.MM.YYYY HH:MI:SS"),"d.m.Y H:i:s")."')";
                    break;   
                case "<DATE_CREATE":
                    $arSqlSearch[] = "P.DATE_CREATE < FROM_UNIXTIME('".MkDateTime(FmtDate($val,"DD.MM.YYYY HH:MI:SS"),"d.m.Y H:i:s")."')";
                    break;                     
                                      
                case "ID_EVENT":
                    $arSqlSearch[] = GetFilterQuery("P.ID_EVENT",$val);
                    break;                      
                case "EMAIL_TO":
                    $arSqlSearch[] = GetFilterQuery("P.EMAIL_TO",$val);
                    break;                                                                             
                case "SPAM":
                    $arSqlSearch[] = GetFilterQuery("P.SPAM",$val);
                    break;
                case "ERROR_CODE":
                    $arSqlSearch[] = GetFilterQuery("P.ERROR_CODE",$val);
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
                case "DATE_CREATE":    $arOrder[$key] = "P.DATE_CREATE ".$ord; break;  
                case "ID_EVENT":    $arOrder[$key] = "P.ID_EVENT ".$ord; break;                     
                case "EMAIL_TO":    $arOrder[$key] = "P.EMAIL_TO ".$ord; break;                 
                case "SPAM":    $arOrder[$key] = "P.SPAM ".$ord; break;
                case "ERROR_CODE":    $arOrder[$key] = "P.ERROR_CODE ".$ord; break;                   
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
            "DATE_CREATE",
            "ID_EVENT",
            "EMAIL_TO",
            "SPAM",
            "ERROR_CODE"
        );
                
        //подчистим лишнее из запроса  
        foreach($arSelect as $k => $v) {
            if(!in_array($v, $b_class_sel)) {
                unset($arSelect[$k]);        
            }        
        } 
        if(!empty($arSelect)) {

            $dateFields = array(
                'DATE_CREATE'
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
            FROM b_sotbit_mailing_undelivered P
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
}
?>