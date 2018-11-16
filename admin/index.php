<?php include "init.php" ;?>
<?php
    $pagetitle='login';    
    include $templates."header.php"; ?>

<?php
    session_start();
    if(isset($_SESSION['username'])){
        header('Location:dashboard.php');
    }
    if($_SERVER['REQUEST_METHOD']=="POST"){
        //get the information from post
        $username=$_POST['username'];
        $pass=$_POST['pass'];
        $hashed_pass=sha1($pass);

        $stmt=$con->prepare('select user_name ,pass,user_id from users
                            where user_name=?
                            and pass=?
                            and group_id=1
                            limit 1');
        $stmt->execute(array($username,$hashed_pass));
        $arr=$stmt->fetch();
        $count=$stmt->rowCount();
        
        if($count>0){
            $_SESSION['username']=$username;
            $_SESSION['id']=$arr['user_id'];
            header('Location:dashboard.php');
            exit();
        }
    
    
    
    
    
    
    }
    ?>


<form class="login" action="<?php echo $_SERVER['PHP_SELF']; ?> " method='POST'>
    <h3 class="text-center">Admin login</h3>
    <input type="text"  class="form-control" name="username" placeholder="Enter your username" autocomplete="off">
    <input type="password" class="form-control" name="pass" placeholder="Enter your password" autocomplete="new-password">
    <input type="submit" class="form-control btn btn-primary" value="Login">
</form>
















<?php include $templates."footer.php" ?>