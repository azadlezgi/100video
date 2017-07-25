<?php

// Locale Settings
setlocale(LC_TIME, 'ru_RU.utf-8'); // Linux Server (Windows may differ)
$locale['charset'] = "UTF-8";
$locale['xml_lang'] = "ru";
$locale['tinymce'] = "ru";
$locale['phpmailer'] = "ru";

// Full & Short Months
$locale['months'] = "&nbsp;|Январь|Февраль|Март|Апрель|Май|Июнь|Июль|Август|Сентябрь|Октябрь|Ноябрь|Декабрь";
$locale['shortmonths'] = "&nbsp|Янв|Фев|Мар|Апр|Май|Июнь|Июль|Авг|Сен|Окт|Ноя|Дек";

// Standard User Levels
$locale['user0'] = "Общий";
$locale['user1'] = "Пользователь";
$locale['user2'] = "Администратор";
$locale['user3'] = "Супер Администратор";
$locale['user_na'] = "Не Доступно";
$locale['user_anonymous'] = "Анонимный пользователь";
// Standard User Status
$locale['status0'] = "Активные";
$locale['status1'] = "Забаненые";
$locale['status2'] = "Неактивные";
$locale['status3'] = "Отложенные";
$locale['status4'] = "Бан по безопасности";
$locale['status5'] = "Отмененные";
$locale['status6'] = "Анонимы";
$locale['status7'] = "Деактивированные";
$locale['status8'] = "Неактивные";
// Forum Moderator Level(s)
$locale['userf1'] = "Модератор";
// Navigation
$locale['global_001'] = "Навигация";
$locale['global_002'] = "Нет ссылок\n";
// Users Online
$locale['global_010'] = "Сейчас на сайте";
$locale['global_011'] = "Гостей";
$locale['global_012'] = "Пользователей";
$locale['global_013'] = "Нет пользователей";
$locale['global_014'] = "Всего пользователей";
$locale['global_015'] = "Неактивных пользователей";
$locale['global_016'] = "Новый пользователь";
// Forum Side panel
$locale['global_020'] = "Темы форума";
$locale['global_021'] = "Новые темы";
$locale['global_022'] = "Обсуждаемые темы";
$locale['global_023'] = "Нет тем";
// Comments Side panel
$locale['global_025'] = "Последние комментарии";
$locale['global_026'] = "Нет доступных комментариев";
// Articles Side panel
$locale['global_030'] = "Последние статьи";
$locale['global_031'] = "Нет статей";
// Downloads Side panel
$locale['global_032'] = "Последние загрузки";
$locale['global_033'] = "Нет доступных загрузок";
// Welcome panel
$locale['global_035'] = "Добро пожаловать";
// Latest Active Forum Threads panel
$locale['global_040'] = "Последние активные темы форума";
$locale['global_041'] = "Мои темы";
$locale['global_042'] = "Мои сообщения";
$locale['global_043'] = "Новые сообщения";
$locale['global_044'] = "Темы";
$locale['global_045'] = "Просмотров";
$locale['global_046'] = "Ответов";
$locale['global_047'] = "Последние сообщения";
$locale['global_048'] = "Форум";
$locale['global_049'] = "Добавлено";
$locale['global_050'] = "Автор";
$locale['global_051'] = "Опрос";
$locale['global_052'] = "Перемещено";
$locale['global_053'] = "У вас нет тем на форумах.";
$locale['global_054'] = "У вас пока нет сообщений на форуме.";
$locale['global_055'] = "Есть %u новое(вых) сообщение(ний) с момента вашего последнего посещения.";
$locale['global_056'] = "Мои подписки на темы";
$locale['global_057'] = "Параметры";
$locale['global_058'] = "Отмена";
$locale['global_059'] = "Вы не подписаны ни на одну тему.";
$locale['global_060'] = "Отменить подписку для этой темы?";
// News & Articles
$locale['global_070'] = "Опубликовал ";
$locale['global_071'] = " ";
$locale['global_072'] = "Читать полностью";
$locale['global_073'] = " Комментариев";
$locale['global_073b'] = " Комментарий";
$locale['global_074'] = " Прочтений";
$locale['global_075'] = "Печать";
$locale['global_076'] = "Редактировать";
$locale['global_077'] = "Новости";
$locale['global_078'] = "Нет новостей";
$locale['global_079'] = "В ";
$locale['global_080'] = "Без категории";
// Page Navigation
$locale['global_090'] = "Предыдущая";
$locale['global_091'] = "Следующая";
$locale['global_092'] = "Страница ";
$locale['global_093'] = " из ";
// Guest User Menu
$locale['global_100'] = "Авторизация";
$locale['global_101'] = "Логин";
$locale['global_102'] = "Пароль";
$locale['global_103'] = "Запомнить меня";
$locale['global_104'] = "Войти";
$locale['global_105'] = "Вы не зарегистрированы?<br /><a href='".BASEDIR."register.php' class='side'>Нажмите здесь</a> для регистрации.";
$locale['global_106'] = "Забыли пароль? <br />Запросите новый <a href='".BASEDIR."lostpassword.php' class='side'>здесь</a>.";
$locale['global_107'] = "Регистрация";
$locale['global_108'] = "Восстановление пароля";
// Member User Menu
$locale['global_120'] = "Редактировать профиль";
$locale['global_121'] = "Приватные сообщения";
$locale['global_122'] = "Список пользователей";
$locale['global_123'] = "Панель администратора";
$locale['global_124'] = "Выход";
$locale['global_125'] = "У вас %u новое ";
$locale['global_126'] = "сообщение";
$locale['global_127'] = "сообщений";
$locale['global_128'] = "утверждено";
$locale['global_129'] = "утверждены";
// Poll
$locale['global_130'] = "Голосование";
$locale['global_131'] = "Голосовать";
$locale['global_132'] = "Выберите ваш вариант";
$locale['global_133'] = "Голос";
$locale['global_134'] = "Голосов";
$locale['global_135'] = "Голосов: ";
$locale['global_136'] = "Начат: ";
$locale['global_137'] = "Закончен: ";
$locale['global_138'] = "Архив опросов";
$locale['global_139'] = "Выберите опрос из списка:";
$locale['global_140'] = "Просмотр";
$locale['global_141'] = "Просмотр опроса";
$locale['global_142'] = "Опросы не найдены.";

// Captcha
$locale['global_150'] = "Проверочный код:";
$locale['global_151'] = "Введите проверочный код:";

// Footer Counter
$locale['global_170'] = "уникальный посетитель";
$locale['global_171'] = "уникальных посетителей";
$locale['global_172'] = "Время загрузки: %s секунд";
$locale['global_173'] = "Запросов";
// Admin Navigation
$locale['global_180'] = "Панель администратора";
$locale['global_181'] = "Вернуться на сайт";
$locale['global_182'] = "<strong>Примечание:</strong> Пароль администратора введен некорректно";
// Miscellaneous
$locale['global_190'] = "Включен режим обслуживания";
$locale['global_191'] = "Ваш IP адрес заблокирован.";
$locale['global_192'] = "Вы вышли как: ";
$locale['global_193'] = "Вы вошли как: ";
$locale['global_194'] = "Этот аккаунт в настоящее время приостановлен.";
$locale['global_195'] = "Этот аккаунт еще не активизирован.";
$locale['global_196'] = "Неправильное имя или пароль.";
$locale['global_197'] = "Пожалуйста, подождите, сейчас вы будете перемещены...<br /><br />
[ <a href='index.php'>или нажмите сюда, если не хотите ждать</a> ]";
$locale['global_198'] = "<strong>Внимание:</strong> Обнаружен файл setup.php, пожалуйста, удалите его немедленно!";
$locale['global_199'] = "<strong>Внимание:</strong> Не введен пароль администратора, нажмите <a href='".BASEDIR."edit_profile.php'>Редактировать профиль</a> и введите его.";
//Titles
$locale['global_200'] = " - ";
$locale['global_201'] = ": ";
$locale['global_202'] = $locale['global_200']."Поиск";
$locale['global_203'] = $locale['global_200']."FAQ";
$locale['global_204'] = $locale['global_200']."Форум";
//Themes
$locale['global_210'] = "Перейти к содержанию";
// No themes found
$locale['global_300'] = "Тема сайта не найдена";
$locale['global_301'] = "Извините, невозможно отобразить страницу. Из-за некоторых обстоятельств, не может быть найдена ни одна тема сайта. Если вы администратор сайта, используйте менеджер FTP для загрузки схемы, которая совместима с <em>PHP-Fusion v7</em> в каталог <em>themes/</em>. После загрузки темы, проверьте в разделе <em>Главные настройки</em>, что выбранна загруженная тема в директории <em>themes/</em>. Имейте в виду, что загруженная тема, должна имееть тоже название (включая регистр символов; важно для Unix-серверов), что и выбранная тема в разделе <em>Главные настройки</em>.<br /><br />Если вы пользователь, пожалуйста, свяжитесь с администратором сайта через e-mail: ".hide_email($settings['siteemail'])." и сообщите о этой проблеме.";
$locale['global_302'] = "Выбранная тема в разделе Главные настройки, не существует или повреждена!";
// JavaScript Not Enabled
$locale['global_303'] = "О, нет! Где <strong>JavaScript</strong>?<br /> В Вашем браузере не включен или он не поддерживает JavaScript. Пожалуйста, <strong>включите JavaScript</strong> в Вашем браузере для нормального просмотра этого сайта,<br /> или <strong>перейдите</strong> на веб браузер, который поддерживает JavaScript; <a href='http://firefox.com' rel='nofollow' title='Mozilla Firefox'>Firefox</a>, <a href='http://apple.com/safari/' rel='nofollow' title='Safari'>Safari</a>, <a href='http://opera.com' rel='nofollow' title='Opera Web Browser'>Opera</a>, <a href='http://www.google.com/chrome' rel='nofollow' title='Google Chrome'>Chrome</a> или версию <a href='http://www.microsoft.com/windows/internet-explorer/' rel='nofollow' title='Internet Explorer'>Internet Explorer</a> не ниже версии 6.";
// User Management
// Member status
$locale['global_400'] = "приостановлено";
$locale['global_401'] = "забанено";
$locale['global_402'] = "отключено";
$locale['global_403'] = "аккаунт удален";
$locale['global_404'] = "анонимный аккаунт";
$locale['global_405'] = "анонимный пользователь";
$locale['global_406'] = "Эта учетная запись была запрещена по следующим причинам:";
$locale['global_407'] = "Эта учетная запись была приостановлена ";
$locale['global_408'] = " по следующей причине:";
$locale['global_409'] = "Эта учетная запись была заблокирована по соображениям безопасности.";
$locale['global_410'] = "Причина в следующем: ";
$locale['global_411'] = "Эта учетная запись была отключена из-за неактивности";
$locale['global_412'] = "Эта учетная запись была переведена в анонимные, так как неактивна.";
// Banning due to flooding
$locale['global_440'] = "Автоматический бан при флудконтроле";
$locale['global_441'] = "Ваш аккаунт ".$settings['sitename']."забанен";
$locale['global_442'] = "Привет [USER_NAME],\n
Ваш аккаунт ".$settings['sitename']." замечен в посылке большого количества запросов за короткое время с одного IP ".USER_IP.", и поэтому он забанен. Это защита от спам ботов.\n
Пожалуйста, свяжитесь с администратором сайта ".$settings['siteemail']." для восстановления учетной записи и снятия бана.\n
".$settings['siteusername'];
// Lifting of suspension
$locale['global_450'] = "Автоматическая отмена отсрочки";
$locale['global_451'] = "Отмена отсрочки ".$settings['sitename'];
$locale['global_452'] = "Привет USER_NAME,\n
Приостановления Вашей учетной записи ".$settings['siteurl']." отменено. Подробнее о вашей учетной записи:\n
Имя: USER_NAME
Пароль: Скрыт в целях безопасности
Если вы забыли свой пароль, вы можете восстановить его кликнув на ссылке:\n";
$locale['global_453'] = "Привет USER_NAME,\n
Приостановление вашего аккаунта ".$settings['siteurl']." отменено.\n\n
Поздравляем,\n
".$settings['siteusername'];
$locale['global_454'] = "Счет активирован ".$settings['sitename'];
$locale['global_455'] = "Привет USER_NAME,\n
В последнее Ваше посещение ваш аккаунт реактивирован ".$settings['siteurl']." и учетная запись уже не помечена как неактивная.\n\n
Поздравляем,\n
".$settings['siteusername'];

// Function parsebytesize()
$locale['global_460'] = "Пусто";
$locale['global_461'] = "Байтов";
$locale['global_462'] = "kB";
$locale['global_463'] = "MB";
$locale['global_464'] = "GB";
$locale['global_465'] = "TB";
//Safe Redirect
$locale['global_500'] = "Вы будете перенаправлены %s, пожалуйста, ждите. Если вы не перенаправлены, нажмите здесь.";

// Captcha Locales
$locale['global_600'] = "код Подтверждения";
$locale['recaptcha'] = "ru";

//Miscellaneous
$locale['global_900'] = "Unable to convert HEX to DEC";

$locale['global_910'] = "Онлайн чат";
$locale['global_911'] = "Язык";
$locale['global_912'] = "Валюта";
$locale['global_913'] = "Вход";
$locale['global_914'] = "Регистрация";
$locale['global_915'] = "Качественный хостинг";

$locale['global_920'] = "24/7<span>support</span>";
$locale['global_921'] = "+7 800 700 2115";
$locale['global_922'] = "e-mail: <a href='mailto:support@issohost.ru'>support@issohost.ru</a>";
$locale['global_923'] = "8 800 700 2115";

$locale['global_950'] = "IssoHost Качественный хостинг";
$locale['global_951'] = "Все права защищены.";

$locale['bere_1'] = "в день";
$locale['bere_2'] = "в месяц";
$locale['bere_3'] = "в год";

?>