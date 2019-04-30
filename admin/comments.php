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
        $pagetitle="Comments";
        include $templates."header.php";
        include $templates."navbar.php";
        
            $action = isset($_GET['action'])? $_GET['action'] : 'manage';
            
            if ($action=='edit' ){
                
                $C_id = isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):0; 
                $stmt=$con->prepare("select comments.* , users.user_name ,items.name 
                                            from comments
                                            inner join users
                                            on comments.user_id=users.user_id
                                            inner join items
                                            on comments.item_id=items.item_id
                                            where c_id=?");
                $stmt->execute(array($C_id));
                $arr=$stmt->fetch();
                $count=$stmt->rowCount();
               
                
                
              ?><div class="container members">
                    <h3 class="text-capitalize mc">Edit The Comment</h3>
                    <form action="?action=update" method='post'>
                        <input type='hidden' name="id" value="<?php echo $arr['c_id'];?>">
                        <div class="form-group row">
                            <label class='col-md-2'>Comment</label>
                            <input type="text" required="required" class="col-md-5" name="comment"  value="<?php echo $arr['text'];?>">
                        </div>
                        <div class="form-group row">
                            <label class='col-md-2'>Date</label>
                            <input type="text" class=" col-md-5" name="date" value="<?php echo $arr['c_date'];?>" >
                        </div>
                        <div class="form-group row">
                            <label class='col-md-2'>Status</label>
                            <div>
                                <input type="radio" id="vis-y" name=status value=0 <?php if($arr['status']==0){echo 'checked';}?>>
                                <label for="vis-y">Not Approved</label>
                            </div>
                            <span class="nvisible">hhhhh</span>
                            <div>
                                <input type="radio" id="vis-n" name=status value=1 <?php if($arr['status']==1){echo 'checked';}?> >
                                <label for="vis-n">Approved</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class='col-md-2' >Item</label>
                            <select name="item" class=" col-md-5 ">
                                <?php
                                $stmt=$con->prepare('select item_id,name from items');
                                $stmt->execute();
                                $urows=$stmt->fetchAll();
                                foreach($urows as $urow){
                                    $str= '<option value="'.$urow["item_id"].'"';
                                    if ($urow["item_id"]==$arr["item_id"]){$str= $str." selected";}
                                    $str=$str. '>'.$urow['name']."</option>";
                                    echo $str;
                                }
                                ?>
                            </select>
                        </div>
                         <div class="form-group row">
                            <label class='col-md-2' >User</label>
                            <select name="user" class=" col-md-5 ">
                                <?php
                                $stmt=$con->prepare('select user_id,user_name from users');
                                $stmt->execute();
                                $urows=$stmt->fetchAll();
                                foreach($urows as $urow){
                                    $str= '<option value="'.$urow["user_id"].'"';
                                    if ($urow["user_id"]==$arr["user_id"]){$str= $str." selected";}
                                    $str=$str. '>'.$urow['user_name']."</option>";
                                    echo $str;
                                }
                                ?>
                            </select>
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
            $c_id = isset($_GET['id']) && is_numeric($_GET['id'])?$_GET['id']:NULL;
            $check=check_item('c_id','comments',$c_id);
            if($check>0){
                $stmt=$con->prepare('delete  from comments where c_id=?');
                $stmt->execute(array($c_id));
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
                echo '<h3>Approve Page</h3>';
            $c_id = isset($_GET['id']) && is_numeric($_GET['id'])?$_GET['id']:NULL;
            $stmt=$con->prepare('update comments set status=1 where c_id=?');
            $stmt->execute(array($c_id));
            $count=$stmt->rowCount();
            if ($count>0){
                echo '<div class="alert alert-success"><strong>'.$count ." row</strong> Aprroved.</div>";
                home_redirect('back');
            }
            
             echo '</div>';
        }else if($action == 'manage'){ ?>
             <div class='container cc'>
                <h3 class="manage_h3" >Manage Comments</h3>
                <a>
                    <table class="table table-bordered main_table text-center">
                        <tr>
                            <td>#ID</td>
                            <td>Comment</td>
                            <td>Date</td>
                            <td>Status</td>
                            <td>Item</td>
                            <td>User</td>
                            <td>Control</td>
                        </tr> <?php
                        //get the users data
                        
                        $stmt=$con->prepare("select comments.* , users.user_name as member,items.name as item
                                            from comments
                                            inner join users
                                            on comments.user_id=users.user_id
                                            inner join items
                                            on comments.item_id=items.item_id");
                        $stmt->execute();
                        $rows=$stmt->fetchAll();
                        foreach($rows as $row){
                            echo "<tr>";
                                echo "<td>".$row['c_id'] .'</td>';
                                echo "<td>".$row['text'] .'</td>';
                                echo "<td>".$row['c_date'] .'</td>';
                                echo "<td>".$row['status'] . "</td>";
                                echo "<td>".$row['item'] . "</td>";
                                echo "<td>".$row['member'] . "</td>";
                                echo "<td>";
                                    echo "<a href='?action=delete&id=".$row['c_id'] ."' class='btn btn-danger manage_btn'>Delete</div> ";
                                    echo "<a href='?action=edit&id=" .$row['c_id']."'class='btn btn-success manage_btn'>Edit</div> ";
                                if ($row['status']==0){
                                    echo "<a href='?action=activate&id=".$row['c_id'] ."' class='btn btn-info manage_btn'>Approve</div> ";
                                }
                                echo"</td>";
                            echo"</tr>";
                        }
                        ?>
                    </table>
                </div>
             </div>
        
        <?php
        }else if($action == 'update'){
                    echo "<div class='container cc'>";
                    if($_SERVER['REQUEST_METHOD']=='POST'){ 
                        echo "<h3>Update Comment</h3>";
                        $id=$_POST['id'];
                        $comment=$_POST['comment'];
                        $date=$_POST['date'];
                        $status=$_POST['status'];
                        $user=$_POST['user'];
                        $item=$_POST['item'];
                        //update the data base
                        
                        $varry=[];
                        if(empty($comment)){
                            $varry[]="the username field musn't be empty ";
                        }
                        
                        if($varry===[]){
                            $stmt=$con->prepare('update comments set text=? ,c_date=?,status=? ,user_id=? ,item_id=?  where c_id=? ');
                            $stmt->execute(array($comment,$date,$status,$user,$item,$id));
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
    
    
    
    