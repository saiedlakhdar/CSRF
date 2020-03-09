<?php
require_once "../vendor/autoload.php" ;
use CSRF\Csrf ;
if (isset($_POST['submit'])){
    var_dump($_POST);
}
$ins = Csrf::getInstance() ;
$ins->gToken('_token');
var_dump($_SERVER['X-Forwarded-Proto']);
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
                <input type="hidden" name="_token">
            </td>
            <td colspan="2">
                <input type="submit" name="submit">
            </td>
        </tr>
    </table>
</form>

</body>
</html>


