<?
$module_id = 'kit.mailing';
$MESS[$module_id."_PAGE_TITLE"] = "Массовый импорт черного списка e-mail";

$MESS[$module_id."_DEMO"] = "Модуль работает в демо-режиме.";
$MESS[$module_id."_DEMO_DETAILS"] = 'Приобрести полнофункциональную версию вы можете по адресу: <a href="http://marketplace.1c-bitrix.ru/solutions/kit.mailing/" target="_blank">http://marketplace.1c-bitrix.ru/solutions/kit.mailing</a>';
$MESS[$module_id."_DEMO_END"] = "Демо-режим закончен.";
$MESS[$module_id."_DEMO_END_DETAILS"] = 'Приобрести полнофункциональную версию вы можете по адресу: <a href="http://marketplace.1c-bitrix.ru/solutions/kit.mailing/" target="_blank">http://marketplace.1c-bitrix.ru/solutions/kit.mailing</a>';

$MESS[$module_id."_edit10"] = "Черный список e-mail";  

$MESS[$module_id."_OPTION_TYPE"] = "Тип импорта"; 
$MESS[$module_id."_OPTION_10"] = "Параметры"; 


                                                  
$MESS[$module_id."_ID_EVENT_TITLE"] = "Рассылка"; 
$MESS[$module_id."_SPAM_TITLE"] = "Метка: Жалоба на спам"; 
$MESS[$module_id."_ERROR_CODE_TITLE"] = "Код ошибки"; 
$MESS[$module_id."_EMAIL_TO_TITLE"] = "E-mail";               
$MESS[$module_id."_EMAIL_TO_NOTES"] = "Вводите email с новой строки или через запятую и далее нажмите кнопку импорт";  
$MESS[$module_id."_ERROR_CODE_TITLE"] = "Код ошибки";                
$MESS[$module_id."_FILE_LOG_TITLE"] = "Путь до файла логов";


$MESS[$module_id."_TYPE_TITLE"] = "Тип импорта"; 
$MESS[$module_id."_TYPE_VALUE_EMPTY"] = "-- Выбрать --"; 
$MESS[$module_id."_TYPE_VALUE_MANUAL"] = "Почтовые адреса с поля email"; 
$MESS[$module_id."_TYPE_VALUE_FILE_LOG"] = "C файла логов"; 

    

$MESS[$module_id."_PANEL_TOP_BACK_TITLE"] = "К списку e-mail";  




$MESS[$module_id."_submit_save"] = "Импортировать";




$MESS[$module_id."_RESULT_IMPORT_MESSAGE"] = "Импорт прошел успешно:
Добавлено: #NEW# 
Обновлены: #OLD#

#ERROR# 
";

$MESS[$module_id."_RESULT_IMPORT_MESSAGE_ERROR"] = 'Код ошибки и количество email:
';
$MESS[$module_id."_RESULT_IMPORT_MESSAGE_CODE"] = 'ошибка ';

$MESS[$module_id."_ERROR_CODE_ANSWER"] = "Значения кодов ошибок:

Ошибка 450: Сервер не может получить доступ к почтовому ящику для доставки сообщения. Это может быть вызвано процессом чистки мертвых адресов на сервере, почтовый ящик может быть поврежден, или почтовый ящик может находиться на другом сервере, который в настоящее время не доступен. Также сетевое соединение могло быть разорвано во время отправки, или удаленный почтовый сервер не хочет принимать почту с вашего сервера по некоторым причинам (IP-адрес, черные списки и т. д.). Повторная попытка отправки письма на этот почтовый ящик может оказаться успешной.

Ошибка 451: Эта ошибка, как правило, возникает из-за перегрузки вашего Интернет провайдера или через ваш SMTP-релей отправлено слишком много сообщений. Следующая попытка отправить письмо может оказаться успешной.

Ошибка 500: Ваш антивирус/брандмауэр блокирует входящие/исходящие соединения SMTP. Вам следует настроить антивирус/брандмауэр для решения проблемы.

Ошибка 501: Недопустимые адреса электронной почты или доменное имя почтового адреса. Иногда указывает на проблемы соединения.

Ошибка 503: Повторяющая ошибка 503 может свидетельствовать о проблемах соединения. Отклик 503 SMTP-сервера чаще всего является показателем того, что SMTP-сервер требует аутентификации, а Вы пытаетесь отправить сообщение без аутентификации (логин + пароль). Проверьте Общие настройки, чтобы убедиться в правильности настроек SMTP-сервера.

Ошибка 512: У одного из серверов на пути к серверу назначения есть проблема с DNS-сервером либо адрес получателя не верный. Проверьте адрес получателя на правильность доменного имени (орфографические ошибки в доменном имени или несуществующее доменное имя).

Ошибка 513: Убедитесь, что адрес электронной почты получателя верный, не содержит ошибок. Затем попробуйте повторно отправить сообщение. Другой причиной может быть то, что SMTP-сервер требует аутентификации, а Вы пытаетесь отправить сообщение без аутентификации (обычно аутентификация ESMTP, логин + пароль). Проверьте Общие настройки, чтобы убедиться в правильности настроек SMTP-сервера.

Ошибка 523: Размер сообщения (сообщение + все его вложения) превышает ограничения по размеру на сервере получателя. Проверьте размер сообщения, которое Вы подготовили для отправки, в частности, размер вложений, возможно, стоит разбить сообщения на части.

Ошибка 530: SMTP-сервер вашего провайдера, требует аутентификации, а Вы пытаетесь отправить сообщение без аутентификации (логин + пароль). Проверьте Общие настройки, чтобы убедиться в правильности настроек SMTP-сервера. Другой причиной может быть то, что ваш SMTP-сервер находится в черном списке сервера получателя. Или почтовый ящик получателя не существует.

Ошибка 535:  Проверьте настройки SMTP-сервера. Убедитесь в том, что логин и пароль введены правильно. Откройте «Настройки»/ «Общие Настройки»/ «SMTP», там двойной клик по адресу SMTP сервера, и в появившемся окне исправьте имя пользователя и пароль.
Примечание 1: Убедитесь, что CAPS LOCK выключен — это важно!
Примечание 2: Некоторые SMTP сервера требуют в качестве логина «user@mail.ru» вместо просто «user», уточните эти моменты. 

Ошибка 541: Этот ответ почти всегда отправляется Антиспам фильтром на стороне получателя. Проверьте ваше сообщение со спам-чекером или попросите получателя добавить вас в белый список.

Ошибка 550: Отклик 550 SMTP-сервера означает, что емейл-адреса получателя нет на сервере. Свяжитесь с получателем устно, чтобы получить его емейл-адрес. Ошибка 550 иногда может быть отправлена Антиспам фильтром. Другим случаем возврата отклика 550 может быть, когда сервер получателя не работает.

Ошибка 552: Почтовый ящик получателя достиг своего максимально допустимого размера. Другим случаем возврата отклика 552 может быть, когда размер входящего сообщения превышает лимит указанный администратором сети.

Ошибка 553: Неверный адрес электронной почты получателя. Отклик 553 SMTP-сервера иногда возвращает почтовый сервер вашего Интернет провайдера. Это происходит, если у Вас нет подключения к Интернету у этого провайдера.

Ошибка 554: Отклик 554 SMTP-сервера возвращает антиспам-фильтр в случае, если не нравится емейл-адрес отправителя, или IP-адрес отправителя, или почтовый сервер отправителя (к примеру, они находятся в RBL). Вам нужно либо попросить отправителя добавить Вас в белый список, либо Вы должны принять меры, чтобы Ваш IP-адрес или ISP сервер был удален из RBL (Realtime Blackhole List). 
";

?>