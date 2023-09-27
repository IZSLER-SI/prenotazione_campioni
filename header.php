<?php
//error_reporting(0);  
if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") { 
    $protocol = 'https';
} else { 
    $protocol = 'https';
}
 $cur_dirname = basename(__DIR__);
    if($cur_dirname=='html'){
        $cur_dirname='';
    }
$cur_dir = '/';
$dots = explode(".",$_SERVER['HTTP_HOST']);
 define("ROOT_PATH", $_SERVER["DOCUMENT_ROOT"]);
 define("BASE_URL", substr($cur_dir,0,-1));
 define("SITE_URL",'../'.$cur_dir);
 define("AJAX_URL",'../assets/lib/');
 define("FRONT_URL",'../front/'); 
//}
?>