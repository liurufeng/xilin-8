<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>manual_pod_record--edit</title>
<link href='<?php echo base_url();?>/admin/css/base.css' rel='stylesheet' type='text/css'>
<script language='javascript'>
	function checkSubmit()
  {
    if(document.form1.manual_records.value==""){
      alert("manual_records can not be empty!");
      document.form1.manual_records.focus();
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
          <td width="24%" style="padding-left:10px;"><b><strong>manual_pod_record add</strong></b> </td>
          <td width="76%" align="right"><strong><a href="<?=$_SESSION['admin_path']?>manualpod/index"><u>manual_pod_record list</u></a></strong></td>
        </tr>
      </table></td>
</tr>
<tr>
    <td height="215" align="center" valign="top" bgcolor="#FFFFFF">
	<form name="form1" action="<?=$_SESSION['admin_path']?>manualpod/edit" onSubmit="return checkSubmit();" method="post">
	<input type="hidden" name="dopost" value="save" />
	<input type="hidden" name="manual_pod_record_id" value="<?php echo $list['manual_pod_record_id']?>" />
  		<table width="98%" border="0" cellspacing="1" cellpadding="1">
        <tr>
          <td height="30"><span style="color:red;">*</span>Semester:</td>
          <td style="text-align:left;">
            <select id="semester_id" name="semester_id">
              <?php foreach($semesters as $semester) {?>
                <option value="<?=$semester['semester_id']?>" <?php if($semester['semester_id'] == $list['semester_id']) echo 'selected'; ?> >
                  <?=$semester['semester_year']?> <?=$semester['semester_name']?>
                </option>
              <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td height="30"><span style="color:red;">*</span>Parent:</td>
          <td style="text-align:left;">
            <select id="parent_id" name="parent_id">
              <?php foreach($parents as $p) {?>
                <option value="<?=$p['parent_id']?>"  <?php if($p['parent_id'] == $list['parent_id']) echo 'selected'; ?>>
                  <?=$p['parent_id']?> | <?=$p['email']?> | <?=$p['primary_en_name']?> | <?=$p['alter_en_name']?>
                </option>
              <?php } ?>
            </select>
        </tr>
        <tr>
          <td height="30"><span style="color:red;">*</span>manual records</td>
          <td style="text-align:left;"><input name="manual_records" type="number" id="manual_records" size="16" style="width:200px" value="<?=$list['manual_records']?>"/></td>
        </tr>

        <tr>
          <td height="30"><span style="color:red;">*</span>notes:</td>
          <td style="text-align:left;"><input name="notes" type="text" id="notes" size="16" style="width:200px" value="<?=$list['notes']?>" /></td>
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