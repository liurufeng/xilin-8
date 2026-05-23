<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>semester--edit</title>
<link href='<?php echo base_url();?>/admin/css/base.css' rel='stylesheet' type='text/css'>
<script language='javascript'>
	function checkSubmit()
  {
     if(document.form1.semester_year.value==""){
	     alert("semester's semester_year is not null！");
	     document.form1.semester_year.focus();
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
          <td width="24%" style="padding-left:10px;"><b><strong>semester edit</strong></b> </td>
          <td width="76%" align="right"><strong><a href="<?=$_SESSION['admin_path']?>semester/index"><u>semester list</u></a></strong></td>
        </tr>
      </table></td>
</tr>
<tr>
    <td height="215" align="center" valign="top" bgcolor="#FFFFFF">
	<form name="form1" action="<?=$_SESSION['admin_path']?>semester/edit" onSubmit="return checkSubmit();" method="post">
	<input type="hidden" name="dopost" value="save" />
	<input type="hidden" name="semester_id" value="<?php echo $info['semester_id']?>" />
  		<table width="98%" border="0" cellspacing="1" cellpadding="1">
          <tr> 
            <td width="16%" height="30"><span style="color:red;">*</span>semester year：(e.g. 2016)</td>
            <td width="84%"  style="text-align:left;"><input name="semester_year" type="text" id="semester_year" size="16" style="width:200px" value="<?php echo  $info['semester_year']?>" /></td>
          </tr>
          <tr> 
            <td width="16%" height="30"><span style="color:red;">*</span>semester name：</td>
            <td width="84%"  style="text-align:left;">
	            <select name='semester_name' style='width:200px'>
				  	<?php
					if($groupList){
					foreach($groupList as $k=>$v)
					{
						$selected = "";
						if($v['ename'] == $info['semester_name']){
							$selected = "selected";
						}
						echo "<option value='".$v['ename']."' $selected >".$v['ename']."</option>\r\n";
					}
					}
			  	?>
				  </select>
            </td>
          </tr>
          <tr> 
            <td width="16%" height="30"><span style="color:red;">*</span>semester status：</td>
            <td width="84%"  style="text-align:left;">
	            <select name='semester_status' style='width:200px'>
				  	<?php
					if($semesterstatus){
					foreach($semesterstatus as $k=>$v)
					{
						$selected = "";
						if($v['ename'] == $info['semester_status']){
							$selected = "selected";
						}
						echo "<option value='".$v['ename']."' $selected>".$v['ename']."</option>\r\n";
					}
					}
			  	?>
				  </select>
            </td>
          </tr>
          <tr> 
            <td height="30">late registration date：<br /> (e.g. 2008-06-01 00:00:00)</td>
            <td style="text-align:left;"><input name="late_registration" type="text" id="late_registration" size="16" style="width:200px" value="<?php echo  $info['late_registration']?>"/></td>
          </tr>
          <tr> 
            <td height="30">registration fee：</td>
            <td style="text-align:left;"><input name="registration_fee" type="text" id="registration_fee" size="16" style="width:200px" value="<?php echo  $info['registration_fee']?>"/></td>
          </tr>
          <tr> 
            <td height="30">late registration fee：</td>
            <td style="text-align:left;"><input name="late_registration_fee" type="text" id="late_registration_fee" size="16" style="width:200px"  value="<?php echo  $info['late_registration_fee']?>"/></td>
          </tr>
          <tr> 
            <td height="30">parent discount base：</td>
            <td style="text-align:left;"><input name="parent_discount_base" type="text" id="parent_discount_base" size="16" style="width:200px"  value="<?php echo  $info['parent_discount_base']?>"/></td>
          </tr>
          <tr> 
            <td height="30">teacher discount base：</td>
            <td style="text-align:left;"><input name="teacher_discount_base" type="text" id="teacher_discount_base" size="16" style="width:200px"  value="<?php echo  $info['teacher_discount_base']?>"/></td>
          </tr>
          <tr> 
            <td height="30">show flag：</td>
            <td style="text-align:left;">
            	<input type="radio" value="1" name="show_flag" <?php if($info['show_flag'] === '1'){ echo 'checked="checked"';}?> />是
            	<input type="radio" value="0" name="show_flag" <?php if($info['show_flag'] === '0'){ echo 'checked="checked"';}?>/>否
            </td>
          </tr>
        <tr>
          <td height="30">show calendar：</td>
          <td style="text-align:left;">
            <input type="radio" value="1" name="show_calendar" <?php if($info['show_calendar'] === '1'){ echo 'checked="checked"';}?> />是
            <input type="radio" value="0" name="show_calendar" <?php if($info['show_calendar'] === '0'){ echo 'checked="checked"';}?> />否
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