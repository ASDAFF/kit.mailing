<?

$MESS['GROUP_PARAM_IBLOCK_NAME'] = 'Выберем инфоблок'; 

$MESS['GROUP_PARAM_IBLOCK_FILLTER_LIST'] = 'Отфильтруем по свойству список'; 
$MESS['GROUP_PARAM_IBLOCK_FILLTER_STRING'] = 'Отфильтруем по свойству строка'; 


$MESS['GROUP_PARAM_IBLOCK_FILLTER_LIST'] = 'Отфильтруем по свойству список'; 
$MESS['GROUP_PARAM_IBLOCK_FILLTER_STRING'] = 'Отфильтруем по свойству строка'; 
$MESS['GROUP_PARAM_IBLOCK_FILLTER_DATE'] = 'Отфильтруем по свойству дата'; 





$MESS['IBLOCK_TYPE_INFO_TITLE'] = 'Тип инфоблока';
$MESS['IBLOCK_ID_INFO_TITLE'] = 'Код инфоблока'; 
$MESS['IBLOCK_INFO_PROPERTY_FILLTER_LIST_TITLE'] = 'Фильтровать товары по свойству типа список'; 
$MESS['IBLOCK_INFO_PROPERTY_FILLTER_LIST_VALUE_TITLE'] = 'Значение фильтра свойства типа список';          
$MESS['IBLOCK_INFO_PROPERTY_FILLTER_LIST_VALUE_NOTES'] = 'Оставьте пустыми значения если не нужно фильтровать';


$MESS['IBLOCK_INFO_PROPERTY_FILLTER_STRING_TITLE'] = 'Фильтровать товары по свойству типа строка'; 
$MESS['IBLOCK_INFO_PROPERTY_FILLTER_STRING_VALUE_TITLE'] = 'Значение фильтра свойства типа строка';   
$MESS['IBLOCK_INFO_PROPERTY_FILLTER_STRING_VALUE_NOTES'] = 'Оставьте пустыми значения если не нужно фильтровать';  

$MESS['IBLOCK_INFO_PROPERTY_FILLTER_DATE_TITLE'] = 'Фильтровать товары по свойству типа дата'; 
$MESS['IBLOCK_INFO_PROPERTY_FILLTER_DATE_VALUE_TITLE'] = 'Значение фильтра свойства типа дата';   
$MESS['IBLOCK_INFO_PROPERTY_FILLTER_DATE_VALUE_NOTES'] = 'Оставьте пустыми значения если не нужно фильтровать';    
   
$MESS['GROUP_PARAM_IBLOCK_FINISH_LIST'] = 'Значение свойства список после отправки сообщения';    
$MESS['IBLOCK_INFO_PROPERTY_FINISH_LIST_TITLE'] = 'Свойство'; 
$MESS['IBLOCK_INFO_PROPERTY_FINISH_LIST_VALUE_TITLE'] = 'Значение';          
$MESS['IBLOCK_INFO_PROPERTY_FINISH_LIST_VALUE_NOTES'] = 'Используйте если необходимо поменять значение свойства, у элемента по которому провели отправку сообщения. Выберите свойства типа "список" и его значение после отправки.';                 
         
         
   
// исключения из рассылки
// START  
$MESS['GROUP_PARAM_EXCEPTIONS'] = 'Исключить из рассылки';  
$MESS['EMAIL_DUBLICATE_TITLE'] = 'Дублирующие e-mail';
$MESS['EMAIL_DUBLICATE_NOTES'] = 'При обнаружение e-mail по которому отсылали сообщение, не производить отправку.';     
// END  
      

   
$MESS["EMAIL_FROM_TITLE"] = 'От кого';
  
 
$MESS["GROUP_PARAM_USER_FILLTER"] = 'Выбираем пользователей';
$MESS["GROUP_PARAM_COUPON_ADD"] = 'Параметры скидочного купона'; 
         
  
$MESS["SUBJECT_DEFAULT"] = 'Важная информация';
$MESS["GROUP_MESSAGE_NAME"] = 'Шаблон письма';  
$MESS["MESSAGE_DEFAULT"] = '
    <p><b>Добрый день !</b></p>
    <br />
    <p>Вы находитесь у нас в базе данных, поэтому вы информируем вас о новых акциях и скидках.</p>              
    #RECOMMEND_PRODUCT#
';   
  
   
  
$MESS["GROUP_MESSAGE_NOTES"] = '
   <b>Статистика и отписка от рассылки: </b>  <br />
   #MAILING_MESSAGE# - для сбора статистики необходимо ссылки ставить с переменной ?MAILING_MESSAGE=#MAILING_MESSAGE# <br />
   #MAILING_UNSUBSCRIBE# - для возможности отписаться от рассылки ставите ссылку с переменной ?MAILING_UNSUBSCRIBE=#MAILING_UNSUBSCRIBE# <br />  
   #MAILING_EVENT_ID# - ID рассылки<br />   
   <br />

   Переменные элемента инфоблока:   <br />
   #ID# - ID элемента <br />   
   #NAME# - Имя товара<br />   
   #PREVIEW_TEXT# - анонс описания<br />   
   #DETAIL_TEXT#  - детальное описание<br />   
   #LIST_PAGE_URL#  - ссылка на товары из категории <br />   
   #DETAIL_PAGE_URL#  - ссылка на товар <br />     
   #PICTURE_SRC# - пусть до изображения <br /> 
   #PICTURE_WIDTH# - ширина изображения <br />  
   #PICTURE_HEIGHT# - высота изображения <br />

   <br />
   Переменные свойств инфоблока: <br />
   #IBLOCK_PROP#  
            
   <br />   
   
   
   Общие  переменные: <br />
   #COUPON# - купон на скидку   <br />
   #RECOMMEND_PRODUCT# - рекомендованные товары (форматированный в сценарии)<br />
   #RECOMMEND_PRODUCT_ID# - ID рекомендованных товаров для компонента <br /> 
    
   <br />
   <br />
   При использовании сервиса unisender для отправки писем обязательна переменная {{UnsubscribeUrl}} для отписки от рассылки.
   <br />    
'; 


// дополнительные данные и логика рассылки
// START

// Перед выборкой элементов инфоблока
$MESS["PHP_FILLTER_IBLOCK_PARAM_BEFORE_TITLE"] = 'PHP: Перед выборкой элементов инфоблока';
$MESS["PHP_FILLTER_IBLOCK_PARAM_BEFORE_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
Подлючается до выборки элементов инфоблока с помощью функции <a target="_blank" href="http://dev.1c-bitrix.ru/api_help/iblock/classes/ciblockelement/getlist.php">CIBlockElement::GetList</a>.<br/>
Вы можете переопределить значения выборки, либо объединить свой с существующим  <a href="http://www.php.su/array_merge" target="_blank">array_merge($arFilter, $MyArray)</a>. <br/>
<b>Переменные:</b> <br />
$arSortIblock - сортировка<br />
$arFilterIblock - фильтрация <br />
$arSelectIblock - выбор данных<br />

<br />
<br />
<b>Отладку</b> можно вести через <a href="/bitrix/admin/php_command_line.php?lang=ru" target="_blank">коммандную PHP-строку</a> в тестовом режиме рассылки.<br />
';

// В конце выборки инфоблока
$MESS["PHP_FILLTER_IBLOCK_PARAM_AFTER_TITLE"] = 'PHP: В конце цикла выборки элементов инфоблока';
$MESS["PHP_FILLTER_IBLOCK_PARAM_AFTER_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
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
