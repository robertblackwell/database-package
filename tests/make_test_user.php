<?php
    include dirname(__FILE__)."/include/UnitTestRegistry.php";
    \UnitTestRegistry::init();
    $db_user = \UnitTestRegistry::$config["sql"]["db_user"];
    $db_passwd = \UnitTestRegistry::$config["sql"]["db_passwd"];
    $db_name = \UnitTestRegistry::$config["sql"]["db_name"];
    $db_host = \UnitTestRegistry::$config["sql"]["db_host"];
    $user="admin";
    $password="password";
    $conn = mysqli_connect('localhost',$user,$password);
    $result = mysqli_query($conn, "CREATE USER '{$db_user}'@'{$db_host}' IDENTIFIED BY '{$db_passwd}';");
    $result = mysqli_query($conn, "GRANT ALL ON *.* TO '{$db_user}'@'{$db_host}'");
    $result = mysqli_query($conn, "CREATE DATABASE {$db_name}");
    $result = mysqli_close($conn);
