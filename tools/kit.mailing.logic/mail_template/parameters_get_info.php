<?
$MESSAGE_DEFAULT = GetMessage("MESSAGE_DEFAULT");

//������� ������ ��� ���������� �������� ���������
$arMailSiteTemplate = array();
$mailSiteTemplateDb = CSiteTemplate::GetList(null, array('TYPE' => 'mail'));
while($mailSiteTemplate = $mailSiteTemplateDb->GetNext())
    $arMailSiteTemplate[] = $mailSiteTemplate;
    
//���������� ������ ��������
$arMailSiteTemplateList = array('' => GetMessage('SELECT_CHANGE'));
    foreach($arMailSiteTemplate as $mailSiteTemplate) { 
        $arMailSiteTemplateList[$mailSiteTemplate['ID']] = '['.$mailSiteTemplate['ID'].'] ' . $mailSiteTemplate['NAME'];          
    }

?>