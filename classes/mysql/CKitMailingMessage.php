<?

/**
 * Класс для работы с таблицей b_kit_mailing_message, которая хранит параметры сообщений, созданных для отправки.
 *
 * Поля таблицы:
 * <ul>
 * <li><b>ID</b> - Идентификатор сообщения</li>
 * <li><b>ID_EVENT</b> - Идентификатор рассылки</li>
 * <li><b>DATE_CREATE</b> - Дата создания сообщения</li>
 * <li><b>COUNT_RUN</b> - Количество запусков</li>
 * <li><b>SEND</b> - Флаг отправки сообщения</li>
 * <li><b>DATE_SEND</b> - Дата отправки сообщения</li>
 * <li><b>SEND_SYSTEM</b> - Система. осуществляющая отправку сообщения</li>
 * <li><b>SEND_ERROR</b> - Ошибки, возникшие при отправке</li>
 * <li><b>SEND_SYSTEM_MESSAGE_CODE</b> - Код сообщения от почтовой системы</li>
 * <li><b>EMAIL_FROM</b> - Значение поля "от" в письме</li>
 * <li><b>EMAIL_TO</b> - Значение поля "Кому" сообщения</li>
 * <li><b>BCC</b> - Скрытая копия</li>
 * <li><b>PARAM_MESSEGE</b> - Сериализованные параметры сообщения</li>
 * <li><b>STATIC_USER_OPEN</b> - Было ли открыто письмо, данные для статистики</li>
 * <li><b>STATIC_USER_OPEN_DATE</b> - Дата открытия письма</li>
 * <li><b>STATIC_USER_BACK</b> - Флаг, вернулся ли пользователь</li>
 * <li><b>STATIC_USER_BACK_DATE</b> - Дата возвращения пользователя</li>
 * <li><b>STATIC_USER_ID</b> - Идентификатор пользователя</li>
 * <li><b>STATIC_SALE_UID</b></li>
 * <li><b>STATIC_GUEST_ID</b> - Идентификатор гостевого пользователя</li>
 * <li><b>STATIC_PAGE_START</b></li>
 * <li><b>STATIC_ORDER_ID</b> - Идентификатор заказа</li>
 * </ul>
 */
class CKitMailingMessage
{
    public function Add($arFields)
    {
        global $DB;

        //составим массив для таблицы сообщений
        $AddTableMessage = array(
            'MESSEGE_PARAMETR' => $arFields['MESSEGE_PARAMETR'],
            'MESSEGE' => $arFields['MESSEGE'],
            'SUBJECT' => $arFields['SUBJECT']
        );
        unset($arFields['MESSEGE_PARAMETR']);
        unset($arFields['MESSEGE']);
        unset($arFields['SUBJECT']);

        $arInsert = $DB->PrepareInsert("b_kit_mailing_message", $arFields);
        $strSql = "INSERT INTO b_kit_mailing_message(" . $arInsert[0] . ") " . "VALUES(" . $arInsert[1] . ")";
        $DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
        $ID = IntVal($DB->LastID());

        if($ID)
        {
            $AddTableMessage['ID_MESSEGE'] = $ID;
            CKitMailingMessageText::Add($AddTableMessage);
        }

        return $ID;
    }

    public function Update($ID, $arFields)
    {
        global $DB;
        $ID = IntVal($ID);
        $strUpdate = $DB->PrepareUpdate("b_kit_mailing_message", $arFields);
        $strSql = "UPDATE b_kit_mailing_message SET " . $strUpdate . " WHERE ID=" . $ID;
        $res = $DB->Query($strSql, true, $err_mess . __LINE__);
        if($res == false)
        {
            return false;
        }
        else
        {
            return $ID;
        }
    }

    public function GetByID($ID)
    {
        global $DB;
        $ORDER_ID = IntVal($ID);
        $strSql = "SELECT P.* FROM b_kit_mailing_message P WHERE P.ID='" . $DB->ForSql($ID) . "'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();

        //получим данные о сообщение
        //START
        $arrMessege = CKitMailingMessageText::GetTextByMessegeID($ID, $arRes['ID_EVENT'], $arRes['COUNT_RUN']);
        if($arrMessege['SUBJECT'])
        {
            $arRes['SUBJECT'] = $arrMessege['SUBJECT'];
        }

        if($arrMessege['MESSEGE'])
        {
            $arRes['MESSEGE'] = $arrMessege['MESSEGE'];
        }
        //END        

        return $arRes;
    }

    public function GetNextID()
    {
        global $DB;
        $ORDER_ID = IntVal($ID);
        $strSql = "SELECT MAX(id) FROM b_kit_mailing_message";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        $arRes = $arRes['MAX(id)'] + 1;
        return $arRes;
    }

    public function GetByIDInfoSend($ID)
    {
        global $DB;
        $ORDER_ID = IntVal($ID);
        $strSql = "SELECT P.ID,P.ID_EVENT,P.SEND,P.SEND_SYSTEM,P.COUNT_RUN,P.EMAIL_FROM,P.EMAIL_TO,P.BCC,P.PARAM_MESSEGE FROM b_kit_mailing_message P WHERE P.ID='" . $DB->ForSql($ID) . "'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();

        //получим данные с таблицы
        //START
        $arrMessege = CKitMailingMessageText::GetTextByMessegeID($ID, $arRes['ID_EVENT'], $arRes['COUNT_RUN']);
        if($arrMessege['SUBJECT'])
        {
            $arRes['SUBJECT'] = $arrMessege['SUBJECT'];
        }

        if($arrMessege['MESSEGE'])
        {
            $arRes['MESSEGE'] = $arrMessege['MESSEGE'];
        }
        //END

        return $arRes;
    }

    public function GetByIDInfoStatic($ID)
    {
        global $DB;
        $ORDER_ID = IntVal($ID);
        $strSql = "SELECT P.STATIC_USER_OPEN,P.STATIC_USER_OPEN_DATE,P.STATIC_USER_BACK,P.STATIC_USER_BACK_DATE,P.STATIC_USER_ID,P.STATIC_SALE_UID,P.STATIC_GUEST_ID,P.STATIC_PAGE_START,P.STATIC_ORDER_ID FROM b_kit_mailing_message P WHERE P.ID='" . $DB->ForSql($ID) . "'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        return $arRes;
    }

    public function GetByIDInfoParamMessage($ID)
    {
        global $DB;
        $ORDER_ID = IntVal($ID);
        $strSql = "SELECT P.PARAM_MESSEGE FROM b_kit_mailing_message P WHERE P.ID='" . $DB->ForSql($ID) . "'";
        $dbRes = $DB->Query($strSql, true);
        $arRes = $dbRes->Fetch();
        $PARAM_MESSEGE = unserialize($arRes['PARAM_MESSEGE']);
        return $PARAM_MESSEGE;
    }

    public function Delete($ID)
    {
        global $DB;
        $ID = IntVal($ID);
        $strSql = "DELETE FROM b_kit_mailing_message WHERE ID='" . $DB->ForSql($ID) . "'";
        $DB->Query($strSql, true);
        //удалим сообщение из темы
        //CKitMailingMessageText::DeleteByMessegeId($ID);
        $strSql = "DELETE FROM b_kit_mailing_message_text WHERE ID_MESSEGE='" . $DB->ForSql($ID) . "'";
        $DB->Query($strSql, true);

        return true;
    }

    public function GetList($aSort = Array(), $arFilter = Array(), $arNavStartParams = false, $arSelect = Array())
    {
        global $DB;
        $arSqlSearch = Array();
        $arSqlSearch_h = Array();
        $strSqlSearch = "";

        if(is_array($arFilter))
        {
            foreach($arFilter as $key => $val)
            {
                if(!is_array($val) && (strlen($val) <= 0 || $val == "NOT_REF"))
                    continue;

                switch(strtoupper($key))
                {
                    case "ID":
                        if(is_array($val))
                        {
                            $val = implode(" | ", $val);
                        }
                        $arSqlSearch[] = GetFilterQuery("P.ID", $val, 'N');
                        break;
                    case "ID_EVENT":
                        if(is_array($val))
                        {
                            $val = implode(" | ", $val);
                        }
                        $arSqlSearch[] = GetFilterQuery("P.ID_EVENT", $val, 'N');
                        break;

                    case ">=DATE_CREATE":
                        $arSqlSearch[] = "P.DATE_CREATE >= FROM_UNIXTIME('" . MkDateTime(FmtDate($val, "DD.MM.YYYY HH:MI:SS"), "d.m.Y H:i:s") . "')";
                        break;
                    case "<=DATE_CREATE":
                        $arSqlSearch[] = "P.DATE_CREATE <= FROM_UNIXTIME('" . MkDateTime(FmtDate($val, "DD.MM.YYYY HH:MI:SS"), "d.m.Y H:i:s") . "')";
                        break;
                    case ">DATE_CREATE":
                        $arSqlSearch[] = "P.DATE_CREATE > FROM_UNIXTIME('" . MkDateTime(FmtDate($val, "DD.MM.YYYY HH:MI:SS"), "d.m.Y H:i:s") . "')";
                        break;
                    case "<DATE_CREATE":
                        $arSqlSearch[] = "P.DATE_CREATE < FROM_UNIXTIME('" . MkDateTime(FmtDate($val, "DD.MM.YYYY HH:MI:SS"), "d.m.Y H:i:s") . "')";
                        break;

                    case ">COUNT_RUN":
                        $arSqlSearch[] = "P.COUNT_RUN > $val";
                        break;
                    case "<COUNT_RUN":
                        $arSqlSearch[] = "P.COUNT_RUN < $val";
                        break;
                    case ">=COUNT_RUN":
                        $arSqlSearch[] = "P.COUNT_RUN >= $val";
                        break;
                    case "<=COUNT_RUN":
                        $arSqlSearch[] = "P.COUNT_RUN <= $val";
                        break;

                    case "SEND":
                        $arSqlSearch[] = GetFilterQuery("P.SEND", $val);
                        break;

                    case ">=DATE_SEND":
                        $arSqlSearch[] = "P.DATE_SEND >= FROM_UNIXTIME('" . MkDateTime(FmtDate($val, "DD.MM.YYYY HH:MI:SS"), "d.m.Y H:i:s") . "')";
                        break;
                    case "<=DATE_SEND":
                        $arSqlSearch[] = "P.DATE_SEND <= FROM_UNIXTIME('" . MkDateTime(FmtDate($val, "DD.MM.YYYY HH:MI:SS"), "d.m.Y H:i:s") . "')";
                        break;
                    case ">DATE_SEND":
                        $arSqlSearch[] = "P.DATE_SEND > FROM_UNIXTIME('" . MkDateTime(FmtDate($val, "DD.MM.YYYY HH:MI:SS"), "d.m.Y H:i:s") . "')";
                        break;
                    case "<DATE_SEND":
                        $arSqlSearch[] = "P.DATE_SEND < FROM_UNIXTIME('" . MkDateTime(FmtDate($val, "DD.MM.YYYY HH:MI:SS"), "d.m.Y H:i:s") . "')";
                        break;

                    case "SEND_SYSTEM":
                        $arSqlSearch[] = GetFilterQuery("P.SEND_SYSTEM", $val);
                        break;
                    case "EMAIL_FROM":
                        $arSqlSearch[] = GetFilterQuery("P.EMAIL_FROM", $val);
                        break;
                    case "EMAIL_TO":
                        $arSqlSearch[] = GetFilterQuery("P.EMAIL_TO", $val);
                        break;
                    case "PARAM_MESSEGE":
                        $arSqlSearch[] = GetFilterQuery("P.PARAM_MESSEGE", $val);
                        break;
                    case "PARAM_1":
                        $arSqlSearch[] = GetFilterQuery("P.PARAM_1", $val);
                        break;
                    case "PARAM_2":
                        $arSqlSearch[] = GetFilterQuery("P.PARAM_1", $val);
                        break;
                    case "PARAM_3":
                        $arSqlSearch[] = GetFilterQuery("P.PARAM_1", $val);
                        break;
                    case "STATIC_USER_OPEN":
                        $arSqlSearch[] = GetFilterQuery("P.STATIC_USER_OPEN", $val);
                        break;
                    case "STATIC_USER_BACK":
                        $arSqlSearch[] = GetFilterQuery("P.STATIC_USER_BACK", $val);
                        break;
                    case "STATIC_USER_ID":
                        $arSqlSearch[] = GetFilterQuery("P.STATIC_USER_ID", $val);
                        break;
                    case "STATIC_SALE_UID":
                        $arSqlSearch[] = GetFilterQuery("P.STATIC_SALE_UID", $val);
                        break;
                    case "STATIC_GUEST_ID":
                        $arSqlSearch[] = GetFilterQuery("P.STATIC_GUEST_ID", $val);
                        break;
                    case "STATIC_PAGE_START":
                        $arSqlSearch[] = GetFilterQuery("P.STATIC_PAGE_START", $val);
                        break;
                    case "STATIC_ORDER_ID":
                        $arSqlSearch[] = GetFilterQuery("P.STATIC_ORDER_ID", $val);
                        break;
                }
            }
        }

        //сортировка запроса
        $arOrder = array();
        foreach($aSort as $key => $ord)
        {
            $key = strtoupper($key);
            $ord = (strtoupper($ord) <> "ASC" ? "DESC" : "ASC");
            switch($key)
            {
                case "ID":
                    $arOrder[$key] = "P.ID " . $ord;
                    break;
                case "ID_EVENT":
                    $arOrder[$key] = "P.ID_EVENT " . $ord;
                    break;
                case "DATE_CREATE":
                    $arOrder[$key] = "P.DATE_CREATE " . $ord;
                    break;
                case "COUNT_RUN":
                    $arOrder[$key] = "P.COUNT_RUN " . $ord;
                    break;
                case "SEND":
                    $arOrder[$key] = "P.SEND " . $ord;
                    break;
                case "DATE_SEND":
                    $arOrder[$key] = "P.DATE_SEND " . $ord;
                    break;
                case "SEND_SYSTEM":
                    $arOrder[$key] = "P.SEND_SYSTEM " . $ord;
                    break;
                case "EMAIL_FROM":
                    $arOrder[$key] = "P.EMAIL_FROM " . $ord;
                    break;
                case "EMAIL_TO":
                    $arOrder[$key] = "P.EMAIL_TO " . $ord;
                    break;
                case "PARAM_MESSEGE":
                    $arOrder[$key] = "P.PARAM_MESSEGE " . $ord;
                    break;
                case "PARAM_1":
                    $arOrder[$key] = "P.PARAM_1 " . $ord;
                    break;
                case "PARAM_2":
                    $arOrder[$key] = "P.PARAM_2 " . $ord;
                    break;
                case "PARAM_3":
                    $arOrder[$key] = "P.PARAM_3 " . $ord;
                    break;
                case "STATIC_USER_OPEN":
                    $arOrder[$key] = "P.STATIC_USER_OPEN " . $ord;
                    break;
                case "STATIC_USER_BACK":
                    $arOrder[$key] = "P.STATIC_USER_BACK " . $ord;
                    break;
                case "STATIC_USER_BACK_DATE":
                    $arOrder[$key] = "P.STATIC_USER_BACK_DATE " . $ord;
                    break;
                case "STATIC_USER_ID":
                    $arOrder[$key] = "P.STATIC_USER_ID " . $ord;
                    break;
                case "STATIC_SALE_UID":
                    $arOrder[$key] = "P.STATIC_SALE_UID " . $ord;
                    break;
                case "STATIC_GUEST_ID":
                    $arOrder[$key] = "P.STATIC_GUEST_ID " . $ord;
                    break;
                case "STATIC_PAGE_START":
                    $arOrder[$key] = "P.STATIC_PAGE_START " . $ord;
                    break;
                case "STATIC_ORDER_ID":
                    $arOrder[$key] = "P.STATIC_ORDER_ID " . $ord;
                    break;
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
            "DATE_CREATE",
            "COUNT_RUN",
            "SEND",
            "DATE_SEND",
            "SEND_SYSTEM",
            "SEND_SYSTEM_MESSEGE_CODE",
            "EMAIL_FROM",
            "EMAIL_TO",
            "PARAM_MESSEGE",
            "PARAM_1",
            "PARAM_2",
            "PARAM_3",
            "STATIC_USER_OPEN",
            "STATIC_USER_OPEN_DATE",
            "STATIC_USER_BACK",
            "STATIC_USER_BACK_DATE",
            "STATIC_USER_ID",
            "STATIC_SALE_UID",
            "STATIC_GUEST_ID",
            "STATIC_PAGE_START",
            "STATIC_ORDER_ID",
            "BCC"
        );


        //подчистим лишнее из запроса  
        foreach($arSelect as $k => $v)
        {
            if(!in_array($v, $b_class_sel))
            {
                unset($arSelect[$k]);
            }
        }
        if(!empty($arSelect))
        {

            $dateFields = array(
                'DATE_CREATE',
                'DATE_SEND'
            );
            $iOrder = 0;
            foreach($arSelect as $selectVal)
            {
                if($iOrder != 0)
                {
                    $strSqlSelect .= ",";
                }
                // если поле с датой
                if(in_array($selectVal, $dateFields))
                {
                    $strSqlSelect .= "
                        " . $DB->DateToCharFunction("P." . $selectVal) . " " . $selectVal . " 
                    ";
                }
                else
                {
                    $strSqlSelect .= "
                        P." . $selectVal . "     
                    ";
                }
                $iOrder++;
            }
        }
        else
        {
            $strSqlSelect = "P.*";
        }

        $strSqlOrder = " ORDER BY " . implode(", ", $arOrder);
        $strSqlSearch = GetFilterSqlSearch($arSqlSearch);
        $strSql = "
            SELECT 
                 " . $strSqlSelect . "            
            FROM b_kit_mailing_message P
            WHERE
            " . $strSqlSearch . "
        ";
        if(count($arSqlSearch_h) > 0)
        {
            $strSqlSearch_h = GetFilterSqlSearch($arSqlSearch_h);
            $strSql = $strSql . " HAVING " . $strSqlSearch_h;
        }
        $strSql .= $strSqlOrder;


        $res = $DB->Query($strSql, false, "File: " . __FILE__ . "<br>Line: " . __LINE__);
        $res->is_filtered = (IsFiltered($strSqlSearch));
        return $res;
    }
}

?>