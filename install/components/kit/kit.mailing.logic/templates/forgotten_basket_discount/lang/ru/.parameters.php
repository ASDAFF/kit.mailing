<?
$MESS['SITE_ID_ALL'] = 'Все'; 
$MESS['SITE_ID_TITLE'] = 'Сайт';


   
$MESS["BASKET_DATE_UPDATE_TITLE"] = 'Корзина оставлена';
$MESS["BASKET_DATE_UPDATE_NOTES"] = 'Выберите период когда оставлены корзины для выборки отправки сообщений по ним.';

$MESS["HOURS_AGO_START_TITLE"] = 'от';
$MESS["HOURS_AGO_END_TITLE"] = 'до';
$MESS["HOURS_AGO_END_NOTES"] = 'Данные для выборки брошеных корзин от данного момента.<br /> 
Например, Вы хотите выбрать корзины, которые были оставлены 10-15 дней тому назад, заполните от 240 часов до 360';


   
// товары в корзине   
// START   
$MESS["GROUP_TEMP_FORGET_BASKET"] = 'Шаблон списка товаров в корзине (переменная #FORGET_BASKET#)';        
$MESS['TABS_FORGET_BASKET_NAME'] = 'Список товаров';
$MESS["FORGET_BASKET_IMG_WIDTH_TITLE"] = "Ширина изображения товара";
$MESS["FORGET_BASKET_IMG_HEIGHT_TITLE"] = "Высота изображения товара"; 
$MESS["TEMP_FORGET_BASKET_TOP_TITLE"] = "Верх списка товаров";
$MESS["TEMP_FORGET_BASKET_TOP_DEFAULT"] = '
    <div style="background-color: #89cbf5; padding: 5px 10px 5px 10px;color: #FFFFFF; margin-bottom: 10px;"><b>Товары в корзине:</b></div> 
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
    #BASKET_DETAIL_PAGE_URL# - Ссылка на товар с корзины <br />   
    #BASKET_NAME# - Имя товара с корзина<br />   
    #BASKET_QUANTITY# - количество товаров в корзине <br />   
    #BASKET_PRICE#  - цена из корзины  <br />   
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
    #PRODUCT_PROP_СИМВОЛЬНЫЙ_КОД_СВОЙСТВА_ИНФОБЛОКА# - вывод свойства товарв из инфоблока    <br />
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
    






   
   
   

     
$MESS["GROUP_PARAM_BASKET_FILLTER"] = 'Корзина брошена часов назад';

         
      
      
$MESS["SUBJECT_DEFAULT"] = 'Вы, кажется, что-то забыли?';
$MESS["GROUP_MESSAGE_NAME"] = 'Шаблон письма';         
$MESS["MESSAGE_DEFAULT"] = '  
               <p><b>Вы, кажется, что-то забыли? </b></p>
               <br />
               <p>Приветствуем Вас #USER_NAME# #USER_LAST_NAME#! Мы хотим Вам напомнить, что Вы забыли в корзине на сайте нашего магазина товары. Хотим убедиться, что все в порядке, и это всего лишь случайное недоразумение.  </p>
               <p>Если вам необходима помощь при оформлении заказа, Вы всегда можете нам позвонить или написать на почту, и мы окажем вам необходимую поддержку в тот же час, как получим ваше сообщение. </p>
               <br />
               <p>Мы дарим Вам купон на 5% скидки, который будет действовать всего 2 дня, успейте купить :) </p>
               <p><b>Номер купона: #COUPON#</b></p>
               <br />
               <br />
               <p>Количество позиций: #BASKET_COUNT#</p>
               <p>Общая стоимость позиций: #BASKET_PRICE_ALL_FORMAT#</p>
               <br />
        
               <div style="text-align: right;">
                    <a style="text-decoration: none;color:#FFFFFF;font-size: 16px;display:inline-block;background-color:#CC0033;padding: 10px 30px 10px 30px; border: 1px solid #BFBFBF"  href="http://#SITE_URL#/personal/cart/?MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=kit_mailing_#MAILING_EVENT_ID#"><b>Оформить заказ</b></a>
               </div>
               <br />
                                   
               #FORGET_BASKET#
               
               <br />     
               <div style="text-align: right;">
                    <a style="text-decoration: none;color:#FFFFFF;font-size: 16px;display:inline-block;background-color:#CC0033;padding: 10px 30px 10px 30px; border: 1px solid #BFBFBF"  href="http://#SITE_URL#/personal/cart/?MAILING_MESSAGE=#MAILING_MESSAGE#&utm_source=newsletter&utm_medium=email&utm_campaign=kit_mailing_#MAILING_EVENT_ID#"><b>Оформить заказ</b></a>
               </div>
';   
  
   
  
$MESS["GROUP_MESSAGE_NOTES"] = '
   <b>Статистика и отписка от рассылки: </b>  <br />
   #MAILING_MESSAGE# - для сбора статистики необходимо ссылки ставить с переменной ?MAILING_MESSAGE=#MAILING_MESSAGE# <br />
   #MAILING_UNSUBSCRIBE# - для возможности отписаться от рассылки ставите ссылку с переменной ?MAILING_UNSUBSCRIBE=#MAILING_UNSUBSCRIBE# <br />  
   #MAILING_EVENT_ID# - ID рассылки<br />   
   <br />
   Информация о пользователе:<br />
   #USER_EMAIL# - e-main пользователя <br />
   #USER_NAME# - имя пользователя  <br />
   #USER_LAST_NAME# - фамилия пользователя  <br />
   Так же есть вся информация получаемая с помощью функции <a href="http://dev.1c-bitrix.ru/api_help/main/reference/cuser/getbyid.php" target="_blank" >CUser::GetByID()</a>, использовать ее можно так  #USER_(значение)#.<br />
   <br />
   Переменные корзины: <br />
   #FORGET_BASKET# - Список товаров в корзине (форматированный в сценарии)<br />
   #FORGET_BASKET_ID# - Список товаров в корзине для компонента<br />   
   #BASKET_COUNT# - Количество товаров в корзине<br />
   #BASKET_PRICE_ALL# - Стоимость всех товаров в корзине<br />  
   #BASKET_PRICE_ALL_FORMAT# -  Стоимость всех товаров в корзине с указанием валюты<br />  
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
$MESS["GROUP_PHP_MODIF_MAILING_NAME"] = 'Модификация рассылки (для разработчиков)';

// Перед выборкой корзин
$MESS["PHP_FILLTER_BASKET_PARAM_AFTER_TITLE"] = 'PHP: Перед выборкой корзин';
$MESS["PHP_FILLTER_BASKET_PARAM_AFTER_NOTES"] = '<span style="color:red">ОСТРОЖНО: Необходимы знания php, использовать при необходимости расширения функционала.</span><br />
Подлючается до выборки корзин с помощью функции <a target="_blank" href="http://dev.1c-bitrix.ru/api_help/main/reference/cuser/getlist.php">CUser::GetList</a>.<br/>
Вы можете переопределить значения выборки, либо объединить свой с существующим  <a href="http://www.php.su/array_merge" target="_blank">array_merge($basketFillter, $MyArray)</a>. <br/>
<b>Переменные:</b> <br />
$basketSort - сортировка<br />
$basketFillter - фильтрация <br />
$basketSelect - выбор данных<br />

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
        <h3>Видео-урок: Маркетинговые рассылки - сценарий брошенная корзина</h3> 
        <iframe width="800" height="500"  src="//www.youtube.com/embed/Y5r4DPObB1g?list=PL2fR59TvIPXe8-iafhCqcLK4r1RxzqhST" frameborder="0" allowfullscreen></iframe>
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
        <h3>Если у вас возникли проблемы или вопросы с настройкой рассылок, смело пишите в <a href="https://asdaff.github.io//support/" target="_blank">техническую поддержку</a> компании «KIT», мы обязательно вам поможем.</h3>
                          
    </div>

';    
  
     
     
?>
