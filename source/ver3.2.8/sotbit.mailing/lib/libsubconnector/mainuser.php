<?php
/**
 * Bitrix Framework
 * @package sotbit
 * @subpackage mailing
 * @copyright 2015 Sotbit
 */

namespace Sotbit\Mailing\LibSubConnector;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class MainUser extends \Sotbit\Mailing\SubConnector
{
    /**
     * @return string
     */
    public function getName()
    {
        return Loc::getMessage('main_user_connector_name');
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return "mainuser";
    }

    /** @return \CDBResult */
    public function getData()
    {
        $groupId = $this->getFieldValue('GROUP_ID', null);
        $active = $this->getFieldValue('ACTIVE', null);

        $filter = array();
        if($groupId)
            $filter['GROUP_ID'] = $groupId;


        if($active=='Y')
            $filter['USER.ACTIVE'] = $active;
        elseif($active=='N')
            $filter['USER.ACTIVE'] = $active;

            
            
        $userDb = \Bitrix\Main\UserGroupTable::getList(array(
            'select' => array(
                'NAME' => 'USER.NAME', 
                'LAST_NAME' => 'USER.LAST_NAME', 
                'EMAIL' => 'USER.EMAIL', 
                'USER_ID'
            ),
            'filter' => $filter,
            'group' => array('NAME', 'EMAIL', 'USER_ID'),
            'order' => array('USER_ID' => 'ASC'),
        ));

        return new \CDBResult($userDb);
    }  
    
    /**
     * @return array
     */    
    public function getDataResult()
    {
        $result = array();        
        $db = $this->getData();
        while($arRes = $db->Fetch()){   
            $result[] = array(
                'NAME' => $arRes['NAME'],            
                'EMAIL_TO' => $arRes['EMAIL'],
                'USER_ID' => $arRes['USER_ID'],                                   
            );
        }
        return $result;
    }   

    /**
     * @return string
     * @throws ArgumentException
     */
    public function getForm()
    {       
        $groupInput = '<select name="'.$this->getFieldName('GROUP_ID').'">';
        $groupDb = \Bitrix\Main\GroupTable::getList(array(
            'select' => array('ID', 'NAME',),
            'order' => array('C_SORT' => 'ASC', 'NAME' => 'ASC')
        ));
        $groupInput .= '<option value="" >'.GetMessage('sender_connector_user_select_change').'</option>';
        while($group = $groupDb->fetch())
        {
            $inputSelected = ($group['ID'] == $this->getFieldValue('GROUP_ID') ? 'selected' : '');
            $groupInput .= '<option value="'.$group['ID'].'" '.$inputSelected.'>';
            $groupInput .= htmlspecialcharsbx($group['NAME']);
            $groupInput .= '</option>';
        }
        $groupInput .= '</select>';


        $booleanValues = array(
            '' => Loc::getMessage('sender_connector_user_all'),
            'Y' => Loc::getMessage('sender_connector_user_y'),
            'N' => Loc::getMessage('sender_connector_user_n'),
        );

        $activeInput = '<select name="'.$this->getFieldName('ACTIVE').'">';
        foreach($booleanValues as $k => $v)
        {
            $inputSelected = ($k == $this->getFieldValue('ACTIVE') ? 'selected' : '');
            $activeInput .= '<option value="'.$k.'" '.$inputSelected.'>';
            $activeInput .= htmlspecialcharsbx($v);
            $activeInput .= '</option>';
        }
        $activeInput .= '</select>';


        $dateRegInput = CalendarDate(
            $this->getFieldName('DATE_REGISTER'),
            $this->getFieldValue('DATE_REGISTER'),
            $this->getFieldFormName()
        );

        return '
                <tr class="subconnector_tr">
                    <td>'.Loc::getMessage('sender_connector_user_group').'</td>
                    <td>'.$groupInput.'</td>
                </tr>
                <tr class="subconnector_tr">
                    <td>'.Loc::getMessage('sender_connector_user_active').'</td>
                    <td>'.$activeInput.'</td>
                </tr>
        ';
    }
}
