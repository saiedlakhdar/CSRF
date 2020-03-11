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

if (isset($_POST['submit'])){
//    $ins->checkPost() ;
//    var_dump($ins->checkPost());
}
var_dump($ins->_token());
var_dump(base64_decode($ins->_token()));
var_dump(md5(Csrf::getRealIpAddr()));
var_dump(substr(base64_decode($ins->_token()), 42,32));

var_dump( (isset($_SERVER['HTTP_USER_AGENT'])) ? md5($_SERVER['HTTP_USER_AGENT']) : md5(null) );
var_dump(substr(base64_decode($ins->gToken()), 10,32));

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


