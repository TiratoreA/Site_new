<?php
/************************************************************************************
 * Запись данных в файл 
 ************************************************************************************/

//Принимаем постовые данные
$name=$_POST['name'];
$surname=$_POST['surname'];
$phone=$_POST['phone'];

//обращаемся к глобальной переменной SERVER
$ip=$_SERVER['REMOTE_ADDR'];

//формируем строку для записи
$str=$name.' '.$surname.', '.$phone.', '.$ip.'\r\n';

//открываем файл для записи.Если файл не существует-он будет создан
$fopen  =  fopen('my_form_reports.txt', 'a+');
//записываем строку
fputs ($fopen, $str);
//закрываем файл
fclose ($fopen);




/************************************************************************************
 * Простой вариант записи в базу
 ***********************************************************************************/

$db_user='db_username';
$db_name='db_name';
$db_pass='db_userpassword';

//принимаем данные
$name=$_POST['name'];
$surname=$_POST['surname'];
$phone=$_POST['phone'];

//соединяемся с БД
mysql_connect('localhost', $db_user, $db_pass);
mysql_select_db($db_name);

//обязательно экранируем нежелательные символы функцией mysql_real_escape_string
$sql="insert into `table_name` (name,surname,phone,ip) values
    (
        '".  mysql_real_escape_string($name)."',
        '".  mysql_real_escape_string($surname)."', 
        '".  mysql_real_escape_string($phone)."',
        '".$_SERVER['REMOTE_ADDR']."'
    )";
$res=mysql_query($sql);


/************************************************************************************
 * Использование PDO
 ***********************************************************************************/

$db  =  new  PDO('mysql:dbname='.$db_name.'; host=localhost',$db_user,$db_pass);

$sql="insert into `table_name` (name,surname,phone,ip) values (:name,:surname,:phone,:ip)";
$sth=$db->prepare($sql);
$sth->bindValue(':name', $name);
$sth->bindValue(':surname', $surname);
$sth->bindValue(':phone', $phone);
$sth->bindValue(':ip', $_SERVER['REMOTE_ADDR']);
$sth->execute();
$error=$sth->errorInfo();
print_r($error);




/************************************************************************************
 * Отправ каданных на e-mail
 ***********************************************************************************/

//Принимаем данные
$mytext=$_POST['mytext'];


//Тут указываем на какой ящик посылать письмо
$to = "tiratorea@gmail.com";
//Далее идет тема и само сообщение
$subject = "Новый клиент";
$message = "
Письмо отправлено из моей формы.<br />
Пользователь указал:<br />Имя: ".htmlspecialchars($mytext)."<br />
$headers = "From: MySite.ru <site-email@mysite.ru>\r\nContent-type: text/html; charset=utf8 \r\n";
mail ($to, $subject, $message, $headers);
?>
