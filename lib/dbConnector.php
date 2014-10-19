<?php

// configure the database
try
{
    if ($isProd) {
        $dbHost = getenv('OPENSHIFT_MYSQL_DB_HOST');
        $dbPort = getenv('OPENSHIFT_MYSQL_DB_PORT');
        $dbName = 'php';

        // root user
        $dbUser = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
        $dbPassword = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
        
        /*
        // read only user
        $dbUser = 'php-local';
        $dbPassword = 'jASpuruZUChequBegEfrABraW8';
        */
        
    } else {
        $dbUser = "root";
        $dbPassword = "";
        $dbHost = '127.0.0.1';
        $dbPort = 3306;
        $dbName = 'galaxy_dev';
    }
    $db = new PDO("mysql:host=$dbHost:$dbPort;dbname=$dbName", $dbUser, $dbPassword);

    unset($dbUser);
    unset($dbPassword);
    unset($dbHost);
    unset($dbname);
}
catch (PDOException $ex)
{
    echo "Error!: " . $ex->getMessage();
    die();
}