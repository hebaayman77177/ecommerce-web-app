$(function(){
    
    'use strict';
    
    //$('[placeholder]').focus(function(){
    //    
    //    $(this).attr('data-text',$(this).atrr('placeholder'));
    //    $(this).attr('placeholder','');
    //}).blur(function(){
    //    
    //    $(this).attr('placeholder',$(this).attr('data-text'));           
    //    });  
    //
    //
    let $place;
 $("input").on('focus',function () {
    $place = $(this).attr("placeholder");
   $(this).attr("placeholder","");
  }).on("blur",function () {
    $(this).attr("placeholder",$place);});
 
    $('input').each(function(){
        if($(this).attr('required')==='required'){
            $(this).after('<span class="astric col-xs-1">*</span>');
        }
        });
    
    
 let pass=$('.pass');
 $('.showpass').hover(function(){
    pass.attr('type','text');
    },function(){
    pass.attr('type','password');}
    );
 
 
 
 //show or not full_info in categories
 $(".categories .cat h3").click(function(){
    $(this).next('.full_info').fadeToggle(200);
    });
 
  $(".categories .card-header .classic").click(function(){
        $(this).addClass("active").siblings('span').removeClass('active');
        $(".full_info").fadeOut(200);
    });
   $(".categories .card-header .full").click(function(){
        $(this).addClass("active").siblings('span').removeClass('active');
        $(".full_info").fadeIn(200);
    });
 
 
 
 
 
 
 
 
 
    
});