
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
   <!--<a class="nav-link nvisible" href="#">kkkkkk</a>-->
  <a class="navbar-brand" href="dashboard.php"><?php echo lang('home');?></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      
     <!-- <li class="nav-item nvisible">
        <a class="nav-link" href="#">kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk</a>
      </li>-->
      
      <li class="nav-item">
        <a class="nav-link" href="categories.php"><?php echo lang('categories');?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="members.php?action=manage"><?php echo lang('members');?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#"><?php echo lang('statistics');?></a>
      </li>

      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Heba
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="members.php?action=edit&id=<?php echo $_SESSION['id']?>"><?php echo lang('edit_profile');?></a>
          <a class="dropdown-item" href="#"><?php echo lang('settings');?></a>
          <a class="dropdown-item" href="logout.php"><?php echo lang('logout');?></a>
        </div>
      </li>
    </ul>
   
   
  </div>
</nav>
















