<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>teacher--add</title>
<link href='<?php echo base_url();?>/admin/css/base.css' rel='stylesheet' type='text/css'>
<script language='javascript'>
	function checkSubmit()
  {
     if(document.form1.subject_name.value==""){
	     alert("subjects's subject_name is not null！");
	     document.form1.subject_name.focus();
	     return false;
     }
     return true;
 }
</script>
</head>
<body background='<?php echo base_url();?>/admin/images/allbg.gif' leftmargin='8' topmargin='8'>
<table width="98%" border="0" align="center" cellpadding="3" cellspacing="1" bgcolor="#D6D6D6">
  <tr>
    <td height="19" background="<?php echo base_url();?>/admin/images/tbg.gif" bgcolor="#E7E7E7"> 
      <table width="96%" border="0" cellspacing="1" cellpadding="1">
        <tr> 
          <td width="24%" style="padding-left:10px;"><b><strong>subjects add</strong></b> </td>
          <td width="76%" align="right"><strong><a href="<?=$_SESSION['admin_path']?>subjects/index"><u>subjects list</u></a></strong></td>
        </tr>
      </table></td>
</tr>
<tr>
    <td height="215" align="center" valign="top" bgcolor="#FFFFFF">
	<form name="form1" action="<?=$_SESSION['admin_path']?>subjects/edit" onSubmit="return checkSubmit();" method="post">
	<input type="hidden" name="dopost" value="save" />
	<input type="hidden" name="subject_id" value="<?php echo $info['subject_id']?>" />
  		<table width="98%" border="0" cellspacing="1" cellpadding="1">
          <tr> 
            <td width="16%" height="30"><span style="color:red;">*</span>Subject name：</td>
            <td width="84%"  style="text-align:left;"><input name="subject_name" type="text" id="subject_name" size="16" style="width:200px" value="<?php echo $info['subject_name']?>"/></td>
          </tr>
          <tr> 
            <td width="16%" height="30">Seq：</td>
            <td width="84%"  style="text-align:left;"><input name="seq" type="text" id="seq" size="16" style="width:200px"  value="<?php echo $info['seq']?>"/></td>
          </tr>
        <tr>
          <td height="30">show flag：</td>
          <td style="text-align:left;">
            <input type="radio" value="1" name="status" <?php if($info['status'] === '1'){ echo 'checked="checked"';} ?> />是
            <input type="radio" value="0" name="status" <?php if($info['status'] === '0'){ echo 'checked="checked"';} ?>/>否
          </td>
        </tr>
          <tr> 
            <td height="60">&nbsp;</td>
            <td><input type="submit" name="Submit" value="save " class="coolbg np" /></td>
          </tr>
        </table>
      </form>
	  </td>
</tr>
</table>
</body>
</html>