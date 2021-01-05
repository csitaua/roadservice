<?php
define('SALT_LENGTH', 9);

function PwdHash($pwd, $salt = null)
{
    if ($salt === null)     {
        $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
    }
    else     {
        $salt = substr($salt, 0, SALT_LENGTH);
    }
    return $salt . sha1($pwd . $salt);
}

$pass = 't%dx@9kH5&Fy';
$pwd = '527c398f7be250ac8a4c704fc509789ee112bb37f16f9a8c9';

//echo PwdHash('t%dx@9kH5&Fy','69540ebda1241dde093446d9337be9927c71f0a5ad6b0ea7e');
echo PwdHash($pass,substr($pwd,0,9))
?>
