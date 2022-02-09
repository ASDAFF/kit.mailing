<?
use Bitrix\Main\Entity;
use Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class CSotbitMailingSectionTable extends Entity\DataManager
{
    public static function getFilePath()
    {
       return __FILE__;
    }

   public static function getTableName()
   {
      return 'b_sotbit_mailing_section';
   }   
   
   
   public static function getTree($_arCat,$parent_id, $level, $pnt){
        global $arCat; 
 
        if (isset($_arCat[$parent_id])) { //≈сли категори€ с таким parent_id существует
            foreach ($_arCat[$parent_id] as $value) { //ќбходим
                
                $level++; //”величиваем уровень вложености
                $pnt .= " . ";
                
                $arCat[][] = array(
                    'ID' => $value["ID"],
                    'NAME' => $pnt.$value["NAME"],
                    'parent_id' => $value["CATEGORY_ID"],
                );

                self::getTree($_arCat,$value["ID"], $level, $pnt);
                $level--;
                $pnt = substr($pnt,3);
            }
        }
        return $arCat;
   }
   
   
   
    public static function getCategoryList(){
        
        //получаем список категорий
        $rsSection = CSotbitMailingSectionTable::getList(array(
            'limit' =>null,
            'offset' => null,
            'select' => array("*"),
            "filter" => array()
        ));

        while($arSection = $rsSection->Fetch())
        {
            $_arCategory[$arSection["CATEGORY_ID"]][] = $arSection;
        }
        
        //дл€ выпадающего списка каталогов
        $_arCategory = self::getTree($_arCategory,0, 0, "");  
        
        //добавим верхний уровень
        $_arCategory[][] = array('ID'=>0,'NAME' => Loc::getMessage("sotbit_mailing_list_title_archive_root"));

        $i=0; $j=0;

        while($i<=count($_arCategory)){

            if($_arCategory[0][0]['ID'] ==0 ) break;
            while($j<=count($_arCategory)){

                if($_arCategory[0][0]['parent_id'] == $_arCategory[$j][0]['ID']){ 
                    
                    $k = ++$j;
                    
                    array_splice($_arCategory,$k,0,array($_arCategory[0]));
                    unset($_arCategory[0]);
                    
                    $temp = array();
                    foreach($_arCategory as $__k => $__arItem){
                        $temp[] =  $__arItem;
                    }
                    
                     $_arCategory = $temp;       
                    
                     break;
                }
              
              $j++;
            }
            
            $i++; $j=0;
            
        }
        
        return $_arCategory;
    }
             
   
   public static function getMap()
   {
      return array(
         'ID' => array(
            'data_type' => 'integer',
            'primary' => true,
            'autocomplete' => true,
            'title' => "ID",
         ),
         'TIMESTAMP_X' => array(
            'data_type' => 'datetime',
            //'required' => true,
            'title' => "TIMESTAMP_X",
         ),
         'DATE_CREATE' => array(
            'data_type' => 'datetime',
            //'required' => true,
            'title' => Loc::getMessage("sotbit_mailing_section_date_title"),
         ),
         'ACTIVE' => array(
            'data_type' => 'boolean',
            'required' => true,
            'values' => array('N', 'Y'),
            'title' => Loc::getMessage("sotbit_mailing_section_active_title"),
         ),
         'SORT' => array(
            'data_type' => 'integer',
            'required' => true,
            'title' => Loc::getMessage("sotbit_mailing_section_sort_title"),
         ),
         'NAME' => array(
            'data_type' => 'string',
            'required' => true,
            'title' => Loc::getMessage("sotbit_mailing_section_name_title"),
         ),
         'DESCRIPTION' => array(
            'data_type' => 'string',
            'title' => Loc::getMessage("sotbit_mailing_section_description_title"),
         ),
         /*
         'PARENT_CATEGORY_ID' => array(
            'data_type' => 'integer',
            'title' => Loc::getMessage("sotbit_mailing_section_parent_title"),
         ),
         */
         'CATEGORY_ID' => array(
            'data_type' => 'integer',
            'title' => Loc::getMessage("sotbit_mailing_section_parent_title"),
         ),

      );
   }
}
?>