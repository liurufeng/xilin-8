<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>calendar--add</title>
<link href='<?php echo base_url();?>/admin/css/base.css' rel='stylesheet' type='text/css'>
<script language='javascript'>
	function checkSubmit()
  {
     if(document.form1.header.value==""){
	     alert("calendar's header is not null！");
	     document.form1.header.focus();
	     return false;
     }
     if(document.form1.date.value==""){
	     alert("calendar's date is not null！");
	     document.form1.date.focus();
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
          <td width="24%" style="padding-left:10px;"><b><strong>calendar add</strong></b> </td>
          <td width="76%" align="right"><strong><a href="<?=$_SESSION['admin_path']?>calendar/index"><u>calendar list</u></a></strong></td>
        </tr>
      </table></td>
</tr>
<tr>
    <td height="215" align="center" valign="top" bgcolor="#FFFFFF">
	<form name="form1" action="<?=$_SESSION['admin_path']?>calendar/edit" onSubmit="return checkSubmit();" method="post">
	<input type="hidden" name="dopost" value="save" />
	<input type="hidden" name="calendar_id" value="<?php echo $info['calendar_id']?>" />
  		<table width="98%" border="0" cellspacing="1" cellpadding="1">
          <tr> 
            <td width="16%" height="30"><span style="color:red;">*</span>Semester：</td>
            <td width="84%"  style="text-align:left;">
			    <select name='semester_id' style='width:200px'>
            <?php foreach($semesters as $semester) {?>
              <option value="<?=$semester['semester_id']?>" <?php if($semester['semester_id'] == $info['semester_id']) echo 'selected'; ?> >
                <?=$semester['semester_year']?> <?=$semester['semester_name']?>
              </option>
            <?php } ?>
			  	</select>
           	</td>
          </tr>
          <tr> 
            <td height="30"><span style="color:red;">*</span>header：</td>
            <td style="text-align:left;"><input name="header" type="text" id="header" size="16" style="width:200px" value="<?php echo $info['header']?>"/></td>
          </tr>
          <tr> 
            <td height="30"><span style="color:red;">*</span>date：</td>
            <td style="text-align:left;"><input name="date" type="text" id="date" size="16" style="width:200px"  value="<?php echo $info['date']?>" /></td>
          </tr>
          <tr> 
            <td height="30">session：</td>
            <td style="text-align:left;"><input name="session" type="text" id="session" size="16" style="width:200px"  value="<?php echo $info['session']?>" /></td>
          </tr>
          <tr> 
            <td height="30">note：</td>
            <td style="text-align:left;"><input name="note" type="text" id="note" size="16" style="width:200px"   value="<?php echo $info['note']?>" /></td>
          </tr>
          <tr> 
            <td height="30">show flag：</td>
            <td style="text-align:left;">
            	<input type="radio" name="show_flag" value="1" <?php if($info['show_flag'] == 1){ echo "checked";}?>/>yes
            	<input type="radio" name="show_flag" value="0" <?php if($info['show_flag'] == 0){ echo "checked";}?>/>no
            </td>
          </tr>
          <tr> 
            <td height="30">show order：</td>
            <td style="text-align:left;"><input name="show_order" type="text" id="show_order" size="16" style="width:200px"   value="<?php echo $info['show_order']?>"/></td>
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