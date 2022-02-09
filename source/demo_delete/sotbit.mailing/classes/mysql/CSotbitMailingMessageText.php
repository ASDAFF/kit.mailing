<?
class CSotbitMailingMessageText
{  
    public function Add($arFields)
    {
        global $DB;
        $arInsert = $DB->PrepareInsert("b_sotbit_mailing_message_text", $arFields);
        $strSql =
            "INSERT INTO b_sotbit_mailing_message_text(".$arInsert[0].") ".
            "VALUES(".$arInsert[1].")";
        $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);       
        $ID = IntVal($DB->LastID());
        return $ID;
    } 
    
    public function Update($ID ,$arFields)
    {
        global $DB;
        $ID = IntVal($ID);  
        $strUpdate = $DB->PrepareUpdate("b_sotbit_mailing_message_text", $arFields);  
        $strSql = "UPDATE b_sotbit_mailing_message_text SET ".$strUpdate." WHERE ID=".$ID;
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
        $strSql = "SELECT P.* FROM b_sotbit_mailing_message_text P WHERE P.ID='".$DB->ForSql($ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        return $arRes;        
    }      
     
    public function GetByMessegeID($ID)
    {
        global $DB;    
        $ORDER_ID = IntVal($ID);
        $strSql = "SELECT P.* FROM b_sotbit_mailing_message_text P WHERE P.ID_MESSEGE='".$DB->ForSql($ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        return $arRes;        
    }      
        
        
    public function GetTextByMessegeID($ID, $EVENT_ID, $COUNT_RUN)
    {
        global $DB;    
        $ORDER_ID = IntVal($ID);
        $strSql = "SELECT P.* FROM b_sotbit_mailing_message_text P WHERE P.ID_MESSEGE='".$DB->ForSql($ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
          
  
        if(empty($arRes['MESSEGE'])) {
            $arRes['MESSEGE_PARAMETR'] = unserialize($arRes['MESSEGE_PARAMETR']);
            if(empty($arRes['MESSEGE_PARAMETR']['MAILING_MESSAGE'])){
                $arRes['MESSEGE_PARAMETR']['MAILING_MESSAGE'] = $arRes['ID_MESSEGE'];        
            } 
            if(empty($arRes['MESSEGE_PARAMETR']['MAILING_EVENT_ID']) && $EVENT_ID){
                $arRes['MESSEGE_PARAMETR']['MAILING_EVENT_ID'] = $EVENT_ID;        
            }                        
            $eventTemplate = CSotbitMailingHelp::GetEventTemplate($EVENT_ID);   
            $TEMPLATE = '';            
            foreach($eventTemplate as $event){
                if($event['COUNT_START'] < $COUNT_RUN && $event['COUNT_END'] >= $COUNT_RUN){
                    $TEMPLATE = $event["TEMPLATE"];
                    break; 
               }
            } 
            
            
            if(empty($TEMPLATE)){
                foreach($eventTemplate as $event){   
                    $TEMPLATE = $event["TEMPLATE"]; 
                    break; 
                }   
            }
            
            
            if($TEMPLATE){
                //для авторизации
                if($arRes['MESSEGE_PARAMETR']['USER_AUTH']) {                   
                    $arRes['MESSEGE_PARAMETR']["MAILING_MESSAGE"] = $arRes['MESSEGE_PARAMETR']["MAILING_MESSAGE"].'&USER_AUTH='.$arRes['MESSEGE_PARAMETR']['USER_AUTH'];          
                }         
                //для отписки
                $arRes['MESSEGE_PARAMETR']['MAILING_UNSUBSCRIBE'] = $arRes['ID_MESSEGE'].'||'.$arRes['MESSEGE_PARAMETR']['MAILING_EVENT_ID'];        
                
                
                //если есть массивы с id товаров для компонентов переведем их в вид для чтения
                //START
                if($arRes['MESSEGE_PARAMETR']['VIEWED_PRODUCT_ID']){
                    $arRes['MESSEGE_PARAMETR']['VIEWED_PRODUCT_ID_ARRAY'] = $arRes['MESSEGE_PARAMETR']['VIEWED_PRODUCT_ID'];
                    $arRes['MESSEGE_PARAMETR']['VIEWED_PRODUCT_ID'] = serialize($arRes['MESSEGE_PARAMETR']['VIEWED_PRODUCT_ID']);        
                }
                if($arRes['MESSEGE_PARAMETR']['RECOMMEND_PRODUCT_ID']){
                    $arRes['MESSEGE_PARAMETR']['RECOMMEND_PRODUCT_ID_ARRAY'] = $arRes['MESSEGE_PARAMETR']['RECOMMEND_PRODUCT_ID'];
                    $arRes['MESSEGE_PARAMETR']['RECOMMEND_PRODUCT_ID'] = serialize($arRes['MESSEGE_PARAMETR']['RECOMMEND_PRODUCT_ID']);        
                }
                if($arRes['MESSEGE_PARAMETR']['FORGET_BASKET_ID']){
                    $arRes['MESSEGE_PARAMETR']['FORGET_BASKET_ID_ARRAY'] = $arRes['MESSEGE_PARAMETR']['FORGET_BASKET_ID'];
                    $arRes['MESSEGE_PARAMETR']['FORGET_BASKET_ID'] = serialize($arRes['MESSEGE_PARAMETR']['FORGET_BASKET_ID']);        
                }                                
                //END
                
                $message = \Bitrix\Main\Mail\Internal\EventMessageTable::replaceTemplateToPhp($TEMPLATE); 
                $themeCompiler = Bitrix\Main\Mail\EventMessageThemeCompiler::createInstance(null, $message);
                if(is_array($arRes['MESSEGE_PARAMETR'])){
                    $themeCompiler->setParams($arRes['MESSEGE_PARAMETR']);                      
                }
                $themeCompiler->execute();
                $arRes['MESSEGE'] = $themeCompiler->getResult();  
                $arRes['MESSEGE'] = CSotbitMailingHelp::ReplaceVariables($arRes['MESSEGE'], $arRes['MESSEGE_PARAMETR']);      
            }            
        
        }
        
        
        return $arRes;        
    } 
            
         
    public function Delete($ID)
    {
        global $DB;
        $ID = IntVal($ID);
        $strSql = "DELETE FROM b_sotbit_mailing_message_text WHERE ID='".$DB->ForSql($ID)."'";
        $DB->Query($strSql, true);
        return true; 
    }
      
    public function DeleteByMessegeId($ID)
    {
        global $DB;
        $ID = IntVal($ID);
        $strSql = "DELETE FROM b_sotbit_mailing_message_text WHERE ID_MESSEGE='".$DB->ForSql($ID)."'";
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
                    $arSqlSearch[] = GetFilterQuery("P.ID",$val,'N');
                    break;
                case "ID_MESSEGE":
                    $arSqlSearch[] = GetFilterQuery("P.ID_MESSEGE",$val,'N');   
                    break;
                case "SUBJECT":
                    $arSqlSearch[] = GetFilterQuery("P.SUBJECT",$val);
                    break;                        
                case "MESSEGE_PARAMETR":
                    $arSqlSearch[] = GetFilterQuery("P.MESSEGE_PARAMETR",$val);
                    break;                                                        
                case "MESSEGE":
                    $arSqlSearch[] = GetFilterQuery("P.MESSEGE",$val);
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
                case "ID_MESSEGE":    $arOrder[$key] = "P.ID_MESSEGE ".$ord; break; 
                case "SUBJECT":    $arOrder[$key] = "P.SUBJECT ".$ord; break; 
                case "MESSEGE_PARAMETR":    $arOrder[$key] = "P.MESSEGE_PARAMETR ".$ord; break;  
                case "MESSEGE":    $arOrder[$key] = "P.MESSEGE ".$ord; break;       
                       
            }        
        }
        if(count($arOrder) <= 0)
        {
            $arOrder["ID"] = "P.ID DESC";
        }
        
        // все таблицы которые есть и могут быть в запросе 
        $b_class_sel = array(
            "ID",
            "ID_MESSEGE",
            "SUBJECT",
            "MESSEGE_PARAMETR",
            "MESSEGE",
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
            FROM b_sotbit_mailing_message_text P
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
