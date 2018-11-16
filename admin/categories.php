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
        $order_arr=array('asc','desc');
        $order=isset($_GET['order'])&&in_array($_GET['order'],$order_arr)?$_GET['order']:'asc';
        $statment=$con->prepare("select * from categories order by ordering $order ");
        $statment->execute();
        $rows=$statment->fetchAll();
        ?>
        <div class="container categories cc ">
            <h1>Manage categories</h1>
            <div class="card cc">
                <div class="card-header ">
                    <h3> Categories</h3>
                    <div class=" float-right">
                        The sort is:
                        <a href='?order=asc' <?php if($order=='asc'){echo 'class=active';}?>>ascending</a>
                        |
                        <a href='?order=desc' <?php if($order=='desc'){echo 'class=active';}?>>descending</a>
                        <span class="nvisible">dd</span>
                        The view is:
                        <span class="classic active"> Classic</span>
                        |
                        <span class="full">Full</span>
                    </div>
                </div>
                <div class="card-body">
                    <?php
                    foreach($rows as $row){
                        echo "<div class='cat'>";
                            echo "<div class='hidden-bttns'>";
                                echo "<a href='?action=edit&id=".$row['id']."'class='btn btn-success btn-sm'><i class='fa fa-edit'></i>Edit</a>";
                                 echo "<a href='?action=delete&id=".$row['id']."'class='btn btn-danger btn-sm'><i class='fas fa-times'></i>Delete</a>";
                            echo "</div>";
                            echo "<h3>".$row['name']."</h3>";
                            echo '<div class="full_info hidden">';
                                echo "<p>";if($row['description']==''){
                                                echo "there is no description for this categorie";}
                                            else{ echo $row['description'];} echo "</p>";
                                if($row['visibility']==0){
                                    echo "<span class='visibility'>Hidden</span>";
                                }
                                if($row['allow_comment']==0){
                                    echo "<span class='comment'>No comments allowed</span>";
                                }
                                if($row['allow_advertisement']==0){
                                    echo "<span class='advertisment'>Advertisment not allowed</span>";
                                }
                            echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>
            </div>
            <a href='?action=add'class='btn btn-primary cc ab'>Add New Category</a>
        </div>
        <?php
    }elseif($action=='add'){
        ?>
        <!--#when there is error the state is deleted-->
           <div class="container members">
                    <h3 class="text-capitalize mc">Add New category</h3>
                    <form action="?action=insert" method='post'>
                        <div class="form-group row">
                            <label class='col-md-2'>Category Name</label>
                            <input type="text" required="required" class="col-md-5" name="name"  >
                        </div>
                        <div class="form-group row">
                            <label class='col-md-2'>Description</label>
                            <input type="text"  class="pass col-md-5" name="description" >
                        </div>
                        <div class="form-group row">
                            <label class='col-md-2'>The Order</label>
                            <input type="text"  class=" col-md-5" name="ordering" >
                        </div>
                          <div class="form-group row">
                            <label class='col-md-2'> Visible</label>
                            <div>
                                <input type="radio" id="vis-y" name=visible value=0 checked>
                                <label for="vis-y">yse</label>
                            </div>
                            <div>
                                <input type="radio" id="vis-n" name=visible value=1  >
                                <label for="vis-n">no</label>
                            </div>
                        </div>
                          <div class="form-group row">
                            <label class='col-md-2'> Allow Comments</label>
                            <div>
                                <input type="radio" id="vis-y" name=comment value=0 checked>
                                <label for="vis-y">yse</label>
                            </div>
                            <div>
                                <input type="radio" id="vis-n" value=1 name=comment >
                                <label for="vis-n">no</label>
                            </div>
                        </div>
                          <div class="form-group row">
                            <label class='col-md-2'>Allow Advertisement</label>
                            <div>
                                <input type="radio" id="vis-y" name=advertise value=0 checked>
                                <label for="vis-y">yse</label>
                            </div>
                            <div>
                                <input type="radio" id="vis-n" value=1 name=advertise >
                                <label for="vis-n">no</label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <input type="submit" class="btn btn-primary mbg custom_botton offset-md-2" value="Add Member">
                        </div>
                    </form>
                </div>
        <?php
    }elseif($action=='insert'){
        echo '<div class="container cc ">';
        if($_SERVER['REQUEST_METHOD']=='POST'){ 
                    echo "<h3>Add Category</h3>";
                    $name=$_POST['name'];
                    $description=$_POST['description'];
                    $ordering=$_POST['ordering'];
                    $visible=$_POST['visible'];
                    $comment=$_POST['comment'];
                    $advertise=$_POST['advertise'];
                    
                    if($name != ''){
                        $count=check_item('name','categories',$name);
                        if ($count>0){
                            echo '<div class="alert alert-danger">this categorie already exist</div>';
                            home_redirect('back');
                        }else{
                            $stmt=$con->prepare('INSERT INTO
                                                 categories(name,description,ordering,visibility,allow_comment,allow_advertisement)
                                                 VALUES(
                                                    :name ,:description,:ordering ,:visible,:comment,:advertise)
                                                 
                                                 ');
                            $stmt->execute(array('name'=>$name,
                                                 'description'=>$description,
                                                 'ordering'=>$ordering,
                                                 'visible'=>$visible,
                                                 'comment'=>$comment,
                                                 'advertise'=>$advertise));
                            $count=$stmt->rowCount();
                            echo '<div class="alert alert-success"><strong>'.$count ." row</strong> updated.</div>";
                            home_redirect('back');}
                    }else{
                        echo '<div class="alert alert-danger"><strong>name </strong> must not be empty</div>';
                        home_redirect('back');}
                  
                }
    
    }elseif($action=='edit'){
        $cat_id = isset($_GET['id']) && is_numeric($_GET['id'])?intval($_GET['id']):0; 
        $stmt=$con->prepare('select * from categories where id=? ');
        $stmt->execute(array($cat_id));
        $arr=$stmt->fetch();
        $count=$stmt->rowCount();
        if ($count>0){
            ?><div class="container cc">
            <h3 class="text-capitalize mc">Edit Category</h3>
            <form action="?action=update" method='post'>
                <input type='hidden' name="id" value="<?php echo $arr['id'];?>">
                <div class="form-group row">
                    <label class='col-md-2'>Name</label>
                    <input type="text" required="required" class="col-md-5" name="name"  value="<?php echo $arr['name'];?>">
                </div>
                <div class="form-group row">
                    <label class='col-md-2'>Description</label>
                    <input type="password" class=" col-md-5" name="description" value="<?php echo $arr['description'];?>">
                </div>
                <div class="form-group row">
                    <label class='col-md-2'>Order</label>
                    <input type="text"  class=" col-md-5" name="ordering"  value="<?php echo $arr['ordering'];?>">
                </div>
                <div class="form-group row">
                    <label class='col-md-2'> Visible</label>
                    <div>
                        <input type="radio" id="vis-y" name=visible value=0 <?php if($arr['visibility']==0){echo 'checked';}?>>
                        <label for="vis-y">yse</label>
                    </div>
                    <div>
                        <input type="radio" id="vis-n" name=visible value=1 <?php if($arr['visibility']==1){echo 'checked';}?> >
                        <label for="vis-n">no</label>
                    </div>
                </div>
                <div class="form-group row">
                     <label class='col-md-2'> Allow Comments</label>
                    <div>
                       <input type="radio" id="vis-y" name=comment value=0 <?php if($arr['allow_comment']==0){echo 'checked';}?>>
                       <label for="vis-y">yse</label>
                    </div>
                    <div>
                        <input type="radio" id="vis-n" value=1 name=comment <?php if($arr['allow_comment']==1){echo 'checked';}?> >
                        <label for="vis-n">no</label>
                    </div>
                </div>
                <div class="form-group row">
                    <label class='col-md-2'>Allow Advertisement</label>
                    <div>
                        <input type="radio" id="vis-y" name=advertise value=0 <?php if($arr['allow_advertisement']==0){echo 'checked';}?> >
                        <label for="vis-y">yse</label>
                    </div>
                    <div>
                        <input type="radio" id="vis-n" value=1 name=advertise <?php if($arr['allow_advertisement']==1){echo 'checked';}?>>
                        <label for="vis-n">no</label>
                    </div>
                </div>
                <div class="form-group row">
                    <input type="submit" class="btn btn-primary mbg custom_botton offset-md-2" value="save">
                </div>
            </form>
        </div>
         
   <?php
        } 
    }elseif($action=='update'){
        echo "<div class='container cc'>";
        if($_SERVER['REQUEST_METHOD']=='POST'){ 
            echo "<h3>Update Category</h3>";
            $id=$_POST['id'];
            $name=$_POST['name'];
            $ordering=$_POST['ordering'];
            $visible=$_POST['visible'];
            $advertise=$_POST['advertise'];
            $comment=$_POST['comment'];
            $description=$_POST['description'];
            //update the data base
            $stmt=$con->prepare('update
                                    categories
                                set
                                    name=?
                                    ,description=?
                                    ,ordering=?
                                    ,visibility=?
                                    ,allow_comment=?
                                    ,allow_advertisement=?
                                where
                                    id=? ');
            $stmt->execute(array($name,$description,$ordering,$visible,$comment,$advertise,$id));
            $count=$stmt->rowCount();
            echo '<div class="alert alert-success"><strong>'.$count ." row</strong> updated.</div>";
            home_redirect('back');
        
        }else{
                home_redirect();
            }
        echo '</div>';
        
    }elseif($action=='delete'){
        echo '<div class="container cc ">';
            echo '<h3>Delete Page</h3>';
            $cat_id = isset($_GET['id']) && is_numeric($_GET['id'])?$_GET['id']:NULL;
            $check=check_item('id','categories',$cat_id);
            if($check>0){
                $stmt=$con->prepare('delete  from categories where id=?');
                $stmt->execute(array($cat_id));
                $count=$stmt->rowCount();
                echo '<div class="alert alert-success"><strong>'.$count ." row</strong> deleted.</div>";
                home_redirect('back');
            }else{
                echo '<div class="alert alert-danger">There is no such category!</div>';
                home_redirect();
            }
            
        echo '</div>';
        
    }
    
        
    include $templates."footer.php";
    ob_end_flush();
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    