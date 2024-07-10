<?php
    $xhost = 'localhost';
    $uname = 'root';
    $xpw = '';
    $xdbname = 'employeepagerdb';
    $xcnstr = "mysql:host=$xhost; dbname=$xdbname;charset=utf8";
    $xopt = array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'");
    $xdbtype="my";

    try
    {
        $link_id = new PDO($xcnstr, $uname, $xpw, $xopt);

    }
    catch(Exception $e)
    {
        echo "No Connection";
    }

    require_once("include/lx.pdodb.php");
?>