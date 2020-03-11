<?php
require_once "../vendor/autoload.php" ;
use CSRF\Csrf ;

session_start() ;
$ins = Csrf::getInstance() ;
$token = $ins->_token();

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $ins->checkPost() ;
    $ins->check($ins->_tokenName,$_POST[$ins->_tokenName]) ;

    var_dump($_POST[$ins->_tokenName]);
    var_dump($ins->_tokenName);
}
var_dump($_SESSION[$ins->_tokenName]);
var_dump($token);

if (isset($_POST['submit'])){
//    $ins->checkPost() ;
//    var_dump($ins->checkPost());
}
var_dump($ins->_token());
var_dump($ins->inputToken());

echo "<hr>" ;


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CSRF Test page</title>
</head>
<body>
<form action="" method="post" enctype="application/x-www-form-urlencoded">
    <table>
        <tr>
            <td>Username</td>
            <td>
                <input type="text" name="username" >
            </td>
        </tr>
        <tr>
            <td>Password</td>
            <td>
                <input type="password" name="password">
            </td>
        </tr>
        <tr>
            <td>
                <?= $ins->inputToken() ;?>
            </td>
            <td colspan="2">
                <input type="submit" name="submit">
            </td>
        </tr>
    </table>
</form>

</body>
</html>


