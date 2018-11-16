<?php

    /*
     *this func echo the title of the page
     *in case the var $pagetitle exist,
     *elso it echo the default
     *
     */
    function get_title(){
        global $pagetitle;
        if (isset($pagetitle)){
            return $pagetitle;
        }
        else{
            return lang('default');
        }
    }
    
    
    function home_redirect($url=null,$where='',$error_mesage='', $seconds=3){
       if($url === null){
            $url='index.php';
            $where='home page';
       }else if($url==='back'){
            $url=isset($_SERVER['HTTP_REFERER'])&&$_SERVER['HTTP_REFERER']!==null?$_SERVER['HTTP_REFERER']:'index.php';
            $where='brevious page';
       }
       echo '<div class="alert alert-primary" >'.$error_mesage .
       'you will be redirected to the '.$where.' in '.$seconds.' seconds</div>';
       header("refresh:$seconds;url=$url");
       exit();
       
    }
    
    /*
     *
     *check_item v1.0
     *Function to check items in the database[function accept parameters ]
     *$select -> the item you want to check
     *$from -> the table you want to select from
     *$value -> the value that you want to check on 
     *
     */
    function check_item($select,$from,$value=null){
        global $con;
        $quere= 'where '.$select .'= ?'; 
        if($value===null){
          $quere= ''; 
        }
        $stmt=$con->prepare("select $select from $from $quere");
        $rows=$stmt->execute(array($value));
        $count=$stmt->rowCount();
        return $count;
    }
    
    /*
     *function count_items v1.0
     *used to count items in table [$item,$table]
     *return the count
     */
    
    
    function count_items($item,$table){
        global $con;
        $stem2=$con->prepare("select count($item) from $table");
        $stem2->execute();
        return $stem2->fetchColumn();
    }
    
    
    /*
     *get_latest_items v1.0
     *returns the latest items
     *params:
     *$select : what would you select
     *$from   : tha table you want select from
     *$order  : the item you want to order the result by
     *$limit  : the number of result that would return to you
     */
    function get_latest_items($select,$from,$order,$limit){
        global $con;
        $statment=$con->prepare("select $select from $from order by $order DESC limit $limit");
        $statment->execute();
        return $statment->fetchAll();
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    