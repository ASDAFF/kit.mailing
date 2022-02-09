<?
/**
* Класс для работы с таблицей b_sotbit_mailing_message_template, которая хранит шаблоны почтовых сообщений для рассылок. 
*
* Поля таблицы:
* <ul>
* <li><b>ID</b> - Идентификатор шаблона/li>
* <li><b>ID_EVENT</b> - Идентификатор рассылки, к которой относится шаблон</li>
* <li><b>COUNT_START</b> - </li>
* <li><b>COUNT_END</b> - </li>
* <li><b>TEMPLATE</b> - Текст шаблона письма</li>
* <li><b>ARCHIVE</b> - Флаг архивации сообщения</li>
* </ul> 
*/
class CSotbitMailingMessageTemplate
{  
    public function Add($arFields)
    {
        global $DB;
        $arInsert = $DB->PrepareInsert("b_sotbit_mailing_message_template", $arFields);
        $strSql =
            "INSERT INTO b_sotbit_mailing_message_template(".$arInsert[0].") ".
            "VALUES(".$arInsert[1].")";
        $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);       
        $ID = IntVal($DB->LastID());
        return $ID;
    } 
    
    public function Update($ID ,$arFields)
    {
        global $DB;
        $ID = IntVal($ID);  
        $strUpdate = $DB->PrepareUpdate("b_sotbit_mailing_message_template", $arFields);  
        $strSql = "UPDATE b_sotbit_mailing_message_template SET ".$strUpdate." WHERE ID=".$ID;
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
        $strSql = "SELECT P.* FROM b_sotbit_mailing_message_template P WHERE P.ID='".$DB->ForSql($ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        return $arRes;        
    }      
        
    public function Delete($ID)
    {
        global $DB;
        $ID = IntVal($ID);
        $strSql = "DELETE FROM b_sotbit_mailing_message_template WHERE ID='".$DB->ForSql($ID)."'";
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
                case "ID_EVENT":
                    $arSqlSearch[] = GetFilterQuery("P.ID_EVENT",$val,'N');
                    break;
                  
                case ">COUNT_START":
                    $arSqlSearch[] = "P.COUNT_START > $val";
                    break; 
                case ">=COUNT_START":
                    $arSqlSearch[] = "P.COUNT_START >= $val";
                    break;                       
                case "<COUNT_START":
                    $arSqlSearch[] = "P.COUNT_START < $val";
                    break;                                                                                 
                case "<=COUNT_START":
                    $arSqlSearch[] = "P.COUNT_START <= $val";
                    break;                                        

                case ">COUNT_END":
                    $arSqlSearch[] = "P.COUNT_END > $val";
                    break; 
                case ">=COUNT_END":
                    $arSqlSearch[] = "P.COUNT_END >= $val";
                    break;                       
                case "<COUNT_END":
                    $arSqlSearch[] = "P.COUNT_END < $val";
                    break;                                                                                 
                case "<=COUNT_END":
                    $arSqlSearch[] = "P.COUNT_END <= $val";
                    break;  
                                                                       
                case "TEMPLATE":
                    $arSqlSearch[] = GetFilterQuery("P.TEMPLATE",$val);
                    break; 
                case "ARСHIVE":
                    $arSqlSearch[] = GetFilterQuery("P.ARСHIVE",$val);                    
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
                case "ID_EVENT":    $arOrder[$key] = "P.ID_EVENT ".$ord; break; 
                case "COUNT_START":    $arOrder[$key] = "P.COUNT_START ".$ord; break; 
                case "COUNT_END":    $arOrder[$key] = "P.COUNT_END ".$ord; break;  
                case "TEMPLATE":    $arOrder[$key] = "P.TEMPLATE ".$ord; break;       
                case "ARСHIVE":    $arOrder[$key] = "P.ARСHIVE ".$ord; break;                       
            }        
        }
        if(count($arOrder) <= 0)
        {
            $arOrder["ID"] = "P.ID DESC";
        }
        
        // все таблицы которые есть и могут быть в запросе 
        $b_class_sel = array(
            "ID",
            "ID_EVENT",
            "COUNT_START",            
            "COUNT_END",
            "TEMPLATE",
            "ARСHIVE",
        );
            
        
        //подчистим лишнее из запроса  
        foreach($arSelect as $k => $v) {
            if(!in_array($v, $b_class_sel)) {
                unset($arSelect[$k]);        
            }        
        } 
        if(!empty($arSelect)) {

            $dateFields = array(
                'DATE_CREATE',
                'DATE_SEND'
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
            FROM b_sotbit_mailing_message_template P
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