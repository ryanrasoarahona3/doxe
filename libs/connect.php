<?php

#DEV
# define('HOST','192.168.2.36:3306');
# define('DBNAME','cercle');
# define('USER','cercledehcoffice');
# define('PASS','fR6JM4X28CbewyATBF4JZjGX6h');

#PROD
#define('HOST','doxekdkcdb3003.mysql.db');
#define('DBNAME','doxekdkcdb3003');
#define('USER','doxekdkcdb3003');
#define('PASS','Asxc023638');

define('HOST', 'localhost');
define('DBNAME', 'doxekdkcdb3003');
define('USER', 'root');
define('PASS', '');

// Connexion base de donnée
$connect = connect();
$GLOBALS['connection'] = $connect;
?>
