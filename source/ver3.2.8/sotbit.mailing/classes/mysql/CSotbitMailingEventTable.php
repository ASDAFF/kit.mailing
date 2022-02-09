<?
use Bitrix\Main\Entity;

class CSotbitMailingEventTable extends Entity\DataManager
{
   const LEFT_TO_RIGHT = 'Y';
   const RIGHT_TO_LEFT = 'N';

   public static function getFilePath()
   {
      return __FILE__;
   }

   public static function getTableName()
   {
      return 'b_sotbit_mailing_event';
   }

   public static function getMap()
   {
      return array(
         'ID' => array(
            'data_type' => 'integer',
            'primary' => true,
            'autocomplete' => true,
         ),
         'ACTIVE' => array(
            'data_type' => 'boolean',
            'values' => array(self::RIGHT_TO_LEFT, self::LEFT_TO_RIGHT),
         ),            
         'NAME' => array(
            'data_type' => 'string',
         ),
         'DESCRIPTION' => array(
            'data_type' => 'string',
         ),         
         'SORT' => array(
            'data_type' => 'integer',
         ),
         'MODE' => array(
            'data_type' => 'string',
         ),         
         'TEMPLATE' => array(
            'data_type' => 'string',
         ), 
         'TEMPLATE_PARAMS' => array(
            'data_type' => 'string',
         ),         
         'DATE_LAST_RUN' => array(
            'data_type' => 'datetime',
         ),                           
         'AGENT_ID' => array(
            'data_type' => 'integer',
         ),         
         'AGENT_TIME_START' => array(
            'data_type' => 'integer',
         ),
         'AGENT_TIME_END' => array(
            'data_type' => 'integer',
         ),         
         'EVENT_TYPE_ID' => array(
            'data_type' => 'integer',
         ),  
         'EVENT_SEND_SYSTEM' => array(
            'data_type' => 'string',
         ),                             
      );
   }
}

?>