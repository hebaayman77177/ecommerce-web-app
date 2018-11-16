<?php
    
    function lang($phrase){
        
        static $lang = array(
            //navbar
            "home"      =>"Home",
            "link"      =>"Link",
            'categories'=>'Categories',
            'logout'    =>'Log Out',
            'members'   =>'Members',
            'statistics'=>'Statistics',
            'profile'   =>'Profile',
            'settings'  =>'Settings',
            'default'   =>'default',
            'dashboard' =>'Dash board',
            'members'   => 'Members',
            'edit_profile'=>"Edit profile"
            );
        
        return $lang[$phrase];
    }