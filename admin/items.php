<?php

    ob_start();
    session_start();
    include 'init.php';
    $pagetitle='Items';
    include $templates."header.php";
    include $templates."navbar.php";
    
    
    if(!isset($_SESSION['username'])){
          
          header('Location:index.php');
          exit();
          
    }
    
    $action=isset($_GET['action'])?$_GET['action']:'manage';
 
    if($action=='manage'){?>
        <h3 class="manage_h3 cc" >Manage Items</h3>
        <div class='container'>
            <table class="table table-bordered main_table text-center">
                <tr>
                    <td>#ID</td>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Price</td>
                    <td>Country Made</td>
                    <td>Category Name</td>
                    <td>User Name</td>
                    <td>Date</td>
                    <td>Control</td> 
                </tr> <?php
                //get the users data
                $stmt=$con->prepare("SELECT items.* ,users.user_name ,categories.name as cat_name 
FROM items INNER JOIN categories on items.cat_id=categories.id 
INNER JOIN users on users.user_id=items.member_id ");
                $stmt->execute();
                $rows=$stmt->fetchAll();
                foreach($rows as $row){
                    echo "<tr>";
                        echo "<td>".$row['item_id'] .'</td>';
                        echo "<td>".$row['name'] .'</td>';
                        echo "<td>".$row['description'] .'</td>';
                        echo "<td>".$row['price'] . "</td>";
                        echo "<td>".$row['country_made'] . "</td>";
                        echo "<td>".$row['cat_name'] . "</td>";
                        echo "<td>".$row['user_name'] . "</td>";
                        echo "<td>".$row['date'] . "</td>";
                        echo "<td>";
                            echo "<a href='?action=delete&id=".$row["item_id"]."' class='btn btn-danger btn-sm confirm'>Delete</a> ";
                            echo "<a href='?action=edit&id=".$row["item_id"]."'  class='btn btn-success btn-sm '>Edit</a> ";
                            if($row["activate"]==0){
                                echo "<a href='?action=activate&id=".$row["item_id"]."' class='btn btn-info btn-sm '>Activate</a> ";
                            }
                        echo"</td>";
                    echo"</tr>";
                }
                    ?>
                </table>
                <a class='btn btn-primary' href="?action=add" >Add Item</a>
            </div>
        </div>
        
        <?php
    }elseif($action=='add'){
        ?>
        <!--#when there is error the state is deleted-->
        <div class="container items cc">
            <h3 class="text-capitalize mc">Add New Item</h3>
            <form action="?action=insert" method='post'>
                <div class="form-group row cc">
                    <label class='col-md-2'>Name</label>
                    <input type="text" required="required" class="col-md-5" name="name" placeholder="The name of the item" >
                </div>
                <div class="form-group row">
                    <label class='col-md-2'>Description</label>
                    <input type="text" required="required" class="pass col-md-5" name="description" placeholder="Descripe your item">
                </div>
                <div class="form-group row">
                    <label class='col-md-2'>Price</label>
                    <input type="text" required="required" class=" col-md-5" name="price" placeholder="the price you want for your item" >
                </div>
                <div class="form-group row">
                    <label class='col-md-2'>Country</label>
                    <input type="text"  required="required" class=" col-md-5" name="country" placeholder="made in which country?" >
                </div>
                <div class="form-group row">
                    <label class='col-md-2' >Member</label>
                    <select name="member_id" class=" col-md-5 ">
                        <option value="0">...</option><?php
                        $stmt=$con->prepare('select user_name,user_id from users');
                        $stmt->execute();
                        $rows=$stmt->fetchAll();
                        foreach($rows as $row){
                            echo '<option value="'.$row["user_id"].'">'.$row['user_name']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group row">
                    <label class='col-md-2' >Category</label>
                    <select name="cat_id" class=" col-md-5 ">
                        <option value="0">...</option><?php
                        $stmt=$con->prepare('select id,name from categories');
                        $stmt->execute();
                        $rows=$stmt->fetchAll();
                        foreach($rows as $row){
                            echo '<option value="'.$row["id"].'">'.$row['name']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group row">
                    <label class='col-md-2' >Status</label>
                    <select name="status" class=" col-md-5 ">
                        <option value="0">...</option>
                        <option value="1">new</option>
                        <option value="2">like new</option>
                        <option value="3">old</option>
                        <option value="4">bad</option>
                    </select>
                </div>
                <div class="form-group row">
                    <input type="submit" class="btn btn-primary mbg custom_botton offset-md-2" value="Add Item">
                </div>
            </form>
        </div>
        <?php
        
    }elseif($action=='insert'){
        echo '<div class="container cc ">';
        if($_SERVER['REQUEST_METHOD']=='POST'){ 
            echo "<h3>Add Item</h3>";
            $name=          $_POST['name'];
            $description=   $_POST['description'];
            $price=         $_POST['price'];
            $country=       $_POST['country'];
            $status=        $_POST['status'];
            $cat_id=        $_POST['cat_id'];
            $member_id=        $_POST['member_id'];
          
            $varry=[];
            if(empty($name)){
                $varry[]="The item name field musn't be empty ";
            }
            if(empty($price)){
                $varry[]="The price field musn't be empty ";
            }
            if(empty($description)){
                $varry[]="The description field musn't be empty ";
            }
        
            if(empty($country)){
                $varry[]="The country field musn't be empty";
            }
            if(empty($status)){
                $varry[]="You must choose a status";
            }
            if(empty($cat_id)){
                $varry[]="You must choose a category";
            }
            if(empty($member_id)){
                $varry[]="You must choose a member";
            }
            
            if($varry===[]){
                $stmt=$con->prepare('INSERT INTO
                                     items(name,description,price,country_made,status,date,cat_id,member_id)
                                     VALUES(
                                        :name,:description,:price,:country,:status,now(),:cat_id,:member_id)
                                     
                                     ');
                $stmt->execute(array('name'=>$name,
                                     'description'=>$description,
                                     'price'=>$price,
                                     'country'=>$country,
                                     'status'=>$status,
                                     'cat_id'=>$cat_id,
                                     'member_id'=>$member_id
                                     ));
                $count=$stmt->rowCount();
                echo '<div class="alert alert-success"><strong>'.$count ." row</strong> updated.</div>";
                home_redirect('back');
               
            }else{
                foreach($varry as $error){
                    echo '<div class="alert alert-danger">';
                    echo $error;
                    echo'</div>';}
                home_redirect('back');}
        }else{
                home_redirect();}
        echo '</div>';
        
    }elseif($action=='edit'){
        $id = isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):null; 
        $stmt=$con->prepare('select * from items where item_id=? ');
        $stmt->execute(array($id));
        $arr=$stmt->fetch();
        ?>
        <div class="container members">
            <h3 class="text-capitalize mc">Edit Item</h3>
            <form action="?action=update" method='post'>
                <input type='hidden' name="id" value="<?php echo $arr['item_id'];?>">
                <div class="form-group row cc">
                    <label class='col-md-2'>Name</label>
                    <input type="text" required="required" class="col-md-5" name="name" placeholder="The name of the item" value="<?php echo $arr['name'];?>" >
                </div>
                <div class="form-group row">
                    <label class='col-md-2'>Description</label>
                    <input type="text" required="required" class="pass col-md-5" name="description" placeholder="Descripe your item" value="<?php echo $arr['description']; ?>">
                </div>
                <div class="form-group row">
                    <label class='col-md-2'>Price</label>
                    <input type="text" required="required" class=" col-md-5" name="price" placeholder="the price you want for your item" value="<?php echo $arr['price'];?>">
                </div>
                <div class="form-group row">
                    <label class='col-md-2'>Country</label>
                    <input type="text"  required="required" class=" col-md-5" name="country" placeholder="made in which country?" value="<?php echo $arr['country_made'];?>">
                </div>
                <div class="form-group row">
                    <label class='col-md-2' >Member</label>
                    <select name="member_id" class=" col-md-5 ">
                        <?php
                        $stmt=$con->prepare('select user_name,user_id from users');
                        $stmt->execute();
                        $urows=$stmt->fetchAll();
                        foreach($urows as $urow){
                            $str= '<option value="'.$urow["user_id"].'"';
                            if ($urow["user_id"]==$arr["member_id"]){$str= $str." selected";}
                            $str=$str. '>'.$urow['user_name']."</option>";
                            echo $str;
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group row">
                    <label class='col-md-2' >Category</label>
                    <select name="cat_id" class=" col-md-5 ">
                        <?php
                        $stmt=$con->prepare('select id,name from categories');
                        $stmt->execute();
                        $rows=$stmt->fetchAll();
                        foreach($rows as $row){
                            $selected=($arr["cat_id"]==$row["id"])?"selected ":"bad";
                            echo '<option value="'.$row["id"].'"'.$selected.'>'.$row['name']."</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group row">
                    <label class='col-md-2' >Status</label>
                    <select name="status" class=" col-md-5 ">
                        <option value="1" <?php if($arr["status"]==1){echo "selected";} ?>>new</option>
                        <option value="2" <?php if($arr["status"]==2){echo "selected";} ?> >like new</option>
                        <option value="3" <?php if($arr["status"]==3){echo "selected";} ?> >old</option>
                        <option value="4" <?php if($arr["status"]==4){echo "selected";} ?> >bad</option>
                    </select>
                </div>
                <div class="form-group row">
                    <input type="submit" class="btn btn-primary mbg custom_botton offset-md-2" value="Add Item">
                </div>
            </form>
          </div>
      
 <?php

    }elseif($action=='update'){
        echo "<div class='container cc'>";
        if($_SERVER['REQUEST_METHOD']=='POST'){ 
            echo "<h3>Update Item</h3>";
            $v=$_POST['id'];
            $name=$_POST['name'];
            $description=$_POST['description'];
            $price=$_POST['price'];
            $country=$_POST['country'];
            $member_id=$_POST['member_id'];
            $cat_id=$_POST['cat_id'];
            $status=$_POST['status'];
            //update the data base
            $varry=[];
            if(empty($name)){
                $varry[]="the name field musn't be empty ";
            }
            if(empty($description)){
                $varry[]="the description field musn't be empty ";
            }
            if(empty($price)){
                $varry[]="the price field musn't be empty ";
            }
            if(empty($status)){
                $varry[]="the status field musn't be empty ";
            }
            if(empty($cat_id)){
                $varry[]="the category field musn't be empty ";
            }
            if(empty($member_id)){
                $varry[]="the member field musn't be empty ";
            }
        
            if($varry===[]){
                $stmt=$con->prepare('update items set name=? ,description=?,price=? ,country_made=? ,cat_id=? ,member_id=? ,status=? where item_id=? ');
                $stmt->execute(array($name,$description,$price,$country,$cat_id,$member_id,$status,$v));
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


        echo '</div>';
        
    }elseif($action=='delete'){
        echo '<div class="container cc ">';
        echo '<h3>Delete Page</h3>';
        $id = isset($_GET['id']) && is_numeric($_GET['id'])?$_GET['id']:NULL;
        $check=check_item('item_id','items',$id);
        if($check>0){
            $stmt=$con->prepare('delete  from items where item_id=?');
            $stmt->execute(array($id));
            $count=$stmt->rowCount();
            echo '<div class="alert alert-success"><strong>'.$count ." row</strong> deleted.</div>";
            home_redirect('back');
        }else{
            echo '<div class="alert alert-danger">There is no such an item!</div>';
            home_redirect();
        }
             echo '</div>';    
    }else if($action="activate"){
        echo '<div class="container cc ">';
            echo '<h3>Activate Page</h3>';
            $id = isset($_GET['id']) && is_numeric($_GET['id'])?$_GET['id']:NULL;
            $check=check_item('item_id','items',$id);
            if($check>0){
                $stmt=$con->prepare('update items set activate=1 where item_id=?');
                $stmt->execute(array($id));
                $count=$stmt->rowCount();
                echo '<div class="alert alert-success"><strong>'.$count ." row</strong> updated.</div>";
                home_redirect('back');
            }else{
                echo '<div class="alert alert-danger">There is no such an item!</div>';
                home_redirect();
            }
            
             echo '</div>';
    }
    
        
    include $templates."footer.php";
    ob_end_flush();
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    