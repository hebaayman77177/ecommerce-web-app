<?php

    ob_start();
    session_start();
    include 'init.php';
    $pagetitle='categories';
    include $templates."header.php";
    include $templates."navbar.php";
    
    
    if(!isset($_SESSION['username'])){
          
          header('Location:index.php');
          exit();
          
    }
    
    $action=isset($_GET['action'])?$_GET['action']:'manage';
    
    if($action=='manage'){
        echo 'welcome';
    }elseif($action=='add'){
        
    }elseif($action=='insert'){
        
    }elseif($action=='edit'){
        
    }elseif($action=='update'){
        
    }elseif($action=='delete'){
        
    }
    
        
    include $templates."footer.php";
    ob_end_flush();
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    