<?
$module_id = 'sotbit.mailing';
$MESS[$module_id."_PAGE_TITLE"] = "Настройка рассылки #ID#";

$MESS[$module_id."_DEMO"] = "Модуль работает в демо-режиме.";
$MESS[$module_id."_DEMO_DETAILS"] = 'Приобрести полнофункциональную версию вы можете по адресу: <a href="http://marketplace.1c-bitrix.ru/solutions/sotbit.mailing/" target="_blank">http://marketplace.1c-bitrix.ru/solutions/sotbit.mailing</a>';
$MESS[$module_id."_DEMO_END"] = "Демо-режим закончен.";
$MESS[$module_id."_DEMO_END_DETAILS"] = 'Приобрести полнофункциональную версию вы можете по адресу: <a href="http://marketplace.1c-bitrix.ru/solutions/sotbit.mailing/" target="_blank">http://marketplace.1c-bitrix.ru/solutions/sotbit.mailing</a>';

$MESS[$module_id."_edit10"] = "Общие настройки";
$MESS[$module_id."_edit20"] = "Расписание и исключения";
$MESS[$module_id."_edit30"] = "Параметры рассылки";

$MESS[$module_id."_OPTION_DEF_NANE"] = "Настройки";

$MESS[$module_id."_OPTION_10"] = "Общие настройки"; 

$MESS[$module_id."_OPTION_40"] = "Отправка писем по расписанию"; 
$MESS[$module_id."_OPTION_50"] = "Ограничение времени рассылки по расписанию"; 

$MESS[$module_id."_OPTION_60"] = "Исключать email из рассылки по дублированию";
$MESS[$module_id."_OPTION_70"] = "Исключить отписавшиеся email";

$MESS[$module_id.'_SELECT_PARAM_ALL'] = 'Все';
$MESS[$module_id.'_SELECT_PARAM_Y'] = 'Да';
$MESS[$module_id.'_SELECT_PARAM_N'] = 'Нет';


$MESS[$module_id."_ID_TITLE"] = "ID"; 
$MESS[$module_id."_CATEGORY_TITLE"] = "Категория"; 

$MESS[$module_id."_DATE_LAST_RUN_TITLE"] = "Последний запуск рассылки"; 
$MESS[$module_id."_COUNT_RUN_TITLE"] = "Выпуск"; 
$MESS[$module_id."_TEMPLATE_TITLE"] = "Сценарий рассылки"; 
$MESS[$module_id."_NAME_TITLE"] = "Название рассылки"; 
$MESS[$module_id."_ACTIVE_TITLE"] = "Активность"; 
$MESS[$module_id."_DESCRIPTION_TITLE"] = "Описание рассылки"; 
$MESS[$module_id."_MODE_TITLE"] = "Режим работы";  
$MESS[$module_id."_MODE_NOTES"] = "При работе в тестовом режиме сообщения будут создаваться в системе, но без отправления к пользователю. <br />
Отправить их можно будет вручную на странице списка сообщений, либо удалить и сделать отправку рассылки в рабочем режиме.";  
$MESS[$module_id."_SITE_URL_TITLE"] = "URL сайта";  
$MESS[$module_id."_SITE_URL_NOTES"] = "Необходимо ввести ссылку на ваш сайт в формате http;//ВАШ_САЙТ.РУ <br />
 Этот адрес будет использоваться в ссылках.
"; 

$MESS[$module_id."_USER_AUTH_TITLE"] = "Авторизовать пользователя";  
$MESS[$module_id."_USER_AUTH_NOTES"] = "При включенном значение, пользователь будет авторизован при переходе по ссылке из  рассылки,  если будет  известен USER_ID в сценарии и MAILING_MESSAGE.
<br />Полезно при многих сценариях, позволяет облегчить путь пользователя до покупки..
"; 
    
$MESS[$module_id."_EVENT_SEND_SYSTEM_TITLE"] = "Система отправки сообщений";  
$MESS[$module_id."_TEMPLATE_SYSTEM_TITLE"] = "Система шаблонов сообщений";  

$MESS[$module_id."_AGENT_ID_TITLE"] = "ID агента";  
$MESS[$module_id."_AGENT_ACTIVE_TITLE"] = "Отправка писем по расписанию";  
$MESS[$module_id."_AGENT_INTERVAL_TITLE"] = "Интервал запуска";

$MESS[$module_id."_AGENT_INTERVAL_SELECT_300"] = "Раз 5 минут";  
$MESS[$module_id."_AGENT_INTERVAL_SELECT_900"] = "Раз 15 минут"; 
$MESS[$module_id."_AGENT_INTERVAL_SELECT_1800"] = "Раз 30 минут"; 
$MESS[$module_id."_AGENT_INTERVAL_SELECT_3600"] = "Раз в час"; 
$MESS[$module_id."_AGENT_INTERVAL_SELECT_7200"] = "Раз в 2 часа"; 
$MESS[$module_id."_AGENT_INTERVAL_SELECT_14400"] = "Раз в 4 часа"; 
$MESS[$module_id."_AGENT_INTERVAL_SELECT_28800"] = "Раз в 8 часов";
$MESS[$module_id."_AGENT_INTERVAL_SELECT_43200"] = "Раз в 12 часов";
$MESS[$module_id."_AGENT_INTERVAL_SELECT_86400"] = "Раз в день";
$MESS[$module_id."_AGENT_INTERVAL_SELECT_172800"] = "Раз в 2 дня";
$MESS[$module_id."_AGENT_INTERVAL_SELECT_259200"] = "Раз в 3 дня";
$MESS[$module_id."_AGENT_INTERVAL_SELECT_345600"] = "Раз в 4 дня";
$MESS[$module_id."_AGENT_INTERVAL_SELECT_604800"] = "Раз в неделю";
$MESS[$module_id."_AGENT_INTERVAL_SELECT_1209600"] = "Раз в 2 недели";
$MESS[$module_id."_AGENT_INTERVAL_SELECT_2678400"] = "Раз в месяц";
  
$MESS[$module_id."_AGENT_LAST_EXEC_TITLE"] = "Дата последнего запуска"; 
$MESS[$module_id."_AGENT_NEXT_EXEC_TITLE"] = "Дата и время следующего запуска"; 

$MESS[$module_id."_EVENT_PARAMS_AGENT_AROUND_TITLE"] = "Круглосуточно";  
$MESS[$module_id."_AGENT_TIME_START_TITLE"] = "Время рассылки сообщений с";         
$MESS[$module_id."_AGENT_TIME_END_TITLE"] = "Время рассылки сообщений до";         
       
      
// исключение по дублированию
// START     
$MESS[$module_id."_EXCLUDE_DAYS_HOUR_TITLE"] = "Исключить дублирование письма (часов)";       
$MESS[$module_id."_EXCLUDE_DAYS_HOUR_NOTES"] = "Исключить из рассылки email, которые получали письма в течение (*) часов.<br />
Данный параметр поможет не надоедать пользователям частыми сообщениями с вашего сайта. <br />
При нулевом значение данный параметр игнорируется, письма будут идти без ограничений.
";  
     
$MESS[$module_id."_EXCLUDE_DAYS_HOUR_SELECT_1"] = "1 часа";  
$MESS[$module_id."_EXCLUDE_DAYS_HOUR_SELECT_2"] = "2 часов"; 
$MESS[$module_id."_EXCLUDE_DAYS_HOUR_SELECT_4"] = "4 часов"; 
$MESS[$module_id."_EXCLUDE_DAYS_HOUR_SELECT_8"] = "8 часов"; 
$MESS[$module_id."_EXCLUDE_DAYS_HOUR_SELECT_16"] = "16 часов"; 
$MESS[$module_id."_EXCLUDE_DAYS_HOUR_SELECT_24"] = "1 дня"; 
$MESS[$module_id."_EXCLUDE_DAYS_HOUR_SELECT_48"] = "2 дней";
$MESS[$module_id."_EXCLUDE_DAYS_HOUR_SELECT_72"] = "3 дней";
$MESS[$module_id."_EXCLUDE_DAYS_HOUR_SELECT_96"] = "4 дней";
$MESS[$module_id."_EXCLUDE_DAYS_HOUR_SELECT_120"] = "5 дней";
$MESS[$module_id."_EXCLUDE_DAYS_HOUR_SELECT_144"] = "6 дней";
$MESS[$module_id."_EXCLUDE_DAYS_HOUR_SELECT_168"] = "1 недели";
$MESS[$module_id."_EXCLUDE_DAYS_HOUR_SELECT_336"] = "2 недели";
 
$MESS[$module_id."_EVENT_PARAMS_EXCLUDE_DAYS_HOUR_MODE_TITLE"] = "Режим работы";   
$MESS[$module_id."_EVENT_PARAMS_EXCLUDE_DAYS_HOUR_MODE_NOTES"] = "Режимы работы позволяют определять логику отсева по дублированию:<br /> 
<b>По текущему сценарию</b> - исключены будут только дубли по текущему сценарию<br />
<b>По всем сценариями</b> - исключены будут только дубли по всем сценариям<br /> 
<b>По текущему и сценариям из списка</b> - исключены будут дубли по текущему сценарию и выбранным из списка<br /> 
";  

$MESS[$module_id."_EVENT_PARAMS_EXCLUDE_DAYS_HOUR_MODE_VALUE_THIS"] = "По текущему сценарию";   
$MESS[$module_id."_EVENT_PARAMS_EXCLUDE_DAYS_HOUR_MODE_VALUE_ALL"] = "По всем сценариями";  
$MESS[$module_id."_EVENT_PARAMS_EXCLUDE_DAYS_HOUR_MODE_VALUE_LIST"] = "По текущему и сценариям из списка";   
 
 
$MESS[$module_id."_EVENT_PARAMS_EXCLUDE_HOUR_AGO_EVENT_TITLE"] = "Сценарии для исключения";       
$MESS[$module_id."_EVENT_PARAMS_EXCLUDE_HOUR_AGO_EVENT_NOTES"] = "Выберите сценарии которые надо мониторить на дублирование email которые получали письма в течении указанного времени";  
//END
      
       
       
$MESS[$module_id."_EXCLUDE_UNSUBSCRIBED_USER_TITLE"] = "Исключить email";       
$MESS[$module_id."_EXCLUDE_UNSUBSCRIBED_USER_NOTES"] = "Из рассылки будут исключены email:<br /> 
<b>Отписавшихся с общего списка</b> - все email адреса, которые есть на странице  <a target='_blank' href='sotbit_mailing_unsubscribed.php'>Отписавшиеся email</a>  <br />
<b>Отписавшихся по этой рассылке</b> - email адреса, связанные с данной рассылкой  <a target='_blank' href='sotbit_mailing_unsubscribed.php'>Отписавшиеся email</a>    <br /> 
<b>Не исключать</b> - email адреса исключены не будут <br /> 
<br />
Для того, чтобы у пользователей была возможность отписаться от рассылки, необходимо в шаблоне письма добавить к ссылке переменную ?MAILING_UNSUBSCRIBE=#MAILING_UNSUBSCRIBE#. <br />
Ссылка может вести на любую страницу вашего сайта.
";
     
$MESS[$module_id."_EXCLUDE_UNSUBSCRIBED_USER_MORE_TITLE"] = "Дополнительно Исключить email";      
$MESS[$module_id."_EXCLUDE_UNSUBSCRIBED_USER_MORE_NOTES"] = 'Выберите рассылки, отписавшиеся email от которых, должны быть исключены из данной рассылки.<br />
Действительно только при выбранном параметре  - "Отписавшихся по этой рассылке".
';         
       
$MESS[$module_id."_EXCLUDE_UNSUBSCRIBED_USER_VALUE_NO"] = "Не исключать";   
$MESS[$module_id."_EXCLUDE_UNSUBSCRIBED_USER_VALUE_ALL"] = "Отписавшихся с общего списка";  
$MESS[$module_id."_EXCLUDE_UNSUBSCRIBED_USER_VALUE_THIS"] = "Отписавшихся по этой рассылке";         
     
     
$MESS[$module_id."_EVENT_SEND_SYSTEM_VALUE_BITRIX"] = "1С-Bitrix";  
$MESS[$module_id."_EVENT_SEND_SYSTEM_VALUE_UNISENDER"] = "Сервис UniSender";

$MESS[$module_id."_TEMPLATE_SYSTEM_VALUE_BITRIX"] = "1С-Bitrix";  
$MESS[$module_id."_TEMPLATE_SYSTEM_VALUE_EMBEDDED"] = "Встроенная в модуль";   
        
$MESS[$module_id."_MODE_VALUE_TEST"] = "Тестовый режим";  
$MESS[$module_id."_MODE_VALUE_WORK"] = "Рабочий режим"; 
 
$MESS[$module_id."_TEMPLATE_TYPE_SYSTEM"] = "Системный"; 
$MESS[$module_id."_TEMPLATE_TYPE_MY"] = "Собственный"; 

 
$MESS[$module_id."_PANEL_TOP_BACK_TITLE"] = "Список рассылок";  
$MESS[$module_id."_PANEL_TOP_ADD_NEW_TITLE"] = "Создать новую рассылку"; 
$MESS[$module_id."_PANEL_TOP_ADD_NEW_ALT"] = "Создать новую рассылку"; 

$MESS[$module_id."_PANEL_TOP_DELETE_TITLE"] = "Удалить"; 
$MESS[$module_id."_PANEL_TOP_DELETE_ALT"] = "Удалить рассылку"; 
$MESS[$module_id."_PANEL_TOP_DELETE_CONFORM"] = "Рассылка №#ID# будет полностью удалена из системы. Данные по отправленным письмам по данной рассылке будут удалены.";
    

$MESS[$module_id."_PANEL_TOP_START_TITLE"] = "Запустить рассылку"; 
$MESS[$module_id."_PANEL_TOP_START_CONFORM"] = "Вы уверены, что хотите запустить рассылку?";

$MESS[$module_id."_PANEL_TOP_STOP_TITLE"] = "Остановить рассылку";

$MESS[$module_id."_JS_OJIDANIE"] = "Загрузка..."; 

$MESS[$module_id."_JS_OTPRAV"] = "Отправлено"; 
$MESS[$module_id."_JS_IZ"] = "из"; 
$MESS[$module_id."_JS_PISEM"] = "писем"; 
$MESS[$module_id."_JS_SEND_MAILING"] = "<b>Отправлено:</b>"; 
$MESS[$module_id."_JS_NO_SEND_EXCLUDE_UNSUBSCRIBED_MAILING"] = "<b>Не отправлено - находятся в списках отписавшихся</b>";   
$MESS[$module_id."_JS_NO_SEND_EXCLUDE_UNDELIVERED_MAILING"] = "<b>Не отправлено - находятся в черном списке</b>";  
$MESS[$module_id."_JS_NO_SEND_EXCLUDE_HOUR_AGO_MAILING"] = "<b>Не отправлено - исключены по дублированию письма:</b> ";  
$MESS[$module_id."_JS_SEND_END"] = "Рассылка завершена";  


$MESS[$module_id."_JS_MORE_INFO"] = " подробнее";
     
//шаблон письма
//START
$MESS["TABS_MESSAGE_NAME"] = 'Шаблон сообщения';  

$MESS["GROUP_MESSAGE_INFO_NAME"] = 'Параметры почтового шаблона';   
$MESS["GROUP_MESSAGE_NAME"] = 'Шаблон письма';  

$MESS["SUBJECT_TITLE"] = 'Тема письма';
$MESS["EMAIL_FROM_TITLE"] = 'От кого';
$MESS["EMAIL_TO_TITLE"] = 'Кому';
$MESS["BCC_TITLE"] = 'Копия'; 
$MESS["SITE_TEMPLATE_ID_TITLE"] = 'Тема оформления'; 
//END     
     
     
// рекомендованные товары
// START   
$MESS['TABS_RECOMMEND_NAME'] = 'Рекомендуемые товары';  
$MESS["GROUP_RECOMMEND_SETTING_NAME"] = 'Настройка рекомендуемых товаров';
$MESS["GROUP_RECOMMEND_FILLTER_NAME"] = 'Выберем и отфильтруем';  
     
$MESS['RECOMMEND_SHOW_TITLE'] = 'Выводить рекомендованные товары';         
$MESS['IBLOCK_TYPE_RECOMMEND_TITLE'] = 'Тип инфоблока';
$MESS['IBLOCK_ID_RECOMMEND_TITLE'] = 'Код инфоблока';  
$MESS['IBLOCK_SECTION_RECOMMEND_TITLE'] = 'Разделы инфоблока';     
$MESS['PROPERTY_FILLTER_1_RECOMMEND_TITLE'] = 'Фильтровать товары по свойству список';  
$MESS['PROPERTY_FILLTER_1_VALUE_RECOMMEND_TITLE'] = 'Значение фильтра свойства';      
$MESS['PROPERTY_FILLTER_1_VALUE_RECOMMEND_NOTES'] = 'Если не задан фильтр "Фильтровать товары по свойству список", то будут в отбор будут идти все товары из инфоблока';
$MESS['DATE_CREATE_AGO_RECOMMEND_TITLE'] = 'Дата создания';  
$MESS["TOP_COUNT_FILLTER_RECOMMEND_TITLE"] = 'Количество выводимых элементов'; 
   
   
   
$MESS["GROUP_RECOMMEND_SORT_NAME"] = 'Сортировка'; 
$MESS['SORT_BY_RECOMMEND_TITLE'] ="Сортировать";
    $MESS['SORT_BY_IBLOCK_LIST_VALUE_SORT'] ="индекс сортировки (sort)";
    $MESS['SORT_BY_IBLOCK_LIST_VALUE_ID'] ="ID элемента (id)";
    $MESS['SORT_BY_IBLOCK_LIST_VALUE_NAME'] ="название (name)";        
    $MESS['SORT_BY_IBLOCK_LIST_VALUE_CREATED'] ="время создания (created)";          
    $MESS['SORT_BY_IBLOCK_LIST_VALUE_SHOWS'] ="усредненное количество показов (shows)";    
    $MESS['SORT_BY_IBLOCK_LIST_VALUE_RAND'] ="случайный порядок (rand)";            
   
        
$MESS['SORT_ORDER_RECOMMEND_TITLE'] ="По";
    $MESS['SORT_ORDER_IBLOCK_LIST_VALUE_ASC'] ="Возрастанию (asc)";
    $MESS['SORT_ORDER_IBLOCK_LIST_VALUE_DESC'] ="Убыванию (desc)";
    
  
$MESS["GROUP_RECOMMEND_PRICE_NAME"] = 'Тип цены';   
$MESS["PRICE_TYPE_RECOMMEND_TITLE"] = 'Показывать цену';  
  
   
$MESS['GROUP_TEMP_RECOMMEND_NAME'] = 'Шаблон списка товаров (переменная #RECOMMEND_PRODUCT#)';  
$MESS['TABS_FORGET_BASKET_NAME'] = 'Список товаров';
$MESS["IMG_WIDTH_RECOMMEND_TITLE"] = "Ширина изображения товара";
$MESS["IMG_HEIGHT_RECOMMEND_TITLE"] = "Высота изображения товара"; 
$MESS["TEMP_TOP_RECOMMEND_TITLE"] = "Верх списка товаров";
$MESS["TEMP_TOP_RECOMMEND_DEFAULT"] = '
    <div style="background-color: #89cbf5; padding: 5px 10px 5px 10px;color: #FFFFFF; margin-bottom: 10px;"><b>Рекомендуем посмотреть:</b></div> 
    <div style="background-color: #fafbfb; border: 1px solid #d3dcdd; padding: 0px 20px 0px 20px;">         
    <table width="100%" cellpadding="0" cellspacing="0">
        <tbody>
';
$MESS["TEMP_LIST_RECOMMEND_TITLE"] = "Внешний вид товара, вывод в цикле";
$MESS["TEMP_LIST_RECOMMEND_DEFAULT"] = '
                <tr>
                    <td width="110px" style="vertical-align: top; padding-bottom: 20px;padding-top: 20px; #BORDER_TABLE_STYLE#">
                        <a href="#DETAIL_PAGE_URL#"><img src="#PICTURE_SRC#" width="#PICTURE_WIDTH#" height="#PICTURE_HEIGHT#" /></a>     
                    </td>
                    <td style="vertical-align: top; padding-bottom: 20px;padding-top: 20px; #BORDER_TABLE_STYLE#">
                        <a href="#DETAIL_PAGE_URL#" style="color: #00b6f4; font-size: 14px"; >#NAME#</a> <br />
                        <br />
                         #PREVIEW_TEXT#
                    </td>
                    <td  style="vertical-align: top; padding-bottom: 20px;padding-top: 20px; #BORDER_TABLE_STYLE#">
                        <s style="color:red;display:block;">#PRINT_NO_DISCOUNT_PRICE#</s>
                        <b style="white-space: nowrap">#PRINT_PRICE#</b>
                    </td>                    
                </tr>   
'; 
$MESS["TEMP_LIST_RECOMMEND_NOTES"] = '
    Переменные элемента инфоблока:   <br />
    #ID# - ID элементы <br />   
    #NAME# - Имя товара<br />   
    #PREVIEW_TEXT# - анонс описания<br />   
    #DETAIL_TEXT#  - детальное описание<br />   
    #LIST_PAGE_URL#  - ссылка на товары из категории <br />   
    #DETAIL_PAGE_URL#  - ссылка на товар <br />     
    #PICTURE_SRC# - пусть до изображения <br /> 
    #PICTURE_WIDTH# - ширина изображения <br />  
    #PICTURE_HEIGHT# - высота изображения <br />

    <br />
    Цена товара:  <br />
    #PRINT_PRICE# - цена товара со валютой<br />
    #PRICE# - цена товара без валюты<br />    
    #PRINT_NO_DISCOUNT_PRICE# - цена товара без скидки с валютой<br />
    #NO_DISCOUNT_PRICE# - цена товара без скидки без валюты<br />    
    #PRINT_DISCOUNT_DIFF# - величина скидки с валютой<br />
    #DISCOUNT_DIFF# - величина скидки без валюты<br />      
    <br />
    Переменные свойств инфоблока: <br />
    #IBLOCK_PROP#          
    <br />
    Дополнительно:  <br />
    #BORDER_TABLE_STYLE# - стиль border-top: 1px solid #E6EAEC; кроме первого элемента<br />
';  
$MESS["TEMP_BOTTOM_RECOMMEND_TITLE"] = "Низ списка товаров";
$MESS["TEMP_BOTTOM_RECOMMEND_DEFAULT"] = '
        </tbody>
    </table>
</div>
'; 

$MESS['GROUP_RECOMMEND_ADDITIONAL_SETTING_NAME'] = 'Дополнительные настройки';
$MESS['CANCEL_EMPTY_RECOMMEND_TITLE'] = 'Отменить рассылку без элементов';
$MESS['CANCEL_EMPTY_RECOMMEND_NOTES'] = 'Если при выборке не оказалось элементов инфоблока то рассылка будет отменена.';


// PHP модификация  
$MESS["GROUP_PHP_MODIF_RECOMMEND_NAME"] = 'Модификация рекомендумых товаров (для разработчиков)';
$MESS['INCLUDE_PHP_MODIF_RECOMMEND_TITLE'] = 'Включить поля для разработчиков';

$MESS["PHP_RECOMMEND_FILLTER_BEFORE_TITLE"] = 'PHP: Перед выборкой товаров';
$MESS["PHP_RECOMMEND_FILLTER_BEFORE_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
Подлючается до выборки товаров с помощью функции <a target="_blank" href="http://dev.1c-bitrix.ru/api_help/iblock/classes/ciblockelement/getlist.php">CIBlockElement::GetList</a>.<br/>
Вы можете переопределить значения выборкм, либо объединить свой с существующими  <a href="http://www.php.su/array_merge" target="_blank">array_merge($arFilterRecommend, $MyArray)</a>. <br/>
<b>Переменные:</b> <br />
$arSortRecpmmend - сортировка<br />
$arFilterRecommend - фильтрация <br />
$arNavStartParams - навигация  <br />
$arSelectRecommend - выбор данных<br />

<br />
<br />
<b>Отладку</b> можно вести через <a href="/bitrix/admin/php_command_line.php?lang=ru" target="_blank">коммандную PHP-строку</a> в тестовом режиме рассылки.<br />
';    
 
 
$MESS["PHP_RECOMMEND_FILLTER_WHILE_AFTER_TITLE"] = 'PHP: В конце цикла выборки';
$MESS["PHP_RECOMMEND_FILLTER_WHILE_AFTER_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
Подключается в конце цикла while, позволяет модифицировать полученные данные, добавить новые.<br />
Массив $arFields, хранит в себе данные конкретного товара.<br />
<br />
<b>Функции:</b> <br />
$phpIncludeFunction["isContinue"]="Y" - пропустить итерацию <br /> 
$phpIncludeFunction["isBreak"]="Y" - прервать работу цикла<br />   
<br />
<br />
<b>Отладку</b> можно вести через <a href="/bitrix/admin/php_command_line.php?lang=ru" target="_blank">коммандную PHP-строку</a> в тестовом режиме рассылки.<br />
';    
    
          
// END       


// просмотренные товары
// START   
$MESS['TABS_VIEWED_NAME'] = 'Просмотренные товары';  
$MESS["GROUP_VIEWED_SETTING_NAME"] = 'Настройка просмотренных товаров';
$MESS["GROUP_VIEWED_FILLTER_NAME"] = 'Отфильтруем товары';  
     
$MESS['VIEWED_SHOW_TITLE'] = 'Выводить просмотренные пользователем товары';         
$MESS['IBLOCK_TYPE_VIEWED_TITLE'] = 'Тип инфоблока';
$MESS['IBLOCK_ID_VIEWED_TITLE'] = 'Код инфоблока'; 
$MESS['IBLOCK_SECTION_VIEWED_TITLE'] = 'Разделы инфоблока';       
$MESS['PROPERTY_FILLTER_1_VIEWED_TITLE'] = 'Фильтровать товары по свойству список';  
$MESS['PROPERTY_FILLTER_1_VALUE_VIEWED_TITLE'] = 'Значение фильтра свойства';      
$MESS['PROPERTY_FILLTER_1_VALUE_VIEWED_NOTES'] = 'Оставьте параметры пустыми если не нужно фильтровать просмотренные товары.<br />
Если не задан фильтр "Фильтровать товары по свойству список", то будут в отбор будут идти все товары из инфоблока';
$MESS["TOP_COUNT_FILLTER_VIEWED_TITLE"] = 'Количество выводимых элементов'; 
   
     
$MESS["GROUP_VIEWED_SORT_NAME"] = 'Сортировка'; 
$MESS['SORT_BY_VIEWED_TITLE'] ="Сортировать";
    $MESS['SORT_BY_VIEWED_VALUE_DATE_VISIT'] ="Дата просмотра (DATE_VISIT)";
    $MESS['SORT_BY_VIEWED_VALUE_VIEW_COUNT'] ="Количество просмотров товара (VIEW_COUNT)";

        
$MESS['SORT_ORDER_VIEWED_TITLE'] ="По";
    $MESS['SORT_ORDER_IBLOCK_LIST_VALUE_ASC'] ="Возрастанию (asc)";
    $MESS['SORT_ORDER_IBLOCK_LIST_VALUE_DESC'] ="Убыванию (desc)";
    
  
$MESS["GROUP_VIEWED_PRICE_NAME"] = 'Тип цены';   
$MESS["PRICE_TYPE_VIEWED_TITLE"] = 'Показывать цену';  
  
   
$MESS['GROUP_TEMP_VIEWED_NAME'] = 'Шаблон списка товаров (переменная #VIEWED_PRODUCT#)';  
$MESS['TABS_FORGET_BASKET_NAME'] = 'Список товаров';
$MESS["IMG_WIDTH_VIEWED_TITLE"] = "Ширина изображения товара";
$MESS["IMG_HEIGHT_VIEWED_TITLE"] = "Высота изображения товара"; 
$MESS["TEMP_TOP_VIEWED_TITLE"] = "Верх списка товаров";
$MESS["TEMP_TOP_VIEWED_DEFAULT"] = '
    <div style="background-color: #89cbf5; padding: 5px 10px 5px 10px;color: #FFFFFF; margin-bottom: 10px;"><b>Вы интересовались:</b></div> 
    <div style="background-color: #fafbfb; border: 1px solid #d3dcdd; padding: 0px 20px 0px 20px;">         
    <table width="100%" cellpadding="0" cellspacing="0">
        <tbody>
';
$MESS["TEMP_LIST_VIEWED_TITLE"] = "Внешний вид товара, вывод в цикле";
$MESS["TEMP_LIST_VIEWED_DEFAULT"] = '
                <tr>
                    <td width="110px" style="vertical-align: top; padding-bottom: 20px;padding-top: 20px; #BORDER_TABLE_STYLE#">
                        <a href="#DETAIL_PAGE_URL#"><img src="#PICTURE_SRC#" width="#PICTURE_WIDTH#" height="#PICTURE_HEIGHT#" /></a>     
                    </td>
                    <td style="vertical-align: top; padding-bottom: 20px;padding-top: 20px; #BORDER_TABLE_STYLE#">
                        <a href="#DETAIL_PAGE_URL#" style="color: #00b6f4; font-size: 14px"; >#NAME#</a> <br />
                        <br />
                         #PREVIEW_TEXT#
                    </td>
                    <td  style="vertical-align: top; padding-bottom: 20px;padding-top: 20px; #BORDER_TABLE_STYLE#">
                        <s style="color:red;display:block;">#PRINT_NO_DISCOUNT_PRICE#</s>
                        <b style="white-space: nowrap">#PRINT_PRICE#</b>
                    </td>                    
                </tr>   
'; 
$MESS["TEMP_LIST_VIEWED_NOTES"] = '
    Переменные элемента инфоблока:   <br />
    #ID# - ID элементы <br />   
    #NAME# - Имя товара<br />   
    #PREVIEW_TEXT# - анонс описания<br />   
    #DETAIL_TEXT#  - детальное описание<br />   
    #LIST_PAGE_URL#  - ссылка на товары из категории <br />   
    #DETAIL_PAGE_URL#  - ссылка на товар <br />     
    #PICTURE_SRC# - пусть до изображения <br /> 
    #PICTURE_WIDTH# - ширина изображения <br />  
    #PICTURE_HEIGHT# - высота изображения <br />

    <br />
    Цена товара:  <br />
    #PRINT_PRICE# - цена товара со валютой<br />
    #PRICE# - цена товара без валюты<br />    
    #PRINT_NO_DISCOUNT_PRICE# - цена товара без скидки с валютой<br />
    #NO_DISCOUNT_PRICE# - цена товара без скидки без валюты<br />    
    #PRINT_DISCOUNT_DIFF# - величина скидки с валютой<br />
    #DISCOUNT_DIFF# - величина скидки без валюты<br />      
    <br />
    Переменные свойств инфоблока: <br />
    #IBLOCK_PROP#          
    <br />
    Дополнительно:  <br />
    #BORDER_TABLE_STYLE# - стиль border-top: 1px solid #E6EAEC; кроме первого элемента<br />
';  
$MESS["TEMP_BOTTOM_VIEWED_TITLE"] = "Низ списка товаров";
$MESS["TEMP_BOTTOM_VIEWED_DEFAULT"] = '
        </tbody>
    </table>
</div>
'; 

// PHP модификация  
$MESS["GROUP_PHP_MODIF_VIEWED_NAME"] = 'Модификация просмотренных товаров (для разработчиков)';
$MESS['INCLUDE_PHP_MODIF_VIEWED_TITLE'] = 'Включить поля для разработчиков';

$MESS["PHP_VIEWED_FILLTER_BEFORE_TITLE"] = 'PHP: Перед выборкой товаров';
$MESS["PHP_VIEWED_FILLTER_BEFORE_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
Подлючается до выборки товаров с помощью функции <a target="_blank" href="http://dev.1c-bitrix.ru/api_help/iblock/classes/ciblockelement/getlist.php">CIBlockElement::GetList</a>.<br/>
Вы можете переопределить значения выборкм, либо объединить свой с существующими  <a href="http://www.php.su/array_merge" target="_blank">array_merge($arFilterRecommend, $MyArray)</a>. <br/>
<b>Переменные:</b> <br />
$arSortRecpmmend - сортировка<br />
$arFilterRecommend - фильтрация <br />
$arNavStartParams - навигация  <br />
$arSelectRecommend - выбор данных<br />

<br />
<br />
<b>Отладку</b> можно вести через <a href="/bitrix/admin/php_command_line.php?lang=ru" target="_blank">коммандную PHP-строку</a> в тестовом режиме рассылки.<br />
';    
 
 
$MESS["PHP_VIEWED_FILLTER_WHILE_AFTER_TITLE"] = 'PHP: В конце цикла выборки';
$MESS["PHP_VIEWED_FILLTER_WHILE_AFTER_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
Подключается в конце цикла while, позволяет модифицировать полученные данные, добавить новые.<br />
Массив $arFields, хранит в себе данные конкретного товара.<br />

<br />
<br />
<b>Отладку</b> можно вести через <a href="/bitrix/admin/php_command_line.php?lang=ru" target="_blank">коммандную PHP-строку</a> в тестовом режиме рассылки.<br />
';             
// END



//Скидки
//START
$MESS['TABS_DISCOUNT_NAME'] = 'Скидки';

$MESS["GROUP_PARAM_NEW_COUPON_ADD"] = 'Параметры купона правила корзины';
$MESS["NEW_COUPON_ADD_TITLE"] = 'Создавать купон правила корзины';
$MESS["NEW_COUPON_ADD_NOTES"] = 'Создание купона правила корзины заменяет создание скидочного купона! Функционал скидочных купонов является устаревшим!';
$MESS["NEW_COUPON_DISCOUNT_ID_TITLE"] = 'Правило корзины (заранее создайте)';
$MESS["NEW_COUPON_TYPE_TITLE"] = 'Тип купона';
    $MESS["NEW_COUPON_ONE_TIME"] = 'на одну позицию заказа';
    $MESS["NEW_COUPON_ONE_ORDER"] = 'на один заказ';
    $MESS["NEW_COUPON_NO_LIMIT"] = 'многоразовый';
$MESS["NEW_COUPON_LIFETIME_TITLE"] = 'Время возможности использования купона в часах';
$MESS["NEW_COUPON_LIFETIME_ACTION_TITLE"] = 'Неиспользованные купоны необходимо';
    $MESS["NEW_COUPON_LIFETIME_ACTION_VALUE_DELETE"] = 'Удалить';
    $MESS["NEW_COUPON_LIFETIME_ACTION_VALUE_DEACTIVATE"] = 'Деактивировать';

$MESS["GROUP_PARAM_COUPON_ADD"] = 'Параметры скидочного купона';
$MESS["COUPON_ADD_TITLE"] = 'Создавать скидочный купон';
$MESS["COUPON_DISCOUNT_ID_TITLE"] = 'Скидка, в которой создавать купон (заранее создайте)';
$MESS["COUPON_ONE_TIME_TITLE"] = 'Тип купона';
    $MESS["COUPON_ONE_TIME_VALUE_Y"] = 'Купон на одну позицию заказа';
    $MESS["COUPON_ONE_TIME_VALUE_O"] = 'Купон на один заказ';
    $MESS["COUPON_ONE_TIME_VALUE_N"] = 'Многоразовый купон';
$MESS["COUPON_TIME_LIFE_TITLE"] = 'Время возможности использования купона в часах';
$MESS["COUPON_TIME_LIFE_ACTION_TITLE"] = 'Неиспользованные купоны необходимо';
    $MESS["COUPON_TIME_LIFE_ACTION_VALUE_DELETE"] = 'Удалить';
    $MESS["COUPON_TIME_LIFE_ACTION_VALUE_DEACTION"] = 'Деактивировать';
//END



// PHP модификация - дополнительные данные и логика рассылки     
//START 
$MESS["GROUP_PHP_MODIF_MAILING_NAME"] = 'Модификация рассылки (для разработчиков)';

$MESS['INCLUDE_PHP_MODIF_MAILING_TITLE'] = 'Включить поля для разработчиков';
//перед циклом
$MESS["PHP_MESSAGE_FOREACH_BEFORE_TITLE"] = 'PHP: До цикла отправки сообщений';
$MESS["PHP_MESSAGE_FOREACH_BEFORE_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
Подключается перед циклом отправки сообщений рассылки, можно использовать для выборки различных данных и использования их в процессе рассылки.<br />
Массив $arrEmailSend, хранит в себе данные отправляемых сообщений.  <br />
<br />
<br />
<b>Отладку</b> можно вести через <a href="/bitrix/admin/php_command_line.php?lang=ru" target="_blank">коммандную PHP-строку</a> в тестовом режиме рассылки.<br />
';

// в цикле
$MESS["PHP_MESSAGE_FOREACH_ITEM_BEFORE_TITLE"] = 'PHP: В начале цикла отправки сообщения';
$MESS["PHP_MESSAGE_FOREACH_ITEM_BEFORE_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
Подключается в начале цикла foreach, позволяет модифицировать отправку добавив новые данные, массив с данными отправки.<br />
Массив $ItemEmailSend, хранит в себе данные конкретного сообщения которое будет отправлено.<br />
<br />
<b>Функции:</b> <br />
$phpIncludeFunction["isContinue"]="Y" - пропустить итерацию <br /> 
$phpIncludeFunction["isBreak"]="Y" - прервать работу цикла<br />   
<br />
<br />
<b>Отладку</b> можно вести через <a href="/bitrix/admin/php_command_line.php?lang=ru" target="_blank">коммандную PHP-строку</a> в тестовом режиме рассылки.<br />
';     
//END  
          
?>