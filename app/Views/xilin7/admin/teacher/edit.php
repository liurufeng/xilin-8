<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>teacher--edit</title>
<link href='<?php echo base_url();?>/admin/css/base.css' rel='stylesheet' type='text/css'>
<script language='javascript'>
	function checkSubmit()
  {
     if(document.form1.email.value==""){
	     alert("teacher's email is not null！");
	     document.form1.email.focus();
	     return false;
     }
     if(document.form1.passwd.value==""){
	     alert("teacher's password is not null！");
	     document.form1.passwd.focus();
	     return false;
     }
     if(document.form1.en_name.value==""){
	     alert("teacher's English Name is not null！");
	     document.form1.en_name.focus();
	     return false;
     }
     if(document.form1.phone1.value==""){
	     alert("teacher's Home Phone is not null！");
	     document.form1.phone1.focus();
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
          <td width="24%" style="padding-left:10px;"><b><strong>teacher edit</strong></b> </td>
          <td width="76%" align="right"><strong><a href="<?=$_SESSION['admin_path']?>teacher/index"><u>teacher list</u></a></strong></td>
        </tr>
      </table></td>
</tr>
<tr>
    <td height="215" align="center" valign="top" bgcolor="#FFFFFF">
	<form name="form1" action="<?=$_SESSION['admin_path']?>teacher/edit" onSubmit="return checkSubmit();" method="post">
	<input type="hidden" name="dopost" value="save" />
	<input type="hidden" name="teacher_id" value="<?php echo $info['teacher_id']?>" />
  		<table width="98%" border="0" cellspacing="1" cellpadding="1">
          <tr> 
            <td width="16%" height="30"><span style="color:red;">*</span>Email：</td>
            <td width="84%"  style="text-align:left;"><input name="email" type="text" id="email" size="16" style="width:200px" value="<?php echo $info['email']?>"/></td>
          </tr>
          <tr> 
            <td height="30"><span style="color:red;">*</span>Password：</td>
            <td style="text-align:left;"><input name="passwd" type="password" id="passwd" size="16" style="width:200px"  value="<?php echo $info['passwd']?>"/></td>
          </tr>
          <tr> 
            <td height="30"><span style="color:red;">*</span>English Name：</td>
            <td style="text-align:left;"><input name="en_name" type="text" id="en_name" size="16" style="width:200px"  value="<?php echo $info['en_name']?>"/></td>
          </tr>
          <tr> 
            <td height="30">teacher type</td>
            <td style="text-align:left;">
			    <select name='type' style='width:200px'>
			  	<?php
				if($groupList){
					foreach($groupList as $k=>$v)
					{
						$selected = "";
						if($v['id'] == $info['type']){
							$selected = "selected";
						}
						echo "<option value='".$v['id']."' $selected>".$v['ename']."</option>\r\n";
					}
					}
			  	?>
			  </select>
            </td>
          </tr>
        
          <tr> 
            <td height="30">Chinese Name：</td>
            <td style="text-align:left;"><input name="cn_name" type="text" id="cn_name" size="16" style="width:200px"  value="<?php echo $info['cn_name']?>"/></td>
          </tr>
        
          <tr> 
            <td height="30"><span style="color:red;">*</span>Home Phone：</td>
            <td style="text-align:left;"><input name="phone1" type="text" id="phone1" size="16" style="width:200px" value="<?php echo $info['phone1']?>"/></td>
          </tr>
        
          <tr> 
            <td height="30">Cell Phone：</td>
            <td style="text-align:left;"><input name="phone2" type="text" id="phone2" size="16" style="width:200px"  value="<?php echo $info['phone2']?>" /></td>
          </tr>
        
          <tr> 
            <td height="30">Home Address：</td>
            <td style="text-align:left;"><input name="address" type="text" id="address" size="16" style="width:200px" value="<?php echo $info['address']?>"/></td>
          </tr>
         
          <tr> 
            <td height="30">Bio URL：</td>
            <td style="text-align:left;"><input name="desc_link" type="text" id="desc_link" size="16" style="width:200px" value="<?php echo $info['desc_link']?>"/></td>
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