<?
class CSotbitMailingSubscribers
{  
    public function Add($arFields,$SETTING)
    {
        global $DB;
        $arInsert = $DB->PrepareInsert("b_sotbit_mailing_subscribers", $arFields);
        $strSql =
            "INSERT INTO b_sotbit_mailing_subscribers(".$arInsert[0].") ".
            "VALUES(".$arInsert[1].")";
        $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);       
        $ID = IntVal($DB->LastID());
        
        //������� ��������� ���� �����
        //START
        if($arFields['CATEGORIES_ID']) {
            CSotbitMailingSubscribers::SubscribersAdd(array('SUBSCRIBERS_ID'=>$ID,'CATEGORIES_ID'=>$arFields['CATEGORIES_ID']),$SETTING);            
        }

        //END
        
        return $ID;
    } 
    
    public function Update($ID ,$arFields,$SETTING)
    {
        global $DB;
        $ID = IntVal($ID);  
        $strUpdate = $DB->PrepareUpdate("b_sotbit_mailing_subscribers", $arFields);  
        $strSql = "UPDATE b_sotbit_mailing_subscribers SET ".$strUpdate." WHERE ID=".$ID;
        $res = $DB->Query($strSql, true, $err_mess.__LINE__);
        
        
        //������� ��������� ���� �����
        //START 
        if($arFields['CATEGORIES_ID']) {
            CSotbitMailingSubscribers::SubscribersAdd(array('SUBSCRIBERS_ID'=>$ID,'CATEGORIES_ID'=>$arFields['CATEGORIES_ID']),$SETTING);
        }
        //END      
        
        if($res == false) {
            return false;    
        }
        else {
            return $ID;     
        }
          
    } 
    
    public function checkSubscribers($arFields) {
        
        $fillter = array(
            'EMAIL_TO' => $arFields['EMAIL_TO'],            
        );
        $dbres = CSotbitMailingSubscribers::GetList(array(),$fillter);     
        $arRes  = array();
        while($Res = $dbres->Fetch()) {
            $arRes = $Res;   
        }
        if($arRes) {
            return $arRes;    
        } else {
            return false;
        }
        
    }
    
    public function Delete($ID)
    {
        global $DB;
        $ID = IntVal($ID);
        $strSql = "DELETE FROM b_sotbit_mailing_subscribers WHERE ID='".$DB->ForSql($ID)."'";
        $DB->Query($strSql, true);
        
        //������ �������� ��� ������� ����������
         CSotbitMailingSubscribers::SubscribersDelete($ID);             
        
        return true; 
    }     
        
    public function GetByID($ID)
    {
        global $DB;    
        $ID = IntVal($ID);
        $strSql = "SELECT P.* FROM b_sotbit_mailing_subscribers P WHERE P.ID='".$DB->ForSql($ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
         
        return $arRes;        
    }      
     
    public function GetByMail($MAIL)
    {
        global $DB;    
        $strSql = "SELECT P.* FROM b_sotbit_mailing_subscribers P WHERE P.EMAIL_TO='".$DB->ForSql($MAIL)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        return $arRes;        
    } 
    
   
    public function SubscribersAdd($arFields, $SETTING)
    {
        global $DB;   
        $arFields['SUBSCRIBERS_ID'] = IntVal($arFields['SUBSCRIBERS_ID']); 
        
        if(!is_array($arFields['CATEGORIES_ID'])) {
            $arFields['CATEGORIES_ID'] = array($arFields['CATEGORIES_ID']);   
        } 
        
        if(!is_array($arFields['CATEGORIES_ID']) || empty($arFields['SUBSCRIBERS_ID'])){
            return false;        
        }

        
        //������� ��� ��������� � ������� ���� ���������
        //START
        $subsInfo = CSotbitMailingSubscribers::GetCategoriesBySubscribers($arFields['SUBSCRIBERS_ID']);
        //�������� �� ����������
        if(is_array($subsInfo)) {
            //���� ��� ��������� �� ��������
            if($SETTING['ACTION_DELETE']!='NO_DELETE'){
                foreach($subsInfo as $k => $it) {
                    //���� ��� � ������� ���������� ��������� ������ ��
                    if(!in_array($it, $arFields['CATEGORIES_ID'])){
                        CSotbitMailingSubscribers::SubscribersDeleteByID($k);            
                    }  
                }                    
            }
        
            // ������ ��������� ������� ��� ����
            foreach($arFields['CATEGORIES_ID'] as $k => $it) { 
                //���� ��� � ������� ���������� ��������� ������ ��
                if(in_array($it, $subsInfo)){
                    unset($arFields['CATEGORIES_ID'][$k]);         
                }              
            }              
        }      
        //END
        if(is_array($arFields['CATEGORIES_ID'])){
            foreach($arFields['CATEGORIES_ID'] as $categor) {
                
                $arInsert = $DB->PrepareInsert("b_sotbit_mailing_subscr_categ", array('SUBSCRIBERS_ID'=>$arFields['SUBSCRIBERS_ID'],'CATEGORIES_ID'=>$categor));        
                $strSql =
                    "INSERT INTO b_sotbit_mailing_subscr_categ(".$arInsert[0].") ".
                    "VALUES(".$arInsert[1].")";
                $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);                  
            }                
        }        
               
           
        return true;       
    }    
    //������� ��� ��������� ����������
    public function SubscribersDelete($SUBSCRIBERS_ID)
    {
        global $DB;   
        $SUBSCRIBERS_ID = IntVal($SUBSCRIBERS_ID); 
        $strSql = "DELETE FROM b_sotbit_mailing_subscr_categ WHERE SUBSCRIBERS_ID='".$DB->ForSql($SUBSCRIBERS_ID)."'";
        $DB->Query($strSql, true);   
        return true;       
    }    
    
    //������� ��� �������� ��������� � ����������
    public function CategoriesDelete($CATEGORIES_ID)
    {
        global $DB;   
        $CATEGORIES_ID = IntVal($CATEGORIES_ID); 
        $strSql = "DELETE FROM b_sotbit_mailing_subscr_categ WHERE CATEGORIES_ID='".$DB->ForSql($CATEGORIES_ID)."'";
        $DB->Query($strSql, true);   
        return true;       
    }     
    //������� �������� �� ID
    public function SubscribersDeleteByID($ID)
    {
        global $DB;   
        $ID = IntVal($ID); 
        $strSql = "DELETE FROM b_sotbit_mailing_subscr_categ WHERE ID='".$DB->ForSql($ID)."'";
        $DB->Query($strSql, true);   
        return true;       
    }    
    
    // ������� �������� ��  ���������� � ���������
    public function SubscribersCategoriesDelete($SUBSCRIBERS_ID, $CATEGORIES_ID)
    {
        global $DB;   
        $SUBSCRIBERS_ID = IntVal($SUBSCRIBERS_ID); 
        $CATEGORIES_ID = IntVal($CATEGORIES_ID); 
        $strSql = "DELETE FROM b_sotbit_mailing_subscr_categ WHERE SUBSCRIBERS_ID='".$DB->ForSql($SUBSCRIBERS_ID)."' AND CATEGORIES_ID='".$DB->ForSql($CATEGORIES_ID)."'";
        $DB->Query($strSql, true);   
        return true;       
    }     
   
        
    public function GetSubscribersByCategory($CATEGORIES_ID)
    {
        global $DB;    
        $strSql = "SELECT P.* FROM b_sotbit_mailing_subscr_categ P WHERE P.CATEGORIES_ID='".$DB->ForSql($CATEGORIES_ID)."'";
        $dbRes = $DB->Query($strSql, true);
        
        $arRes = array();
        while($arResitem = $dbRes->Fetch()) {
            $arRes[$arResitem['ID']] = $arResitem['SUBSCRIBERS_ID'];   
        }
        return $arRes;        
    } 
      
    public function GetCategoriesBySubscribers($SUBSCRIBERS_ID)
    {
        global $DB;    
        $SUBSCRIBERS_ID = IntVal($SUBSCRIBERS_ID); 
        $strSql = "SELECT P.* FROM b_sotbit_mailing_subscr_categ P WHERE P.SUBSCRIBERS_ID='".$DB->ForSql($SUBSCRIBERS_ID)."'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = array();
        while($arResitem = $dbRes->Fetch()) {
            $arRes[$arResitem['ID']] = $arResitem['CATEGORIES_ID'];   
        }
        
        return $arRes;        
    } 
    
        
    
    public function GetSubscribersByAllCategory()
    {
        global $DB;    
        $strSql = "SELECT P.* FROM b_sotbit_mailing_subscr_categ P";
        $dbRes = $DB->Query($strSql, true);
        
        $arRes = array();
        while($arResitem = $dbRes->Fetch()) {
            $arRes[$arResitem['CATEGORIES_ID']][$arResitem['ID']] = $arResitem['SUBSCRIBERS_ID'];   
        }
        return $arRes;        
    }     
    
    
    public function GetCategoryByAllSubscribers()
    {
        global $DB;    
        $strSql = "SELECT P.* FROM b_sotbit_mailing_subscr_categ P";
        $dbRes = $DB->Query($strSql, true);
        
        $arRes = array();
        while($arResitem = $dbRes->Fetch()) {
            $arRes[$arResitem['SUBSCRIBERS_ID']][$arResitem['CATEGORIES_ID']] = $arResitem['CATEGORIES_ID'];   
        }
        return $arRes;        
    }          
 
          
          
           
    public function GetList($aSort=Array(), $arFilter=Array(), $arNavStartParams=false, $arSelect=Array())
    {
        global $DB;
        $arSqlSearch = Array();
        $arSqlSearch_h = Array();
        $strSqlSearch = ""; 
 
 
        // ���� ���� ������� ����������� ��  ����������
        // START
        if($arFilter['CATEGORIES_ID'] && empty($arFilter['ID'])){
            if(!is_array($arFilter['CATEGORIES_ID'])) {
                $arFilter['CATEGORIES_ID'] = array($arFilter['CATEGORIES_ID']);   
            }  
            $SubscribersId = array();
            foreach($arFilter['CATEGORIES_ID'] as $categ) {
                $categorSubscribers = CSotbitMailingSubscribers::GetSubscribersByCategory($categ);   
                $SubscribersId = array_merge($SubscribersId,$categorSubscribers);
            }  
            
         
            if($arFilter['ID']) {
                $arFilter['ID'] = array_merge($arFilter['ID'],$SubscribersId);                
            } else {
                $arFilter['ID'] = $SubscribersId;                   
            }
            
            
            if($arFilter['CATEGORIES_ID'] && empty($arFilter['ID'])){
                $arFilter['ID'][] = '0';        
            }
            
            unset($arFilter['CATEGORIES_ID']);
            
        }
        // END
 


 
        if (is_array($arFilter))
        {
            
            
            
            foreach($arFilter as $key=>$val)
            {
                CModule::IncludeModule('iblock');
                $res = CIBlock::MkOperationFilter($key);
                $key = strtoupper($res["FIELD"]);                
                $cOperationType = $res["OPERATION"];
            
                
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
                  
                case ">=DATE_UPDATE":
                    $arSqlSearch[] = "P.DATE_UPDATE >= FROM_UNIXTIME('".MkDateTime(FmtDate($val,"DD.MM.YYYY HH:MI:SS"),"d.m.Y H:i:s")."')";
                    break;   
                case "<=DATE_UPDATE":
                    $arSqlSearch[] = "P.DATE_UPDATE <= FROM_UNIXTIME('".MkDateTime(FmtDate($val,"DD.MM.YYYY HH:MI:SS"),"d.m.Y H:i:s")."')";
                    break;                     
                case ">DATE_UPDATE":
                    $arSqlSearch[] = "P.DATE_UPDATE > FROM_UNIXTIME('".MkDateTime(FmtDate($val,"DD.MM.YYYY HH:MI:SS"),"d.m.Y H:i:s")."')";
                    break;   
                case "<DATE_UPDATE":
                    $arSqlSearch[] = "P.DATE_UPDATE < FROM_UNIXTIME('".MkDateTime(FmtDate($val,"DD.MM.YYYY HH:MI:SS"),"d.m.Y H:i:s")."')";
                    break;             
                case "EMAIL_TO":
                    $arSqlSearch[] = GetFilterQuery("P.EMAIL_TO",$val);
                    break;  
                case "NAME":
                    $arSqlSearch[] = GetFilterQuery("P.NAME",$val);
                    break;                         
                case "USER_ID":         
                    $arSqlSearch[] =  CIBlock::FilterCreate("P.USER_ID", $val, "number", $cOperationType);
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
                case "DATE_CREATE":    $arOrder[$key] = "P.DATE_CREATE ".$ord; break;  
                case "DATE_UPDATE":    $arOrder[$key] = "P.DATE_UPDATE ".$ord; break; 
                case "NAME":    $arOrder[$key] = "P.NAME ".$ord; break;                                  
                case "EMAIL_TO":    $arOrder[$key] = "P.EMAIL_TO ".$ord; break;                                    
                case "USER_ID":    $arOrder[$key] = "P.USER_ID ".$ord; break;  
                case "STATIC_PAGE_SIGNED":    $arOrder[$key] = "P.STATIC_PAGE_SIGNED ".$ord; break;  
                case "STATIC_PAGE_CAME":    $arOrder[$key] = "P.STATIC_PAGE_CAME ".$ord; break;                                                   
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
            "DATE_CREATE",
            "DATE_UPDATE",
            "NAME",               
            "EMAIL_TO",
            "USER_ID",
            "CATEGORIES",
            "STATIC_PAGE_SIGNED",
            "STATIC_PAGE_CAME"
        );
        
       
        
        //��������� ������ �� �������  
        foreach($arSelect as $k => $v) {
            if(!in_array($v, $b_class_sel)) {
                unset($arSelect[$k]);        
            }        
        } 
        if(!empty($arSelect)) {

            $dateFields = array(
                "DATE_CREATE",
                "DATE_UPDATE"
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
        
//        $strSqlSearch['ID'] = 2;
        $strSqlSearch = GetFilterSqlSearch($arSqlSearch);  
        
        
        $strSql = "
            SELECT 
                 ".$strSqlSelect."            
            FROM b_sotbit_mailing_subscribers P
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