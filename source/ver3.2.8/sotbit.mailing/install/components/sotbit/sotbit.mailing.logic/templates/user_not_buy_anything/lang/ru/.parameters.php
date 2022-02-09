<?
$MESS['SITE_ID_ALL'] = 'Все'; 
$MESS['SITE_ID_TITLE'] = 'Сайт';


$MESS["DATE_REGISTER_TITLE"] = 'Пользователь зарегистрирован'; 
$MESS["DAY_AGO_START_TITLE"] = 'Пользователь зарегистрирован от дней'; 
$MESS["DAY_AGO_END_TITLE"] = 'Пользователь зарегистрирован до дней'; 
$MESS["DAY_AGO_END_NOTES"] = 'Установите цифровое значение количества дней от и до, которое пользователь зарегистрирован.<br />';





      



       

     
$MESS["GROUP_PARAM_USER_FILLTER"] = 'Выбираем пользователей';


      
$MESS["SUBJECT_DEFAULT"] = 'Возникли проблемы #USER_NAME#? Поможем!';
$MESS["MESSAGE_DEFAULT"] = '
               <p><b>Добрый день !</b></p>
               <br />
               <p>Приветствуем Вас #USER_NAME# #USER_LAST_NAME#! Вы зарегистрировались на сайте нашего магазина, но так и не совершили ни одной покупки, хотим убедиться, что все в порядке. </p>
               <p>Если Вам необходима помощь при оформлении заказа, то Вы всегда можете нам позвонить или написать на почту. Мы окажем Вам необходимую поддержку в тот же час, как получим ваше сообщение. </p>
               <br />
               <p>Мы дарим Вам купон на 5% скидки, который будет действовать 2 дня, успейте купить:) </p>
               <p><b>Номер купона: #COUPON#</b></p>
               <br />
               <br />            
               Удачных покупок!        
              
              #RECOMMEND_PRODUCT#
';   
  
   
  
$MESS["GROUP_MESSAGE_NOTES"] = '
   <b>Статистика и отписка от рассылки: </b>  <br />
   #MAILING_MESSAGE# - для сбора статистики необходимо ссылки ставить с переменной ?MAILING_MESSAGE=#MAILING_MESSAGE# <br />
   #MAILING_UNSUBSCRIBE# - для возможности отписаться от рассылки ставите ссылку с переменной ?MAILING_UNSUBSCRIBE=#MAILING_UNSUBSCRIBE# <br />  
   #MAILING_EVENT_ID# - ID рассылки<br />   
   <br />
   Информация о пользователе:<br />
   #USER_ID# - ID пользователя <br />
   #USER_LOGIN# - логин пользователя <br />
   #USER_EMAIL# - e-main пользователя <br />
   #USER_LAST_NAME# - фамилия пользователя <br />
   #USER_NAME# - имя пользователя <br />
   #USER_SECOND_NAME# -  отчество пользователя <br />
   <br />
   
   Общие  переменные: <br />
   #COUPON# - купон на скидку   <br />
   #RECOMMEND_PRODUCT# - рекомендованные товары (форматированный в сценарии)<br />
   #RECOMMEND_PRODUCT_ID# - ID рекомендованных товаров для компонента <br /> 
   #VIEWED_PRODUCT# - просмотренные пользователем товары (форматированный в сценарии)<<br />  
   #VIEWED_PRODUCT_ID# - ID рекомендованных товаров для компонента <br />   
   <br />
   <br />
   При использовании сервиса unisender для отправки писем обязательна переменная {{UnsubscribeUrl}} для отписки от рассылки.
   <br />    
'; 



// дополнительные данные и логика рассылки
// START


// Перед выборкой пользователе  
$MESS["PHP_FILLTER_USER_PARAM_BEFORE_TITLE"] = 'PHP: Перед выборкой пользователей';
$MESS["PHP_FILLTER_USER_PARAM_BEFORE_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
Подлючается до выборки пользователей с помощью функции <a target="_blank" href="http://dev.1c-bitrix.ru/api_help/main/reference/cuser/getlist.php">CUser::GetList</a>.<br/>
Вы можете переопределить значения выборки, либо объединить свой с существующим  <a href="http://www.php.su/array_merge" target="_blank">array_merge($fillterUser, $MyArray)</a>. <br/>
<b>Переменные:</b> <br />
$byUser - сортировка<br />
$orderUser - порядок сортировки   <br />
$fillterUser - для фильтрации пользователей  <br />
$arParametersUser - дополнительные параметры функции  <br />

<br />
<br />
<b>Отладку</b> можно вести через <a href="/bitrix/admin/php_command_line.php?lang=ru" target="_blank">коммандную PHP-строку</a> в тестовом режиме рассылки.<br />
';

// В конце выборки пользователей
$MESS["PHP_FILLTER_USER_PARAM_AFTER_TITLE"] = 'PHP: В конце цикла выборки пользователей';
$MESS["PHP_FILLTER_USER_PARAM_AFTER_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
Подключается в конце цикла while, позволяет модифицировать полученные данные, добавить новые.<br />
Массив $EmailSend, хранит в себе данные конкретного сообщения.<br />
<br />
<b>Функции:</b> <br />
$phpIncludeFunction["isContinue"]="Y" - пропустить итерацию <br /> 
$phpIncludeFunction["isBreak"]="Y" - прервать работу цикла<br />   
<br />
<br />
<b>Отладку</b> можно вести через <a href="/bitrix/admin/php_command_line.php?lang=ru" target="_blank">коммандную PHP-строку</a> в тестовом режиме рассылки.<br />
';  


// END







$MESS['SELECT_CHANGE'] = '--Выберите--';
  
  
 
$MESS["TABS_SOTBIT_MAILING_INSTRUCTION_TITLE"] = "Видео-инструкции"; 
$MESS["GROUP_TABS_SOTBIT_MAILING_INSTRUCTION_NAME"] = "Видео-инструкции"; 
$MESS["TABS_SOTBIT_MAILING_INSTRUCTION_DEFAULT"] = '
    <div style="text-align:center">
        <h3>Видео-урок: Маркетинговые рассылки - сценарий пользователь зарегистрирован, ничего не купил  </h3> 
        <iframe width="800" height="500" src="//www.youtube.com/embed/nK46H1AFGRw?list=PL2fR59TvIPXe8-iafhCqcLK4r1RxzqhST" frameborder="0" allowfullscreen></iframe>
        <br />
        <br />
        <br />
        <h3>Видео-урок: Маркетинговые рассылки - рекомендованные товары    </h3> 
        <iframe width="800" height="500" src="//www.youtube.com/embed/FQtNjHpho3U?list=PL2fR59TvIPXe8-iafhCqcLK4r1RxzqhST" frameborder="0" allowfullscreen></iframe>
        <br />
        <br />
        <br />
        <h3>Видео-урок: Маркетинговые рассылки - скидки  </h3> 
        <iframe width="800" height="500" src="//www.youtube.com/embed/EGtwhublnU4?list=PL2fR59TvIPXe8-iafhCqcLK4r1RxzqhST" frameborder="0" allowfullscreen></iframe>
        <br />  
        <br /> 
        <br />     
        <h3>Видео-урок: Маркетинговые рассылки общие настройки, расписание и исключения  </h3> 
        <iframe width="800" height="500" src="//www.youtube.com/embed/ygSlr97rlDo?list=PL2fR59TvIPXe8-iafhCqcLK4r1RxzqhST" frameborder="0" allowfullscreen></iframe>  
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
