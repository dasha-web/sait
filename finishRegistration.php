<?php
    include('config.php');
    $registrationSuccess = true;
    $registrationUserErrors = [];
    $mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB);
    
    if ($mysqli->connect_errno) {
        $registrationSuccess = false;
        die("Ошибка подключения к базе данных: " . $mysqli->connect_errno . " " . $mysqli->connect_error);
    } else {
        $name = $_POST['name'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        $verificationPassword = $_POST['verificationPassword'];
        $telephone = $_POST['telephone'];
        
        if ($password !== $verificationPassword) {
            $registrationSuccess = false;
            $registrationUserErrors[] = 'Введёные пароли не совпадают!';
        }
        if (2 > strlen($name) || strlen($name) > 32) {
            $registrationSuccess = false;
            $registrationUserErrors[] = 'Длина имени должна быть от 2 до 32 симбволов';
        }
        if (6 > strlen($login) || strlen($login) > 32) {
            $registrationSuccess = false;
            $registrationUserErrors[] = 'Длина логина должна быть от 6 до 32 симбволов';
        }
        if (6 > strlen($password) || strlen($password) > 20) {
            $registrationSuccess = false;
            $registrationUserErrors[] = 'Длина пароля должна быть от 6 до 20 симбволов';
        }
        $loginQuery = "SELECT * FROM `users` WHERE `login` = '$login'";
        if (!$loginSelect = $mysqli->query($loginQuery)) {
            $registrationSuccess = false;
            die('Ошибка запроса: '. $mysqli->error);
        } else {
            if ($loginSelect->num_rows) {
                $registrationSuccess = false;
                $registrationUserErrors[] = 'Введёный логин уже существует!';
            }
        }
        
        $hashPassword = hash('md5', $password);
        
        if ($registrationSuccess) {
            $query = "INSERT INTO `users` (`name`, `login`, `password`,`telephone`) VALUES ('$name', '$login', '$hashPassword','telephone')";
            if (!$mysqli->query($query)) {
                die('Ошибка запроса: '. $mysqli->error);
            }
        } else {
            foreach ($registrationUserErrors as $error) {
                ?>
                    <div style="color: red;">
                        <?php
                            echo($error . '<br>');
                        ?>
                    </div>
                <?php
            }
            ?>
                <a href="registration.php">Вернуться</a>
        <?php
        }

        if ($registrationSuccess) {
            ?>
                <html>
                    <head>
                        <link href="https://fonts.googleapis.com/css?family=Rubik:400,900&display=swap&subset=cyrillic" rel="stylesheet">
                        <meta charset="utf-8"/>
                        <title>Регистрация</title>
                        <link href="Css\finishRegistration.css" rel="stylesheet" type="text/css"/>
            
                    </head>
                    <body background="devuska.jpg">
                        <div class="cherta">     
                        <ul class="spisok" >
                        <li class="levo"><div class="Mid">Mid</div><div class="aV">aV</div></li>
                            <li class="right_spisok">
                                <a class="ssilka margin"  href="index.html">Главное меню</a>
                                <a class="ssilka margin"  href="html2.html">О себе</a>
                                <a class="ssilka margin"  href="html5.html">Галерея</a>
                                <a class="ssilka margin"  href="html6.html">Игра</a>
                            </li>
                        </ul>
                        </div>
                        <div class="avtorizacia">
                            <div class="verh">
                                <ul class="spisok" >
                                    <li class="slovo">Регистрация</li>
                                </ul>
                            </div>
                            <div class="avtorizaciya word">Вы успешно зарегистрировались!<div></Br>
                            <div class="avtorizaciya word"><a class="ssilka" href="index.php">Нажмите чтобы вернуться на главную страницу.<a></div>
                        </div>
                    <body>
                </html>    
            <?php
        }
    }
    $mysqli->close();
    
?>
