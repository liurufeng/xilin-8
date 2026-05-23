<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Admin menu</title>
<link rel="stylesheet" href="<?php echo base_url();?>/admin/css/base.css" type="text/css" />
<script language="javascript" type="text/javascript" src="<?php echo base_url();?>/admin/js/dedeajax2.js"></script>
<script src="<?php echo base_url();?>/admin/js/jquery/jquery.js" language="javascript" type="text/javascript"></script>
<?php
echo "<script language='javascript'>var curopenItem = '11';</script>\r\n";
?>
<script language="javascript" type="text/javascript" src="<?php echo base_url();?>/admin/js/leftmenu.js"></script>
<style>
div {
	padding:0px;
	margin:0px;
}
body {
	padding:0px;
	margin:auto;
	text-align:center;
	background-color:#eff5ed;
	background:url<?php echo base_url();?>/admin/images/leftmenu_bg.gif);
	padding-left:3px;
	overflow:scroll;
	overflow-x:hidden;
	scrollbar-face-color: #eff8e6;
	scrollbar-shadow-color: #edf2e3;
	scrollbar-highlight-color: #ffffff;
	scrollbar-3dlight-color: #F2F2F2;
	scrollbar-darkshadow-color: #bdbcbd;
	scrollbar-arrow-color: #bdbcbd
}
dl.bitem {
	clear:both;
	width:140px;
	margin:0px 0px 5px 12px;
	background:url(<?php echo base_url();?>/admin/images/menunewbg.gif) repeat-x;
}
dl.bitem2 {
	clear:both;
	width:140px;
	margin:0px 0px 5px 12px;
	background:url(<?php echo base_url();?>/admin/images/menunewbg2.gif) repeat-x;
}
dl.bitem dt, dl.bitem2 dt {
	height:25px;
	line-height:25px;
	padding-left:35px;
	cursor:pointer;
}
dl.bitem dt b, dl.bitem2 dt b {
	color:#4D6C2F;
}
dl.bitem dd, dl.bitem2 dd {
	padding:3px 3px 3px 3px;
	background-color:#fff;
}
div.items {
	clear:both;
	padding:0px;
	height:0px;
}
.fllct {
	float:left;
	width:85px;
}
.flrct {
	padding-top:3px;
	float:left;
}
.sitemu li {
	padding:0px 0px 0px 18px;
	line-height:22px;
	background:url(<?php echo base_url();?>/admin/images/arr4.gif) no-repeat 5px 9px;
}
ul {
	padding-top:3px;
}
li {
	height:22px;
}
a.mmac div {
	background:url(<?php echo base_url();?>/admin/images/leftbg2.gif) no-repeat;
	height:37px!important;
	height:47px;
	padding:6px 4px 4px 10px;
	word-wrap: break-word;
	word-break : break-all;
	font-weight:bold;
	color:#325304;
}
a.mm div {
	background:url(<?php echo base_url();?>/admin/images/leftmbg1.gif) no-repeat;
	height:37px!important;
	height:47px;
	padding:6px 4px 4px 10px;
	word-wrap: break-word;
	word-break : break-all;
	font-weight:bold;
	color:#475645;
	cursor:pointer;
}
a.mm:hover div {
	background:url(<?php echo base_url();?>/admin/images/leftbg2.gif) no-repeat;
	color:#4F7632;
}
.mmf {
	height:1px;
	padding:5px 7px 5px 7px;
}
#mainct {
	padding-top:8px;
	background: url(<?php echo base_url();?>/admin/images/idnbg1.gif) repeat-y;
}
</style>
<link href="<?php echo base_url();?>/admin/images/style4/style.css" rel="stylesheet" type="text/css" />
<base target="main" />
</head>
<body target="main" onLoad="CheckOpenMenu();">
<table width="180" align="left" border='0' cellspacing='0' cellpadding='0' style="text-align:left;">
  <tr>
    <td valign='top' style='padding-top:10px' width='20'>
		<!--<a id='link9999' class='mm'>
		  <div onClick="ShowMainMenu(9999)">信息</div>
		  </a> -->

      <div class='mmf'></div>
	  </td>
		<td width="160" valign="top" id="mainct">
 <?php 
 $i = '0';
 foreach ($menus as $k=>$v){ 
 ?>
<div id="ct<?php echo $k?>" style="display: <?php if($i =='0'){ echo 'block';}else{ echo 'none';}?>;">
	<?php 
	if ($v['child_count'] > 0) {
	foreach($v['child'] as $k2=>$v2){
		if (!empty($v2['child'])){
	?>
	<!-- Item * Strat -->
	<dl id="sunitems<?php echo $v2['id']?>_<?php echo $k?>" class="bitem">
	<dt onclick="showHide(&quot;items<?php echo $v2['id']?>_<?php echo $k?>&quot;)"><b><?php echo $v2['name']?></b></dt>
	<dd id="items<?php echo $v2['id']?>_<?php echo $k?>" class="sitem" style="display:none">
	<ul class="sitemu">
		<?php 
		if ($v2['child_count'] > 0) 
		{
			foreach ($v2['child'] as $k3=>$v3)
			{ 
			?>
			<li><a target="main" href="<?php echo $v3['url']?>" title="<?php echo $v3['name']?>"><?php echo $v3['name']?></a>
			</li>
			<?php 
			}

		}
		?>
	</ul>
	</dd>
	</dl>
	<!-- Item * End -->
	<?php 
	}
	
	}
	}?>
 </div>
 <?php  
 $i++;
 }?>

 
 <div id="ct9999" style="display: block;">
	<?php foreach($result as $k=>$v){
	    $auth =session()->get('authorized_nodes');
  if(is_array($auth) && in_array($v['id'], session()->get('authorized_nodes'))) {
    ?>
	<dl id="sunitems<?php echo $v['id']?>_9999" class="bitem">
	<dt onclick="showHide(&quot;items<?php echo $v['id']?>_9999&quot;)"><b><?php echo $v['typename']?></b></dt>
	<dd id="items<?php echo $v['id']?>_9999" class="sitem" style="display:none">
	<ul class="sitemu">
		<?php foreach($v['child'] as $key=>$val){
      if(in_array($val['id'], session()->get('authorized_nodes')) && $val['active'] > 0) {
      if($val['typename'] == "Teachers"){?>
      <li><a target="main" href="<?=$_SESSION['admin_path']?>teacher/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['typename'] == "Calendar"){?>
      <li><a target="main" href="<?=$_SESSION['admin_path']?>calendar/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['typename'] == "Semester"){?>
      <li><a target="main" href="<?=$_SESSION['admin_path']?>semester/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['typename'] == "Subjects"){?>
      <li><a target="main" href="<?=$_SESSION['admin_path']?>subjects/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['typename'] == "Classes"){?>
      <li><a target="main" href="<?=$_SESSION['admin_path']?>classes/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['typename'] == "Sponsor Level"){?>
      <li><a target="main" href="<?=$_SESSION['admin_path']?>sponsorlevel/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['typename'] == "Sponsors Info"){?>
      <li><a target="main" href="<?=$_SESSION['admin_path']?>sponsorinfo/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['typename'] == "Sponsor Payments"){?>
      <li><a target="main" href="<?=$_SESSION['admin_path']?>sponsorpayments/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "50"){?>
      <li><a target="main" href="<?=$_SESSION['admin_path']?>schooluser/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['typename'] == "Admin Groups"){?>
      <li><a target="main" href="<?=$_SESSION['admin_path']?>member/group?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['typename'] == "Admin Users"){?>
      <li><a target="main" href="<?=$_SESSION['admin_path']?>member/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "82"){?>
      <li><a target="main" href="<?=$_SESSION['admin_path']?>pod/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['typename'] == "Newsletters"){?>
      <li><a target="main" href="<?=$_SESSION['admin_path']?>newsletters/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "89"){?>
        <li><a target="main" href="<?=$_SESSION['admin_path']?>classstudents/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "90"){?>
        <li><a target="main" href="<?=$_SESSION['admin_path']?>teacherstudents/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "91"){?>
        <li><a target="main" href="<?=$_SESSION['admin_path']?>parentemails/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "92"){?>
        <li><a target="main" href="<?=$_SESSION['admin_path']?>findpass/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "93"){?>
        <li><a target="main" href="<?=$_SESSION['admin_path']?>failedpod/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "94"){?>
        <li><a target="main" href="<?=$_SESSION['admin_path']?>manualpod/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['typename'] == "POD Reports"){?>
        <li><a target="main" href="<?=$_SESSION['admin_path']?>podreports/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "96"){?>
        <li><a target="main" href="<?=$_SESSION['admin_path']?>payment/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "98"){?>
        <li><a target="main" href="<?=$_SESSION['admin_path']?>payment/todate?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "99"){?>
        <li><a target="main" href="<?=$_SESSION['admin_path']?>event_dates/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "100"){?>
        <li><a target="main" href="<?=$_SESSION['admin_path']?>podwaiver/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "101"){?>
        <li><a target="main" href="<?=$_SESSION['admin_path']?>discounter/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "102"){?>
        <li><a target="main" href="<?=$_SESSION['admin_path']?>payment/online?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "103"){?>
        <li><a target="main" href="<?=$_SESSION['admin_path']?>email_list/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      elseif($val['id'] == "120"){?>
        <li><a target="main" href="<?=$_SESSION['admin_path']?>roster/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      else{?>
      <li><a target="main" href="<?=$_SESSION['admin_path']?>info/index?cid=<?php echo $val['id']?>" title="<?php echo $val['typename']?>"><?php echo leftStr2($val['typename'],16)?></a></li>
      <?php }
      }
    }?>
	</ul>
	</dd>
	</dl>
	<?php }
  }?>
 </div>
	  

</td>
  </tr>
  <tr>
    <td width='26'></td>
    <td width='160' valign='top'><img src='<?php echo base_url();?>/admin/images/idnbgfoot.gif' /></td>
  </tr>
</table>


<script language='javascript'>
	ShowMainMenu(9999);
</script>

</body>
</html>