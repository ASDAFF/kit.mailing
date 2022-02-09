<?AddEventHandler("sale", "OnSaleStatusOrder", "OnSaleStatusOrder___order_thank_status_change");   
function OnSaleStatusOrder___order_thank_status_change($ID,$val,$MAILING_LOGIC='order_thank_status_change')
{    
    // ������� �������� �������� ��� ��������
    // START
    $ar_activeMailing = array();
    $db_activeTemp = CKitMailingEvent::GetList(array(),array('ACTIVE'=>'Y','TEMPLATE'=>$MAILING_LOGIC),false,array('ID','TEMPLATE_PARAMS'));
    while($res_activeTemp = $db_activeTemp->Fetch()) {
        $res_activeTemp['TEMPLATE_PARAMS'] = unserialize($res_activeTemp['TEMPLATE_PARAMS']);
        $ar_activeMailing[$res_activeTemp['ID']] = $res_activeTemp;
    }
    //END  
    
    // �������� ��������
    foreach($ar_activeMailing as $kMailing=>$vMailing){
        // �������� ������
        if($vMailing['TEMPLATE_PARAMS']['ORDER_FILLTER_STATUS']==$val){
            CKitMailingTools::StartMailing($kMailing,array('ORDER_FILLTER_ID'=> $ID,'ORDER_FILLTER_STATUS'=>$val));
        }    
    }        
}?>