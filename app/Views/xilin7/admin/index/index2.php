<!--This is IE DTD patch , Don't delete this line.-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Xilin Admin</title>
<link href="<?php echo base_url();?>/admin/css/frame.css" rel="stylesheet" type="text/css" />
<script src="<?php echo base_url();?>/admin/js/jquery/jquery.js" language="javascript" type="text/javascript"></script>
<script src="<?php echo base_url();?>/admin/js/frame.js" language="javascript" type="text/javascript"></script>
<link href="<?php echo base_url();?>/admin/images/style4/style.css" rel="stylesheet" type="text/css" />
<style type="text/css">
#skinlist {
    display: block;
    height: 11px;
	margin-top: 10px;
    overflow: hidden;
    width: 86px;
}
#skin div {
    float: left;
}
#skin li {
    cursor: pointer;
    float: left;
    height: 11px;
    width: 14px;
}
#def div, #s1 div, #s2 div, #s3 div, #s4 div{
    background-image: url("<?php echo base_url();?>/admin/images/skinbutton.png");
    background-repeat: no-repeat;
}
#s1 div {
    background-position: 0 0px;
}
#s2 div {
    background-position: 0 -11px;
}
#s3 div {
    background-position: 0 -22px;
}
#s4 div {
    background-position: 0 -33px;
}
#s1 div.sel {
    background: url("<?php echo base_url();?>/admin/images/skinbutton.png") no-repeat scroll -14px top transparent;
}
#s2 div.sel {
    background: url("<?php echo base_url();?>/admin/images/skinbutton.png") no-repeat scroll -14px -11px transparent;
}
#s3 div.sel {
    background: url("<?php echo base_url();?>/admin/images/skinbutton.png") no-repeat scroll -14px -22px transparent;
}
#s4 div.sel {
    background: url("<?php echo base_url();?>/admin/images/skinbutton.png") no-repeat scroll -14px -33px transparent;
}
</style>
</head>
<body class="showmenu">
<div class="pagemask"></div>
<iframe class="iframemask"></iframe>

<div class="allmenu">
  <div class="allmenu-box">
	demo
    <br style='clear:both' />
  </div>
</div>
<div class="head">
  <div class="top">
    <div class="top_logo">  </div>
    <div class="top_link">
      <ul>
        <li class="welcome">Hello Admin User！</li>
        <li><a href="<?=$_SESSION['admin_path']?>index/index_menu" target="menu">Main Menu</a></li>
       
        <li><a href="#" onclick="JumpFrame('<?=$_SESSION['admin_path']?>index/index_menu','<?=$_SESSION['admin_path']?>index/index_body');">Main Page</a></li>
        <li><a href="<?php echo base_url('index.php')?>" target="_blank">Website</a></li>
        <li><a href="<?=$_SESSION['admin_path']?>login/logout" target="_top">Logout</a></li>
      </ul>

    </div>
  </div>
  <div class="topnav">
    <div class="menuact"> 
		<a href="#" id="togglemenu">Hide the Menu</a>
    </div>
  </div>
</div>
<div class="left">
  <div class="menu" id="menu">
    <iframe src="<?=$_SESSION['admin_path']?>index/index_menu" id="menufra" name="menu" frameborder="0"></iframe>
  </div>
</div>
<div class="right">
  <div class="main">
    <iframe id="main" name="main" frameborder="0" src="<?=$_SESSION['admin_path']?>index/index_body"></iframe>
  </div>
</div>
<script language="javascript">
function JumpFrame(url1, url2){
    jQuery('#menufra').get(0).src = url1;
    jQuery('#main').get(0).src = url2;
}
</script>
</body>
</html>
