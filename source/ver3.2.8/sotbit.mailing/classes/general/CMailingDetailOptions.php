<?
IncludeModuleLangFile(__FILE__); 

/**
* ����� ������������ ��� ���������� ������ ������� �������� � ������ ��������������
*
*/
class CMailingDetailOptions        
{
    public $arCurOptionValues = array();
    
    private $module_id = '';
    private $arTabs = array();
    private $arGroups = array();
    private $arOptions = array();
    private $arSettings = array();
    private $need_access_tab = false;
    
    /**
    * ����������� ������
    * 
    * ����������� �������������� �������� �������� ������ ����������,
    * ��������� ��������� ������ ����� $arCurOptionValues ���������� �� ��������� 
    * 
    * @param string $module_id ������������� ������
    *  
    * @param array $arTabs ������ ������� ��� �������� ����������������� �������� ��������
    *   
    * @param array $arGroups ������ ����� �������� ��������
    *   
    * @param array $arOptions ������ �����
    *  
    * @param array $arSettings ������ �������������� �������� 
    * 
    * @return void
    *
    */
    public function CMailingDetailOptions($module_id, $arTabs, $arGroups, $arOptions, $arSettings = array())
    {
        $this->module_id = $module_id;
        $this->arTabs = $arTabs;
        $this->arGroups = $arGroups;
        $this->arOptions = $arOptions;
        $this->arSettings = $arSettings; 

        $this->GetCurOptionValues();
    }
    
    private function SaveOptions()
    {
        global $APPLICATION;
        $CONS_RIGHT = $APPLICATION->GetGroupRight($this->module_id); 
        if($CONS_RIGHT <= "R") {
            echo CAdminMessage::ShowMessage(GetMessage($this->module_id.'_ERROR_RIGTH'));
            return false;    
        }
        
        foreach($this->arOptions as $opt => $arOptParams)
        {
            /*
            if($arOptParams['TYPE'] != 'CUSTOM')
            {
                $val = $_REQUEST[$opt];
    
                if($arOptParams['TYPE'] == 'CHECKBOX' && $val != 'Y')
                    $val = 'N';
                elseif(is_array($val))
                    $val = serialize($val);

               // COption::SetOptionString($this->module_id, $opt, $val);
            }
            */
            
        }
        
    }
        
    /**
    * ����� ��������� ��������� ������ $arCurOptionValues ���������� �� ��������� 
    * 
    * �������� �� ��������� ������� ��� �������� �������� � ������ DEFAULT �� �������� $arOptions
    * 
    * @return void
    *
    */
    private function GetCurOptionValues()
    {
        foreach($this->arOptions as $opt => $arOptParams)
        {
            //echo $opt; 
            //printr();
            //$this->arCurOptionValues[$opt] = $opt=="CATEGORY_ID"?"S".$arOptParams['DEFAULT']:$arOptParams['DEFAULT'];
            $this->arCurOptionValues[$opt] = $arOptParams['DEFAULT'];
        }
    }
    
    /**
    * ����� ���������� HTML �������� ����������� �������� ����������������� �������� 
    * 
    * ����� ���������� ������������ ����������, �������� � �������� ��������� ������ (�� �������� ��������������� � ������������).
    * ����� � ����������� �� ���� ��������������� ����� ����� ������������ �������������� ���������.
    * ��� �����, ��� ��������� ����� ���������� ��������� ������������ ������ 'fileman', ���� �� ������ ������������ �����������
    * ��������� ���������� � ������ ������.
    * 
    * @return string HTML �������� �������� �����������������.
    *
    */
    public function ShowHTML()
    {
        global $APPLICATION;

        $arP = array();
        
        //file_put_contents(dirname(__FILE__).'/parentID.txt', $parentID,FILE_APPEND);
        //file_put_contents(dirname(__FILE__).'/request.txt', print_r($_REQUEST,true));
          
        foreach($this->arGroups as $group_id => $group_params)
            $arP[$group_params['TAB']][$group_id] = array();        
        
        if(is_array($this->arOptions))
        {
            
            //printr($this->arOptions);
            foreach($this->arOptions as $option => $arOptParams)
            {          
               
                $val = $this->arCurOptionValues[$option];
                
                //file_put_contents(dirname(__FILE__).'/val.txt', print_r($val,true),FILE_APPEND);
                //file_put_contents(dirname(__FILE__).'/keys.txt', $option."\n",LOCK_EX);
                //file_put_contents(dirname(__FILE__).'/arOptParams.txt', print_r($arOptParams,true),FILE_APPEND);
                                                 
                if($arOptParams['SORT'] < 0 || !isset($arOptParams['SORT']))
                    $arOptParams['SORT'] = 0;
                
                $label = (isset($arOptParams['TITLE']) && $arOptParams['TITLE'] != '') ? $arOptParams['TITLE'] : '';
                $opt = htmlspecialcharsEx($option);

                $submit_refresh = '';
                if($arOptParams['REFRESH'] == 'Y') {
                     $submit_refresh = 'onchange=\'$(this).parents("form").submit();\' ';    
                }
                
                
                        // ���� ����� ������� ������� ��������
                        if($arOptParams['DEFAULT_TYPE']=='DELIVERY_ID' && CModule::IncludeModule("sale")) {
                             
                              
                            if($arOptParams['TYPE']=='SELECT') {
                                $arOrderDeliveryId = array('' => GetMessage($this->module_id.'_SELECT_PARAM_ALL'));                                 
                            }
                               
                            
 
                            //������� ������������������ ������ ��������       
                            $rsDeliveryServicesList = CSaleDeliveryHandler::GetList(array(), array('ACTIVE'=>'Y')); 
                            while ($arDeliveryService = $rsDeliveryServicesList->GetNext())
                            {    
                                if (!is_array($arDeliveryService) || !is_array($arDeliveryService["PROFILES"])) continue;
                                foreach ($arDeliveryService["PROFILES"] as $profile_id => $arDeliveryProfile)
                                {
                                    $delivery_id = $arDeliveryService["SID"].":".$profile_id;
                                    $arOrderDeliveryId[$delivery_id] = '['.$delivery_id.'] '.$arDeliveryService["NAME"].": ".$arDeliveryProfile["TITLE"];     
                                }  
                            } 
                            //������� ������� ������ ��������   
                            $dbDelivery = CSaleDelivery::GetList(
                                array("SORT"=>"ASC", "NAME"=>"ASC"),
                                array(
                                    "ACTIVE" => "Y",
                                )
                            );
                            while ($arDelivery = $dbDelivery->GetNext())
                            {
                                $arOrderDeliveryId[$arDelivery["ID"]] = '['.$arDelivery["ID"].'] '.$arDelivery["NAME"];
                            } 
                            
                            foreach($arOrderDeliveryId as $k => $v) {
                                $arOptParams['VALUES']['REFERENCE_ID'][] = $k;
                                $arOptParams['VALUES']['REFERENCE'][] = $v;          
                            }                                  
                        } 
                                       
                
                switch($arOptParams['TYPE'])
                {
                    case 'CHECKBOX':
                        $input = '
                        <input type="hidden" name="'.$opt.'" value="N" />  
                        <input  type="checkbox" name="'.$opt.'" id="'.$opt.'" value="Y"'.($val == 'Y' ? ' checked' : '').' '.$submit_refresh.' />';
                        break;                                                                                                                                  
                    case 'TEXT':
                        if(!isset($arOptParams['COLS']))
                            $arOptParams['COLS'] = 25;
                        if(!isset($arOptParams['ROWS'])) {
                            $arOptParams['ROWS'] = 5;                            
                        }
                        $input = '<textarea cols="'.$arOptParams['COLS'].'" rows="'.$arOptParams['ROWS'].'" name="'.$opt.'">'.htmlspecialcharsEx($val).'</textarea>';
                        break;
                    case 'HTML':    
                              $input = '';
                         break;
                    case 'INT_FROM_TO':
                         $input = '';
                         break;                         
                    case 'USER_ID':    
                              $input = '';  
                         break;                            
                    case 'TABS_INFO':  
                         $input = '';        
                        break;
                    case 'DATE':    
                        $input = CalendarDate($opt, htmlspecialcharsbx($val), $this->module_id ,20);
                        break;                        
                    case 'SELECT':
                        $input = SelectBoxFromArray($opt, $arOptParams['VALUES'], $val, '', $submit_refresh, false );
                        break;
                    case 'MSELECT':
                        if($arOptParams['WIDTH']) {
                           $selHTML =  'style="width: '.$arOptParams['WIDTH'].'px"';
                        }
                        if(empty($arOptParams['VALUES'])){
                            $arOptParams['VALUES'] = array();        
                        }
                        
                        $input = SelectBoxMFromArray($opt.'[]', $arOptParams['VALUES'], $val,'',false,$arOptParams['SIZE'], $selHTML);

                        break;
                    case 'COLORPICKER':
                        if(!isset($arOptParams['FIELD_SIZE']))
                            $arOptParams['FIELD_SIZE'] = 25;
                        ob_start();
                        echo     '<input id="__CP_PARAM_'.$opt.'" name="'.$opt.'" size="'.$arOptParams['FIELD_SIZE'].'" value="'.htmlspecialcharsEx($val).'" type="text" style="float: left;" '.($arOptParams['FIELD_READONLY'] == 'Y' ? 'readonly' : '').' />
                                <script>
                                    function onSelect_'.$opt.'(color, objColorPicker)
                                    {
                                        var oInput = BX("__CP_PARAM_'.$opt.'");
                                        oInput.value = color;
                                    }
                                </script>';
                        $APPLICATION->IncludeComponent('bitrix:main.colorpicker', '', Array(
                                'SHOW_BUTTON' => 'Y',
                                'ID' => $opt,
                                'NAME' => GetMessage("sns.tools1c_choice_color"),
                                'ONSELECT' => 'onSelect_'.$opt
                            ), false
                        );
                        $input = ob_get_clean();
                        if($arOptParams['REFRESH'] == 'Y')
                            $input .= '<input type="submit" name="refresh" value="OK" />';
                        break;
                    case 'FILE':
                        if(!isset($arOptParams['FIELD_SIZE']))
                            $arOptParams['FIELD_SIZE'] = 25;
                        if(!isset($arOptParams['BUTTON_TEXT']))
                            $arOptParams['BUTTON_TEXT'] = '...';
                        CAdminFileDialog::ShowScript(Array(
                            'event' => 'BX_FD_'.$opt,
                            'arResultDest' => Array('FUNCTION_NAME' => 'BX_FD_ONRESULT_'.$opt),
                            'arPath' => Array(),
                            'select' => 'F',
                            'operation' => 'O',
                            'showUploadTab' => true,
                            'showAddToMenuTab' => false,
                            'fileFilter' => '',
                            'allowAllFiles' => true,
                            'SaveConfig' => true
                        ));
                        $input =     '<input id="__FD_PARAM_'.$opt.'" name="'.$opt.'" size="'.$arOptParams['FIELD_SIZE'].'" value="'.htmlspecialcharsEx($val).'" type="text" style="float: left;" '.($arOptParams['FIELD_READONLY'] == 'Y' ? 'readonly' : '').' />
                                    <input value="'.$arOptParams['BUTTON_TEXT'].'" type="button" onclick="window.BX_FD_'.$opt.'();" />
                                    <script>
                                        setTimeout(function(){
                                            if (BX("bx_fd_input_'.strtolower($opt).'"))
                                                BX("bx_fd_input_'.strtolower($opt).'").onclick = window.BX_FD_'.$opt.';
                                        }, 200);
                                        window.BX_FD_ONRESULT_'.$opt.' = function(filename, filepath)
                                        {
                                            var oInput = BX("__FD_PARAM_'.$opt.'");
                                            if (typeof filename == "object")
                                                oInput.value = filename.src;
                                            else
                                                oInput.value = (filepath + "/" + filename).replace(/\/\//ig, \'/\');
                                        }
                                    </script>';
                        if($arOptParams['REFRESH'] == 'Y')
                            $input .= '<input type="submit" name="refresh" value="OK" />';
                        break;
                    case 'CUSTOM':
                        $input = $arOptParams['VALUE'];
                        break;
                    default:
                        if(!isset($arOptParams['SIZE']))
                            $arOptParams['SIZE'] = 25;
                        if(!isset($arOptParams['MAXLENGTH']))
                            $arOptParams['MAXLENGTH'] = 255;
                            
                        if($arOptParams['TYPE'] == 'INT') {
                            $type = 'number';    
                        } 
                        elseif($arOptParams['TYPE'] == 'HIDDEN') {
                            $type = 'hidden';                              
                        }
                        else {
                            $type = 'text';                                 
                        }   
                            
                        $input = '<input type="'.$type.'" size="'.$arOptParams['SIZE'].'" maxlength="'.$arOptParams['MAXLENGTH'].'" value="'.htmlspecialcharsEx($val).'" name="'.htmlspecialcharsEx($option).'" />';
                        
                        
                        if($arOptParams['TYPE'] == 'HIDDEN' && $arOptParams['VALUE_SHOW']) {
                            $input .= $arOptParams['VALUE_SHOW'];    
                        }                        
                        elseif($arOptParams['TYPE'] == 'HIDDEN') {
                            $input .= $val;    
                        }
                        
                        if($arOptParams['REFRESH'] == 'Y')
                            $input .= '<input type="submit" name="refresh" value="OK" />';
                        break;
                }



                
                                    

                if(in_array($arOptParams['TYPE'], array('HTML','PHP','CONNECTOR_SUB'))) {
                    $arP[$this->arGroups[$arOptParams['GROUP']]['TAB']][$arOptParams['GROUP']]['OPTIONS'][$option] = '';
                }
                else {    
    
                    $arP[$this->arGroups[$arOptParams['GROUP']]['TAB']][$arOptParams['GROUP']]['OPTIONS'][$option] =  '
                        <tr>
                            <td width="50%">'.$label.':</td>
                            <td width="50%">'.$input.'</td>
                        </tr>
                    ';           

                }                  

                 
                $arP[$this->arGroups[$arOptParams['GROUP']]['TAB']][$arOptParams['GROUP']]['OPTIONS_SORT'][$option] = $arOptParams['SORT'];     
            }
               
            

            echo '<form name="'.str_replace(".", "", $this->module_id).'" method="POST" action="'.$APPLICATION->GetCurPageParam().'&mid='.$this->module_id.'&lang='.LANGUAGE_ID.'#form" enctype="multipart/form-data">'.bitrix_sessid_post();
             
            
              
            $tabControl = new CAdminTabControl('tabControl', $this->arTabs);
            $tabControl->Begin();  
                 
            foreach($arP as $tab => $groups)
            {
                $tabControl->BeginNextTab();
                 
                foreach($groups as $group_id => $group)
                {
                    if(is_array($group['OPTIONS_SORT']) && sizeof($group['OPTIONS_SORT']) > 0)
                    {
                        echo '<tr class="heading"><td colspan="2">'.$this->arGroups[$group_id]['TITLE'].'</td></tr>';
                        
                        array_multisort($group['OPTIONS_SORT'], $group['OPTIONS']);
                        $hide = true;
                        $hide_viwed = true;
                        $hide_basket = true;
                        $hide_new = true;
                        
                        foreach($group['OPTIONS'] as $key => $opt)
                        {
                            $types = array(
                                'TEMPLATE_PARAMS_TEMP_TOP_RECOMMEND',
                                'TEMPLATE_PARAMS_TEMP_LIST_RECOMMEND',
                                'TEMPLATE_PARAMS_TEMP_BOTTOM_RECOMMEND',
                            );
                            if(in_array($key, $types) && $hide)
                            {
                                echo '<tr class="heading"><td colspan="2"><span class="hide_use_click">'.GetMessage('HIDE_SHOW').'</span></td></tr>';
                                echo '<td align="center" colspan="2">           
                                    <div id="hide_warnings" class="adm-info-message-wrap">
                                        <div class="adm-info-message" align="left">
                                            '.GetMessage('WARNING').'
                                        </div>
                                    </div>
                                </td>';
                                $classes = 'hide_use';
                                $hide = false;
                            }

                            $types_viwed = array(
                                'TEMPLATE_PARAMS_TEMP_TOP_VIEWED',
                                'TEMPLATE_PARAMS_TEMP_LIST_VIEWED',
                                'TEMPLATE_PARAMS_TEMP_BOTTOM_VIEWED'
                            );

                            if(in_array($key, $types_viwed) && $hide_viwed)
                            {
                                echo '<tr class="heading"><td colspan="2"><span class="hide_use_click_viwed">'.GetMessage('HIDE_SHOW').'</span></td></tr>';
                                echo '<td align="center" colspan="2">
                                    <div id="hide_warning" class="adm-info-message-wrap">
                                        <div class="adm-info-message" align="left">
                                           '.GetMessage('WARNING').'
                                        </div>
                                    </div>
                                </td>';
                                $hide_viwed = false;
                                $classes = 'hide_use_viwed';
                            }

                            $types_basket = array(
                                'TEMPLATE_PARAMS_TEMP_FORGET_BASKET_TOP',
                                'TEMPLATE_PARAMS_TEMP_FORGET_BASKET_LIST',
                                'TEMPLATE_PARAMS_TEMP_FORGET_BASKET_BOTTOM'
                            );

                            if(in_array($key, $types_basket) && $hide_basket)
                            {
                                echo '<tr class="heading"><td colspan="2"><span class="hide_use_click_basket">'.GetMessage('HIDE_SHOW').'</span></td></tr>';
                                echo '<td align="center" colspan="2">           
                                    <div id="hide_warning" class="adm-info-message-wrap">
                                        <div class="adm-info-message" align="left">
                                           '.GetMessage('WARNING').'
                                        </div>
                                    </div>
                                </td>';
                                $hide_basket = false;
                                $classes = 'hide_use_basket';
                            }

                            //  $settings['TEMPLATE_PARAMS_TEMP_NOVELTY_GOODS_TOP']['TYPE_FIELD'] = $types['TEMP_NOVELTY_GOODS_TOP_TYPE'];
                            //  $settings['TEMPLATE_PARAMS_TEMP_NOVELTY_GOODS_BOTTOM']['TYPE_FIELD'] = $types['TEMP_NOVELTY_GOODS_BOTTOM_TYPE'];
                            //  $settings['TEMPLATE_PARAMS_TEMP_NOVELTY_GOODS_LIST']['TYPE_FIELD'] = $types['TEMP_NOVELTY_GOODS_LIST_TYPE'];

                            $types_new = array(
                                'TEMPLATE_PARAMS_TEMP_NOVELTY_GOODS_TOP',
                                'TEMPLATE_PARAMS_TEMP_NOVELTY_GOODS_BOTTOM_LIST',
                                'TEMPLATE_PARAMS_TEMP_NOVELTY_GOODS_LIST'
                            );

                            if(in_array($key, $types_new) && $hide_new)
                            {
                                echo '<tr class="heading"><td colspan="2"><span class="hide_use_click_new">'.GetMessage('HIDE_SHOW').'</span></td></tr>';
                                echo '<td align="center" colspan="2">           
                                    <div id="hide_warning" class="adm-info-message-wrap">
                                        <div class="adm-info-message" align="left">
                                           '.GetMessage('WARNING').'
                                        </div>
                                    </div>
                                </td>';
                                $hide_new = false;
                                $classes = 'hide_use_new';
                            }

                            $template = array(
                                'TEMPLATE_PARAMS_SUBJECT',
                                'TEMPLATE_PARAMS_EMAIL_FROM',
                                'TEMPLATE_PARAMS_EMAIL_TO',
                                'TEMPLATE_PARAMS_BCC',
                                'TEMPLATE_PARAMS_SITE_TEMPLATE_ID',
                                'TEMPLATE_PARAMS_MESSAGE',
                            );
                            if(in_array($key, $template))
                            {
                                $classes = '';
                            }

                            if($this->arOptions[$key]['TYPE'] == 'HTML'):?>
                                <?CModule::IncludeModule('fileman');?>
                                <tr class="<?=$classes?>">
                                    <td colspan="2" width="100%">
                                    <?if($this->arOptions[$key]['TITLE']):?>
                                    <p><b><?=$this->arOptions[$key]['TITLE']?>:</b></p>  
                                    <?endif;?>
                                    <? 
                                    $val = $this->arCurOptionValues[$key];

                                    if($this->arOptions[$key]['HEIGHT']) {
                                        $HEIGHT = $this->arOptions[$key]['HEIGHT'];    
                                    } else {
                                        $HEIGHT = 450;        
                                    }
                                    
                                    //���� ����� ���������� � ������
                                    if($this->arOptions[$key]['TYPE_MAIL']=='Y'):?>
                                    
                                        <?CFileMan::AddHTMLEditorFrame(
                                            $key,
                                            $val,
                                            $key.'_TYPE',
                                            "html",
                                            array(
                                                'height' => $HEIGHT,
                                                'width' => '100%'
                                            ),
                                            "N",
                                            0,
                                            "",
                                        "onfocus=\"t=this\"",
                                            false,
                                            true,
                                            false,
                                            array(
                                                'componentFilter' => array('TYPE' => 'mail'),
                                                'limit_php_access' => true
                                            )     
                                        ); 
                                        ?> 
                                        <script type="text/javascript" language="JavaScript">
                                            BX.addCustomEvent('OnEditorInitedAfter', function(editor){editor.components.SetComponentIcludeMethod('EventMessageThemeCompiler::includeComponent'); });
                                        </script>   
                                        
                                    
                                    <?else:?>
                                        <?CFileMan::AddHTMLEditorFrame(
                                            $key,
                                            $val,
                                            $key.'_TYPE',
                                            $this->arSettings[$key]['TYPE_FIELD'],//"text",
                                            array(
                                                'height' => $HEIGHT,
                                                'width' => '100%'
                                            ),
                                            "N",
                                            0,
                                            "",
                                            "",
                                            SITE_ID      
                                        );?>
                                        
                                    <?endif;?>
                                    </td>
                                </tr>
                            <?elseif($this->arOptions[$key]['TYPE'] == 'PHP'):?>
                                <?CModule::IncludeModule('fileman');?>                            
                            
                                <?
                                $val = $this->arCurOptionValues[$key];
                                if(empty($val)){
                                    $val = "<?

                                    ?>";    
                                }
                    
                                //c������� ���� php
                                //START
                                if($_GET['ID']) {
                                    $name_file_php =  str_replace("TEMPLATE_PARAMS_", "", $key);
                                    RewriteFile($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$this->module_id."/php_files_mailing/".$_GET['ID']."/".$name_file_php.".php", $val);        
                                }
                                //END
                                ?>
                                <tr>
                                    <td colspan="2" width="100%" >  
                                        <div style="width:1000px; margin: 0px auto">
                                                            
                                        <?if($this->arOptions[$key]['TITLE']):?>
                                        <p><b><?=$this->arOptions[$key]['TITLE']?>:</b></p>  
                                        <?endif;?>  
                                        
                                        <textarea id="<?=$key?>" style="width:100%; height: <?=$this->arOptions[$key]['HEIGHT']?>px" name="<?=$key?>"><?=$val?></textarea> 
                                        <?
                                        $ceid = CCodeEditor::Show(array(
                                            'id'=>$key, 
                                            'textareaId' => $key , 
                                            'forceSyntax' => 'php',
                                            'theme' => 'light', 
                                        ));

                                        ?>                                      

                                        <script type="text/javascript">
                                        
                                        <?
                                        if(empty($this->arOptions[$key]['HEIGHT'])){
                                            $this->arOptions[$key]['HEIGHT'] = '300';    
                                        }
                                        if(empty($this->arOptions[$key]['WIDTH'])){   
                                            $this->arOptions[$key]['WIDTH'] = '1000'; 
                                        }                                    
                                        ?> 

                                        // ��� ���� ����� �� ������������� ��� ������������ � ����� ��������
                                        $(function(){
                                            $('body,html').animate({scrollTop: 130}, 1);    
                                        });
        
                                        $('#tabControl_tabs span').click(function(){
                                            var CE_<?=$ceid?> = window.BXCodeEditors['<?=$ceid?>'];
                                            CE_<?=$ceid?>.Resize('<?=$this->arOptions[$key]['WIDTH']?>', '<?=$this->arOptions[$key]['HEIGHT']?>');     
                                        }); 
                                        </script>                         
                                        </div>                             
                            
                                    </td>
                                </tr>
                            <?elseif($this->arOptions[$key]['TYPE'] == 'HTML_SHOW'):?>
                                <tr>
                                    <td colspan="2" width="100%" bgcolor="white" align="center">
                                    <br />
                                    <?if($this->arOptions[$key]['TITLE']):?>
                                    <p><b><?=$this->arOptions[$key]['TITLE']?>:</b></p>  
                                    <?endif;?>
                                    <? 
                                    $val = $this->arCurOptionValues[$key];
                                    
                                    echo $val;                                
                                    ?>
                                    <br />
                                    </td>
                                </tr>
                            <?elseif($this->arOptions[$key]['TYPE'] == 'TABS_INFO'):
                                $val = $this->arCurOptionValues[$key];
                                ?>
                                <tr>
                                    <td colspan="2" width="100%">
                                        <?=$val?>
                                    </td>
                                </tr> 
                                                            
                            <?elseif($this->arOptions[$key]['TYPE'] == 'USER_ID'):
                                $str_USER_ID = $this->arCurOptionValues[$key];
                                ?>
                                <tr>
                                    <td><?=$this->arOptions[$key]['TITLE']?></td>
                                    <td>
                                    <?
                                    $sUser = "";
                                    if($str_USER_ID > 0)
                                    {
                                        $rsUser = CUser::GetByID($str_USER_ID);
                                        $arUser = $rsUser->GetNext();
                                        if($arUser)
                                            $sUser = "[<a href=\"user_edit.php?ID=".$arUser["ID"]."&amp;lang=".LANG."\">".$arUser["ID"]."</a>] (".$arUser["LOGIN"].") ".$arUser["NAME"]." ".$arUser["LAST_NAME"];
                                    }
                                    echo FindUserID($key, $str_USER_ID, $sUser, str_replace(".", "", $this->module_id), "10", "", " ... ", "", "");

                                    if((integer)$str_USER_ID==0):
                                    ?><script language="JavaScript">document.subscrform.USER_ID.disabled=document.subscrform.FindUser.disabled=true;</script><?
                                    endif;                            
                                    ?>
                                    </td>  
                                </tr>
                            <?elseif($this->arOptions[$key]['TYPE'] == 'DATE_PERIOD'):?>
                                <?$val = $this->arCurOptionValues[$key];?>
                                <tr>
                                    <td>
                                        <?=$this->arOptions[$key]['TITLE']?>:
                                    </td>                         
                                    <td>
                                        <div style="max-width: 400px;">     
                                            <?echo CalendarPeriod($key."_from", $val['from'], $key."_to", $val['to'], str_replace(".", "", $this->module_id), "N")?>                                    
                                        </div>
    
                                    </td>                 
                                </tr>
                            <?elseif($this->arOptions[$key]['TYPE'] == 'DATE_PERIOD_AGO'):?>
                                <?
                                $val = $this->arCurOptionValues[$key];   
                                ?>
                                <tr>
                                    <td>
                                        <?=$this->arOptions[$key]['TITLE']?>:                             
                                    </td>                         
                                    <td>
                                        <?echo GetMessage($this->module_id."_FROM");?>
                                        <input type="int" name="<?=$key?>_from" value="<?=$val['from']?>" size="20">

                                        <?echo GetMessage($this->module_id."_TO");?>
                                        <input type="int" name="<?=$key?>_to" value="<?=$val['to']?>" size="20">

                                        <?
                                        $select_type = array(
                                            '' =>  GetMessage($this->module_id."_DATE_PERIOD_AGO_SELECT_TYPE"),
                                            'MIN' => GetMessage($this->module_id."_DATE_PERIOD_AGO_SELECT_TYPE_MIN"),
                                            'HOURS' => GetMessage($this->module_id."_DATE_PERIOD_AGO_SELECT_TYPE_HOURS"),
                                            'DAYS' => GetMessage($this->module_id."_DATE_PERIOD_AGO_SELECT_TYPE_DAYS"),
                                            'MONTH' => GetMessage($this->module_id."_DATE_PERIOD_AGO_SELECT_TYPE_MONTH"),  
                                            'MIN_FORWARD' => GetMessage($this->module_id."_DATE_PERIOD_AGO_SELECT_TYPE_MIN_FORWARD"),
                                            'HOURS_FORWARD' => GetMessage($this->module_id."_DATE_PERIOD_AGO_SELECT_TYPE_HOURS_FORWARD"),
                                            'DAYS_FORWARD' => GetMessage($this->module_id."_DATE_PERIOD_AGO_SELECT_TYPE_DAYS_FORWARD"),
                                            'MONTH_FORWARD' => GetMessage($this->module_id."_DATE_PERIOD_AGO_SELECT_TYPE_MONTH_FORWARD"),                                                                                                                                                                                                         
                                        );
                                        ?>
                                        
                                        <select name="<?=$key?>_type" >
                                            <?foreach($select_type as $k => $v):?> 
                                            <option value="<?=$k?>" <?if($k==$val['type']):?>selected="selected"<?endif;?>><?=$v?></option>   
                                            <?endforeach;?>                                                                                                                                                            
                                        </select>    
                                    </td>                 
                                </tr>
                            <?elseif($this->arOptions[$key]['TYPE'] == 'INT_FROM_TO'):?>
                                <?$val = $this->arCurOptionValues[$key];?>
                                <tr>   
                                    <td>
                                        <?=$this->arOptions[$key]['TITLE']?>:
                                    </td>                         
                                    <td>
                                        <?echo GetMessage($this->module_id."_FROM");?>
                                        <input type="text" name="<?=$key?>_from" value="<?=(floatval($val['from'])>0)?floatval($val['from']):""?>" size="20">

                                        <?echo GetMessage($this->module_id."_TO");?>
                                        <input type="text" name="<?=$key?>_to" value="<?=(floatval($val['to'])>0)?floatval($val['to']):""?>" size="20">
                                    </td>
                                </tr>
                            <?elseif($this->arOptions[$key]['TYPE'] == 'CONNECTOR_SUB'):?>
                                <? 
                                    $arr_connect = explode('||', $val);
                                    $endpointList = array(
                                        'MODULE_ID' => $arr_connect[0],
                                        'CODE' => $arr_connect[1]
                                    );
                                    $connector = \Sotbit\Mailing\SubConnectorManager::getConnector($endpointList);   
                                    if($connector) {
                                        $connector_count = 0;         
                                        //$connector->setFieldValues(array('GROUP_ID'=>'1'));
                                        $connector->setFieldPrefix('CONNECTOR_SETTING');
                                        
                                        if($_REQUEST['CONNECTOR_SETTING'][$connector->getModuleId()][$connector->getCode()][$connector_count]){
                                            $connector->setFieldValues($_REQUEST['CONNECTOR_SETTING'][$connector->getModuleId()][$connector->getCode()][$connector_count]);        
                                        } 
                                        
                                        
                                        
                                        echo str_replace('%CONNECTOR_NUM%',$connector_count,$connector->getForm()); 
                                    }
                                ?>  
                                <tr>
                                    <td colspan="2">
                                        <?if($this->arOptions[$key]['REFRESH'] == 'Y'):?>

                                        <script type="text/javascript">
                                        $('.subconnector_tr select, .subconnector_tr input').change(function(){
                                            $(this).parents("form").submit();  
                                                    
                                        });                                         
                                        </script>                                             
                                              
                                        <?endif;?>   
                                        
                                        <div id="connector_form_container" class="sender-group-address-counter">
                                            <span class="sender-mailing-sprite sender-group-address-counter-img"></span>
                                            <span class="sender-group-address-counter-text"><?=GetMessage($this->module_id.'_group_conn_cnt_all')?></span>
                                            <span id="sender_group_address_counter" class="sender-group-address-counter-cnt">
                                            <?if($connector){
                                                echo $connector->getDataCount();    
                                            }?>
                                                
                                            </span>
                                        </div>                                     
                                    </td>
                                </tr>
                            <?else:?>
                                <?echo $opt;?>
                            <?endif;?>


                            <?
                                $excludeDiscounts = array(
                                    'TEMPLATE_PARAMS_NEW_COUPON_ADD'
                                );
                                if(in_array($key, $excludeDiscounts))
                                {
                                    $classes = '';
                                }
                            ?>

                            <?if($this->arOptions[$key]['NOTES'] != ''):?>
                                <tr>
                                    <td class="<?=$classes?>" align="center" colspan="2">
                                        <div  class="adm-info-message-wrap">
                                            <div class="adm-info-message" align="left">
                                                <?=$this->arOptions[$key]['NOTES']?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?endif;?>
                            
                            
                            <?    
                        }
                    }
                }
            }
            
            if($this->need_access_tab)
            {
                $tabControl->BeginNextTab();
                $module_id = $this->module_id;
                require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
            }
            
            $tabControl->Buttons();
            ?>

            <style>
                .hide_use, .hide_use_viwed , .hide_use_basket, .hide_use_new {
                    display: none;
                }
                .hide_use_click_viwed , .hide_use_click, .hide_use_click_basket, .hide_use_click_new  {
                    color: #2675d7;
                    padding-bottom: 2px;
                    border-bottom: 1px dashed #2675d7;
                }
                .hide_use_click_viwed:hover , .hide_use_click:hover, .hide_use_click_new:hover, .hide_use_click_basket:hover {
                    border: none;
                }

            </style>
            <script type="text/javascript">
                var hide_and_show = function(e){
                    var getHide = document.querySelectorAll('.hide_use');
                    if(getHide.length > 0){
                        getHide.forEach(function (value, id) {
                            value.className = 'show_use';
                        });
                    }else{
                        var getShow = document.querySelectorAll('.show_use');
                        getShow.forEach(function (value, id) {
                            value.className = 'hide_use';
                        });
                    }
                }
                var hide = document.querySelector('.hide_use_click');
                if(hide){
                    hide.addEventListener('click', hide_and_show);
                }

                var hide_and_show_viwed = function(e){
                    var getHide_viwed = document.querySelectorAll('.hide_use_viwed');
                    if(getHide_viwed.length > 0){
                        getHide_viwed.forEach(function (value, id) {
                            value.className = 'show_use_viwed';
                        });
                    }else{
                        var getShow_viwed = document.querySelectorAll('.show_use_viwed');
                        getShow_viwed.forEach(function (value, id) {
                            value.className = 'hide_use_viwed';
                        });
                    }
                }
                var hide_viwed = document.querySelector('.hide_use_click_viwed');
                if(hide_viwed){
                    hide_viwed.addEventListener('click', hide_and_show_viwed);
                }

                var hide_and_show_basket = function(e){
                    var getHide_basket = document.querySelectorAll('.hide_use_basket');
                    if(getHide_basket.length > 0){
                        getHide_basket.forEach(function (value, id) {
                            value.className = 'show_use_basket';
                        });
                    }else{
                        var getShow_viwed = document.querySelectorAll('.show_use_basket');
                        getShow_viwed.forEach(function (value, id) {
                            value.className = 'hide_use_basket';
                        });
                    }
                }
                var hide_basket = document.querySelector('.hide_use_click_basket');
                if(hide_basket){
                    hide_basket.addEventListener('click', hide_and_show_basket);
                }

                var hide_and_show_new = function(e){
                    var getHide_new = document.querySelectorAll('.hide_use_new');
                    if(getHide_new.length > 0){
                        getHide_new.forEach(function (value, id) {
                            value.className = 'show_use_new';
                        });
                    }else{
                        var getShow_viwed = document.querySelectorAll('.show_use_new');
                        getShow_viwed.forEach(function (value, id) {
                            value.className = 'hide_use_new';
                        });
                    }
                }
                var hide_new = document.querySelector('.hide_use_click_new');
                if(hide_new){
                    hide_new.addEventListener('click', hide_and_show_new);
                }




            </script>
                <input type="hidden" name="update" value="Y" />
                <?if($this->arSettings['page'] == 'import_user'):?> 
                <input type="submit" name="import" value="<?=GetMessage($this->module_id."_submit_import")?>"  class="adm-btn-save"/>                
                <?else:?>

                <input type="submit" name="save" value="<?=GetMessage($this->module_id."_submit_save")?>"  class="adm-btn-save"/>
                <input type="reset" name="reset" value="<?=GetMessage($this->module_id."_submit_cancel")?>" />
                <?endif;?>
                
            <?
            $tabControl->End();    
            echo '</form>';
            
        }
    }
}

?>