<?
     
$MESS["GROUP_PARAM_USER_FILLTER"] = 'Выбирем группу подписчиков';
$MESS["CATEGORIES_ID_TITLE"] = 'Категории подписчиков';
$MESS["CATEGORIES_ID_NOTES"] = 'Выберите категории, по которым производить поиск новинок для подписчиков, если пусто то поиск будет по всему списку категорий.';

   
      
// новинки товаров
// START  
$MESS["GROUP_NOVELTY_GOODS_SETTING_NAME"] = 'Настройка рекомендуемых товаров';
$MESS["GROUP_NOVELTY_GOODS_FILLTER_TIME_NAME"] = 'Время выборки новинок'; 

$MESS["GROUP_NOVELTY_GOODS_FILLTER_NAME"] = 'Выберем и отфильтруем';  
     
$MESS['NOVELTY_GOODS_SHOW_TITLE'] = 'Выводить рекомендованные товары';         
$MESS['IBLOCK_TYPE_NOVELTY_GOODS_TITLE'] = 'Тип инфоблока';
$MESS['IBLOCK_ID_NOVELTY_GOODS_TITLE'] = 'Код инфоблока';     
$MESS['PROPERTY_FILLTER_1_NOVELTY_GOODS_TITLE'] = 'Фильтровать товары по свойству список';  
$MESS['PROPERTY_FILLTER_1_VALUE_NOVELTY_GOODS_TITLE'] = 'Значение фильтра свойства';      
$MESS['PROPERTY_FILLTER_1_VALUE_NOVELTY_GOODS_NOTES'] = 'Оставьте параметры пустыми если не нужно фильтровать новинки.<br />
Если не задан фильтр "Фильтровать товары по свойству список", то будут в отбор будут идти все товары из инфоблока';
$MESS["TOP_COUNT_FILLTER_NOVELTY_GOODS_FROM_TITLE"] = 'Количество выводимых элементов (от)'; 
$MESS["TOP_COUNT_FILLTER_NOVELTY_GOODS_TO_TITLE"] = 'Количество выводимых элементов (до)';    
$MESS["TOP_COUNT_FILLTER_NOVELTY_GOODS_TO_NOTES"] = 'Если количество новинок в разделе меньше значения «Количество выводимых элементов (от)», то рассылка по категории инфоблока не будет произведена.';    

$MESS["NOVELTY_GOODS_DATE_CREATE_TITLE"] = 'Товар создан';
$MESS["NOVELTY_GOODS_HOURS_AGO_START_TITLE"] = 'Товар создан часов назад (от)';
$MESS["NOVELTY_GOODS_HOURS_AGO_END_TITLE"] = 'Товар создан часов назад (до)';
$MESS["NOVELTY_GOODS_HOURS_AGO_END_NOTES"] = 'Данные для выборки новинок из раздела от данного момента.<br /> 
Например, вы хотите выбрать новинки которые были созданы за неделю, заполните от 0 часов до 168';

$MESS["GROUP_NOVELTY_GOODS_SORT_NAME"] = 'Сортировка'; 
$MESS['SORT_BY_NOVELTY_GOODS_TITLE'] ="Сортировать";
    $MESS['SORT_BY_IBLOCK_LIST_VALUE_SORT'] ="индекс сортировки (sort)";
    $MESS['SORT_BY_IBLOCK_LIST_VALUE_ID'] ="ID элемента (id)";
    $MESS['SORT_BY_IBLOCK_LIST_VALUE_NAME'] ="название (name)";        
    $MESS['SORT_BY_IBLOCK_LIST_VALUE_CREATED'] ="время создания (created)";          
    $MESS['SORT_BY_IBLOCK_LIST_VALUE_SHOWS'] ="усредненное количество показов (shows)";    
    $MESS['SORT_BY_IBLOCK_LIST_VALUE_RAND'] ="случайный порядок (rand)";            
   
        
$MESS['SORT_ORDER_NOVELTY_GOODS_TITLE'] ="По";
    $MESS['SORT_ORDER_IBLOCK_LIST_VALUE_ASC'] ="Возрастанию (asc)";
    $MESS['SORT_ORDER_IBLOCK_LIST_VALUE_DESC'] ="Убыванию (desc)";
    
  
$MESS["GROUP_NOVELTY_GOODS_PRICE_NAME"] = 'Тип цены';   
$MESS["PRICE_TYPE_NOVELTY_GOODS_TITLE"] = 'Показывать цену';  



$MESS["GROUP_TEMP_NOVELTY_GOODS"] = 'Шаблон списка новинок (переменная #NOVELTY_GOODS#)';        
$MESS['TABS_NOVELTY_GOODS_NAME'] = 'Список новинок';
$MESS["NOVELTY_GOODS_IMG_WIDTH_TITLE"] = "Ширина изображения товара";
$MESS["NOVELTY_GOODS_IMG_HEIGHT_TITLE"] = "Высота изображения товара"; 
$MESS["TEMP_NOVELTY_GOODS_TOP_TITLE"] = "Верх списка товаров";
$MESS["TEMP_NOVELTY_GOODS_TOP_DEFAULT"] = '
    <div style="background-color: #89cbf5; padding: 5px 10px 5px 10px;color: #FFFFFF; margin-bottom: 10px;"><b>Новинки бренда #PROPERTY_VALUE#:</b></div> 
    <div style="background-color: #fafbfb; border: 1px solid #d3dcdd; padding: 0px 20px 0px 20px;">         
    <table width="100%" cellpadding="0" cellspacing="0">
        <tbody>
';
$MESS["TEMP_NOVELTY_GOODS_TOP_NOTES"] = ' 
    #PROPERTY_VALUE# - значение свойства<br />
'; 

$MESS["TEMP_NOVELTY_GOODS_LIST_TITLE"] = "Внешний вид товаров";
$MESS["TEMP_NOVELTY_GOODS_LIST_DEFAULT"] = '
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
$MESS["TEMP_NOVELTY_GOODS_LIST_NOTES"] = '
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
    Переменные свойств инфоблока: <br />
    #IBLOCK_PROP#    
    #PROP_СИМВОЛЬНЫЙ_КОД_СВОЙСТВА_ИНФОБЛОКА# - вывод свойства товарв из инфоблока    
    <br />
    <br />
    Цена товара:  <br />
    #PRINT_PRICE# - цена товара со валютой<br />
    #PRICE# - цена товара без валюты<br />    
    #PRINT_NO_DISCOUNT_PRICE# - цена товара без скидки с валютой<br />
    #NO_DISCOUNT_PRICE# - цена товара без скидки без валюты<br />    
    #PRINT_DISCOUNT_DIFF# - величина скидки с валютой<br />
    #DISCOUNT_DIFF# - величина скидки без валюты<br />      
    <br />
    <br />

    <br /> <br />
    Дополнительно:  <br />  
    #BORDER_TABLE_STYLE# - стиль border-top: 1px solid #E6EAEC; кроме первого элемента<br />
';  
 

$MESS["TEMP_NOVELTY_GOODS_BOTTOM_TITLE"] = "Низ списка товаров";
$MESS["TEMP_NOVELTY_GOODS_BOTTOM_DEFAULT"] = '
        </tbody>
    </table>
</div>
<br />
';
$MESS["TEMP_NOVELTY_GOODS_BOTTOM_NOTES"] = ' 
    #PROPERTY_VALUE# - значение свойства<br />
   
'; 


// PHP модификация  
$MESS["GROUP_PHP_MODIF_NOVELTY_GOODS_NAME"] = 'Модификация списка товаров (для разработчиков)';


$MESS["PHP_NOVELTY_GOODS_FILLTER_BEFORE_TITLE"] = 'PHP: Перед выборкой товаров';
$MESS["PHP_NOVELTY_GOODS_FILLTER_BEFORE_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
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
 
 
$MESS["PHP_NOVELTY_GOODS_FILLTER_WHILE_AFTER_TITLE"] = 'PHP: В конце цикла выборки';
$MESS["PHP_NOVELTY_GOODS_FILLTER_WHILE_AFTER_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
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
      
      
      

$MESS["SUBJECT_DEFAULT"] = 'Новое поступление в магазин!';
$MESS["MESSAGE_DEFAULT"] = '
    <p><b>Добрый день #USER_NAME#!</b></p>
    <br />
    <p>Мы рады вам сообщить что у нас появились новые товары, вы просили нас сообщать о поступлениях.</p>        
    <p>Мы подготовили для вас подарочный купон <b>#COUPON#</b> с 5% скидкой, который будет действовать 2 дня.</p>
    #NOVELTY_GOODS#
';   
  
   
  
$MESS["GROUP_MESSAGE_NOTES"] = '
   <b>Статистика и отписка от рассылки: </b>  <br />
   #MAILING_MESSAGE# - для сбора статистики необходимо ссылки ставить с переменной ?MAILING_MESSAGE=#MAILING_MESSAGE# <br />
   #MAILING_UNSUBSCRIBE# - для возможности отписаться от рассылки ставите ссылку с переменной ?MAILING_UNSUBSCRIBE=#MAILING_UNSUBSCRIBE# <br />  
   #MAILING_EVENT_ID# - ID рассылки<br />   
   <br />
   Информация по подписчикам:  <br />
   #SUBSCRIBLE_EMAIL_TO# - email почта <br />
   #SUBSCRIBLE_USER_ID# - ID пользователя<br />
   #SUBSCRIBLE_DATE_CREATE# - дата создания<br />
   <br />
   
   Информация о пользователе (будут только для тех пользователей у кого есть привязка к USER_ID):<br />
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


// Перед выборкой пользователей
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

//в конце цикла выборки пользователей
$MESS["PHP_FILLTER_USER_PARAM_AFTER_TITLE"] = 'PHP: В конце цикла выборки пользователей';
$MESS["PHP_FILLTER_USER_PARAM_AFTER_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
Подключается в конце цикла while, позволяет модифицировать полученные данные, добавить новые.<br />
Массив $arItemsUser, хранит в себе данные конкретного пользователя.<br />
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
$MESS['SELECT_ALL'] = '--Все--';  
  
  
  
  
$MESS["TABS_KIT_MAILING_INSTRUCTION_TITLE"] = "Видео-инструкции";
$MESS["GROUP_TABS_KIT_MAILING_INSTRUCTION_NAME"] = "Видео-инструкции";
$MESS["TABS_KIT_MAILING_INSTRUCTION_DEFAULT"] = '
    <div style="text-align:center">
    
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
        <h3>Если у вас возникли проблемы или вопросы с настройкой рассылок, смело пишите в <a href="https://asdaff.github.io//support/" target="_blank">техническую поддержку</a> компании «KIT», мы обязательно вам поможем.</h3>
                                  
    </div>

';        
     
     
?>
