<?
class CSotbitMailingCategories
{  
    public function Add($arFields)
    {
        global $DB;
        
        if(!$arFields['DATE_CREATE']){
            $arFields['DATE_CREATE'] = Date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL", SITE_ID)));           
        }
        
        $arInsert = $DB->PrepareInsert("b_sotbit_mailing_categories", $arFields);
        $strSql =
            "INSERT INTO b_sotbit_mailing_categories(".$arInsert[0].") ".
            "VALUES(".$arInsert[1].")";
        $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);       
        $ID = IntVal($DB->LastID());
        return $ID;
    } 
    
    public function Update($ID ,$arFields)
    {
        global $DB;
        $ID = IntVal($ID);  
        $strUpdate = $DB->PrepareUpdate("b_sotbit_mailing_categories", $arFields);  
        $strSql = "UPDATE b_sotbit_mailing_categories SET ".$strUpdate." WHERE ID=".$ID;
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
        $strSql = "SELECT P.* FROM b_sotbit_mailing_categories P WHERE P.ID='".$DB->ForSql($ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        return $arRes;        
    }      
          
          
    public function Delete($ID)
    {
        global $DB;
        $ID = IntVal($ID);
        $strSql = "DELETE FROM b_sotbit_mailing_categories WHERE ID='".$DB->ForSql($ID)."'";
        $DB->Query($strSql, true);  
        CSotbitMailingSubscribers::CategoriesDelete($ID);
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
                    
                case "NAME":
                    $arSqlSearch[] = GetFilterQuery("P.NAME",$val);
                    break;                    
                case "DESCRIPTION":
                    $arSqlSearch[] = GetFilterQuery("P.DESCRIPTION",$val);
                    break;                      
                case "SUNC_USER":
                    $arSqlSearch[] = GetFilterQuery("P.SUNC_USER",$val);
                    break;                                                                             
                case "SUNC_USER_MESSAGE":
                    $arSqlSearch[] = GetFilterQuery("P.SUNC_USER_MESSAGE",$val);
                    break;  
                case "SUNC_USER_EVENT":
                    $arSqlSearch[] = GetFilterQuery("P.SUNC_USER_EVENT",$val);
                    break;    
                case "SUNC_SUBSCRIPTION":
                    $arSqlSearch[] = GetFilterQuery("P.SUNC_SUBSCRIPTION",$val);
                    break; 
                case "SUNC_SUBSCRIPTION_LIST":
                    $arSqlSearch[] = GetFilterQuery("P.SUNC_SUBSCRIPTION_LIST",$val);
                    break;   
                case "SUNC_MAILCHIMP":
                    $arSqlSearch[] = GetFilterQuery("P.SUNC_MAILCHIMP",$val);
                    break; 
                case "SUNC_MAILCHIMP_LIST":
                    $arSqlSearch[] = GetFilterQuery("P.SUNC_MAILCHIMP_LIST",$val);
                    break;    
                case "SUNC_MAILCHIMP_BACK":
                    $arSqlSearch[] = GetFilterQuery("P.SUNC_MAILCHIMP_BACK",$val);
                    break;                      
                case "SUNC_UNISENDER":
                    $arSqlSearch[] = GetFilterQuery("P.SUNC_UNISENDER",$val);
                    break;  
                case "SUNC_UNISENDER_BACK":
                    $arSqlSearch[] = GetFilterQuery("P.SUNC_UNISENDER_BACK",$val);
                    break;                      
                case "SUNC_UNISENDER_LIST":
                    $arSqlSearch[] = GetFilterQuery("P.SUNC_UNISENDER_LIST",$val);
                    break;   
                case "PARAM_1":
                    $arSqlSearch[] = GetFilterQuery("P.PARAM_1",$val);
                    break;    
                case "PARAM_2":
                    $arSqlSearch[] = GetFilterQuery("P.PARAM_2",$val);
                    break;  
                case "PARAM_3":
                    $arSqlSearch[] = GetFilterQuery("P.PARAM_3",$val);
                    break; 
                case "PARAM_INFO":
                    $arSqlSearch[] = GetFilterQuery("P.PARAM_INFO",$val);
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
                case "TYPE":    $arOrder[$key] = "P.TYPE ".$ord; break;
                case "EMAIL_TO":    $arOrder[$key] = "P.EMAIL_TO ".$ord; break;                     
                case "PHONE_TO":    $arOrder[$key] = "P.PHONE_TO ".$ord; break;                 
                case "USER_ID":    $arOrder[$key] = "P.USER_ID ".$ord; break;       
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
            "NAME",             
            "DESCRIPTION",
            "SUNC_USER",
            "SUNC_USER_MESSAGE",
            "SUNC_USER_EVENT",
            "SUNC_SUBSCRIPTION",
            "SUNC_SUBSCRIPTION_LIST",
            "SUNC_MAILCHIMP", 
            "SUNC_MAILCHIMP_BACK",
            "SUNC_MAILCHIMP_LIST",
            "SUNC_UNISENDER",
            "SUNC_UNISENDER_BACK",
            "SUNC_UNISENDER_LIST",
            "PARAM_1",
            "PARAM_2",
            "PARAM_3",
            "PARAM_INFO"                         
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
            FROM b_sotbit_mailing_categories P
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