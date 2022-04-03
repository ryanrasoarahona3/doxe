<?php

#DEV
# define('HOST','192.168.2.36:3306');
# define('DBNAME','cercle');
# define('USER','cercledehcoffice');
# define('PASS','fR6JM4X28CbewyATBF4JZjGX6h');

#PROD
define('HOST','localhost:3306');
define('DBNAME','doxekdkcdb3003');
define('USER','root');
define('PASS','');


// Connexion base de donnée
$connect = connect();
$GLOBALS['connection'] = $connect;
?>
