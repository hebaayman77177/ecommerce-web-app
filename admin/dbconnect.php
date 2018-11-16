<?php

    $dsn="mysql:host=localhost;dbname=shop";
    $user='root';
    $pass='';
    $option=array(
                 PDO::MYSQL_ATTR_INIT_COMMAND=>'set NAMES utf8' 
                  );
    
	try{
        
        $con= new PDO($dsn,$user,$pass,$option);
        $con->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
		

	}catch(PDOException $e){

        echo 'failed to connect to the database ' . $e->getMessage();
	}