<?php

    /*
     #######################################
     #manage members page
     #you can add|edit|delete members from here
     #######################################
     */
    session_start();
    if(isset($_SESSION['username'])){
        include 'init.php';
        $pagetitle=lang('members');
        include $templates."header.php";
        include $templates."navbar.php";
        
            $action = isset($_GET['action'])? $_GET['action'] : 'manage';
            $vactions=['add'];
            
            if ($action=='edit' ){
                
                $userid = isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):0; 
                $stmt=$con->prepare('select * from users where user_id=? limit 1');
                $stmt->execute(array($userid));
                $arr=$stmt->fetch();
                $count=$stmt->rowCount();
               
                
                
              ?><div class="container members">
                    <h3 class="text-capitalize mc">Edit you information</h3>
                    <form action="?action=update" method='post'>
                        <input type='hidden' name="id" value="<?php echo $arr['user_id'];?>">
                        <div class="form-group row">
                            <label class='col-md-2'>User name</label>
                            <input type="text" required="required" class="col-md-5" name="username" autocomplete="off" value="<?php echo $arr['user_name'];?>">
                        </div>
                        <div class="form-group row">
                            <input type="hidden" name="oldpass" value='<?php echo $arr['pass'];?>'>
                            <label class='col-md-2'>Pasword</label>
                            <input type="password" class=" col-md-5" name="newpass" placeholder="leave it empty if you don't want to update." autocomplete="new-password">
                        </div>
                        <div class="form-group row">
                            <label class='col-md-2'>Email</label>
                            <input type="email" required="required" class=" col-md-5" name="email" autocomplete="off" value="<?php echo $arr['email'];?>">
                        </div>
                        <div class="form-group row">
                            <input type="submit" class="btn btn-primary mbg custom_botton offset-md-2" value="save">
                        </div>
                    </form>
                </div>
                
           <?php
        }else if ($action == 'delete'){
            echo '<div class="container cc ">';
                echo '<h3>Delete Page</h3>';
            $userid = isset($_GET['id']) && is_numeric($_GET['id'])?$_GET['id']:NULL;
            $check=check_item('user_id','users',$userid);
            if($check>0){
                $stmt=$con->prepare('delete  from users where user_id=?');
                $stmt->execute(array($userid));
                $count=$stmt->rowCount();
                echo '<div class="alert alert-success"><strong>'.$count ." row</strong> deleted.</div>";
                home_redirect('back');
            }else{
                echo '<div class="alert alert-danger">There is no such a user!</div>';
                home_redirect();
            }
            
             echo '</div>';
            
            
        }else if($action == 'activate'){
            
        echo '<div class="container cc ">';
                echo '<h3>Activate Page</h3>';
            $userid = isset($_GET['id']) && is_numeric($_GET['id'])?$_GET['id']:NULL;
            $stmt=$con->prepare('update users set register_status=1 where user_id=?');
            $stmt->execute(array($userid));
            $count=$stmt->rowCount();
            if ($count>0){
                echo '<div class="alert alert-success"><strong>'.$count ." row</strong> deleted.</div>";
                home_redirect('back');
            }
            
             echo '</div>';
        }else if ($action == 'add'){?>
        <!--#when there is error the state is deleted-->
           <div class="container members">
                    <h3 class="text-capitalize mc">Add New member</h3>
                    <form action="?action=insert" method='post'>
                        <div class="form-group row">
                            <label class='col-md-2'>User name</label>
                            <input type="text" required="required" class="col-md-5" name="username" autocomplete="off" >
                        </div>
                        <div class="form-group row">
                            
                            <label class='col-md-2'>Pasword</label>
                            <input type="password" required="required" class="pass col-md-5" name="pass"  autocomplete="new-password">
                            <i class="fas fa-eye col-xs-2 showpass text-center mc"></i>
                        </div>
                        <div class="form-group row">
                            <label class='col-md-2'>Email</label>
                            <input type="email" required="required" class=" col-md-5" name="email" autocomplete="off" >
                        </div>
                        <div class="form-group row">
                            <input type="submit" class="btn btn-primary mbg custom_botton offset-md-2" value="Add Member">
                        </div>
                    </form>
                </div>
        
        <?php
        }else if ($action == 'insert'){
            echo '<div class="container cc ">';
            if($_SERVER['REQUEST_METHOD']=='POST'){ 
                        echo "<h3>Add member</h3>";
                        $pass=$_POST['pass'];
                        $username=$_POST['username'];
                        $email=$_POST['email'];
                        $hpass=sha1($_POST['pass']);
                        $varry=[];
                        if(empty($username)){
                            $varry[]="the username field musn't be empty ";
                        }
                        if(empty($pass)){
                            $varry[]="the password field musn't be empty ";
                        }
                        else if(strlen($username)<3){
                            $varry[]="the username must'nt be less than<strong> 3 characters </strong>";
                        }
                        else if(strlen($username)>15){
                            $varry[]="the username must'nt be more than <strong>15 characters </strong>";
                        }
                        if(empty($email)){
                            $varry[]="the email field musn't be <strong>empty</strong> ";
                        }
                        
                        if($varry===[]){
                            $count=check_item('user_name','users',$username);
                            if ($count>0){
                                echo '<div class="alert alert-danger">this username already exist</div>';
                                home_redirect('back');
                            }else
                            {
                                $stmt=$con->prepare('INSERT INTO
                                                     users(user_name,pass,email,date,register_status)
                                                     VALUES(
                                                        :username ,:pass ,:email,now(),1)
                                                     
                                                     ');
                                $stmt->execute(array('username'=>$username,
                                                     'pass'=>$hpass,
                                                     'email'=>$email));
                                $count=$stmt->rowCount();
                                echo '<div class="alert alert-success"><strong>'.$count ." row</strong> updated.</div>";
                                home_redirect('back');
                            }
                        }else{
                            foreach($varry as $error){
                                echo '<div class="alert alert-danger">';
                                echo $error;
                                echo'</div>';
                            }
                            home_redirect('back');
                        }
                        }else{
                            home_redirect();
                        }
                        echo '</div>';
        }else if($action == 'manage'){ ?>
             <h3 class="manage_h3" >Manage Members</h3>
             <div class='container'>
                <a>
                    <table class="table table-bordered main_table text-center">
                        <tr>
                            <td>#ID</td>
                            <td>Username</td>
                            <td>Email</td>
                            <td>Registerd Date</td>
                            <td>Control</td>
                        </tr> <?php
                        //get the users data
                        $page=isset($_GET['page'])&&$_GET['page']==='binding'?$_GET['page']:'';
                        if($page==='binding'){
                            $quere='and register_status=0';
                        }else{
                            $quere='';
                        }
                        $stmt=$con->prepare("select * from users where group_id !=1  $quere");
                        $stmt->execute();
                        $rows=$stmt->fetchAll();
                        foreach($rows as $row){
                            echo "<tr>";
                                echo "<td>".$row['user_id'] .'</td>';
                                echo "<td>".$row['user_name'] .'</td>';
                                echo "<td>".$row['email'] .'</td>';
                                echo "<td>".$row['date'] . "</td>";
                                echo "<td>";
                                    echo "<a href='?action=delete&id=".$row['user_id'] ."' class='btn btn-danger manage_btn'>Delete</div> ";
                                    echo "<a href='?action=edit&id=" .$row['user_id']."'class='btn btn-success manage_btn'>Edit</div> ";
                                if ($row['register_status']==0){
                                    echo "<a href='?action=activate&id=".$row['user_id'] ."' class='btn btn-info manage_btn'>Activate</div> ";
                                }
                                echo"</td>";
                            echo"</tr>";
                        }
                        ?>
                    </table>
                    <a class='btn btn-primary' href="?action=add" >Add Member</a>
                </div>
             </div>
        
        <?php
        }else if($action == 'update'){
                    echo "<div class='container cc'>";
                    if($_SERVER['REQUEST_METHOD']=='POST'){ 
                        echo "<h3>Update Account</h3>";
                        $id=$_POST['id'];
                        $username=$_POST['username'];
                        $email=$_POST['email'];
                        //update the data base
                        $pass='';
                        if(empty($_POST['newpass'])){
                           
                            $pass=$_POST['oldpass'];
                        }else{
                            $pass=sha1($_POST['newpass']);
                        }
                        $varry=[];
                        if(empty($username)){
                            $varry[]="the username field musn't be empty ";
                        }
                        else if(strlen($username)<3){
                            $varry[]="the username must'nt be less than<strong> 3 characters </strong>";
                        }
                        else if(strlen($username)>15){
                            $varry[]="the username must'nt be more than <strong>15 characters </strong>";
                        }
                        if(empty($email)){
                            $varry[]="the email field musn't be <strong>empty</strong> ";
                        }
                        
                        if($varry===[]){
                            $stmt=$con->prepare('update users set user_name=? ,email=?,pass=? where user_id=? ');
                            $stmt->execute(array($username,$email,$pass,$id));
                            $count=$stmt->rowCount();
                            echo '<div class="alert alert-success"><strong>'.$count ." row</strong> updated.</div>";
                            home_redirect('back');
                        }else{
                            foreach($varry as $error){
                                echo '<div class="alert alert-danger">';
                                echo $error;
                                echo'</div>';
                            }
                            home_redirect($url='back');
                        }
                    }else{
                            home_redirect();
                        }
            
        }
            echo '</div>';
        
        
        
    include $templates."footer.php";
        
    }else{
        header('Location:index.php');
        exit();
    }
    
    ?>
    
    
    
    