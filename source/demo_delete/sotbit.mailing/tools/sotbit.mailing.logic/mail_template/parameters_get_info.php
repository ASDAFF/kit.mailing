<?
$MESSAGE_DEFAULT = GetMessage("MESSAGE_DEFAULT");

//Получим список тем оформления шаблонов сообщений
$arMailSiteTemplate = array();
$mailSiteTemplateDb = CSiteTemplate::GetList(null, array('TYPE' => 'mail'));
while($mailSiteTemplate = $mailSiteTemplateDb->GetNext())
    $arMailSiteTemplate[] = $mailSiteTemplate;
    
//Сформируем массив значений
$arMailSiteTemplateList = array('' => GetMessage('SELECT_CHANGE'));
    foreach($arMailSiteTemplate as $mailSiteTemplate) { 
        $arMailSiteTemplateList[$mailSiteTemplate['ID']] = '['.$mailSiteTemplate['ID'].'] ' . $mailSiteTemplate['NAME'];          
    }

?>