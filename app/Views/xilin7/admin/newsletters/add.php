<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>newsletters--add</title>
<link href='<?php echo base_url();?>/admin/css/base.css' rel='stylesheet' type='text/css'>
<script language='javascript'>
	function checkSubmit()
  {
     if(document.form1.name.value==""){
	     alert("newsletters's name is not null!");
	     document.form1.name.focus();
	     return false;
     }
     return true;
 }
</script>
<script language='javascript' src="<?php echo base_url();?>/admin/js/main.js"></script>
</head>
<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#D6D6D6">
  <tr>
    <td height="19" background="<?php echo base_url();?>/admin/images/tbg.gif" bgcolor="#E7E7E7"> 
      <table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr> 
          <td width="24%" style="padding-left:10px;"><b><strong>newsletters add</strong></b> </td>
          <td width="76%" align="right"><strong><a href="<?=$_SESSION['admin_path']?>newsletters/index"><u>newsletters list</u></a></strong></td>
        </tr>
      </table></td>
</tr>
<tr>
    <td height="215" align="center" valign="top" bgcolor="#FFFFFF">
	<form name="form1" enctype="multipart/form-data" action="<?=$_SESSION['admin_path']?>newsletters/add" onSubmit="return checkSubmit();" method="post">
	<input type="hidden" name="dopost" value="add" />
  		<table width="98%" border="0" cellspacing="1" cellpadding="1">        
          <tr> 
            <td height="30"><span style="color:red;">*</span>name:</td>
            <td style="text-align:left;"><input name="name" type="text" id="name" size="16" style="width:200px" /></td>
          </tr>       
          <tr> 
            <td height="30">url:</td>
            <td style="text-align:left;">
			    <!--<input type="file" name="url" value="" id="url" />-->
              <input type="text" name="url" value="" id="url" size="80"/>
            </td>
          </tr>
            <tr>
                <td height="30">image url:</td>
                <td style="text-align:left;">
                    <!--<input type="file" name="url" value="" id="url" />-->
                    <input type="text" name="img_url" value="" id="url" size="80"/>
                </td>
            </tr>
            <tr>
            <td height="30">isshow:</td>
            <td style="text-align:left;">
            	<input type="radio" name="isshow" value="1" checked/>yes
            	<input type="radio" name="isshow" value="0" />no
            </td>
          </tr>        
          <tr> 
            <td height="30">desc:</td>
            <td style="text-align:left;"><input name="desc" type="text" id="desc" size="16" style="width:200px" /></td>
          </tr>        
          <tr> 
            <td height="30">seq:</td>
            <td style="text-align:left;"><input name="seq" type="text" id="seq" size="16" style="width:200px" /></td>
          </tr> 
          <tr> 
            <td height="60">&nbsp;</td>
            <td><input type="submit" name="Submit" value="add " class="coolbg np" /></td>
          </tr>
        </table>
      </form>
	  </td>
</tr>
</table>
</body>
</html>