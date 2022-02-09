<?
$MESS['SITE_ID_ALL'] = 'Все'; 
$MESS['SITE_ID_TITLE'] = 'Сайт';


$MESS['SELECT_PARAM_ALL'] = '(Все)';
$MESS['SELECT_PARAM_Y'] = 'Да';
$MESS['SELECT_PARAM_N'] = 'Нет';

$MESS['GROUP_PARAM_ORDER_FILLTER'] = 'Фильтр заказов (общие)'; 
$MESS['GROUP_PARAM_ORDER_STATUS_FILLTER'] = 'Фильтр заказов (статус)'; 
$MESS['GROUP_PARAM_ORDER_PAY_FILLTER'] = 'Фильтр заказов (оплата)'; 
$MESS['GROUP_PARAM_ORDER_DELIVERY_FILLTER'] = 'Фильтр заказов (доставка)'; 
$MESS['GROUP_PARAM_ORDER_CANCELED_FILLTER'] = 'Фильтр заказов (отмена)'; 

$MESS['ORDER_FILLTER_PERSON_TYPE_ID_TITLE'] = 'Тип плательщика:'; 
$MESS['ORDER_FILLTER_USER_ID_TITLE'] = 'Пользователь'; 
$MESS['ORDER_FILLTER_PRICE_TITLE'] = 'Цена заказа'; 

$MESS['ORDER_FILLTER_DATE_INSERT_TITLE'] = 'Дата создания';
$MESS['ORDER_FILLTER_DATE_UPDATE_TITLE'] = 'Дата изменения';

$MESS['ORDER_FILLTER_STATUS_TITLE'] = 'Статус заказа';
$MESS['ORDER_FILLTER_DATE_STATUS_TITLE'] = 'Дата изменения статуса';

$MESS['ORDER_FILLTER_PAY_SYSTEM_ID_TITLE'] = 'Система оплаты';
$MESS['ORDER_FILLTER_PAYED_TITLE'] = 'Оплачен';
$MESS['ORDER_FILLTER_DATE_PAYED_TITLE'] = 'Дата оплаты';  

$MESS['ORDER_FILLTER_DELIVERY_ID_TITLE'] = 'Система доставки';
$MESS['ORDER_FILLTER_ALLOW_DELIVERY_TITLE'] = 'Доставка разрешена';
$MESS['ORDER_FILLTER_DATE_ALLOW_DELIVERY_TITLE'] = 'Дата разрешения доставки';
 
$MESS['ORDER_FILLTER_CANCELED_TITLE'] = 'Отмена заказа';
$MESS['ORDER_FILLTER_DATE_CANCELED_TITLE'] = 'Дата отмены заказа'; 
      
     

$MESS['ORDER_PROPER_COUNTRY'] = 'страна';
$MESS['ORDER_PROPER_CITY'] = 'город';
    
        

      
// товары в корзине   
// START    
$MESS["GROUP_TEMP_FORGET_BASKET"] = 'Шаблон списка товаров в заказе (переменная #FORGET_BASKET#)';   
$MESS['TABS_FORGET_BASKET_NAME'] = 'Список товаров';
$MESS["FORGET_BASKET_IMG_WIDTH_TITLE"] = "Ширина изображения товара";
$MESS["FORGET_BASKET_IMG_HEIGHT_TITLE"] = "Высота изображения товара"; 
$MESS["TEMP_FORGET_BASKET_TOP_TITLE"] = "Верх списка товаров";
$MESS["TEMP_FORGET_BASKET_TOP_DEFAULT"] = '
    <div style="background-color: #89cbf5; padding: 5px 10px 5px 10px;color: #FFFFFF; margin-bottom: 10px;"><b>Товары в заказе:</b></div> 
    <div style="background-color: #fafbfb; border: 1px solid #d3dcdd; padding: 0px 20px 0px 20px;">         
    <table width="100%" cellpadding="0" cellspacing="0">
        <tbody>
';
 

$MESS["TEMP_FORGET_BASKET_LIST_TITLE"] = "Внешний вид товаров";
$MESS["TEMP_FORGET_BASKET_LIST_DEFAULT"] = '
                <tr>
                    <td width="110px" style="vertical-align: top; padding-bottom: 20px;padding-top: 20px; #BORDER_TABLE_STYLE#">
                        <a href="#BASKET_DETAIL_PAGE_URL#"><img src="#PRODUCT_PICTURE_SRC#" width="#PRODUCT_PICTURE_WIDTH#" height="#PRODUCT_PICTURE_HEIGHT#" /></a>     
                    </td>
                    <td style="vertical-align: top; padding-bottom: 20px;padding-top: 20px; #BORDER_TABLE_STYLE#">
                        <a href="#BASKET_DETAIL_PAGE_URL#" style="color: #00b6f4; font-size: 14px"; >#BASKET_NAME#</a> <br />
                        <br />
                         Количество: #BASKET_QUANTITY# 
                    </td>
                    <td width="100px" style="vertical-align: top; padding-bottom: 20px;padding-top: 20px; #BORDER_TABLE_STYLE#" align="center">
                        <b style=" font-size: 16px;"> #BASKET_PRICE_FORMAT# </b>            
                    </td>
                </tr>   
'; 
$MESS["TEMP_FORGET_BASKET_LIST_NOTES"] = '
    Переменные корзины:   <br />
    #BASKET_DETAIL_PAGE_URL# - Ссылка на товар с заказа <br />   
    #BASKET_NAME# - Имя товара с заказа<br />   
    #BASKET_QUANTITY# - количество товаров в заказе <br />   
    #BASKET_PRICE#  - цена из заказа  <br />   
    #BASKET_PRICE_FORMAT#  - цена из корзины с валютой <br />   
    <br />
    Переменные товара: <br />
    #PRODUCT_ID#  - ID товара  <br />
    #PRODUCT_NAME#  - имя товара   <br />
    #PRODUCT_PICTURE_SRC# - пусть до изображения <br /> 
    #PRODUCT_PICTURE_WIDTH# - ширина изображения <br />  
    #PRODUCT_PICTURE_HEIGHT# - высота изображения <br />           
    #PRODUCT_PREVIEW_TEXT# - текст анонса товара<br />  
    #PRODUCT_DETAIL_TEXT# - детальный текст товара<br />       
    #PRODUCT_LIST_PAGE_URL# - ссылка на список товаров <br />   
    #PRODUCT_DETAIL_PAGE_URL# - ссылка на детальную страницу товара<br />   
    #PRODUCT_PROP_СИМВОЛЬНЫЙ_КОД_СВОЙСТВА_ИНФОБЛОКА# - вывод свойства товарв из инфоблока<br />
    <br />
    Переменные торгового предложения: <br />
    #PRODUCT_OFFER_ID#  - ID товара  <br />
    #PRODUCT_OFFER_NAME#  - имя товара   <br />
    #PRODUCT_OFFER_PICTURE_SRC# - пусть до изображения <br /> 
    #PRODUCT_OFFER_PICTURE_WIDTH# - ширина изображения <br />  
    #PRODUCT_OFFER_PICTURE_HEIGHT# - высота изображения <br />           
    #PRODUCT_OFFER_PREVIEW_TEXT# - текст анонса товара<br />  
    #PRODUCT_OFFER_DETAIL_TEXT# - детальный текст товара<br />       
    #PRODUCT_OFFER_LIST_PAGE_URL# - ссылка на список товаров <br />   
    #PRODUCT_OFFER_DETAIL_PAGE_URL# - ссылка на детальную страницу товара<br />   
    #PRODUCT_OFFER_PROP_СИМВОЛЬНЫЙ_КОД_СВОЙСТВА_ИНФОБЛОКА# - вывод свойства товарв из инфоблока<br />   
          
    <br />
    Дополнительно:  <br />
    #BORDER_TABLE_STYLE# - стиль border-top: 1px solid #E6EAEC; кроме первого элемента<br />
';  
 

$MESS["TEMP_FORGET_BASKET_BOTTOM_TITLE"] = "Низ списка товаров";
$MESS["TEMP_FORGET_BASKET_BOTTOM_DEFAULT"] = '
        </tbody>
    </table>
</div>
';
// PHP модификация  
$MESS["GROUP_PHP_MODIF_BASKET_PRODUCT_NAME"] = 'Модификация списка товаров (для разработчиков)';


$MESS["PHP_BASKET_PRODUCT_FILLTER_BEFORE_TITLE"] = 'PHP: Перед выборкой товаров';
$MESS["PHP_BASKET_PRODUCT_FILLTER_BEFORE_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
Подлючается до выборки товаров с помощью функции <a target="_blank" href="http://dev.1c-bitrix.ru/api_help/iblock/classes/ciblockelement/getlist.php">CIBlockElement::GetList</a>.<br/>
Вы можете переопределить значения выборкм, либо объединить свой с существующими  <a href="http://www.php.su/array_merge" target="_blank">array_merge($arFilterRecommend, $MyArray)</a>. <br/>
<b>Переменные:</b> <br />
$arSortProduct - сортировка<br />
$arFilterProduct - фильтрация <br />
$arSelectProduct - выбор данных<br />

<br />
<br />
<b>Отладку</b> можно вести через <a href="/bitrix/admin/php_command_line.php?lang=ru" target="_blank">коммандную PHP-строку</a> в тестовом режиме рассылки.<br />
';    
 
 
$MESS["PHP_BASKET_PRODUCT_FILLTER_WHILE_AFTER_TITLE"] = 'PHP: В конце цикла выборки';
$MESS["PHP_BASKET_PRODUCT_FILLTER_WHILE_AFTER_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
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
      
      

 
            
      
  
// исключения из рассылки
// START  
$MESS['GROUP_PARAM_EXCEPTIONS'] = 'Исключить из рассылки';  
$MESS['EXCEPTIONS_SALE_SEND_TITLE'] = 'Заказы получившие сообщение';
$MESS['EXCEPTIONS_SALE_SEND_NOTES'] = 'Заказы, получившие сообщение по данному сценарию ранее, будут исключены.';  
$MESS['EXCEPTIONS_USER_SEND_TITLE'] = 'Пользователи получившие сообщение';
$MESS['EXCEPTIONS_USER_SEND_NOTES'] = 'Пользователи, получившие сообщение по данному сценарию ранее, будут исключены.<br />
Это полезно в случаях когда один пользователь сделал несколько заказов, а вы проводите рассылку по новым клиентам.
';      
// END                                                                                                  
  
      



     
$MESS["GROUP_PARAM_USER_FILLTER"] = 'Выбираем пользователей';

         
$MESS["SUBJECT_DEFAULT"] = 'Дарим подарки любимым клиентам!';  
$MESS["MESSAGE_DEFAULT"] = '
               <p><b>Добрый день #ORDER_PROP_FIO#!</b></p>
               <br />
               <p>Вы делали у нас заказ №#ORDER_ID#, мы надеемся что эти вещи которые вы у нас приобрели, радуют вас каждый день. </p>
               <br />
               #FORGET_BASKET#
               <br />
               <p>Мы хотим, чтобы качественных вещей у вас стало больше, поэтому...</p>              
               <p>Мы дарим Вам купон на 5% скидки, который будет действовать 2 дня.</p>
               <p>Номер купона: <b>#COUPON#</b></p>
               <p>Сделайте себе подарок: получите именно то, что хочется Вам!</p>    
                           
               #RECOMMEND_PRODUCT#
';   
  
   
  
$MESS["GROUP_MESSAGE_NOTES"] = '
   <b>Статистика и отписка от рассылки: </b>  <br />
   #MAILING_MESSAGE# - для сбора статистики необходимо ссылки ставить с переменной ?MAILING_MESSAGE=#MAILING_MESSAGE# <br />
   #MAILING_UNSUBSCRIBE# - для возможности отписаться от рассылки ставите ссылку с переменной ?MAILING_UNSUBSCRIBE=#MAILING_UNSUBSCRIBE# <br />  
   #MAILING_EVENT_ID# - ID рассылки<br />   
   <br />

   Переменные заказа:   <br />
   #ORDER_ID# - ID заказа  <br />
   #ORDER_STATUS_ID_PRINT# - Статус заказа<br />   
   #ORDER_PAYED_PRINT# - Оплачен  <br />
   #ORDER_CANCELED_PRINT# - Отменен<br />
   #ORDER_ALLOW_DELIVERY_PRINT# - Разрешена доставка<br /> 
   #ORDER_PRICE_PRINT# - Стоимость заказа<br /> 
   #ORDER_PRICE_DELIVERY_PRINT# - Стоимость доставки<br /> 
   #ORDER_PAY_SYSTEM_ID_PRINT# - Система оплаты<br /> 
   #ORDER_DELIVERY_ID_PRINT# - Система доставки<br />   
      
   Есть вся информация получаемая с помощью функции <a href="http://dev.1c-bitrix.ru/api_help/sale/classes/csaleorder/csaleorder__getbyid.5cbe0078.php" target="_blank" >CSaleOrder::GetByID</a>, использовать ее можно так  #ORDER_(значение)#.<br />
   <br /> 
   
   
   <br />
   Свойства заказа: <br />
   #PEREMEN_ORDER_PROP#         
   <br />   
   
   
   
   Информация о пользователе:<br />
   #USER_ID# - ID пользователя <br />
   #USER_LOGIN# - логин пользователя <br />
   #USER_EMAIL# - e-main пользователя <br />
   #USER_LAST_NAME# - фамилия пользователя <br />
   #USER_NAME# - имя пользователя <br />
   #USER_SECOND_NAME# -  отчество пользователя <br />
   <br />
   
   Переменные товаров в заказе: <br />
   #FORGET_BASKET# - Список товаров в заказе (форматированный в сценарии)<br />
   #FORGET_BASKET_ID# - Список товаров в заказе для компонента<br />  
   #BASKET_COUNT# - Количество товаров в заказе<br />
   #BASKET_PRICE_ALL# - Стоимость всех товаров в заказе<br />  
   #BASKET_PRICE_ALL_FORMAT# -  Стоимость всех товаров в заказе с указанием валюты<br />     
   
   
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

// Перед выборкой заказов
$MESS["PHP_FILLTER_ORDER_PARAM_BEFORE_TITLE"] = 'PHP: Перед выборкой заказов';
$MESS["PHP_FILLTER_ORDER_PARAM_BEFORE_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
Подлючается до выборки заказов с помощью функции <a target="_blank" href="http://dev.1c-bitrix.ru/api_help/sale/classes/csaleorder/csaleorder__getlist.41061294.php">CSaleOrder::GetList</a>.<br/>
Вы можете переопределить значения выборки, либо объединить свой с существующим  <a href="http://www.php.su/array_merge" target="_blank">array_merge($fillterUser, $MyArray)</a>. <br/>
<b>Переменные:</b> <br />
$arSortOrder - сортировка<br />
$arFilterOrder - фильтрация <br />
$arSelectOrder - выбор данных<br />

<br />
<br />
<b>Отладку</b> можно вести через <a href="/bitrix/admin/php_command_line.php?lang=ru" target="_blank">коммандную PHP-строку</a> в тестовом режиме рассылки.<br />
';

// В конце выборки пользователей
$MESS["PHP_FILLTER_ORDER_PARAM_AFTER_TITLE"] = 'PHP: В конце цикла выборки заказов';
$MESS["PHP_FILLTER_ORDER_PARAM_AFTER_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
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
