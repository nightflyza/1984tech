<!DOCTYPE html>
<html lang="uk">
    <head>
        <meta charset="utf-8">
        <title>Халепа</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/jpg" href="favicon.ico" />
        <link rel="stylesheet" href="style.css" type="text/css" id="main-css">
    </head>

    <body>

        <div class="outer">
            <div class="middle">
                <div class="inner">
                    <h1 class="centered">Шановний абонент.</h1>
                    Набув чинності Указ президента України №133/2017 "Про рішення Ради національної безпеки і оборони України від 28 квітня 2017 року "Про застосування персональних спеціальних економічних та інших обмежувальних заходів (санкцій)" та
                    Указ президента України №126/2018 від 14 травня 2018 року.
                    Відповідно до цих указів інтернет провайдери повинні припинити надання послуг з доступу до ряду веб-ресурсів, зокрема соціальних мереж "Однокласники", "Вконтакте", сервісів Yandex та mail.ru, а також деяких інших. 
                    Більш детально зі списком веб-ресурсів ви можете ознайомитись за наступними посиланнями: <a href="http://www.president.gov.ua/documents/1332017-21850">1</a>, <a href="http://www.rnbo.gov.ua/documents/473.html">2</a>.
                    <br>
                    Виконуючи вимоги зазначеного Указу проводяться технічні заходи з обмеження надання послуг доступу до зазначених веб-ресурсів, відповідно з нашими технічними можливостями.
                    <br>
                    <br>
                    За додатковою інформацією ви можете звертатись за адресою: 01220, м. Київ, вул. Банкова, 11.<br>
                    Або ж телефонувати за номером - (044) 255-73-33.
                </div>
            </div>
        </div>

        <?php
        $logFile = 'log/redirect.log';
        $curDate = date("Y-m-d H:i:s");
        $remoteIp = $_SERVER['REMOTE_ADDR'];
        $actualUrl = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $logData = $remoteIp . ' [' . $curDate . '] ' . $actualUrl."\n";

        file_put_contents($logFile, $logData, FILE_APPEND | LOCK_EX);
        ?>

    </body>
</html>