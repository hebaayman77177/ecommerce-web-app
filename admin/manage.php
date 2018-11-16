<?php
    include "init.php";
    //$pagetitle='kk';
    include $templates."header.php"; 
    $action = isset($_GET['action'])? $_GET['action'] : 'manage';
    
    $vactions=['add','delete','update','eddit'];
    if (in_array($action,$vactions)){
        echo "you are in " .$action.'page';
    }else{
        echo "hello you are in the manage page";
        echo "</br>";
        foreach($vactions as $a){
        echo "<a href=manage.php?action=".$a.">got to the ".$a." page</a></br>";
        }
        }
    
    include $templates."footer.php"; 