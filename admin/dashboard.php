<?php
session_start();
include 'init.php';
$pagetitle=lang('dashboard');
include $templates."header.php";
include $templates."navbar.php";


if(!isset($_SESSION['username'])){
      
      header('Location:index.php');
      exit();
}

$number_of_latest_users=5;
$rows_of_latest_users=get_latest_items('*','users','user_id',$number_of_latest_users);

$number_of_latest_items=5;
$rows_of_latest_items=get_latest_items('*','items','item_id',$number_of_latest_users);

/* start dashboard */
?>
<div class='container home-status text-center cc'>
      <h1>DASHBOARD</h1>
      <div class='row'>
           <div class="stat col-md-3 us row">
                  <i class="fa fa-users fa-3x col-sm-4 hs-i"></i>
                  <span class="numberof col-sm-8">
                        Total Members
                        <span ><a href='members.php'><?php echo count_items('user_id','users'); ?></a></span>
                  </span>
           </div>
           <div class="stat col-md-3 bus row">
                  <i class="fas fa-user-plus fa-3x col-sm-4 hs-i"></i>
                  <span class="numberof col-sm-8">
                        Pinding Members
                        <span><a href='members.php?action=manage&page=binding'>
                              <?php echo check_item('register_status','users',0);?>
                        </a></span>
                  </span>
            </div>
           <div class="stat col-md-3 is row">
                  <i class="fas fa-tags fa-3x col-sm-4 hs-i"></i>
                  <span class="numberof col-sm-8">
                        Total Items
                        <span><a href='items.php'><?php echo count_items('item_id','items'); ?></a></span>
                       
                  </span>
           </div>
           <div class="stat col-md-3 cs row ">
                  <i class="fas fa-comments fa-3x col-sm-4 hs-i"></i>
                  <span class="numberof col-sm-8 ">
                        Total Comments
                        <span >50</span>
                  </span>
            </div>
      </div>   
</div>
<div class="container latest cc">
      <div class="row">
            <div class="card col-md-6">
                  <h5 class="card-header "> <i class="fa fa-users"></i>
                  latest <?php echo count($rows_of_latest_users)>1?count($rows_of_latest_users)." registered users":count($rows_of_latest_users)." registered user";?>
                  <i class="fas fa-plus float-right b-m"></i>
                  <i class="fas fa-minus float-right selected b-m"></i></h5>
                  <div class="card-body">
                        <?php
                        echo '<ul class="list-unstyled latest_users control selected">';
                              foreach($rows_of_latest_users as $row){
                                    echo "<li >";
                                          echo $row['user_name'];
                                          echo '<a class="btn btn-info float-right astyle" href="members.php?action=edit&id='.$row['user_id'].'">Edit</a>';
                                          if ($row['register_status']==0){
                                                echo "<a class= 'btn btn-success manage_btn float-right astyle' href='members.php?action=activate&id=". $row['user_id']."'>Activate</a>"; }
                                    echo "</li>";}
                        echo '</ul>';
                        ?>
                  </div>
            </div>
            <div class="card col-md-6">
                  <h5 class="card-header">
                        <i class="fa fa-tag"></i>
                        latest  <?php echo count($rows_of_latest_items)>1?count($rows_of_latest_items)." items":count($rows_of_latest_items)." item";?>
                        <i class="fas fa-plus float-right b-m"></i>
                        <i class="fas fa-minus float-right selected b-m"></i></h5>
                  <div class="card-body">
                              <ul class="list-unstyled latest_users control selected">
                        <?php
                              foreach($rows_of_latest_items as $row){
                                    echo "<li >";
                                          echo $row['name'];
                                          echo '<a class="btn btn-info float-right astyle" href="items.php?action=edit&id='.$row['item_id'].'">Edit</a>';
                                          if ($row['activate']==0){
                                                echo "<a class= 'btn btn-success manage_btn float-right astyle' href='itmes.php?action=activate&id=". $row['item_id']."'>Activate</a>"; }
                                    echo "</li>";}
                        echo '</ul>';
                        ?>
                  </div>
            </div> 
      </div>
</div>
<br>
<br>
<?php
/* end dashboard */
include $templates."footer.php";






















