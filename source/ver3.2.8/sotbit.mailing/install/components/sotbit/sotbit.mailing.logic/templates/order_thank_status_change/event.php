<?AddEventHandler("sale", "OnSaleStatusOrder", "OnSaleStatusOrder___order_thank_status_change");   
function OnSaleStatusOrder___order_thank_status_change($ID,$val,$MAILING_LOGIC='order_thank_status_change')
{    
    // получим активные сценарии для рассылки
    // START
    $ar_activeMailing = array();
    $db_activeTemp = CSotbitMailingEvent::GetList(array(),array('ACTIVE'=>'Y','TEMPLATE'=>$MAILING_LOGIC),false,array('ID','TEMPLATE_PARAMS'));
    while($res_activeTemp = $db_activeTemp->Fetch()) {
        $res_activeTemp['TEMPLATE_PARAMS'] = unserialize($res_activeTemp['TEMPLATE_PARAMS']);
        $ar_activeMailing[$res_activeTemp['ID']] = $res_activeTemp;
    }
    //END  
    
    // запустим сценарии
    foreach($ar_activeMailing as $kMailing=>$vMailing){
        // проверим статус
        if($vMailing['TEMPLATE_PARAMS']['ORDER_FILLTER_STATUS']==$val){
            CSotbitMailingTools::StartMailing($kMailing,array('ORDER_FILLTER_ID'=> $ID,'ORDER_FILLTER_STATUS'=>$val));                   
        }    
    }        
}?>