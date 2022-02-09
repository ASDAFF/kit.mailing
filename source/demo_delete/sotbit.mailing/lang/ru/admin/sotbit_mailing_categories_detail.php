<?
$module_id = 'sotbit.mailing';
$MESS[$module_id."_PAGE_TITLE"] = "Группа подписчиков #ID#";

$MESS[$module_id."_DEMO"] = "Модуль работает в демо-режиме.";
$MESS[$module_id."_DEMO_DETAILS"] = 'Приобрести полнофункциональную версию вы можете по адресу: <a href="http://marketplace.1c-bitrix.ru/solutions/sotbit.mailing/" target="_blank">http://marketplace.1c-bitrix.ru/solutions/sotbit.mailing</a>';
$MESS[$module_id."_DEMO_END"] = "Демо-режим закончен.";
$MESS[$module_id."_DEMO_END_DETAILS"] = 'Приобрести полнофункциональную версию вы можете по адресу: <a href="http://marketplace.1c-bitrix.ru/solutions/sotbit.mailing/" target="_blank">http://marketplace.1c-bitrix.ru/solutions/sotbit.mailing</a>';

$MESS[$module_id."_edit10"] = "Категория подписки";  

$MESS[$module_id."_edit_VIDEO"] = "Видео-инструкции"; 

$MESS[$module_id."_OPTION_DEF_NANE"] = "Настройки";
 

$MESS[$module_id."_OPTION_10"] = "Общие данные"; 
$MESS[$module_id."_OPTION_20"] = "Синхронизация пользователей"; 
$MESS[$module_id."_OPTION_30"] = "Синхронизация подписчиков bitrix"; 
$MESS[$module_id."_OPTION_40"] = "Синхронизация c mailchimp.com"; 
$MESS[$module_id."_OPTION_50"] = "Синхронизация c unisender.com"; 
$MESS[$module_id."_OPTION_VIDEO"] = "Видео-инструкции"; 


$MESS[$module_id."_ID_TITLE"] = "ID"; 
$MESS[$module_id."_ACTIVE_TITLE"] = "Активность"; 
$MESS[$module_id."_DATE_CREATE_TITLE"] = "Дата создания"; 
$MESS[$module_id."_PARAM_1_TITLE"] = "Параметр 1"; 
$MESS[$module_id."_PARAM_2_TITLE"] = "Параметр 2"; 
$MESS[$module_id."_PARAM_3_TITLE"] = "Параметр 3"; 
$MESS[$module_id."_NAME_TITLE"] = "Название";   
$MESS[$module_id."_NAME_DEF"] = "Название категории подписки"; 
$MESS[$module_id."_DESCRIPTION_TITLE"] = "Описание"; 
$MESS[$module_id."_SUNC_USER_TITLE"] = "Создавать пользователей"; 
$MESS[$module_id."_SUNC_USER_MESSAGE_TITLE"] = "Уведомлять пользователя о регистрации"; 
$MESS[$module_id."_SUNC_USER_GROUP_TITLE"] = "Добавить в группу пользователей"; 
$MESS[$module_id."_SUNC_USER_EVENT_TITLE"] = "Шаблон уведомления"; 
$MESS[$module_id."_SUNC_USER_INFO_NOTES"] = 'При создании нового email адреса в данной категории, можно создавать новых пользователей в системе, определять в группы, отсылать уведомления о регистрации с паролем.<br />
Для того чтобы пользователи создавались, необходимо нажать на галочку "Создавать пользователей", определить необходимую группу.
Если вы хотите уведомить пользователя о регистрации, выслать ему пароль, возможно подарочный купон или еще что либо. <br />
Вам необходимо нажать галочку "Уведомлять пользователя о регистрации", выбрать сценарий в поле "Шаблон уведомления" ,  сценарий необходимо создать заранее - {user_register_mailing} Уведомление пользователя зарегистрированного модулем маркетинговые рассылки.  <br />
<br />
<span style="color:red">Крайне рекомендуем настроить, это позволит собирать в системе данные о пользователе.</span>
'; 

$MESS[$module_id."_SUNC_SUBSCRIPTION_TITLE"] = "Создавать подписчиков";                                                     
$MESS[$module_id."_SUNC_SUBSCRIPTION_LIST_TITLE"] = "Список рассылок"; 
$MESS[$module_id."_SUNC_SUBSCRIPTION_INFO_NOTES"] = 'При создании нового email адреса в данной категории, можно создавать подписчиков в модуле <a target="_blank" href="/bitrix/admin/subscr_admin.php?lang=ru">рассылок</a>, определяя их по спискам рассылок<br />
Для этого необходимо нажать "Создавать подписчиков" и определить списки рассылок.
'; 

$MESS[$module_id."_SUNC_MAILCHIMP_TITLE"] = "Создавать подписчиков в mailchimp";     
$MESS[$module_id."_SUNC_MAILCHIMP_LIST_TITLE"] = "Выгружать в список";     
$MESS[$module_id."_SUNC_MAILCHIMP_INFO_NOTES"] = 'При включеном параметре "Создавать подписчиков в mailchimp", все новые подписчики. будут выгружаться в список <a target="_blank" href="http://mailchimp.com/">mailchimp.com</a><br />
Для работы данного функционала необходимо получить ключ в личном кабинете сервиса mailchimp.com.
';        
    
    
$MESS[$module_id."_SUNC_UNISENDER_TITLE"] = "Создавать подписчиков в unisender";     
$MESS[$module_id."_SUNC_UNISENDER_LIST_TITLE"] = "Выгружать в список";     
$MESS[$module_id."_SUNC_UNISENDER_INFO_NOTES"] = 'При включеном параметре "Создавать подписчиков в unisender", все новые подписчики будут выгружаться в список в сервисе <a target="_blank" href="http://unisender.com/">unisender.com</a><br />
Для работы данного функционала необходимо получить ключ в личном кабинете сервиса unisender.com.
';        
      
 
$MESS[$module_id."_PANEL_TOP_BACK_TITLE"] = "Категории подписок";  

$MESS[$module_id."_PANEL_TOP_DELETE_TITLE"] = "Удалить"; 
$MESS[$module_id."_PANEL_TOP_DELETE_ALT"] = "Удалить категорию подписки"; 
$MESS[$module_id."_PANEL_TOP_DELETE_CONFORM"] = "Вы уверены, что хотите удалить категории подписки №#ID# из базы?";


$MESS[$module_id."_PANEL_TOP_EXPORT_UNISENDER_TITLE"] = "Экспорт из Unisender"; 
$MESS[$module_id."_PANEL_TOP_EXPORT_UNISENDER_ALT"] = "Экспортировать контакты из unisender в  данную категорию"; 
$MESS[$module_id."_PANEL_TOP_EXPORT_UNISENDER_CONFORM"] = 'Важно: При экспорте контактов из unisender с включеным параметром "Уведомлять пользователя о регистрации", пользователям будут высланы сообщения.
  Эспортироваться будут контакты из выбранной вам категории, работает только при включеном параметре "Создавать подписчиков в unisender".    
   Вы уверены, что хотите экспортировать данные из Unisender? ';
$MESS[$module_id."_EXPORT_UNISENDER"] = "Успешно экспортировано #COUNT# контактов из unisender"; 
  
   
   
$MESS[$module_id."_PANEL_TOP_SEND_TITLE"] = "Отправить сообщение";
$MESS[$module_id."_PANEL_TOP_SEND_ALT"] = "Отправить сообщение получателю";
$MESS[$module_id."_PANEL_TOP_SEND_CONFORM"] = "Вы уверены, что хотите отправить сообщение №#ID# получателю?";


$MESS[$module_id."_ERROR_NAME_SAVE"] = "Заполните название подписки";


$MESS[$module_id."_TABS_SOTBIT_MAILING_CATEGORY_INSTRUCTION"] = '
    <div style="text-align:center">
        <h3>Видео-урок: Маркетинговые рассылки - категории подписки</h3> 
        <iframe width="800" height="500"  src="//www.youtube.com/embed/1cuF76c7SgU?list=PL2fR59TvIPXe8-iafhCqcLK4r1RxzqhST" frameborder="0" allowfullscreen></iframe>
        <br />
        <br />
        <br />
        <h3>Видео-урок: Маркетинговые рассылки - всплывашка по времени сбор e mail </h3> 
        <iframe width="800" height="500" src="//www.youtube.com/embed/-9RVUalNzMo?list=PL2fR59TvIPXe8-iafhCqcLK4r1RxzqhST" frameborder="0" allowfullscreen></iframe>
        <br />
        <br />
        <br />
        <h3>Видео-урок: Маркетинговые рассылки - нижняя плашка сбора e mail </h3> 
        <iframe width="800" height="500" src="//www.youtube.com/embed/pNsPdUDlJuk?list=PL2fR59TvIPXe8-iafhCqcLK4r1RxzqhST" frameborder="0" allowfullscreen></iframe>
        <br />  
        <br /> 
        <br />         
        <h3>Видео-обзор: Маркетинговые рассылки</h3> 
        <iframe width="800" height="500" src="//www.youtube.com/embed/DYTnKHJAr70?list=PL2fR59TvIPXe8-iafhCqcLK4r1RxzqhST" frameborder="0" allowfullscreen></iframe>
        <br />
        <br />  
        <br />
        <br />       
        <h3>Если у вас возникли проблемы или вопросы с настройкой рассылок, смело пишите в <a href="http://www.sotbit.ru/support/" target="_blank">техническую поддержку</a> компании «Сотбит», мы обязательно вам поможем.</h3>
                          
    </div>

';  

?>