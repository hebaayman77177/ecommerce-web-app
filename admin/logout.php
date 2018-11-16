<?php
    
    session_start();
    session_unset();//unset the data
    session_destroy();
    header('Location:index.php');
    exit();