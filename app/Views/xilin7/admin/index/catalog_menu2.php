<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title></title>
<link href="<?php echo base_url();?>/admin/css/base.css" rel='stylesheet' type="text/css" />
<script language="javascript" src="<?php echo base_url();?>/admin/js/context_menu.js"></script>
<script language="javascript" src="j<?php echo base_url();?>/admin/s/ieemu.js"></script>
<script language="javascript" src="<?php echo base_url();?>/admin/js/dedeajax2.js"></script>
<script language="javascript">
function LoadSuns(ctid,tid)
{
	if($DE(ctid).innerHTML.length < 10){
	  var myajax = new DedeAjax($DE(ctid),true,true,'','x','...');
	  myajax.SendGet('catalog_do.php?dopost=GetSunListsMenu&cid='+tid);
  }
  else{ if(document.all) showHide(ctid); }
}
function showHide(objname)
{
   if($DE(objname).style.display=="none") $DE(objname).style.display = "block";
	 else $DE(objname).style.display="none";
	 return false;
}
if(moz) {
	extendEventObject();
	extendElementModel();
	emulateAttachEvent();
}
</script>
<style>
.nbt{
  font:12px ����; padding: 1px 1px 0 1px ;
  vertical-align:middle ;
  margin:2px 0 2px 0;
  border-left:1px solid #DADF9D;
  border-top:1px solid #DADF9D;
  border-right:1px solid #666666;
  border-bottom:1px solid #666666;
  background:#eff6b6; height:21px ;
  float:left;
  margin-left:8px;
}
div,dd{ margin:0px; padding:0px }
.dlf { margin-right:3px; margin-left:6px; margin-top:2px; float:left }
.dlr { float:left }
.topcc { margin-top:5px }
.suncc { margin-bottom:3px }
dl { clear:left; margin:0px; padding:0px }
.sunct{  }
#items1
{
	border-bottom: 1px solid #74c63f;
  border-left: 1px solid #74c63f;
  border-right: 1px solid #74c63f;
}
.sunlist { width:100%; padding-left:0px; margin:0px; clear:left } 
.tdborder {
  border-left: 1px solid #43938B;
  border-right: 1px solid #43938B;
  border-bottom: 1px solid #43938B;
}
.tdline-left {
  border-bottom: 1px solid #656363;
  border-left: 1px solid #788C47;
}
.tdline-right {
  border-bottom: 1px solid #656363;
  border-right: 1px solid #788C47;
}
.tdrl {
border-left: 1px solid #788C47;
border-right: 1px solid #788C47;
}
.top { cursor: pointer; }
body
{
 padding:3px 0px 0px 0px;
 margin:auto;
 text-align:center;
 background-color:#9ad075;
 background:url(<?php echo base_url();?>/admin/images/leftmenu_bg.gif);
}
</style>
<base target="main" />
</head>
<body target="main" onLoad="ContextMenu.intializeContextMenu()">
<form name="formjump" method="post" target="main" action=""></form>	
<table width='180' border='0' align='center' cellpadding='0' cellspacing='0'>
 
  <tr> 
    <td width="20%" align='center' background='<?php echo base_url();?>/admin/images/mtbg1.gif'  style='border-left: 1px solid #74c63f;'>
    	<a href="#" onClick="showHide('items1')" target="_self"><img src="<?php echo base_url();?>/admin/images/mtimg1.gif" width="21" height="24" border="0" /></a>
    </td>
    <td width="80%" background='<?php echo base_url();?>/admin/images/mtbg1.gif'  style='border-right: 1px solid #74c63f;'>վ��Ŀ¼��</td>
  </tr>
  <tr> 
    <td colspan='2' id='items1' align='center' style="background:#ffffff url(<?php echo base_url();?>/admin/images/mmenubg2.gif) no-repeat;padding-left:8px;"> 
<?php 

?>
    </td>
  </tr>
</table>
</body>
</html>