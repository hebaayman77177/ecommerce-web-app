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

$number_of_users=5;
$rows=get_latest_items('*','users','user_id',5);

/* start dashboard */
?>
<div class='container home-status text-center cc'>
      <h1>DASHBOARD</h1>
      <div class='row'>
           <div class="stat col-md-3 us">
                  Total Members
                  <span><a href='members.php'><?php echo count_items('user_id','users'); ?></a></span>
            </div>
           <div class="stat col-md-3 bus">
                  Pinding Members
                  <span><a href='members.php?action=manage&page=binding'>
                        <?php echo check_item('register_status','users',0);?>
                  </a></span>
            </div>
           <div class="stat col-md-3 is">
                  Total Items
                  <span>200</span>
            </div>
           <div class="stat col-md-3 cs">
                  Total Comments
                  <span>500</span>
            </div>
      </div>   
</div>
<div class="container latest cc">
      <div class="row">
            <div class="card col-md-6">
                  <h5 class="card-header "> <i class="fa fa-users"></i> latest <?php echo $number_of_users;?> registered users</h5>
                  <div class="card-body">
                        <?php
                        echo '<ul class="list-unstyled latest_users">';
                              foreach($rows as $row){
                                    echo "<li>";
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
                  <h5 class="card-header"><i class="fa fa-tag"></i> latest items</h5>
                  <div class="card-body">
                        test
                  </div>
            </div> 
      </div>
</div>
<?php
/* end dashboard */
include $templates."footer.php";






















